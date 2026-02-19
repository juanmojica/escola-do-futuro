@extends('layouts.admin')

@section('title', 'Matrículas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="fas fa-clipboard-check me-2"></i>Matrículas</h2>
        <p class="text-muted mb-0">Gerencie as matrículas dos alunos</p>
    </div>
    <a href="{{ route('admin.enrollments.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-2"></i>Nova Matrícula
    </a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.enrollments.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="course_id" class="form-label fw-semibold"><i class="fas fa-book me-1"></i>Curso</label>
                <select name="course_id" id="course_id" class="form-select">
                    <option value="">Todos os cursos</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ (isset($filters['course_id']) && $filters['course_id'] == $course->id) ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label fw-semibold"><i class="fas fa-filter me-1"></i>Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="ativa" {{ (isset($filters['status']) && $filters['status'] == 'ativa') ? 'selected' : '' }}>Ativa</option>
                    <option value="concluida" {{ (isset($filters['status']) && $filters['status'] == 'concluida') ? 'selected' : '' }}>Concluída</option>
                    <option value="cancelada" {{ (isset($filters['status']) && $filters['status'] == 'cancelada') ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label fw-semibold"><i class="fas fa-calendar me-1"></i>Data Inicial</label>
                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label fw-semibold"><i class="fas fa-calendar me-1"></i>Data Final</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search me-1"></i>Filtrar
                </button>
                <a href="{{ route('admin.enrollments.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Limpar
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    @if($enrollments->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th><i class="fas fa-user me-1"></i>Aluno</th>
                        <th><i class="fas fa-book me-1"></i>Curso</th>
                        <th><i class="fas fa-calendar me-1"></i>Data</th>
                        <th class="text-center"><i class="fas fa-info-circle me-1"></i>Status</th>
                        <th class="text-center" style="width: 150px;"><i class="fas fa-cog me-1"></i>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($enrollments as $enrollment)
                    <tr>
                        <td class="text-center text-muted">{{ $enrollment->id }}</td>
                        <td class="fw-semibold">{{ $enrollment->student->name ?? 'N/A' }}</td>
                        <td>{{ $enrollment->course->title ?? 'N/A' }}</td>
                        <td>{{ $enrollment->enrollment_date->format('d/m/Y') }}</td>
                        <td class="text-center">
                            @if($enrollment->status == 'ativa')
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>{{ ucfirst($enrollment->status) }}</span>
                            @elseif($enrollment->status == 'concluida')
                                <span class="badge bg-primary"><i class="fas fa-trophy me-1"></i>{{ ucfirst($enrollment->status) }}</span>
                            @elseif($enrollment->status == 'cancelada')
                                <span class="badge bg-danger"><i class="fas fa-ban me-1"></i>{{ ucfirst($enrollment->status) }}</span>

                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.enrollments.show', $enrollment->id) }}" class="btn btn-sm btn-outline-primary" title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.enrollments.edit', $enrollment->id) }}" class="btn btn-sm btn-outline-secondary" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('delete-form-{{ $enrollment->id }}', 'Tem certeza que deseja excluir esta matrícula?')" title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <form id="delete-form-{{ $enrollment->id }}" action="{{ route('admin.enrollments.destroy', $enrollment->id) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-0">
            {{ $enrollments->appends(request()->query())->links() }}
        </div>
    @else
        <div class="card-body text-center py-5">
            <i class="fas fa-inbox fs-1 text-muted d-block mb-3"></i>
            <p class="text-muted mb-0">Nenhuma matrícula cadastrada.</p>
        </div>
    @endif
</div>
@endsection
