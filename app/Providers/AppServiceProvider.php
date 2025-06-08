<?php

namespace App\Providers;

use App\Services\ArticleCrawlers\BBCNews\BBCNewsClient;
use App\Services\ArticleCrawlers\BBCNews\BBCNewsCrawler;
use App\Services\ArticleCrawlers\Interfaces\BBCNewsClientInterface;
use App\Services\ArticleCrawlers\Interfaces\BBCNewsCrawlerInterface;
use App\Services\ArticleCrawlers\Interfaces\NewsAPIClientInterface;
use App\Services\ArticleCrawlers\Interfaces\NewsAPICrawlerInterface;
use App\Services\ArticleCrawlers\NewsAPI\NewsAPIClient;
use App\Services\ArticleCrawlers\NewsAPI\NewsAPICrawler;
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
        // Bind service interfaces
        $this->app->bind(PreferencesServiceInterface::class, PreferencesService::class);
        $this->app->bind(HashRequestServiceInterface::class, HashRequestService::class);

        // Bind BBC News interfaces
        $this->app->bind(BBCNewsCrawlerInterface::class, BBCNewsCrawler::class);
        $this->app->bind(BBCNewsClientInterface::class, BBCNewsClient::class);

        // Bind NewsAPI interfaces
        $this->app->bind(NewsAPICrawlerInterface::class, NewsAPICrawler::class);
        $this->app->bind(NewsAPIClientInterface::class, NewsAPIClient::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
