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

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
            'pdf_file' => ['required', 'file', 'mimes:pdf', 'max:10240'], // 10MB
            'published_date' => ['required', 'date'],
            'edition_number' => ['nullable', 'integer', 'min:1'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        // Upload do PDF
        $pdfPath = null;
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $filename = Str::slug($validated['title']) . '-' . time() . '.pdf';
            $pdfPath = $file->storeAs('journal-editions/pdf', $filename, 'public');
        }

        // Upload da capa
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $coverPath = $file->storeAs('journal-editions/covers', $filename, 'public');
        }

        JournalEdition::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'] ?? null,
            'cover_image' => $coverPath ? Storage::disk('public')->url($coverPath) : null,
            'pdf_file' => $pdfPath ? Storage::disk('public')->url($pdfPath) : null,
            'published_date' => $validated['published_date'],
            'edition_number' => $validated['edition_number'] ?? null,
            'is_featured' => $request->has('is_featured'),
        ]);

        return redirect()->route('admin.journal-editions.index')
            ->with('success', 'Edição do jornal criada com sucesso!');
    }

    public function edit(JournalEdition $journalEdition)
    {
        $this->checkAdmin();
        
        return view('admin.journal-editions.edit', compact('journalEdition'));
    }

    public function update(Request $request, JournalEdition $journalEdition)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
            'pdf_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'published_date' => ['required', 'date'],
            'edition_number' => ['nullable', 'integer', 'min:1'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

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

        $journalEdition->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'] ?? null,
            'cover_image' => $coverPath,
            'pdf_file' => $pdfPath,
            'published_date' => $validated['published_date'],
            'edition_number' => $validated['edition_number'] ?? null,
            'is_featured' => $request->has('is_featured'),
        ]);

        return redirect()->route('admin.journal-editions.index')
            ->with('success', 'Edição do jornal atualizada com sucesso!');
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
