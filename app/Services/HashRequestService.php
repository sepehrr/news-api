<?php

namespace App\Services;

use App\Services\Interfaces\HashRequestServiceInterface;

class HashRequestService implements HashRequestServiceInterface
{
    /**
     * Hash the request parameters
     */
    public function hash(string $prefix, array $filters): string
    {
        return $prefix . ':' . md5($prefix . collect($filters)->sortKeys()->toJson());
    }
}
