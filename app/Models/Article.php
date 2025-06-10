<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    public const PREFERABLE_RELATIONS = [
        'category' => Category::class,
        'author' => Author::class,
        'source' => Source::class,
    ];

    protected $fillable = [
        'title',
        'body',
        'published_at',
        'source_id',
        'category_id',
        'author_id',
        'external_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    public function scopePreferredBy(Builder $query, User $user = null)
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return $query;
        }

        $preferences = $user->preferences()->get();

        foreach (self::PREFERABLE_RELATIONS as $type => $class) {
            $this->filterByPreferredIds($query, $preferences, $type, $class);
        }

    }

    private function filterByPreferredIds(Builder $query, Collection $preferences, string $type, string $class)
    {

        $preferredIds = $preferences->where('preferable_type', $class)->pluck('preferable_id')->toArray();

        if (count($preferredIds) > 0) {
            $query->whereHas($type, function ($query) use ($preferredIds) {
                $query->whereIn('id', $preferredIds);
            });
        }
    }
}
