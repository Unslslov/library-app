<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rating\StoreRequest;
use App\Http\Requests\Rating\UpdateRequest;
use App\Models\Book;
use App\Models\Rating;
use App\Services\RatingService;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function __construct(private RatingService $ratingService)
    {
    }

    public function index()
    {
        return response()->json([
            'average_rating' => $this->ratingService->getAverageRating()
        ]);
    }

    public function store(StoreRequest $request)
    {
        $result = $this->ratingService->createRating($request->validated());
        return isset($result['error'])
            ? response()->json(['error' => $result['error']], $result['code'])
            : response()->json(['message' => $result['message']], $result['code']);
    }

    public function update(UpdateRequest $request)
    {
        $this->ratingService->updateRating($request->validated());
        return response()->noContent(204);
    }

    public function destroy(string $id)
    {
        $this->ratingService->deleteRating($id);
        return response()->noContent(204);
    }
}
