@extends('layouts.admin')

@section('title', 'Usuários - Painel Administrativo')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>
            <i class="bi bi-people me-2"></i>Usuários
        </h1>
        <p>Gerencie os usuários do sistema</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Novo Usuário
    </a>
</div>

{{-- Mensagens serão exibidas via Toast --}}

<div class="admin-card mb-4">
    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label for="search" class="form-label">Buscar</label>
            <input type="text" class="form-control" id="search" name="search" 
                   value="{{ request('search') }}" placeholder="Nome ou email...">
        </div>
        <div class="col-md-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role">
                <option value="">Todos</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
                <option value="editor" {{ request('role') === 'editor' ? 'selected' : '' }}>Editor</option>
                <option value="author" {{ request('role') === 'author' ? 'selected' : '' }}>Autor</option>
                <option value="reviewer" {{ request('role') === 'reviewer' ? 'selected' : '' }}>Revisor</option>
                <option value="social_media" {{ request('role') === 'social_media' ? 'selected' : '' }}>Social Media</option>
                <option value="communication" {{ request('role') === 'communication' ? 'selected' : '' }}>Comunicação</option>
                <option value="designer" {{ request('role') === 'designer' ? 'selected' : '' }}>Designer</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-secondary w-100">
                <i class="bi bi-funnel me-2"></i>Filtrar
            </button>
        </div>
        <div class="col-md-3 text-end">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-clockwise me-2"></i>Limpar Filtros
            </a>
        </div>
    </form>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Avatar</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Cargo</th>
                    <th>Posts</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>
                        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=64&background=e63946&color=fff' }}" 
                             alt="{{ $user->name }}" 
                             class="rounded-circle" 
                             style="width: 40px; height: 40px; object-fit: cover;">
                    </td>
                    <td>
                        <strong>{{ $user->name }}</strong>
                        @if($user->id === Auth::id())
                            <span class="badge bg-info ms-2">Você</span>
                        @endif
                    </td>
                    <td>
                        <small>{{ $user->email }}</small>
                    </td>
                    <td>
                        <span class="role-badge {{ $user->role }}">{{ ucfirst($user->role) }}</span>
                    </td>
                    <td>
                        <small class="text-muted">{{ $user->position ?? '-' }}</small>
                    </td>
                    <td>
                        <span class="badge bg-secondary">{{ $user->posts_count }} post(s)</span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($user->id !== Auth::id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" 
                                  onsubmit="return confirm('Tem certeza que deseja excluir o usuário {{ $user->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <p class="text-muted mb-0">Nenhum usuário encontrado.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection

