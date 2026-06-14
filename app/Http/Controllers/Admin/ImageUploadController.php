<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        // Verificar autenticação
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Não autorizado'
            ], 401);
        }

        // Validar arquivo
        $request->validate([
            'file' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120', // 5MB max
        ]);

        try {
            $file = $request->file('file');
            
            // Gerar nome único para o arquivo
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // Salvar no storage público
            $path = $file->storeAs('posts/images', $filename, 'public');
            
            // ✨ SOLUÇÃO DEFINITIVA: Retornar URL relativa para o TinyMCE (/storage/...)
            // Remove o "http://localhost" ou o domínio oficial, salvando apenas o caminho universal
            $location = '/storage/' . $path;
            
            return response()->json([
                'location' => $location
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao fazer upload da imagem: ' . $e->getMessage()
            ], 500);
        }
    }
}