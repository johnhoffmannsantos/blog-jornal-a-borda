@extends('layouts.admin')

@section('title', 'Parceiros - Painel Administrativo')

@section('content')
{{-- Mensagens serão exibidas via Toast --}}
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>
            <i class="bi bi-handshake me-2"></i>Parceiros
        </h1>
        <p>Gerencie os parceiros do site</p>
    </div>
    <a href="{{ route('admin.partners.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Novo Parceiro
    </a>
</div>

<div class="admin-card">
    <div class="card-body">
        @if($partners->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Nome</th>
                        <th>Nível</th>
                        <th>Ordem</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($partners as $partner)
                    <tr>
                        <td>
                            @if($partner->logo)
                            <img src="{{ $partner->logo }}" alt="{{ $partner->name }}" 
                                 style="max-width: 60px; max-height: 60px; object-fit: contain;">
                            @else
                            <span class="text-muted">Sem logo</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $partner->name }}</strong>
                            @if($partner->description)
                            <br><small class="text-muted">{{ Str::limit($partner->description, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $partner->level_badge_class }}">
                                {{ $partner->level_label }}
                            </span>
                        </td>
                        <td>{{ $partner->order }}</td>
                        <td>
                            @if($partner->is_active)
                            <span class="badge bg-success">Ativo</span>
                            @else
                            <span class="badge bg-secondary">Inativo</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.partners.edit', $partner) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $partners->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-handshake" style="font-size: 4rem; color: #ccc;"></i>
            <p class="text-muted mt-3">Nenhum parceiro cadastrado ainda.</p>
            <a href="{{ route('admin.partners.create') }}" class="btn btn-primary mt-2">
                <i class="bi bi-plus-circle me-2"></i>Criar Primeiro Parceiro
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

