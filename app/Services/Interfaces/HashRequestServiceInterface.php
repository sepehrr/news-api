<?php

namespace App\Services\Interfaces;

interface HashRequestServiceInterface
{
    /**
     * Hash the request parameters
     */
    public function hash(string $prefix, array $filters): string;
}
