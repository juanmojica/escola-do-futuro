@extends('layouts.admin')

@section('title', 'Alunos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="fas fa-users me-2"></i>Alunos</h2>
    </div>
    <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-2"></i>Novo Aluno
    </a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('admin.students.index') }}" method="GET" class="row g-3">
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" name="search" placeholder="Buscar por nome ou email" value="{{ $search ?? '' }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i>Buscar
                </button>
            </div>
            @if($search)
                <div class="col-12">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times-circle me-1"></i>Limpar Busca
                    </a>
                    <span class="text-muted ms-2">Resultados para: <strong>"{{ $search }}"</strong></span>
                </div>
            @endif
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    @if($students->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th><i class="fas fa-user me-1"></i>Nome</th>
                        <th><i class="fas fa-envelope me-1"></i>Email</th>
                        <th><i class="fas fa-calendar-alt me-1"></i>Data de Nascimento</th>
                        <th class="text-center" style="width: 150px;"><i class="fas fa-cog me-1"></i>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td class="text-center text-muted">{{ $student->id }}</td>
                        <td class="fw-semibold">{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->birth_date ? $student->birth_date->format('d/m/Y') : '-' }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.students.show', $student->id) }}" class="btn btn-sm btn-outline-primary" title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-sm btn-outline-secondary" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('delete-form-{{ $student->id }}', 'Tem certeza que deseja excluir o aluno {{ addslashes($student->name) }}?')" title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <form id="delete-form-{{ $student->id }}" action="{{ route('admin.students.destroy', $student->id) }}" method="POST" class="d-none">
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
            {{ $students->links() }}
        </div>
    @else
        <div class="card-body text-center py-5">
            <i class="fas fa-inbox fs-1 text-muted d-block mb-3"></i>
            <p class="text-muted mb-0">
                @if($search)
                    Nenhum aluno encontrado com os critérios de busca.
                @else
                    Nenhum aluno cadastrado.
                @endif
            </p>
        </div>
    @endif
</div>
@endsection
