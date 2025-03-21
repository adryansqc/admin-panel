<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\UserApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// User Routes
Route::get('users', [UserApiController::class, 'index']);
Route::post('users', [UserApiController::class, 'store']);
Route::get('users/{id}', [UserApiController::class, 'show']);
Route::put('users/{id}', [UserApiController::class, 'update']);
Route::delete('users/{id}', [UserApiController::class, 'destroy']);

// Post Routes
Route::get('posts', [PostApiController::class, 'index']);
Route::post('posts', [PostApiController::class, 'store']);
Route::get('posts/{id}', [PostApiController::class, 'show']);
Route::put('posts/{id}', [PostApiController::class, 'update']);
Route::delete('posts/{id}', [PostApiController::class, 'destroy']);

// Category Routes
Route::get('categories', [CategoryApiController::class, 'index']);
Route::post('categories', [CategoryApiController::class, 'store']);
Route::get('categories/{id}', [CategoryApiController::class, 'show']);
Route::put('categories/{id}', [CategoryApiController::class, 'update']);
Route::delete('categories/{id}', [CategoryApiController::class, 'destroy']);
