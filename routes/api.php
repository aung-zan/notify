<?php

use App\Http\Controllers\Api\EmailController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PushController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return 'api testing route';
});

Route::prefix('notification')->group(function () {
    Route::controller(NotificationController::class)->group(function () {
        Route::get('list', 'list');
        Route::get('send', 'send');
    });
});
