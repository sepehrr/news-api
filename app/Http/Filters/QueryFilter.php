<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class QueryFilter
{
    public const DEFAULT_ORDER_BY = 'created_at';

    public const DEFAULT_ORDER_DIRECTION = 'desc';

    protected $builder;

    protected array $filters = [];

    public function query(Builder $query): self
    {
        $this->builder = $query;

        return $this;
    }

    public function filters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * Apply the filters to the query
     */
    public function apply(string $orderBy = null, string $direction = null): Builder
    {
        foreach ($this->filters as $key => $value) {
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
