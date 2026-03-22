<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Listagem de posts do painel (filtros, ordenação por data de publicação).
 * Mantido fora de Admin\PostController quando esse arquivo não é gravável no deploy.
 */
class AdminPostIndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'published_from' => ['nullable', 'date'],
            'published_to' => ['nullable', 'date'],
        ]);

        if ($user->canManageAllPosts() && $request->filled('author')) {
            $request->validate([
                'author' => ['required', 'exists:users,id'],
            ]);
        }

        $query = Post::with(['author', 'category', 'tags']);

        $statsQuery = Post::query();
        if (! $user->canManageAllPosts()) {
            $statsQuery->where('author_id', $user->id);
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'published' => (clone $statsQuery)->where('status', 'published')->count(),
            'draft' => (clone $statsQuery)->where('status', 'draft')->count(),
        ];

        if (! $user->canManageAllPosts()) {
            $query->where('author_id', $user->id);
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('category') && $request->category !== '') {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search') && $request->search !== '') {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        if ($user->canManageAllPosts() && $request->filled('author')) {
            $query->where('author_id', $request->author);
        }

        if ($request->filled('published_from')) {
            $query->whereDate('published_at', '>=', $request->published_from);
        }
        if ($request->filled('published_to')) {
            $query->whereDate('published_at', '<=', $request->published_to);
        }

        $dir = strtolower((string) $request->get('dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $query->orderByRaw('published_at IS NULL ASC')
            ->orderBy('published_at', $dir);

        $posts = $query->paginate(15)->withQueryString();

        $categories = Category::all();
        $authors = $user->canManageAllPosts()
            ? User::where('is_active', true)->orderBy('name')->get()
            : collect();

        return view('admin.posts.index', compact('posts', 'categories', 'stats', 'authors'));
    }
}
