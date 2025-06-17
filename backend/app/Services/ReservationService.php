<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Reservation;
use Illuminate\Support\Carbon;

class ReservationService
{
    public function createReservation(array $data): array
    {
        $existingReservation = Reservation::where('book_id', $data['book_id'])
            ->where('user_id', $data['user_id'])
            ->where('status', 'active')
            ->first();

        if ($existingReservation) {
            return ['error' => 'Данная книга уже забронирована этим пользователем', 'code' => 400];
        }

        $reservation = Reservation::create([
            'book_id' => $data['book_id'],
            'user_id' => $data['user_id'],
            'status' => 'active',
            'expires_at' => Carbon::now()->addDays(2)
        ]);

        return ['message' => 'Книга успешно забронирована', 'code' => 201, 'reservation' => $reservation];
    }

    public function cancelReservation(int $id): void
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => 'canceled']);
        $reservation->delete();
    }

    public function getUserReservations(int $userId)
    {
        return \App\Models\User::find($userId)
            ->reservations()
            ->with(['book', 'user'])
            ->get();
    }
}
