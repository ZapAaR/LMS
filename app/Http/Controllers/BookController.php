<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookStoreRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Models\Book;
use App\Models\Category;
use App\services\BookService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    // Menampilkan daftar buku
    public function index(Request $request)
    {
        $search = $request->search;
        $categoryId = $request->category_id;
        $sort = $request->sort ?? 'asc';
        $stockStatus = $request->stock_status;

        $books = $this->bookService->index(
            $search,
            $categoryId,
            $sort,
            $stockStatus
        );

        $categories = Category::orderBy('name')->get();

        return view('book.index', compact(
            'books',
            'categories',
            'search',
            'categoryId',
            'sort',
            'stockStatus'
        ));
    }

    // Menambahkan buku
    public function store(BookStoreRequest $request)
    {
        $this->bookService->store($request->validated());

        return redirect()
            ->route('book.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    // Menampilkan detail buku
    public function show(Book $book)
    {
        $book = $this->bookService->show($book);

        return redirect()->route('book.index')
            ->with('info', 'Detail buku ' . $book->title . ' tersedia pada daftar buku.');
    }

    // Mengubah buku
    public function update(BookUpdateRequest $request, Book $book)
    {
        $result = $this->bookService->update(
            $book,
            $request->validated()
        );

        if (!$result) {
            return back()->with(
                'error',
                'Stok tidak boleh lebih kecil dari jumlah buku yang sedang dipinjam.'
            );
        }

        return redirect()
            ->route('book.index')
            ->with('success', 'Buku berhasil diperbarui.');
    }

    // Menghapus satu buku
    public function destroy(Book $book)
    {
        $result = $this->bookService->deleteBook($book);

        if (!$result) {
            return back()->with(
                'error',
                'Buku tidak dapat dihapus karena memiliki riwayat peminjaman.'
            );
        }

        return redirect()
            ->route('book.index')
            ->with('success', 'Buku berhasil dihapus.');
    }

    // Menghapus banyak buku
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'distinct', 'exists:books,id'],
        ]);

        $result = $this->bookService->bulkDeleteBooks($validated['ids']);

        if (!$result) {
            return back()->with(
                'error',
                'Sebagian buku tidak dapat dihapus karena memiliki riwayat peminjaman.'
            );
        }

        return redirect()
            ->route('book.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}
