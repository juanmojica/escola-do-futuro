@extends('layouts.admin')

@section('title', 'Professor')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="fas fa-chalkboard-teacher me-2"></i>Professor</h2>
    </div>
    <div>
        <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="btn btn-primary">
            <i class="fas fa-pen me-2"></i>Editar
        </a>
        <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 pt-4">
        <h5 class="fw-bold mb-0"><i class="fas fa-info-circle me-2 text-primary"></i>Informações do Professor</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td class="text-muted fw-semibold" style="width: 200px;">
                            Nome:
                        </td>
                        <td class="fw-bold">{{ $teacher->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">
                            Email:
                        </td>
                        <td>{{ $teacher->email }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-4">
        <h5 class="fw-bold mb-0"><i class="fas fa-book-open me-2 text-success"></i>Disciplinas Ministradas</h5>
    </div>
    @if($teacher->subjects->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th><i class="fas fa-book me-1"></i>Disciplina</th>
                        <th><i class="fas fa-graduation-cap me-1"></i>Curso</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teacher->subjects as $subject)
                    <tr>
                        <td class="fw-semibold">{{ $subject->title }}</td>
                        <td>{{ $subject->course->title }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="card-body text-center py-4">
            <i class="fas fa-inbox fs-1 text-muted d-block mb-3"></i>
            <p class="text-muted mb-0">Nenhuma disciplina atribuída a este professor.</p>
        </div>
    @endif
</div>
@endsection
