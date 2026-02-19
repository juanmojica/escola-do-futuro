@extends('layouts.base')

@section('title', '404 - Página não encontrada')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 60vh;">
    <div class="col-md-6 text-center">
        <div class="error-template">
            <h1 class="display-1 text-primary">
                <i class="fas fa-search"></i>
            </h1>
            <h1 class="display-4 mb-3">404</h1>
            <h2 class="mb-4">Página não encontrada</h2>
            <p class="text-muted mb-4">
                Desculpe, a página que você está procurando não existe ou foi movida.
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
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .error-template h1.display-1 {
        font-size: 8rem;
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
    
    .gap-2 {
        gap: 0.5rem !important;
    }
</style>
@endpush
