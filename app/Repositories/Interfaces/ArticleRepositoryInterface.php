<?php

namespace App\Repositories\Interfaces;

use App\Models\Article;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ArticleRepositoryInterface
{
    public const PER_PAGE = 15;

    /**
     * Get paginated articles with filters
     */
    public function getPaginated(array $filters = [], int $perPage = self::PER_PAGE): LengthAwarePaginator;

    /**
     * Get article by ID with relations
     */
    public function findById(int $id): ?Article;

    /**
     * Get article by ID with relations
     */
    public function findByIdOrFail(int $id): Article;

    /**
     * Get articles preferred by user
     */
    public function getPreferredByUser(User $user = null, array $filters = [], int $perPage = self::PER_PAGE): LengthAwarePaginator;

    /**
     * Create a new article
     */
    public function create(array $data): Article;

    /**
     * Check if article exists by external ID and source
     */
    public function existsByExternalId(string $externalId, int $sourceId): bool;
}
