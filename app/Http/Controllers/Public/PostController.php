<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $query = Post::published();

        if ($category = $request->query('categorie')) {
            $query->where('category', $category);
        }

        return view('public.posts.index', [
            'posts' => $query->paginate(9)->withQueryString(),
            'categories' => Post::published()->reorder()->whereNotNull('category')->select('category')->distinct()->orderBy('category')->pluck('category')->filter()->values(),
            'activeCategory' => $category ?? null,
        ]);
    }

    public function show(string $slug): View
    {
        $post = Post::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $post->load('author');

        return view('public.posts.show', [
            'post' => $post,
            'relatedPosts' => Post::published()->where('id', '!=', $post->id)->limit(3)->get(),
        ]);
    }
}
