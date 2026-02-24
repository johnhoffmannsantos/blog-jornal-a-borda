<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            ]);

            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Senha atual incorreta'])->withInput();
            }

            $user->password = Hash::make($validated['password']);
        } else {
            // Se não está alterando senha, validar e atualizar informações pessoais
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'bio' => ['nullable', 'string', 'max:500'],
                'position' => ['nullable', 'string', 'max:255'],
            ]);

            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->bio = $validated['bio'] ?? null;
            $user->position = $validated['position'] ?? null;
        }

        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Perfil atualizado com sucesso!');
    }
}
