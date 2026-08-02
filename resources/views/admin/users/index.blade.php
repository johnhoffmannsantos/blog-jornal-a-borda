@extends('layouts.admin')

@section('title', 'Usuários - Painel Administrativo')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">
            <i class="bi bi-people me-2"></i>Usuários
        </h1>
        <p class="text-muted mb-0">Gerencie a equipe e permissões de acesso do portal</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Novo Usuário
    </a>
</div>

{{-- Mensagens serão exibidas via Toast --}}

<!-- Filtros de Busca, Setor e Role -->
<div class="admin-card p-3 mb-4">
    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label for="search" class="form-label fw-semibold">Buscar</label>
            <input type="text" class="form-control" id="search" name="search" 
                   value="{{ request('search') }}" placeholder="Nome, email ou cargo...">
        </div>
        <div class="col-md-3">
            <label for="department" class="form-label fw-semibold">Setor / Time</label>
            <select class="form-select" id="department" name="department" onchange="this.form.submit()">
                <option value="">Todos os Setores</option>
                <option value="Redação" {{ request('department') === 'Redação' ? 'selected' : '' }}>Redação</option>
                <option value="Fotografia" {{ request('department') === 'Fotografia' ? 'selected' : '' }}>Fotografia</option>
                <option value="Edição & Design" {{ request('department') === 'Edição & Design' ? 'selected' : '' }}>Edição & Design</option>
                <option value="Tecnologia" {{ request('department') === 'Tecnologia' ? 'selected' : '' }}>Tecnologia</option>
                <option value="Comercial & Marketing" {{ request('department') === 'Comercial & Marketing' ? 'selected' : '' }}>Comercial & Marketing</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="role" class="form-label fw-semibold">Role</label>
            <select class="form-select" id="role" name="role" onchange="this.form.submit()">
                <option value="">Todas as Roles</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
                <option value="editor" {{ request('role') === 'editor' ? 'selected' : '' }}>Editor</option>
                <option value="author" {{ request('role') === 'author' ? 'selected' : '' }}>Autor</option>
                <option value="reviewer" {{ request('role') === 'reviewer' ? 'selected' : '' }}>Revisor</option>
                <option value="social_media" {{ request('role') === 'social_media' ? 'selected' : '' }}>Social Media</option>
                <option value="communication" {{ request('role') === 'communication' ? 'selected' : '' }}>Comunicação</option>
                <option value="designer" {{ request('role') === 'designer' ? 'selected' : '' }}>Designer</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-secondary w-100">
                <i class="bi bi-funnel me-1"></i>Filtrar
            </button>
            @if(request()->hasAny(['search', 'role', 'department']))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary" title="Limpar Filtros">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Tabela de Usuários Categorizada por Setor -->
<div class="admin-card overflow-hidden">
    @forelse($groupedUsers as $department => $members)
        <div class="p-3 bg-light border-bottom d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-bold text-primary">
                <i class="bi bi-building me-2"></i>{{ $department }}
            </h6>
            <span class="badge bg-primary rounded-pill">{{ $members->count() }} {{ $members->count() === 1 ? 'membro' : 'membros' }}</span>
        </div>

        <div class="table-responsive mb-3">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 8%;">Avatar</th>
                        <th style="width: 22%;">Nome</th>
                        <th style="width: 20%;">Email</th>
                        <th style="width: 15%;">Role</th>
                        <th style="width: 12%;">Cargo</th>
                        <th style="width: 8%;">Posts</th>
                        <th style="width: 5%;">Ativo</th>
                        <th style="width: 5%;" class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $user)
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
                            @php
                                $roleLabel = match ($user->role) {
                                    'admin' => 'Administrador',
                                    'editor' => 'Editor',
                                    'author' => 'Autor',
                                    'reviewer' => 'Revisor',
                                    'social_media' => 'Social Media',
                                    'communication' => 'Comunicação',
                                    'designer' => 'Designer',
                                    default => ucfirst((string) $user->role),
                                };
                            @endphp
                            <span class="role-badge {{ $user->role }}">{{ $roleLabel }}</span>
                        </td>
                        <td>
                            <small class="text-muted">{{ $user->position ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $user->posts_count ?? 0 }} post(s)</span>
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
                        <td class="text-end">
                            <div class="table-actions">
                                <a href="{{ route('admin.users.edit', $user) }}" class="action-icon action-icon--edit" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($user->id !== Auth::id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Tem certeza que deseja excluir o usuário {{ $user->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-icon action-icon--delete" title="Excluir">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="bi bi-people fs-1 text-muted mb-2 d-block"></i>
            <p class="text-muted mb-0">Nenhum usuário encontrado com os filtros selecionados.</p>
        </div>
    @endforelse
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Intercepta mudanças nos switches de ativar/desativar
    document.querySelectorAll('.user-active-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const userId = this.dataset.userId;
            const userName = this.dataset.userName;
            const isActive = this.checked;
            const toggleElement = this;
            
            toggleElement.disabled = true;
            
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
            
            const formData = new FormData();
            formData.append('_token', token);
            
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
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'Erro na requisição');
                    }
                    return data;
                } else {
                    const text = await response.text();
                    console.error('Resposta não é JSON:', text.substring(0, 200));
                    throw new Error('Resposta do servidor não é válida. Tente novamente.');
                }
            })
            .then(data => {
                toggleElement.disabled = false;
                
                if (data.success) {
                    const status = isActive ? 'ativado' : 'desativado';
                    if (typeof showToast === 'function') {
                        showToast('success', `Usuário '${userName}' ${status} com sucesso!`);
                    }
                } else {
                    toggleElement.checked = !isActive;
                    if (typeof showToast === 'function') {
                        showToast('error', data.message || 'Erro ao alterar status do usuário.');
                    }
                }
            })
            .catch(error => {
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