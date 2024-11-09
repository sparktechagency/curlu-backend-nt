<?php

namespace App\Providers;

use App\Services\DistanceService;
use Illuminate\Support\ServiceProvider;


class DistanceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(DistanceService::class, function () {
            return new DistanceService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
