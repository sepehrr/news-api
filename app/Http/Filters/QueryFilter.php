<?php
namespace App\Http\Filters;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class QueryFilter
{

    protected $builder;
    protected $request;

   public function __construct(Request $request)
    {
        $this->request = $request;
        $this->builder = Article::query();
    }

    protected function filter($arr) {
        foreach($arr as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }

        return $this->builder;
    }

    public function query(Builder $query):self
    {
        $this->builder = $query;
        return $this;
    }

    public function apply():Builder  {
        foreach($this->request->all() as $key => $value) {
            if (method_exists($this, $key)) {
                $functionName = str_replace('_', '', ucwords($key, '_'));
                $this->$functionName($value);
            }
        }

        return $this->builder;
    }
}