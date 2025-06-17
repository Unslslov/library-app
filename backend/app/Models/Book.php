<?php

namespace App\Models;

use App\Jobs\SendAvailableBookJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'genre',
        'publisher',
        'available_copies',
        'year_published',
        'total_copies',
        'description',
        'image_url',
        ];

    protected $casts = [
        'year_published' => 'datetime',
    ];

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'loans', 'book_id', 'user_id');
    }

    public function reservations() : HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function loans() : HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function bookWaitlists() : HasMany
    {
        return $this->hasMany(BookWaitlist::class);
    }

    public function waitlistUsers() : HasManyThrough
    {
        return $this->hasManyThrough(User::class, BookWaitlist::class);
    }

    public function ratings() : HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class);
    }

    protected static function booted()
    {
        static::updated(function ($book) {
            if ($book->isDirty('available_copies')) {
                Bus::dispatch(new SendAvailableBookJob($book));
            }
        });
    }
}
