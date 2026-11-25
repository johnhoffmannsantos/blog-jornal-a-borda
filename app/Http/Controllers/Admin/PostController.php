<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * NOTA: a listagem (index) fica em App\Http\Controllers\AdminPostIndexController
 * (ver routes/web.php). O index() antigo deste controller era código morto
 * (a rota usa ->except(['index'])) e foi removido para evitar divergência.
 */
class PostController extends Controller
{
    public function preview($id)
    {
        $post = Post::with(['author', 'category', 'tags', 'comments.replies'])
            ->findOrFail($id);

        $user = Auth::user();

        if (!$user->canManageAllPosts() && $post->author_id !== $user->id) {
            abort(403, 'Você não tem permissão para visualizar este post.');
        }

        $previousPost = null;
        $nextPost = null;

        $categories = Category::withCount('posts')
            ->orderBy('name')
            ->get();

        return view('post', compact(
            'post',
            'previousPost',
            'nextPost',
            'categories'
        ));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        $authors = Auth::user()->isAdmin() ? User::where('is_active', true)->orderBy('name')->get() : collect([Auth::user()]);
        return view('admin.posts.create', compact('categories', 'tags', 'authors'));
    }

    public function store(Request $request)
    {
        $status = $request->input('status');

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'featured_image_file' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'status' => ['required', 'in:draft,published'],
            'published_at_date' => ['nullable', 'date'],
            'published_at_time' => ['nullable', 'regex:/^([01]\d|2[0-3]):[0-5]\d$/'],
            'tags' => ['nullable', 'array'],
            'author_id' => ['nullable', 'exists:users,id'],
        ];

        if ($status === 'published') {
            $rules['published_at_date'] = ['required', 'date'];
            $rules['published_at_time'] = ['required', 'regex:/^([01]\d|2[0-3]):[0-5]\d$/'];
        }

        $validated = $request->validate($rules);

        $publishedAt = $this->parsedPublishedAtFromDateAndTime($request);

        if ($validated['status'] === 'published') {
            $publishedAt = $publishedAt ?? Carbon::now(config('app.timezone'));
        }

        if ($validated['status'] === 'draft') {
            $publishedAt = null;
        }

        $featuredImage = null;

        if ($request->hasFile('featured_image_file')) {
            $file = $request->file('featured_image_file');
            $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('posts/featured', $filename, 'public');
            $featuredImage = '/storage/'.$path;
        }

        $authorId = Auth::user()->isAdmin() && isset($validated['author_id']) && $validated['author_id']
            ? $validated['author_id']
            : Auth::id();

        $post = Post::create([
            'title' => $validated['title'],
            'slug' => $this->uniquePostSlug($validated['title']),
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'author_id' => $authorId,
            'featured_image' => $featuredImage,
            'status' => $validated['status'],
            'published_at' => $publishedAt,
        ]);

        if ($request->has('tags')) {
            $post->tags()->attach($validated['tags']);
        }

        session()->forget('admin_posts_filters');

