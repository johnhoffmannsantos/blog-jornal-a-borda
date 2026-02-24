@extends('layouts.admin')

@section('title', 'Criar Post - Painel Administrativo')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>
            <i class="bi bi-plus-circle me-2"></i>Criar Novo Post
        </h1>
        <p>Preencha os campos abaixo para criar um novo post</p>
    </div>
    <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Voltar
    </a>
</div>

<form method="POST" action="{{ route('admin.posts.store') }}" id="postForm">
    @csrf

    <div class="row">
        <!-- Coluna Principal - Conteúdo -->
        <div class="col-lg-8">
            <!-- Card Principal -->
            <div class="admin-card mb-4">
                <div class="card-header">
                    <h5>
                        <i class="bi bi-file-text me-2"></i>Conteúdo do Post
                    </h5>
                </div>
                
                <div class="mb-4">
                    <label for="title" class="form-label fw-semibold">
                        Título do Post *
                        <span class="badge bg-light text-dark ms-2" id="title-count">0 caracteres</span>
                    </label>
                    <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" 
                           id="title" name="title" value="{{ old('title') }}" required
                           placeholder="Digite o título do post aqui...">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="excerpt" class="form-label fw-semibold">
                        Resumo / Descrição *
                        <span class="badge bg-light text-dark ms-2" id="excerpt-count">0 / 500</span>
                    </label>
                    <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                              id="excerpt" name="excerpt" rows="4" required
                              placeholder="Escreva um resumo atrativo do post (aparecerá na listagem)..."
                              maxlength="500">{{ old('excerpt') }}</textarea>
                    <small class="text-muted">Este texto aparecerá na listagem de posts. Seja objetivo e atrativo!</small>
                    @error('excerpt')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="content" class="form-label fw-semibold">
                        Conteúdo Completo *
                        <span class="badge bg-light text-dark ms-2" id="content-count">0 caracteres</span>
                    </label>
                    <textarea class="form-control @error('content') is-invalid @enderror" 
                              id="content" name="content" rows="20" required
                              placeholder="Escreva o conteúdo completo do post aqui...">{{ old('content') }}</textarea>
                    <small class="text-muted">Use o editor WYSIWYG acima para formatar o conteúdo do post.</small>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Coluna Lateral - Configurações -->
        <div class="col-lg-4">
            <!-- Card de Publicação -->
            <div class="admin-card mb-4">
                <div class="card-header">
                    <h5>
                        <i class="bi bi-send me-2"></i>Publicação
                    </h5>
                </div>
                
                <div class="mb-3">
                    <label for="status" class="form-label fw-semibold">Status *</label>
                    <select class="form-select @error('status') is-invalid @enderror" 
                            id="status" name="status" required>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>
                            <i class="bi bi-file-earmark"></i> Rascunho
                        </option>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>
                            <i class="bi bi-check-circle"></i> Publicado
                        </option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="published_at" class="form-label fw-semibold">Data de Publicação</label>
                    <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" 
                           id="published_at" name="published_at" value="{{ old('published_at') }}">
                    <small class="text-muted">Deixe em branco para publicar agora (se status = Publicado)</small>
                    @error('published_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Salvar Post
                    </button>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </a>
                </div>
            </div>

            <!-- Card de Categoria -->
            <div class="admin-card mb-4">
                <div class="card-header">
                    <h5>
                        <i class="bi bi-folder me-2"></i>Categoria
                    </h5>
                </div>
                
                <div class="mb-3">
                    <label for="category_id" class="form-label fw-semibold">Categoria *</label>
                    <select class="form-select @error('category_id') is-invalid @enderror" 
                            id="category_id" name="category_id" required>
                        <option value="">Selecione uma categoria</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Card de Imagem -->
            <div class="admin-card mb-4">
                <div class="card-header">
                    <h5>
                        <i class="bi bi-image me-2"></i>Imagem de Destaque
                    </h5>
                </div>
                
                <div class="mb-3">
                    <label for="featured_image" class="form-label fw-semibold">URL da Imagem</label>
                    <input type="url" class="form-control @error('featured_image') is-invalid @enderror" 
                           id="featured_image" name="featured_image" value="{{ old('featured_image') }}" 
                           placeholder="https://exemplo.com/imagem.jpg">
                    @error('featured_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div id="image-preview" class="mt-3" style="display: none;">
                    <img id="preview-img" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px; width: 100%; object-fit: cover;">
                </div>
            </div>

            <!-- Card de Tags -->
            <div class="admin-card mb-4">
                <div class="card-header">
                    <h5>
                        <i class="bi bi-tags me-2"></i>Tags
                    </h5>
                </div>
                
                <div class="mb-2">
                    <small class="text-muted">Selecione as tags relacionadas ao post</small>
                </div>

                <div class="tags-container" style="max-height: 300px; overflow-y: auto;">
                    @foreach($tags as $tag)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="tags[]" 
                               value="{{ $tag->id }}" id="tag_{{ $tag->id }}"
                               {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="tag_{{ $tag->id }}">
                            <span class="badge bg-secondary">{{ $tag->name }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>

                @if($tags->isEmpty())
                <p class="text-muted small mb-0">Nenhuma tag disponível. Crie tags primeiro.</p>
                @endif
            </div>
        </div>
    </div>
</form>

@push('styles')
<style>
    .form-control-lg {
        font-size: 1.25rem;
        font-weight: 600;
    }

    #content {
        font-family: 'Courier New', monospace;
        font-size: 14px;
        line-height: 1.8;
    }

    .tags-container {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 12px;
        background: #f8f9fa;
    }

    .form-check-input:checked + .form-check-label .badge {
        background: #e63946 !important;
        color: white !important;
    }

    #image-preview {
        border: 2px dashed #e9ecef;
        border-radius: 8px;
        padding: 12px;
        background: #f8f9fa;
    }

    .admin-card .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 2px solid #e63946;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script>
    // Inicializar TinyMCE
    tinymce.init({
        selector: '#content',
        language: 'pt-BR',
        language_url: '{{ asset('tinymce/js/tinymce/langs/pt_BR.js') }}',
        base_url: '{{ asset('tinymce/js/tinymce') }}',
        suffix: '.min',
        height: 500,
        menubar: true,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | ' +
            'bold italic forecolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help',
        content_style: 'body { font-family: Inter, Arial, sans-serif; font-size: 16px; }',
        branding: false,
        promotion: false,
        license_key: 'gpl',
        setup: function(editor) {
            editor.on('keyup', function() {
                const content = editor.getContent({ format: 'text' });
                document.getElementById('content-count').textContent = content.length + ' caracteres';
            });
        }
    });

    // Contador de caracteres
    document.getElementById('title').addEventListener('input', function() {
        document.getElementById('title-count').textContent = this.value.length + ' caracteres';
    });

    document.getElementById('excerpt').addEventListener('input', function() {
        const count = this.value.length;
        document.getElementById('excerpt-count').textContent = count + ' / 500';
        if (count > 450) {
            document.getElementById('excerpt-count').classList.add('bg-warning');
        } else {
            document.getElementById('excerpt-count').classList.remove('bg-warning');
        }
    });

    // Preview de imagem
    document.getElementById('featured_image').addEventListener('input', function() {
        const url = this.value;
        const preview = document.getElementById('image-preview');
        const img = document.getElementById('preview-img');
        
        if (url && url.startsWith('http')) {
            img.src = url;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    });

    // Auto-save draft (opcional - pode ser implementado no futuro)
    // setInterval(() => {
    //     // Salvar rascunho automaticamente
    // }, 30000);
</script>
@endpush
@endsection
