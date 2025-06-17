<?php

namespace App\Services;

use App\Models\Rating;

class RatingService
{
    public function getAverageRating(): float
    {
        return round(Rating::avg('rating'), 2);
    }

    public function createRating(array $data): array
    {
        $existingRating = Rating::where('user_id', $data['user_id'])
            ->where('book_id', $data['book_id'])
            ->first();

        if ($existingRating) {
            return ['error' => 'Вы уже оценивали эту книгу', 'code' => 400];
        }

        Rating::create($data);
        return ['message' => 'Рейтинг успешно создан', 'code' => 201];
    }

    public function updateRating(array $data): void
    {
        Rating::where('id', $data['id'])->update($data);
    }

    public function deleteRating(int $id): void
    {
        Rating::destroy($id);
    }
}
