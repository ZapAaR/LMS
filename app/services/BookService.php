<?php

namespace App\services;

use App\Models\Book;
use Illuminate\Support\Facades\DB;

class BookService
{
    protected $book;

    // Batas stok menipis
    protected $lowStockLimit = 3;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    public function index(
        $search,
        $categoryId,
        $sort,
        $stockStatus
    ) {
        if (!in_array($sort, ['asc', 'desc'], true)) {
            $sort = 'asc';
        }

        return $this->book->with('category')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', $search . '%')
                        ->orWhere('book_code', 'like', $search . '%')
                        ->orWhere('author', 'like', $search . '%')
                        ->orWhere('isbn', 'like', $search . '%');
                });
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($stockStatus === 'available', function ($query) {
                $query->where('available_stock', '>', 0);
            })
            ->when($stockStatus === 'empty', function ($query) {
                $query->where('available_stock', 0);
            })
            ->when($stockStatus === 'low', function ($query) {
                $query->where('available_stock', '<=', $this->lowStockLimit)
                    ->where('available_stock', '>', 0);
            })
            ->orderBy('title', $sort)
            ->paginate(10)
            ->withQueryString();
    }

    public function store(array $data)
    {
        $data['available_stock'] = $data['stock'];

        return $this->book->create($data);
    }

    public function show(Book $book)
    {
        return $book->load('category');
    }

    public function update(Book $book, array $data)
    {
        return DB::transaction(function () use ($book, $data) {

            $book = $this->book
                ->lockForUpdate()
                ->findOrFail($book->id);

            // Menghitung jumlah buku yang sedang dipinjam
            $borrowedStock = $book->stock - $book->available_stock;

            // Stok baru tidak boleh lebih kecil dari jumlah yang dipinjam
            if ($data['stock'] < $borrowedStock) {
                return false;
            }

            // Menghitung stok tersedia setelah perubahan
            $data['available_stock'] = $data['stock'] - $borrowedStock;

            return $book->update($data);
        });
    }

    // Menghapus banyak buku sekaligus
    public function bulkDeleteBooks(array $ids)
    {
        return DB::transaction(function () use ($ids) {

            $ids = array_unique($ids);

            $books = $this->book
                ->whereIn('id', $ids)
                ->lockForUpdate()
                ->get();

            // Pastikan semua ID buku ditemukan
            if ($books->count() !== count($ids)) {
                return false;
            }

            // Periksa apakah buku memiliki riwayat peminjaman
            $hasBorrowingHistory = $this->book
                ->whereIn('id', $ids)
                ->whereHas('borrowingDetails')
                ->exists();

            if ($hasBorrowingHistory) {
                return false;
            }

            // Hapus semua buku yang dipilih
            return $this->book->whereIn('id', $ids)->delete();
        });
    }

    public function deleteBook(Book $book)
    {
        if ($book->borrowingDetails()->exists()) {
            return false;
        }

        return $book->delete();
    }
}
