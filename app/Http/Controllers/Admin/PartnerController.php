<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PartnerController extends Controller
{
    private function checkAdmin()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado. Apenas administradores podem gerenciar parceiros.');
        }
    }

    public function index()
    {
        $this->checkAdmin();
        
        $partners = Partner::orderBy('order')->orderBy('name')->paginate(15);
        
        return view('admin.partners.index', compact('partners'));
    }

    public function create()
    {
        $this->checkAdmin();
        
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'logo' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,svg,webp', 'max:2048'],
            'website_url' => ['nullable', 'url', 'max:500'],
            'level' => ['required', 'string', 'in:gold,silver,bronze'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // Upload do logo
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $logoPath = $file->storeAs('partners/logos', $filename, 'public');
        }

        Partner::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'logo' => $logoPath ? Storage::disk('public')->url($logoPath) : null,
            'website_url' => $validated['website_url'] ?? null,
            'level' => $validated['level'],
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.partners.index')
            ->with('success', 'Parceiro criado com sucesso!');
    }

    public function edit(Partner $partner)
    {
        $this->checkAdmin();
        
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,svg,webp', 'max:2048'],
            'website_url' => ['nullable', 'url', 'max:500'],
            'level' => ['required', 'string', 'in:gold,silver,bronze'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'O nome do parceiro é obrigatório.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'logo.image' => 'O arquivo deve ser uma imagem.',
            'logo.mimes' => 'A imagem deve ser JPEG, PNG, GIF, SVG ou WebP.',
            'logo.max' => 'A imagem não pode ter mais de 2MB.',
            'website_url.url' => 'A URL do site deve ser válida.',
            'level.required' => 'O nível do parceiro é obrigatório.',
            'level.in' => 'O nível deve ser Ouro, Prata ou Bronze.',
            'order.integer' => 'A ordem deve ser um número inteiro.',
            'order.min' => 'A ordem não pode ser negativa.',
        ]);

        // Limpar website_url se estiver vazio
        if (empty($validated['website_url'])) {
            $validated['website_url'] = null;
        }

        $logoPath = $partner->logo;

        // Upload do novo logo se houver
        if ($request->hasFile('logo')) {
            // Deletar logo antigo se existir
            if ($partner->logo && str_contains($partner->logo, '/storage/partners/logos/')) {
                $oldPath = str_replace(Storage::disk('public')->url(''), '', $partner->logo);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            
            $file = $request->file('logo');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('partners/logos', $filename, 'public');
            $logoPath = Storage::disk('public')->url($path);
        }

        $updateData = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'logo' => $logoPath,
            'website_url' => $validated['website_url'] ?? null,
            'level' => $validated['level'],
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->has('is_active'),
        ];

        $partner->update($updateData);

        return redirect()->route('admin.partners.index')
            ->with('success', 'Parceiro atualizado com sucesso!');
    }

    public function destroy(Partner $partner)
    {
        $this->checkAdmin();

        // Deletar logo se existir
        if ($partner->logo && str_contains($partner->logo, '/storage/partners/logos/')) {
            $oldPath = str_replace(Storage::disk('public')->url(''), '', $partner->logo);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $partnerName = $partner->name;
        $partner->delete();

        return redirect()->route('admin.partners.index')
            ->with('success', "Parceiro '{$partnerName}' excluído com sucesso!");
    }
}
