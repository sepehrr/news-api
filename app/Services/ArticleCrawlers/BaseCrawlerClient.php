<?php

namespace App\Services\ArticleCrawlers;

use App\Services\ArticleCrawlers\Interfaces\CrawlerClientInterface;

abstract class BaseCrawlerClient implements CrawlerClientInterface
{
    abstract public function getArticles(): array;

    abstract public function getArticle(string $id): array;
}
