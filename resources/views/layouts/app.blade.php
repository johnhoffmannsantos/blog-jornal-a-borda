<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jornal a Borda')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #e63946;
            --primary-dark: #d62839;
            --secondary-color: #457b9d;
            --accent-color: #f1faee;
            --dark-bg: #1d3557;
            --text-dark: #2b2d42;
            --text-light: #6c757d;
            --border-color: #e9ecef;
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--text-dark);
            line-height: 1.7;
            background: #fafbfc;
            font-size: 16px;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            line-height: 1.3;
            color: var(--text-dark);
        }

        /* Header Styles */
        .header-top {
            background: linear-gradient(135deg, var(--dark-bg) 0%, #2a4a6b 100%);
            padding: 12px 0;
            color: white;
        }

        .logo-area {
            padding: 20px 0;
            background: white;
            box-shadow: var(--shadow-sm);
        }

        @media (max-width: 991px) {
            .logo-area {
                padding: 15px 0;
            }

            .logo-area .container {
                padding-left: 15px;
                padding-right: 15px;
            }

            .logo h2 {
                font-size: 1.5rem;
            }

            .logo img {
                max-height: 50px !important;
                max-width: 200px !important;
            }

            .header-nav {
                padding: 0;
            }

            .header-nav .container {
                padding-left: 15px;
                padding-right: 15px;
            }

            .navbar {
                padding: 10px 0;
            }
        }

        .logo h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .logo a {
            text-decoration: none;
        }

        .header-nav {
            background: white;
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: var(--transition);
        }

        .navbar {
            padding: 0;
        }

        .navbar-nav .nav-link {
            color: var(--text-dark);
            font-weight: 500;
            padding: 18px 20px;
            transition: var(--transition);
            position: relative;
            font-size: 15px;
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 20px;
            right: 20px;
            height: 3px;
            background: var(--primary-color);
            transform: scaleX(0);
            transition: var(--transition);
        }

        .navbar-nav .nav-link:hover::after,
        .navbar-nav .nav-link.active::after {
            transform: scaleX(1);
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary-color);
            background: var(--accent-color);
        }

        .dropdown-menu {
            border: none;
            box-shadow: var(--shadow-md);
            border-radius: 8px;
            padding: 8px 0;
            margin-top: 8px;
        }

        .dropdown-item {
            padding: 10px 20px;
            transition: var(--transition);
            font-size: 14px;
        }

        .dropdown-item:hover {
            background: var(--accent-color);
            color: var(--primary-color);
        }

        /* Breadcrumb */
        .breadcrumb {
            background: transparent;
            padding: 20px 0;
            margin: 0;
            font-size: 14px;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            color: var(--text-light);
            padding: 0 8px;
        }

        .breadcrumb a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        /* Post Cards */
        .post-block-style {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            margin-bottom: 30px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .post-block-style:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .post-thumb {
            position: relative;
            overflow: hidden;
            height: 280px;
        }

        .post-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .post-block-style:hover .post-thumb img {
            transform: scale(1.05);
        }

        .grid-cat {
            position: absolute;
            top: 15px;
            left: 15px;
            z-index: 10;
        }

        .post-cat {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
        }

        .post-cat:hover {
            background: var(--primary-dark);
            color: white;
            transform: scale(1.05);
        }

        .post-content {
            padding: 24px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .post-title {
            margin: 0 0 12px 0;
        }

        .post-title a {
            color: var(--text-dark);
            text-decoration: none;
            font-size: 1.4rem;
            font-weight: 700;
            line-height: 1.3;
            transition: var(--transition);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .post-title a:hover {
            color: var(--primary-color);
        }

        .post-meta {
            color: var(--text-light);
            font-size: 13px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .post-meta img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid var(--border-color);
        }

        .post-meta a {
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .post-meta a:hover {
            color: var(--primary-color);
        }

        .entry-blog-summery {
            color: var(--text-light);
            font-size: 15px;
            line-height: 1.7;
            flex: 1;
        }

        .readmore-btn {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            margin-top: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: var(--transition);
        }

        .readmore-btn:hover {
            gap: 10px;
            color: var(--primary-dark);
        }

        /* Sidebar */
        .sidebar {
            background: white;
            padding: 28px;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 100px;
        }

        .sidebar h4 {
            font-size: 1.3rem;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--border-color);
        }

        .sidebar .list-unstyled li {
            margin-bottom: 12px;
        }

        .sidebar .list-unstyled a {
            color: var(--text-dark);
            text-decoration: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            transition: var(--transition);
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar .list-unstyled a:hover {
            color: var(--primary-color);
            padding-left: 8px;
        }

        .sidebar .badge {
            background: var(--primary-color);
            font-size: 11px;
            padding: 4px 10px;
        }

        /* Pagination */
        .pagination {
            margin-top: 40px;
            justify-content: center;
        }

        .pagination .page-link {
            color: var(--text-dark);
            border: 1px solid var(--border-color);
            padding: 10px 16px;
            margin: 0 4px;
            border-radius: 8px;
            transition: var(--transition);
        }

        .pagination .page-link:hover {
            background: var(--accent-color);
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .pagination .page-item.active .page-link {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        /* Footer */
        .copy-right {
            background: var(--dark-bg);
            color: white;
            padding: 30px 0;
            margin-top: 60px;
            text-align: center;
        }

        .copy-right p {
            margin: 0;
            font-size: 14px;
            opacity: 0.9;
        }

        /* Partners Section */
        .partners-section {
            margin-top: 60px;
        }

        .partner-item {
            padding: 15px;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .partner-item:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .partner-link {
            display: block;
            width: 100%;
            text-decoration: none;
        }

        .partner-link:hover .partner-logo {
            filter: grayscale(0) !important;
            transform: scale(1.05);
        }

        .partner-logo {
            transition: var(--transition);
        }

        /* Author Box */
        .author-box {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 16px;
            padding: 32px;
            box-shadow: var(--shadow-md);
            margin-bottom: 40px;
        }

        .author-box img {
            border: 4px solid white;
            box-shadow: var(--shadow-md);
        }

        /* Category Title */
        .category-main-title {
            margin-bottom: 30px;
        }

        .category-main-title h1,
        .category-main-title h2 {
            font-size: 2.5rem;
            color: var(--text-dark);
            position: relative;
            padding-bottom: 15px;
        }

        .category-main-title h1::after,
        .category-main-title h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border-radius: 2px;
        }

        /* Single Post Styles */
        .post-single .post-title {
            font-size: 2.8rem;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .post-single .post-media {
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 40px;
            box-shadow: var(--shadow-lg);
        }

        .post-single .post-media img {
            width: 100%;
            height: auto;
            display: block;
        }

        .post-single .entry-content {
            font-size: 18px;
            line-height: 1.9;
            color: var(--text-dark);
        }

        .post-single .entry-content p {
            margin-bottom: 24px;
        }

        .post-single .entry-content h2 {
            font-size: 2rem;
            margin: 40px 0 20px 0;
            color: var(--text-dark);
        }

        .post-single .entry-content h3 {
            font-size: 1.6rem;
            margin: 32px 0 16px 0;
            color: var(--text-dark);
        }

        .post-single .entry-content ul,
        .post-single .entry-content ol {
            margin: 20px 0;
            padding-left: 30px;
        }

        .post-single .entry-content li {
            margin-bottom: 12px;
        }

        /* Post Navigation */
        .post-navigation {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 2px solid var(--border-color);
        }

        .post-previous,
        .post-next {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .post-previous:hover,
        .post-next:hover {
            box-shadow: var(--shadow-md);
            transform: translateX(5px);
        }

        .post-next:hover {
            transform: translateX(-5px);
        }

        /* Comment Form */
        .comment-form {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            margin-top: 40px;
        }

        .comment-form .form-control {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 12px 16px;
            transition: var(--transition);
        }

        .comment-form .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.1);
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 12px 32px;
            border-radius: 8px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Responsive */
        @media (max-width: 991px) {
            .header-nav .navbar {
                flex-wrap: nowrap;
            }

            .header-nav .navbar-toggler {
                border: none;
                padding: 8px;
                margin-left: auto;
            }

            .header-nav .navbar-toggler:focus {
                box-shadow: none;
            }

            .header-nav .navbar-toggler-icon {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2833, 37, 41, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
            }
        }

        @media (max-width: 768px) {
            .post-single .post-title {
                font-size: 2rem;
            }

            .category-main-title h1,
            .category-main-title h2 {
                font-size: 1.8rem;
            }

            .post-thumb {
                height: 200px;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .post-block-style {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Header Top -->
    <div class="header-top d-none d-md-block">
        <div class="container">
            <div class="row">
                <div class="col-12 text-end">
                    <small>Jornalismo com propósito • Notícias que importam</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Logo Area & Navigation -->
    <div class="logo-area d-md-block d-none">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <a href="{{ route('home') }}" class="logo">
                        @php
                            $logo = \App\Models\Setting::get('site_logo');
                        @endphp
                        @if($logo)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($logo) }}" alt="{{ \App\Models\Setting::get('site_name', 'Jornal a Borda') }}" style="max-height: 60px; max-width: 300px;">
                        @else
                            <h2>{{ \App\Models\Setting::get('site_name', 'Jornal a Borda') }}</h2>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <header class="header-nav">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <!-- Logo Mobile (dentro do nav) -->
                <div class="d-md-none d-flex align-items-center me-auto">
                    <a href="{{ route('home') }}" class="logo me-3">
                        @php
                            $logo = \App\Models\Setting::get('site_logo');
                        @endphp
                        @if($logo)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($logo) }}" alt="{{ \App\Models\Setting::get('site_name', 'Jornal a Borda') }}" style="max-height: 45px; max-width: 180px;">
                        @else
                            <h2 style="font-size: 1.3rem; margin: 0;">{{ \App\Models\Setting::get('site_name', 'Jornal a Borda') }}</h2>
                        @endif
                    </a>
                </div>
                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                <i class="bi bi-house-door me-1"></i>Início
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="categoriasDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-grid me-1"></i>Categorias
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="categoriasDropdown">
                                @foreach($categories ?? [] as $category)
                                <li><a class="dropdown-item" href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                                <i class="bi bi-info-circle me-1"></i>Sobre Nós
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('team') ? 'active' : '' }}" href="{{ route('team') }}">
                                <i class="bi bi-people me-1"></i>Nossa Equipe
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('journal-editions*') ? 'active' : '' }}" href="{{ route('journal-editions.index') }}">
                                <i class="bi bi-journal-text me-1"></i>Jornal Digital
                            </a>
                        </li>
                        @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i>Painel
                            </a>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Login
                            </a>
                        </li>
                        @endauth
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <!-- Breadcrumb -->
    @hasSection('breadcrumb')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main id="main-content" class="blog main-container py-4">
        @yield('content')
    </main>

    <!-- Partners Section -->
    @php
        $partners = \App\Models\Partner::where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get();
    @endphp
    @if($partners->count() > 0)
    <section class="partners-section py-5" style="background: #f8f9fa; border-top: 1px solid var(--border-color);">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h3 class="mb-2">Nossos Parceiros</h3>
                    <p class="text-muted">Empresas e organizações que apoiam nosso trabalho</p>
                </div>
            </div>
            
            @if($partners->count() > 6)
            <!-- Carrossel para muitos parceiros -->
            <div id="partnersCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-inner">
                    @foreach($partners->chunk(6) as $chunkIndex => $chunk)
                    <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                        <div class="row g-4">
                            @foreach($chunk as $partner)
                            <div class="col-6 col-md-4 col-lg-2">
                                <div class="partner-item text-center">
                                    @if($partner->website_url)
                                    <a href="{{ $partner->website_url }}" target="_blank" rel="noopener noreferrer" class="partner-link">
                                    @endif
                                        @if($partner->logo)
                                        <img src="{{ $partner->logo }}" alt="{{ $partner->name }}" 
                                             class="partner-logo img-fluid" 
                                             style="max-height: 80px; max-width: 100%; object-fit: contain; filter: grayscale(0.3); transition: var(--transition);">
                                        @else
                                        <div class="partner-placeholder" style="height: 80px; display: flex; align-items: center; justify-content: center; background: #e9ecef; border-radius: 8px;">
                                            <span class="text-muted">{{ $partner->name }}</span>
                                        </div>
                                        @endif
                                    @if($partner->website_url)
                                    </a>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#partnersCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#partnersCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Próximo</span>
                </button>
            </div>
            @else
            <!-- Grid simples para poucos parceiros -->
            <div class="row g-4">
                @foreach($partners as $partner)
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="partner-item text-center">
                        @if($partner->website_url)
                        <a href="{{ $partner->website_url }}" target="_blank" rel="noopener noreferrer" class="partner-link">
                        @endif
                            @if($partner->logo)
                            <img src="{{ $partner->logo }}" alt="{{ $partner->name }}" 
                                 class="partner-logo img-fluid" 
                                 style="max-height: 80px; max-width: 100%; object-fit: contain; filter: grayscale(0.3); transition: var(--transition);">
                            @else
                            <div class="partner-placeholder" style="height: 80px; display: flex; align-items: center; justify-content: center; background: #e9ecef; border-radius: 8px;">
                                <span class="text-muted">{{ $partner->name }}</span>
                            </div>
                            @endif
                        @if($partner->website_url)
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <div class="row mt-4">
                <div class="col-12 text-center">
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#advertiseModal">
                        <i class="bi bi-megaphone me-2"></i>Anuncie aqui / Colabore com essa causa
                    </button>
                </div>
            </div>
        </div>
    </section>
    @else
    <!-- Se não houver parceiros, mostrar apenas o botão -->
    <section class="partners-section py-5" style="background: #f8f9fa; border-top: 1px solid var(--border-color);">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#advertiseModal">
                        <i class="bi bi-megaphone me-2"></i>Anuncie aqui / Colabore com essa causa
                    </button>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Modal Anuncie Aqui -->
    <div class="modal fade" id="advertiseModal" tabindex="-1" aria-labelledby="advertiseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="advertiseModalLabel">
                        <i class="bi bi-megaphone me-2"></i>Anuncie aqui / Colabore com essa causa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Entre em contato conosco para se tornar um parceiro ou anunciar em nosso site:</p>
                    <div class="contact-info">
                        @php
                            $contactEmail = \App\Models\Setting::get('contact_email', 'contato@jornalaborda.com.br');
                            $contactPhone = \App\Models\Setting::get('contact_phone', '');
                            $contactWhatsApp = \App\Models\Setting::get('contact_whatsapp', '');
                        @endphp
                        @if($contactEmail)
                        <div class="mb-3">
                            <strong><i class="bi bi-envelope me-2"></i>E-mail:</strong><br>
                            <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                        </div>
                        @endif
                        @if($contactPhone)
                        <div class="mb-3">
                            <strong><i class="bi bi-telephone me-2"></i>Telefone:</strong><br>
                            <a href="tel:{{ $contactPhone }}">{{ $contactPhone }}</a>
                        </div>
                        @endif
                        @if($contactWhatsApp)
                        <div class="mb-3">
                            <strong><i class="bi bi-whatsapp me-2"></i>WhatsApp:</strong><br>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contactWhatsApp) }}" target="_blank" rel="noopener noreferrer">
                                {{ $contactWhatsApp }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="copy-right">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p>Desenvolvido com muito carinho pela equipe de T.I do Jornal a Borda ♥, {{ date('Y') }}</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
