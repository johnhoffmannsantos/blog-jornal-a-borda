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
            --primary-color: #1A25FF;
            --primary-dark: #1419CC;
            --secondary-color: #4A54FF;
            --sidebar-bg: #1A25FF;
            --sidebar-hover: #1419CC;
            --text-dark: #000000;
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
            background: #1A25FF;
            color: white;
        }

        .role-badge.editor {
            background: #4A54FF;
            color: white;
        }

        .role-badge.author {
            background: #6B73FF;
            color: white;
        }

        .btn-primary {
            background: #1A25FF;
            border-color: #1A25FF;
        }

        .btn-primary:hover {
            background: #1419CC;
            border-color: #1419CC;
        }

        .btn-outline-primary {
            border-color: #1A25FF;
            color: #1A25FF;
        }

        .btn-outline-primary:hover {
            background: #1A25FF;
            border-color: #1A25FF;
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
            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&size=80&background=1A25FF&color=fff" 
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
            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&size=120&background=1A25FF&color=fff" 
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
            <a class="nav-link {{ request()->routeIs('admin.profile*') ? 'active' : '' }}" href="{{ route('admin.profile') }}">
                <i class="bi bi-person"></i>
                <span>Perfil</span>
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
    </div>

    <!-- Main Content -->
    <main class="admin-content">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Global Loading Script for Submit Buttons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Aplicar loading em todos os botões de submit
            const forms = document.querySelectorAll('form');
            
            forms.forEach(function(form) {
                // Pular formulários que já têm validação customizada
                if (form.id === 'journalEditionForm' || form.id === 'settingsForm' || form.id === 'avatarForm' || form.id === 'partnerForm') {
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

