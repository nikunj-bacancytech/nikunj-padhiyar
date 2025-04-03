<?php

use App\Http\Controllers\Api\BorrowBookController;
use App\Http\Controllers\Api\ReturnBookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/borrow-book', BorrowBookController::class);
    Route::post('/return-book', ReturnBookController::class);
});
