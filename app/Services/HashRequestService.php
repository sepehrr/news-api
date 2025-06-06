<?php

namespace App\Services;

use Illuminate\Http\Request;

class HashRequestService
{
    /**
     * Hash the request parameters
     */
    public static function hash(Request $request): string
    {
        return md5(collect($request->all())->sortKeys()->toJson());
    }
}
