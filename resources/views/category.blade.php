@extends('layouts.app')

@section('title', $category->name . ' - Jornal a Borda')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house"></i> Home</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <!-- Category Header -->
        <div class="col-12 mb-4">
            <div class="category-main-title">
                <h1>
                    <i class="bi bi-folder me-2"></i>{{ $category->name }}
                </h1>
                @if($category->description)
                <p class="text-muted mt-2 mb-0">{{ $category->description }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
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
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            <h4>Nenhum post encontrado</h4>
                            <p class="mb-0">Esta categoria ainda não possui artigos publicados.</p>
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
                    @foreach($categories as $cat)
                    <li>
                        <a href="{{ route('category.show', $cat->slug) }}" class="{{ $cat->id === $category->id ? 'fw-bold text-primary' : '' }}">
                            <span>{{ $cat->name }}</span>
                            <span class="badge">{{ $cat->posts_count }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
