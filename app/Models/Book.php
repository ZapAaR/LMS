<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'category_id',
        'book_code',
        'title',
        'author',
        'publisher',
        'publication_year',
        'isbn',
        'stock',
        'available_stock',
    ];

    protected function casts(): array
    {
        return [
            'publication_year' => 'integer',
            'stock' => 'integer',
            'available_stock' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowingDetails(): HasMany
    {
        return $this->hasMany(BorrowingDetail::class);
    }

    public function borrowings(): BelongsToMany
    {
        return $this->belongsToMany(Borrowing::class, 'borrowing_details')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
