<?php

use App\Http\Controllers\Api\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return 'api testing route';
});

Route::controller(NotificationController::class)->prefix('notification')->group(function () {
    Route::get('list', 'list');
    Route::get('send', 'send');
});
