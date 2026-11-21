<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowingReturn extends Model
{
    protected $table = 'returns';

    protected $fillable = [
        'borrowing_id',
        'processed_by',
        'return_date',
        'late_days',
        'fine_amount',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'return_date' => 'date',
            'late_days' => 'integer',
            'fine_amount' => 'decimal:2',
        ];
    }

    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
