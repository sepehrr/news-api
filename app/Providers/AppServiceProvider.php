<?php

namespace App\Providers;

use App\Services\HashRequestService;
use App\Services\Interfaces\HashRequestServiceInterface;
use App\Services\Interfaces\PreferencesServiceInterface;
use App\Services\PreferencesService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PreferencesServiceInterface::class, PreferencesService::class);
        $this->app->bind(HashRequestServiceInterface::class, HashRequestService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
