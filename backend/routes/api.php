<?php

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Client\BookWaitListController;
use App\Http\Controllers\Client\CommentController;
use App\Http\Controllers\Client\RatingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout/{id}', [AuthController::class, 'logout']);
});

Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('clients', ClientController::class);
    Route::patch('clients/{id}/reset-password', [ClientController::class, 'resetPassword'])->name('clients.reset-password');

    Route::get('roles', [RoleController::class, 'index']);
});

Route::prefix('librarian')->middleware(['auth:sanctum', 'librarian'])->group(function () {
    Route::get('clients', [ClientController::class, 'index']);

    Route::apiResource('books', BookController::class);

    Route::apiResource('loans', LoanController::class)->only(['index', 'store', 'destroy']);

    Route::apiResource('reservations', ReservationController::class);
});

Route::prefix('client')->middleware(['auth:sanctum', 'client'])->group(function () {
    Route::apiResource('books', BookController::class)->only(['index', 'show']);

    Route::post('books/waitlist', [BookWaitListController::class, 'store'])->name('books.waitlist.store');

    Route::apiResource('reservations', ReservationController::class)->only(['index', 'store', 'destroy']);
    Route::get('reservations/my/{id}', [ReservationController::class, 'clientReserve'])->name('reservations.clientReserve');

    Route::apiResource('comments', CommentController::class)->only(['index', 'store', 'destroy']);

    Route::apiResource('ratings', RatingController::class)->only(['index', 'store', 'destroy']);
});
