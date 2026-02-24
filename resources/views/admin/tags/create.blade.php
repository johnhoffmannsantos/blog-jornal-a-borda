@extends('layouts.admin')

@section('title', 'Criar Tag - Painel Administrativo')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>
            <i class="bi bi-plus-circle me-2"></i>Criar Nova Tag
        </h1>
        <p>Preencha o campo abaixo para criar uma nova tag</p>
    </div>
    <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Voltar
    </a>
</div>

<div class="admin-card">
    <form method="POST" action="{{ route('admin.tags.store') }}">
        @csrf

        <div class="mb-4">
            <label for="name" class="form-label fw-semibold">Nome da Tag *</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                   id="name" name="name" value="{{ old('name') }}" required
                   placeholder="Ex: cultura, esporte, política...">
            <small class="text-muted">O slug será gerado automaticamente a partir do nome</small>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle me-2"></i>Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-2"></i>Salvar Tag
            </button>
        </div>
    </form>
</div>
@endsection

