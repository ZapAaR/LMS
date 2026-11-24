<?php

namespace App\services;

use App\Models\Member;
use Illuminate\Support\Facades\DB;

class MemberService
{
    protected $member;

    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    // Search, filter, sorting, dan pagination
    public function index(
        $search,
        $status,
        $sort,
        $perPage=10
    ) {
        if (!in_array($sort, ['asc', 'desc'], true)) {
            $sort = 'asc';
        }

        return $this->member
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('member_code', 'like', $search . '%')
                        ->orWhere('name', 'like', $search . '%')
                        ->orWhere('email', 'like', $search . '%')
                        ->orWhere('phone', 'like', $search . '%');
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('name', $sort)
            ->paginate($perPage)
            ->withQueryString();
    }

    // Menambahkan anggota
    public function store(array $data)
    {
        return $this->member->create($data);
    }

    // Mengambil detail anggota
    public function show(Member $member)
    {
        return $member;
    }

    // Mengambil profil anggota untuk PDF
    public function pdf(Member $member)
    {
        return $member;
    }

    // Mengubah data anggota
    public function update(Member $member, array $data)
    {
        return $member->update($data);
    }

    // Mengubah status satu anggota (PATCH)
    public function patch(Member $member, string $status)
    {
        return $member->update([
            'status' => $status,
        ]);
    }

    // Menghapus satu anggota
    public function delete(Member $member)
    {
        if ($member->borrowings()->exists()) {
            return false;
        }

        return $member->delete();
    }

    // Bulk Delete
    public function deleteBulk(array $ids)
    {
        return DB::transaction(function () use ($ids) {

            $ids = array_unique($ids);

            if (empty($ids)) {
                return false;
            }

            $members = $this->member
                ->whereIn('id', $ids)
                ->lockForUpdate()
                ->get();

            if ($members->count() !== count($ids)) {
                return false;
            }

            // Periksa apakah ada anggota yang memiliki riwayat peminjaman
            $hasBorrowingHistory = $this->member
                ->whereIn('id', $ids)
                ->whereHas('borrowings')
                ->exists();

            if ($hasBorrowingHistory) {
                return false;
            }

            return $this->member
                ->whereIn('id', $ids)
                ->delete();
        });
    }

    // Bulk Activate dan Bulk Deactivate
    public function bulkStatus(array $ids, string $status)
    {
        if (!in_array($status, ['active', 'inactive'], true)) {
            return false;
        }

        return DB::transaction(function () use ($ids, $status) {

            $ids = array_unique($ids);

            if (empty($ids)) {
                return false;
            }

            $members = $this->member
                ->whereIn('id', $ids)
                ->lockForUpdate()
                ->get();

            if ($members->count() !== count($ids)) {
                return false;
            }

            return $this->member
                ->whereIn('id', $ids)
                ->update([
                    'status' => $status,
                ]);
        });
    }
}
