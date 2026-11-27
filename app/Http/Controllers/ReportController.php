<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'type' => ['nullable', Rule::in([
                'borrowing',
                'return',
                'overdue',
            ])],
            'search' => ['nullable', 'string', 'max:100'],
            'start_date' => ['nullable', 'date'],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
            'sort' => ['nullable', Rule::in(['asc', 'desc'])],
            'per_page' => ['nullable', Rule::in([10, 25, 50])],
        ]);

        $type = $validated['type'] ?? 'borrowing';
        $search = $validated['search'] ?? '';
        $startDate = $validated['start_date'] ?? '';
        $endDate = $validated['end_date'] ?? '';
        $sort = $validated['sort'] ?? 'desc';
        $perPage = (int) ($validated['per_page'] ?? 10);

        // LAPORAN PEMINJAMAN
        if ($type === 'borrowing') {
            $transactions = $this->reportService->getBorrowingReport(
                $search,
                $startDate,
                $endDate,
                $sort,
                $perPage
            );
        }

        // LAPORAN PENGEMBALIAN
        elseif ($type === 'return') {
            $transactions = $this->reportService->getReturnReport(
                $search,
                $startDate,
                $endDate,
                $sort,
                $perPage
            );
        }

        // LAPORAN KETERLAMBATAN
        else {
            $transactions = $this->reportService->getOverdueReport(
                $search,
                $startDate,
                $endDate,
                $sort,
                $perPage
            );
        }

        return view('reports.index', compact(
            'transactions',
            'type',
            'search',
            'startDate',
            'endDate',
            'sort',
            'perPage'
        ));
    }
}
