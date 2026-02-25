@extends('layouts.admin')

@section('title', 'Tags - Painel Administrativo')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>
            <i class="bi bi-tags me-2"></i>Tags
        </h1>
        <p>Gerencie as tags do blog</p>
    </div>
    <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Nova Tag
    </a>
</div>

{{-- Mensagens serão exibidas via Toast --}}

<!-- Gráficos -->
@if($chartData->count() > 0)
<div class="row mb-4">
    <div class="col-lg-6">
        <div class="admin-card">
            <div class="card-header">
                <h5>
                    <i class="bi bi-bar-chart me-2"></i>Top 10 Tags Mais Usadas
                </h5>
            </div>
            <div class="card-body">
                <canvas id="tagsBarChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="admin-card">
            <div class="card-header">
                <h5>
                    <i class="bi bi-pie-chart me-2"></i>Percentual de Uso
                </h5>
            </div>
            <div class="card-body">
                <canvas id="tagsPieChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>
@endif

<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Slug</th>
                    <th>Posts</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tags as $tag)
                <tr>
                    <td>{{ $tag->id }}</td>
                    <td>
                        <strong>{{ $tag->name }}</strong>
                    </td>
                    <td>
                        <code class="text-muted">{{ $tag->slug }}</code>
                    </td>
                    <td>
                        <span class="badge bg-info">{{ $tag->posts_count }} post(s)</span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($tag->posts_count > 0)
                            <form action="{{ route('admin.tags.forceDestroy', $tag) }}" method="POST" class="d-inline" 
                                  onsubmit="return confirm('Tem certeza? Esta tag está atrelada a {{ $tag->posts_count }} post(s). O atrelamento será removido.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir (removerá atrelamento)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @else
                            <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="d-inline" 
                                  onsubmit="return confirm('Tem certeza que deseja excluir esta tag?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <p class="text-muted mb-0">Nenhuma tag encontrada.</p>
                        <a href="{{ route('admin.tags.create') }}" class="btn btn-sm btn-primary mt-2">
                            <i class="bi bi-plus-circle me-1"></i>Criar Primeira Tag
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($tags->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $tags->links() }}
    </div>
    @endif
</div>

@push('styles')
<style>
    .admin-card .card-body canvas {
        max-height: 300px;
    }
    @media (max-width: 991.98px) {
        .row.mb-4 > div {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
@if($chartData->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Dados do gráfico
    const tagsData = @json($chartData);
    
    // Cores para os gráficos
    const colors = [
        '#1A25FF', '#4A54FF', '#6B73FF', '#8C92FF', '#ADB1FF',
        '#CECFFF', '#F0F1FF', '#1419CC', '#0F14B3', '#0A0F99',
        '#050A80', '#0005FF', '#3338FF', '#666BFF', '#999EFF'
    ];

    // Gráfico de Barras
    const barCtx = document.getElementById('tagsBarChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: tagsData.map(tag => tag.name),
            datasets: [{
                label: 'Número de Posts',
                data: tagsData.map(tag => tag.posts_count),
                backgroundColor: colors.slice(0, tagsData.length),
                borderColor: colors.slice(0, tagsData.length).map(c => c + 'dd'),
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' post(s)';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Gráfico de Pizza
    const pieCtx = document.getElementById('tagsPieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: tagsData.map(tag => tag.name),
            datasets: [{
                data: tagsData.map(tag => tag.posts_count),
                backgroundColor: colors.slice(0, tagsData.length),
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 15,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} post(s) (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
</script>
@endif
@endpush
@endsection

