<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use  HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role() : BelongsTo
    {
        return $this->belongsTo(Role::class);
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

    public function ratings() : HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class);
    }

    private function checkUserRole(string $role) : bool
    {
        return $this->role->name === $role;
    }

    public function isAdmin(): bool
    {
        return $this->checkUserRole('admin');
    }

    public function isLibrarian(): bool
    {
        return $this->checkUserRole('librarian');
    }

    public function isClient(): bool
    {
        return $this->checkUserRole('client');
    }
}
