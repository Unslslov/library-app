<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookWaitlist extends Model
{
    /** @use HasFactory<\Database\Factories\BookWaitlistFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book() : BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
