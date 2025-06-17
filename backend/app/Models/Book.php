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

    public function loanTo(array $data): bool
    {
        return DB::transaction(function () use ($data) {
            $book = $this;

            if ($book->available_copies <= 0) {
                throw new \Exception('Копии данной книги закончились');
            }

            $reservation = Reservation::where('book_id', $data['book_id'])
                ->where('user_id', $data['user_id'])
                ->where('status', 'active')
                ->first();

            if (!$reservation) {
                throw new \Exception('Пользователь не зарезервировал данную книгу');
            }

            $existingLoan = Loan::where('book_id', $data['book_id'])
                ->where('user_id', $data['user_id'])
                ->whereNull('returned_at')
                ->first();

            if ($existingLoan) {
                throw new \Exception('Данная книга уже выдана этому пользователю');
            }

            $data['loaned_at'] = now();
            $data['reservation_id'] = $reservation->id;

            Loan::create($data);

            $book->decrement('available_copies');

            return true;
        });
    }

    public function returnFrom(Loan $loan): bool
    {
        return DB::transaction(function () use ($loan) {

            $loan->update(['returned_at' => now()]);

            $this->increment('available_copies');

            if ($loan->reservation) {
                $loan->reservation->update(['status' => 'returned']);
                $loan->reservation->delete();
            }

            $loan->delete();

            return true;
        });
    }
    public function reserveFor(array $data): bool
    {
        return DB::transaction(function () use ($data) {

            $existingReservation = Reservation::where('book_id', $this->id)
                ->where('user_id', $data['user_id'])
                ->where('status', 'active')
                ->first();

            if ($existingReservation) {
                throw new \Exception('данная книга уже забронирована этим пользователем');
            }

            $data['book_id'] = $this->id;
            $data['status'] = 'active';
            $data['expires_at'] = Carbon::now()->addDays(2);

            Reservation::create($data);

            return true;
        });
    }
    public function rateBy(array $data): bool
    {
        return DB::transaction(function () use ($data) {

            $existingRating = Rating::where('user_id', $data['user_id'])
                ->where('book_id', $this->id)
                ->first();

            if ($existingRating) {
                throw new \Exception('Вы уже оценивали эту книгу');
            }

            Rating::create($data);

            return true;
        });
    }
}
