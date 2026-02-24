@extends('layouts.app')

@section('title', 'Jornal a Borda - Início')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-12">
            @forelse($posts as $post)
            <article class="post-wrapper post-block-style row">
                <div class="col-md-6">
                    <div class="post-media post-image">
                        <a href="{{ route('post.show', $post->slug) }}">
                            <img class="img-fluid" src="{{ $post->featured_image ?? 'https://via.placeholder.com/600x400?text=Sem+Imagem' }}" alt="{{ $post->title }}">
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="post-content">
                        <div class="entry-blog-header">
                            <a class="post-cat" href="{{ route('category.show', $post->category->slug) }}">
                                {{ $post->category->name }}
                            </a>
                            <h2 class="post-title md">
                                <a href="{{ route('post.show', $post->slug) }}">{{ $post->title }}</a>
                            </h2>
                        </div>
                        <div class="post-meta">
                            <span class="post-author">
                                <img alt="" src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name) }}&size=55" class="avatar">
                                <a href="{{ route('author.show', $post->author->id) }}">{{ $post->author->name }}</a>
                            </span>
                            <span class="post-meta-date">
                                <i class="bi bi-clock"></i>
                                {{ $post->published_at->format('d/m/Y') }}
                            </span>
                        </div>
                        <div class="entry-blog-summery">
                            <p>{{ Str::limit($post->excerpt, 150) }} <a class="readmore-btn" href="{{ route('post.show', $post->slug) }}">Leia mais <i class="bi bi-arrow-right"></i></a></p>
                        </div>
                    </div>
                </div>
            </article>
            @empty
            <div class="alert alert-info">
                <p>Nenhum post encontrado.</p>
            </div>
            @endforelse

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

