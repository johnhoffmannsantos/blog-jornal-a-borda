<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('posts')
            ->orderBy('name')
            ->paginate(15);

        // Dados para o gráfico (todas as tags, sem paginação, top 10 mais usadas)
        $chartData = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.tags.index', compact('tags', 'chartData'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tags,name'],
        ]);

        Tag::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag criada com sucesso!');
    }

    public function edit(Tag $tag)
    {
        $tag->loadCount('posts');
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tags,name,' . $tag->id],
        ]);

        $tag->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag atualizada com sucesso!');
    }

    public function destroy(Tag $tag)
    {
        $postsCount = $tag->posts()->count();

        if ($postsCount > 0) {
            return back()->with('error', 
                "Esta tag está atrelada a {$postsCount} post(s). " .
                "Ao excluir, o atrelamento será removido. " .
                "Confirme a exclusão novamente para prosseguir."
            );
        }

        $tag->delete();

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag excluída com sucesso!');
    }

    public function forceDestroy(Tag $tag)
    {
        $postsCount = $tag->posts()->count();
        
        // Remover o atrelamento dos posts (detach)
        $tag->posts()->detach();
        
        $tag->delete();

        return redirect()->route('admin.tags.index')
            ->with('success', "Tag excluída com sucesso! O atrelamento de {$postsCount} post(s) foi removido.");
    }
}
