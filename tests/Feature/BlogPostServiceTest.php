<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Services\BlogPostService;
use App\Utils\CacheKey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class BlogPostServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_cache_hit_detail()
    {
        Cache::flush();
        $post = BlogPost::factory()->create();
        $this->assertFalse(Cache::has(CacheKey::post($post->id)), 'Cache should not be a hit');

        (new BlogPostService())->show($post->id);
        $this->assertTrue(Cache::has(CacheKey::post($post->id)), 'Cache should be a hit');
    }

    public function test_cache_no_hit_detail_after_create_publishet()
    {
        Cache::flush();
        $post1 = BlogPost::factory()->create([
            'published_at' => now()->subHour(),
        ]);
        $this->assertFalse(Cache::has(CacheKey::post($post1->id)), 'Cache should not be a hit');
        (new BlogPostService())->show($post1->id);

        BlogPost::factory()->create();
        $this->assertFalse(Cache::has(CacheKey::post($post1->id)), 'Cache should not be a hit');
    }

    public function test_show_unpublished_post()
    {
        $post = BlogPost::factory()->create([
            'published_at' => now()->addHour(),
        ]);

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        (new BlogPostService())->show($post->id);
    }

    public function test_show_published_post()
    {
        $post = BlogPost::factory()->create([
            'published_at' => now()->subHour(),
        ]);

        (new BlogPostService())->show($post->id);
        $this->assertModelExists($post);
    }

    public function test_cache_hit_list()
    {
        Cache::flush();
        BlogPost::factory(100)->create();
        $page = 1;
        $order = 'asc';
        $isAscendingOrder = $order === 'asc';
        $this->assertFalse(Cache::has(CacheKey::postList($isAscendingOrder, $page)), 'Cache should not be a hit');

        (new BlogPostService())->index($order, $page);
        $this->assertTrue(Cache::has(CacheKey::postList($isAscendingOrder, $page)), 'Cache should be a hit');
    }

    public function test_cache_no_hit_list_different_orders()
    {
        Cache::flush();
        BlogPost::factory(100)->create();
        $page = 1;
        $order = 'asc';
        $this->assertFalse(Cache::has(CacheKey::postList(true, $page)), 'Cache should not be a hit');

        (new BlogPostService())->index($order, $page);
        $this->assertFalse(Cache::has(CacheKey::postList(false, $page)), 'Cache should not be a hit');
    }

    public function test_cache_no_hit_list_different_pages()
    {
        Cache::flush();
        BlogPost::factory(100)->create();
        $page = 1;
        $order = 'asc';
        $this->assertFalse(Cache::has(CacheKey::postList(true, $page)), 'Cache should not be a hit');

        (new BlogPostService())->index($order, $page);
        $this->assertFalse(Cache::has(CacheKey::postList(true, $page + 1)), 'Cache should not be a hit');
    }
}
