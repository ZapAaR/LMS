<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\BookReturn;
use App\Models\BorrowingReturn;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ReturnService
{
    protected $return;
    protected $borrowing;

    // Tarif denda per buku per hari
    protected int $finePerBook = 1000;

    public function __construct(
        BorrowingReturn $return,
        Borrowing $borrowing
    ) {
        $this->return = $return;
        $this->borrowing = $borrowing;
    }

    // MENGAMBIL SEMUA DATA PENGEMBALIAN
    public function getAllReturns(
        string $search = '',
        string $status = '',
        string $sort = 'desc',
        int $perPage = 10
    ) {
        $sort = in_array($sort, ['asc', 'desc']) ? $sort : 'desc';

        $perPage = in_array($perPage, [10, 25, 50])
            ? $perPage
            : 10;

        return $this->return
            ->with([
                'borrowing.member',
                'borrowing.processedBy',
                'borrowing.details.book',
                'processedBy',
            ])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('borrowing', function ($borrowing) use ($search) {
                    $borrowing->where('borrowing_code', 'like', "%{$search}%")
                        ->orWhereHas('member', function ($member) use ($search) {
                            $member->where('name', 'like', "%{$search}%")
                                ->orWhere('member_code', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status === 'late', function ($query) {
                $query->where('late_days', '>', 0);
            })
            ->when($status === 'on_time', function ($query) {
                $query->where('late_days', 0);
            })
            ->orderBy('return_date', $sort)
            ->orderBy('id', $sort)
            ->paginate($perPage)
            ->withQueryString();
    }

    // MENGAMBIL DETAIL PENGEMBALIAN
    public function getReturnDetail(BorrowingReturn $return)
    {
        return $return->load([
            'borrowing.member',
            'borrowing.processedBy',
            'borrowing.details.book',
            'processedBy',
        ]);
    }

    // MENGAMBIL PEMINJAMAN YANG BISA DIKEMBALIKAN
    public function getEligibleBorrowings(
        string $search = '',
        int $perPage = 10
    ) {
        return $this->borrowing
            ->with([
                'member',
                'details.book',
            ])
            ->whereIn('status', ['borrowed', 'overdue'])
            ->whereNotIn('id', function ($query) {
                $query->select('borrowing_id')
                    ->from('returns');
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('borrowing_code', 'like', "%{$search}%")
                        ->orWhereHas('member', function ($member) use ($search) {
                            $member->where('name', 'like', "%{$search}%")
                                ->orWhere('member_code', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('due_date', 'asc')
            ->paginate($perPage)
            ->withQueryString();
    }

    // MEMPROSES PENGEMBALIAN
    public function storeReturn(array $data, int $processedBy)
    {
        return DB::transaction(function () use ($data, $processedBy) {

            // 1. AMBIL DAN KUNCI TRANSAKSI PEMINJAMAN
            $borrowing = $this->borrowing
                ->where('id', $data['borrowing_id'])
                ->lockForUpdate()
                ->firstOrFail();

            // 2. PERIKSA STATUS PEMINJAMAN
            if (!in_array($borrowing->status, ['borrowed', 'overdue'], true)) {
                throw ValidationException::withMessages([
                    'borrowing_id' => 'Peminjaman ini tidak dapat dikembalikan.',
                ]);
            }

            // 3. PERIKSA PENGEMBALIAN GANDA
            if ($this->return->where('borrowing_id', $borrowing->id)->exists()) {
                throw ValidationException::withMessages([
                    'borrowing_id' => 'Peminjaman ini sudah dikembalikan.',
                ]);
            }

            // 4. AMBIL DETAIL BUKU
            $details = $borrowing->details()
                ->orderBy('book_id')
                ->get();

            if ($details->isEmpty()) {
                throw ValidationException::withMessages([
                    'borrowing_id' => 'Transaksi peminjaman tidak memiliki detail buku.',
                ]);
            }

            // 5. KUNCI SEMUA BUKU
            $bookIds = $details->pluck('book_id')->unique()->sort()->values();

            $books = Book::whereIn('id', $bookIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($books->count() !== $bookIds->count()) {
                throw ValidationException::withMessages([
                    'borrowing_id' => 'Ada buku dalam transaksi yang tidak ditemukan.',
                ]);
            }

            // 6. HITUNG KETERLAMBATAN
            $returnDate = Carbon::today();
            $dueDate = Carbon::parse($borrowing->due_date)->startOfDay();

            $lateDays = $returnDate->greaterThan($dueDate)
                ? (int) $dueDate->diffInDays($returnDate)
                : 0;

            // 7. HITUNG DENDA
            $totalQuantity = $details->sum('quantity');

            $fineAmount = $this->calculateFine(
                $lateDays,
                $totalQuantity
            );

            // 8. SIMPAN DATA PENGEMBALIAN
            $return = $this->return->create([
                'borrowing_id' => $borrowing->id,
                'processed_by' => $processedBy,
                'return_date' => $returnDate->toDateString(),
                'late_days' => $lateDays,
                'fine_amount' => $fineAmount,
                'notes' => $data['notes'] ?? null,
            ]);

            // 9. KEMBALIKAN STOK SEMUA BUKU
            foreach ($details as $detail) {
                $book = $books->get($detail->book_id);

                $book->increment('available_stock', $detail->quantity);
            }

            // 10. UPDATE STATUS PEMINJAMAN
            $borrowing->update([
                'status' => 'returned',
            ]);

            return $return->load([
                'borrowing.member',
                'borrowing.details.book',
                'processedBy',
            ]);
        });
    }

    // MENGHITUNG DENDA
    public function calculateFine(int $lateDays, int $totalQuantity): float
    {
        return $lateDays * $totalQuantity * $this->finePerBook;
    }


    public function getReturnHistory(
        string $search = '',
        string $status = '',
        string $startDate = '',
        string $endDate = '',
        string $sort = 'desc',
        int $perPage = 10
    ) {
        $query = $this->return->with([
            'borrowing.member',
            'borrowing.processedBy',
            'borrowing.details.book',
            'processedBy',
        ]);

        // SEARCH
        $query->when($search, function ($query) use ($search) {
            $query->whereHas('borrowing', function ($query) use ($search) {
                $query->where('borrowing_code', 'like', "%{$search}%")
                    ->orWhereHas('member', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('member_code', 'like', "%{$search}%");
                    });
            });
        });

        // FILTER STATUS
        $query->when($status === 'late', function ($query) {
            $query->where('late_days', '>', 0);
        });

        $query->when($status === 'on_time', function ($query) {
            $query->where('late_days', 0);
        });

        // FILTER TANGGAL
        $query->when($startDate, function ($query) use ($startDate) {
            $query->whereDate('return_date', '>=', $startDate);
        });

        $query->when($endDate, function ($query) use ($endDate) {
            $query->whereDate('return_date', '<=', $endDate);
        });

        // SORTING
        $sort = $sort === 'asc' ? 'asc' : 'desc';

        return $query
            ->orderBy('return_date', $sort)
            ->orderBy('id', $sort)
            ->paginate($perPage)
            ->withQueryString();
    }
}
