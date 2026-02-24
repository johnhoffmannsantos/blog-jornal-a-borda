@extends('layouts.app')

@section('title', 'Jornal a Borda - Início')

@section('content')
<div class="container">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8 col-md-12">
            <!-- Hero Section -->
            @if($posts->count() > 0)
            <div class="hero-post mb-5">
                @php $featuredPost = $posts->first(); @endphp
                <article class="post-block-style">
                    <div class="post-thumb">
                        <a href="{{ route('post.show', $featuredPost->slug) }}">
                            <img src="{{ $featuredPost->featured_image ?? 'https://via.placeholder.com/800x500?text=Sem+Imagem' }}" alt="{{ $featuredPost->title }}">
                        </a>
                        <div class="grid-cat">
                            <a class="post-cat" href="{{ route('category.show', $featuredPost->category->slug) }}">
                                {{ $featuredPost->category->name }}
                            </a>
                        </div>
                    </div>
                    <div class="post-content">
                        <h1 class="post-title">
                            <a href="{{ route('post.show', $featuredPost->slug) }}">{{ $featuredPost->title }}</a>
                        </h1>
                        <div class="post-meta">
                            <span class="post-author">
                                <img alt="{{ $featuredPost->author->name }}" src="https://ui-avatars.com/api/?name={{ urlencode($featuredPost->author->name) }}&size=64&background=e63946&color=fff">
                                <a href="{{ route('author.show', $featuredPost->author->id) }}">{{ $featuredPost->author->name }}</a>
                            </span>
                            <span class="post-meta-date">
                                <i class="bi bi-calendar3"></i>
                                {{ $featuredPost->published_at->format('d/m/Y') }}
                            </span>
                        </div>
                        <div class="entry-blog-summery">
                            <p>{{ Str::limit($featuredPost->excerpt, 200) }}</p>
                            <a class="readmore-btn" href="{{ route('post.show', $featuredPost->slug) }}">
                                Continuar lendo <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </article>
            </div>
            @endif

            <!-- Posts Grid -->
            <div class="posts-grid">
                <h3 class="mb-4" style="font-size: 1.8rem; color: var(--text-dark);">
                    <i class="bi bi-newspaper me-2"></i>Últimas Notícias
                </h3>
                <div class="row">
                    @forelse($posts->skip(1) as $post)
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
                                        <img alt="{{ $post->author->name }}" src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name) }}&size=32&background=e63946&color=fff">
                                        <a href="{{ route('author.show', $post->author->id) }}">{{ $post->author->name }}</a>
                                    </span>
                                    <span class="post-meta-date">
                                        <i class="bi bi-calendar3"></i>
                                        {{ $post->published_at->format('d/m/Y') }}
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
                            <i class="bi bi-info-circle fs-1 d-block mb-3"></i>
                            <p class="mb-0">Nenhum post encontrado.</p>
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
