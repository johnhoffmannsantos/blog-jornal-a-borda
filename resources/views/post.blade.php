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
        <div class="col-lg-8 col-md-12">
            <article class="post-single">
                <header class="entry-header mb-4">
                    <div class="mb-3">
                        <a class="post-cat" href="{{ route('category.show', $post->category->slug) }}">
                            {{ $post->category->name }}
                        </a>
                    </div>
                    <h1 class="post-title">{{ $post->title }}</h1>
                    <ul class="post-meta list-inline mb-4">
                        <li class="list-inline-item">
                            <img alt="{{ $post->author->name }}" src="{{ $post->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->author->name) . '&size=48&background=1A25FF&color=fff' }}">
                            <a href="{{ route('author.show', $post->author->id) }}">{{ $post->author->name }}</a>
                        </li>
                        <li class="list-inline-item">
                            <i class="bi bi-calendar3"></i>
                            {{ $post->published_at ? $post->published_at->format('d/m/Y') : 'Rascunho' }}
                        </li>
                        <li class="list-inline-item">
                            <i class="bi bi-eye"></i>
                            {{ $post->views ?? 0 }} visualizações
                        </li>
                    </ul>
                </header>

                <div class="post-media mb-5">
                    @if($post->featured_image)
                        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="img-fluid rounded w-100" style="max-height: 500px; object-fit: cover;">
                    @else
                        <div class="bg-light text-muted d-flex align-items-center justify-content-center rounded" style="height: 350px; border: 2px dashed #dee2e6;">
                            <div class="text-center">
                                <i class="bi bi-image fs-1 d-block mb-2 text-secondary"></i>
                                <span>Matéria sem imagem de capa</span>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="post-body">
                    <div class="entry-content">
                        {!! $post->content !!}

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

                <div class="author-box mt-5">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <img alt="{{ $post->author->name }}" src="{{ $post->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->author->name) . '&size=120&background=1A25FF&color=fff' }}" class="rounded-circle">
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

                <nav class="post-navigation">
                    <div class="row g-3">
                        @if($previousPost)
                        <div class="col-md-6">
                            <a href="{{ route('post.show', $previousPost->slug) }}"
                            class="post-previous d-block text-decoration-none text-dark">
                                <small class="text-muted d-block mb-2">
                                    ← Post Anterior
                                </small>
                                <span class="fw-bold">
                                    {{ Str::limit($previousPost->title, 60) }}
                                </span>
                            </a>
                        </div>
                        @endif
                        @if($nextPost)
                        <div class="col-md-6 {{ !$previousPost ? 'offset-md-6' : '' }}">
                            <a href="{{ route('post.show', $nextPost->slug) }}"
                            class="post-next d-block text-decoration-none text-dark text-end">
                                <small class="text-muted d-block mb-2">
                                    Próximo Post →
                                </small>
                                <span class="fw-bold">
                                    {{ Str::limit($nextPost->title, 60) }}
                                </span>
                            </a>
                        </div>
                        @endif
                    </div>
                </nav>

                <div id="comments" class="mt-5">
                    <h3 class="mb-4">
                        <i class="bi bi-chat-dots me-2"></i>Comentários
                        @if($post->comments->count() > 0)
                            <span class="badge bg-primary ms-2">{{ $post->comments->count() }}</span>
                        @endif
                    </h3>

                    @if($post->comments->count() > 0)
                    <div class="comments-list mb-5">
                        @foreach($post->comments as $comment)
                        <div class="comment-item mb-4 pb-4 border-bottom">
                            <div class="d-flex align-items-start">
                                <div class="comment-avatar me-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->author_name) }}&size=48&background=1A25FF&color=fff"
                                         alt="{{ $comment->author_name }}" class="rounded-circle">
                                </div>
                                <div class="comment-content flex-grow-1">
                                    <div class="comment-header mb-2">
                                        <strong class="comment-author">{{ $comment->author_name }}</strong>
                                        @if($comment->author_url)
                                        <a href="{{ $comment->author_url }}" target="_blank" rel="nofollow" class="text-decoration-none ms-2">
                                            <i class="bi bi-link-45deg"></i>
                                        </a>
                                        @endif
                                        <small class="text-muted ms-2">
                                            <i class="bi bi-clock"></i> {{ $comment->created_at->format('d/m/Y \à\s H:i') }}
                                        </small>
                                    </div>
                                    <div class="comment-text">
                                        {!! nl2br(e($comment->content)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i>Seja o primeiro a comentar!
                    </div>
                    @endif

                    <h4 class="mb-4 mt-5">
                        <i class="bi bi-pencil me-2"></i>Deixe um comentário
                    </h4>

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form action="{{ route('post.comment.store', $post->slug) }}" method="POST" class="comment-form">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label fw-semibold">Nome *</label>
                                <input type="text" class="form-control @error('author_name') is-invalid @enderror"
                                       placeholder="Seu nome" name="author_name"
                                       value="{{ old('author_name') }}" required>
                                @error('author_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email *</label>
                                <input type="email" class="form-control @error('author_email') is-invalid @enderror"
                                       placeholder="seu@email.com" name="author_email"
                                       value="{{ old('author_email') }}" required>
                                @error('author_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Website (opcional)</label>
                                <input type="url" class="form-control @error('author_url') is-invalid @enderror"
                                       placeholder="https://seu-site.com" name="author_url"
                                       value="{{ old('author_url') }}">
                                @error('author_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Comentário *</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" rows="6"
                                      placeholder="Escreva seu comentário aqui..." name="content" required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i>Enviar Comentário
                        </button>
                    </form>
                </div>
            </article>
        </div>

        <div class="col-lg-4 col-md-12 mt-5 mt-lg-0">
            @include('partials.sidebar')
        </div>
    </div>
</div>

<style>
    .author-box img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        object-position: center;
    }
    .entry-content img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 20px auto;
        border-radius: 5px;
    }
</style>
@endsection