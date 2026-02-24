@extends('layouts.admin')

@section('title', 'Comentários - Painel Administrativo')

@section('content')
<div class="page-header">
    <h1>
        <i class="bi bi-chat-dots me-2"></i>Comentários
    </h1>
    <p>Gerencie todos os comentários dos posts</p>
</div>

{{-- Mensagens serão exibidas via Toast --}}

<!-- Filtros -->
<div class="admin-card mb-4">
    <form method="GET" action="{{ route('admin.comments.index') }}" class="row g-3">
        <div class="col-md-8">
            <label class="form-label">Buscar</label>
            <input type="text" class="form-control" name="search" 
                   value="{{ request('search') }}" placeholder="Nome do autor ou conteúdo...">
        </div>
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status">
                <option value="">Todos</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Aprovado</option>
                <option value="spam" {{ request('status') === 'spam' ? 'selected' : '' }}>Spam</option>
            </select>
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button type="submit" class="btn btn-outline-primary w-100">
                <i class="bi bi-funnel"></i>
            </button>
        </div>
    </form>
</div>

<!-- Lista de Comentários -->
<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Autor</th>
                    <th>Comentário</th>
                    <th>Post</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th style="width: 200px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($comments as $comment)
                <tr>
                    <td>
                        <strong>{{ $comment->author_name }}</strong><br>
                        <small class="text-muted">{{ $comment->author_email }}</small>
                    </td>
                    <td>
                        <p class="mb-0">{{ \Illuminate\Support\Str::limit($comment->content, 100) }}</p>
                    </td>
                    <td>
                        <a href="{{ route('post.show', $comment->post->slug) }}" target="_blank" class="text-decoration-none">
                            {{ \Illuminate\Support\Str::limit($comment->post->title, 40) }}
                        </a>
                    </td>
                    <td>
                        <span class="badge bg-{{ $comment->status === 'approved' ? 'success' : ($comment->status === 'pending' ? 'warning' : 'danger') }}">
                            {{ $comment->status === 'approved' ? 'Aprovado' : ($comment->status === 'pending' ? 'Pendente' : 'Spam') }}
                        </span>
                    </td>
                    <td>
                        <small>{{ $comment->created_at->format('d/m/Y H:i') }}</small>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            @if($comment->status !== 'approved')
                            <form method="POST" action="{{ route('admin.comments.updateStatus', $comment) }}" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-outline-success" title="Aprovar">
                                    <i class="bi bi-check-circle"></i>
                                </button>
                            </form>
                            @endif
                            @if($comment->status !== 'spam')
                            <form method="POST" action="{{ route('admin.comments.updateStatus', $comment) }}" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="spam">
                                <button type="submit" class="btn btn-outline-danger" title="Marcar como Spam">
                                    <i class="bi bi-shield-exclamation"></i>
                                </button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" class="d-inline"
                                  onsubmit="return confirm('Tem certeza que deseja excluir este comentário?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" title="Excluir">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Nenhum comentário encontrado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $comments->links() }}
    </div>
</div>
@endsection

