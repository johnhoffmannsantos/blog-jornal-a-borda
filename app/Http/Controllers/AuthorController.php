<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function show($id)
    {
        $author = User::withCount('posts')->findOrFail($id);

        $posts = $author->posts()
            ->with(['category', 'tags'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        $categories = Category::withCount('posts')->orderBy('name')->get();

        return view('author', compact('author', 'posts', 'categories'));
    }
}
