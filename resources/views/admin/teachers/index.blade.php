@extends('layouts.admin')

@section('title', 'Professores')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="fas fa-chalkboard-teacher me-2"></i>Professores</h2>
    </div>
    <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-2"></i>Novo Professor
    </a>
</div>

<div class="card border-0 shadow-sm">
    @if($teachers->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th><i class="fas fa-user me-1"></i>Nome</th>
                        <th><i class="fas fa-envelope me-1"></i>Email</th>
                        <th class="text-center" style="width: 150px;"><i class="fas fa-cog me-1"></i>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teachers as $teacher)
                    <tr>
                        <td class="text-center text-muted">{{ $teacher->id }}</td>
                        <td class="fw-semibold">{{ $teacher->name }}</td>
                        <td>{{ $teacher->email }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.teachers.show', $teacher->id) }}" class="btn btn-sm btn-outline-primary" title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="btn btn-sm btn-outline-secondary" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('delete-form-{{ $teacher->id }}', 'Tem certeza que deseja excluir o professor {{ addslashes($teacher->name) }}?')" title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <form id="delete-form-{{ $teacher->id }}" action="{{ route('admin.teachers.destroy', $teacher->id) }}" method="POST" class="d-none">
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
            {{ $teachers->links() }}
        </div>
    @else
        <div class="card-body text-center py-5">
            <i class="fas fa-inbox fs-1 text-muted d-block mb-3"></i>
            <p class="text-muted mb-0">Nenhum professor cadastrado.</p>
        </div>
    @endif
</div>
@endsection
