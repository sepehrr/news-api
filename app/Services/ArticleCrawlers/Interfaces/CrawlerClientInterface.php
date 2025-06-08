<?php

namespace App\Services\ArticleCrawlers\Interfaces;

interface CrawlerClientInterface
{
    public function getArticles(): array;
}
