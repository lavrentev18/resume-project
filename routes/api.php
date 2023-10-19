<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('me', [AuthController::class, 'me']);

    Route::put('books/reserve/{book:slug}', [BookController::class, 'userReserve'])
        ->missing(function () {
            throw new NotFoundHttpException('Книги не существует');
        });

    Route::resource('books', BookController::class);

    Route::get('books/search', [BookController::class, 'search']);
    Route::put('books/{book:slug}/reserve/{user}', [BookController::class, 'reserve'])
        ->middleware('role:librarian')
        ->missing(function (Request $request, ModelNotFoundException $notFoundException) {
            $modelName = $notFoundException->getModel();

            if (str_contains($modelName, 'Book')) {
                throw new NotFoundHttpException('Книги не существует');
            }

            if (str_contains($modelName, 'User')) {
                throw new NotFoundHttpException('Пользователя не существует');
            }
        });



    Route::put('books/free/{book:slug}', [BookController::class, 'free'])
        ->missing(function () {
            throw new NotFoundHttpException('Книги не существует');
        });


    Route::group(['middleware' => 'role:admin'], function() {
        Route::resource('users', UserController::class)->only([
            'index', 'store', 'update', 'destroy'
        ])->missing(function () {
            throw new NotFoundHttpException('Пользователя не существует');
        });

    });



    Route::group(['middleware' => 'role:admin,librarian'], function() {
        Route::resource('books', BookController::class)->only([
            'store', 'destroy'
        ])->missing(function () {
            throw new NotFoundHttpException('Книги не существует');
        });

        Route::put('books/{book:slug}/take/{user}', [BookController::class, 'take'])
            ->missing(function (Request $request, ModelNotFoundException $notFoundException) {
                $modelName = $notFoundException->getModel();

                if (str_contains($modelName, 'Book')) {
                    throw new NotFoundHttpException('Книги не существует');
                }

                if (str_contains($modelName, 'User')) {
                    throw new NotFoundHttpException('Пользователя не существует');
                }
            });

        Route::put('books/return/{book:slug}', [BookController::class, 'returnBook'])
            ->missing(function () {
                throw new NotFoundHttpException('Книги не существует');
            });
    });
});

