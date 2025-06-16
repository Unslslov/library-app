<?php

use App\Http\Controllers\Admin\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::post('/register', [\App\Http\Controllers\Auth\AuthController::class, 'register']);

Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout/{id}', [\App\Http\Controllers\Auth\AuthController::class, 'logout']);
});

Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('roles', [\App\Http\Controllers\Admin\RoleController::class, 'index']);

    Route::get('clients', [ClientController::class, 'index']);
    Route::post('clients', [ClientController::class, 'store']);
    Route::get('clients/{id}', [ClientController::class, 'show']);
    Route::patch('clients/{id}/edit', [ClientController::class, 'update']);
    Route::patch('clients/{id}/reset-password', [ClientController::class, 'resetPassword']);
    Route::delete('clients/{id}', [ClientController::class, 'destroy']);
});

Route::prefix('librarian')->middleware(['auth:sanctum', 'librarian'])->group(function () {
    Route::get('clients', [ClientController::class, 'index']);

    Route::get('books', [\App\Http\Controllers\BookController::class, 'index']);
    Route::post('books', [\App\Http\Controllers\BookController::class, 'store']);
    Route::get('books/{id}', [\App\Http\Controllers\BookController::class, 'show']);
    Route::patch('books/{id}/edit', [\App\Http\Controllers\BookController::class, 'update']);
    Route::delete('books/{id}', [\App\Http\Controllers\BookController::class, 'destroy']);

    Route::get('loans', [\App\Http\Controllers\LoanController::class, 'index']);
    Route::post('loans', [\App\Http\Controllers\LoanController::class, 'store']);
    Route::delete('loans/{id}', [\App\Http\Controllers\LoanController::class, 'delete']);

    Route::get('reservations', [\App\Http\Controllers\ReservationController::class, 'index']);
    Route::post('reservations', [\App\Http\Controllers\ReservationController::class, 'store']);
    Route::get('reservations/{id}', [\App\Http\Controllers\ReservationController::class, 'show']);
    Route::patch('reservations/{id}', [\App\Http\Controllers\ReservationController::class, 'update']);
    Route::delete('reservations/{id}', [\App\Http\Controllers\ReservationController::class, 'destroy']);
});

Route::prefix('client')->middleware(['auth:sanctum', 'client'])->group(function () {
    Route::get('books', [\App\Http\Controllers\BookController::class, 'index']);
    Route::get('books/{id}', [\App\Http\Controllers\BookController::class, 'show']);

    Route::post('books/waitlist', [\App\Http\Controllers\Client\BookWaitListController::class, 'store']);

    Route::get('reservations', [\App\Http\Controllers\ReservationController::class, 'index']);
    Route::get('reservations/my/{id}', [\App\Http\Controllers\ReservationController::class, 'clientReserve']);
    Route::post('reservations', [\App\Http\Controllers\ReservationController::class, 'store']);
    Route::delete('reservations/{id}', [\App\Http\Controllers\ReservationController::class, 'destroy']);

    Route::get('comments', [\App\Http\Controllers\Client\CommentController::class, 'index']);
    Route::post('comments', [\App\Http\Controllers\Client\CommentController::class, 'store']);
    Route::delete('comments/{id}', [\App\Http\Controllers\Client\CommentController::class, 'destroy']);

    Route::get('ratings', [\App\Http\Controllers\Client\RatingController::class, 'index']);
    Route::post('ratings', [\App\Http\Controllers\Client\RatingController::class, 'store']);
    Route::delete('ratings/{id}', [\App\Http\Controllers\Client\RatingController::class, 'destroy']);
});
