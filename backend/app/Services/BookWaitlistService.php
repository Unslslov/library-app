<?php

namespace App\Services;

use App\Models\BookWaitlist;

class BookWaitlistService
{
    public function addToWaitlist(array $data): array
    {
        $exists = BookWaitlist::where('user_id', $data['user_id'])
            ->where('book_id', $data['book_id'])
            ->exists();

        if ($exists) {
            return [
                'error' => 'Вы уже подписаны на уведомления по этой книге',
                'code' => 400
            ];
        }

        BookWaitlist::create($data);

        return [
            'message' => 'Вы успешно добавили книгу в список ожидания',
            'code' => 201
        ];
    }
}
