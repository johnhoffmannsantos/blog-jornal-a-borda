@extends('layouts.app')

@section('title', 'Nossa Equipe - Jornal a Borda')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house"></i> Home</a></li>
<li class="breadcrumb-item active" aria-current="page">Nossa Equipe</li>
@endsection

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="text-center mb-5 py-2">
        <h1 class="display-4 mb-3 fw-bold" style="font-family: 'Playfair Display', serif;">
            <i class="bi bi-people me-2 text-primary"></i>Nossa Equipe
        </h1>
        
        <!-- Badges de Estatística -->
        <div class="d-flex justify-content-center gap-3 my-3">
            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill shadow-sm fs-6">
                <i class="bi bi-people-fill text-primary me-1"></i>
                <strong>{{ $totalMembers ?? 0 }}</strong> {{ ($totalMembers ?? 0) == 1 ? 'Membro Ativo' : 'Membros Ativos' }}
            </span>
            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill shadow-sm fs-6">
                <i class="bi bi-building text-primary me-1"></i>
                <strong>{{ $team->count() }}</strong> {{ $team->count() == 1 ? 'Setor' : 'Setores' }}
            </span>
        </div>

        <p class="lead text-muted col-lg-8 mx-auto mb-3">
            Seja bem-vindo ao universo informativo do Jornal Aborda, sua principal fonte de notícias e análises na web. Nossa equipe é composta por profissionais apaixonados pela comunicação e comprometidos em oferecer conteúdo relevante, imparcial e de qualidade.
        </p>
        <p class="text-muted col-lg-8 mx-auto small">
            Estamos comprometidos em manter você informado sobre os eventos mais relevantes, desafiando a perspectiva convencional e proporcionando uma compreensão mais profunda dos acontecimentos que moldam as periferias de Osasco. No Jornal Aborda, acreditamos que a informação é uma ferramenta poderosa e estamos aqui para levá-las até você. Obrigado por confiar em nós como sua fonte confiável de notícias.
        </p>
    </div>

    <!-- Team Members Loop por Setor / Grupo -->
    @foreach($team as $department => $members)
    @php
        $icon = match (trim($department)) {
            'Redação'                                  => 'pencil-square',
            'Fotografia'                               => 'camera',
            'Edição & Design', 'Design'                => 'palette',
            'Tecnologia', 'TI'                         => 'laptop',
            'Comercial & Marketing', 'Social Media'   => 'graph-up-arrow',
            'Comunicação'                              => 'megaphone',
            'Fundadores', 'Gestão', 'Fundadora'        => 'star',
            'Revisão', 'Revisora de Texto'             => 'check-circle',
            default                                    => 'people',
        };
    @endphp

    <div class="mb-5">
        <div class="d-flex align-items-center mb-4 border-bottom pb-2">
            <h2 class="mb-0 fw-bold" style="font-family: 'Playfair Display', serif; font-size: 2.2rem;">
                <i class="bi bi-{{ $icon }} me-2 text-primary"></i>
                {{ $department }}
            </h2>
        </div>

        <div class="row g-4">
            @foreach($members as $member)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 team-card" style="border-radius: 20px; background: #ffffff; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                    <div class="card-body p-4 d-flex flex-column align-items-center text-center">
                        @php
                            $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&size=200&background=1A25FF&color=fff';
                            if (!empty($member->avatar)) {
                                if (str_starts_with($member->avatar, 'http://') || str_starts_with($member->avatar, 'https://')) {
                                    $avatarUrl = $member->avatar;
                                } else {
                                    $avatarUrl = asset('storage/' . ltrim(str_replace('/storage/', '', $member->avatar), '/'));
                                }
                            }
                        @endphp

                        <!-- Foto com moldura elegante -->
                        <div class="position-relative mb-3">
                            <img src="{{ $avatarUrl }}" 
                                 alt="{{ $member->name }}"
                                 class="rounded-circle shadow" 
                                 style="width: 140px; height: 140px; border: 4px solid #ffffff; object-fit: cover;">
                        </div>
                        
                        <!-- Nome -->
                        <h4 class="fw-bold mb-2 text-dark" style="font-family: 'Playfair Display', serif;">
                            {{ $member->name }}
                        </h4>
                        
                        <!-- Cargo em Destaque (Estilo Bolha / Badge Pill) -->
                        @if($member->position)
                            <div class="mb-3">
                                <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fw-semibold fs-6">
                                    <i class="bi bi-person-badge me-1"></i>{{ $member->position }}
                                </span>
                            </div>
                        @endif

                        <!-- Biografia Completa Organizada -->
                        @if($member->bio)
                            <div class="w-100 pt-3 border-top mt-auto text-start">
                                <p class="text-secondary small mb-0" style="line-height: 1.7; font-size: 0.925rem; white-space: pre-line;">
                                    {{ $member->bio }}
                                </p>
                            </div>
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

@push('styles')
<style>
    .team-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.08) !important;
    }
</style>
@endpush