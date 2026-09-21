<?php

namespace App\Website\Http\Controllers;

use App\Website\Models\Post;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = Post::query()
            ->published()
            ->orderByRaw('COALESCE(published_at, created_at) DESC')
            ->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function show(string $slug): View
    {
        $post = Post::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedPosts = Post::query()
            ->published()
            ->whereKeyNot($post->getKey())
            ->orderByRaw('COALESCE(published_at, created_at) DESC')
            ->limit(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
