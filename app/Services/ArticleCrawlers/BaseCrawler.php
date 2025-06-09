<?php

namespace App\Services\ArticleCrawlers;

use App\Models\Article;
use App\Models\Source;
use App\Repositories\Interfaces\SourceRepositoryInterface;
use App\Services\ArticleCrawlers\Interfaces\CrawlerClientInterface;
use App\Services\ArticleCrawlers\Interfaces\CrawlerInterface;
use App\Services\Interfaces\ArticleServiceInterface;
use Log;

abstract class BaseCrawler implements CrawlerInterface
{
    protected ?Source $source = null;

    public function __construct(
        protected CrawlerClientInterface $client,
        protected ArticleServiceInterface $articleService,
        protected SourceRepositoryInterface $sourceRepository
    ) {
    }

    public function createArticles(): void
    {
        $articles = $this->client->getArticles();

        foreach ($articles as $article) {
            if ($this->articleExists($article)) {
                continue;
            }

            try {
                $article = $this->createArticle($article);
                Log::info("Created article (#{$article->id}) {$article->title}");
            } catch (\Exception $e) {
                Log::error("Error creating article: {$e->getMessage()}");
            }
        }
    }

    abstract public function getArticles(): array;

    abstract public function createArticle(array $article): Article;

    public function articleExists(array $article): bool
    {
        return Article::where('source_id', $this->getSource()->id)->where('external_id', $this->getExternalId($article))->exists();
    }

    abstract public function sourceName(): string;

    abstract public function getExternalId(array $article): string;

    protected function getSource(): Source
    {
        if (!$this->source) {
            $this->source = $this->sourceRepository->findByName($this->sourceName());
        }

        return $this->source;
    }
}
