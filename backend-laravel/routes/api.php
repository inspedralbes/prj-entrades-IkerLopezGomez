<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\ConcertController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/movies', [MovieController::class , 'index']);
Route::get('/movies/{id}', [MovieController::class , 'show']);
Route::get('/movies/{id}/seats', [\App\Http\Controllers\MovieSeatController::class , 'index']);
Route::post('/movies/purchase', [\App\Http\Controllers\MovieSeatController::class , 'purchase']);
Route::get('/concerts', [ConcertController::class , 'index']);
Route::get('/concerts/{id}', [ConcertController::class , 'show']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');