<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Post::with(['author', 'category', 'tags']);

        // Query base para estatísticas (sem filtros de busca/filtro)
        $statsQuery = Post::query();
        if (!$user->canManageAllPosts()) {
            $statsQuery->where('author_id', $user->id);
        }

        // Calcular estatísticas
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'published' => (clone $statsQuery)->where('status', 'published')->count(),
            'draft' => (clone $statsQuery)->where('status', 'draft')->count(),
        ];

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

        return view('admin.posts.index', compact('posts', 'categories', 'stats'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        $authors = Auth::user()->isAdmin() ? \App\Models\User::where('is_active', true)->orderBy('name')->get() : collect([Auth::user()]);
        return view('admin.posts.create', compact('categories', 'tags', 'authors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'featured_image_file' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'tags' => ['nullable', 'array'],
            'author_id' => ['nullable', 'exists:users,id'],
        ]);

        $featuredImage = null;

        // Processar upload de imagem se houver
        if ($request->hasFile('featured_image_file')) {
            $file = $request->file('featured_image_file');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('posts/featured', $filename, 'public');
            $featuredImage = Storage::disk('public')->url($path);
        }

        // Determinar o autor: se for ADMIN e forneceu author_id, usar; senão usar o usuário logado
        $authorId = Auth::user()->isAdmin() && isset($validated['author_id']) && $validated['author_id'] 
            ? $validated['author_id'] 
            : Auth::id();

        $post = Post::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'author_id' => $authorId,
            'featured_image' => $featuredImage,
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
        $authors = Auth::user()->isAdmin() ? \App\Models\User::where('is_active', true)->orderBy('name')->get() : collect([Auth::user()]);

        return view('admin.posts.edit', compact('post', 'categories', 'tags', 'postTags', 'authors'));
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
            'featured_image_file' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'tags' => ['nullable', 'array'],
            'author_id' => ['nullable', 'exists:users,id'],
        ]);

        $featuredImage = $post->featured_image;

        // Processar upload de imagem se houver
        if ($request->hasFile('featured_image_file')) {
            // Deletar imagem antiga se existir e for do nosso storage
            if ($post->featured_image && str_contains($post->featured_image, '/storage/posts/featured/')) {
                $oldPath = str_replace(Storage::disk('public')->url(''), '', $post->featured_image);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            
            $file = $request->file('featured_image_file');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('posts/featured', $filename, 'public');
            $featuredImage = Storage::disk('public')->url($path);
        }

        // Determinar o autor: se for ADMIN e forneceu author_id, usar; senão manter o autor atual
        $authorId = $post->author_id;
        if (Auth::user()->isAdmin() && isset($validated['author_id']) && $validated['author_id']) {
            $authorId = $validated['author_id'];
        }

        $post->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'author_id' => $authorId,
            'featured_image' => $featuredImage,
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
