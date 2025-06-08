<?php

namespace App\Services\ArticleCrawlers\Interfaces;

use App\Models\Article;

interface CrawlerInterface
{
    public function createArticles(): void;

    public function getArticles(): array;

    public function createArticle(array $article): Article;

    public function sourceName(): string;

    public function articleExists(array $article): bool;
}
