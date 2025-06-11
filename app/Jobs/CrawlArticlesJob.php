<?php

namespace App\Jobs;

use App\Services\ArticleCrawlers\Interfaces\ArticleCrawlerServiceInterface;
use App\Services\ArticleCrawlers\Interfaces\CrawlerInterface;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CrawlArticlesJob
{
    use Dispatchable;
    use SerializesModels;

    public function handle(ArticleCrawlerServiceInterface $crawlerService): void
    {
        Log::info('Starting article crawl...');
        $this->feedCrawlers($crawlerService);

        try {
            $crawlerService->crawl();
            Log::info('Article crawl completed successfully.');
        } catch (\Exception $e) {
            Log::error('Error during article crawl: ' . $e->getMessage());

            throw $e;
        }
    }

    private function feedCrawlers(ArticleCrawlerServiceInterface $crawlerService): void
    {
        $crawlers = resolve(CrawlerInterface::class);

        foreach ($crawlers as $crawler) {
            Log::info('Adding crawler: ' . $crawler->sourceName());
            $crawlerService->addCrawler($crawler);
        }
    }
}
