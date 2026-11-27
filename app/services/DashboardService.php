<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Member;
use App\Models\Borrowing;
use App\Models\BorrowingReturn;

class DashboardService
{
    public function getDashboardData(): array
    {
        // STATISTIK
        $totalBooks = Book::count();

        $totalMembers = Member::count();

        $activeBorrowings = Borrowing::whereIn('status', [
            'borrowed',
            'overdue',
        ])->count();

        $totalReturns = BorrowingReturn::count();

        // TRANSAKSI PEMINJAMAN TERBARU
        $borrowings = Borrowing::with([
            'member',
            'details.book',
        ])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($borrowing) {
                return [
                    'member' => $borrowing->member->name,
                    'books' => $borrowing->details
                        ->pluck('book.title')
                        ->implode(', '),
                    'date' => $borrowing->borrow_date,
                    'created_at' => $borrowing->created_at,
                    'status' => $borrowing->status,
                    'type' => 'borrowing',
                ];
            });

        // TRANSAKSI PENGEMBALIAN TERBARU
        $returns = BorrowingReturn::with([
            'borrowing.member',
            'borrowing.details.book',
        ])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($return) {
                return [
                    'member' => $return->borrowing->member->name,
                    'books' => $return->borrowing->details
                        ->pluck('book.title')
                        ->implode(', '),
                    'date' => $return->return_date,
                    'created_at' => $return->created_at,
                    'status' => $return->late_days > 0
                        ? 'late'
                        : 'returned',
                    'type' => 'return',
                ];
            });

        // GABUNGKAN DAN AMBIL 5 TRANSAKSI TERBARU
        $latestTransactions = $borrowings
            ->concat($returns)
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        return [
            'totalBooks' => $totalBooks,
            'totalMembers' => $totalMembers,
            'activeBorrowings' => $activeBorrowings,
            'totalReturns' => $totalReturns,
            'latestTransactions' => $latestTransactions,
        ];
    }
}
