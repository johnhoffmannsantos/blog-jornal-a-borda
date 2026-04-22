<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Painel Administrativo - Jornal a Borda')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --sidebar-width: 264px;
            --primary-500: #4f46e5;
            --primary-600: #4338ca;
            --surface-bg: #f3f4f6;
            --surface-card: #ffffff;
            --surface-soft: #f9fafb;
            --text-main: #111827;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --shadow-sm: 0 1px 2px rgba(17, 24, 39, 0.06);
            --shadow-md: 0 8px 30px rgba(17, 24, 39, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--surface-bg);
            color: var(--text-main);
            font-size: 14px;
            line-height: 1.45;
            overflow-x: hidden;
            transition: background-color .2s ease, color .2s ease;
        }

        .admin-shell {
            min-height: 100vh;
        }

        .admin-topbar {
            position: sticky;
            top: 0;
            z-index: 40;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 64px;
            padding: 10px 18px;
            border-bottom: 1px solid var(--border-color);
            background: color-mix(in srgb, var(--surface-card) 92%, transparent);
            backdrop-filter: blur(8px);
        }

        .admin-topbar .logo {
            font-size: 1.06rem;
            font-weight: 700;
            color: var(--text-main);
            text-decoration: none;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .admin-topbar .logo img {
            height: 34px;
            width: auto;
            max-width: 210px;
            object-fit: contain;
        }

        .topbar-action {
            border: 1px solid var(--border-color);
            background: var(--surface-card);
            color: var(--text-main);
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all .2s ease;
        }

        .topbar-action:hover {
            background: var(--surface-soft);
            color: var(--primary-500);
        }

        .admin-topbar .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
        }

        .admin-topbar .user-info .role-badge {
            margin-top: 3px;
        }

        .admin-topbar .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            border: 2px solid var(--border-color);
        }

        .user-menu-btn {
            border: 0;
            background: transparent;
            color: inherit;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 4px 6px;
            border-radius: 10px;
            transition: background-color .15s ease;
        }

        .user-menu-btn:hover {
            background: var(--surface-soft);
        }

        .user-menu {
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            width: 250px;
            min-width: 220px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background: var(--surface-card);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            z-index: 80;
        }

        .user-menu-item {
            display: flex;
            width: 100%;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            text-decoration: none;
            background: transparent;
            border: 0;
            color: var(--text-main);
            font-size: .9rem;
            transition: background-color .15s ease;
        }

        .user-menu-item:hover {
            background: var(--surface-soft);
        }

        .admin-sidebar {
            position: fixed;
            top: 64px;
            left: 0;
            z-index: 50;
            width: var(--sidebar-width);
            height: calc(100vh - 64px);
            background: var(--surface-card);
            border-right: 1px solid var(--border-color);
            overflow-y: auto;
            transition: transform .24s ease;
        }

        .admin-sidebar .nav {
            padding: 12px 10px 16px;
        }

        .admin-sidebar .nav-link {
            color: color-mix(in srgb, var(--text-main) 86%, transparent);
            padding: 10px 13px;
            border-radius: 11px;
            margin-bottom: 4px;
            transition: all .18s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            font-weight: 500;
        }

        .admin-sidebar .nav-link:hover {
            background: color-mix(in srgb, var(--primary-500) 10%, var(--surface-card));
            color: var(--primary-500);
        }

        .admin-sidebar .nav-link.active {
            background: color-mix(in srgb, var(--primary-500) 17%, var(--surface-card));
            color: var(--primary-500);
            font-weight: 600;
        }

        .admin-sidebar .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1.02rem;
        }

        .sidebar-overlay {
            position: fixed;
            inset: 64px 0 0;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(1px);
            z-index: 45;
            opacity: 0;
            pointer-events: none;
            transition: opacity .2s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
            pointer-events: auto;
        }

        .admin-content {
            margin-left: var(--sidebar-width);
            min-height: calc(100vh - 64px);
            padding: 22px;
        }

        .admin-content .page-header {
            margin-bottom: 20px;
        }

        .admin-content .page-header h1 {
            font-size: 1.65rem;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .admin-content .page-header p {
            color: var(--text-muted);
            margin: 0;
        }

        .admin-card {
            background: var(--surface-card);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            box-shadow: var(--shadow-sm);
            padding: 18px;
            margin-bottom: 18px;
        }

        .admin-card .card-header {
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 12px;
            margin-bottom: 14px;
            background: transparent;
        }

        .admin-card .card-header h5 {
            font-size: 1.03rem;
            font-weight: 700;
            margin: 0;
        }

        .stat-card {
            background: var(--surface-card);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 16px;
            box-shadow: var(--shadow-sm);
            transition: transform .16s ease, box-shadow .16s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stat-card .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .stat-card .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 2px;
        }

        .stat-card .stat-label {
            color: var(--text-muted);
            font-size: 12px;
            margin: 0;
        }

        .stat-card.stat-card-compact {
            border-radius: 12px;
        }

        .badge-sidebar {
            background: var(--primary-500);
            color: #fff;
            font-weight: 600;
            font-size: 0.72rem;
        }

        .table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--text-main);
            --bs-table-hover-color: var(--text-main);
            --bs-table-hover-bg: color-mix(in srgb, var(--primary-500) 6%, transparent);
            --bs-table-border-color: var(--border-color);
            margin-bottom: 0;
        }

        .table thead th {
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: var(--text-muted);
            font-weight: 700;
            border-bottom-width: 1px;
        }

        .table-actions {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            justify-content: flex-end;
        }

        .action-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background: var(--surface-card);
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all .16s ease;
        }

        .action-icon:hover {
            transform: translateY(-1px);
        }

        .action-icon i {
            font-size: .95rem;
            line-height: 1;
        }

        .action-icon--view:hover {
            color: #2563eb;
            border-color: #93c5fd;
            background: #eff6ff;
        }

        .action-icon--edit:hover {
            color: #4f46e5;
            border-color: #c4b5fd;
            background: #eef2ff;
        }

        .action-icon--success:hover {
            color: #059669;
            border-color: #a7f3d0;
            background: #ecfdf5;
        }

        .action-icon--warn:hover {
            color: #b45309;
            border-color: #fcd34d;
            background: #fffbeb;
        }

        .action-icon--delete:hover {
            color: #dc2626;
            border-color: #fca5a5;
            background: #fef2f2;
        }

        .form-control,
        .form-select {
            border-color: var(--border-color);
            background: var(--surface-card);
            color: var(--text-main);
            border-radius: 10px;
            min-height: 40px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: color-mix(in srgb, var(--primary-500) 70%, white);
            box-shadow: 0 0 0 .2rem rgba(79, 70, 229, .16);
            background: var(--surface-card);
            color: var(--text-main);
        }

        .offcanvas,
        .offcanvas-header,
        .offcanvas-body {
            background: var(--surface-card) !important;
            color: var(--text-main);
        }

        .modal-content {
            background: var(--surface-card);
            color: var(--text-main);
            border: 1px solid var(--border-color);
        }

        .role-badge {
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .2px;
        }

        .role-badge.admin { background: #4f46e5; color: white; }
        .role-badge.editor { background: #0ea5e9; color: white; }
        .role-badge.author { background: #14b8a6; color: white; }

        .btn-primary {
            background: var(--primary-500);
            border-color: var(--primary-500);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: var(--primary-600);
            border-color: var(--primary-600);
        }

        .btn-outline-primary {
            border-color: var(--primary-500);
            color: var(--primary-500);
        }

        .btn-outline-primary:hover {
            background: var(--primary-500);
            border-color: var(--primary-500);
            color: #fff;
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        @media (max-width: 992px) {
            .admin-sidebar {
                transform: translateX(-102%);
                box-shadow: var(--shadow-md);
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-content {
                margin-left: 0;
                padding: 16px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-shell">
    <!-- Top Bar -->
    @php
        $adminLogo = \App\Models\Setting::get('site_logo');
        $adminSiteName = \App\Models\Setting::get('site_name') ?: 'Jornal a Borda';
    @endphp
    <div class="admin-topbar">
        <div class="d-flex align-items-center gap-2 gap-md-3">
            <button class="topbar-action d-lg-none" id="openSidebar" type="button" aria-label="Abrir menu">
                <i class="bi bi-list fs-5"></i>
            </button>
            <a href="{{ route('admin.dashboard') }}" class="logo">
                @if($adminLogo)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($adminLogo) }}" alt="{{ $adminSiteName }}">
                @endif
            </a>
        </div>
        <div class="user-info">
            <a href="{{ route('home') }}" class="text-muted text-decoration-none d-none d-md-inline-flex align-items-center" target="_blank">
                <i class="bi bi-box-arrow-up-right me-1"></i>Ver Site
            </a>
            <button id="userMenuBtn" class="user-menu-btn" type="button" aria-expanded="false" aria-haspopup="true">
                <div class="text-end">
                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&size=80&background=1A25FF&color=fff" 
                     class="user-avatar" alt="{{ auth()->user()->name }}">
            </button>
            <div id="userMenu" class="user-menu d-none">
                <a href="{{ route('admin.profile') }}" class="user-menu-item border-bottom">
                    <i class="bi bi-person-circle"></i>
                    <span>Perfil</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="user-menu-item text-danger">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Sair</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <aside class="admin-sidebar" id="adminSidebar">
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
            <a class="nav-link {{ request()->routeIs('admin.posts*') ? 'active' : '' }}" href="{{ route('admin.posts.index') }}">
                <i class="bi bi-file-text"></i>
                <span>Posts</span>
            </a>
            <a class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                <i class="bi bi-folder"></i>
                <span>Categorias</span>
            </a>
            <a class="nav-link {{ request()->routeIs('admin.tags*') ? 'active' : '' }}" href="{{ route('admin.tags.index') }}">
                <i class="bi bi-tags"></i>
                <span>Tags</span>
            </a>
            <a class="nav-link {{ request()->routeIs('admin.comments*') ? 'active' : '' }}" href="{{ route('admin.comments.index') }}">
                <i class="bi bi-chat-dots"></i>
                <span>Comentários</span>
            </a>
            @if(auth()->user()->isAdmin())
            <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <i class="bi bi-people"></i>
                <span>Usuários</span>
            </a>
            <a class="nav-link {{ request()->routeIs('admin.partners*') ? 'active' : '' }}" href="{{ route('admin.partners.index') }}">
                <i class="bi bi-building"></i>
                <span>Parceiros</span>
            </a>
            <a class="nav-link {{ request()->routeIs('admin.journal-editions*') ? 'active' : '' }}" href="{{ route('admin.journal-editions.index') }}">
                <i class="bi bi-journal-text"></i>
                <span>Jornal Digital</span>
            </a>
            <a class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                <i class="bi bi-gear"></i>
                <span>Configurações</span>
            </a>
            @endif
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="admin-content">
        @yield('content')
    </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('adminSidebar');
            const openSidebar = document.getElementById('openSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const userMenuBtn = document.getElementById('userMenuBtn');
            const userMenu = document.getElementById('userMenu');

            function closeSidebar() {
                if (!sidebar || !overlay) return;
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }

            if (openSidebar && sidebar && overlay) {
                openSidebar.addEventListener('click', function() {
                    sidebar.classList.add('show');
                    overlay.classList.add('show');
                });
                overlay.addEventListener('click', closeSidebar);
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 992) {
                        closeSidebar();
                    }
                });
            }

            function closeUserMenu() {
                if (!userMenu || !userMenuBtn) return;
                userMenu.classList.add('d-none');
                userMenuBtn.setAttribute('aria-expanded', 'false');
            }

            if (userMenuBtn && userMenu) {
                userMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isHidden = userMenu.classList.contains('d-none');
                    if (isHidden) {
                        userMenu.classList.remove('d-none');
                        userMenuBtn.setAttribute('aria-expanded', 'true');
                    } else {
                        closeUserMenu();
                    }
                });

                document.addEventListener('click', function(e) {
                    if (!userMenu.contains(e.target) && !userMenuBtn.contains(e.target)) {
                        closeUserMenu();
                    }
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeUserMenu();
                    }
                });
            }
        });
    </script>
    
    <!-- Global Loading Script for Submit Buttons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Aplicar loading em todos os botões de submit
            const forms = document.querySelectorAll('form');
            
            forms.forEach(function(form) {
                // Pular formulários que já têm validação customizada
                if (form.id === 'postForm' || form.id === 'journalEditionForm' || form.id === 'settingsForm' || form.id === 'avatarForm' || form.id === 'partnerForm' || form.id === 'cronPublishForm' || form.id === 'cronScheduleListForm' || form.id === 'testEmailForm') {
                    return;
                }
                
                form.addEventListener('submit', function(e) {
                    // Não interferir se o formulário já foi validado e está sendo submetido
                    if (form.dataset.validated === 'true') {
                        return;
                    }
                    
                    const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
                    
                    submitButtons.forEach(function(button) {
                        if (!button.disabled && !button.classList.contains('loading')) {
                            button.classList.add('loading');
                            button.disabled = true;
                            
                            // Salvar conteúdo original
                            const originalHTML = button.innerHTML;
                            button.dataset.originalHTML = originalHTML;
                            
                            // Adicionar spinner e texto
                            const icon = button.querySelector('i');
                            if (icon) {
                                icon.style.display = 'none';
                            }
                            
                            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Salvando...';
                        }
                    });
                });
            });
        });
    </script>
    
    <style>
        .btn.loading {
            pointer-events: none;
            opacity: 0.7;
            position: relative;
        }
        
        .btn.loading .spinner-border {
            display: inline-block !important;
        }
    </style>
    
    <!-- Modal de Confirmação -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">
                        <i class="bi bi-exclamation-triangle me-2"></i>Confirmar Exclusão
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmDeleteMessage">Tem certeza que deseja excluir este item?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </button>
                    <form id="confirmDeleteForm" method="POST" style="display: inline;">
                        <button type="submit" class="btn btn-danger" id="confirmDeleteButton">
                            <i class="bi bi-trash me-2"></i>Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div id="toastNotification" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
            <div class="toast-header">
                <i id="toastIcon" class="bi me-2"></i>
                <strong class="me-auto" id="toastTitle">Notificação</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Fechar"></button>
            </div>
            <div class="toast-body" id="toastMessage">
                Mensagem aqui
            </div>
        </div>
    </div>

    <script>
        // Sistema de Toasts
        function showToast(type, message, title = null) {
            const toast = document.getElementById('toastNotification');
            const toastIcon = document.getElementById('toastIcon');
            const toastTitle = document.getElementById('toastTitle');
            const toastMessage = document.getElementById('toastMessage');
            const toastInstance = new bootstrap.Toast(toast);

            // Configurar ícone e cor baseado no tipo
            let iconClass = '';
            let bgClass = '';
            
            switch(type) {
                case 'success':
                    iconClass = 'bi-check-circle-fill text-success';
                    bgClass = 'bg-success text-white';
                    title = title || 'Sucesso!';
                    break;
                case 'error':
                case 'danger':
                    iconClass = 'bi-exclamation-triangle-fill text-danger';
                    bgClass = 'bg-danger text-white';
                    title = title || 'Erro!';
                    break;
                case 'warning':
                    iconClass = 'bi-exclamation-circle-fill text-warning';
                    bgClass = 'bg-warning text-dark';
                    title = title || 'Atenção!';
                    break;
                case 'info':
                    iconClass = 'bi-info-circle-fill text-info';
                    bgClass = 'bg-info text-white';
                    title = title || 'Informação';
                    break;
                default:
                    iconClass = 'bi-info-circle-fill text-primary';
                    bgClass = 'bg-primary text-white';
                    title = title || 'Notificação';
            }

            toastIcon.className = iconClass + ' me-2';
            toastTitle.textContent = title;
            toastMessage.textContent = message;
            
            // Aplicar cor ao header
            toast.querySelector('.toast-header').className = 'toast-header ' + bgClass;
            
            toastInstance.show();
        }

        // Mostrar toast baseado em mensagens de sessão
        @if(session('success'))
            showToast('success', @json(session('success')));
        @endif

        @if(session('error'))
            showToast('error', @json(session('error')));
        @endif

        @if(session('info'))
            showToast('info', @json(session('info')));
        @endif

        @if(session('warning'))
            showToast('warning', @json(session('warning')));
        @endif

        // Sistema de Modal de Confirmação
        document.addEventListener('DOMContentLoaded', function() {
            const confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            const confirmForm = document.getElementById('confirmDeleteForm');
            const confirmMessage = document.getElementById('confirmDeleteMessage');

            // Interceptar todos os formulários de exclusão
            document.querySelectorAll('form[method="POST"]').forEach(function(form) {
                const methodInput = form.querySelector('input[name="_method"][value="DELETE"]');
                
                if (methodInput) {
                    // É um formulário de exclusão
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Obter mensagem do onsubmit ou usar padrão
                        const onsubmitAttr = form.getAttribute('onsubmit');
                        let message = 'Tem certeza que deseja excluir este item?';
                        
                        if (onsubmitAttr && onsubmitAttr.includes('confirm')) {
                            // Extrair mensagem do confirm
                            const match = onsubmitAttr.match(/confirm\(['"]([^'"]+)['"]\)/);
                            if (match) {
                                message = match[1].replace(/\\'/g, "'").replace(/\\"/g, '"');
                            }
                        }
                        
                        confirmMessage.textContent = message;
                        confirmForm.action = form.action;
                        
                        // Limpar apenas os inputs hidden, mantendo o botão
                        const confirmButton = document.getElementById('confirmDeleteButton');
                        confirmForm.querySelectorAll('input[type="hidden"]').forEach(function(input) {
                            input.remove();
                        });
                        
                        // Adicionar CSRF token
                        const csrfToken = form.querySelector('input[name="_token"]');
                        if (csrfToken) {
                            const tokenInput = csrfToken.cloneNode(true);
                            confirmForm.insertBefore(tokenInput, confirmButton);
                        }
                        
                        // Adicionar método DELETE
                        const methodInputNew = document.createElement('input');
                        methodInputNew.type = 'hidden';
                        methodInputNew.name = '_method';
                        methodInputNew.value = 'DELETE';
                        confirmForm.insertBefore(methodInputNew, confirmButton);
                        
                        // Adicionar outros campos hidden se houver
                        form.querySelectorAll('input[type="hidden"]:not([name="_token"]):not([name="_method"])').forEach(function(input) {
                            const hiddenInput = input.cloneNode(true);
                            confirmForm.insertBefore(hiddenInput, confirmButton);
                        });
                        
                        confirmModal.show();
                    });
                    
                    // Remover onsubmit original se existir
                    if (form.hasAttribute('onsubmit')) {
                        form.removeAttribute('onsubmit');
                    }
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>

