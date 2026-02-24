<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private function checkAdmin()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado. Apenas administradores podem gerenciar usuários.');
        }
    }

    public function index(Request $request)
    {
        $this->checkAdmin();
        
        $query = User::withCount('posts');

        // Filtro por role
        if ($request->has('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }

        // Busca
        if ($request->has('search') && $request->search !== '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('name')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $this->checkAdmin();
        
        // Não permitir editar a si mesmo (deve usar o perfil)
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.profile')
                ->with('info', 'Para editar seu próprio perfil, use a página de Perfil.');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->checkAdmin();
        
        // Não permitir editar a si mesmo
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.profile')
                ->with('info', 'Para editar seu próprio perfil, use a página de Perfil.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'string', 'in:admin,editor,author,reviewer,social_media,communication,designer'],
            'position' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'url', 'max:500'],
            'password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->position = $validated['position'] ?? null;
        $user->bio = $validated['bio'] ?? null;
        $user->avatar = $validated['avatar'] ?? null;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        $this->checkAdmin();
        
        // Não permitir excluir a si mesmo
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Você não pode excluir sua própria conta.');
        }

        // Não permitir excluir se for o último admin
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Não é possível excluir o último administrador do sistema.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Usuário '{$userName}' excluído com sucesso!");
    }
}
