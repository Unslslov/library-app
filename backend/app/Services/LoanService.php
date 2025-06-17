<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Reservation;
use Illuminate\Support\Carbon;

class LoanService
{
    public function loanBook(array $data): array
    {
        $book = Book::find($data['book_id']);

        if (!$book) {
            return ['error' => 'Книга не найдена', 'code' => 404];
        }

        if ($book->available_copies <= 0) {
            return ['error' => 'Копии данной книги закончились', 'code' => 400];
        }

        $reservation = Reservation::where('book_id', $data['book_id'])
            ->where('user_id', $data['user_id'])
            ->where('status', 'active')
            ->first();

        if (!$reservation) {
            return ['error' => 'Пользователь не зарезервировал данную книгу', 'code' => 400];
        }

        $existingLoan = Loan::where('book_id', $data['book_id'])
            ->where('user_id', $data['user_id'])
            ->whereNull('returned_at')
            ->first();

        if ($existingLoan) {
            return ['error' => 'Данная книга уже выдана этому пользователю', 'code' => 400];
        }

        $data['loaned_at'] = Carbon::now();
        $data['reservation_id'] = $reservation->id;

        Loan::create($data);
        $book->decrement('available_copies');

        return ['message' => 'Клиент получил книгу', 'code' => 201];
    }

    public function returnBook(int $loanId): void
    {
        $loan = Loan::findOrFail($loanId);
        $loan->update(['returned_at' => Carbon::now()]);

        $book = Book::find($loan->book_id);
        $book->increment('available_copies');

        $reservation = Reservation::find($loan->reservation_id);
        if ($reservation) {
            $reservation->update(['status' => 'returned']);
            $reservation->delete();
        }

        $loan->delete();
    }
}
