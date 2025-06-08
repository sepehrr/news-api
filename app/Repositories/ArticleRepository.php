<?php

namespace App\Repositories;

use App\Http\Filters\ArticleListFilter;
use App\Http\Requests\Article\CreateArticleRequest;
use App\Models\Article;
use App\Models\User;
use App\Repositories\Interfaces\ArticleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function __construct(
        protected Article $model,
        protected ArticleListFilter $filter
    ) {
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        $this->filter->query($query)->filters($filters)->apply();

        return $query->with(['category', 'author', 'source'])
            ->paginate($perPage);
    }

    public function findById(int $id): ?Article
    {
        return $this->model->with(['category', 'author', 'source'])
            ->find($id);
    }

    public function getPreferredByUser(User $user = null, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery();
        $query->preferredBy($user);
        $this->filter->query($query)->filters($filters)->apply();

        return $query->with(['category', 'author', 'source'])
            ->paginate($perPage);
    }

    /**
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
        if ($this->existsByExternalId($data['external_id'], $data['source_id'])) {
            throw ValidationException::withMessages([
                'external_id' => 'An article with this external ID already exists for this source.'
            ]);
        }

        return $this->model->create($data);
    }

    public function existsByExternalId(string $externalId, int $sourceId): bool
    {
        return $this->model->where('external_id', $externalId)
            ->where('source_id', $sourceId)
            ->exists();
    }
}
