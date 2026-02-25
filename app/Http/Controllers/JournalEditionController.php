<?php

namespace App\Http\Controllers;

use App\Models\JournalEdition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JournalEditionController extends Controller
{
    public function index()
    {
        $editions = JournalEdition::orderBy('published_date', 'desc')
            ->orderBy('edition_number', 'desc')
            ->paginate(12);

        return view('journal-editions.index', compact('editions'));
    }

    public function show($slug)
    {
        $edition = JournalEdition::where('slug', $slug)->firstOrFail();
        
        // Incrementar visualizações
        $edition->incrementViews();

        return view('journal-editions.show', compact('edition'));
    }

    public function download($slug)
    {
        $edition = JournalEdition::where('slug', $slug)->firstOrFail();
        
        // Incrementar downloads
        $edition->incrementDownloads();

        // Obter caminho do arquivo
        $filePath = str_replace(Storage::disk('public')->url(''), '', $edition->pdf_file);
        
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->download($filePath, $edition->title . '.pdf');
        }

        abort(404, 'Arquivo não encontrado');
    }
}
