@extends('layouts.admin')

@section('title', 'Perfil - Painel Administrativo')

@section('content')
<div class="page-header">
    <h1>
        <i class="bi bi-person me-2"></i>Meu Perfil
    </h1>
    <p>Gerencie suas informações pessoais e configurações da conta</p>
</div>

{{-- Mensagens serão exibidas via Toast --}}

<div class="row">
    <div class="col-lg-8">
        <div class="admin-card">
            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" 
                            type="button" role="tab" aria-controls="personal" aria-selected="true">
                        <i class="bi bi-person me-2"></i>Informações Pessoais
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" 
                            type="button" role="tab" aria-controls="password" aria-selected="false">
                        <i class="bi bi-lock me-2"></i>Alterar Senha
                    </button>
                </li>
            </ul>

            <!-- Tabs Content -->
            <div class="tab-content" id="profileTabsContent">
                <!-- Tab 1: Informações Pessoais -->
                <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                    <form method="POST" action="{{ route('admin.profile.update') }}" id="personalForm">
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

                        <div class="mb-3">
                            <label for="position" class="form-label fw-semibold">Cargo</label>
                            <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                   id="position" name="position" value="{{ old('position', $user->position) }}" 
                                   placeholder="Ex: Redatora, Editor, etc.">
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="bio" class="form-label fw-semibold">Biografia</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                      id="bio" name="bio" rows="5" 
                                      placeholder="Conte um pouco sobre você...">{{ old('bio', $user->bio) }}</textarea>
                            <small class="text-muted">Máximo 500 caracteres</small>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Salvar Informações
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tab 2: Alterar Senha -->
                <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                    <form method="POST" action="{{ route('admin.profile.update') }}" id="passwordForm">
                        @csrf
                        @method('PUT')

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Importante:</strong> Para alterar sua senha, você precisa informar a senha atual.
                        </div>

                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-semibold">Senha Atual *</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Nova Senha *</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            <small class="text-muted">Mínimo de 8 caracteres</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">Confirmar Nova Senha *</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-key me-2"></i>Alterar Senha
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
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
                    <p class="mb-1 text-muted small">Role</p>
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

@push('styles')
<style>
    .nav-tabs {
        border-bottom: 2px solid var(--border-color);
    }

    .nav-tabs .nav-link {
        color: var(--text-light);
        border: none;
        border-bottom: 3px solid transparent;
        padding: 12px 20px;
        transition: all 0.3s;
    }

    .nav-tabs .nav-link:hover {
        border-bottom-color: var(--primary-color);
        color: var(--primary-color);
    }

    .nav-tabs .nav-link.active {
        color: var(--primary-color);
        background: transparent;
        border-bottom-color: var(--primary-color);
        font-weight: 600;
    }

    .tab-content {
        padding: 20px 0;
    }

    .tab-pane {
        animation: fadeIn 0.3s;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush
@endsection
