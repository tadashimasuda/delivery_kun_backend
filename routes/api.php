<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AnnouncementReadController;
use App\Http\Controllers\DaysEarningsIncentiveController;
use App\Http\Controllers\EarningsIncentivesSheetController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserController;
use App\Models\AnnouncementRead;
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

Route::post('/OAuth', [UserController::class, 'OAuthLogin']);

Route::prefix('user')->group(function () {
    Route::middleware('auth:api')->delete('/',[UserController::class,'destroy']);
    Route::middleware('auth:api')->patch('/update', [UserController::class, 'update']);
    Route::middleware('auth:api')->get('/', [UserController::class, 'user']);
});

Route::middleware('auth:api')->post('/logout', [UserController::class, 'logout']);

Route::prefix('order')->group(function () {
    Route::middleware('auth:api')->delete('/{id}', [OrderController::class, 'destroy']);
    Route::middleware('auth:api')->patch('/{id}', [OrderController::class, 'update']);
    Route::middleware('auth:api')->get('/{id}', [OrderController::class, 'show']);
    Route::middleware('auth:api')->post('/', [OrderController::class, 'store']);
    Route::middleware('auth:api')->get('/', [OrderController::class, 'index']);
});

Route::get('/status', [StatusController::class, 'index']);
Route::middleware('auth:api')->patch('/actual_cost', [StatusController::class, 'updateActualCost']);

Route::prefix('incentive')->group(function () {
    Route::middleware('auth:api')->get('/', [DaysEarningsIncentiveController::class, 'index']);
    Route::middleware('auth:api')->post('/', [DaysEarningsIncentiveController::class, 'store']);
});

Route::prefix('incentive_sheets')->group(function () {
    Route::middleware('auth:api')->delete('/{id}', [EarningsIncentivesSheetController::class, 'destroy']);
    Route::middleware('auth:api')->patch('/{id}', [EarningsIncentivesSheetController::class, 'update']);
    Route::middleware('auth:api')->get('/{id}', [EarningsIncentivesSheetController::class, 'show']);
    Route::middleware('auth:api')->post('/', [EarningsIncentivesSheetController::class, 'store']);
    Route::middleware('auth:api')->get('/', [EarningsIncentivesSheetController::class, 'index']);
});

Route::prefix('announcement')->group(function () {
    Route::middleware('auth:api')->post('/{id}', [AnnouncementReadController::class, 'store']);
    Route::middleware('auth:api')->get('/{id}', [AnnouncementController::class, 'show']);
    Route::middleware('auth:api')->get('/', [AnnouncementController::class, 'index']);
    Route::middleware('auth:api')->post('/', [AnnouncementController::class, 'store']);
});

Route::get('/count/users',[UserController::class,'userCount']);