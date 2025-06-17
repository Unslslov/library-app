<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Loan extends Model
{
    /** @use HasFactory<\Database\Factories\LoanFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'book_id',
        'loaned_at',
        'returned_at',
        'reservation_id'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book() : BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function reservation() : BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}
