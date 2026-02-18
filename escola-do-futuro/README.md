# Escola do Futuro - Sistema de Gestão Educacional

Sistema completo de gestão educacional desenvolvido em Laravel 5, seguindo princípios de DDD (Domain-Driven Design) e boas práticas de desenvolvimento.

## 📋 Sobre o Projeto

Sistema de gestão educacional que permite:
- Gerenciamento de Cursos, Disciplinas, Professores e Alunos
- Sistema de Matrículas
- Área administrativa completa
- Área do aluno para gerenciar perfil e visualizar matrículas
- Relatórios de idade por curso (idade média, aluno mais novo e mais velho)

## 🏗️ Arquitetura

O projeto segue uma arquitetura em camadas inspirada em DDD:

```
app/
├── Models/              # Modelos de domínio
├── Repositories/        # Camada de acesso a dados
│   ├── Contracts/      # Interfaces dos repositórios
│   └── *Repository.php # Implementações
├── Services/           # Camada de lógica de negócio
├── Http/
│   ├── Controllers/
│   │   ├── Admin/     # Controllers administrativos
│   │   └── Student/   # Controllers da área do aluno
│   └── Middleware/    # Middlewares customizados
└── Providers/         # Service Providers
```

## 🚀 Instalação e Configuração

### Pré-requisitos
- Docker e Docker Compose
- Git

### Passo a Passo

1. **Clone o repositório** (se ainda não tiver)
```bash
git clone <url-do-repositorio>
cd escola-do-futuro
```

2. **Configure o arquivo .env**
```bash
cp .env.example .env
```

Certifique-se de que o `.env` tenha as seguintes configurações:
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root
```

3. **Inicie os containers**
```bash
docker-compose up -d
```

4. **Execute as migrations e seeders**
```bash
docker exec laravel5_app php artisan migrate:fresh --seed
```

5. **Gere a chave da aplicação**
```bash
docker exec laravel5_app php artisan key:generate
```

6. **Acesse a aplicação**
- URL: http://localhost:8000

## 👤 Credenciais de Acesso

### Administrador
- **Email:** admin@escola.com
- **Senha:** password

### Alunos
- **Emanuel Silva**
  - Email: emanuel@aluno.com
  - Senha: password

- **Maria Santos**
  - Email: maria@aluno.com
  - Senha: password

## 📚 Funcionalidades

### Área Administrativa (`/admin`)

#### CRUD de Cursos
- Criar, listar, editar e visualizar áreas de cursos
- Campos: título, descrição, data de início, data de fim

#### CRUD de Professores
- Gerenciar professores
- Campos: nome, email

#### CRUD de Disciplinas
- Gerenciar disciplinas vinculadas a cursos e professores
- Campos: título, descrição, curso, professor

#### CRUD de Alunos
- Gerenciar alunos com busca por nome e email
- Campos: nome, email, data de nascimento
- Opção de criar conta de acesso para o aluno

#### CRUD de Matrículas
- Matricular alunos em cursos
- Campos: aluno, curso, data da matrícula, status
- Validação para evitar matrículas duplicadas

#### Relatórios
- Relatório de idade por curso mostrando:
  - Total de alunos
  - Idade média
  - Aluno mais novo
  - Aluno mais velho

### Área do Aluno (`/student`)

- Dashboard com informações do perfil
- Visualização de matrículas ativas
- Edição do próprio perfil (nome, email, data de nascimento, senha)

## 🔒 Autenticação e Autorização

O sistema possui dois middlewares customizados:

- **AdminMiddleware:** Garante que apenas usuários administradores acessem a área admin
- **StudentMiddleware:** Garante que apenas alunos acessem a área do aluno

## 🗄️ Estrutura do Banco de Dados

### Principais Tabelas

- `users` - Usuários do sistema (admin e alunos)
- `students` - Perfis de alunos
- `courses` - Cursos disponíveis
- `teachers` - Professores
- `subjects` - Disciplinas vinculadas a cursos e professores
- `enrollments` - Matrículas de alunos em cursos

### Relacionamentos

- Um aluno pode ter múltiplas matrículas (N:N com courses)
- Uma disciplina pertence a um curso e um professor
- Um aluno pode ter uma conta de usuário (1:1 com users)

## 🛠️ Comandos Úteis

### Executar migrations
```bash
docker exec laravel5_app php artisan migrate
```

### Executar seeders
```bash
docker exec laravel5_app php artisan db:seed
```

### Resetar banco de dados
```bash
docker exec laravel5_app php artisan migrate:fresh --seed
```

### Acessar o container
```bash
docker exec -it laravel5_app bash
```

### Ver logs do Laravel
```bash
docker exec laravel5_app tail -f storage/logs/laravel.log
```

## 📦 Dependências Principais

- Laravel 5.8
- MySQL 5.7
- PHP 7.4+
- Blade Templates
- Eloquent ORM

## 🎯 Padrões e Boas Práticas Implementadas

1. **Repository Pattern:** Abstração da camada de dados
2. **Service Layer:** Lógica de negócio centralizada
3. **Dependency Injection:** Uso de interfaces e injeção via Service Provider
4. **Eloquent Relationships:** Uso de relacionamentos do Eloquent
5. **Soft Deletes:** Exclusão lógica de registros
6. **Form Validation:** Validação de dados de entrada
7. **Middleware:** Controle de acesso e autenticação
8. **Blade Components:** Reutilização de templates
9. **Route Groups:** Organização de rotas por contexto

## 📝 Observações

- O sistema foi desenvolvido seguindo os requisitos da avaliação técnica
- A arquitetura permite fácil expansão e manutenção
- Todos os CRUDs implementam as operações básicas completas
- O sistema possui tratamento de erros e mensagens de feedback ao usuário

## 🐛 Troubleshooting

### Erro de conexão com o banco de dados
Certifique-se de que:
- O container MySQL está rodando: `docker ps`
- O `.env` está configurado com `DB_HOST=mysql`
- As migrations foram executadas

### Página em branco ou erro 500
Verifique os logs:
```bash
docker exec laravel5_app tail -f storage/logs/laravel.log
```

### Permissões de arquivo
Se houver erros de permissão:
```bash
docker exec laravel5_app chmod -R 775 storage bootstrap/cache
```

## 📧 Suporte

Para dúvidas ou problemas, entre em contato com o desenvolvedor.

---

**Desenvolvido com ❤️ usando Laravel 5**
