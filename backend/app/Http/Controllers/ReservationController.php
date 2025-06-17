<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\StoreRequest;
use App\Http\Requests\Reservation\UpdateRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Book;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{

    public function __construct(private ReservationService $reservationService)
    {
    }

    public function index()
    {
        $reservations = Reservation::with(['book', 'user'])->get();

        return ReservationResource::collection($reservations);
    }

    public function store(StoreRequest $request)
    {
        $result = $this->reservationService->createReservation($request->validated());
        return response()->json(['message' => $result['message']], $result['code']);
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
        $this->reservationService->cancelReservation($id);
        return response()->noContent(204);
    }

    public function clientReserve(string $id)
    {
        return ReservationResource::collection(
            $this->reservationService->getUserReservations($id)
        );
    }
}
