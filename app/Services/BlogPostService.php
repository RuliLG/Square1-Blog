<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Utils\CacheKey;
use Illuminate\Support\Facades\Cache;

class BlogPostService {
    public function index()
    {
        $isAscendingOrder = request()->get('sort') === 'asc';
        $page = request()->get('page', 1);
        $key = CacheKey::postList($isAscendingOrder, $page);
        return Cache::remember($key, now()->addMinutes(5), function () use ($isAscendingOrder) {
            return BlogPost::published()
                ->orderBy('published_at', $isAscendingOrder ? 'ASC' : 'DESC')
                ->paginate(10);
        });
    }

    public function show($id)
    {
        return Cache::remember(CacheKey::post($id), now()->addMinutes(5), function () use ($id) {
            return BlogPost::with('owner')
                ->published()
                ->findOrFail($id);
        });
    }

    public function forceShow($id)
    {
        return BlogPost::with('owner')
            ->findOrFail($id);
    }
}
