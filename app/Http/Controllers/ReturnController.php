<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReturnStoreRequest;
use App\Models\BookReturn;
use App\Services\ReturnService;
use App\Http\Requests\StoreReturnRequest;
use App\Models\BorrowingReturn;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    protected $returnService;

    public function __construct(ReturnService $returnService)
    {
        $this->returnService = $returnService;
    }

    // DAFTAR PENGEMBALIAN
    public function index(Request $request)
    {
        $search = $request->search ?? '';
        $status = $request->status ?? '';
        $sort = $request->sort ?? 'desc';
        $perPage = $request->per_page ?? 10;

        $returns = $this->returnService->getAllReturns(
            $search,
            $status,
            $sort,
            $perPage
        );

        $borrowings = $this->returnService->getEligibleBorrowings();

        return view('return.index', compact(
            'returns',
            'borrowings',
            'search',
            'status',
            'sort',
            'perPage'
        ));
    }

    // PROSES PENGEMBALIAN
    public function store(ReturnStoreRequest $request)
    {
        $this->returnService->storeReturn(
            $request->validated(),
            $request->user()->id
        );

        return redirect()
            ->route('return.index')
            ->with('success', 'Pengembalian buku berhasil diproses.');
    }

    // DETAIL PENGEMBALIAN
    public function show(BorrowingReturn $return)
    {
        $return = $this->returnService->getReturnDetail($return);

        return view('return.index', compact('return'));
    }
}
