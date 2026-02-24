<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $posts = Post::with(['author', 'category', 'tags'])
            ->where('category_id', $category->id)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        $categories = Category::withCount('posts')->orderBy('name')->get();

        return view('category', compact('category', 'posts', 'categories'));
    }
}
