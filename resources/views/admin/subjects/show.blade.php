@extends('layouts.admin')

@section('title', 'Disciplina')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="fas fa-book-open me-2"></i>Disciplina</h2>
    </div>
    <div>
        <a href="{{ route('admin.subjects.edit', $subject->id) }}" class="btn btn-primary">
            <i class="fas fa-pen me-2"></i>Editar
        </a>
        <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-4">
        <h5 class="fw-bold mb-0"><i class="fas fa-info-circle me-2 text-primary"></i>Informações da Disciplina</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td class="text-muted fw-semibold" style="width: 200px;">
                            Título:
                        </td>
                        <td class="fw-bold">{{ $subject->title }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">
                            Descrição:
                        </td>
                        <td>{{ $subject->description ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">
                            Curso:
                        </td>
                        <td>{{ $subject->course->title }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">
                            Professor:
                        </td>
                        <td>{{ $subject->teacher->name }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
