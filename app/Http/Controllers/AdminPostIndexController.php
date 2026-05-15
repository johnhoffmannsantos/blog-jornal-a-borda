<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * Listagem de posts do painel (filtros, ordenação por data de publicação).
 */
class AdminPostIndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        $searchTrim = trim((string) $request->input('search', ''));

        $request->merge([
            'search' => $searchTrim !== '' ? $searchTrim : null,
            'status' => $request->filled('status') ? $request->status : null,
            'visibility' => $request->filled('visibility') ? $request->visibility : null,
            'category' => $request->filled('category') ? $request->category : null,
            'author' => $user->canManageAllPosts() && $request->filled('author') ? $request->author : null,
            'published_from' => $request->filled('published_from') ? $request->published_from : null,
            'published_to' => $request->filled('published_to') ? $request->published_to : null,
        ]);

        $rules = [
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:published,draft'],
            'visibility' => ['nullable', 'in:live,upcoming'],
            'category' => ['nullable', 'integer', 'exists:categories,id'],
            'published_from' => ['nullable', 'date'],
            'published_to' => ['nullable', 'date'],
        ];
        if ($user->canManageAllPosts()) {
            $rules['author'] = ['nullable', 'integer', 'exists:users,id'];
        }

        Validator::make($request->all(), $rules)->validate();

        $query = Post::with(['author', 'category', 'tags']);

        $statsQuery = Post::query();
        if (! $user->canManageAllPosts()) {
            $statsQuery->where('author_id', $user->id);
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'live' => (clone $statsQuery)->visibleOnSite()->count(),
            'upcoming' => (clone $statsQuery)->publishedAwaitingDate()->count(),
            'draft' => (clone $statsQuery)->where('status', 'draft')->count(),
        ];

        if (! $user->canManageAllPosts()) {
            $query->where('author_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('visibility')) {
            if ($request->visibility === 'live') {
                $query->visibleOnSite();
            } elseif ($request->visibility === 'upcoming') {
                $query->publishedAwaitingDate();
            }
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
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
