<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jornal a Borda')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #ff2d20;
            --dark-bg: #1a1a1a;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .logo-area {
            padding: 15px 0;
        }
        .logo img {
            max-height: 50px;
        }
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-nav .nav-link {
            color: #333;
            font-weight: 500;
            padding: 10px 15px;
            transition: color 0.3s;
        }
        .navbar-nav .nav-link:hover {
            color: var(--primary-color);
        }
        .post-block-style {
            margin-bottom: 30px;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .post-block-style:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .post-thumb img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        .post-cat {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            text-decoration: none;
            margin-bottom: 10px;
        }
        .post-title a {
            color: #333;
            text-decoration: none;
            font-weight: 600;
        }
        .post-title a:hover {
            color: var(--primary-color);
        }
        .post-meta {
            color: #666;
            font-size: 14px;
        }
        .post-meta img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 8px;
        }
        .breadcrumb {
            background: transparent;
            padding: 15px 0;
        }
        .breadcrumb a {
            color: var(--primary-color);
            text-decoration: none;
        }
        .copy-right {
            background: var(--dark-bg);
            color: #fff;
            padding: 20px 0;
            margin-top: 50px;
        }
        .sidebar {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }
        .pagination .page-link {
            color: var(--primary-color);
        }
        .pagination .page-item.active .page-link {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Header Middle Area -->
    <div class="header-middle-area">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-lg-3 align-self-center">
                    <div class="logo-area">
                        <a href="{{ route('home') }}" class="logo">
                            <h2 class="text-primary fw-bold">Jornal a Borda</h2>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header id="header" class="header header-gradient">
        <div class="header-wrapper">
            <div class="container">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">Início</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="categoriasDropdown" role="button" data-bs-toggle="dropdown">
                                    Categorias
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach($categories ?? [] as $category)
                                    <li><a class="dropdown-item" href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Sobre Nós</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Nossa Equipe</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
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
    <main id="main-content" class="blog main-container">
        @yield('content')
    </main>

    <!-- Footer -->
    <div class="copy-right">
        <div class="container">
            <div class="row">
                <div class="col-md-11 align-self-center copyright-text text-center">
                    <p>Desenvolvido com muito carinho pela equipe de T.I do Jornal a Borda ♥, {{ date('Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

