<?php

namespace App\Services\ArticleCrawlers\NewsAPI;

use App\Models\Article;
use App\Models\Author;
use App\Services\ArticleCrawlers\BaseCrawler;
use App\Services\ArticleCrawlers\Interfaces\NewsAPIClientInterface;
use App\Services\ArticleCrawlers\Interfaces\NewsAPICrawlerInterface;

class NewsAPICrawler extends BaseCrawler implements NewsAPICrawlerInterface
{
    public function __construct(
        NewsAPIClientInterface $client,
    ) {
        parent::__construct($client);
    }

    public function getArticles(): array
    {
        return $this->client->getArticles();
    }

    public function createArticle(array $article): Article
    {
        return Article::create([
            'title' => $article['title'],
            'body' => $article['description'] ?? $article['content'] ?? '',
            'published_at' => $article['publishedAt'] ? date('Y-m-d H:i:s', strtotime($article['publishedAt'])) : now(),
            'external_id' => $this->getExternalId($article),
            'source_id' => $this->getSource()->id,
            'author_id' => isset($article['author']) ? Author::firstOrCreate(['name' => $article['author']])->id : null,
        ]);
    }

    public function sourceName(): string
    {
        return 'NewsAPI';
    }

    public function getExternalId(array $article): string
    {
        return md5($article['url'] ?? $article['title']);
    }
}
