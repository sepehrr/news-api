<?php

namespace App\Services\ArticleCrawlers;

use App\Services\ArticleCrawlers\Interfaces\BBCNewsCrawlerInterface;
use App\Services\ArticleCrawlers\Interfaces\CrawlerInterface;
use App\Services\ArticleCrawlers\Interfaces\NewsAPICrawlerInterface;
use Log;

class ArticleCrawlerService
{
    /** @var CrawlerInterface[] */
    private array $crawlers;

    public function __construct(
        BBCNewsCrawlerInterface $bbcNewsCrawler,
        NewsAPICrawlerInterface $newsAPICrawler,
    ) {
        $this->crawlers = [
            $bbcNewsCrawler,
            $newsAPICrawler,
        ];
    }

    public function crawl(): void
    {
        foreach ($this->crawlers as $crawler) {
            Log::info('Crawling ' . $crawler->sourceName());
            $crawler->createArticles();
        }
    }
}
