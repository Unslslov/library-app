<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\StoreRequest;
use App\Http\Requests\Reservation\UpdateRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Book;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::with(['book', 'user'])->get();

        return ReservationResource::collection($reservations);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $book = Book::find($data['book_id']);
        if (!$book) {
            return response()->json(['error' => 'Книга не найдена'], 404);
        }

        try {
            $book->reserveFor($data);
            return response()->json(['message' => 'Книга успешно забронирована'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function show(string $id)
    {
        $reservation = Reservation::find($id)->with(['book', 'user'])->get();

        return new ReservationResource($reservation);
    }

    public function update(UpdateRequest $request, string $id)
    {
        $data = $request->validated();

        $reservation = Reservation::find($id);

        $reservation->update($data);

        return response()->noContent(204);
    }

    public function destroy(string $id)
    {
        $reservation = Reservation::find($id);

        $reservation->update(['status' => 'canceled']);

        $reservation->delete();

        return response()->noContent(204);
    }

    public function clientReserve(string $id)
    {
        $user = \App\Models\User::find($id);

        $reservations = $user->reservations()->with(['book', 'user'])->get();

        return ReservationResource::collection($reservations);
    }
}
