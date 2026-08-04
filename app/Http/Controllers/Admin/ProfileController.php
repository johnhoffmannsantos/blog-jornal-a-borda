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

        // Se está alterando senha
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
        // Se é upload ou remoção de avatar
        elseif ($request->hasFile('avatar') || $request->has('remove_avatar')) {
            $validated = $request->validate([
                'avatar' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
                'remove_avatar' => ['nullable', 'boolean'],
            ], [
                'avatar.image' => 'O arquivo deve ser uma imagem.',
                'avatar.mimes' => 'A imagem deve ser do tipo: jpeg, jpg, png, gif ou webp.',
                'avatar.max' => 'A imagem não pode ter mais de 2MB.',
            ]);

            // Remover avatar antigo se solicitado
            if ($request->has('remove_avatar') && $request->remove_avatar) {
                $this->deleteOldAvatar($user->avatar);
                $user->avatar = null;
            }
            // Processar upload de avatar
            elseif ($request->hasFile('avatar')) {
                $this->deleteOldAvatar($user->avatar);

                $file = $request->file('avatar');
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('users/avatars', $filename, 'public');
                $user->avatar = Storage::disk('public')->url($path);
            }
        } else {
            // Atualizar dados do perfil (Nome, Email, Setor, Cargo, Bio)
            $validated = $request->validate([
                'name'       => ['required', 'string', 'max:255'],
                'email'      => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'department' => ['nullable', 'string', 'max:100'],
                'position'   => ['nullable', 'string', 'max:255'],
                'bio'        => ['nullable', 'string', 'max:2000'],
            ], [
                'name.required'  => 'O nome é obrigatório.',
                'name.max'       => 'O nome não pode ter mais de 255 caracteres.',
                'email.required' => 'O email é obrigatório.',
                'email.email'    => 'O email deve ser válido.',
                'email.max'      => 'O email não pode ter mais de 255 caracteres.',
                'email.unique'   => 'Este email já está em uso.',
                'department.max' => 'O setor não pode ter mais de 100 caracteres.',
                'position.max'   => 'O cargo não pode ter mais de 255 caracteres.',
                'bio.max'        => 'A biografia não pode ter mais de 2000 caracteres.',
            ]);

            $user->name       = $validated['name'];
            $user->email      = $validated['email'];
            $user->department = $validated['department'] ?? null;
            $user->position   = $validated['position'] ?? null;
            $user->bio        = $validated['bio'] ?? null;
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

    /**
     * Auxiliar para excluir o avatar antigo do storage se ele existir.
     */
    private function deleteOldAvatar(?string $avatarUrl): void
    {
        if ($avatarUrl && (str_contains($avatarUrl, '/storage/users/avatars/') || str_contains($avatarUrl, '/storage/avatars/'))) {
            $oldPath = str_replace(Storage::disk('public')->url(''), '', $avatarUrl);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }
    }
}