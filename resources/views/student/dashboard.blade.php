@extends('layouts.student')

@section('title', 'Meu Painel')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold"><i class="fas fa-user-circle me-2"></i>Bem-vindo, {{ $student->name }}!</h2>
    <p class="text-muted">Confira suas informações e matrículas</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-4">
                <h5 class="fw-bold mb-0"><i class="fas fa-user-vcard me-2 text-primary"></i>Meus Dados</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td class="text-muted fw-semibold" style="width: 180px;">
                                    Nome:
                                </td>
                                <td>{{ $student->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">
                                    Email:
                                </td>
                                <td>{{ $student->email }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">
                                    Data de Nascimento:
                                </td>
                                <td>{{ $student->birth_date ? $student->birth_date->format('d/m/Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-semibold">
                                    Idade:
                                </td>
                                <td>{{ $student->age ? $student->age . ' anos' : '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <a href="{{ route('student.profile.edit') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-pen-square me-1"></i>Editar Perfil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-4">
                <h5 class="fw-bold mb-0"><i class="fas fa-clipboard-check me-2 text-success"></i>Resumo</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center p-3 bg-light rounded">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="fas fa-clipboard-check fs-4 text-primary"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold">{{ $student->enrollments->count() }}</h3>
                            <small class="text-muted">Total de Matrículas</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center p-3 bg-light rounded">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="fas fa-check-circle fs-4 text-success"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold">{{ $student->enrollments->where('status', 'ativa')->count() }}</h3>
                            <small class="text-muted">Matrículas Ativas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center pt-4">
        <h5 class="fw-bold mb-0"><i class="fas fa-book me-2 text-info"></i>Minhas Matrículas</h5>
        <a href="{{ route('student.enrollments.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-eye me-1"></i>Ver Todas
        </a>
    </div>
    <div class="card-body p-0">
        @if($student->enrollments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 text-center" style="width: 60px;">#</th>
                            <th class="border-0">
                                <i class="fas fa-book me-1"></i>Curso
                            </th>
                            <th class="border-0">
                                <i class="fas fa-calendar me-1"></i>Data da Matrícula
                            </th>
                            <th class="border-0 text-center">
                                <i class="fas fa-info-circle me-1"></i>Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student->enrollments->take(5) as $enrollment)
                        <tr>
                            <td class="text-center text-muted">{{ $enrollment->id }}</td>
                            <td class="fw-semibold">{{ $enrollment->course->title }}</td>
                            <td>{{ $enrollment->enrollment_date->format('d/m/Y') }}</td>
                            <td class="text-center">
                                @if($enrollment->status == 'ativa')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>{{ ucfirst($enrollment->status) }}
                                    </span>
                                @elseif($enrollment->status == 'concluida')
                                    <span class="badge bg-primary">
                                        <i class="fas fa-trophy me-1"></i>{{ ucfirst($enrollment->status) }}
                                    </span>
                                @elseif($enrollment->status == 'cancelada')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-ban me-1"></i>{{ ucfirst($enrollment->status) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-minus-circle me-1"></i>{{ ucfirst($enrollment->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fs-1 text-muted d-block mb-3"></i>
                <p class="text-muted mb-0">Você ainda não está matriculado em nenhum curso.</p>
            </div>
        @endif
    </div>
</div>
@endsection
