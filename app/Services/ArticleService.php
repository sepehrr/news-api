<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use App\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Services\Interfaces\ArticleServiceInterface;
use App\Services\Interfaces\HashRequestServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ArticleService implements ArticleServiceInterface
{
    public const CACHE_TTL = 60;

    public function __construct(
        protected ArticleRepositoryInterface $articleRepository,
        protected HashRequestServiceInterface $hashRequestService
    ) {
    }

    /**
     * Get paginated list of articles with filters
     */
    public function getPaginatedArticles(Request $request, int $perPage = 15): LengthAwarePaginator
    {
        $hash = $this->hashRequestService::hash($request);

        return Cache::remember("articles:query:{$hash}", self::CACHE_TTL, function () use ($request, $perPage) {
            return $this->articleRepository->getPaginated($request->all(), $perPage);
        });
    }

    /**
     * Get article by ID
     */
    public function getArticleById(int $id): Article
    {
        return Cache::remember("articles:{$id}", self::CACHE_TTL, function () use ($id) {
            return $this->articleRepository->findById($id);
        });
    }

    /**
     * Get personalized feed for a user
     */
    public function getPersonalizedFeed(User $user, Request $request, int $perPage = 15): LengthAwarePaginator
    {
        return $this->articleRepository->getPreferredByUser($user, $request->all(), $perPage);
    }
}
