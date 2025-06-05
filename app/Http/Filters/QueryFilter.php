<?php

namespace App\Http\Filters;

use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QueryFilter
{
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

    public function apply(): Builder
    {
        foreach ($this->request->all() as $key => $value) {
            $functionName = Str::camel($key);
            if (method_exists($this, $functionName)) {
                $this->$functionName($value);
            }
        }

        return $this->builder;
    }

    protected function filter($arr)
    {
        foreach ($arr as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }

        return $this->builder;
    }
}
