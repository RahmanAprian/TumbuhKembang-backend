<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\GrowthController;
use App\Http\Controllers\VaccineController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MilestoneController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    Route::apiResource('children', ChildController::class);

    Route::get('/children/{childId}/growth',           [GrowthController::class, 'index']);
    Route::post('/children/{childId}/growth',          [GrowthController::class, 'store']);
    Route::delete('/children/{childId}/growth/{id}',   [GrowthController::class, 'destroy']);

    Route::get('/children/{childId}/vaccines',         [VaccineController::class, 'index']);
    Route::put('/children/{childId}/vaccines/{id}',    [VaccineController::class, 'update']);

    // Milestones — di sini, BUKAN di dalam admin group
    Route::get('/children/{childId}/milestones',          [MilestoneController::class, 'index']);
    Route::post('/children/{childId}/milestones/toggle',  [MilestoneController::class, 'toggle']);

    // Admin only
    Route::middleware('admin')->group(function () {
        Route::get('/admin/stats',            [AdminController::class, 'stats']);
        Route::get('/admin/users',            [AdminController::class, 'users']);
        Route::get('/admin/users/{id}',       [AdminController::class, 'showUser']);
        Route::delete('/admin/users/{id}',    [AdminController::class, 'deleteUser']);
        Route::get('/admin/children',         [AdminController::class, 'children']);
        Route::delete('/admin/children/{id}', [AdminController::class, 'deleteChild']);
    });
});