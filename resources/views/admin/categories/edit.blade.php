@extends('layouts.admin')

@section('title', 'Editar Categoria - Painel Administrativo')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>
            <i class="bi bi-pencil me-2"></i>Editar Categoria
        </h1>
        <p>Atualize as informações da categoria</p>
    </div>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Voltar
    </a>
</div>

<div class="admin-card">
    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Nome da Categoria *</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                   id="name" name="name" value="{{ old('name', $category->name) }}" required
                   placeholder="Ex: Cultura, Esporte, Política...">
            <small class="text-muted">O slug será atualizado automaticamente a partir do nome</small>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="form-label fw-semibold">Descrição</label>
            <textarea class="form-control @error('description') is-invalid @enderror" 
                      id="description" name="description" rows="4"
                      placeholder="Descreva brevemente esta categoria (opcional)...">{{ old('description', $category->description) }}</textarea>
            <small class="text-muted">Máximo 500 caracteres</small>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Informação:</strong> Esta categoria está atrelada a 
            <strong>{{ $category->posts_count }} post(s)</strong>.
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle me-2"></i>Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-2"></i>Atualizar Categoria
            </button>
        </div>
    </form>
</div>
@endsection

