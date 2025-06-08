<?php

namespace App\Services\ArticleCrawlers\Interfaces;

interface BBCNewsClientInterface extends CrawlerClientInterface
{
    public function getArticles(): array;
}
