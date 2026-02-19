@extends('layouts.admin')

@section('title', 'Aluno')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="fas fa-user me-2"></i>Aluno</h2>
    </div>
    <div>
        <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-primary">
            <i class="fas fa-pen me-2"></i>Editar
        </a>
        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="fw-bold mb-0"><i class="fas fa-user-vcard me-2 text-primary"></i>Informações Pessoais</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td class="text-muted fw-semibold" style="width: 200px;">
                                    Nome:
                                </td>
                                <td class="fw-bold">{{ $student->name }}</td>
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
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="fw-bold mb-0"><i class="fas fa-clipboard-check me-2 text-success"></i>Matrículas</h5>
            </div>
            @if($student->enrollments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-book me-1"></i>Curso</th>
                                <th><i class="fas fa-calendar me-1"></i>Data</th>
                                <th class="text-center"><i class="fas fa-info-circle me-1"></i>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($student->enrollments as $enrollment)
                            <tr>
                                <td class="fw-semibold">{{ $enrollment->course->title }}</td>
                                <td>{{ $enrollment->enrollment_date->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    @if($enrollment->status == 'ativa')
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>{{ ucfirst($enrollment->status) }}</span>
                                    @elseif($enrollment->status == 'concluída')
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
                <div class="card-body text-center py-5">
                    <i class="fas fa-inbox fs-1 text-muted d-block mb-3"></i>
                    <p class="text-muted mb-0">Nenhuma matrícula registrada para este aluno.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
