@extends('layouts.app')

@section('title', 'Jornal Digital - Jornal a Borda')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="page-header mb-5">
                <h1 class="page-title">Jornal Digital</h1>
                <p class="page-subtitle">Acesse todas as edições do nosso jornal</p>
            </div>
        </div>
    </div>

    @if($editions->count() > 0)
    <div class="row g-4">
        @foreach($editions as $edition)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="journal-edition-card">
                <a href="{{ route('journal-editions.show', $edition->slug) }}" class="edition-link">
                    <div class="edition-cover">
                        @if($edition->cover_image)
                        <img src="{{ $edition->cover_image }}" alt="{{ $edition->title }}" class="img-fluid">
                        @else
                        <div class="edition-placeholder">
                            <i class="bi bi-journal-text"></i>
                            <span>Edição {{ $edition->edition_number ?? '#' }}</span>
                        </div>
                        @endif
                        @if($edition->is_featured)
                        <span class="featured-badge">
                            <i class="bi bi-star-fill"></i> Destaque
                        </span>
                        @endif
                    </div>
                    <div class="edition-info">
                        <h3 class="edition-title">{{ $edition->title }}</h3>
                        <div class="edition-meta">
                            @if($edition->edition_number)
                            <span class="edition-number">Edição #{{ $edition->edition_number }}</span>
                            @endif
                            <span class="edition-date">
                                <i class="bi bi-calendar3 me-1"></i>{{ $edition->published_date->format('d/m/Y') }}
                            </span>
                        </div>
                        @if($edition->description)
                        <p class="edition-description">{{ Str::limit($edition->description, 100) }}</p>
                        @endif
                        <div class="edition-stats">
                            <span><i class="bi bi-eye me-1"></i>{{ $edition->views }}</span>
                            <span><i class="bi bi-download me-1"></i>{{ $edition->downloads }}</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $editions->links() }}
    </div>
    @else
    <div class="text-center py-5">
        <i class="bi bi-journal-text" style="font-size: 4rem; color: #ccc;"></i>
        <p class="text-muted mt-3">Nenhuma edição disponível no momento.</p>
    </div>
    @endif
</div>

<style>
.page-header {
    text-align: center;
    padding: 40px 0;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: var(--text-dark);
}

.page-subtitle {
    font-size: 1.1rem;
    color: var(--text-light);
}

.journal-edition-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.journal-edition-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-4px);
}

.edition-link {
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.edition-cover {
    position: relative;
    width: 100%;
    padding-top: 140%;
    overflow: hidden;
    background: #f8f9fa;
}

.edition-cover img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.edition-placeholder {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--text-light);
    font-size: 3rem;
}

.edition-placeholder span {
    font-size: 1rem;
    margin-top: 10px;
}

.featured-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: var(--primary-color);
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    box-shadow: var(--shadow-md);
}

.edition-info {
    padding: 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.edition-title {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: var(--text-dark);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.edition-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 10px;
    font-size: 0.85rem;
    color: var(--text-light);
}

.edition-number {
    background: var(--accent-color);
    padding: 4px 10px;
    border-radius: 12px;
    font-weight: 600;
}

.edition-description {
    font-size: 0.9rem;
    color: var(--text-light);
    margin-bottom: 15px;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.edition-stats {
    display: flex;
    gap: 15px;
    font-size: 0.85rem;
    color: var(--text-light);
    margin-top: auto;
    padding-top: 15px;
    border-top: 1px solid var(--border-color);
}
</style>
@endsection

