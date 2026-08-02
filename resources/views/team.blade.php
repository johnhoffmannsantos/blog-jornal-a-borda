@extends('layouts.app')

@section('title', 'Nossa Equipe - Jornal a Borda')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house"></i> Home</a></li>
<li class="breadcrumb-item active" aria-current="page">Nossa Equipe</li>
@endsection

@section('content')
<div class="container py-4">
    <!-- Header Institucional -->
    <div class="text-center mb-5 py-3">
        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold text-uppercase tracking-wider mb-2" style="font-size: 12px;">
            Conheça Quem Faz
        </span>
        <h1 class="display-4 fw-bold mb-3" style="font-family: 'Playfair Display', serif;">
            Nossa Equipe
        </h1>
        <p class="lead text-muted col-lg-8 mx-auto" style="font-size: 1.1rem; line-height: 1.7;">
            Seja bem-vindo ao universo informativo do <strong>Jornal a Borda</strong>. Nossa equipe é composta por profissionais apaixonados pela comunicação, comprometidos em oferecer conteúdo relevante, imparcial e de qualidade.
        </p>
    </div>

    <!-- Loop por Setores / Times -->
    @forelse($team as $department => $members)
        @php
            // Define o ícone do setor de forma limpa e sem erros de compilação
            $sectorIcon = match(true) {
                str_contains($department, 'Redação') => 'pen-fill',
                str_contains($department, 'Fotografia') => 'camera-fill',
                str_contains($department, 'Edição') || str_contains($department, 'Design') => 'palette-fill',
                str_contains($department, 'Tecnologia') || str_contains($department, 'TI') => 'code-slash',
                str_contains($department, 'Comercial') || str_contains($department, 'Marketing') => 'graph-up-arrow',
                default => 'people-fill'
            };
        @endphp

        <div class="team-sector mb-5 pb-3">
            
            <!-- Cabeçalho do Setor -->
            <div class="d-flex align-items-center mb-4">
                <div class="sector-icon-wrapper me-3 d-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm" style="width: 45px; height: 45px;">
                    <i class="bi bi-{{ $sectorIcon }} fs-5"></i>
                </div>
                <h2 class="h3 fw-bold mb-0 text-dark" style="font-family: 'Playfair Display', serif;">
                    {{ $department }}
                </h2>
                <div class="flex-grow-1 ms-3 border-bottom opacity-50"></div>
            </div>

            <!-- Grid dos Cartões dos Integrantes -->
            <div class="row g-4">
                @foreach($members as $member)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="card h-100 border-0 shadow-sm custom-team-card">
                        <div class="card-body text-center p-4 d-flex flex-column align-items-center">
                            
                            <!-- Foto do Integrante -->
                            <div class="position-relative mb-3">
                                <img src="{{ $member->avatar ? asset('storage/' . $member->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&size=150&background=1A25FF&color=fff' }}" 
                                     alt="{{ $member->name }}" 
                                     class="rounded-circle shadow-sm object-fit-cover"
                                     style="width: 110px; height: 110px; border: 4px solid #fff;">
                            </div>

                            <!-- Nome -->
                            <h5 class="fw-bold text-dark mb-1">{{ $member->name }}</h5>
                            
                            <!-- Cargo / Função (Position) -->
                            @if($member->position)
                            <span class="badge bg-light text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-medium mb-3" style="font-size: 12px;">
                                {{ $member->position }}
                            </span>
                            @endif

                            <!-- Resumo Profissional / Biografia -->
                            @if($member->bio)
                            <p class="text-muted small mb-0 mt-2 text-start w-100" style="font-size: 13px; line-height: 1.6; text-align: justify !important;">
                                {{ $member->bio }}
                            </p>
                            @else
                            <p class="text-muted small italic mb-0 mt-2 opacity-50">
                                Integrante da equipe do Jornal a Borda.
                            </p>
                            @endif

                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center py-4 rounded-3 shadow-sm">
            <i class="bi bi-info-circle fs-3 d-block mb-2 text-primary"></i>
            Nenhum integrante cadastrado nos setores no momento.
        </div>
    @endforelse
</div>

<!-- Estilos Personalizados para Cartões -->
<style>
    .custom-team-card {
        border-radius: 16px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #ffffff;
    }
    .custom-team-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08) !important;
    }
    .object-fit-cover {
        object-fit: cover;
    }
    .tracking-wider {
        letter-spacing: 0.05em;
    }
</style>
@endsection