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
            
            // Retornar URL da imagem para o TinyMCE
            return response()->json([
                'location' => Storage::disk('public')->url($path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao fazer upload da imagem: ' . $e->getMessage()
            ], 500);
        }
    }
}
