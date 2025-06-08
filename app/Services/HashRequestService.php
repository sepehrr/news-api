<?php

namespace App\Services;

use App\Services\Interfaces\HashRequestServiceInterface;
use Illuminate\Http\Request;

class HashRequestService implements HashRequestServiceInterface
{
    /**
     * Hash the request parameters
     */
    public static function hash(Request $request): string
    {
        return md5(collect($request->all())->sortKeys()->toJson());
    }
}
