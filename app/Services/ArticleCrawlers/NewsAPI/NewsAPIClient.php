<?php

namespace App\Services\ArticleCrawlers\NewsAPI;

use App\Services\ArticleCrawlers\BaseCrawlerClient;
use App\Services\ArticleCrawlers\Interfaces\NewsAPIClientInterface;
use Illuminate\Support\Facades\Http;

class NewsAPIClient extends BaseCrawlerClient implements NewsAPIClientInterface
{
    private string $apiKey;

    private string $baseUrl = 'https://newsapi.org/v2';

    public function __construct()
    {
        $this->apiKey = config('crawling.sources.newsapi.api_key');
    }

    public function getArticles(): array
    {
        $response = Http::withHeaders([
            'X-Api-Key' => $this->apiKey,
        ])->get("{$this->baseUrl}/top-headlines", [
            'country' => 'us',
            'pageSize' => 100,
        ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to fetch articles from NewsAPI: ' . $response->body());
        }

        $data = $response->json();

        return $data['articles'] ?? [];
    }

    public function getArticle(string $id): array
    {
        throw new \Exception('Not implemented');
    }
}
