<?php

namespace App\Models;

use App\Jobs\ExpireReservationsJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Bus;

class Reservation extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'book_id',
        'expires_at',
        'status',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book() : BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function loan() : HasOne
    {
        return $this->hasOne(Loan::class);
    }

    protected static function booted()
    {
        static::created(function ($reservation) {
            Bus::dispatch(
                (new ExpireReservationsJob($reservation))->onQueue('default')->delay(now()->addDays(2))
            );
        });
    }
}
