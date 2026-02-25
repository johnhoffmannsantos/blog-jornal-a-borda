<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Se está alterando senha, validar apenas campos de senha
        if ($request->filled('current_password')) {
            $validated = $request->validate([
                'current_password' => ['required'],
                'password' => ['required', 'min:8', 'confirmed'],
            ], [
                'current_password.required' => 'A senha atual é obrigatória.',
                'password.required' => 'A nova senha é obrigatória.',
                'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
                'password.confirmed' => 'A confirmação da senha não confere.',
            ]);

            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Senha atual incorreta'])->withInput();
            }

            $user->password = Hash::make($validated['password']);
        }
        // Se é apenas upload/remoção de avatar
        elseif ($request->hasFile('avatar') || $request->has('remove_avatar')) {
            $validated = $request->validate([
                'avatar' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
                'remove_avatar' => ['nullable', 'boolean'],
            ], [
                'avatar.image' => 'O arquivo deve ser uma imagem.',
                'avatar.mimes' => 'A imagem deve ser do tipo: jpeg, jpg, png, gif ou webp.',
                'avatar.max' => 'A imagem não pode ter mais de 2MB.',
            ]);

            // Remover avatar se solicitado
            if ($request->has('remove_avatar') && $request->remove_avatar) {
                if ($user->avatar && str_contains($user->avatar, '/storage/avatars/')) {
                    $oldPath = str_replace(Storage::disk('public')->url(''), '', $user->avatar);
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
                $user->avatar = null;
            }
            // Processar upload de avatar se houver
            elseif ($request->hasFile('avatar')) {
                // Deletar avatar antigo se existir e for do nosso storage
                if ($user->avatar && str_contains($user->avatar, '/storage/avatars/')) {
                    $oldPath = str_replace(Storage::disk('public')->url(''), '', $user->avatar);
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
                
                $file = $request->file('avatar');
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('avatars', $filename, 'public');
                $user->avatar = Storage::disk('public')->url($path);
            }
        } else {
            // Se não está alterando senha nem avatar, validar e atualizar informações pessoais
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'bio' => ['nullable', 'string', 'max:500'],
                'position' => ['nullable', 'string', 'max:255'],
            ], [
                'name.required' => 'O nome é obrigatório.',
                'name.max' => 'O nome não pode ter mais de 255 caracteres.',
                'email.required' => 'O email é obrigatório.',
                'email.email' => 'O email deve ser válido.',
                'email.max' => 'O email não pode ter mais de 255 caracteres.',
                'email.unique' => 'Este email já está em uso.',
                'bio.max' => 'A biografia não pode ter mais de 500 caracteres.',
                'position.max' => 'O cargo não pode ter mais de 255 caracteres.',
            ]);

            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->bio = $validated['bio'] ?? null;
            $user->position = $validated['position'] ?? null;
        }

        $user->save();

        $message = 'Perfil atualizado com sucesso!';
        if ($request->hasFile('avatar')) {
            $message = 'Foto de perfil atualizada com sucesso!';
        } elseif ($request->has('remove_avatar')) {
            $message = 'Foto de perfil removida com sucesso!';
        }

        return redirect()->route('admin.profile')->with('success', $message);
    }
}
