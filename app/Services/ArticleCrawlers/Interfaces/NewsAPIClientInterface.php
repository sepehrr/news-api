<?php

namespace App\Services\ArticleCrawlers\Interfaces;

interface NewsAPIClientInterface extends CrawlerClientInterface
{
    public function getArticles(): array;
}
