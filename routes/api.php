<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [AuthController::class, 'register'])
    ->middleware('restrictothers');
Route::post('login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
    ->middleware('guest')->name('password.email');

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('users', [UserController::class, 'index']);
    Route::get('me', [AuthController::class, 'me']);

    Route::delete('users/{id}', [UserController::class, 'delete']);
    Route::post('users', [UserController::class, 'create']);
    Route::put('users/{id}', [UserController::class, 'update']);


    Route::post('books', [BookController::class, 'create']);
    Route::delete('books/{slug}', [BookController::class, 'delete']);
    Route::put('books/{slug}/take/{id}', [BookController::class, 'take']);
    Route::put('books/return/{slug}', [BookController::class, 'returnBook']);

    Route::get('books', [BookController::class, 'index']);
    Route::get('books/search', [BookController::class, 'search']);
    Route::put('books/{slug}/reserve/{id}', [BookController::class, 'reserve']);
    Route::put('books/free/{slug}', [BookController::class, 'free']);


});

