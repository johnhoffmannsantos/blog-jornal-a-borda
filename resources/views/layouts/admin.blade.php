<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Painel Administrativo - Jornal a Borda')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #e63946;
            --primary-dark: #d62839;
            --secondary-color: #457b9d;
            --sidebar-bg: #1d3557;
            --sidebar-hover: #2a4a6b;
            --text-dark: #2b2d42;
            --text-light: #6c757d;
            --border-color: #e9ecef;
            --bg-light: #f8f9fa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg-light);
            color: var(--text-dark);
            font-size: 14px;
        }

        /* Top Bar */
        .admin-topbar {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .admin-topbar .logo {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
        }

        .admin-topbar .user-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .admin-topbar .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid var(--border-color);
        }

        /* Sidebar */
        .admin-sidebar {
            background: var(--sidebar-bg);
            color: white;
            width: 260px;
            min-height: calc(100vh - 60px);
            position: fixed;
            left: 0;
            top: 60px;
            padding: 24px 0;
            overflow-y: auto;
        }

        .admin-sidebar .user-card {
            padding: 0 24px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 16px;
        }

        .admin-sidebar .user-card img {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,0.2);
            margin-bottom: 12px;
        }

        .admin-sidebar .user-card h6 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .admin-sidebar .user-card small {
            color: rgba(255,255,255,0.7);
            font-size: 12px;
        }

        .admin-sidebar .nav {
            padding: 0 12px;
        }

        .admin-sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 4px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .admin-sidebar .nav-link:hover {
            background: var(--sidebar-hover);
            color: white;
        }

        .admin-sidebar .nav-link.active {
            background: var(--primary-color);
            color: white;
        }

        .admin-sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .admin-content {
            margin-left: 260px;
            padding: 24px;
            min-height: calc(100vh - 60px);
        }

        .admin-content .page-header {
            margin-bottom: 24px;
        }

        .admin-content .page-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .admin-content .page-header p {
            color: var(--text-light);
            margin: 0;
        }

        /* Cards */
        .admin-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 24px;
            margin-bottom: 24px;
        }

        .admin-card .card-header {
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 16px;
            margin-bottom: 16px;
        }

        .admin-card .card-header h5 {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 12px;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .stat-card .stat-label {
            color: var(--text-light);
            font-size: 13px;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-content {
                margin-left: 0;
            }
        }

        /* Badges */
        .role-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .role-badge.admin {
            background: #dc3545;
            color: white;
        }

        .role-badge.editor {
            background: #0d6efd;
            color: white;
        }

        .role-badge.author {
            background: #198754;
            color: white;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Top Bar -->
    <div class="admin-topbar">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="logo">
                <i class="bi bi-speedometer2 me-2"></i>Painel Admin
            </a>
            <a href="{{ route('home') }}" class="text-muted text-decoration-none" target="_blank">
                <i class="bi bi-box-arrow-up-right me-1"></i>Ver Site
            </a>
        </div>
        <div class="user-info">
            <div class="text-end">
                <div class="fw-semibold">{{ auth()->user()->name }}</div>
                <small class="text-muted">{{ auth()->user()->position ?? ucfirst(auth()->user()->role) }}</small>
            </div>
            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&size=80&background=e63946&color=fff" 
                 class="user-avatar" alt="{{ auth()->user()->name }}">
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-box-arrow-right me-1"></i>Sair
                </button>
            </form>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="user-card text-center">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&size=120&background=e63946&color=fff" 
                 alt="{{ auth()->user()->name }}">
            <h6>{{ auth()->user()->name }}</h6>
            <small>{{ auth()->user()->position ?? ucfirst(auth()->user()->role) }}</small>
            <div class="mt-2">
                <span class="role-badge {{ auth()->user()->role }}">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </div>
        </div>

        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
            <a class="nav-link" href="#">
                <i class="bi bi-person"></i>
                <span>Perfil</span>
            </a>
            <a class="nav-link" href="#">
                <i class="bi bi-file-text"></i>
                <span>Posts</span>
            </a>
            <a class="nav-link" href="#">
                <i class="bi bi-chat-dots"></i>
                <span>Comentários</span>
            </a>
            @if(auth()->user()->isAdmin())
            <a class="nav-link" href="#">
                <i class="bi bi-gear"></i>
                <span>Configurações</span>
            </a>
            @endif
        </nav>
    </div>

    <!-- Main Content -->
    <main class="admin-content">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

