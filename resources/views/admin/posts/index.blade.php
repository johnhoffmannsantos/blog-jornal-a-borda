@extends('layouts.admin')

@section('title', 'Posts - Painel Administrativo')

@section('content')
@php
    $activeFilterKeys = collect(['search', 'status', 'category', 'author', 'published_from', 'published_to'])
        ->filter(fn ($k) => filled(request($k)));
    $activeFilterCount = $activeFilterKeys->count();
@endphp

<div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <h1>
            <i class="bi bi-file-text me-2"></i>Posts
        </h1>
        <p class="mb-0">Gerencie todos os seus posts</p>
    </div>
    <div class="d-flex align-items-center gap-2 ms-auto">
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="offcanvas" data-bs-target="#postsFiltersOffcanvas" aria-controls="postsFiltersOffcanvas">
            <i class="bi bi-sliders me-1"></i>Filtrar
            @if($activeFilterCount > 0)
                <span class="badge bg-primary ms-1">{{ $activeFilterCount }}</span>
            @endif
        </button>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Novo Post
        </a>
    </div>
</div>

{{-- Offcanvas filtros --}}
<div class="offcanvas offcanvas-start bg-white" tabindex="-1" id="postsFiltersOffcanvas" aria-labelledby="postsFiltersOffcanvasLabel" style="--bs-offcanvas-width: min(420px, 100vw);">
    <div class="offcanvas-header border-bottom bg-white">
        <div>
            <h5 class="offcanvas-title fw-semibold" id="postsFiltersOffcanvasLabel">
                <i class="bi bi-funnel me-2 text-primary"></i>Filtrar posts
            </h5>
            <p class="small text-muted mb-0">Refine a listagem por texto, status, datas e mais.</p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
    </div>
    <div class="offcanvas-body">
        <form method="GET" action="{{ route('admin.posts.index') }}" id="postsFiltersForm">
            <input type="hidden" name="page" value="1">
            <input type="hidden" name="dir" value="{{ request('dir', 'desc') }}">

            <div class="mb-3">
                <label class="form-label fw-medium">Buscar</label>
                <input type="text" class="form-control" name="search"
                       value="{{ request('search') }}" placeholder="Título do post...">
            </div>
            <div class="mb-3">
                <label class="form-label fw-medium">Status</label>
                <select class="form-select" name="status">
                    <option value="">Todos</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publicado</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Rascunho</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-medium">Categoria</label>
                <select class="form-select" name="category">
                    <option value="">Todas</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @if($authors->isNotEmpty())
            <div class="mb-3">
                <label class="form-label fw-medium">Autor</label>
                <select class="form-select" name="author">
                    <option value="">Todos</option>
                    @foreach($authors as $a)
                    <option value="{{ $a->id }}" {{ (string) request('author') === (string) $a->id ? 'selected' : '' }}>
                        {{ $a->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endif
            <hr class="my-4 opacity-25">
            <p class="small text-uppercase text-muted fw-semibold mb-3">Data de publicação</p>
            <div class="mb-3">
                <label class="form-label">A partir de</label>
                <input type="date" class="form-control" name="published_from" value="{{ request('published_from') }}">
            </div>
            <div class="mb-4">
                <label class="form-label">Até</label>
                <input type="date" class="form-control" name="published_to" value="{{ request('published_to') }}">
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check2-circle me-1"></i>Aplicar filtros
                </button>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">Limpar tudo</a>
            </div>
        </form>
    </div>
</div>

{{-- Mensagens serão exibidas via Toast --}}

<!-- Cards de Estatísticas -->
<div class="row g-2 g-md-3 mb-3">
    <div class="col-md-4">
        <div class="stat-card stat-card-compact">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-file-text"></i>
            </div>
            <div class="stat-value">{{ $stats['total'] }}</div>
            <p class="stat-label">Total de Posts</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card stat-card-compact">
            <div class="stat-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-value">{{ $stats['published'] }}</div>
            <p class="stat-label">Publicados</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card stat-card-compact">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-file-earmark"></i>
            </div>
            <div class="stat-value">{{ $stats['draft'] }}</div>
            <p class="stat-label">Rascunhos</p>
        </div>
    </div>
</div>

<!-- Lista de Posts -->
<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th style="width: 60px;">Imagem</th>
                    <th style="max-width: 280px;">Título</th>
                    <th>Categoria</th>
                    <th>Autor</th>
                    <th>Status</th>
                    <th style="min-width: 140px;">
                        @php
                            $dirNow = request('dir', 'desc') === 'asc' ? 'asc' : 'desc';
                            $dirToggle = $dirNow === 'desc' ? 'asc' : 'desc';
                            $sortQuery = array_merge(request()->except('page'), ['dir' => $dirToggle]);
                        @endphp
                        <a href="{{ route('admin.posts.index', $sortQuery) }}" class="text-reset text-decoration-none d-inline-flex align-items-center gap-1">
                            Data publicação
                            @if($dirNow === 'desc')
                                <i class="bi bi-sort-down" title="Mais recentes primeiro"></i>
                            @else
                                <i class="bi bi-sort-up" title="Mais antigos primeiro"></i>
                            @endif
                        </a>
                    </th>
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
                    <td class="align-middle" style="max-width: 280px;">
                        <div class="small">
                            <strong class="d-block mb-0" style="font-size: 0.8125rem; line-height: 1.25;">{{ \Illuminate\Support\Str::limit($post->title, 40) }}</strong>
                            <span class="text-muted d-block" style="font-size: 0.72rem; line-height: 1.3;">{{ \Illuminate\Support\Str::limit($post->excerpt, 48) }}</span>
                        </div>
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
                        <small>{{ $post->published_at ? $post->published_at->format('d/m/Y') : '—' }}</small>
                    </td>
                    <td>
                        <span class="badge badge-sidebar">
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
