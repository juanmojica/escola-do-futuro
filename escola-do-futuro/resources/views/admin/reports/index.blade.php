@extends('layouts.admin')

@section('title', 'Relatórios de Idade por Curso')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="fas fa-chart-line me-2"></i>Relatórios de Idade por Curso</h2>
        <p class="text-muted mb-0">Análise estatística de idade dos alunos por curso</p>
    </div>
    <div>
        <a href="{{ route('admin.reports.export.pdf', ['course_id' => $courseId]) }}" class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf me-2"></i>Exportar PDF
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="course_id" class="form-label fw-semibold"><i class="fas fa-book me-1"></i>Filtrar por Curso</label>
                <select name="course_id" id="course_id" class="form-select">
                    <option value="">Todos os cursos</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ $courseId == $course->id ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search me-1"></i>Filtrar
                </button>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Limpar
                </a>
            </div>
        </form>
    </div>
</div>

@if($courseId)
    <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4">
        <i class="fas fa-filter fs-4 me-3"></i>
        <div>
            <strong>Filtro ativo:</strong> Exibindo relatório apenas para o curso selecionado.
        </div>
    </div>
@endif

@if(count($reports) > 0)
    <div class="row g-4">
        @foreach($reports as $report)
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header card-header-report text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-white">
                                <i class="fas fa-graduation-cap me-2"></i>{{ $report['course']->title }}
                            </h5>
                            @if($report['total_students'] > 0)
                                <span class="badge bg-white text-primary">
                                    <i class="fas fa-users me-1"></i>{{ $report['total_students'] }}
                                </span>
                            @endif
                        </div>
                        @if($report['course']->start_date || $report['course']->end_date)
                            <small class="d-block mt-2 opacity-75">
                                <i class="fas fa-calendar me-1"></i>
                                @if($report['course']->start_date)
                                    {{ $report['course']->start_date->format('d/m/Y') }}
                                @endif
                                @if($report['course']->start_date && $report['course']->end_date)
                                    até
                                @endif
                                @if($report['course']->end_date)
                                    {{ $report['course']->end_date->format('d/m/Y') }}
                                @endif
                            </small>
                        @endif
                    </div>

                    @if($report['total_students'] > 0)
                        <div class="card-body">
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <div class="p-3 bg-primary bg-opacity-10 rounded text-center border border-primary border-opacity-25">
                                        <i class="fas fa-chart-line text-primary fs-1 d-block mb-2"></i>
                                        <h2 class="fw-bold mb-0 text-primary">{{ $report['average_age'] }} anos</h2>
                                        <small class="text-muted fw-semibold">Idade Média dos Alunos</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="p-3 bg-success bg-opacity-10 rounded border border-success border-opacity-25">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-arrow-down text-success fs-4 me-2"></i>
                                            <small class="text-muted fw-semibold">Mais Novo</small>
                                        </div>
                                        <h4 class="fw-bold mb-0 text-success">{{ $report['youngest_age'] }} anos</h4>
                                        <small class="text-muted d-block">{{ $report['youngest_student']->name }}</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-info bg-opacity-10 rounded border border-info border-opacity-25">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-arrow-up text-info fs-4 me-2"></i>
                                            <small class="text-muted fw-semibold">Mais Velho</small>
                                        </div>
                                        <h4 class="fw-bold mb-0 text-info">{{ $report['oldest_age'] }} anos</h4>
                                        <small class="text-muted d-block">{{ $report['oldest_student']->name }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card-body text-center py-5">
                            <i class="fas fa-user-slash fs-1 text-muted d-block mb-3"></i>
                            <p class="text-muted mb-0">Nenhum aluno com data de nascimento registrada neste curso.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            @if($courseId)
                <i class="fas fa-filter fs-1 text-muted d-block mb-3"></i>
                <h5 class="mb-2">Nenhum resultado encontrado</h5>
                <p class="text-muted mb-3">Não foram encontrados dados para o curso selecionado.</p>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-times me-1"></i>Limpar Filtros
                </a>
            @else
                <i class="fas fa-inbox fs-1 text-muted d-block mb-3"></i>
                <h5 class="mb-2">Nenhum curso cadastrado</h5>
                <p class="text-muted mb-0">Cadastre cursos e alunos para visualizar os relatórios.</p>
            @endif
        </div>
    </div>
@endif
@endsection
