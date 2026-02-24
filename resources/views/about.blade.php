@extends('layouts.app')

@section('title', 'Sobre Nós - Jornal a Borda')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house"></i> Home</a></li>
<li class="breadcrumb-item active" aria-current="page">Sobre Nós</li>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Hero Section -->
            <div class="text-center mb-5 py-5">
                <h1 class="display-4 mb-4" style="font-family: 'Playfair Display', serif;">
                    Bem-vindo ao "Jornal Aborda"
                </h1>
                <p class="lead text-muted">
                    A Voz das Periferias de Osasco
                </p>
            </div>

            <!-- Main Content -->
            <div class="card border-0 shadow-lg mb-5" style="border-radius: 16px; overflow: hidden;">
                <div class="card-body p-5">
                    <div class="row align-items-center mb-5">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <img src="https://via.placeholder.com/600x400/e63946/ffffff?text=Visao+do+Bairro" 
                                 class="img-fluid rounded" alt="Visão do Bairro" style="box-shadow: var(--shadow-md);">
                        </div>
                        <div class="col-md-6">
                            <h2 class="mb-4" style="font-family: 'Playfair Display', serif;">
                                <i class="bi bi-quote me-2"></i>Nossa História
                            </h2>
                            <p class="lead mb-4">
                                O "Jornal Aborda" é muito mais do que um simples veículo de notícias; é uma manifestação da nossa dedicação em dar voz às comunidades periféricas de Osasco.
                            </p>
                            <p>
                                Acreditamos que cada bairro, cada rua e cada história têm um valor inestimável, e nosso objetivo é trazer à tona as vozes e as experiências que muitas vezes são negligenciadas pela mídia convencional.
                            </p>
                        </div>
                    </div>

                    <hr class="my-5">

                    <div class="row">
                        <div class="col-12">
                            <h2 class="mb-4" style="font-family: 'Playfair Display', serif;">
                                <i class="bi bi-bullseye me-2"></i>Nossa Missão
                            </h2>
                            <p class="fs-5 mb-4" style="line-height: 1.9;">
                                Nossa missão é promover a inclusão social, aumentar a conscientização sobre as desigualdades e defender os direitos das comunidades periféricas. Acreditamos que o acesso a informações precisas e representativas é essencial para construir uma sociedade mais justa e igualitária.
                            </p>
                            <p class="fs-5" style="line-height: 1.9;">
                                Portanto, buscamos destacar as histórias de sucesso, os desafios enfrentados pela comunidade, os eventos culturais e muito mais, proporcionando uma visão abrangente de Osasco.
                            </p>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-4 mb-4">
                            <div class="text-center p-4 bg-light rounded" style="border-radius: 16px;">
                                <i class="bi bi-heart fs-1 text-primary mb-3"></i>
                                <h5 class="fw-bold">Inclusão Social</h5>
                                <p class="text-muted mb-0">Promovemos a inclusão e representatividade</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="text-center p-4 bg-light rounded" style="border-radius: 16px;">
                                <i class="bi bi-megaphone fs-1 text-primary mb-3"></i>
                                <h5 class="fw-bold">Voz das Periferias</h5>
                                <p class="text-muted mb-0">Damos voz às comunidades de Osasco</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="text-center p-4 bg-light rounded" style="border-radius: 16px;">
                                <i class="bi bi-journal-text fs-1 text-primary mb-3"></i>
                                <h5 class="fw-bold">Jornalismo de Qualidade</h5>
                                <p class="text-muted mb-0">Informação precisa e representativa</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="text-center mb-5">
                <a href="{{ route('team') }}" class="btn btn-primary btn-lg px-5 py-3" style="border-radius: 12px;">
                    <i class="bi bi-people me-2"></i>Conheça Nossa Equipe
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

