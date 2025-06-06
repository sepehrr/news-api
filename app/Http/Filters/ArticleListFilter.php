<?php

namespace App\Http\Filters;

class ArticleListFilter extends QueryFilter
{
    public const DEFAULT_ORDER_BY = 'published_at';

    public function keyword($keyword)
    {
        $this->builder->where(function ($query) use ($keyword) {
            $query->where('title', 'like', "%{$keyword}%")
                ->orWhere('body', 'like', "%{$keyword}%");
        });
    }

    public function startDate($date)
    {
        $this->builder->where('published_at', '>=', $date);
    }

    public function endDate($date)
    {
        $this->builder->where('published_at', '<=', $date);
    }

    public function category($category)
    {
        $this->builder->where('category_id', $category);
    }

    public function author($author)
    {
        $this->builder->where('author_id', $author);
    }

    public function source($source)
    {
        $this->builder->where('source_id', $source);
    }
}
