<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JournalEdition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JournalEditionController extends Controller
{
    private function checkAdmin()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado. Apenas administradores podem gerenciar edições do jornal.');
        }
    }

    public function index()
    {
        $this->checkAdmin();
        
        $editions = JournalEdition::orderBy('published_date', 'desc')
            ->orderBy('edition_number', 'desc')
            ->paginate(15);
        
        return view('admin.journal-editions.index', compact('editions'));
    }

    public function create()
    {
        $this->checkAdmin();
        
        return view('admin.journal-editions.create');
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        try {
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'cover_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
                'pdf_file' => ['required', 'file', 'mimes:pdf', 'max:10240'], // 10MB
                'published_date' => ['required', 'date'],
                'edition_number' => ['nullable', 'integer', 'min:1'],
            ], [
                'title.required' => 'O título da edição é obrigatório.',
                'title.max' => 'O título não pode ter mais de 255 caracteres.',
                'pdf_file.required' => 'O arquivo PDF é obrigatório.',
                'pdf_file.file' => 'O arquivo enviado não é válido.',
                'pdf_file.mimes' => 'O arquivo deve ser um PDF.',
                'pdf_file.max' => 'O arquivo PDF não pode ter mais de 10MB.',
                'published_date.required' => 'A data de publicação é obrigatória.',
                'published_date.date' => 'A data de publicação deve ser uma data válida.',
                'edition_number.integer' => 'O número da edição deve ser um número inteiro.',
                'edition_number.min' => 'O número da edição deve ser pelo menos 1.',
                'cover_image.image' => 'A capa deve ser uma imagem.',
                'cover_image.mimes' => 'A capa deve ser do tipo: jpeg, jpg, png, gif ou webp.',
                'cover_image.max' => 'A capa não pode ter mais de 2MB.',
            ]);

            // Verificar se o diretório existe, se não, criar
            if (!Storage::disk('public')->exists('journal-editions/pdf')) {
                Storage::disk('public')->makeDirectory('journal-editions/pdf', 0755, true);
            }
            if (!Storage::disk('public')->exists('journal-editions/covers')) {
                Storage::disk('public')->makeDirectory('journal-editions/covers', 0755, true);
            }

            // Upload do PDF
            $pdfPath = null;
            if ($request->hasFile('pdf_file')) {
                $file = $request->file('pdf_file');
                $filename = Str::slug($validated['title']) . '-' . time() . '.pdf';
                $pdfPath = $file->storeAs('journal-editions/pdf', $filename, 'public');
                
                if (!$pdfPath) {
                    return back()->withErrors(['pdf_file' => 'Erro ao fazer upload do PDF. Verifique as permissões do diretório.'])->withInput();
                }
            }

            // Upload da capa
            $coverPath = null;
            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $coverPath = $file->storeAs('journal-editions/covers', $filename, 'public');
            }

            // Gerar slug único
            $baseSlug = Str::slug($validated['title']);
            $slug = $baseSlug;
            $counter = 1;
            while (JournalEdition::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            // Garantir que is_featured seja sempre um booleano
            $isFeatured = false;
            if ($request->has('is_featured') && $request->input('is_featured')) {
                $isFeatured = true;
            }

            $edition = JournalEdition::create([
                'title' => $validated['title'],
                'slug' => $slug,
                'description' => $validated['description'] ?? null,
                'cover_image' => $coverPath ? Storage::disk('public')->url($coverPath) : null,
                'pdf_file' => $pdfPath ? Storage::disk('public')->url($pdfPath) : null,
                'published_date' => $validated['published_date'],
                'edition_number' => $validated['edition_number'] ?? null,
                'is_featured' => $isFeatured,
            ]);

            return redirect()->route('admin.journal-editions.index')
                ->with('success', 'Edição do jornal criada com sucesso!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Erro ao criar edição do jornal: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Erro ao criar edição: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit(JournalEdition $journalEdition)
    {
        $this->checkAdmin();
        
        return view('admin.journal-editions.edit', compact('journalEdition'));
    }

    public function update(Request $request, JournalEdition $journalEdition)
    {
        $this->checkAdmin();

        try {
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'cover_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
                'pdf_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
                'published_date' => ['required', 'date'],
                'edition_number' => ['nullable', 'integer', 'min:1'],
            ], [
                'title.required' => 'O título da edição é obrigatório.',
                'title.max' => 'O título não pode ter mais de 255 caracteres.',
                'pdf_file.file' => 'O arquivo enviado não é válido.',
                'pdf_file.mimes' => 'O arquivo deve ser um PDF.',
                'pdf_file.max' => 'O arquivo PDF não pode ter mais de 10MB.',
                'published_date.required' => 'A data de publicação é obrigatória.',
                'published_date.date' => 'A data de publicação deve ser uma data válida.',
                'edition_number.integer' => 'O número da edição deve ser um número inteiro.',
                'edition_number.min' => 'O número da edição deve ser pelo menos 1.',
                'cover_image.image' => 'A capa deve ser uma imagem.',
                'cover_image.mimes' => 'A capa deve ser do tipo: jpeg, jpg, png, gif ou webp.',
                'cover_image.max' => 'A capa não pode ter mais de 2MB.',
            ]);

            // Verificar se o diretório existe, se não, criar
            if (!Storage::disk('public')->exists('journal-editions/pdf')) {
                Storage::disk('public')->makeDirectory('journal-editions/pdf', 0755, true);
            }
            if (!Storage::disk('public')->exists('journal-editions/covers')) {
                Storage::disk('public')->makeDirectory('journal-editions/covers', 0755, true);
            }

            $pdfPath = $journalEdition->pdf_file;
            $coverPath = $journalEdition->cover_image;

            // Upload do novo PDF se houver
            if ($request->hasFile('pdf_file')) {
                // Deletar PDF antigo se existir
                if ($journalEdition->pdf_file && str_contains($journalEdition->pdf_file, '/storage/journal-editions/pdf/')) {
                    $oldPath = str_replace(Storage::disk('public')->url(''), '', $journalEdition->pdf_file);
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
                
                $file = $request->file('pdf_file');
                $filename = Str::slug($validated['title']) . '-' . time() . '.pdf';
                $path = $file->storeAs('journal-editions/pdf', $filename, 'public');
                
                if (!$path) {
                    return back()->withErrors(['pdf_file' => 'Erro ao fazer upload do PDF. Verifique as permissões do diretório.'])->withInput();
                }
                
                $pdfPath = Storage::disk('public')->url($path);
            }

            // Upload da nova capa se houver
            if ($request->hasFile('cover_image')) {
                // Deletar capa antiga se existir
                if ($journalEdition->cover_image && str_contains($journalEdition->cover_image, '/storage/journal-editions/covers/')) {
                    $oldPath = str_replace(Storage::disk('public')->url(''), '', $journalEdition->cover_image);
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
                
                $file = $request->file('cover_image');
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('journal-editions/covers', $filename, 'public');
                $coverPath = Storage::disk('public')->url($path);
            }

            // Gerar slug único se o título mudou
            $slug = $journalEdition->slug;
            if ($journalEdition->title !== $validated['title']) {
                $baseSlug = Str::slug($validated['title']);
                $slug = $baseSlug;
                $counter = 1;
                while (JournalEdition::where('slug', $slug)->where('id', '!=', $journalEdition->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
            }

            // Garantir que is_featured seja sempre um booleano
            $isFeatured = false;
            if ($request->has('is_featured') && $request->input('is_featured')) {
                $isFeatured = true;
            }

            $journalEdition->update([
                'title' => $validated['title'],
                'slug' => $slug,
                'description' => $validated['description'] ?? null,
                'cover_image' => $coverPath,
                'pdf_file' => $pdfPath,
                'published_date' => $validated['published_date'],
                'edition_number' => $validated['edition_number'] ?? null,
                'is_featured' => $isFeatured,
            ]);

            return redirect()->route('admin.journal-editions.index')
                ->with('success', 'Edição do jornal atualizada com sucesso!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar edição do jornal: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Erro ao atualizar edição: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(JournalEdition $journalEdition)
    {
        $this->checkAdmin();

        // Deletar arquivos se existirem
        if ($journalEdition->pdf_file && str_contains($journalEdition->pdf_file, '/storage/journal-editions/pdf/')) {
            $oldPath = str_replace(Storage::disk('public')->url(''), '', $journalEdition->pdf_file);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        if ($journalEdition->cover_image && str_contains($journalEdition->cover_image, '/storage/journal-editions/covers/')) {
            $oldPath = str_replace(Storage::disk('public')->url(''), '', $journalEdition->cover_image);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $editionTitle = $journalEdition->title;
        $journalEdition->delete();

        return redirect()->route('admin.journal-editions.index')
            ->with('success', "Edição '{$editionTitle}' excluída com sucesso!");
    }
}
