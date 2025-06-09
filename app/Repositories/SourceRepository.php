<?php

namespace App\Repositories;

use App\Models\Source;
use App\Repositories\Interfaces\SourceRepositoryInterface;

class SourceRepository implements SourceRepositoryInterface
{
    public function __construct(
        protected Source $model
    ) {
    }

    /**
     * Find source by name
     */
    public function findByName(string $name): ?Source
    {
        return $this->model->firstOrCreate([
            'name' => $name,
        ]);
    }
}
