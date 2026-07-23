<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\MemberController;
use App\Http\Controllers\Api\v1\EventController;
use App\Http\Controllers\Api\v1\TaskController;
use App\Http\Controllers\Api\v1\FieldOpsController;
use App\Http\Controllers\Api\v1\ResultController;
use App\Http\Controllers\Api\v1\ContentController;
use App\Http\Controllers\Api\v1\NotificationController;
use App\Http\Controllers\Api\v1\GeographyController;
use App\Http\Controllers\Api\v1\CommunicationsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('auth/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('auth/resend-otp', [AuthController::class, 'resendOtp']);
    Route::post('auth/reset-password', [AuthController::class, 'resetPassword']);

    Route::get('geography/lgas', [GeographyController::class, 'lgas']);
    Route::get('geography/wards', [GeographyController::class, 'wards']);
    Route::get('geography/polling-units', [GeographyController::class, 'pollingUnits']);
    Route::get('geography/roles', [GeographyController::class, 'roles']);
    Route::get('settings', [GeographyController::class, 'settings']);

    // WhatsApp Webhooks
    Route::get('webhooks/whatsapp', [\App\Http\Controllers\Api\v1\WhatsAppWebhookController::class, 'verify']);
    Route::post('webhooks/whatsapp', [\App\Http\Controllers\Api\v1\WhatsAppWebhookController::class, 'handle']);

    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::post('auth/logout', [AuthController::class, 'logout']);

        // Members
        Route::get('members', [MemberController::class, 'index']);
        Route::get('members/nearby', [MemberController::class, 'nearby']);
        Route::post('members/recruit', [MemberController::class, 'recruit']);
        Route::get('members/{id}', [MemberController::class, 'show']);
        Route::match(['put', 'post'], 'members/{id}', [MemberController::class, 'update']);

        // Events
        Route::get('events', [EventController::class, 'index']);
        Route::get('events/{id}', [EventController::class, 'show']);
        Route::post('events/{id}/check-in', [EventController::class, 'checkIn']);

        // Tasks
        Route::get('tasks', [TaskController::class, 'index']);
        Route::put('tasks/{id}/complete', [TaskController::class, 'complete']);

        // Field Operations
        Route::post('location/ping', [FieldOpsController::class, 'ping']);
        Route::post('door-knocks', [FieldOpsController::class, 'doorKnock']);
        Route::get('door-knocks', [FieldOpsController::class, 'doorKnockHistory']);

        // Results & Incidents
        Route::post('results', [ResultController::class, 'uploadResults']);
        Route::get('results/my-pu', [ResultController::class, 'myPuResults']);
        Route::post('incidents', [ResultController::class, 'reportIncident']);
        Route::post('voice-reports', [ResultController::class, 'uploadVoiceReport']);

        // Content
        Route::get('news', [ContentController::class, 'news']);
        Route::get('media', [ContentController::class, 'media']);
        Route::get('leaderboard', [ContentController::class, 'leaderboard']);
        // Notifications
        Route::get('notifications', [NotificationController::class, 'index']);
        Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::post('notifications/token', [NotificationController::class, 'registerToken']);
        Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead']);

        // Communications
        Route::get('broadcasts', [CommunicationsController::class, 'getBroadcasts']);
        Route::post('broadcasts', [CommunicationsController::class, 'sendBroadcast']);
        Route::get('scheduled-posts', [CommunicationsController::class, 'getScheduledPosts']);
        Route::post('scheduled-posts', [CommunicationsController::class, 'schedulePost']);
    });
});
