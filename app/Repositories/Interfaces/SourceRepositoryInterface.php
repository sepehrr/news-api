<?php

namespace App\Repositories\Interfaces;

use App\Models\Source;

interface SourceRepositoryInterface
{
    /**
     * Find source by name
     */
    public function findByName(string $name): ?Source;
}
