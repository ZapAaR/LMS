<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class BorrowingService
{
    protected $borrowing;

    public function __construct(Borrowing $borrowing)
    {
        $this->borrowing = $borrowing;
    }


    // MENGAMBIL SEMUA DATA PEMINJAMAN
    public function getAllBorrowings(
        string $search = '',
        string $status = '',
        string $sort = 'desc',
        int $perPage = 10
    ) {
        // Perbarui status peminjaman yang sudah melewati jatuh tempo
        $this->updateOverdueStatuses();

        // Batasi pilihan sorting
        $sort = in_array($sort, ['asc', 'desc']) ? $sort : 'desc';

        // Batasi jumlah data per halaman
        $perPage = in_array($perPage, [10, 25, 50]) ? $perPage : 10;

        return $this->borrowing
            ->with([
                'member',
                'processedBy',
                'returnRecord',
                'details.book',
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('borrowing_code', 'like', "%{$search}%")
                        ->orWhereHas('member', function ($member) use ($search) {
                            $member->where('name', 'like', "%{$search}%")
                                ->orWhere('member_code', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('borrow_date', $sort)
            ->orderBy('id', $sort)
            ->paginate($perPage)
            ->withQueryString();
    }


    // MENGAMBIL DETAIL PEMINJAMAN
    public function getBorrowingDetail(Borrowing $borrowing)
    {
        $this->updateOverdueStatuses();

        return $borrowing->refresh()->load([
            'member',
            'processedBy',
            'returnRecord',
            'details.book',
        ]);
    }

    // MENCATAT PENGEMBALIAN BUKU
    public function markAsReturned(Borrowing $borrowing, int $processedBy): Borrowing
    {
        return DB::transaction(function () use ($borrowing, $processedBy) {
            $lockedBorrowing = Borrowing::whereKey($borrowing->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array($lockedBorrowing->status, ['borrowed', 'overdue'], true)) {
                throw ValidationException::withMessages([
                    'status' => 'Peminjaman ini sudah dikembalikan atau tidak dapat diproses.',
                ]);
            }

            $details = $lockedBorrowing->details()
                ->orderBy('book_id')
                ->get();

            if ($details->isEmpty()) {
                throw ValidationException::withMessages([
                    'status' => 'Peminjaman ini tidak memiliki buku untuk dikembalikan.',
                ]);
            }

            $books = Book::whereIn('id', $details->pluck('book_id'))
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($books->count() !== $details->count()) {
                throw ValidationException::withMessages([
                    'status' => 'Buku pada peminjaman tidak ditemukan.',
                ]);
            }

            foreach ($details as $detail) {
                $books->get($detail->book_id)->increment('available_stock', $detail->quantity);
            }

            $returnDate = Carbon::today();
            $lateDays = max(
                0,
                (int) Carbon::parse($lockedBorrowing->due_date)
                    ->startOfDay()
                    ->diffInDays($returnDate, false)
            );

            $lockedBorrowing->returnRecord()->create([
                'processed_by' => $processedBy,
                'return_date' => $returnDate->toDateString(),
                'late_days' => $lateDays,
                'fine_amount' => $lateDays * 2000,
            ]);
            $lockedBorrowing->update(['status' => 'returned']);

            return $lockedBorrowing->load([
                'member',
                'processedBy',
                'returnRecord',
                'details.book',
            ]);
        });
    }

    public function deleteBorrowing(Borrowing $borrowing): void
    {
        DB::transaction(function () use ($borrowing) {
            $lockedBorrowing = Borrowing::whereKey($borrowing->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if (in_array($lockedBorrowing->status, ['borrowed', 'overdue'], true)) {
                $details = $lockedBorrowing->details()
                    ->orderBy('book_id')
                    ->get();

                $bookIds = $details->pluck('book_id')->unique()->sort()->values();
                $books = Book::whereIn('id', $bookIds)
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                if ($books->count() !== $bookIds->count()) {
                    throw ValidationException::withMessages([
                        'borrowing' => 'Buku pada peminjaman tidak ditemukan.',
                    ]);
                }

                foreach ($details->groupBy('book_id') as $bookId => $bookDetails) {
                    $books->get($bookId)->increment(
                        'available_stock',
                        $bookDetails->sum('quantity')
                    );
                }
            }

            $lockedBorrowing->delete();
        });
    }


    // MEMPERBARUI STATUS PEMINJAMAN TERLAMBAT
    public function updateOverdueStatuses(): void
    {
        $this->borrowing
            ->where('status', 'borrowed')
            ->whereDate('due_date', '<', today())
            ->update([
                'status' => 'overdue',
            ]);
    }


    // MENAMBAH PEMINJAMAN
    public function storeBorrowing(array $data, int $processedBy)
    {
        return DB::transaction(function () use ($data, $processedBy) {

            // 1. PERIKSA STATUS ANGGOTA
            $member = Member::where('id', $data['member_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($member->status !== 'active') {
                throw ValidationException::withMessages([
                    'member_id' => 'Anggota tidak aktif dan tidak dapat meminjam buku.',
                ]);
            }

            // 2. PERIKSA BATAS MAKSIMAL PEMINJAMAN
            $totalQuantity = collect($data['books'])
                ->sum('quantity');

            if ($totalQuantity > 3) {
                throw ValidationException::withMessages([
                    'books' => 'Maksimal peminjaman adalah 3 buku.',
                ]);
            }

            // 3. MENENTUKAN TANGGAL JATUH TEMPO
            $borrowDate = Carbon::parse($data['borrow_date']);

            $dueDate = $borrowDate->copy()->addDays(7);

            // 4. AMBIL DAN KUNCI STOK BUKU
            $bookIds = collect($data['books'])
                ->pluck('book_id')
                ->unique()
                ->sort()
                ->values();

            $books = Book::whereIn('id', $bookIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($books->count() !== $bookIds->count()) {
                throw ValidationException::withMessages([
                    'books' => 'Ada buku yang tidak ditemukan.',
                ]);
            }

            // 5. PERIKSA KETERSEDIAAN STOK
            foreach ($data['books'] as $item) {

                $book = $books->get($item['book_id']);

                if ($book->available_stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'books' => 'Stok buku ' . $book->title . ' tidak mencukupi.',
                    ]);
                }
            }

            // 6. SIMPAN TRANSAKSI PEMINJAMAN
            $borrowing = $this->borrowing->create([
                'borrowing_code' => $this->generateBorrowingCode(),
                'member_id' => $member->id,
                'processed_by' => $processedBy,
                'borrow_date' => $borrowDate->toDateString(),
                'due_date' => $dueDate->toDateString(),
                'status' => 'borrowed',
                'notes' => $data['notes'] ?? null,
            ]);

            // 7. SIMPAN DETAIL DAN KURANGI STOK
            foreach ($data['books'] as $item) {

                $book = $books->get($item['book_id']);

                $borrowing->details()->create([
                    'book_id' => $book->id,
                    'quantity' => $item['quantity'],
                ]);

                $book->decrement(
                    'available_stock',
                    $item['quantity']
                );
            }

            return $borrowing->load([
                'member',
                'processedBy',
                'details.book',
            ]);
        });
    }

    // GENERATE KODE PEMINJAMAN
    protected function generateBorrowingCode(): string
    {
        do {
            $code = 'PJM-' . now()->format('Ymd')
                . '-' . Str::upper(Str::random(6));
        } while (
            $this->borrowing
            ->withTrashed()
            ->where('borrowing_code', $code)
            ->exists()
        );

        return $code;
    }
}
