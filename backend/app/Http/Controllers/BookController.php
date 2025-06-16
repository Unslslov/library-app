<?php

namespace App\Http\Controllers;

use App\Http\Filter\Author;
use App\Http\Filter\Genre;
use App\Http\Filter\Publisher;
use App\Http\Requests\Book\StoreRequest;
use App\Http\Requests\Book\UpdateRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Carbon;

class BookController extends Controller
{
    public function index()
    {
        $bookQuery = Book::query();

        $builder = app()->make(Pipeline::class)
            ->send($bookQuery)
            ->through([
                Author::class,
                Genre::class,
                Publisher::class,
            ])
            ->thenReturn();

        $books = $builder->orderBy('title')->get();
        return BookResource::collection($books);
    }

    public function show(string $id)
    {
        $book = Book::with(['ratings', 'comments.user'])->findOrFail($id);

        $averageRating = $book->ratings->avg('rating');

        $book['average_rating'] = round($averageRating, 2);
        $book['comments'] = $book->comments;

        return response()->json(['data' => $book]);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $book = Book::create($data);

        return response()->json(['Книга добавлен' => 'успех'], 201);
    }

    public function update(UpdateRequest $request, string $id)
    {
        $data = $request->validated();

        $book = Book::findOrFail($id);
        $book->update($data);

        return response()->json(['message' => 'Книга обновлена'], 204);
    }

    public function destroy(string $id)
    {
        book::destroy($id);

        return response()->noContent(204);
    }
}
