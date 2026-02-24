@extends('layouts.app')

@section('title', $post->title . ' - Jornal a Borda')

@section('breadcrumb')
<li><i class="bi bi-house"></i> <a href="{{ route('home') }}">Home</a> <i class="bi bi-chevron-right"></i></li>
<li><a href="{{ route('category.show', $post->category->slug) }}">{{ $post->category->name }}</a></li>
<li><i class="bi bi-chevron-right"></i>{{ $post->title }}</li>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <article class="post-content post-single">
                <header class="entry-header clearfix">
                    <h1 class="post-title lg">{{ $post->title }}</h1>
                    <ul class="post-meta list-inline">
                        <li class="list-inline-item post-category">
                            <a class="post-cat" href="{{ route('category.show', $post->category->slug) }}">
                                {{ $post->category->name }}
                            </a>
                        </li>
                        <li class="list-inline-item post-author">
                            <img alt="" src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name) }}&size=55" class="avatar">
                            <a href="{{ route('author.show', $post->author->id) }}">{{ $post->author->name }}</a>
                        </li>
                        <li class="list-inline-item post-meta-date">
                            <i class="bi bi-clock"></i>
                            {{ $post->published_at->format('d/m/Y') }}
                        </li>
                    </ul>
                </header>

                <div class="post-media post-image mb-4">
                    <img class="img-fluid" src="{{ $post->featured_image ?? 'https://via.placeholder.com/1200x600?text=Sem+Imagem' }}" alt="{{ $post->title }}">
                </div>

                <div class="post-body clearfix">
                    <div class="entry-content">
                        {!! nl2br(e($post->content)) !!}

                        @if($post->tags->count() > 0)
                        <div class="post-footer mt-4">
                            <div class="post-tag-container">
                                <div class="tag-lists">
                                    <span>Tags: </span>
                                    @foreach($post->tags as $tag)
                                    <a href="#" class="badge bg-secondary text-decoration-none me-1">{{ $tag->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Author Box -->
                <div class="author-box solid-bg mt-4 p-4 bg-light rounded">
                    <div class="row">
                        <div class="col-md-2">
                            <img alt="" src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name) }}&size=96" class="avatar rounded-circle">
                        </div>
                        <div class="col-md-10">
                            <h3>{{ $post->author->name }}</h3>
                            <p>Autor do Jornal a Borda</p>
                        </div>
                    </div>
                </div>

                <!-- Post Navigation -->
                <nav class="post-navigation clearfix mt-4">
                    <div class="row">
                        @if($previousPost)
                        <div class="col-md-6">
                            <div class="post-previous">
                                <a href="{{ route('post.show', $previousPost->slug) }}">
                                    <span>Post anterior</span>
                                    <p>{{ Str::limit($previousPost->title, 50) }}</p>
                                </a>
                            </div>
                        </div>
                        @endif
                        @if($nextPost)
                        <div class="col-md-6 text-end">
                            <div class="post-next">
                                <a href="{{ route('post.show', $nextPost->slug) }}">
                                    <span>Próximo post</span>
                                    <p>{{ Str::limit($nextPost->title, 50) }}</p>
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </nav>

                <!-- Comments -->
                <div id="comments" class="blog-post-comment mt-5">
                    <h3>Deixe um comentário</h3>
                    <form action="#" method="post" class="comment-form">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Nome" name="author_name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" class="form-control" placeholder="Email" name="author_email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" rows="5" placeholder="Comentário" name="content" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar Comentário</button>
                    </form>
                </div>
            </article>
        </div>
    </div>
</div>
@endsection

