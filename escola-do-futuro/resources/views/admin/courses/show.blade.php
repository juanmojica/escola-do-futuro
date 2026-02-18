@extends('layouts.admin')

@section('title', 'Curso')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="fas fa-book me-2"></i>Curso</h2>
    </div>
    <div>
        <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-primary">
            <i class="fas fa-pen me-2"></i>Editar
        </a>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 pt-4">
        <h5 class="fw-bold mb-0"><i class="fas fa-info-circle me-2 text-primary"></i>Informações do Curso</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td class="text-muted fw-semibold" style="width: 200px;">
                            Título:
                        </td>
                        <td class="fw-bold">{{ $course->title }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">
                            Descrição:
                        </td>
                        <td>{{ $course->description ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">
                            Data de Início:
                        </td>
                        <td>{{ $course->start_date ? $course->start_date->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-semibold">
                            Data de Fim:
                        </td>
                        <td>{{ $course->end_date ? $course->end_date->format('d/m/Y') : '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 pt-4">
        <h5 class="fw-bold mb-0"><i class="fas fa-book-open me-2 text-success"></i>Disciplinas do Curso</h5>
    </div>
    @if($course->subjects->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th><i class="fas fa-book me-1"></i>Disciplina</th>
                        <th><i class="fas fa-chalkboard-teacher me-1"></i>Professor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($course->subjects as $subject)
                    <tr>
                        <td class="fw-semibold">{{ $subject->title }}</td>
                        <td>{{ $subject->teacher->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="card-body text-center py-4">
            <i class="fas fa-inbox fs-1 text-muted d-block mb-3"></i>
            <p class="text-muted mb-0">Nenhuma disciplina cadastrada para este curso.</p>
        </div>
    @endif
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-4">
        <h5 class="fw-bold mb-0"><i class="fas fa-users me-2 text-info"></i>Alunos Matriculados</h5>
    </div>
    @if($course->enrollments->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th><i class="fas fa-user me-1"></i>Nome</th>
                        <th><i class="fas fa-calendar me-1"></i>Data da Matrícula</th>
                        <th class="text-center"><i class="fas fa-info-circle me-1"></i>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($course->enrollments as $enrollment)
                    <tr>
                        <td class="fw-semibold">{{ $enrollment->student->name }}</td>
                        <td>{{ $enrollment->enrollment_date->format('d/m/Y') }}</td>
                        <td class="text-center">
                            @if($enrollment->status == 'ativa')
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>{{ ucfirst($enrollment->status) }}</span>
                            @elseif($enrollment->status == 'concluida')
                                <span class="badge bg-primary"><i class="fas fa-trophy me-1"></i>{{ ucfirst($enrollment->status) }}</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-minus-circle me-1"></i>{{ ucfirst($enrollment->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="card-body text-center py-4">
            <i class="fas fa-inbox fs-1 text-muted d-block mb-3"></i>
            <p class="text-muted mb-0">Nenhum aluno matriculado neste curso.</p>
        </div>
    @endif
</div>
@endsection
