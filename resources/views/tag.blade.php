@extends('layouts.app')

@section('title', 'Tag: ' . $tag->name . ' - Jornal a Borda')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house"></i> Home</a></li>
<li class="breadcrumb-item active" aria-current="page">Tag: {{ $tag->name }}</li>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8 col-md-12">
            <!-- Category Header -->
            <div class="category-header mb-5">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-tags me-2" style="font-size: 2rem; color: var(--primary-color);"></i>
                    <h1 class="mb-0" style="font-size: 2.5rem;">Tag: {{ $tag->name }}</h1>
                </div>
                <p class="text-muted">
                    <i class="bi bi-file-text me-1"></i>
                    {{ $posts->total() }} {{ $posts->total() == 1 ? 'post encontrado' : 'posts encontrados' }}
                </p>
            </div>

            <!-- Posts Grid -->
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
                                    <span class="post-author">
                                        <img alt="{{ $post->author->name }}" src="{{ $post->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->author->name) . '&size=48&background=1A25FF&color=fff' }}">
                                        <a href="{{ route('author.show', $post->author->id) }}">{{ $post->author->name }}</a>
                                    </span>
                                    <span class="post-meta-date">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $post->published_at->format('d/m/Y') }}
                                    </span>
                                </div>
                                <div class="entry-blog-summery">
                                    <p>{{ Str::limit($post->excerpt, 150) }}</p>
                                    <a class="readmore-btn" href="{{ route('post.show', $post->slug) }}">
                                        Continuar lendo <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center py-5">
                            <i class="bi bi-info-circle me-2" style="font-size: 2rem;"></i>
                            <h4 class="mt-3">Nenhum post encontrado</h4>
                            <p class="text-muted">Não há posts publicados com esta tag no momento.</p>
                            <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                                <i class="bi bi-house me-2"></i>Voltar para Home
                            </a>
                        </div>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($posts->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $posts->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="sidebar">
                <!-- Categories Widget -->
                <div class="widget mb-4">
                    <h4 class="widget-title">
                        <i class="bi bi-folder me-2"></i>Categorias
                    </h4>
                    <ul class="widget-list">
                        @foreach($categories as $category)
                        <li>
                            <a href="{{ route('category.show', $category->slug) }}">
                                {{ $category->name }}
                                <span class="badge bg-secondary float-end">{{ $category->posts_count }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

