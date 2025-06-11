<?php

namespace App\Services;

use App\Http\Requests\Article\CreateArticleRequest;
use App\Models\Article;
use App\Models\User;
use App\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Services\Interfaces\ArticleServiceInterface;
use App\Services\Interfaces\HashRequestServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @property \App\Repositories\ArticleRepository $articleRepository
 * @property \App\Services\HashRequestService $hashRequestService
 */
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
    public function getPaginatedArticles(array $filters, ?int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?? config('pagination.per_page');
        $hash = $this->hashRequestService->hash('articles:query', $filters);

        return Cache::remember("articles:query:{$hash}", self::CACHE_TTL, function () use ($filters, $perPage) {
            return $this->articleRepository->getPaginated($filters, $perPage);
        });
    }

    /**
     * Get article by ID
     */
    public function getArticleById(int $id): Article
    {
        try {
            return Cache::remember("articles:{$id}", self::CACHE_TTL, function () use ($id) {
                return $this->articleRepository->findByIdOrFail($id);
            });
        } catch (ModelNotFoundException $e) {
            Log::error('Article not found', ['id' => $id]);

            throw $e;
        }
    }

    /**
     * Get personalized feed for a user
     */
    public function getPersonalizedFeed(User $user, array $filters, ?int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?? config('pagination.per_page');

        return $this->articleRepository->getPreferredByUser($user, $filters, $perPage);
    }

    /**
     * Create a new article
     *
     * @throws ValidationException
     */
    public function create(array $data): Article
    {
        // Validate the data
        $validator = Validator::make($data, (new CreateArticleRequest())->rules());

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Check if article already exists
        if ($this->articleRepository->existsByExternalId($data['external_id'], $data['source_id'])) {
            throw ValidationException::withMessages([
                'external_id' => 'An article with this external ID already exists for this source.'
            ]);
        }

        return $this->articleRepository->create($data);
    }
}
