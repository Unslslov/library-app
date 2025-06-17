<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookWaitlist\StoreRequest;
use App\Models\BookWaitlist;
use Illuminate\Http\Request;

class BookWaitListController extends Controller
{
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $userId = $data['user_id'];
        $bookId = $data['book_id'];

        $exists = BookWaitlist::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Вы уже подписаны на уведомления по этой книге'
            ], 400);
        }

        BookWaitlist::create($data);

        return response()->json([
            'message' => 'Вы успешно добавили книгу в список ожидания'
        ], 201);
    }
}
