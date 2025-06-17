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

        return LoanResource::collection($loans);
    }

    public function store(LoanBookRequest $request)
    {
        $data = $request->validated();

        $book = Book::find($data['book_id']);
        if (!$book) {
            return response()->json(['error' => 'Книга не найдена'], 404);
        }

        try {
            $book->loanTo($data);
            return response()->json(['message' => 'Клиент получил книгу'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(string $id)
    {
        $loan = Loan::with('book')->findOrFail($id);
        $book = $loan->book;

        try {
            $book->returnFrom($loan);
            return response()->noContent(204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
