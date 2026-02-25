#!/bin/bash

# Script de Instalação - Jornal a Borda
# Este script instala e configura o projeto sem Docker

set -e  # Para o script se houver erro

echo "=========================================="
echo "  Instalação - Jornal a Borda"
echo "=========================================="
echo ""

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Função para verificar se comando existe
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Verificar dependências
echo "🔍 Verificando dependências..."
echo ""

if ! command_exists php; then
    echo -e "${RED}❌ PHP não encontrado. Por favor, instale o PHP 8.2 ou superior.${NC}"
    exit 1
fi

PHP_VERSION=$(php -r 'echo PHP_VERSION;')
echo -e "${GREEN}✅ PHP encontrado: $PHP_VERSION${NC}"

if ! command_exists composer; then
    echo -e "${RED}❌ Composer não encontrado. Por favor, instale o Composer.${NC}"
    exit 1
fi

COMPOSER_VERSION=$(composer --version | head -n 1)
echo -e "${GREEN}✅ Composer encontrado: $COMPOSER_VERSION${NC}"

if ! command_exists mysql; then
    echo -e "${YELLOW}⚠️  MySQL não encontrado no PATH. Certifique-se de que está instalado.${NC}"
fi

echo ""
echo "=========================================="
echo ""

# Verificar se .env existe
if [ ! -f .env ]; then
    echo "📝 Criando arquivo .env..."
    if [ -f .env.example ]; then
        cp .env.example .env
        echo -e "${GREEN}✅ Arquivo .env criado a partir de .env.example${NC}"
    else
        echo -e "${RED}❌ Arquivo .env.example não encontrado!${NC}"
        exit 1
    fi
    echo ""
    echo -e "${YELLOW}⚠️  IMPORTANTE: Configure o arquivo .env com suas credenciais do banco de dados!${NC}"
    echo "   Edite o arquivo .env e configure:"
    echo "   - DB_DATABASE"
    echo "   - DB_USERNAME"
    echo "   - DB_PASSWORD"
    echo ""
    read -p "Pressione ENTER quando tiver configurado o .env..."
else
    echo -e "${GREEN}✅ Arquivo .env já existe${NC}"
fi

echo ""
echo "=========================================="
echo ""

# Instalar dependências do Composer
echo "📦 Instalando dependências do Composer..."
composer install --no-interaction --prefer-dist --optimize-autoloader
echo -e "${GREEN}✅ Dependências instaladas${NC}"
echo ""

# Gerar chave da aplicação
echo "🔑 Gerando chave da aplicação..."
php artisan key:generate --force
echo -e "${GREEN}✅ Chave gerada${NC}"
echo ""

# Verificar conexão com banco de dados
echo "🔌 Verificando conexão com banco de dados..."
if php artisan db:show >/dev/null 2>&1; then
    echo -e "${GREEN}✅ Conexão com banco de dados OK${NC}"
else
    echo -e "${RED}❌ Erro ao conectar com o banco de dados!${NC}"
    echo "   Verifique as configurações no arquivo .env"
    exit 1
fi
echo ""

# Executar migrations
echo "🗄️  Executando migrations..."
php artisan migrate:fresh --force
echo -e "${GREEN}✅ Migrations executadas${NC}"
echo ""

# Executar seeders
echo "🌱 Populando banco de dados com seeders..."
php artisan db:seed --force
echo -e "${GREEN}✅ Seeders executados${NC}"
echo ""

# Criar link simbólico para storage
echo "🔗 Criando link simbólico para storage..."
php artisan storage:link
echo -e "${GREEN}✅ Link simbólico criado${NC}"
echo ""

# Ajustar permissões
echo "🔐 Ajustando permissões..."
if [ -d storage ]; then
    chmod -R 775 storage bootstrap/cache 2>/dev/null || true
    echo -e "${GREEN}✅ Permissões ajustadas${NC}"
else
    echo -e "${YELLOW}⚠️  Diretório storage não encontrado${NC}"
fi
echo ""

# Limpar caches
echo "🧹 Limpando caches..."
php artisan optimize:clear
echo -e "${GREEN}✅ Caches limpos${NC}"
echo ""

echo "=========================================="
echo -e "${GREEN}✅ Instalação concluída com sucesso!${NC}"
echo "=========================================="
echo ""
echo "📋 Próximos passos:"
echo ""
echo "1. Inicie o servidor de desenvolvimento:"
echo "   ${YELLOW}php artisan serve${NC}"
echo ""
echo "2. Acesse no navegador:"
echo "   ${YELLOW}http://localhost:8000${NC}"
echo ""
echo "3. Faça login no painel admin:"
echo "   ${YELLOW}http://localhost:8000/login${NC}"
echo ""
echo "4. Credenciais padrão:"
echo "   Admin: ${YELLOW}admin@jornalaborda.com.br${NC} / ${YELLOW}password${NC}"
echo "   Editor: ${YELLOW}editor@jornalaborda.com.br${NC} / ${YELLOW}password${NC}"
echo "   Redatora: ${YELLOW}redatora@jornalaborda.com.br${NC} / ${YELLOW}password${NC}"
echo ""
echo "=========================================="