        return redirect()->route('admin.posts.index')->with('success', 'Post criado com sucesso!');
    }

    public function show(Post $post)
    {
        $user = Auth::user();

        if (!$user->canManageAllPosts() && $post->author_id !== $user->id) {
            abort(403, 'Você não tem permissão para ver este post.');
        }

        return redirect()->route('post.show', $post->slug);
    }

    public function edit(Post $post)
    {
        $user = Auth::user();

        if (!$user->canManageAllPosts() && $post->author_id !== $user->id) {
            abort(403, 'Você não tem permissão para editar este post.');
        }

        $categories = Category::all();
        $tags = Tag::all();
        $postTags = $post->tags->pluck('id')->toArray();
        $authors = Auth::user()->isAdmin() ? User::where('is_active', true)->orderBy('name')->get() : collect([Auth::user()]);

        return view('admin.posts.edit', compact('post', 'categories', 'tags', 'postTags', 'authors'));
    }

    public function update(Request $request, Post $post)
    {
        $user = Auth::user();

        if (!$user->canManageAllPosts() && $post->author_id !== $user->id) {
            abort(403, 'Você não tem permissão para editar este post.');
        }

        $status = $request->input('status');

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'featured_image_file' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'status' => ['required', 'in:draft,published'],
            'published_at_date' => ['nullable', 'date'],
            'published_at_time' => ['nullable', 'regex:/^([01]\d|2[0-3]):[0-5]\d$/'],
            'tags' => ['nullable', 'array'],
            'author_id' => ['nullable', 'exists:users,id'],
        ];

        if ($status === 'published') {
            $rules['published_at_date'] = ['required', 'date'];
            $rules['published_at_time'] = ['required', 'regex:/^([01]\d|2[0-3]):[0-5]\d$/'];
        }

        $validated = $request->validate($rules);

        $publishedAt = $this->parsedPublishedAtFromDateAndTime($request);

        if ($validated['status'] === 'published') {
            $publishedAt = $publishedAt ?? $post->published_at ?? Carbon::now(config('app.timezone'));
        }

        if ($validated['status'] === 'draft') {
            $publishedAt = null;
        }

        $featuredImage = $post->featured_image;

        if ($request->hasFile('featured_image_file')) {
            if ($post->featured_image && str_contains($post->featured_image, '/storage/posts/featured/')) {
                $oldPath = str_replace('/storage/', '', $post->featured_image);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $file = $request->file('featured_image_file');
            $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('posts/featured', $filename, 'public');
            $featuredImage = '/storage/'.$path;
        }

        $authorId = $post->author_id;
        if (Auth::user()->isAdmin() && isset($validated['author_id']) && $validated['author_id']) {
            $authorId = $validated['author_id'];
        }

        $post->update([
            'title' => $validated['title'],
            'slug' => $this->uniquePostSlug($validated['title'], $post->id),
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'author_id' => $authorId,
            'featured_image' => $featuredImage,
            'status' => $validated['status'],
            'published_at' => $publishedAt,
        ]);

        if ($request->has('tags')) {
            $post->tags()->sync($validated['tags']);
        } else {
            $post->tags()->detach();
        }

        // Pega a sessão salva e anexa no redirecionamento do update
        $savedFilters = session('admin_posts_filters', []);
        return redirect()->route('admin.posts.index', $savedFilters)->with('success', 'Post atualizado com sucesso!');
    }

    public function destroy(Post $post)
    {
        $user = Auth::user();

        // Excluir post alheio: apenas admin/editor. Qualquer um pode excluir o próprio.
        if (!$user->canDeleteAllPosts() && $post->author_id !== $user->id) {
            abort(403, 'Você não tem permissão para excluir este post.');
        }

        $post->delete();

        $savedFilters = session('admin_posts_filters', []);
        return redirect()->route('admin.posts.index', $savedFilters)->with('success', 'Post excluído com sucesso!');
    }

    private function uniquePostSlug(string $title, ?int $ignorePostId = null): string
    {
        $base = Str::slug($title);
        if ($base === '') {
            $base = 'post';
        }
        $base = Str::limit($base, 220, '');

        $candidate = $base;
        $n = 2;
        while (Post::query()
            ->where('slug', $candidate)
            ->when($ignorePostId !== null, fn ($q) => $q->where('id', '!=', $ignorePostId))
            ->exists()) {
            $suffix = '-'.$n;
            $n++;
            $candidate = Str::limit($base, 255 - strlen($suffix), '').$suffix;
        }

        return $candidate;
    }

    private function parsedPublishedAtFromDateAndTime(Request $request): ?Carbon
    {
        if (! $request->filled('published_at_date')) {
            return null;
        }

        $time = $request->input('published_at_time', '00:00');
        $time = is_string($time) ? preg_replace('/\s+/', '', substr($time, 0, 5)) : '00:00';
        if (! preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $time)) {
            $time = '00:00';
        }

        try {
            return Carbon::parse($request->published_at_date.' '.$time.':00', config('app.timezone'));
        } catch (\Throwable) {
            return null;
        }
    }
}
