@extends('layouts.admin')

@section('title', 'Novo Aluno')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="fas fa-user-plus me-2"></i>Novo Aluno</h2>
        <p class="text-muted mb-0">Preencha os dados para cadastrar um novo aluno</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.students.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">
                    <i class="fas fa-user me-1"></i>Nome *
                </label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="Digite o nome completo">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">
                    <i class="fas fa-envelope me-1"></i>Email *
                </label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="exemplo@email.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="birth_date" class="form-label fw-semibold">
                    <i class="fas fa-calendar-alt me-1"></i>Data de Nascimento
                </label>
                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                @error('birth_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="alert alert-info mt-4 mb-3">
                <i class="fas fa-info-circle me-2"></i>
                <small>Preencha os campos de senha apenas se desejar criar uma conta de acesso para o aluno</small>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label fw-semibold">
                        <i class="fas fa-lock me-1"></i>Senha (opcional)
                    </label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Mínimo 8 caracteres">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label fw-semibold">
                        <i class="fas fa-lock me-1"></i>Confirmar Senha
                    </label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Repita a senha">
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Salvar
                </button>
                <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
