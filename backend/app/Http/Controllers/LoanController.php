<?php

namespace App\Http\Controllers;

use App\Http\Requests\Book\LoanBookRequest;
use App\Http\Requests\Book\TakeBookBackRequest;
use App\Http\Resources\LoanResource;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['book', 'user'])->get();

//        return response()->json($loans);
        return LoanResource::collection($loans);
    }

    public function store(LoanBookRequest $request)
    {
        $data = $request->validated();

        $book = Book::find($data['book_id']);

        if (!isset($book)) {
            return response()->json(['error' => 'Книга не найдена'], 404);
        }

        if ($book->available_copies <= 0) {
            return response()->json(['error' => 'Копии данной книги закончились'], 400);
        }

        $reservation = Reservation::where('book_id', $data['book_id'])
            ->where('user_id', $data['user_id'])
            ->where('status', 'active')
            ->first();

        if(!$reservation) {
            return response()->json(['error' => 'пользователь не зарезервировал данную книгу'], 400);
        }

        $existingLoan = Loan::where('book_id', $data['book_id'])
            ->where('user_id', $data['user_id'])
            ->whereNull('returned_at')
            ->first();

        if($existingLoan) {
            return response()->json(['error' => 'данная книга уже выдана этому пользователю'], 400);
        }

        if($reservation->book->available_copies <= 0)
        {
            return response()->json(['error' => 'Копии данной книги закончились'], 400);
        }


        $data['loaned_at'] = Carbon::now();
        $data['reservation_id'] = $reservation->id;

        Loan::create($data);
//        return response()->json($existingLoan);

        $book->decrement('available_copies');

        return response()->json(['Клиент получил книгу' => 'успех'], 201);
    }

    public function delete(string $id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update(['returned_at' => Carbon::now()]);

        $book = Book::find($loan->book_id);
        $book->increment('available_copies');

        $reservation = Reservation::find($loan->reservation->id);

        if ($reservation) {
            $reservation->update(['status' => 'returned']);
        }

        $reservation->delete();
        $loan->delete();

        return response()->noContent(204);
    }
}
