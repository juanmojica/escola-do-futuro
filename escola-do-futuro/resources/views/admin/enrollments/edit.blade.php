@extends('layouts.admin')

@section('title', 'Editar Matrícula')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="fas fa-edit me-2"></i>Editar Matrícula</h2>
        <p class="text-muted mb-0">{{ $enrollment->student->name }} - {{ $enrollment->course->title }}</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.enrollments.update', $enrollment->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="alert alert-info mb-4">
                <i class="fas fa-info-circle me-2"></i>
                <small>Não é possível alterar o aluno ou curso após a matrícula ser criada</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    <i class="fas fa-user me-1"></i>Aluno
                </label>
                <input type="text" class="form-control" value="{{ $enrollment->student->name }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    <i class="fas fa-book me-1"></i>Curso
                </label>
                <input type="text" class="form-control" value="{{ $enrollment->course->title }}" disabled>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="enrollment_date" class="form-label fw-semibold">
                        <i class="fas fa-calendar me-1"></i>Data da Matrícula
                    </label>
                    <input type="date" class="form-control @error('enrollment_date') is-invalid @enderror" id="enrollment_date" name="enrollment_date" value="{{ old('enrollment_date', $enrollment->enrollment_date->format('Y-m-d')) }}">
                    @error('enrollment_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label fw-semibold">
                        <i class="fas fa-check-circle me-1"></i>Status
                    </label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="ativa" {{ old('status', $enrollment->status) == 'ativa' ? 'selected' : '' }}>Ativa</option>
                        <option value="concluida" {{ old('status', $enrollment->status) == 'concluida' ? 'selected' : '' }}>Concluída</option>
                        <option value="cancelada" {{ old('status', $enrollment->status) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Atualizar
                </button>
                <a href="{{ route('admin.enrollments.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
