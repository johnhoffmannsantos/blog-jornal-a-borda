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

        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:500'],
                'logo' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,svg,webp', 'max:2048'],
                'website_url' => ['nullable', 'string', 'max:500'],
                'level' => ['required', 'string', 'in:gold,silver,bronze'],
                'order' => ['nullable', 'integer', 'min:0'],
                'is_active' => ['nullable', 'in:0,1'],
            ], [
                'name.required' => 'O nome do parceiro é obrigatório.',
                'name.max' => 'O nome não pode ter mais de 255 caracteres.',
                'logo.required' => 'O logo é obrigatório.',
                'logo.image' => 'O arquivo deve ser uma imagem.',
                'logo.mimes' => 'A imagem deve ser JPEG, PNG, GIF, SVG ou WebP.',
                'logo.max' => 'A imagem não pode ter mais de 2MB.',
                'website_url.url' => 'A URL do site deve ser válida.',
                'level.required' => 'O nível do parceiro é obrigatório.',
                'level.in' => 'O nível deve ser Ouro, Prata ou Bronze.',
                'order.integer' => 'A ordem deve ser um número inteiro.',
                'order.min' => 'A ordem não pode ser negativa.',
            ]);

            // Upload do logo
            $logoPath = null;
            if ($request->hasFile('logo')) {
                // Garantir que o diretório existe
                if (!Storage::disk('public')->exists('partners/logos')) {
                    Storage::disk('public')->makeDirectory('partners/logos');
                }
                
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
                'is_active' => (bool) $request->input('is_active', 0),
            ]);

            return redirect()->route('admin.partners.index')
                ->with('success', 'Parceiro criado com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Erro ao criar parceiro. Verifique os campos e tente novamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao criar parceiro: ' . $e->getMessage());
        }
    }

    public function edit(Partner $partner)
    {
        $this->checkAdmin();
        
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $this->checkAdmin();

        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'logo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,svg,webp', 'max:2048'],
                'website_url' => ['nullable', 'string', 'max:500'],
                'level' => ['required', 'string', 'in:gold,silver,bronze'],
                'order' => ['nullable', 'integer', 'min:0'],
                'is_active' => ['nullable', 'in:0,1'],
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
            if (empty($validated['website_url']) || trim($validated['website_url']) === '') {
                $validated['website_url'] = null;
            } else {
                // Validar URL se foi fornecida
                $url = trim($validated['website_url']);
                // Adicionar http:// se não tiver protocolo
                if (!preg_match('/^https?:\/\//', $url)) {
                    $url = 'http://' . $url;
                }
                if (!filter_var($url, FILTER_VALIDATE_URL)) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'A URL do site deve ser válida.');
                }
                $validated['website_url'] = $url;
            }

            $logoPath = $partner->logo;

            // Upload do novo logo se houver
            if ($request->hasFile('logo')) {
                // Garantir que o diretório existe
                if (!Storage::disk('public')->exists('partners/logos')) {
                    Storage::disk('public')->makeDirectory('partners/logos');
                }
                
                // Deletar logo antigo se existir
                if ($partner->logo) {
                    // Extrair o caminho relativo do logo
                    $logoUrl = $partner->logo;
                    // Se contém /storage/, extrair o caminho após /storage/
                    if (str_contains($logoUrl, '/storage/')) {
                        $relativePath = str_replace(url('/storage/'), '', $logoUrl);
                        $fullPath = 'partners/logos/' . basename($relativePath);
                        
                        // Tentar deletar o arquivo antigo
                        if (Storage::disk('public')->exists($fullPath)) {
                            Storage::disk('public')->delete($fullPath);
                        }
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
                'is_active' => (bool) $request->input('is_active', 0),
            ];

            $partner->update($updateData);

            return redirect()->route('admin.partners.index')
                ->with('success', 'Parceiro atualizado com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Erro ao atualizar parceiro. Verifique os campos e tente novamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar parceiro: ' . $e->getMessage());
        }
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
