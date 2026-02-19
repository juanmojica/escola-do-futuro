<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Idade por Curso</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #0d6efd;
        }
        
        .header h1 {
            font-size: 24px;
            color: #0d6efd;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 11px;
            color: #666;
        }
        
        .info-box {
            background-color: #f8f9fa;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-left: 4px solid #0d6efd;
        }
        
        .info-box strong {
            color: #0d6efd;
        }
        
        .course-report {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .course-header {
            background-color: #4da3ff;
            color: white;
            padding: 10px 15px;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: bold;
        }
        
        .course-dates {
            font-size: 10px;
            margin-top: 3px;
            opacity: 0.9;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .stat-row {
            display: table-row;
        }
        
        .stat-cell {
            display: table-cell;
            padding: 12px;
            border: 1px solid #dee2e6;
            background-color: #fff;
        }
        
        .stat-label {
            font-size: 10px;
            color: #666;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #0d6efd;
        }
        
        .stat-student {
            font-size: 11px;
            color: #333;
            margin-top: 3px;
        }
        
        .no-data {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #666;
            font-style: italic;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            background-color: #fff;
            color: #0d6efd;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório de Idade por Curso</h1>
        <p>Análise Estatística de Idade dos Alunos - Escola do Futuro</p>
    </div>
    
    <div class="info-box">
        <strong>Data de Geração:</strong> {{ now()->format('d/m/Y H:i:s') }}
        @if($courseId)
            <br><strong>Filtro Aplicado:</strong> Curso específico selecionado
        @else
            <br><strong>Escopo:</strong> Todos os cursos
        @endif
    </div>
    
    @forelse($reports as $report)
        <div class="course-report">
            <div class="course-header">
                {{ $report['course']->title }}
                @if($report['total_students'] > 0)
                    <span class="badge">{{ $report['total_students'] }} aluno{{ $report['total_students'] > 1 ? 's' : '' }}</span>
                @endif
                
                @if($report['course']->start_date || $report['course']->end_date)
                    <div class="course-dates">
                        @if($report['course']->start_date)
                            {{ $report['course']->start_date->format('d/m/Y') }}
                        @endif
                        @if($report['course']->start_date && $report['course']->end_date)
                            até
                        @endif
                        @if($report['course']->end_date)
                            {{ $report['course']->end_date->format('d/m/Y') }}
                        @endif
                    </div>
                @endif
            </div>
            
            @if($report['total_students'] > 0)
                <div class="stats-grid">
                    <div class="stat-row">
                        <div class="stat-cell">
                            <div class="stat-label">Idade Média</div>
                            <div class="stat-value">{{ number_format($report['average_age'], 1, ',', '.') }} anos</div>
                        </div>
                        <div class="stat-cell">
                            <div class="stat-label">Aluno Mais Jovem</div>
                            <div class="stat-value">{{ $report['youngest_age'] }} anos</div>
                            <div class="stat-student">{{ $report['youngest_student']->user->name }}</div>
                        </div>
                        <div class="stat-cell">
                            <div class="stat-label">Aluno Mais Velho</div>
                            <div class="stat-value">{{ $report['oldest_age'] }} anos</div>
                            <div class="stat-student">{{ $report['oldest_student']->user->name }}</div>
                        </div>
                    </div>
                </div>
            @else
                <div class="no-data">
                    Nenhum aluno cadastrado neste curso com data de nascimento informada
                </div>
            @endif
        </div>
    @empty
        <div class="no-data">
            Nenhum relatório disponível
        </div>
    @endforelse
    
    <div class="footer">
        Escola do Futuro - Sistema de Gestão Escolar<br>
        Documento gerado automaticamente em {{ now()->format('d/m/Y \à\s H:i:s') }}
    </div>
</body>
</html>
