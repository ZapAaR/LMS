<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberStoreRequest;
use App\Http\Requests\MemberUpdateRequest;
use App\Models\Member;
use App\services\MemberService;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    protected $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    // Menampilkan daftar anggota
    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;
        $sort = $request->sort ?? 'asc';
        $perPage = $request->per_page ?? 10;

        $members = $this->memberService->index(
            $search,
            $status,
            $sort,
            $perPage
        );

        return view('member.index', compact(
            'members',
            'search',
            'status',
            'sort',
            'perPage'
        ));
    }

    // Menambahkan anggota
    public function store(MemberStoreRequest $request)
    {
        $this->memberService->store($request->validated());

        return redirect()
            ->route('member.index')
            ->with('success', 'Anggota berhasil ditambahkan.');
    }

    // Menampilkan detail anggota
    public function show(Member $member)
    {
        $member = $this->memberService->show($member);

        return redirect()->route('member.index')
        ->with('success', 'Detail Member' . $member->name);
    }

    // Mengubah data anggota
    public function update(MemberUpdateRequest $request, Member $member)
    {
        $this->memberService->update(
            $member,
            $request->validated()
        );

        return redirect()
            ->route('member.index')
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    // Mengubah status anggota menggunakan PATCH
    public function updateStatus(Request $request, Member $member)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:active,inactive'],
        ]);

        $this->memberService->patch(
            $member,
            $validated['status']
        );

        return back()->with(
            'success',
            'Status anggota berhasil diperbarui.'
        );
    }

    // Menghapus satu anggota
    public function destroy(Member $member)
    {
        $result = $this->memberService->delete($member);

        if (!$result) {
            return back()->with(
                'error',
                'Anggota tidak dapat dihapus karena memiliki riwayat peminjaman.'
            );
        }

        return redirect()
            ->route('member.index')
            ->with('success', 'Anggota berhasil dihapus.');
    }

    // Bulk Delete
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'distinct', 'exists:members,id'],
        ]);

        $result = $this->memberService->deleteBulk(
            $validated['ids']
        );

        if (!$result) {
            return back()->with(
                'error',
                'Anggota tidak dapat dihapus karena ada yang memiliki riwayat peminjaman.'
            );
        }

        return redirect()
            ->route('member.index')
            ->with('success', 'Anggota berhasil dihapus.');
    }

    // Bulk Activate dan Bulk Deactivate
    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'distinct', 'exists:members,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $result = $this->memberService->bulkStatus(
            $validated['ids'],
            $validated['status']
        );

        if (!$result) {
            return back()->with(
                'error',
                'Gagal memperbarui status anggota.'
            );
        }

        return redirect()
            ->route('member.index')
            ->with('success', 'Status anggota berhasil diperbarui.');
    }

    // Cetak profil anggota berdasarkan ID
    public function printProfile(Member $member)
    {
        $member = $this->memberService->pdf($member);

        return view('member.pdf.profile', compact('member'));
    }
}
