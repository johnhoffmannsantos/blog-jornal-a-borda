@extends('layouts.admin')

@section('title', 'Configurações - Painel Administrativo')

@section('content')
<div class="page-header">
    <h1>
        <i class="bi bi-gear me-2"></i>Configurações
    </h1>
    <p>Gerencie as configurações gerais do site e SMTP</p>
</div>

{{-- Mensagens serão exibidas via Toast --}}

<div class="admin-card">
    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="site-tab" data-bs-toggle="tab" data-bs-target="#site" 
                    type="button" role="tab" aria-controls="site" aria-selected="true">
                <i class="bi bi-globe me-2"></i>Configurações do Site
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="smtp-tab" data-bs-toggle="tab" data-bs-target="#smtp" 
                    type="button" role="tab" aria-controls="smtp" aria-selected="false">
                <i class="bi bi-envelope me-2"></i>Configurações SMTP
            </button>
        </li>
    </ul>

    <!-- Form único para todas as configurações -->
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" id="settingsForm">
        @csrf
        @method('PUT')

        <!-- Tabs Content -->
        <div class="tab-content" id="settingsTabsContent">
            <!-- Tab 1: Configurações do Site -->
            <div class="tab-pane fade show active" id="site" role="tabpanel" aria-labelledby="site-tab">
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

                    <div class="col-12 mb-4">
                        <label for="site_logo" class="form-label fw-semibold">Logo do Site</label>
                        <input type="file" class="form-control @error('site_logo') is-invalid @enderror" 
                               id="site_logo" name="site_logo" accept="image/*">
                        <small class="text-muted">Formatos aceitos: JPEG, PNG, GIF, SVG. Tamanho máximo: 2MB</small>
                        @error('site_logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        @if(isset($siteSettings['site_logo']) && $siteSettings['site_logo'])
                        <div class="mt-3">
                            <p class="mb-2"><strong>Logo atual:</strong></p>
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($siteSettings['site_logo']) }}" alt="Logo atual" 
                                 class="img-thumbnail" style="max-height: 100px; max-width: 300px;">
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tab 2: Configurações SMTP -->
            <div class="tab-pane fade" id="smtp" role="tabpanel" aria-labelledby="smtp-tab">
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Importante:</strong> Configure as credenciais SMTP para envio de emails. Deixe a senha em branco para manter a atual.
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
                               value="" 
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
                    <h6 class="mb-3">
                        <i class="bi bi-send me-2"></i>Testar Configuração de Email
                    </h6>
                    <form method="POST" action="{{ route('admin.settings.testEmail') }}" class="row" id="testEmailForm">
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
        </div>

        <!-- Botão Salvar (fora das abas, sempre visível) -->
        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left me-2"></i>Voltar
            </a>
            <button type="submit" id="saveSettingsBtn" class="btn btn-primary btn-lg">
                <i class="bi bi-save me-2"></i>Salvar Todas as Configurações
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    (function() {
        'use strict';
        
        function initSettingsForm() {
            const form = document.getElementById('settingsForm');
            const saveBtn = document.getElementById('saveSettingsBtn');
            
            if (!form || !saveBtn) {
                console.error('Elementos do formulário não encontrados');
                return;
            }
            
            // Listener no botão de submit
            saveBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Validar formulário
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return false;
                }
                
                // Mostrar loading
                saveBtn.disabled = true;
                saveBtn.classList.add('loading');
                
                const icon = saveBtn.querySelector('i');
                if (icon) {
                    icon.style.display = 'none';
                }
                
                saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Salvando...';
                
                // Submeter formulário
                form.submit();
                
                return false;
            });
            
            // Listener no submit do formulário (backup)
            form.addEventListener('submit', function(e) {
                // Não prevenir se já foi tratado pelo botão
                if (saveBtn.disabled) {
                    return;
                }
                
                // Mostrar loading
                saveBtn.disabled = true;
                saveBtn.classList.add('loading');
                
                const icon = saveBtn.querySelector('i');
                if (icon) {
                    icon.style.display = 'none';
                }
                
                saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Salvando...';
            });
        }
        
        function initTestEmailForm() {
            const testEmailForm = document.getElementById('testEmailForm');
            if (testEmailForm) {
                testEmailForm.addEventListener('submit', function(e) {
                    const submitBtn = testEmailForm.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.disabled) {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('loading');
                        const icon = submitBtn.querySelector('i');
                        if (icon) {
                            icon.style.display = 'none';
                        }
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Enviando...';
                    }
                });
            }
        }
        
        // Inicializar quando DOM estiver pronto
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                initSettingsForm();
                initTestEmailForm();
            });
        } else {
            initSettingsForm();
            initTestEmailForm();
        }
    })();
</script>
@endpush

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
