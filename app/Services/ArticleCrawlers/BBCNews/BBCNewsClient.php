<?php

namespace App\Services\ArticleCrawlers\BBCNews;

use App\Services\ArticleCrawlers\BaseCrawlerClient;
use Illuminate\Support\Facades\Http;

class BBCNewsClient extends BaseCrawlerClient
{
    public function getArticles(): array
    {
        $response = Http::get('https://feeds.bbci.co.uk/news/rss.xml');

        $xml = simplexml_load_string($response->body());

        $articles = [];

        foreach ($xml->channel->item as $item) {
            $articles[] = [
                'title' => (string) $item->title,
                'description' => (string) $item->description,
                'link' => (string) $item->link,
                'published_at' => (string) $item->pubDate,
            ];
        }

        return $articles;
    }

    public function getArticle(string $id): array
    {
        throw new \Exception('Not implemented');
    }
}
