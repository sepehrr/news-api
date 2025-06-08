<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;

interface HashRequestServiceInterface
{
    /**
     * Hash the request parameters
     */
    public static function hash(Request $request): string;
}
