<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\Api\V1\DriverController;
use App\Http\Controllers\Api\V1\CountryController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\UserLogController;
use App\Http\Controllers\Api\V1\PermissionController;

// Taxonomy Controllers
use App\Http\Controllers\Api\V1\PaymentTypeController;
use App\Http\Controllers\Api\V1\VehicleMakeController;
use App\Http\Controllers\Api\V1\VehicleTypeController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Api\V1\DocumentTypeController;
use App\Http\Controllers\Api\V1\DriverStatusController;
use App\Http\Controllers\Api\V1\IncidentTypeController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\VehicleModelController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\TaxonomyImportController;
use App\Http\Controllers\Api\V1\IncidentSubTypeController;
use App\Http\Controllers\Api\V1\PaymentCategoryController;
use App\Http\Controllers\Api\V1\IncidentCategoryController;
use App\Http\Controllers\Api\V1\DeductionCategoryController;
use App\Http\Controllers\Api\V1\DeductionLocationController;
use App\Http\Controllers\Api\V1\IncidentSubCategoryController;
use App\Http\Controllers\Api\V1\DeductionSubCategoryController;
use App\Http\Controllers\Api\V1\DeductionSpecificIssueController;

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


        // ============================================
        // TAXONOMY ROUTES (CONFIGURATIONS)
        // ============================================

        Route::middleware(['permission:view configurations'])->group(function () {

            // Incident Taxonomies (4 levels)
            Route::apiResource('incident-categories', IncidentCategoryController::class);
            Route::apiResource('incident-sub-categories', IncidentSubCategoryController::class);
            Route::apiResource('incident-types', IncidentTypeController::class);
            Route::apiResource('incident-sub-types', IncidentSubTypeController::class);

            // Deduction Taxonomies (4 levels)
            Route::apiResource('deduction-categories', DeductionCategoryController::class);
            Route::apiResource('deduction-sub-categories', DeductionSubCategoryController::class);
            Route::apiResource('deduction-specific-issues', DeductionSpecificIssueController::class);
            Route::apiResource('deduction-locations', DeductionLocationController::class);

            // Payment Taxonomies (2 levels)
            Route::apiResource('payment-categories', PaymentCategoryController::class);
            Route::apiResource('payment-types', PaymentTypeController::class);

            // Other Taxonomies (Single level)
            Route::apiResource('vehicle-types', VehicleTypeController::class);
            Route::apiResource('document-types', DocumentTypeController::class);
            Route::apiResource('driver-statuses', DriverStatusController::class);

            // Taxonomy Import Routes
            Route::prefix('taxonomy-import')->group(function () {
                Route::get('/taxonomies', [TaxonomyImportController::class, 'getAvailableTaxonomies']);
                Route::post('/upload', [TaxonomyImportController::class, 'upload']);
                Route::get('/template/{taxonomy}', [TaxonomyImportController::class, 'downloadTemplate']);
            });

            // Vehicle Makes & Models
            Route::apiResource('vehicle-makes', VehicleMakeController::class);
            Route::apiResource('vehicle-models', VehicleModelController::class);
        });

        // Notifications with permission checks
        Route::prefix('notifications')->group(function () {
            Route::middleware(['permission:view notifications'])->group(function () {
                Route::get('/', [NotificationController::class, 'index']);
                Route::get('/unread', [NotificationController::class, 'unread']);
                Route::get('/stats', [NotificationController::class, 'stats']);
                Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
                Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
                Route::delete('/{id}', [NotificationController::class, 'destroy']);
                //mark as unread
                Route::post('/{id}/unread', [NotificationController::class, 'markAsUnread']);
                //mark all as unread
                Route::post('/mark-all-unread', [NotificationController::class, 'markAllAsUnread']);
            });
        });

        // Countries

        Route::apiResource('countries', CountryController::class);


        // Drivers
        Route::middleware(['permission:view drivers'])->group(function () {
            Route::apiResource('drivers', DriverController::class);
            Route::get('drivers/{driver}/documents/{document}/download', [DriverController::class, 'downloadDocument']);
            Route::get('drivers/status-summary', [DriverController::class, 'statusSummary']);
        });
    });
});
