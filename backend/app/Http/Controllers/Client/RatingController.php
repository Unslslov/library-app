<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rating\StoreRequest;
use App\Http\Requests\Rating\UpdateRequest;
use App\Models\Book;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index()
    {
        $averageRating = Rating::avg('rating');

        return response()->json([
            'average_rating' => round($averageRating, 2)
        ]);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $book = Book::find($data['book_id']);
        if (!$book) {
            return response()->json(['error' => 'Книга не найдена'], 404);
        }

        try {
            $book->rateBy($data);
            return response()->json(['message' => 'Рейтинг успешно создан'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(UpdateRequest $request)
    {
        $data = $request->validated();

        $rating = Rating::update($data);

        return response()->noContent(204);
    }

    public function destroy(string $id)
    {
        Rating::destroy($id);

        return response()->noContent(204);
    }
}
