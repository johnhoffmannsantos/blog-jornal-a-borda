@extends('layouts.app')

@section('title', $author->name . ' - Jornal a Borda')

@section('breadcrumb')
<li><i class="bi bi-house"></i> <a href="{{ route('home') }}">Home</a> <i class="bi bi-chevron-right"></i></li>
<li>{{ $author->name }}</li>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 mb-4">
            <!-- Author Info -->
            <div class="author-box solid-bg p-4 bg-light rounded mb-4">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <img alt="{{ $author->name }}" src="https://ui-avatars.com/api/?name={{ urlencode($author->name) }}&size=200&background=ff2d20&color=fff" class="avatar rounded-circle mb-3" style="width: 150px; height: 150px;">
                    </div>
                    <div class="col-md-9">
                        <h1 class="mb-3">{{ $author->name }}</h1>
                        <p class="text-muted mb-2">
                            <i class="bi bi-envelope"></i> {{ $author->email }}
                        </p>
                        <p class="mb-3">
                            <span class="badge bg-primary">{{ $author->posts_count }} {{ $author->posts_count == 1 ? 'artigo publicado' : 'artigos publicados' }}</span>
                        </p>
                        <p class="text-muted">
                            Autor do Jornal a Borda. Especializado em trazer notícias relevantes sobre a comunidade e temas importantes para a sociedade.
                        </p>
                    </div>
                </div>
            </div>

            <div class="category-main-title mb-4">
                <h2 class="block-title">
                    <span>Artigos de {{ $author->name }}</span>
                </h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="main-content-inner row">
                @forelse($posts as $post)
                <article class="post-layout col-md-6 mb-4">
                    <div class="post-block-style">
                        <div class="post-thumb">
                            <a href="{{ route('post.show', $post->slug) }}">
                                <img class="img-fluid" src="{{ $post->featured_image ?? 'https://via.placeholder.com/600x400?text=Sem+Imagem' }}" alt="{{ $post->title }}">
                            </a>
                            <div class="grid-cat">
                                <a class="post-cat" href="{{ route('category.show', $post->category->slug) }}">
                                    {{ $post->category->name }}
                                </a>
                            </div>
                        </div>
                        <div class="post-content">
                            <div class="entry-blog-header">
                                <h3 class="post-title md">
                                    <a href="{{ route('post.show', $post->slug) }}">{{ $post->title }}</a>
                                </h3>
                            </div>
                            <div class="post-meta">
                                <span class="post-meta-date">
                                    <i class="bi bi-clock"></i>
                                    {{ $post->published_at->format('d/m/Y') }}
                                </span>
                            </div>
                            <div class="entry-blog-summery">
                                <p>{{ Str::limit($post->excerpt, 100) }} <a class="readmore-btn" href="{{ route('post.show', $post->slug) }}">Leia mais <i class="bi bi-arrow-right"></i></a></p>
                            </div>
                        </div>
                    </div>
                </article>
                @empty
                <div class="alert alert-info">
                    <p>Este autor ainda não publicou nenhum artigo.</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $posts->links() }}
            </div>
        </div>

        <div class="col-lg-4 col-md-12">
            <div id="sidebar" class="sidebar">
                <h4>Categorias</h4>
                <ul class="list-unstyled">
                    @foreach($categories as $category)
                    <li class="mb-2">
                        <a href="{{ route('category.show', $category->slug) }}" class="text-decoration-none">
                            {{ $category->name }} <span class="badge bg-primary">{{ $category->posts_count }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

