<?php

namespace App\Services\ArticleCrawlers;

use App\Services\ArticleCrawlers\Interfaces\ArticleCrawlerServiceInterface;
use App\Services\ArticleCrawlers\Interfaces\CrawlerInterface;
use Log;

class ArticleCrawlerService implements ArticleCrawlerServiceInterface
{
    /** @var CrawlerInterface[] */
    private array $crawlers;

    public function __construct()
    {
        $this->crawlers = [];
    }

    public function addCrawler(CrawlerInterface $crawler): self
    {
        $this->crawlers[] = $crawler;

        return $this;
    }

    public function crawl(): void
    {
        foreach ($this->crawlers as $crawler) {
            Log::info('Crawling ' . $crawler->sourceName());
            $crawler->createArticles();
        }
    }
}
