<?php

use App\Http\Controllers\Api\EmailController;
use App\Http\Controllers\Api\PushController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return 'api testing route';
});

Route::prefix('email')->group(function () {
    Route::controller(EmailController::class)->group(function () {
        Route::get('list', 'list');
        Route::post('send', 'send');
    });
});

Route::prefix('push')->group(function () {
    Route::controller(PushController::class)->group(function () {
        Route::get('list', 'list');
        Route::post('send', 'send');
    });
});
