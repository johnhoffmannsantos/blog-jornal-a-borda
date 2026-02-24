@extends('layouts.admin')

@section('title', 'Dashboard - Painel Administrativo')

@section('content')
<div class="page-header">
    <h1>
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </h1>
    <p>Bem-vindo ao painel administrativo do Jornal a Borda</p>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-file-text"></i>
            </div>
            <div class="stat-value">{{ $stats['total_posts'] }}</div>
            <p class="stat-label">Total de Posts</p>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="stat-value">{{ $stats['my_posts'] }}</div>
            <p class="stat-label">Meus Posts</p>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon bg-info bg-opacity-10 text-info">
                <i class="bi bi-chat-dots"></i>
            </div>
            <div class="stat-value">{{ $stats['total_comments'] }}</div>
            <p class="stat-label">Comentários</p>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-tags"></i>
            </div>
            <div class="stat-value">{{ $stats['total_categories'] }}</div>
            <p class="stat-label">Categorias</p>
        </div>
    </div>
</div>

<!-- Recent Posts -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="admin-card">
            <div class="card-header">
                <h5>
                    <i class="bi bi-clock-history me-2"></i>Posts Recentes
                </h5>
            </div>
            <div class="card-body">
                @forelse($recentPosts as $post)
                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    <div class="flex-shrink-0">
                        <img src="{{ $post->featured_image ?? 'https://via.placeholder.com/80x80' }}" 
                             class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1">{{ \Illuminate\Support\Str::limit($post->title, 50) }}</h6>
                        <small class="text-muted">
                            <i class="bi bi-person me-1"></i>{{ $post->author->name }} • 
                            <i class="bi bi-calendar3 me-1"></i>{{ $post->published_at->format('d/m/Y') }}
                        </small>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="badge bg-{{ $post->status === 'published' ? 'success' : 'warning' }}">
                            {{ $post->status === 'published' ? 'Publicado' : 'Rascunho' }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center py-4">Nenhum post encontrado.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="admin-card">
            <div class="card-header">
                <h5>
                    <i class="bi bi-hourglass-split me-2"></i>Comentários Pendentes
                </h5>
            </div>
            <div class="card-body">
                @forelse($pendingComments as $comment)
                <div class="mb-3 pb-3 border-bottom">
                    <p class="mb-1 small">{{ \Illuminate\Support\Str::limit($comment->content, 80) }}</p>
                    <small class="text-muted">
                        <i class="bi bi-person me-1"></i>{{ $comment->author_name }} • 
                        <i class="bi bi-file-text me-1"></i>{{ \Illuminate\Support\Str::limit($comment->post->title, 30) }}
                    </small>
                </div>
                @empty
                <p class="text-muted text-center py-4">Nenhum comentário pendente.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

