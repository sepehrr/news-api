<?php

namespace App\Services\Interfaces;

use App\Models\Article;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ArticleServiceInterface
{
    public const PER_PAGE = 15;

    /**
     * Get paginated list of articles with filters
     */
    public function getPaginatedArticles(array $filters, int $perPage = self::PER_PAGE): LengthAwarePaginator;

    /**
     * Get article by ID
     */
    public function getArticleById(int $id): Article;

    /**
     * Get personalized feed for a user
     */
    public function getPersonalizedFeed(User $user, array $filters, int $perPage = self::PER_PAGE): LengthAwarePaginator;

    /**
     * Create a new article
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(array $data): Article;
}
