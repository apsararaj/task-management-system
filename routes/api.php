<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

Route::get('/login', function () {
    return response()->json(['message' => 'Please log in'], 401);
})->name('login');

Route::post('register', [AuthController::class,'register']);
Route::post('login', [AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class,'logout']); 
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{id}/assign', [TaskController::class, 'assign']);
    Route::put('/tasks/{id}/complete', [TaskController::class, 'complete']);
    Route::get('/tasks', [TaskController::class, 'index']);
});