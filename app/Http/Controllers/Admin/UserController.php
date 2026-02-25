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

    public function create()
    {
        $this->checkAdmin();
        
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:admin,editor,author,reviewer,social_media,communication,designer'],
            'position' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'url', 'max:500'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'position' => $validated['position'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'avatar' => $validated['avatar'] ?? null,
            'is_active' => true, // Novos usuários são criados como ativos por padrão
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Usuário '{$user->name}' criado com sucesso!");
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

    public function toggleActive(User $user)
    {
        $this->checkAdmin();
        
        // Verificar se é requisição AJAX/JSON
        $isAjax = request()->expectsJson() || request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest';
        
        // Não permitir desativar a si mesmo
        if ($user->id === Auth::id()) {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'Você não pode desativar sua própria conta.'], 403);
            }
            return back()->with('error', 'Você não pode desativar sua própria conta.');
        }

        // Não permitir desativar o último admin
        if ($user->isAdmin() && $user->is_active && User::where('role', 'admin')->where('is_active', true)->count() <= 1) {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'Não é possível desativar o último administrador ativo do sistema.'], 403);
            }
            return back()->with('error', 'Não é possível desativar o último administrador ativo do sistema.');
        }

        try {
            $user->is_active = !$user->is_active;
            $user->save();

            $status = $user->is_active ? 'ativado' : 'desativado';
            
            // Retornar JSON para requisições AJAX
            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => "Usuário '{$user->name}' {$status} com sucesso!",
                    'is_active' => $user->is_active
                ]);
            }
            
            return back()->with('success', "Usuário '{$user->name}' {$status} com sucesso!");
        } catch (\Exception $e) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao alterar status do usuário: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Erro ao alterar status do usuário.');
        }
    }
}
