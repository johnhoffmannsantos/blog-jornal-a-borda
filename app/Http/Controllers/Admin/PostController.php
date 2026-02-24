<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Post::with(['author', 'category', 'tags']);

        // Filtros baseados no role
        if (!$user->canManageAllPosts()) {
            $query->where('author_id', $user->id);
        }

        // Filtro por status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filtro por categoria
        if ($request->has('category') && $request->category !== '') {
            $query->where('category_id', $request->category);
        }

        // Busca
        if ($request->has('search') && $request->search !== '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(15);
        $categories = Category::all();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'featured_image' => ['nullable', 'url', 'max:500'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'tags' => ['nullable', 'array'],
        ]);

        $post = Post::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'author_id' => Auth::id(),
            'featured_image' => $validated['featured_image'] ?? null,
            'status' => $validated['status'],
            'published_at' => $validated['published_at'] ?? ($validated['status'] === 'published' ? now() : null),
        ]);

        if ($request->has('tags')) {
            $post->tags()->attach($validated['tags']);
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post criado com sucesso!');
    }

    public function show(Post $post)
    {
        $user = Auth::user();
        
        // Verificar permissão
        if (!$user->canManageAllPosts() && $post->author_id !== $user->id) {
            abort(403, 'Você não tem permissão para ver este post.');
        }

        return redirect()->route('post.show', $post->slug);
    }

    public function edit(Post $post)
    {
        $user = Auth::user();
        
        // Verificar permissão
        if (!$user->canManageAllPosts() && $post->author_id !== $user->id) {
            abort(403, 'Você não tem permissão para editar este post.');
        }

        $categories = Category::all();
        $tags = Tag::all();
        $postTags = $post->tags->pluck('id')->toArray();

        return view('admin.posts.edit', compact('post', 'categories', 'tags', 'postTags'));
    }

    public function update(Request $request, Post $post)
    {
        $user = Auth::user();
        
        // Verificar permissão
        if (!$user->canManageAllPosts() && $post->author_id !== $user->id) {
            abort(403, 'Você não tem permissão para editar este post.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'featured_image' => ['nullable', 'url', 'max:500'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'tags' => ['nullable', 'array'],
        ]);

        $post->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'featured_image' => $validated['featured_image'] ?? null,
            'status' => $validated['status'],
            'published_at' => $validated['published_at'] ?? ($validated['status'] === 'published' && !$post->published_at ? now() : $post->published_at),
        ]);

        if ($request->has('tags')) {
            $post->tags()->sync($validated['tags']);
        } else {
            $post->tags()->detach();
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post atualizado com sucesso!');
    }

    public function destroy(Post $post)
    {
        $user = Auth::user();
        
        // Verificar permissão
        if (!$user->canManageAllPosts() && $post->author_id !== $user->id) {
            abort(403, 'Você não tem permissão para excluir este post.');
        }

        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Post excluído com sucesso!');
    }
}
