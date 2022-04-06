<?php

use App\Http\Controllers\ExternalBookController;
use App\Http\Controllers\InternalBookController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/external-books', [ExternalBookController::class, 'getBook']);
Route::post('/v1/books', [InternalBookController::class, 'createBook']);
Route::get('/v1/books', [InternalBookController::class, 'readBooks']);
Route::post('/v1/books/{id}', [InternalBookController::class, 'updateBook']);
Route::get('/v1/books/{id}', [InternalBookController::class, 'showBook']);
Route::post('/v1/books/{id}/delete', [InternalBookController::class, 'deleteBook']);
Route::delete('/v1/books/{id}', [InternalBookController::class, 'deleteBook']);

