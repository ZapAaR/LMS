<?php

namespace App\Http\Controllers;

use App\Services\BorrowingService;
use App\Services\ReturnService;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    protected $borrowingService;
    protected $returnService;

    public function __construct(
        BorrowingService $borrowingService,
        ReturnService $returnService
    ) {
        $this->borrowingService = $borrowingService;
        $this->returnService = $returnService;
    }

    // HALAMAN RIWAYAT TRANSAKSI
    public function index(Request $request)
    {
        $type = $request->type ?? 'borrowing';

        $search = $request->search ?? '';
        $status = $request->status ?? '';
        $startDate = $request->start_date ?? '';
        $endDate = $request->end_date ?? '';
        $sort = $request->sort ?? 'desc';
        $perPage = (int) ($request->per_page ?? 10);

        // VALIDASI JENIS TRANSAKSI
        if (!in_array($type, ['borrowing', 'return'], true)) {
            $type = 'borrowing';
        }

        // RIWAYAT PEMINJAMAN
        if ($type === 'borrowing') {
            $transactions = $this->borrowingService->getBorrowingHistory(
                $search,
                $status,
                $startDate,
                $endDate,
                $sort,
                $perPage
            );
        }

        // RIWAYAT PENGEMBALIAN
        else {
            $transactions = $this->returnService->getReturnHistory(
                $search,
                $status,
                $startDate,
                $endDate,
                $sort,
                $perPage
            );
        }

        return view('history.index', compact(
            'transactions',
            'type',
            'search',
            'status',
            'startDate',
            'endDate',
            'sort',
            'perPage'
        ));
    }
}
