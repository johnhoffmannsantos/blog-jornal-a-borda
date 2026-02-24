@extends('layouts.admin')

@section('title', 'Criar Post - Painel Administrativo')

@section('content')
<div class="page-header">
    <h1>
        <i class="bi bi-plus-circle me-2"></i>Criar Novo Post
    </h1>
    <p>Preencha os campos abaixo para criar um novo post</p>
</div>

<div class="admin-card">
    <form method="POST" action="{{ route('admin.posts.store') }}">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label fw-semibold">Título *</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                   id="title" name="title" value="{{ old('title') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="excerpt" class="form-label fw-semibold">Resumo *</label>
            <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                      id="excerpt" name="excerpt" rows="3" required>{{ old('excerpt') }}</textarea>
            <small class="text-muted">Breve descrição do post (máximo 500 caracteres)</small>
            @error('excerpt')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="content" class="form-label fw-semibold">Conteúdo *</label>
            <textarea class="form-control @error('content') is-invalid @enderror" 
                      id="content" name="content" rows="15" required>{{ old('content') }}</textarea>
            @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
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

            <div class="col-md-6 mb-3">
                <label for="featured_image" class="form-label fw-semibold">Imagem de Destaque (URL)</label>
                <input type="url" class="form-control @error('featured_image') is-invalid @enderror" 
                       id="featured_image" name="featured_image" value="{{ old('featured_image') }}" 
                       placeholder="https://exemplo.com/imagem.jpg">
                @error('featured_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="status" class="form-label fw-semibold">Status *</label>
                <select class="form-select @error('status') is-invalid @enderror" 
                        id="status" name="status" required>
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Rascunho</option>
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Publicado</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="published_at" class="form-label fw-semibold">Data de Publicação</label>
                <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" 
                       id="published_at" name="published_at" value="{{ old('published_at') }}">
                @error('published_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Tags</label>
            <div class="row g-2">
                @foreach($tags as $tag)
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="tags[]" 
                               value="{{ $tag->id }}" id="tag_{{ $tag->id }}"
                               {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="tag_{{ $tag->id }}">
                            {{ $tag->name }}
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-2"></i>Salvar Post
            </button>
        </div>
    </form>
</div>
@endsection

