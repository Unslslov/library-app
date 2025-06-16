<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        return Comment::all();
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
//        return response()->json(['message' => $data]);

        $comment = Comment::create($data);

        return response()->json(['message' => 'комментарий успешно создан'],201);
    }

    public function destroy(string $id)
    {
        Comment::destroy($id);

        return response()->noContent(204);
    }
}
