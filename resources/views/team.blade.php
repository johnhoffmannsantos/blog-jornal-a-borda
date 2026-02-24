@extends('layouts.app')

@section('title', 'Nossa Equipe - Jornal a Borda')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house"></i> Home</a></li>
<li class="breadcrumb-item active" aria-current="page">Nossa Equipe</li>
@endsection

@section('content')
<div class="container">
    <!-- Header -->
    <div class="text-center mb-5 py-4">
        <h1 class="display-4 mb-3" style="font-family: 'Playfair Display', serif;">
            <i class="bi bi-people me-2"></i>Nossa Equipe
        </h1>
        <p class="lead text-muted col-lg-8 mx-auto">
            Seja bem-vindo ao universo informativo do Jornal Aborda, sua principal fonte de notícias e análises na web. Nossa equipe é composta por profissionais apaixonados pela comunicação e comprometidos em oferecer conteúdo relevante, imparcial e de qualidade.
        </p>
        <p class="text-muted col-lg-8 mx-auto">
            Estamos comprometidos em manter você informado sobre os eventos mais relevantes, desafiando a perspectiva convencional e proporcionando uma compreensão mais profunda dos acontecimentos que moldam as periferias de Osasco. No Jornal Aborda, acreditamos que a informação é uma ferramenta poderosa e estamos aqui para levá-las até você. Obrigado por confiar em nós como sua fonte confiável de notícias.
        </p>
    </div>

    <!-- Team Members by Position -->
    @foreach($team as $position => $members)
    <div class="mb-5">
        <h2 class="mb-4" style="font-family: 'Playfair Display', serif; font-size: 2rem;">
            <i class="bi bi-{{ $position === 'Fundadora' ? 'star' : ($position === 'Editor Chefe' || str_contains($position, 'Editor') ? 'pencil-square' : ($position === 'Gestor de TI' ? 'laptop' : ($position === 'Redatora' || $position === 'Redator' ? 'pen' : ($position === 'Revisora de Texto' ? 'check-circle' : ($position === 'Social Media' ? 'instagram' : ($position === 'Comunicação' ? 'megaphone' : 'person')))))) }} me-2"></i>
            {{ $position }}
        </h2>
        <div class="row g-4">
            @foreach($members as $member)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; transition: var(--transition);">
                    <div class="card-body text-center p-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&size=150&background=e63946&color=fff" 
                             class="rounded-circle mb-3" 
                             style="width: 120px; height: 120px; border: 4px solid white; box-shadow: var(--shadow-md);">
                        <h5 class="fw-bold mb-1">{{ $member->name }}</h5>
                        <p class="text-muted small mb-2">{{ $member->position }}</p>
                        @if($member->bio)
                        <p class="small text-muted mb-0" style="font-size: 13px; line-height: 1.6;">
                            {{ Str::limit($member->bio, 100) }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
@endsection

