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

<form method="POST" action="{{ route('admin.posts.store') }}" id="postForm" enctype="multipart/form-data">
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
                              id="content" name="content" rows="20"
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
                
                @php
                    $pubDate = old('published_at_date', now()->format('Y-m-d'));
                    $pubTime = old('published_at_time', now()->format('H:i'));
                    $oldStatus = old('status', 'draft');
                @endphp

                <div class="mb-3">
                    <label for="status" class="form-label fw-semibold">Status *</label>
                    <select class="form-select @error('status') is-invalid @enderror" 
                            id="status" name="status" required>
                        <option value="draft" {{ $oldStatus === 'draft' ? 'selected' : '' }}>Rascunho</option>
                        <option value="scheduled" {{ $oldStatus === 'scheduled' ? 'selected' : '' }}>Agendado</option>
                        <option value="published" {{ $oldStatus === 'published' ? 'selected' : '' }}>Publicado</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if(auth()->user()->isAdmin())
                <div class="mb-3">
                    <label for="author_id" class="form-label fw-semibold">Autor</label>
                    <select class="form-select @error('author_id') is-invalid @enderror" 
                            id="author_id" name="author_id">
                        <option value="">Selecione um autor (padrão: você)</option>
                        @foreach($authors as $author)
                        <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>
                            {{ $author->name }} ({{ ucfirst($author->role) }})
                        </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Deixe em branco para usar você como autor</small>
                    @error('author_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @endif

                <div class="mb-3" id="publishedAtFields">
                    <span class="form-label fw-semibold d-block mb-2">Data e hora de publicação</span>
                    <div class="row g-2">
                        <div class="col-md-7">
                            <label for="published_at_date" class="form-label small text-muted mb-0">Data</label>
                            <input type="date" class="form-control @error('published_at_date') is-invalid @enderror"
                                   id="published_at_date" name="published_at_date" value="{{ $pubDate }}">
                            @error('published_at_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-5">
                            <label for="published_at_time" class="form-label small text-muted mb-0">Horário (24 h)</label>
                            <input type="text" class="form-control @error('published_at_time') is-invalid @enderror"
                                   id="published_at_time" name="published_at_time" value="{{ $pubTime }}"
                                   placeholder="14:30" maxlength="5" inputmode="numeric" autocomplete="off"
                                   pattern="([01][0-9]|2[0-3]):[0-5][0-9]"
                                   title="Horas 00–23 e minutos 00–59 (ex.: 09:05 ou 18:00). Sem AM/PM.">
                            @error('published_at_time')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">Dois campos: data no calendário e hora só em <strong>24 horas</strong> (digite como 14:30). Em <strong>Rascunho</strong> não usa; em <strong>Agendado</strong> precisa ser no futuro.</small>
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
                    <label for="featured_image_file" class="form-label fw-semibold">Upload de Imagem</label>
                    <input type="file" class="form-control @error('featured_image_file') is-invalid @enderror" 
                           id="featured_image_file" name="featured_image_file" accept="image/*">
                    <small class="text-muted">Formatos aceitos: JPEG, PNG, GIF, WebP. Tamanho máximo: 5MB</small>
                    @error('featured_image_file')
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
        background: #1A25FF !important;
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
        border-bottom: 2px solid #1A25FF;
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
            'image link | removeformat | help',
        content_style: 'body { font-family: Inter, Arial, sans-serif; font-size: 16px; }',
        branding: false,
        promotion: false,
        license_key: 'gpl',
        images_upload_url: '{{ route('admin.upload.image') }}',
        images_upload_handler: function (blobInfo, progress) {
            return new Promise(function (resolve, reject) {
                const xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', '{{ route('admin.upload.image') }}');
                
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (token) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }
                
                xhr.upload.onprogress = function (e) {
                    progress(e.loaded / e.total * 100);
                };
                
                xhr.onload = function () {
                    if (xhr.status === 403) {
                        reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
                        return;
                    }
                    
                    if (xhr.status < 200 || xhr.status >= 300) {
                        reject('HTTP Error: ' + xhr.status);
                        return;
                    }
                    
                    const json = JSON.parse(xhr.responseText);
                    
                    if (!json || typeof json.location != 'string') {
                        reject('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                    
                    resolve(json.location);
                };
                
                xhr.onerror = function () {
                    reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
                };
                
                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                
                xhr.send(formData);
            });
        },
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

    // Preview de imagem (upload)
    const featuredImageFile = document.getElementById('featured_image_file');
    if (featuredImageFile) {
        featuredImageFile.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('image-preview');
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
    }

    // Status x data/hora de publicação (hora em texto 24 h, sem type=time)
    const statusEl = document.getElementById('status');
    const publishedAtDate = document.getElementById('published_at_date');
    const publishedAtTimeInput = document.getElementById('published_at_time');
    function normalizeTime24h(el) {
        if (!el) return;
        const digits = el.value.replace(/\D/g, '').slice(0, 4);
        if (digits.length >= 3) {
            el.value = digits.slice(0, 2) + ':' + digits.slice(2, 4);
        }
    }
    if (publishedAtTimeInput) {
        publishedAtTimeInput.addEventListener('blur', function() { normalizeTime24h(this); });
    }
    function syncPublishFields() {
        if (!statusEl || !publishedAtDate || !publishedAtTimeInput) return;
        const status = statusEl.value;
        publishedAtDate.removeAttribute('required');
        publishedAtTimeInput.removeAttribute('required');
        if (status === 'scheduled') {
            publishedAtDate.setAttribute('required', 'required');
            publishedAtTimeInput.setAttribute('required', 'required');
        }
    }
    if (statusEl) {
        statusEl.addEventListener('change', syncPublishFields);
        syncPublishFields();
    }

    // Validação antes de enviar (TinyMCE esconde o textarea, então não dá para usar required nele)
    const postForm = document.getElementById('postForm');
    if (postForm) {
        postForm.addEventListener('submit', function(e) {
            normalizeTime24h(document.getElementById('published_at_time'));
            // Primeiro, deixa o browser validar os campos "normais"
            if (!postForm.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                postForm.reportValidity();
                return false;
            }

            const editor = tinymce.get('content');
            const textContent = editor ? editor.getContent({ format: 'text' }).trim() : '';

            if (!textContent) {
                e.preventDefault();
                e.stopPropagation();
                if (typeof window.showToast === 'function') {
                    window.showToast('error', 'O conteúdo do post é obrigatório.');
                } else {
                    alert('O conteúdo do post é obrigatório.');
                }
                if (editor) editor.focus();
                return false;
            }

            // Sincroniza conteúdo do TinyMCE com o textarea antes de enviar
            if (typeof tinymce.triggerSave === 'function') {
                tinymce.triggerSave();
            }
        });
    }

    // Auto-save draft (opcional - pode ser implementado no futuro)
    // setInterval(() => {
    //     // Salvar rascunho automaticamente
    // }, 30000);
</script>
@endpush
@endsection
