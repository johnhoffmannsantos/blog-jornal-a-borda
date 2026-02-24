@extends('layouts.admin')

@section('title', 'Perfil - Painel Administrativo')

@section('content')
<div class="page-header">
    <h1>
        <i class="bi bi-person me-2"></i>Meu Perfil
    </h1>
    <p>Gerencie suas informações pessoais</p>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="card-header">
                <h5>Informações Pessoais</h5>
            </div>
            <form method="POST" action="{{ route('admin.profile.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nome</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="position" class="form-label fw-semibold">Cargo</label>
                    <input type="text" class="form-control @error('position') is-invalid @enderror" 
                           id="position" name="position" value="{{ old('position', $user->position) }}" 
                           placeholder="Ex: Redatora, Editor, etc.">
                    @error('position')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="bio" class="form-label fw-semibold">Biografia</label>
                    <textarea class="form-control @error('bio') is-invalid @enderror" 
                              id="bio" name="bio" rows="4" 
                              placeholder="Conte um pouco sobre você...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Alterar Senha</h5>

                <div class="mb-3">
                    <label for="current_password" class="form-label fw-semibold">Senha Atual</label>
                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                           id="current_password" name="current_password">
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Deixe em branco se não quiser alterar a senha</small>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Nova Senha</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold">Confirmar Nova Senha</label>
                    <input type="password" class="form-control" 
                           id="password_confirmation" name="password_confirmation">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>Salvar Alterações
                </button>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="admin-card">
            <div class="card-header">
                <h5>Foto de Perfil</h5>
            </div>
            <div class="text-center">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=200&background=e63946&color=fff" 
                     class="rounded-circle mb-3" style="width: 150px; height: 150px; border: 4px solid var(--border-color);">
                <p class="text-muted small mb-0">A foto é gerada automaticamente com base no seu nome</p>
            </div>
        </div>

        <div class="admin-card mt-4">
            <div class="card-header">
                <h5>Informações da Conta</h5>
            </div>
            <div>
                <p class="mb-2">
                    <strong>Role:</strong> 
                    <span class="role-badge {{ $user->role }}">{{ ucfirst($user->role) }}</span>
                </p>
                <p class="mb-2">
                    <strong>Membro desde:</strong><br>
                    <small class="text-muted">{{ $user->created_at->format('d/m/Y') }}</small>
                </p>
                <p class="mb-0">
                    <strong>Total de Posts:</strong><br>
                    <small class="text-muted">{{ $user->posts()->count() }} posts publicados</small>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

