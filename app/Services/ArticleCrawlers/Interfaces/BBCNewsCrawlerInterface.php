<?php

namespace App\Services\ArticleCrawlers\Interfaces;

use App\Models\Article;

interface BBCNewsCrawlerInterface extends CrawlerInterface
{
    public function getArticles(): array;

    public function createArticle(array $article): Article;

    public function sourceName(): string;

    public function getExternalId(array $article): string;
}
