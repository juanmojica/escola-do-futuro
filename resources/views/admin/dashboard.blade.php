@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@push('styles')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="mb-4">
    <h2 class="fw-bold"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Administrativo</h2>
    <p class="text-muted">Bem-vindo ao painel administrativo da Escola do Futuro</p>
</div>

<div class="row g-4 mt-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-chart-bar me-2 text-primary"></i>Alunos por Curso
                </h5>
            </div>
            <div class="card-body">
                <canvas id="studentsPerCourseChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-chart-line me-2 text-success"></i>Estatísticas de Idade por Curso
                </h5>
            </div>
            <div class="card-body">
                <canvas id="agePerCourseChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    window.dashboardData = {
        studentsPerCourse: @json($studentsPerCourse),
        ageData: @json($ageData)
    };
</script>
<script src="{{ asset('js/dashboard-charts.js') }}"></script>
@endpush
