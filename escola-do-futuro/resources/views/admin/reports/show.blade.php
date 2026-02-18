@extends('layouts.admin')

@section('title', 'Relatório do Curso')

@section('content')
<div class="card">
    <div class="card-header">Relatório: {{ $report['course']->title }}</div>

    @if($report['total_students'] > 0)
        <table>
            <tr>
                <th style="width: 250px;">Total de Alunos:</th>
                <td>{{ $report['total_students'] }}</td>
            </tr>
            <tr>
                <th>Idade Média:</th>
                <td>{{ $report['average_age'] }} anos</td>
            </tr>
            <tr>
                <th>Aluno Mais Novo:</th>
                <td>{{ $report['youngest_student']->name }} ({{ $report['youngest_age'] }} anos)</td>
            </tr>
            <tr>
                <th>Aluno Mais Velho:</th>
                <td>{{ $report['oldest_student']->name }} ({{ $report['oldest_age'] }} anos)</td>
            </tr>
        </table>
    @else
        <p>Nenhum aluno com data de nascimento registrada neste curso.</p>
    @endif

    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">Voltar</a>
</div>
@endsection
