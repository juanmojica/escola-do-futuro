<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Escola do Futuro') }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/welcome.css') }}?v={{ time() }}" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-custom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-graduation-cap me-2"></i>
                {{ config('app.name', 'Escola do Futuro') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/home') }}">
                                    <i class="fas fa-home me-1"></i>Dashboard
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt me-1"></i>Entrar
                                </a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus me-1"></i>Registrar
                                    </a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center hero-content">
                    <h1 class="display-3 fw-bold mb-4">
                        Bem-vindo à Escola do Futuro
                    </h1>
                    <p class="lead mb-5 fs-4">
                        Uma plataforma moderna de gestão educacional que conecta alunos, professores e administradores em um ambiente digital inovador.
                    </p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-custom btn-lg">
                                <i class="fas fa-rocket me-2"></i>Começar Agora
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Fazer Login
                            </a>
                        @else
                            <a href="{{ url('/home') }}" class="btn btn-custom btn-lg">
                                <i class="fas fa-home me-2"></i>Ir para Dashboard
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 features-section">
        <div class="container py-5">
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="display-5 fw-bold mb-3">Recursos Principais</h2>
                    <p class="lead text-muted">
                        Tudo que você precisa para gerenciar sua instituição de ensino de forma eficiente
                    </p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card shadow-sm p-4">
                        <div class="card-body text-center">
                            <div class="feature-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Gestão de Alunos</h5>
                            <p class="text-muted">
                                Cadastro completo e acompanhamento detalhado do desempenho de cada aluno.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card shadow-sm p-4">
                        <div class="card-body text-center">
                            <div class="feature-icon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Gestão de Professores</h5>
                            <p class="text-muted">
                                Organize disciplinas, turmas e facilite a comunicação com o corpo docente.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card shadow-sm p-4">
                        <div class="card-body text-center">
                            <div class="feature-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Cursos e Disciplinas</h5>
                            <p class="text-muted">
                                Estruture o conteúdo pedagógico e organize o currículo da instituição.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card shadow-sm p-4">
                        <div class="card-body text-center">
                            <div class="feature-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Relatórios</h5>
                            <p class="text-muted">
                                Análises e relatórios completos para tomada de decisões estratégicas.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col-md-4">
                    <div class="stat-number">100%</div>
                    <p class="mb-0 fs-5">Digital</p>
                </div>
                <div class="col-md-4">
                    <div class="stat-number">24/7</div>
                    <p class="mb-0 fs-5">Acesso</p>
                </div>
                <div class="col-md-4">
                    <div class="stat-number">∞</div>
                    <p class="mb-0 fs-5">Possibilidades</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">
                        <i class="fas fa-graduation-cap me-2"></i>
                        © {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
