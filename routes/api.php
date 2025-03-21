<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('posts', [PostApiController::class, 'index']);
Route::post('posts', [PostApiController::class, 'store']);
Route::get('posts/{id}', [PostApiController::class, 'show']);
Route::put('posts/{id}', [PostApiController::class, 'update']);
Route::delete('posts/{id}', [PostApiController::class, 'destroy']);
