<?php

use App\Http\Controllers\Web\EmailController;
use App\Http\Controllers\Web\AppController;
use App\Http\Controllers\Web\PushController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return 'web testing route.';
});

Route::controller(AppController::class)->prefix('app')->group(function () {
    Route::get('', 'index')->name('app.index');

    Route::prefix('{id}')->group(function () {
        Route::get('', 'show')->name('app.show');
        Route::get('edit', 'edit')->name('app.edit');
        Route::match(['put', 'patch'], 'update', 'update')->name('app.update');
        Route::delete('delete', 'delete')->name('app.delete');
    });
});

Route::controller(EmailController::class)->prefix('email')->group(function () {
    Route::get('', 'index')->name('email.index');
    Route::get('create', 'create')->name('email.create');
    Route::post('', 'store')->name('email.store');

    Route::prefix('{id}')->group(function () {
        Route::get('', 'show')->name('email.show');
        Route::get('edit', 'edit')->name('email.edit');
        Route::match(['put', 'patch'], 'update', 'update')->name('email.update');
    });
});

Route::controller(PushController::class)->prefix('push')->group(function () {
    Route::get('', 'index')->name('push.index');
    Route::post('data', 'getData')->name('push.getData');
    Route::get('create', 'create')->name('push.create');
    Route::post('', 'store')->name('push.store');

    Route::prefix('{id}')->group(function () {
        Route::get('', 'show')->name('push.show');
        Route::get('edit', 'edit')->name('push.edit');
        Route::match(['put', 'patch'], 'update', 'update')->name('push.update');

        Route::get('test', 'testPage')->name('push.testPage');
        Route::post('test', 'test')->name('push.test');
    });
});
