<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;
use Illuminate\Support\Str;

class Member extends Model
{
    protected $fillable = [
        'member_code',
        'name',
        'email',
        'phone',
        'address',
        'status',
    ];

    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($member) {
            $member->uuid = (string) Str::uuid();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
