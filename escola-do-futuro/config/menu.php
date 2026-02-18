<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Menu de Administração
    |--------------------------------------------------------------------------
    |
    | Define a estrutura do menu para administradores.
    | Suporta submenus e permissões personalizadas.
    |
    */
    
    'admin' => [
        [
            'titulo' => 'Dashboard',
            'rota' => 'admin.dashboard',
            'icone' => 'fas fa-tachometer-alt',
            'ativo' => true,
            'permissoes' => [],
            'submenu' => [],
        ],
        [
            'titulo' => 'Cursos',
            'rota' => 'admin.courses.index',
            'icone' => 'fas fa-book',
            'ativo' => true,
            'permissoes' => [],
            'submenu' => [],
        ],
        [
            'titulo' => 'Disciplinas',
            'rota' => 'admin.subjects.index',
            'icone' => 'fas fa-book-open',
            'ativo' => true,
            'permissoes' => [],
            'submenu' => [],
        ],
        [
            'titulo' => 'Professores',
            'rota' => 'admin.teachers.index',
            'icone' => 'fas fa-chalkboard-teacher',
            'ativo' => true,
            'permissoes' => [],
            'submenu' => [],
        ],
        [
            'titulo' => 'Alunos',
            'rota' => 'admin.students.index',
            'icone' => 'fas fa-users',
            'ativo' => true,
            'permissoes' => [],
            'submenu' => [],
        ],
        [
            'titulo' => 'Matrículas',
            'rota' => 'admin.enrollments.index',
            'icone' => 'fas fa-clipboard-check',
            'ativo' => true,
            'permissoes' => [],
            'submenu' => [],
        ],
        [
            'titulo' => 'Relatórios',
            'rota' => 'admin.reports.index',
            'icone' => 'fas fa-chart-line',
            'ativo' => true,
            'permissoes' => [],
            'submenu' => [],
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Menu de Estudante
    |--------------------------------------------------------------------------
    |
    | Define a estrutura do menu para estudantes.
    |
    */
    
    'student' => [
        [
            'titulo' => 'Meu Painel',
            'rota' => 'student.dashboard',
            'icone' => 'fas fa-home',
            'ativo' => true,
            'permissoes' => [],
            'submenu' => [],
        ],
        [
            'titulo' => 'Minhas Matrículas',
            'rota' => 'student.enrollments.index',
            'icone' => 'fas fa-clipboard-check',
            'ativo' => true,
            'permissoes' => [],
            'submenu' => [],
        ],
        [
            'titulo' => 'Meu Perfil',
            'rota' => 'student.profile.edit',
            'icone' => 'fas fa-user-cog',
            'ativo' => true,
            'permissoes' => [],
            'submenu' => [],
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Exemplo de Menu com Submenu e Permissões
    |--------------------------------------------------------------------------
    |
    | Exemplo de como configurar submenus e verificar permissões.
    | Descomente e adapte conforme necessário.
    |
    */
    
    // 'admin_exemplo' => [
    //     [
    //         'titulo' => 'Financeiro',
    //         'rota' => '',
    //         'icone' => 'fas fa-dollar-sign',
    //         'ativo' => true,
    //         'permissoes' => ['financeiro.acessar'],
    //         'submenu' => [
    //             [
    //                 'titulo' => 'Caixa',
    //                 'rota' => 'admin.financeiro.caixa',
    //                 'icone' => 'fas fa-cash-register',
    //                 'ativo' => true,
    //                 'permissoes' => ['financeiro.caixa.listar'],
    //                 'submenu' => [],
    //             ],
    //             [
    //                 'titulo' => 'Conta Bancária',
    //                 'rota' => 'admin.financeiro.conta-bancaria',
    //                 'icone' => 'fas fa-university',
    //                 'ativo' => true,
    //                 'permissoes' => ['financeiro.conta.listar'],
    //                 'submenu' => [],
    //             ],
    //         ],
    //     ],
    // ],
    
];
