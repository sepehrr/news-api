<?php

namespace App\Services\ArticleCrawlers;

use App\Services\ArticleCrawlers\BBCNews\BBCNewsCrawler;
use App\Services\ArticleCrawlers\NewsAPI\NewsAPICrawler;
use Log;

class ArticleCrawlerService
{
    private array $crawlers;

    public function __construct(
        BBCNewsCrawler $bbcNewsCrawler,
        NewsAPICrawler $newsAPICrawler,
    ) {
        $this->crawlers = [
            $bbcNewsCrawler,
            $newsAPICrawler,
        ];
    }

    public function crawl(): void
    {
        foreach ($this->crawlers as $crawler) {
            Log::info('Crawling ' . $crawler->sourceTitle());
            $crawler->createArticles();
        }
    }
}
