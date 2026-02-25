<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show($slug)
    {
        $post = Post::with(['author', 'category', 'tags', 'comments.replies'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $previousPost = Post::where('status', 'published')
            ->where('published_at', '<', $post->published_at)
            ->orderBy('published_at', 'desc')
            ->first();

        $nextPost = Post::where('status', 'published')
            ->where('published_at', '>', $post->published_at)
            ->orderBy('published_at', 'asc')
            ->first();

        $categories = Category::withCount('posts')->orderBy('name')->get();

        // Incrementar visualizações
        $post->increment('views');

        return view('post', compact('post', 'previousPost', 'nextPost', 'categories'));
    }

    public function storeComment(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $validated = $request->validate([
            'author_name' => ['required', 'string', 'max:255'],
            'author_email' => ['required', 'email', 'max:255'],
            'author_url' => ['nullable', 'url', 'max:255'],
            'content' => ['required', 'string', 'min:3', 'max:2000'],
        ], [
            'author_name.required' => 'O nome é obrigatório.',
            'author_name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'author_email.required' => 'O email é obrigatório.',
            'author_email.email' => 'O email deve ser válido.',
            'author_email.max' => 'O email não pode ter mais de 255 caracteres.',
            'author_url.url' => 'A URL deve ser válida.',
            'content.required' => 'O comentário é obrigatório.',
            'content.min' => 'O comentário deve ter pelo menos 3 caracteres.',
            'content.max' => 'O comentário não pode ter mais de 2000 caracteres.',
        ]);

        Comment::create([
            'post_id' => $post->id,
            'author_name' => $validated['author_name'],
            'author_email' => $validated['author_email'],
            'author_url' => $validated['author_url'] ?? null,
            'content' => $validated['content'],
            'status' => 'pending', // Comentários precisam ser aprovados
        ]);

        return redirect()->route('post.show', $post->slug)
            ->with('success', 'Comentário enviado com sucesso! Ele será publicado após aprovação.');
    }
}
