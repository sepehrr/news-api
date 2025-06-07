<?php

namespace App\Services\ArticleCrawlers;

use App\Models\Article;
use App\Models\Source;
use Log;

abstract class BaseCrawler
{
    public function __construct(
        protected BaseCrawlerClient $client,
    ) {
    }

    public function createArticles(): void
    {
        $articles = $this->client->getArticles();

        foreach ($articles as $article) {
            if ($this->articleExists($article)) {
                continue;
            }

            $article = $this->createArticle($article);

            Log::info("Created article (#{$article->id}) {$article->title}");
        }
    }

    abstract public function getArticles(): array;

    abstract public function createArticle(array $article): Article;

    public function articleExists(array $article): bool
    {
        return Article::where('source_id', $this->getSource()->id)->where('external_id', $this->getExternalId($article))->exists();
    }

    abstract public function sourceName(): string;

    abstract protected function getExternalId(array $article): string;

    protected function getSource(): Source
    {
        return Source::firstOrCreate([
            'name' => $this->sourceName(),
        ]);
    }
}
