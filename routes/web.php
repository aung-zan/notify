<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web\EmailController;
use App\Http\Controllers\Web\AppController;
use App\Http\Controllers\Web\PushController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(LoginController::class)
    ->middleware('guest')
    ->group(function () {
        Route::get('/login', 'login')->name('auth.login');
        Route::post('/login', 'authenticate')->name('auth.authenticate');
        Route::post('/logout', 'logout')->name('auth.logout')
            ->withoutMiddleware('guest')
            ->middleware('auth');
    });

Route::controller(AppController::class)
    ->prefix('app')
    ->middleware('auth')
    ->group(function () {
        Route::get('', 'index')->name('app.index');
        Route::post('data', 'getData')->name('app.getData');
        Route::get('create', 'create')->name('app.create');
        Route::post('', 'store')->name('app.store');

        Route::prefix('{id}')->group(function () {
            Route::get('', 'show')->name('app.show');
            Route::get('edit', 'edit')->name('app.edit');
            Route::match(['put', 'patch'], 'update', 'update')->name('app.update');
            Route::delete('delete', 'delete')->name('app.delete');
        });
    });

Route::controller(EmailController::class)
    ->prefix('email')
    ->middleware('auth')
    ->group(function () {
        Route::get('', 'index')->name('email.index');
        Route::post('data', 'getData')->name('email.getData');
        Route::get('create', 'create')->name('email.create');
        Route::post('', 'store')->name('email.store');

        Route::prefix('{id}')->group(function () {
            Route::get('', 'show')->name('email.show');
            Route::get('edit', 'edit')->name('email.edit');
            Route::match(['put', 'patch'], 'update', 'update')->name('email.update');
        });
    });

Route::controller(PushController::class)
    ->prefix('push')
    ->middleware('auth')
    ->group(function () {
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
