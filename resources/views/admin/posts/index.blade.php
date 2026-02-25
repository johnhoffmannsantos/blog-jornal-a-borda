@extends('layouts.admin')

@section('title', 'Posts - Painel Administrativo')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>
            <i class="bi bi-file-text me-2"></i>Posts
        </h1>
        <p>Gerencie todos os seus posts</p>
    </div>
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Novo Post
    </a>
</div>

{{-- Mensagens serão exibidas via Toast --}}

<!-- Cards de Estatísticas -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-file-text"></i>
            </div>
            <div class="stat-value">{{ $stats['total'] }}</div>
            <p class="stat-label">Total de Posts</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-value">{{ $stats['published'] }}</div>
            <p class="stat-label">Publicados</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-file-earmark"></i>
            </div>
            <div class="stat-value">{{ $stats['draft'] }}</div>
            <p class="stat-label">Rascunhos</p>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="admin-card mb-4">
    <form method="GET" action="{{ route('admin.posts.index') }}" class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Buscar</label>
            <input type="text" class="form-control" name="search" 
                   value="{{ request('search') }}" placeholder="Título do post...">
        </div>
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status">
                <option value="">Todos</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publicado</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Rascunho</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Categoria</label>
            <select class="form-select" name="category">
                <option value="">Todas</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-outline-primary w-100">
                <i class="bi bi-funnel me-1"></i>Filtrar
            </button>
        </div>
    </form>
</div>

<!-- Lista de Posts -->
<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th style="width: 60px;">Imagem</th>
                    <th>Título</th>
                    <th>Categoria</th>
                    <th>Autor</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th>Visualizações</th>
                    <th style="width: 120px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr>
                    <td>
                        <img src="{{ $post->featured_image ?? 'https://via.placeholder.com/60x60' }}" 
                             class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                    </td>
                    <td>
                        <strong>{{ \Illuminate\Support\Str::limit($post->title, 50) }}</strong>
                        <br>
                        <small class="text-muted">{{ \Illuminate\Support\Str::limit($post->excerpt, 60) }}</small>
                    </td>
                    <td>
                        <span class="badge bg-secondary">{{ $post->category->name }}</span>
                    </td>
                    <td>
                        <small>{{ $post->author->name }}</small>
                    </td>
                    <td>
                        <span class="badge bg-{{ $post->status === 'published' ? 'success' : 'warning' }}">
                            {{ $post->status === 'published' ? 'Publicado' : 'Rascunho' }}
                        </span>
                    </td>
                    <td>
                        <small>{{ $post->published_at ? $post->published_at->format('d/m/Y') : '-' }}</small>
                    </td>
                    <td>
                        <span class="badge bg-info">
                            <i class="bi bi-eye me-1"></i>{{ $post->views ?? 0 }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-outline-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" class="d-inline" 
                                  onsubmit="return confirm('Tem certeza que deseja excluir este post?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" title="Excluir">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Nenhum post encontrado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</div>
@endsection

