<?php

namespace App\Providers;

use App\Interfaces\DBInterface;
use App\Repositories\AppRepository;
use App\Repositories\EmailChannelRepository;
use App\Repositories\PushChannelRepository;
use App\Services\AppDBService;
use App\Services\EmailChannelService;
use App\Services\PushChannelService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->when(PushChannelService::class)
            ->needs(DBInterface::class)
            ->give(PushChannelRepository::class);

        $this->app->when(EmailChannelService::class)
            ->needs(DBInterface::class)
            ->give(EmailChannelRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
