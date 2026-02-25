@extends('layouts.app')

@section('title', $edition->title . ' - Jornal Digital')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house"></i> Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('journal-editions.index') }}">Jornal Digital</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ Str::limit($edition->title, 50) }}</li>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-12 mx-auto">
            <article class="journal-edition-single">
                <!-- Header -->
                <header class="edition-header mb-4">
                    @if($edition->is_featured)
                    <span class="featured-badge-large mb-3">
                        <i class="bi bi-star-fill me-1"></i> Edição em Destaque
                    </span>
                    @endif
                    
                    <h1 class="edition-title-large">{{ $edition->title }}</h1>
                    
                    <div class="edition-meta-large">
                        @if($edition->edition_number)
                        <span class="edition-number-large">Edição #{{ $edition->edition_number }}</span>
                        @endif
                        <span class="edition-date-large">
                            <i class="bi bi-calendar3 me-1"></i>{{ $edition->published_date->locale('pt_BR')->translatedFormat('d \d\e F \d\e Y') }}
                        </span>
                    </div>

                    @if($edition->description)
                    <p class="edition-description-large mt-3">{{ $edition->description }}</p>
                    @endif

                    <div class="edition-stats-large mt-4">
                        <span><i class="bi bi-eye me-1"></i>{{ $edition->views }} visualizações</span>
                        <span><i class="bi bi-download me-1"></i>{{ $edition->downloads }} downloads</span>
                    </div>
                </header>

                <!-- Cover Image -->
                @if($edition->cover_image)
                <div class="edition-cover-large mb-5">
                    <img src="{{ $edition->cover_image }}" alt="{{ $edition->title }}" class="img-fluid rounded">
                </div>
                @endif

                <!-- Actions -->
                <div class="edition-actions mb-5">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('journal-editions.download', $edition->slug) }}" 
                               class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-download me-2"></i>Baixar PDF
                            </a>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-outline-primary btn-lg w-100" 
                                    onclick="shareEdition()">
                                <i class="bi bi-share me-2"></i>Compartilhar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- PDF Viewer -->
                <div class="pdf-viewer-container mb-5">
                    <div class="pdf-viewer-header">
                        <h3><i class="bi bi-file-pdf me-2"></i>Leia Online</h3>
                    </div>
                    <div class="pdf-viewer-wrapper">
                        <iframe src="{{ $edition->pdf_file }}#toolbar=1&navpanes=0&scrollbar=1" 
                                class="pdf-iframe" 
                                frameborder="0">
                            <p>Seu navegador não suporta visualização de PDF. 
                            <a href="{{ route('journal-editions.download', $edition->slug) }}">Clique aqui para baixar</a>.</p>
                        </iframe>
                    </div>
                </div>
            </article>
        </div>
    </div>
</div>

<style>
.edition-header {
    text-align: center;
    padding-bottom: 30px;
    border-bottom: 2px solid var(--border-color);
}

.featured-badge-large {
    display: inline-block;
    background: var(--primary-color);
    color: white;
    padding: 8px 20px;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    box-shadow: var(--shadow-md);
}

.edition-title-large {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 20px 0;
    color: var(--text-dark);
}

.edition-meta-large {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
    margin-top: 15px;
}

.edition-number-large {
    background: var(--accent-color);
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 1rem;
}

.edition-date-large {
    color: var(--text-light);
    font-size: 1rem;
}

.edition-description-large {
    font-size: 1.1rem;
    color: var(--text-light);
    line-height: 1.8;
}

.edition-stats-large {
    display: flex;
    justify-content: center;
    gap: 30px;
    color: var(--text-light);
}

.edition-cover-large img {
    width: 100%;
    border-radius: 12px;
    box-shadow: var(--shadow-lg);
}

.edition-actions {
    padding: 20px;
    background: var(--accent-color);
    border-radius: 12px;
}

.pdf-viewer-container {
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.pdf-viewer-header {
    background: var(--dark-bg);
    color: white;
    padding: 15px 20px;
}

.pdf-viewer-header h3 {
    margin: 0;
    font-size: 1.2rem;
    color: white;
}

.pdf-viewer-wrapper {
    position: relative;
    width: 100%;
    padding-top: 141.42%; /* Aspect ratio 1:√2 (A4) */
    background: #f0f0f0;
}

.pdf-iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

@media (max-width: 768px) {
    .edition-title-large {
        font-size: 1.8rem;
    }
    
    .pdf-viewer-wrapper {
        padding-top: 200%;
    }
}
</style>

<script>
function shareEdition() {
    const url = window.location.href;
    const title = '{{ $edition->title }}';
    
    if (navigator.share) {
        navigator.share({
            title: title,
            text: 'Confira esta edição do Jornal a Borda',
            url: url
        }).catch(console.error);
    } else {
        // Fallback: copiar para clipboard
        navigator.clipboard.writeText(url).then(() => {
            alert('Link copiado para a área de transferência!');
        }).catch(() => {
            // Fallback final: mostrar URL
            prompt('Copie o link:', url);
        });
    }
}
</script>
@endsection

