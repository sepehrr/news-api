<?php

namespace App\Providers;

use App\Repositories\ArticleRepository;
use App\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Repositories\Interfaces\SourceRepositoryInterface;
use App\Repositories\SourceRepository;
use App\Services\ArticleCrawlers\BBCNews\BBCNewsClient;
use App\Services\ArticleCrawlers\BBCNews\BBCNewsCrawler;
use App\Services\ArticleCrawlers\Interfaces\BBCNewsClientInterface;
use App\Services\ArticleCrawlers\Interfaces\BBCNewsCrawlerInterface;
use App\Services\ArticleCrawlers\Interfaces\NewsAPIClientInterface;
use App\Services\ArticleCrawlers\Interfaces\NewsAPICrawlerInterface;
use App\Services\ArticleCrawlers\NewsAPI\NewsAPIClient;
use App\Services\ArticleCrawlers\NewsAPI\NewsAPICrawler;
use App\Services\ArticleService;
use App\Services\HashRequestService;
use App\Services\Interfaces\ArticleServiceInterface;
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
        $this->app->bind(ArticleServiceInterface::class, ArticleService::class);

        // Bind repository interfaces
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
        $this->app->bind(SourceRepositoryInterface::class, SourceRepository::class);

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
