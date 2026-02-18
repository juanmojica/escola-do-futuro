@extends('layouts.base')

@section('title', '500 - Erro no servidor')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 60vh;">
    <div class="col-md-6 text-center">
        <div class="error-template">
            <h1 class="display-1 text-danger">
                <i class="fas fa-exclamation-triangle"></i>
            </h1>
            <h1 class="display-4 mb-3">500</h1>
            <h2 class="mb-4">Erro no servidor</h2>
            <p class="text-muted mb-4">
                Desculpe, algo deu errado no nosso servidor. Nossa equipe já foi notificada e está trabalhando para corrigir o problema.
            </p>
            <div class="d-flex gap-2 justify-content-center flex-wrap">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Voltar
                </a>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-home me-1"></i>
                            Dashboard
                        </a>
                    @elseif(auth()->user()->isStudent())
                        <a href="{{ route('student.dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-home me-1"></i>
                            Início
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt me-1"></i>
                        Login
                    </a>
                @endauth
            </div>
            
            @if(config('app.debug'))
                <div class="alert alert-warning mt-4 text-start">
                    <strong><i class="fas fa-bug"></i> Modo Debug:</strong> Este erro está sendo exibido porque APP_DEBUG=true
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .error-template h1.display-1 {
        font-size: 8rem;
        animation: shake 0.5s infinite;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }
    
    .gap-2 {
        gap: 0.5rem !important;
    }
</style>
@endpush
