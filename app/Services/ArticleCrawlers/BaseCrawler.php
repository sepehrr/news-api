<?php

namespace App\Services\ArticleCrawlers;

use App\Models\Article;
use App\Models\Source;
use App\Services\ArticleCrawlers\Interfaces\CrawlerClientInterface;
use App\Services\ArticleCrawlers\Interfaces\CrawlerInterface;
use Log;

abstract class BaseCrawler implements CrawlerInterface
{
    public function __construct(
        protected CrawlerClientInterface $client,
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
        return Source::firstOrCreate([
            'name' => $this->sourceName(),
        ]);
    }
}
