@extends('layouts.admin')

@section('title', 'Criar Usuário - Painel Administrativo')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>
            <i class="bi bi-person-plus me-2"></i>Criar Novo Usuário
        </h1>
        <p>Preencha os campos abaixo para criar um novo usuário</p>
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
            <form method="POST" action="{{ route('admin.users.store') }}" id="userForm">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label fw-semibold">Nome *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label fw-semibold">Email *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
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
                            <option value="">Selecione uma role</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="editor" {{ old('role') === 'editor' ? 'selected' : '' }}>Editor</option>
                            <option value="author" {{ old('role') === 'author' ? 'selected' : '' }}>Autor</option>
                            <option value="reviewer" {{ old('role') === 'reviewer' ? 'selected' : '' }}>Revisor</option>
                            <option value="social_media" {{ old('role') === 'social_media' ? 'selected' : '' }}>Social Media</option>
                            <option value="communication" {{ old('role') === 'communication' ? 'selected' : '' }}>Comunicação</option>
                            <option value="designer" {{ old('role') === 'designer' ? 'selected' : '' }}>Designer</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="position" class="form-label fw-semibold">Cargo</label>
                        <input type="text" class="form-control @error('position') is-invalid @enderror" 
                               id="position" name="position" value="{{ old('position') }}" 
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
                              placeholder="Conte um pouco sobre este usuário...">{{ old('bio') }}</textarea>
                    <small class="text-muted">Máximo 500 caracteres</small>
                    @error('bio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="avatar" class="form-label fw-semibold">URL do Avatar</label>
                    <input type="url" class="form-control @error('avatar') is-invalid @enderror" 
                           id="avatar" name="avatar" value="{{ old('avatar') }}" 
                           placeholder="https://exemplo.com/avatar.jpg">
                    @error('avatar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Senha</h5>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Importante:</strong> A senha é obrigatória para criar um novo usuário. Mínimo de 8 caracteres.
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label fw-semibold">Senha *</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required
                               placeholder="Mínimo 8 caracteres">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirmar Senha *</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required
                               placeholder="Confirme a senha">
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Criar Usuário
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="admin-card">
            <div class="card-header">
                <h5>
                    <i class="bi bi-info-circle me-2"></i>Informações
                </h5>
            </div>
            <div class="py-3">
                <div class="mb-3 pb-3 border-bottom">
                    <p class="mb-1 text-muted small">Roles Disponíveis</p>
                    <ul class="small mb-0 ps-3">
                        <li><strong>Administrador:</strong> Acesso total ao sistema</li>
                        <li><strong>Editor:</strong> Pode ver e criar posts</li>
                        <li><strong>Autor:</strong> Pode ver e criar próprios posts</li>
                        <li><strong>Revisor:</strong> Revisa conteúdo</li>
                        <li><strong>Social Media:</strong> Gerencia redes sociais</li>
                        <li><strong>Comunicação:</strong> Comunicação externa</li>
                        <li><strong>Designer:</strong> Design e layout</li>
                    </ul>
                </div>
                <div class="mb-0">
                    <p class="mb-1 text-muted small">Dicas</p>
                    <ul class="small mb-0 ps-3">
                        <li>O email deve ser único no sistema</li>
                        <li>A senha deve ter no mínimo 8 caracteres</li>
                        <li>O avatar pode ser uma URL de imagem</li>
                        <li>Se não informar avatar, será gerado automaticamente</li>
                    </ul>
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
        // Pode adicionar preview aqui se necessário
    });
</script>
@endpush
@endsection

