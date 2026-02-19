@extends('layouts.admin')

@section('title', 'Detalhes da Matrícula')

@section('content')
<div class="card">
    <div class="card-header">
        Detalhes da Matrícula
        <a href="{{ route('admin.enrollments.edit', $enrollment->id) }}" class="btn btn-secondary" style="float: right;">Editar</a>
    </div>
    <table>
        <tr><th style="width: 200px;">Aluno:</th><td>{{ $enrollment->student->name }}</td></tr>
        <tr><th>Email do Aluno:</th><td>{{ $enrollment->student->email }}</td></tr>
        <tr><th>Curso:</th><td>{{ $enrollment->course->title }}</td></tr>
        <tr><th>Data da Matrícula:</th><td>{{ $enrollment->enrollment_date->format('d/m/Y') }}</td></tr>
        <tr><th>Status:</th><td>{{ ucfirst($enrollment->status) }}</td></tr>
    </table>
    <a href="{{ route('admin.enrollments.index') }}" class="btn btn-secondary">Voltar</a>
</div>
@endsection
