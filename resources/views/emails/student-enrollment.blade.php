<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matrícula Confirmada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 30px 40px;
        }
        .content p {
            color: #333;
            line-height: 1.6;
            margin: 15px 0;
        }
        .course-info {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .course-info h2 {
            margin: 0 0 10px 0;
            color: #667eea;
            font-size: 22px;
        }
        .course-info p {
            margin: 8px 0;
            color: #555;
        }
        .course-info strong {
            color: #333;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            margin: 20px 0;
            font-weight: bold;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #777;
            font-size: 14px;
        }
        .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">🎓</div>
            <h1>Matrícula Confirmada!</h1>
        </div>
        
        <div class="content">
            <p>Olá, <strong>{{ $student_name }}</strong>!</p>
            
            <p>É com grande satisfação que confirmamos sua matrícula no curso:</p>
            
            <div class="course-info">
                <h2>{{ $course_title }}</h2>
                <p>{{ $course_description }}</p>
                <p><strong>📅 Data da Matrícula:</strong> {{ $enrollment_date }}</p>
                <p><strong>🗓️ Início do Curso:</strong> {{ $start_date }}</p>
                <p><strong>🏁 Término do Curso:</strong> {{ $end_date }}</p>
            </div>
            
            <p>Você já pode acessar sua área do aluno e começar a acompanhar o conteúdo do curso.</p>
            
            <center>
                <a href="{{ url('/student/dashboard') }}" class="button">Acessar Minha Área</a>
            </center>
            
            <p>Desejamos a você muito sucesso nesta jornada de aprendizado! 🚀</p>
            
            <p>Em caso de dúvidas, entre em contato com nossa equipe.</p>
        </div>
        
        <div class="footer">
            <p>Escola do Futuro - Sistema de Gerenciamento Escolar</p>
            <p>&copy; {{ date('Y') }} - Todos os direitos reservados</p>
        </div>
    </div>
</body>
</html>
