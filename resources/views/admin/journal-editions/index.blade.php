@extends('layouts.admin')

@section('title', 'Jornal Digital - Painel Administrativo')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>
            <i class="bi bi-journal-text me-2"></i>Jornal Digital
        </h1>
        <p>Gerencie as edições do jornal digital</p>
    </div>
    <a href="{{ route('admin.journal-editions.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Nova Edição
    </a>
</div>

<div class="admin-card">
    <div class="card-body">
        @if($editions->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Capa</th>
                        <th>Título</th>
                        <th>Número</th>
                        <th>Data</th>
                        <th>Visualizações</th>
                        <th>Downloads</th>
                        <th>Destaque</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($editions as $edition)
                    <tr>
                        <td>
                            @if($edition->cover_image)
                            <img src="{{ $edition->cover_image }}" alt="{{ $edition->title }}" 
                                 style="max-width: 60px; max-height: 80px; object-fit: cover; border-radius: 4px;">
                            @else
                            <span class="text-muted">Sem capa</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $edition->title }}</strong>
                            @if($edition->description)
                            <br><small class="text-muted">{{ Str::limit($edition->description, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            @if($edition->edition_number)
                            <span class="badge bg-primary">#{{ $edition->edition_number }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $edition->published_date->format('d/m/Y') }}</td>
                        <td>
                            <i class="bi bi-eye me-1"></i>{{ $edition->views }}
                        </td>
                        <td>
                            <i class="bi bi-download me-1"></i>{{ $edition->downloads }}
                        </td>
                        <td>
                            @if($edition->is_featured)
                            <span class="badge bg-warning text-dark">Destaque</span>
                            @else
                            <span class="badge bg-secondary">Normal</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('journal-editions.show', $edition->slug) }}" 
                                   class="btn btn-sm btn-outline-info" target="_blank" title="Ver no site">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.journal-editions.edit', $edition) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.journal-editions.destroy', $edition) }}" 
                                      method="POST" class="d-inline delete-form">
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
            {{ $editions->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-journal-text" style="font-size: 4rem; color: #ccc;"></i>
            <p class="text-muted mt-3">Nenhuma edição cadastrada ainda.</p>
            <a href="{{ route('admin.journal-editions.create') }}" class="btn btn-primary mt-2">
                <i class="bi bi-plus-circle me-2"></i>Criar Primeira Edição
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

