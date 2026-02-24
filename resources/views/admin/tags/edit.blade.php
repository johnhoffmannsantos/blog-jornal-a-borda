@extends('layouts.admin')

@section('title', 'Editar Tag - Painel Administrativo')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>
            <i class="bi bi-pencil me-2"></i>Editar Tag
        </h1>
        <p>Atualize as informações da tag</p>
    </div>
    <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Voltar
    </a>
</div>

<div class="admin-card">
    <form method="POST" action="{{ route('admin.tags.update', $tag) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="form-label fw-semibold">Nome da Tag *</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                   id="name" name="name" value="{{ old('name', $tag->name) }}" required
                   placeholder="Ex: cultura, esporte, política...">
            <small class="text-muted">O slug será atualizado automaticamente a partir do nome</small>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Informação:</strong> Esta tag está atrelada a 
            <strong>{{ $tag->posts_count }} post(s)</strong>.
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle me-2"></i>Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-2"></i>Atualizar Tag
            </button>
        </div>
    </form>
</div>
@endsection

