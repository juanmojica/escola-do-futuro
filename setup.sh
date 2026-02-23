#!/bin/bash

# Script de instalação automatizada

echo "Instalação da aplicação Laravel 5.8 com Docker"

# Cores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' 

# 1. Verifica se o Docker está rodando
echo -e "${YELLOW}[1/8]${NC} Verificando Docker..."
if ! docker info > /dev/null 2>&1; then
    echo "Docker não está rodando. Inicie o Docker e tente novamente."
    exit 1
fi
echo -e "${GREEN}✓${NC} Docker está rodando"

# 2. Criar arquivo .env se não existir
echo -e "${YELLOW}[2/8]${NC} Configurando arquivo .env..."
if [ ! -f .env ]; then
    cp .env.example .env 2>/dev/null || echo "APP_NAME=\"Escola do Futuro\"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost
APP_TIMEZONE=America/Sao_Paulo

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=escola
DB_USERNAME=root
DB_PASSWORD=root

QUEUE_CONNECTION=database
" > .env
    echo -e "${GREEN}✓${NC} Arquivo .env criado"
else
    echo -e "${GREEN}✓${NC} Arquivo .env já existe"
fi

# 3. Criar arquivo .env.testing
echo -e "${YELLOW}[3/8]${NC} Configurando arquivo .env.testing..."
if [ ! -f .env.testing ]; then
    cp .env .env.testing
    # Substituir nome do banco para testing
    sed -i 's/DB_DATABASE=escola/DB_DATABASE=escola_testing/' .env.testing
    sed -i 's/APP_ENV=local/APP_ENV=testing/' .env.testing
    echo -e "${GREEN}✓${NC} Arquivo .env.testing criado"
else
    echo -e "${GREEN}✓${NC} Arquivo .env.testing já existe"
fi

# 4. Subir containers Docker
echo -e "${YELLOW}[4/8]${NC} Iniciando containers Docker..."
docker compose up -d
echo -e "${GREEN}✓${NC} Containers iniciados"

# Aguardar MySQL iniciar
echo "Aguardando MySQL inicializar (10 segundos)..."
sleep 10

# 5. Instalar dependências do Composer
echo -e "${YELLOW}[5/8]${NC} Instalando dependências do Composer..."
docker exec laravel5_app composer install --no-interaction
echo -e "${GREEN}✓${NC} Dependências instaladas"

# 6. Gerar chave da aplicação
echo -e "${YELLOW}[6/8]${NC} Gerando chave da aplicação..."
docker exec laravel5_app php artisan key:generate
echo -e "${GREEN}✓${NC} Chave gerada"

# Reiniciar container para recarregar o .env com a nova chave
echo "Reiniciando container para aplicar a chave..."
docker compose restart app
sleep 5

# 7. Executar migrations no banco de desenvolvimento
echo -e "${YELLOW}[7/8]${NC} Executando migrations (desenvolvimento)..."
docker exec laravel5_app php artisan migrate --force
echo -e "${GREEN}✓${NC} Migrations executadas no banco de desenvolvimento"

# 8. Executar migrations no banco de testes
echo -e "${YELLOW}[8/8]${NC} Executando migrations (testes)..."
docker exec laravel5_app php artisan migrate --env=testing --force
echo -e "${GREEN}✓${NC} Migrations executadas no banco de testes"

echo ""
echo "================================================"
echo -e "${GREEN}  ✓ Instalação concluída com sucesso!${NC}"
echo "================================================"
echo ""
echo "Próximos passos:"
echo ""
echo "  1. Acessar a aplicação:"
echo "     http://localhost:8000"
echo ""
echo "  2. Popular o banco (opcional):"
echo "     docker exec laravel5_app php artisan db:seed"
echo ""
echo "  3. Rodar testes:"
echo "     docker exec laravel5_app vendor/bin/phpunit"
echo ""
echo "  4. Rodar testes com descrições:"
echo "     docker exec laravel5_app vendor/bin/phpunit --testdox"
echo ""
echo "  5. Processar fila de jobs (notificações/emails):"
echo "     docker exec laravel5_app php artisan queue:work"
echo ""
echo "     Ou em modo daemon (background):"
echo "     docker exec -d laravel5_app php artisan queue:work --daemon"
echo ""
echo "================================================"
