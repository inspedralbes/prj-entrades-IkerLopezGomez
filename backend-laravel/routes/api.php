<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ConcertController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/movies', [MovieController::class , 'index']);
Route::get('/movies/{id}', [MovieController::class , 'show']);
Route::get('/movies/{id}/seats', [\App\Http\Controllers\MovieSeatController::class , 'index']);
Route::post('/movies/purchase', [\App\Http\Controllers\MovieSeatController::class , 'purchase'])->middleware('auth:sanctum');
Route::get('/concerts', [ConcertController::class , 'index']);
Route::get('/concerts/{id}', [ConcertController::class , 'show']);
Route::get('/concerts/{id}/seats', [\App\Http\Controllers\ConcertSeatController::class , 'index']);
Route::post('/concerts/purchase', [\App\Http\Controllers\ConcertSeatController::class , 'purchase'])->middleware('auth:sanctum');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/auth/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/my-tickets', [\App\Http\Controllers\TicketController::class, 'index'])->middleware('auth:sanctum');