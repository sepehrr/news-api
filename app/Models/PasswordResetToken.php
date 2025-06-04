<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    public const UPDATED_AT = null;

    public const TOKEN_LENGTH = 32;

    public const TOKEN_TTL = 120; // in minutes

    public $primaryKey = 'token';

    public $fillable = [
        'token',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public function scopeValid(Builder $query)
    {
        return $query->where('created_at', '>', now()->subMinutes(self::TOKEN_TTL));
    }

    public static function findByToken($token)
    {
        return static::query()
            ->where('token', $token)
            ->valid()
            ->firstOrFail();
    }

    protected static function booted()
    {
        parent::booted();

        static::creating(function ($model) {
            $model->token = bin2hex(random_bytes(self::TOKEN_LENGTH / 2));
        });
    }
}
