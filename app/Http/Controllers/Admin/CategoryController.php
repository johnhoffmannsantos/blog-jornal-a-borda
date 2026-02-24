<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('posts')
            ->orderBy('name')
            ->paginate(15);

        // Dados para o gráfico (todas as categorias, sem paginação)
        $chartData = Category::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->get();

        return view('admin.categories.index', compact('categories', 'chartData'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(Category $category)
    {
        $category->loadCount('posts');
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(Category $category)
    {
        $postsCount = $category->posts()->count();

        if ($postsCount > 0) {
            return back()->with('error', 
                "Esta categoria está atrelada a {$postsCount} post(s). " .
                "Ao excluir, o atrelamento será removido. " .
                "Confirme a exclusão novamente para prosseguir."
            );
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria excluída com sucesso!');
    }

    public function forceDestroy(Category $category)
    {
        $postsCount = $category->posts()->count();
        
        // Remover o atrelamento dos posts (definir category_id como null)
        $category->posts()->update(['category_id' => null]);
        
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', "Categoria excluída com sucesso! O atrelamento de {$postsCount} post(s) foi removido.");
    }
}
