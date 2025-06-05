<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Preference extends Model
{
    protected $fillable = [
        'user_id',
        'preferable_id',
        'preferable_type', // 'category', 'author', 'source'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function preferable(): MorphTo
    {
        return $this->morphTo();
    }
}
