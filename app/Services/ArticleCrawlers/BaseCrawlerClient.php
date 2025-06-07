<?php

namespace App\Services\ArticleCrawlers;

abstract class BaseCrawlerClient
{
    abstract public function getArticles(): array;

    abstract public function getArticle(string $id): array;
}
