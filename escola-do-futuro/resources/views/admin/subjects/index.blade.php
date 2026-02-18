@extends('layouts.admin')

@section('title', 'Disciplinas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="fas fa-book-open me-2"></i>Disciplinas</h2>
    </div>
    <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-2"></i>Nova Disciplina
    </a>
</div>

<div class="card border-0 shadow-sm">
    @if($subjects->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th>Título</th>
                        <th><i class="fas fa-book me-1"></i>Curso</th>
                        <th><i class="fas fa-chalkboard-teacher me-1"></i>Professor</th>
                        <th class="text-center" style="width: 150px;"><i class="fas fa-cog me-1"></i>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjects as $subject)
                    <tr>
                        <td class="text-center text-muted">{{ $subject->id }}</td>
                        <td class="fw-semibold">{{ $subject->title }}</td>
                        <td>{{ $subject->course->title }}</td>
                        <td>{{ $subject->teacher->name }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.subjects.show', $subject->id) }}" class="btn btn-sm btn-outline-primary" title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.subjects.edit', $subject->id) }}" class="btn btn-sm btn-outline-secondary" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('delete-form-{{ $subject->id }}', 'Tem certeza que deseja excluir a disciplina {{ addslashes($subject->title) }}?')" title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <form id="delete-form-{{ $subject->id }}" action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" class="d-none">
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
            {{ $subjects->links() }}
        </div>
    @else
        <div class="card-body text-center py-5">
            <i class="fas fa-inbox fs-1 text-muted d-block mb-3"></i>
            <p class="text-muted mb-0">Nenhuma disciplina cadastrada.</p>
        </div>
    @endif
</div>
@endsection
