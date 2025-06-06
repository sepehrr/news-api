<?php

namespace App\Http\Filters;

use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QueryFilter
{
    public const DEFAULT_ORDER_BY = 'created_at';

    public const DEFAULT_ORDER_DIRECTION = 'desc';

    protected $builder;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->builder = Article::query();
    }

    public function query(Builder $query): self
    {
        $this->builder = $query;

        return $this;
    }

    /**
     * Apply the filters to the query
     */
    public function apply(string $orderBy = null, string $direction = null): Builder
    {
        foreach ($this->request->all() as $key => $value) {
            $functionName = Str::camel($key);
            if (method_exists($this, $functionName)) {
                $this->$functionName($value);
            }
        }
        $orderBy = $orderBy ?? static::DEFAULT_ORDER_BY;
        $direction = $direction ?? static::DEFAULT_ORDER_DIRECTION;
        if ($orderBy) {
            $this->builder->orderBy($orderBy, $direction);
        }

        return $this->builder;
    }
}
