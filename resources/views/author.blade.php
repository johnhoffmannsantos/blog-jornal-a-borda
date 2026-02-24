@extends('layouts.app')

@section('title', $author->name . ' - Jornal a Borda')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house"></i> Home</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $author->name }}</li>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <!-- Author Info Card -->
            <div class="author-box mb-5">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center mb-4 mb-md-0">
                        <img alt="{{ $author->name }}" src="https://ui-avatars.com/api/?name={{ urlencode($author->name) }}&size=200&background=e63946&color=fff" class="rounded-circle" style="width: 180px; height: 180px; border: 5px solid white; box-shadow: var(--shadow-lg);">
                    </div>
                    <div class="col-md-9">
                        <h1 class="mb-3">{{ $author->name }}</h1>
                        <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                            <span class="text-muted">
                                <i class="bi bi-envelope me-2"></i>{{ $author->email }}
                            </span>
                            <span class="badge bg-primary px-3 py-2" style="font-size: 14px;">
                                <i class="bi bi-file-text me-1"></i>
                                {{ $author->posts_count }} {{ $author->posts_count == 1 ? 'artigo publicado' : 'artigos publicados' }}
                            </span>
                        </div>
                        <p class="text-muted mb-0" style="line-height: 1.8;">
                            Autor do Jornal a Borda. Especializado em trazer notícias relevantes sobre a comunidade e temas importantes para a sociedade. Comprometido com o jornalismo de qualidade e informação que faz a diferença.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section Title -->
            <div class="category-main-title mb-4">
                <h2>
                    <i class="bi bi-journal-text me-2"></i>Artigos de {{ $author->name }}
                </h2>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Posts Grid -->
        <div class="col-lg-8 col-md-12">
            <div class="posts-grid">
                <div class="row">
                    @forelse($posts as $post)
                    <div class="col-md-6 mb-4">
                        <article class="post-block-style">
                            <div class="post-thumb">
                                <a href="{{ route('post.show', $post->slug) }}">
                                    <img src="{{ $post->featured_image ?? 'https://via.placeholder.com/600x400?text=Sem+Imagem' }}" alt="{{ $post->title }}">
                                </a>
                                <div class="grid-cat">
                                    <a class="post-cat" href="{{ route('category.show', $post->category->slug) }}">
                                        {{ $post->category->name }}
                                    </a>
                                </div>
                            </div>
                            <div class="post-content">
                                <h3 class="post-title">
                                    <a href="{{ route('post.show', $post->slug) }}">{{ $post->title }}</a>
                                </h3>
                                <div class="post-meta">
                                    <span class="post-meta-date">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $post->published_at->format('d/m/Y') }}
                                    </span>
                                    <span class="post-meta-date">
                                        <i class="bi bi-eye"></i>
                                        {{ $post->views ?? 0 }} visualizações
                                    </span>
                                </div>
                                <div class="entry-blog-summery">
                                    <p>{{ Str::limit($post->excerpt, 120) }}</p>
                                    <a class="readmore-btn" href="{{ route('post.show', $post->slug) }}">
                                        Ler mais <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            <h4>Nenhum artigo encontrado</h4>
                            <p class="mb-0">Este autor ainda não publicou nenhum artigo.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            @if($posts->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $posts->links() }}
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4 col-md-12 mt-5 mt-lg-0">
            <div class="sidebar">
                <h4>
                    <i class="bi bi-tags me-2"></i>Categorias
                </h4>
                <ul class="list-unstyled">
                    @foreach($categories as $category)
                    <li>
                        <a href="{{ route('category.show', $category->slug) }}">
                            <span>{{ $category->name }}</span>
                            <span class="badge">{{ $category->posts_count }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
