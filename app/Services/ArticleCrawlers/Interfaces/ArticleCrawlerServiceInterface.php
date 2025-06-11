<?php

namespace App\Services\ArticleCrawlers\Interfaces;

interface ArticleCrawlerServiceInterface
{
    /**
     * Add a crawler to the service
     */
    public function addCrawler(CrawlerInterface $crawler): self;

    /**
     * Execute the crawling process for all registered crawlers
     */
    public function crawl(): void;
}
