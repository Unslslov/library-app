<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\StoreRequest;
use App\Http\Requests\Reservation\UpdateRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use http\Client\Curl\User;
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

        $reservation = Reservation::where('book_id', $data['book_id'])
            ->where('user_id', $data['user_id'])
            ->where('status', 'active')
            ->first();

        if($reservation) {
            return response()->json(['error' => 'данная книга уже забронирована этим пользователем'], 400);
        }

        $data['status'] = 'active';
        $data['expires_at'] = Carbon::now()->addDays(2);

         Reservation::create($data);

        return response()->json(['message' => 'Книга успешно выдана'], 201);
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

//        return response()->json($user);
        $reservations = $user->reservations()->with(['book', 'user'])->get();

        return ReservationResource::collection($reservations);
    }
}
