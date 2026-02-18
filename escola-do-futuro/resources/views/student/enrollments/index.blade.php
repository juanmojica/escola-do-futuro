@extends('layouts.student')

@section('title', 'Minhas Matrículas')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold mb-1"><i class="fas fa-clipboard-check me-2"></i>Minhas Matrículas</h2>
</div>

<div class="card border-0 shadow-sm">
    @if($enrollments->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th><i class="fas fa-book me-1"></i>Curso</th>
                        <th><i class="fas fa-align-left me-1"></i>Descrição</th>
                        <th><i class="fas fa-calendar-check me-1"></i>Data da Matrícula</th>
                        <th class="text-center"><i class="fas fa-info-circle me-1"></i>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($enrollments as $enrollment)
                    <tr>
                        <td class="text-center text-muted">{{ $enrollment->id }}</td>
                        <td class="fw-semibold">{{ $enrollment->course->title }}</td>
                        <td class="text-muted">{{ Str::limit($enrollment->course->description, 50) ?: '-' }}</td>
                        <td>{{ $enrollment->enrollment_date->format('d/m/Y') }}</td>
                        <td class="text-center">
                            @if($enrollment->status == 'ativa')
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Ativa
                                </span>
                            @elseif($enrollment->status == 'concluida')
                                <span class="badge bg-primary">
                                    <i class="fas fa-trophy me-1"></i>Concluída
                                </span>
                            @elseif($enrollment->status == 'cancelada')
                                <span class="badge bg-danger">
                                    <i class="fas fa-ban me-1"></i>Cancelada
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="card-body text-center py-5">
            <i class="fas fa-inbox fs-1 text-muted d-block mb-3"></i>
            <p class="text-muted mb-0">Você ainda não está matriculado em nenhum curso.</p>
        </div>
    @endif
</div>
@endsection
