<?php

namespace App\Services\ArticleCrawlers\BBCNews;

use App\Models\Article;
use App\Repositories\Interfaces\SourceRepositoryInterface;
use App\Services\ArticleCrawlers\BaseCrawler;
use App\Services\ArticleCrawlers\Interfaces\BBCNewsClientInterface;
use App\Services\ArticleCrawlers\Interfaces\BBCNewsCrawlerInterface;
use App\Services\ArticleCrawlers\Interfaces\CrawlerInterface;
use App\Services\Interfaces\ArticleServiceInterface;
use Illuminate\Validation\ValidationException;
use Log;

class BBCNewsCrawler extends BaseCrawler implements BBCNewsCrawlerInterface, CrawlerInterface
{
    public function __construct(
        BBCNewsClientInterface $client,
        ArticleServiceInterface $articleService,
        SourceRepositoryInterface $sourceRepository
    ) {
        parent::__construct($client, $articleService, $sourceRepository);
    }

    public function getArticles(): array
    {
        return $this->client->getArticles();
    }

    public function createArticle(array $article): Article
    {
        try {
            return $this->articleService->create([
                'title' => $article['title'],
                'body' => $article['description'],
                'published_at' => $article['published_at'] ? date('Y-m-d H:i:s', strtotime($article['published_at'])) : now(),
                'external_id' => $this->getExternalId($article),
                'source_id' => $this->getSource()->id,
            ]);
        } catch (ValidationException $e) {
            Log::warning('Failed to create BBC News article: ' . json_encode($e->errors()));

            throw $e;
        }
    }

    public function sourceName(): string
    {
        return 'BBC News';
    }

    public function getExternalId(array $article): string
    {
        return last(explode('/', $article['link']));
    }
}
