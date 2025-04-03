<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Borrow extends Model
{
    use HasFactory;

    protected $table = 'borrows';

    protected $fillable = [
        'book_copy_id',
        'user_id',
        'borrowed_from',
        'due_date',
        'returned_at',
    ];

    protected $casts = [
        'borrowed_from' => 'datetime',
        'due_date' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function bookCopy(): BelongsTo
    {
        return $this->belongsTo(BookCopy::class, 'book_copy_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isCurrentlyBorrowed(): bool
    {
        return is_null($this->returned_at);
    }

    public function scopeCurrentlyBorrowed($query)
    {
        return $query->whereNull('returned_at');
    }
}