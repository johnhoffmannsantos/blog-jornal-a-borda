@extends('layouts.app')

@section('title', 'Login - Jornal a Borda')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0" style="border-radius: 16px; margin-top: 60px; margin-bottom: 60px;">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="mb-2" style="font-family: 'Playfair Display', serif; color: var(--text-dark);">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </h2>
                        <p class="text-muted">Acesse o painel administrativo</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required autofocus 
                                   placeholder="seu@email.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Senha</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required placeholder="••••••••">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Lembrar-me
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 mb-3" style="font-weight: 600;">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
                        </button>
                    </form>

                    @if(app()->environment('local'))
                    <div class="mt-4 pt-4 border-top">
                        <p class="text-muted text-center small mb-3">Acesso Rápido (Desenvolvimento)</p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('quick-login', 'admin') }}" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-shield-check me-1"></i>Admin
                            </a>
                            <a href="{{ route('quick-login', 'editor') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-pencil-square me-1"></i>Editor
                            </a>
                            <a href="{{ route('quick-login', 'author') }}" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-person me-1"></i>Redatora
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

