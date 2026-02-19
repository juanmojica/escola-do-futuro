# 🎓 Escola do Futuro

Sistema de gestão educacional desenvolvido em **Laravel 5.8** com arquitetura em camadas (Repository + Service Pattern), seguindo boas práticas de Clean Code e SOLID.

---

## 📋 Sobre o Projeto

Sistema completo para gestão de instituições de ensino, permitindo o gerenciamento de:
- 📚 Cursos e Disciplinas
- 👨‍🏫 Professores
- 👨‍🎓 Alunos
- 📝 Matrículas
- 📊 Relatórios e Estatísticas

**Principais características:**
- ✅ 138 testes unitários (100% passing)
- ✅ Arquitetura em camadas (Repository + Service)
- ✅ Validações robustas com Form Requests
- ✅ Separação de ambientes (dev/test)
- ✅ Docker pronto para uso

---

## 🎯 Funcionalidades

### Área Administrativa (`/admin`)

**Gestão de Cursos**
- Criar, editar e visualizar cursos
- Definir datas de início e término
- Vincular disciplinas e alunos

**Gestão de Professores**
- Cadastro completo de docentes
- Vinculação com disciplinas

**Gestão de Disciplinas**
- Criar disciplinas vinculadas a cursos e professores
- Descrição e informações detalhadas

**Gestão de Alunos**
- Cadastro com busca e filtros
- Criação automática de conta de acesso
- Registro de data de nascimento

**Sistema de Matrículas**
- Matrícula de alunos em cursos
- Validação automática de duplicatas
- Controle de status (ativa, concluída, cancelada)
- Filtros por curso, aluno e data

**Relatórios**
- Relatório de idade por curso com:
  - Idade média dos alunos
  - Aluno mais novo e mais velho
  - Total de alunos matriculados
- Gráficos interativos (Chart.js)
- Exportação em PDF

### Área do Aluno (`/student`)

- Dashboard personalizado
- Visualização de matrículas ativas
- Edição de perfil e senha
- Consulta de cursos matriculados

---

## 🛠️ Tecnologias

**Backend:** Laravel 5.8 • PHP 7.3+ • MySQL 5.7  
**Frontend:** Blade Templates • Bootstrap 5.3 • Chart.js 4.4
**DevOps:** Docker • Docker Compose  
**Testes:** PHPUnit 7.5 • Mockery  
**Outros:** DomPDF (geração de PDFs)

---

## 🚀 Instalação

### Pré-requisitos

- [Docker](https://www.docker.com/get-started) e Docker Compose
- Git

### Instalação Automatizada (Recomendado)

```bash
# 1. Clone o repositório
git clone <url-do-repositorio>
cd escola-do-futuro

# 2. Execute o script de instalação
chmod +x setup.sh
./setup.sh
```

O script irá:
- ✅ Verificar Docker
- ✅ Criar arquivos de configuração (`.env` e `.env.testing`)
- ✅ Subir containers (App, MySQL, Nginx)
- ✅ Instalar dependências
- ✅ Executar migrations
- ✅ Configurar bancos de desenvolvimento e testes

**Tempo estimado:** ~2 minutos

### Instalação Manual

<details>
<summary>Clique para ver passo a passo</summary>

```bash
# 1. Clone e acesse
git clone <url-do-repositorio>
cd escola-do-futuro

# 2. Configure ambientes
cp .env.example .env
cp .env .env.testing

# Edite .env.testing e altere:
# DB_DATABASE=escola_testing
# APP_ENV=testing

# 3. Inicie containers
docker-compose up -d
sleep 10  # Aguardar MySQL

# 4. Instale dependências
docker exec laravel5_app composer install

# 5. Gere chave
docker exec laravel5_app php artisan key:generate

# 6. Execute migrations
docker exec laravel5_app php artisan migrate
docker exec laravel5_app php artisan migrate --env=testing

# 7. (Opcional) Popule com dados de exemplo
docker exec laravel5_app php artisan db:seed
```

</details>

---

## 🎉 Acessar a Aplicação

Após a instalação, acesse:

**URL:** http://localhost:8000

### Credenciais de Acesso (após seed)

| Perfil | Email | Senha |
|--------|-------|-------|
| **Administrador** | admin@escola.com | password |
| **Aluno** | emanuel@aluno.com | password |

---

## 🧪 Executar Testes

O projeto possui **137 testes unitários** cobrindo Services e Validações.

```bash
# Todos os testes
docker exec laravel5_app composer test

# Apenas testes unitários
docker exec laravel5_app composer test:unit

# Com descrições legíveis
docker exec laravel5_app composer test:dox

# Teste específico
docker exec laravel5_app vendor/bin/phpunit tests/Unit/Services/CourseServiceTest.php
```

**Importante:** Os testes utilizam banco separado (`escola_testing`) e não afetam os dados de desenvolvimento.

---

## 📦 Comandos Úteis

```bash
# Ver containers rodando
docker ps

# Parar containers
docker-compose down

# Acessar container
docker exec -it laravel5_app bash

# Resetar banco com dados de exemplo
docker exec laravel5_app php artisan migrate:fresh --seed

# Ver logs
docker exec laravel5_app tail -f storage/logs/laravel.log

# Limpar caches
docker exec laravel5_app php artisan cache:clear
docker exec laravel5_app php artisan config:clear
```

---

## 🏗️ Arquitetura

O projeto segue arquitetura em camadas:

```
Controllers → Services → Repositories → Database
```

- **Controllers:** Recebem requisições HTTP
- **Services:** Contêm lógica de negócio
- **Repositories:** Acessam dados do banco
- **Models:** Representam entidades do domínio

### Estrutura Principal

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/      # Área administrativa
│   │   └── Student/    # Área do aluno
│   ├── Requests/       # Validações
│   └── Middleware/     # Autenticação
├── Services/           # Lógica de negócio
├── Repositories/       # Acesso a dados
└── Models/             # Eloquent Models
```

---

## 🐛 Problemas Comuns

**Docker não conecta ao MySQL:**
```bash
# Aguarde alguns segundos após docker-compose up
sleep 10
```

**Erro 500 ou página em branco:**
```bash
# Verifique se APP_KEY foi gerada
docker exec laravel5_app php artisan key:generate

# Limpe caches
docker exec laravel5_app php artisan config:clear
```

**Permissão negada:**
```bash
docker exec laravel5_app chmod -R 775 storage bootstrap/cache
```