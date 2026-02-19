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
- ✅ Sistema de notificações (Events, Listeners, Jobs)
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

**Sistema de Notificações** 🔔
- Notificações in-app em tempo real
- Sininho com contador no navbar
- Email automático ao matricular aluno
- Jobs assíncronos para envio de emails
- Página de histórico de notificações

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
- Notificações de matrículas (sininho)
- Histórico de notificações

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
- ✅ Configurar fila de jobs (notificações)

**Tempo estimado:** ~2 minutos

**Após a instalação:**

```bash
# 1. Popular banco com dados de exemplo
docker exec laravel5_app php artisan db:seed

# 2. Processar fila de notificações (recomendado)
docker exec laravel5_app php artisan queue:work --stop-when-empty
```

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

# 6. Configure fila de jobs
docker exec laravel5_app php artisan queue:table
docker exec laravel5_app php artisan queue:failed-table

# 7. Execute migrations
docker exec laravel5_app php artisan migrate
docker exec laravel5_app php artisan migrate --env=testing

# 8. (Opcional) Popule com dados de exemplo
docker exec laravel5_app php artisan db:seed

# 9. (Recomendado) Processar fila de notificações
docker exec laravel5_app php artisan queue:work --stop-when-empty
```

</details>

---

## 🎉 Acessar a Aplicação

Após a instalação, acesse:

**URL:** http://localhost:8000

### Credenciais de Acesso (após seed)

| Perfil | Email | Senha |
|--------|-------|-------|
| **Administrador** | admin@escola.com | 123123123 |
| **Aluno** | emanuel@aluno.com | 123123123 |

---

## 🔔 Sistema de Notificações

O sistema envia notificações automáticas **para o estudante** por **email** e **in-app** usando Events, Listeners e Jobs.

### Como Funciona

Quando um estudante é matriculado em um curso:
1. ✅ Notificação in-app criada instantaneamente **para o estudante**
2. ✅ Email enviado em background **para o estudante** (fila assíncrona)
3. ✅ Estudante vê sininho com contador no navbar
4. ✅ Estudante pode visualizar histórico completo de suas notificações

### Processar Fila de Notificações

**IMPORTANTE:** Para que os emails sejam enviados, você precisa rodar o worker da fila:

```bash
# Processar fila até esvaziar e parar (recomendado para testes)
docker exec laravel5_app php artisan queue:work --stop-when-empty

# Processar fila continuamente (para desenvolvimento)
docker exec laravel5_app php artisan queue:work

# Processar fila em background (para produção)
docker exec -d laravel5_app php artisan queue:work --daemon
```

💡 **Dica:** Use `--stop-when-empty` após criar matrículas para processar os emails e parar automaticamente.

### Gerenciar Jobs Falhados

Se um job falhar (ex: servidor de email indisponível), ele será registrado na tabela `failed_jobs` após **3 tentativas**.

**Comandos úteis:**

```bash
# Listar jobs falhados
docker exec laravel5_app php artisan queue:failed

# Reprocessar job específico (pelo ID)
docker exec laravel5_app php artisan queue:retry 1

# Reprocessar TODOS os jobs falhados
docker exec laravel5_app php artisan queue:retry all

# Esquecer (deletar) job falhado específico
docker exec laravel5_app php artisan queue:forget 1

# Limpar (deletar) TODOS os jobs falhados
docker exec laravel5_app php artisan queue:flush
```

**Configuração do Job:**
- ✅ **3 tentativas** antes de considerar como falhado
- ✅ **30 segundos** de espera entre tentativas (backoff)
- ✅ **60 segundos** de timeout por tentativa
- ✅ Log detalhado de erros

### Configurar Email (Opcional)

Por padrão, emails usam driver `log` (aparecem em `storage/logs/laravel.log`).

Para enviar emails reais, edite `.env`:

```env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io  # ou smtp.gmail.com
MAIL_PORT=2525
MAIL_USERNAME=seu_usuario
MAIL_PASSWORD=sua_senha
MAIL_ENCRYPTION=tls
```

**Recomendado para testes:** [Mailtrap.io](https://mailtrap.io) (gratuito)

---

## 🧪 Executar Testes

O projeto possui **138 testes unitários** cobrindo Services e Validações.

```bash
# Com descrições legíveis e coloridas
docker exec laravel5_app vendor/bin/phpunit --testdox --colors=always
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

# Processar fila até esvaziar e parar
docker exec laravel5_app php artisan queue:work --stop-when-empty

# Processar fila continuamente
docker exec laravel5_app php artisan queue:work

# Processar fila em background (daemon)
docker exec -d laravel5_app php artisan queue:work --daemon

# Ver status da fila
docker exec laravel5_app php artisan queue:failed

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

**Notificações/Emails não estão sendo enviados:**
```bash
# Verificar se a fila está rodando
docker exec laravel5_app php artisan queue:work

# Ver jobs falhados
docker exec laravel5_app php artisan queue:failed

# Reprocessar jobs falhados
docker exec laravel5_app php artisan queue:retry all

# Verificar configuração de email nos logs
docker exec laravel5_app tail -f storage/logs/laravel.log
```

**Fila com muitos jobs pendentes:**
```bash
# Limpar toda a fila (cuidado!)
docker exec laravel5_app php artisan queue:flush
```