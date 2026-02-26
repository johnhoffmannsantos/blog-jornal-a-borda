@extends('layouts.admin')

@section('title', 'Novo Parceiro - Painel Administrativo')

@section('content')
<div class="page-header">
    <div>
        <h1>
            <i class="bi bi-handshake me-2"></i>Novo Parceiro
        </h1>
        <p>Adicione um novo parceiro ao site</p>
    </div>
</div>

<form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data" id="partnerForm">
    @csrf
    
    <div class="row">
        <div class="col-lg-8">
            <div class="admin-card mb-4">
                <div class="card-header">
                    <h5>
                        <i class="bi bi-info-circle me-2"></i>Informações Básicas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nome do Parceiro *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Descrição</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        <small class="text-muted">Breve descrição sobre o parceiro (opcional)</small>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="website_url" class="form-label fw-semibold">URL do Site</label>
                        <input type="url" class="form-control @error('website_url') is-invalid @enderror" 
                               id="website_url" name="website_url" value="{{ old('website_url') }}" 
                               placeholder="https://exemplo.com">
                        @error('website_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card mb-4">
                <div class="card-header">
                    <h5>
                        <i class="bi bi-image me-2"></i>Logo
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="logo" class="form-label fw-semibold">Logo do Parceiro *</label>
                        <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                               id="logo" name="logo" accept="image/*" required>
                        <small class="text-muted">Formatos: JPEG, PNG, GIF, SVG, WebP. Máx: 2MB</small>
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="logo-preview" class="mt-3" style="display: none;">
                        <img id="preview-img" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px; width: 100%; object-fit: contain;">
                    </div>
                </div>
            </div>

            <div class="admin-card mb-4">
                <div class="card-header">
                    <h5>
                        <i class="bi bi-sliders me-2"></i>Configurações
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="level" class="form-label fw-semibold">Nível *</label>
                        <select class="form-select @error('level') is-invalid @enderror" 
                                id="level" name="level" required>
                            <option value="">Selecione...</option>
                            <option value="gold" {{ old('level') == 'gold' ? 'selected' : '' }}>Ouro</option>
                            <option value="silver" {{ old('level') == 'silver' ? 'selected' : '' }}>Prata</option>
                            <option value="bronze" {{ old('level') == 'bronze' ? 'selected' : '' }}>Bronze</option>
                        </select>
                        @error('level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="order" class="form-label fw-semibold">Ordem de Exibição</label>
                        <input type="number" class="form-control @error('order') is-invalid @enderror" 
                               id="order" name="order" value="{{ old('order', 0) }}" min="0">
                        <small class="text-muted">Números menores aparecem primeiro</small>
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check form-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Ativo
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-2"></i>Criar Parceiro
        </button>
    </div>
</form>

@push('scripts')
<script>
    // Preview do logo
    document.getElementById('logo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('logo-preview');
        const img = document.getElementById('preview-img');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    // Garantir que o formulário seja submetido corretamente
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('partnerForm');
        const submitBtn = form.querySelector('button[type="submit"]');
        
        if (form && submitBtn) {
            // Marcar formulário como validado para o script global não interferir
            form.dataset.validated = 'true';
            
            // Interceptar o submit do formulário
            form.addEventListener('submit', function(e) {
                // Validar formulário antes de submeter
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                    form.reportValidity();
                    return false;
                }
                
                // Aplicar loading apenas se ainda não estiver aplicado
                if (!submitBtn.disabled && !submitBtn.classList.contains('loading')) {
                    submitBtn.classList.add('loading');
                    submitBtn.disabled = true;
                    
                    const originalHTML = submitBtn.innerHTML;
                    submitBtn.dataset.originalHTML = originalHTML;
                    
                    const icon = submitBtn.querySelector('i');
                    if (icon) {
                        icon.style.display = 'none';
                    }
                    
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Criando...';
                }
                
                // Permitir que o formulário seja submetido normalmente
                // Não fazer preventDefault aqui se a validação passou!
            });
        }
    });
</script>
@endpush
@endsection

