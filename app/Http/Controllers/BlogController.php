<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Services\BlogPostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class BlogController extends Controller
{
    public function index()
    {
        return view('blog-list', [
            'posts' => (new BlogPostService())->index(request()->get('sort'), request()->get('page', 1)),
        ]);
    }

    public function show($id, Request $request)
    {
        // If the URL is signed, we can display the post even if it's not published

        $service = new BlogPostService();
        $post = URL::hasValidSignature($request) ? $service->forceShow($id) : $service->show($id);
        $headers = [];
        if (!$post->is_published) {
            $headers['X-Robots-Tag'] = 'noindex';
        }

        return response()
            ->view('blog-post', [
                'post' => $post,
            ])
            ->withHeaders($headers);
    }
}
