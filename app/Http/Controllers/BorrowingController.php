<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorrowingStoreRequest;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use App\Services\BorrowingService;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    protected $borrowingService;

    public function __construct(BorrowingService $borrowingService)
    {
        $this->borrowingService = $borrowingService;
    }

    // DAFTAR PEMINJAMAN
    public function index(Request $request)
    {
        $search = $request->search ?? '';
        $status = $request->status ?? '';
        $sort = $request->sort ?? 'desc';
        $perPage = $request->per_page ?? 10;

        $borrowings = $this->borrowingService->getAllBorrowings(
            $search,
            $status,
            $sort,
            $perPage
        );

        $members = Member::where('status', 'active')
            ->orderBy('name')
            ->get();

        $books = Book::where('available_stock', '>', 0)
            ->orderBy('title')
            ->get();

        return view('borrowing.index', compact(
            'borrowings',
            'members',
            'books',
            'search',
            'status',
            'sort',
            'perPage'
        ));
    }

    // TAMBAH PEMINJAMAN
    public function store(BorrowingStoreRequest $request)
    {
        $this->borrowingService->storeBorrowing(
            $request->validated(),
            $request->user()->id
        );

        return redirect()
            ->route('borrowing.index')
            ->with('success', 'Peminjaman berhasil ditambahkan.');
    }

    // DETAIL PEMINJAMAN
    public function show(Borrowing $borrowing)
    {
        $borrowing = $this->borrowingService->getBorrowingDetail($borrowing);

        return view('borrowing.index', compact('borrowing'));
    }

    public function destroy(Borrowing $borrowing)
    {
        $this->borrowingService->deleteBorrowing($borrowing);

        return redirect()
            ->route('borrowing.index')
            ->with('success', 'Peminjaman berhasil dihapus.');
    }

    // MENANDAI PEMINJAMAN SEBAGAI DIKEMBALIKAN
    public function updateStatus(Request $request, Borrowing $borrowing)
    {
        $request->validate([
            'status' => ['required', 'in:returned'],
        ]);

        $this->borrowingService->markAsReturned(
            $borrowing,
            $request->user()->id
        );

        return back()->with('success', 'Peminjaman berhasil ditandai sebagai dikembalikan.');
    }
}
