@extends('layouts.admin')

@section('title', 'Configurações - Painel Administrativo')

@section('content')
<div class="page-header">
    <h1>
        <i class="bi bi-gear me-2"></i>Configurações
    </h1>
    <p>Gerencie as configurações gerais do site e SMTP</p>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('info'))
<div class="alert alert-info alert-dismissible fade show" role="alert">
    <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    @method('PUT')

    <!-- Configurações do Site -->
    <div class="admin-card mb-4">
        <div class="card-header">
            <h5>
                <i class="bi bi-globe me-2"></i>Configurações do Site
            </h5>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="site_name" class="form-label fw-semibold">Nome do Site *</label>
                <input type="text" class="form-control @error('site_name') is-invalid @enderror" 
                       id="site_name" name="site_name" 
                       value="{{ old('site_name', $siteSettings['site_name'] ?? 'Jornal a Borda') }}" required>
                @error('site_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="site_email" class="form-label fw-semibold">Email de Contato *</label>
                <input type="email" class="form-control @error('site_email') is-invalid @enderror" 
                       id="site_email" name="site_email" 
                       value="{{ old('site_email', $siteSettings['site_email'] ?? 'contato@jornalaborda.com.br') }}" required>
                @error('site_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 mb-3">
                <label for="site_description" class="form-label fw-semibold">Descrição do Site *</label>
                <textarea class="form-control @error('site_description') is-invalid @enderror" 
                          id="site_description" name="site_description" rows="3" required>{{ old('site_description', $siteSettings['site_description'] ?? 'A Voz das Periferias de Osasco') }}</textarea>
                @error('site_description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Configurações SMTP -->
    <div class="admin-card mb-4">
        <div class="card-header">
            <h5>
                <i class="bi bi-envelope me-2"></i>Configurações de Email (SMTP)
            </h5>
            <small class="text-muted">Configure as credenciais SMTP para envio de emails</small>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="mail_mailer" class="form-label fw-semibold">Driver de Email *</label>
                <select class="form-select @error('mail_mailer') is-invalid @enderror" 
                        id="mail_mailer" name="mail_mailer" required>
                    <option value="smtp" {{ old('mail_mailer', $smtpSettings['mail_mailer'] ?? 'smtp') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                    <option value="sendmail" {{ old('mail_mailer', $smtpSettings['mail_mailer'] ?? '') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                    <option value="log" {{ old('mail_mailer', $smtpSettings['mail_mailer'] ?? '') === 'log' ? 'selected' : '' }}>Log (Desenvolvimento)</option>
                </select>
                @error('mail_mailer')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="mail_host" class="form-label fw-semibold">Servidor SMTP</label>
                <input type="text" class="form-control @error('mail_host') is-invalid @enderror" 
                       id="mail_host" name="mail_host" 
                       value="{{ old('mail_host', $smtpSettings['mail_host'] ?? '') }}" 
                       placeholder="smtp.gmail.com">
                @error('mail_host')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label for="mail_port" class="form-label fw-semibold">Porta SMTP</label>
                <input type="number" class="form-control @error('mail_port') is-invalid @enderror" 
                       id="mail_port" name="mail_port" 
                       value="{{ old('mail_port', $smtpSettings['mail_port'] ?? '587') }}" 
                       placeholder="587">
                <small class="text-muted">Porta padrão: 587 (TLS) ou 465 (SSL)</small>
                @error('mail_port')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label for="mail_encryption" class="form-label fw-semibold">Criptografia</label>
                <select class="form-select @error('mail_encryption') is-invalid @enderror" 
                        id="mail_encryption" name="mail_encryption">
                    <option value="tls" {{ old('mail_encryption', $smtpSettings['mail_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS</option>
                    <option value="ssl" {{ old('mail_encryption', $smtpSettings['mail_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                </select>
                @error('mail_encryption')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label for="mail_username" class="form-label fw-semibold">Usuário SMTP</label>
                <input type="text" class="form-control @error('mail_username') is-invalid @enderror" 
                       id="mail_username" name="mail_username" 
                       value="{{ old('mail_username', $smtpSettings['mail_username'] ?? '') }}" 
                       placeholder="seu@email.com">
                @error('mail_username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="mail_password" class="form-label fw-semibold">Senha SMTP</label>
                <input type="password" class="form-control @error('mail_password') is-invalid @enderror" 
                       id="mail_password" name="mail_password" 
                       value="{{ old('mail_password', $smtpSettings['mail_password'] ?? '') }}" 
                       placeholder="••••••••">
                <small class="text-muted">Deixe em branco para manter a senha atual</small>
                @error('mail_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="mail_from_address" class="form-label fw-semibold">Email Remetente *</label>
                <input type="email" class="form-control @error('mail_from_address') is-invalid @enderror" 
                       id="mail_from_address" name="mail_from_address" 
                       value="{{ old('mail_from_address', $smtpSettings['mail_from_address'] ?? 'noreply@jornalaborda.com.br') }}" 
                       required>
                @error('mail_from_address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="mail_from_name" class="form-label fw-semibold">Nome do Remetente *</label>
                <input type="text" class="form-control @error('mail_from_name') is-invalid @enderror" 
                       id="mail_from_name" name="mail_from_name" 
                       value="{{ old('mail_from_name', $smtpSettings['mail_from_name'] ?? 'Jornal a Borda') }}" 
                       required>
                @error('mail_from_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Teste de Email -->
        <div class="mt-4 pt-4 border-top">
            <h6 class="mb-3">Testar Configuração de Email</h6>
            <form method="POST" action="{{ route('admin.settings.testEmail') }}" class="row">
                @csrf
                <div class="col-md-8">
                    <input type="email" class="form-control" name="test_email" 
                           placeholder="Digite um email para testar o envio" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="bi bi-send me-2"></i>Enviar Email de Teste
                    </button>
                </div>
            </form>
            <small class="text-muted d-block mt-2">
                <i class="bi bi-info-circle me-1"></i>Um email de teste será enviado para verificar se as configurações SMTP estão corretas.
            </small>
        </div>
    </div>


    <!-- Botão Salvar -->
    <div class="mt-4">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-save me-2"></i>Salvar Todas as Configurações
        </button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-lg ms-2">
            <i class="bi bi-arrow-left me-2"></i>Voltar
        </a>
    </div>
</form>
@endsection
