@extends('layouts.admin')

@section('title', 'Cursos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="fas fa-book me-2"></i>Cursos</h2>
    </div>
    <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-2"></i>Novo Curso
    </a>
</div>

<div class="card border-0 shadow-sm">
    @if($courses->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th>Título</th>
                        <th><i class="fas fa-calendar-check me-1"></i>Data Início</th>
                        <th><i class="fas fa-calendar-times me-1"></i>Data Fim</th>
                        <th class="text-center" style="width: 150px;"><i class="fas fa-cog me-1"></i>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $course)
                    <tr>
                        <td class="text-center text-muted">{{ $course->id }}</td>
                        <td class="fw-semibold">{{ $course->title }}</td>
                        <td>{{ $course->start_date ? $course->start_date->format('d/m/Y') : '-' }}</td>
                        <td>{{ $course->end_date ? $course->end_date->format('d/m/Y') : '-' }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.courses.show', $course->id) }}" class="btn btn-sm btn-outline-primary" title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-sm btn-outline-secondary" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('delete-form-{{ $course->id }}', 'Tem certeza que deseja excluir o curso {{ addslashes($course->title) }}?')" title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <form id="delete-form-{{ $course->id }}" action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" class="d-none">
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
            {{ $courses->links() }}
        </div>
    @else
        <div class="card-body text-center py-5">
            <i class="fas fa-inbox fs-1 text-muted d-block mb-3"></i>
            <p class="text-muted mb-0">Nenhum curso cadastrado.</p>
        </div>
    @endif
</div>
@endsection
