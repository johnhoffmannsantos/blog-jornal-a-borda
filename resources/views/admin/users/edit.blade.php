@extends('layouts.admin')

@section('title', 'Editar Usuário - Painel Administrativo')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>
            <i class="bi bi-pencil me-2"></i>Editar Usuário
        </h1>
        <p>Atualize as informações do usuário</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Voltar
    </a>
</div>

{{-- Mensagens serão exibidas via Toast --}}

<div class="row">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="card-header">
                <h5>
                    <i class="bi bi-person me-2"></i>Informações do Usuário
                </h5>
            </div>
            <form method="POST" action="{{ route('admin.users.update', $user) }}" id="userForm">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label fw-semibold">Nome *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label fw-semibold">Email *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="role" class="form-label fw-semibold">Role *</label>
                        <select class="form-select @error('role') is-invalid @enderror" 
                                id="role" name="role" required>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="editor" {{ old('role', $user->role) === 'editor' ? 'selected' : '' }}>Editor</option>
                            <option value="author" {{ old('role', $user->role) === 'author' ? 'selected' : '' }}>Autor</option>
                            <option value="reviewer" {{ old('role', $user->role) === 'reviewer' ? 'selected' : '' }}>Revisor</option>
                            <option value="social_media" {{ old('role', $user->role) === 'social_media' ? 'selected' : '' }}>Social Media</option>
                            <option value="communication" {{ old('role', $user->role) === 'communication' ? 'selected' : '' }}>Comunicação</option>
                            <option value="designer" {{ old('role', $user->role) === 'designer' ? 'selected' : '' }}>Designer</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="position" class="form-label fw-semibold">Cargo</label>
                        <input type="text" class="form-control @error('position') is-invalid @enderror" 
                               id="position" name="position" value="{{ old('position', $user->position) }}" 
                               placeholder="Ex: Redatora, Editor, etc.">
                        @error('position')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="bio" class="form-label fw-semibold">Biografia</label>
                    <textarea class="form-control @error('bio') is-invalid @enderror" 
                              id="bio" name="bio" rows="4" 
                              placeholder="Conte um pouco sobre este usuário...">{{ old('bio', $user->bio) }}</textarea>
                    <small class="text-muted">Máximo 500 caracteres</small>
                    @error('bio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="avatar" class="form-label fw-semibold">URL do Avatar</label>
                    <input type="url" class="form-control @error('avatar') is-invalid @enderror" 
                           id="avatar" name="avatar" value="{{ old('avatar', $user->avatar) }}" 
                           placeholder="https://exemplo.com/avatar.jpg">
                    @error('avatar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Alterar Senha</h5>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Opcional:</strong> Deixe em branco para manter a senha atual.
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label fw-semibold">Nova Senha</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" 
                               placeholder="Mínimo 8 caracteres">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirmar Nova Senha</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" 
                               placeholder="Confirme a nova senha">
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Atualizar Usuário
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="admin-card">
            <div class="card-header">
                <h5>
                    <i class="bi bi-image me-2"></i>Foto de Perfil
                </h5>
            </div>
            <div class="text-center py-4">
                <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=200&background=e63946&color=fff' }}" 
                     alt="{{ $user->name }}" 
                     id="avatarPreview"
                     class="rounded-circle mb-3" 
                     style="width: 150px; height: 150px; object-fit: cover; border: 4px solid var(--border-color);">
                <p class="text-muted small mb-0">
                    @if($user->avatar)
                        Foto personalizada
                    @else
                        Avatar gerado automaticamente
                    @endif
                </p>
            </div>
        </div>

        <div class="admin-card mt-4">
            <div class="card-header">
                <h5>
                    <i class="bi bi-info-circle me-2"></i>Informações da Conta
                </h5>
            </div>
            <div class="py-3">
                <div class="mb-3 pb-3 border-bottom">
                    <p class="mb-1 text-muted small">Role Atual</p>
                    <p class="mb-0">
                        <span class="role-badge {{ $user->role }}">{{ ucfirst($user->role) }}</span>
                    </p>
                </div>
                <div class="mb-3 pb-3 border-bottom">
                    <p class="mb-1 text-muted small">Membro desde</p>
                    <p class="mb-0 fw-semibold">{{ $user->created_at->format('d/m/Y') }}</p>
                </div>
                <div class="mb-0">
                    <p class="mb-1 text-muted small">Total de Posts</p>
                    <p class="mb-0 fw-semibold">{{ $user->posts()->count() }} posts publicados</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview do avatar
    document.getElementById('avatar').addEventListener('input', function() {
        const url = this.value;
        const preview = document.getElementById('avatarPreview');
        
        if (url && url.startsWith('http')) {
            preview.src = url;
        } else if (!url) {
            preview.src = 'https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=200&background=e63946&color=fff';
        }
    });
</script>
@endpush
@endsection

