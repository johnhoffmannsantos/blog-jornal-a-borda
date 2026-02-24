@extends('layouts.app')

@section('title', $post->title . ' - Jornal a Borda')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house"></i> Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('category.show', $post->category->slug) }}">{{ $post->category->name }}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title, 50) }}</li>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-12 mx-auto">
            <article class="post-single">
                <!-- Header -->
                <header class="entry-header mb-4">
                    <div class="mb-3">
                        <a class="post-cat" href="{{ route('category.show', $post->category->slug) }}">
                            {{ $post->category->name }}
                        </a>
                    </div>
                    <h1 class="post-title">{{ $post->title }}</h1>
                    <ul class="post-meta list-inline mb-4">
                        <li class="list-inline-item">
                            <img alt="{{ $post->author->name }}" src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name) }}&size=48&background=e63946&color=fff">
                            <a href="{{ route('author.show', $post->author->id) }}">{{ $post->author->name }}</a>
                        </li>
                        <li class="list-inline-item">
                            <i class="bi bi-calendar3"></i>
                            {{ $post->published_at->format('d/m/Y') }}
                        </li>
                        <li class="list-inline-item">
                            <i class="bi bi-eye"></i>
                            {{ $post->views ?? 0 }} visualizações
                        </li>
                    </ul>
                </header>

                <!-- Featured Image -->
                <div class="post-media mb-5">
                    <img src="{{ $post->featured_image ?? 'https://via.placeholder.com/1200x600?text=Sem+Imagem' }}" alt="{{ $post->title }}">
                </div>

                <!-- Content -->
                <div class="post-body">
                    <div class="entry-content">
                        {!! $post->content !!}

                        <!-- Tags -->
                        @if($post->tags->count() > 0)
                        <div class="post-footer mt-5 pt-4 border-top">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <span class="fw-bold me-2"><i class="bi bi-tags"></i> Tags:</span>
                                @foreach($post->tags as $tag)
                                <a href="{{ route('tag.show', $tag->slug) }}" class="badge bg-secondary text-decoration-none px-3 py-2" style="font-size: 13px; font-weight: 500;">
                                    {{ $tag->name }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Author Box -->
                <div class="author-box mt-5">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <img alt="{{ $post->author->name }}" src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name) }}&size=120&background=e63946&color=fff" class="rounded-circle">
                        </div>
                        <div class="col">
                            <h3 class="mb-1">{{ $post->author->name }}</h3>
                            <p class="text-muted mb-2">{{ $post->author->email }}</p>
                            <p class="mb-0">
                                <a href="{{ route('author.show', $post->author->id) }}" class="btn btn-sm btn-outline-primary">
                                    Ver todos os artigos <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Post Navigation -->
                <nav class="post-navigation">
                    <div class="row g-3">
                        @if($previousPost)
                        <div class="col-md-6">
                            <div class="post-previous">
                                <small class="text-muted d-block mb-2">← Post Anterior</small>
                                <a href="{{ route('post.show', $previousPost->slug) }}" class="text-decoration-none text-dark fw-bold">
                                    {{ Str::limit($previousPost->title, 60) }}
                                </a>
                            </div>
                        </div>
                        @endif
                        @if($nextPost)
                        <div class="col-md-6 {{ !$previousPost ? 'offset-md-6' : '' }}">
                            <div class="post-next text-end">
                                <small class="text-muted d-block mb-2">Próximo Post →</small>
                                <a href="{{ route('post.show', $nextPost->slug) }}" class="text-decoration-none text-dark fw-bold">
                                    {{ Str::limit($nextPost->title, 60) }}
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </nav>

                <!-- Comments Section -->
                <div id="comments" class="mt-5">
                    <h3 class="mb-4">
                        <i class="bi bi-chat-dots me-2"></i>Deixe um comentário
                    </h3>
                    <form action="#" method="post" class="comment-form">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label fw-semibold">Nome *</label>
                                <input type="text" class="form-control" placeholder="Seu nome" name="author_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email *</label>
                                <input type="email" class="form-control" placeholder="seu@email.com" name="author_email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Comentário *</label>
                            <textarea class="form-control" rows="6" placeholder="Escreva seu comentário aqui..." name="content" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i>Enviar Comentário
                        </button>
                    </form>
                </div>
            </article>
        </div>
    </div>
</div>
@endsection
