<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserController;
use App\Models\Status;
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

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::prefix('user')->group(function () {
    Route::middleware('auth:api')->patch('/update', [UserController::class, 'update']);
    Route::middleware('auth:api')->get('/', [UserController::class, 'user']);
});

Route::middleware('auth:api')->post('/logout', [UserController::class, 'logout']);

Route::prefix('order')->group(function () {
    Route::middleware('auth:api')->post('/', [OrderController::class, 'store']);
    Route::middleware('auth:api')->get('/', [OrderController::class, 'index']);
});

Route::get('/status', [StatusController::class, 'index']);
