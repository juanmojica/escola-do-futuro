@extends('layouts.admin')

@section('title', 'Nova Matrícula')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="fas fa-clipboard-check me-2"></i>Nova Matrícula</h2>
        <p class="text-muted mb-0">Preencha os dados para matricular um aluno em um curso</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.enrollments.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="student_id" class="form-label fw-semibold">
                    <i class="fas fa-user me-1"></i>Aluno *
                </label>
                <select class="form-select @error('student_id') is-invalid @enderror" id="student_id" name="student_id" required>
                    <option value="">Selecione um aluno</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>{{ $student->name }} ({{ $student->email }})</option>
                    @endforeach
                </select>
                @error('student_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="course_id" class="form-label fw-semibold">
                    <i class="fas fa-book me-1"></i>Curso *
                </label>
                <select class="form-select @error('course_id') is-invalid @enderror" id="course_id" name="course_id" required>
                    <option value="">Selecione um curso</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                    @endforeach
                </select>
                @error('course_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="enrollment_date" class="form-label fw-semibold">
                        <i class="fas fa-calendar me-1"></i>Data da Matrícula
                    </label>
                    <input type="date" class="form-control @error('enrollment_date') is-invalid @enderror" id="enrollment_date" name="enrollment_date" value="{{ old('enrollment_date', date('Y-m-d')) }}">
                    @error('enrollment_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label fw-semibold">
                        <i class="fas fa-check-circle me-1"></i>Status
                    </label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="ativa" {{ old('status') == 'ativa' ? 'selected' : 'selected' }}>Ativa</option>
                        <option value="concluida" {{ old('status') == 'concluida' ? 'selected' : '' }}>Concluída</option>
                        <option value="cancelada" {{ old('status') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Salvar
                </button>
                <a href="{{ route('admin.enrollments.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
