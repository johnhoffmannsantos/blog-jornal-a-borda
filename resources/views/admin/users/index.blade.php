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
                    <th>Ativo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>
                        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=64&background=1A25FF&color=fff' }}" 
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
                        @if($user->id !== Auth::id())
                        <div class="form-check form-switch">
                            <input class="form-check-input user-active-toggle" 
                                   type="checkbox" 
                                   role="switch" 
                                   id="toggle_{{ $user->id }}"
                                   data-user-id="{{ $user->id }}"
                                   data-user-name="{{ $user->name }}"
                                   {{ $user->is_active ? 'checked' : '' }}
                                   style="cursor: pointer; width: 3em; height: 1.5em;">
                            <label class="form-check-label" for="toggle_{{ $user->id }}" style="cursor: pointer;">
                                <span class="visually-hidden">Ativar/Desativar usuário</span>
                            </label>
                        </div>
                        @else
                        <span class="badge bg-info">Você</span>
                        @endif
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
                    <td colspan="9" class="text-center py-4">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Interceptar mudanças nos switches de ativar/desativar
    document.querySelectorAll('.user-active-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const userId = this.dataset.userId;
            const userName = this.dataset.userName;
            const isActive = this.checked;
            const toggleElement = this;
            
            // Desabilitar o toggle durante a requisição
            toggleElement.disabled = true;
            
            // Obter CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            const token = csrfToken ? csrfToken.getAttribute('content') : '';
            
            if (!token) {
                toggleElement.disabled = false;
                toggleElement.checked = !isActive;
                if (typeof showToast === 'function') {
                    showToast('error', 'Token CSRF não encontrado. Recarregue a página.');
                }
                return;
            }
            
            // Criar form data
            const formData = new FormData();
            formData.append('_token', token);
            
            // Fazer requisição AJAX
            fetch(`/painel/users/${userId}/toggle-active`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                credentials: 'same-origin'
            })
            .then(async response => {
                // Verificar se a resposta é JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'Erro na requisição');
                    }
                    return data;
                } else {
                    // Se não for JSON, tentar ler como texto para debug
                    const text = await response.text();
                    console.error('Resposta não é JSON:', text.substring(0, 200));
                    throw new Error('Resposta do servidor não é válida. Tente novamente.');
                }
            })
            .then(data => {
                // Reabilitar o toggle
                toggleElement.disabled = false;
                
                if (data.success) {
                    const status = isActive ? 'ativado' : 'desativado';
                    if (typeof showToast === 'function') {
                        showToast('success', `Usuário '${userName}' ${status} com sucesso!`);
                    }
                } else {
                    // Reverter o estado do toggle em caso de erro
                    toggleElement.checked = !isActive;
                    if (typeof showToast === 'function') {
                        showToast('error', data.message || 'Erro ao alterar status do usuário.');
                    }
                }
            })
            .catch(error => {
                // Reabilitar o toggle e reverter estado
                toggleElement.disabled = false;
                toggleElement.checked = !isActive;
                console.error('Erro:', error);
                if (typeof showToast === 'function') {
                    showToast('error', error.message || 'Erro ao alterar status do usuário. Tente novamente.');
                }
            });
        });
    });
});
</script>
@endpush
@endsection

