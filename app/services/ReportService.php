<?php

namespace App\Services;

use App\Models\Borrowing;
use App\Models\BorrowingReturn;

class ReportService
{
    // LAPORAN PEMINJAMAN
    public function getBorrowingReport(
        string $search = '',
        string $startDate = '',
        string $endDate = '',
        string $sort = 'desc',
        int $perPage = 10
    ) {
        $query = Borrowing::with([
            'member',
            'processedBy',
            'details.book',
        ]);

        // SEARCH
        $query->when($search, function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('borrowing_code', 'like', "%{$search}%")
                    ->orWhereHas('member', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('member_code', 'like', "%{$search}%");
                    });
            });
        });

        // FILTER TANGGAL PEMINJAMAN
        $query->when($startDate, function ($query) use ($startDate) {
            $query->whereDate('borrow_date', '>=', $startDate);
        });

        $query->when($endDate, function ($query) use ($endDate) {
            $query->whereDate('borrow_date', '<=', $endDate);
        });

        $sort = $sort === 'asc' ? 'asc' : 'desc';

        return $query
            ->orderBy('borrow_date', $sort)
            ->orderBy('id', $sort)
            ->paginate($perPage)
            ->withQueryString();
    }


    // LAPORAN PENGEMBALIAN
    public function getReturnReport(
        string $search = '',
        string $startDate = '',
        string $endDate = '',
        string $sort = 'desc',
        int $perPage = 10
    ) {
        $query = BorrowingReturn::with([
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

        // FILTER TANGGAL PENGEMBALIAN
        $query->when($startDate, function ($query) use ($startDate) {
            $query->whereDate('return_date', '>=', $startDate);
        });

        $query->when($endDate, function ($query) use ($endDate) {
            $query->whereDate('return_date', '<=', $endDate);
        });

        $sort = $sort === 'asc' ? 'asc' : 'desc';

        return $query
            ->orderBy('return_date', $sort)
            ->orderBy('id', $sort)
            ->paginate($perPage)
            ->withQueryString();
    }


    // LAPORAN KETERLAMBATAN
    public function getOverdueReport(
        string $search = '',
        string $startDate = '',
        string $endDate = '',
        string $sort = 'asc',
        int $perPage = 10
    ) {
        $query = Borrowing::with([
            'member',
            'processedBy',
            'details.book',
        ])
            ->whereIn('status', ['borrowed', 'overdue'])
            ->whereDate('due_date', '<', today())
            ->whereNotIn('id', function ($subQuery) {
                $subQuery->select('borrowing_id')
                    ->from('returns');
            });

        // SEARCH
        $query->when($search, function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('borrowing_code', 'like', "%{$search}%")
                    ->orWhereHas('member', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('member_code', 'like', "%{$search}%");
                    });
            });
        });

        // FILTER BERDASARKAN TANGGAL JATUH TEMPO
        $query->when($startDate, function ($query) use ($startDate) {
            $query->whereDate('due_date', '>=', $startDate);
        });

        $query->when($endDate, function ($query) use ($endDate) {
            $query->whereDate('due_date', '<=', $endDate);
        });

        $sort = $sort === 'desc' ? 'desc' : 'asc';

        return $query
            ->orderBy('due_date', $sort)
            ->orderBy('id', $sort)
            ->paginate($perPage)
            ->withQueryString();
    }
}
