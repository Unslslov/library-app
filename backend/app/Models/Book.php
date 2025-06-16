<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
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

    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class, 'loans', 'book_id', 'user_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function bookWaitlists()
    {
        return $this->hasMany(BookWaitlist::class);
    }

    public function waitlistUsers()
    {
        return $this->hasManyThrough(User::class, BookWaitlist::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
