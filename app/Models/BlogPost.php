<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'published_at', 'description',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function booted()
    {
        // When a new post is created, we should refresh the cache
        static::created(function () {
            Cache::flush();
        });

        // When a certain post is updated, we should also refresh the cache
        static::updated(function ($record) {
            Cache::flush();
        });
    }

    public function getIsPublishedAttribute()
    {
        return $this->published_at->isBefore(now());
    }

    public function getExcerptAttribute()
    {
        return Str::words(strip_tags($this->description), 20);
    }

    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now());
    }
}
