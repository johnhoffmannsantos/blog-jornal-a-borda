@extends('layouts.admin')

@section('title', 'Editar Edição - Painel Administrativo')

@section('content')
<div class="page-header">
    <div>
        <h1>
            <i class="bi bi-journal-text me-2"></i>Editar Edição do Jornal
        </h1>
        <p>Atualize as informações da edição</p>
    </div>
</div>

<form action="{{ route('admin.journal-editions.update', $journalEdition) }}" method="POST" enctype="multipart/form-data" id="journalEditionForm">
    @csrf
    @method('PUT')
    
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
                        <label for="title" class="form-label fw-semibold">Título da Edição *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $journalEdition->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Descrição</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4">{{ old('description', $journalEdition->description) }}</textarea>
                        <small class="text-muted">Breve descrição sobre esta edição (opcional)</small>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="published_date" class="form-label fw-semibold">Data de Publicação *</label>
                                <input type="date" class="form-control @error('published_date') is-invalid @enderror" 
                                       id="published_date" name="published_date" 
                                       value="{{ old('published_date', $journalEdition->published_date->format('Y-m-d')) }}" required>
                                @error('published_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edition_number" class="form-label fw-semibold">Número da Edição</label>
                                <input type="number" class="form-control @error('edition_number') is-invalid @enderror" 
                                       id="edition_number" name="edition_number" 
                                       value="{{ old('edition_number', $journalEdition->edition_number) }}" min="1">
                                <small class="text-muted">Ex: 89, 90, 91...</small>
                                @error('edition_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card mb-4">
                <div class="card-header">
                    <h5>
                        <i class="bi bi-file-pdf me-2"></i>Arquivo PDF
                    </h5>
                </div>
                <div class="card-body">
                    @if($journalEdition->pdf_file)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">PDF Atual</label>
                        <div>
                            <a href="{{ $journalEdition->pdf_file }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-file-pdf me-1"></i>Ver PDF Atual
                            </a>
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="pdf_file" class="form-label fw-semibold">Novo PDF</label>
                        <input type="file" class="form-control @error('pdf_file') is-invalid @enderror" 
                               id="pdf_file" name="pdf_file" accept=".pdf">
                        <small class="text-muted">Tamanho máximo: 10MB</small>
                        @error('pdf_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="admin-card mb-4">
                <div class="card-header">
                    <h5>
                        <i class="bi bi-image me-2"></i>Capa
                    </h5>
                </div>
                <div class="card-body">
                    @if($journalEdition->cover_image)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Capa Atual</label>
                        <div>
                            <img src="{{ $journalEdition->cover_image }}" alt="Capa atual" 
                                 class="img-fluid rounded mb-2" style="max-height: 200px; width: 100%; object-fit: cover;">
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="cover_image" class="form-label fw-semibold">Nova Capa</label>
                        <input type="file" class="form-control @error('cover_image') is-invalid @enderror" 
                               id="cover_image" name="cover_image" accept="image/*">
                        <small class="text-muted">Formatos: JPEG, PNG, GIF, WebP. Máx: 2MB</small>
                        @error('cover_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="cover-preview" class="mt-3" style="display: none;">
                        <img id="preview-img" src="" alt="Preview" class="img-fluid rounded" style="max-height: 300px; width: 100%; object-fit: cover;">
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
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                               {{ old('is_featured', $journalEdition->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">
                            Destacar esta edição
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('admin.journal-editions.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i>Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-2"></i>Salvar Alterações
        </button>
    </div>
</form>

@push('scripts')
<script>
    // Preview da capa
    document.getElementById('cover_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('cover-preview');
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
</script>
@endpush
@endsection

