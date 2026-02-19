@extends('layouts.student')

@section('title', 'Editar Perfil')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="fas fa-user-edit me-2"></i>Editar Meu Perfil</h2>
        <p class="text-muted mb-0">Atualize suas informações pessoais</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('student.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label fw-semibold required">
                        <i class="fas fa-user me-1"></i>Nome Completo
                    </label>
                    <input 
                        type="text" 
                        class="form-control @error('name') is-invalid @enderror" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $student->name) }}"
                        required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label fw-semibold required">
                        <i class="fas fa-envelope me-1"></i>E-mail
                    </label>
                    <input 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        id="email" 
                        name="email" 
                        value="{{ old('email', $student->email) }}" 
                        required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="birth_date" class="form-label fw-semibold">
                    <i class="fas fa-calendar me-1"></i>Data de Nascimento
                </label>
                <input 
                    type="date" 
                    class="form-control @error('birth_date') is-invalid @enderror" 
                    id="birth_date" 
                    name="birth_date" 
                    value="{{ old('birth_date', $student->birth_date ? $student->birth_date->format('Y-m-d') : '') }}">
                @error('birth_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <hr class="my-4">

            <h5 class="mb-3 fw-semibold">
                <i class="fas fa-key me-2"></i>Alterar Senha
            </h5>

            <div class="alert alert-warning border-0 mb-3">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Deixe os campos de senha em branco para manter a senha atual
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label fw-semibold">
                        <i class="fas fa-lock me-1"></i>Nova Senha
                    </label>
                    <input 
                        type="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        id="password" 
                        name="password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold">
                        <i class="fas fa-lock me-1"></i>Confirmar Nova Senha
                    </label>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="password_confirmation" 
                        name="password_confirmation">
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Atualizar Perfil
                </button>
                <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
