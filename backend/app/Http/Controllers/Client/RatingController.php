<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rating\StoreRequest;
use App\Http\Requests\Rating\UpdateRequest;
use App\Models\Rating;
use http\Client\Curl\User;
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

        $existingRating = Rating::where('user_id', $data['user_id'])
            ->where('book_id', $data['book_id'])
            ->first();

        if ($existingRating) {
            return response()->json([
                'error' => 'Вы уже оценивали эту книгу'
            ], 400);
        }

        $rating = Rating::create($data);

        return response()->json(['message' => 'рейтинг успешно создан'],201);
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
