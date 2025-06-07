<?php

namespace App\Services\ArticleCrawlers\BBCNews;

use App\Models\Article;
use App\Services\ArticleCrawlers\BaseCrawler;

class BBCNewsCrawler extends BaseCrawler
{
    public function __construct(
        BBCNewsClient $client,
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
            'body' => $article['description'],
            'published_at' => $article['published_at'] ? date('Y-m-d H:i:s', strtotime($article['published_at'])) : now(),
            'external_id' => $this->getExternalId($article),
            'source_id' => $this->getSource()->id,
        ]);
    }

    public function sourceName(): string
    {
        return 'BBC News';
    }

    protected function getExternalId(array $article): string
    {
        return last(explode('/', $article['link']));
    }
}
