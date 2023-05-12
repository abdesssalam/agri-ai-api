<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlantsConroller;
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



Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('user', [AuthController::class, 'getUser']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/users/upload-photo', [AuthController::class, 'uploadPhoto']);
    Route::put('/users/edit', [AuthController::class, 'update']);
    Route::post('/plants', [PlantsConroller::class, 'store']);
    Route::put('/plants/{id}', [PlantsConroller::class, 'update']);
    Route::delete('/plants/{id}', [PlantsConroller::class, 'destroy']);
    Route::get('/plants', [PlantsConroller::class, 'index']);
    Route::patch('/plants/{id}/to-garden', [PlantsConroller::class, 'toGarden']);
    Route::patch('/plants/{id}/from-garden', [PlantsConroller::class, 'fromGarden']);
});
