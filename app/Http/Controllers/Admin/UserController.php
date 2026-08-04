<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

        // 1. Filtro por Role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // 2. Filtro por Setor / Departamento (department)
        if ($request->filled('department')) {
            $departmentParam = trim($request->department);

            if ($departmentParam === 'Sem Setor') {
                $query->where(function($q) {
                    $q->whereNull('department')
                      ->orWhereRaw("TRIM(department) = ''")
                      ->orWhereRaw("TRIM(department) = 'Sem Setor'");
                });
            } else {
                // Filtra no SQL ignorando espaços acidentais no início/fim da coluna
                $query->whereRaw("TRIM(department) = ?", [$departmentParam]);
            }
        }

        // 3. Busca por termo (nome, email ou cargo)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('department')->orderBy('name')->get();

        // Agrupa os usuários filtrados por Setor/Time para renderização nas tabelas do painel
        $usersByDepartment = $users->groupBy(function ($user) {
            $dept = trim($user->department ?? '');
            return !empty($dept) ? $dept : 'Sem Setor';
        });

        return view('admin.users.index', compact('users', 'usersByDepartment'));
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
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'role'        => ['required', 'string', 'in:admin,editor,author,reviewer,social_media,communication,designer'],
            'department'  => ['nullable', 'string', 'max:100'],
            'position'    => ['nullable', 'string', 'max:255'],
            'bio'         => ['nullable', 'string', 'max:2000'],
            'avatar'      => ['nullable', 'url', 'max:500'],
        ]);

        $user = User::create([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'role'       => $validated['role'],
            'department' => !empty($validated['department']) ? trim($validated['department']) : null,
            'position'   => $validated['position'] ?? null,
            'bio'        => $validated['bio'] ?? null,
            'avatar'     => $validated['avatar'] ?? null,
            'is_active'  => true,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Usuário '{$user->name}' criado com sucesso!");
    }

    public function edit(User $user)
    {
        $this->checkAdmin();
        
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.profile')
                ->with('info', 'Para editar seu próprio perfil, use a página de Perfil.');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->checkAdmin();
        
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.profile')
                ->with('info', 'Para editar seu próprio perfil, use a página de Perfil.');
        }

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'        => ['required', 'string', 'in:admin,editor,author,reviewer,social_media,communication,designer'],
            'department'  => ['nullable', 'string', 'max:100'],
            'position'    => ['nullable', 'string', 'max:255'],
            'bio'         => ['nullable', 'string', 'max:2000'],
            'avatar'      => ['nullable', 'url', 'max:500'],
            'avatar_file' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
            'password'    => ['nullable', 'min:8', 'confirmed'],
        ]);

        $user->name       = $validated['name'];
        $user->email      = $validated['email'];
        $user->role       = $validated['role'];
        $user->department = !empty($validated['department']) ? trim($validated['department']) : null;
        $user->position   = $validated['position'] ?? null;
        $user->bio        = $validated['bio'] ?? null;
        $user->avatar     = $validated['avatar'] ?? null;

        if ($request->hasFile('avatar_file')) {
            if ($user->avatar && str_contains($user->avatar, '/storage/users/avatars/')) {
                $oldPath = str_replace(Storage::disk('public')->url(''), '', $user->avatar);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $path = $request->file('avatar_file')->store('users/avatars', 'public');
            $user->avatar = Storage::disk('public')->url($path);
        }

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
        
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Você não pode excluir sua própria conta.');
        }

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
        
        $isAjax = request()->expectsJson() || request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest';
        
        if ($user->id === Auth::id()) {
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => 'Você não pode desativar sua própria conta.'], 403);
            }
            return back()->with('error', 'Você não pode desativar sua própria conta.');
        }

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
            
            if ($isAjax) {
                return response()->json([
                    'success'   => true,
                    'message'   => "Usuário '{$user->name}' {$status} com sucesso!",
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