<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Post::with(['author', 'category', 'tags'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        $categories = Category::withCount('posts')->orderBy('name')->get();

        return view('home', compact('posts', 'categories'));
    }
}
