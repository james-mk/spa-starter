<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\UserLogController;
use App\Http\Controllers\Api\V1\PermissionController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink']);
    Route::post('/reset-password', [ResetPasswordController::class, 'reset']);
    Route::get('settings/public', [SettingController::class, 'index']); // Public settings access


    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        // Settings routes

        Route::get('profile', [ProfileController::class, 'show']);
        Route::post('profile', [ProfileController::class, 'update']);
        Route::post('profile/password', [ProfileController::class, 'updatePassword']);

        Route::middleware(['permission:view settings'])->group(function () {
            Route::get('settings', [SettingController::class, 'index']);
            Route::post('settings', [SettingController::class, 'update']);
        });

        Route::middleware(['permission:view roles'])->group(function () {
            Route::apiResource('roles', RoleController::class);
        });
        Route::middleware(['permission:view permissions'])->group(function () {
            Route::apiResource('permissions', PermissionController::class)->except(['show']);
        });
        //users
        Route::middleware(['permission:view users'])->group(function () {
            Route::apiResource('users', \App\Http\Controllers\Api\V1\UserController::class);
        });

        // User logs
        Route::middleware(['permission:view logs'])->group(function () {
            Route::get('user-logs', [UserLogController::class, 'index']);
            Route::get('user-logs/{id}', [UserLogController::class, 'show']);
        });
    });
});