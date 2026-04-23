.PHONY: help up down restart build logs ps shell install ensure-vendor migrate fix-post-enum fresh seed test clean

# Bash evita sh do Windows sem suporte adequado a escapes / UTF-8 no terminal
SHELL := bash

# Cores: ESC real via printf (evita '\033' literal quando echo nao interpreta escapes)
ESC := $(shell printf '\033')
GREEN  := $(ESC)[0;32m
YELLOW := $(ESC)[0;33m
BLUE   := $(ESC)[0;34m
NC     := $(ESC)[0m

help: ## Mostra esta mensagem de ajuda
	@echo "$(BLUE)Comandos disponíveis:$(NC)"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  $(GREEN)%-15s$(NC) %s\n", $$1, $$2}'

install: ## Instala dependências PHP (Composer) dentro do container app
	@echo "$(BLUE)Executando composer install...$(NC)"
	@docker compose exec app composer install --no-interaction

ensure-vendor:
	@docker compose exec app sh -c 'test -f vendor/autoload.php || composer install --no-interaction'

up: ## Sobe os containers Docker e mostra informações de acesso
	@echo "$(BLUE)Subindo containers Docker...$(NC)"
	@docker compose up -d
	@echo "$(YELLOW)Aguardando containers iniciarem...$(NC)"
	@sleep 5
	@echo ""
	@echo "$(GREEN)[OK] Containers iniciados com sucesso!$(NC)"
	@echo ""
	@echo "$(BLUE)============================================================$(NC)"
	@echo "$(GREEN)INFORMAÇÕES DE ACESSO$(NC)"
	@echo "$(BLUE)============================================================$(NC)"
	@echo ""
	@echo "$(YELLOW)Laravel:$(NC)"
	@echo "   URL: $(GREEN)http://localhost:3098$(NC)"
	@echo ""
	@echo "$(YELLOW)MySQL (host da sua máquina):$(NC)"
	@echo "   Host: $(GREEN)localhost$(NC)"
	@echo "   Porta: $(GREEN)3309$(NC)"
	@echo "   Database: $(GREEN)laravel_db$(NC)"
	@echo "   Usuário: $(GREEN)laravel_user$(NC)"
	@echo "   Senha: $(GREEN)laravel_password$(NC)"
	@echo ""
	@echo "$(YELLOW)Containers:$(NC)"
	@docker compose ps
	@echo ""
	@echo "$(BLUE)============================================================$(NC)"
	@echo "$(GREEN)Comandos úteis:$(NC)"
	@echo "   make install  - composer install no container (necessario na primeira vez)"
	@echo "   make logs     - Ver logs dos containers"
	@echo "   make shell    - Acessar shell do container Laravel"
	@echo "   make migrate       - Executar migrações"
	@echo "   make fix-post-enum - Corrigir ENUM status (se post agendado quebrar no MySQL)"
	@echo "   make down     - Parar containers"
	@echo "$(BLUE)============================================================$(NC)"

down: ## Para os containers Docker
	@echo "$(YELLOW)Parando containers...$(NC)"
	@docker compose down

restart: ## Reinicia os containers
	@echo "$(YELLOW)Reiniciando containers...$(NC)"
	@docker compose restart

build: ## Constrói as imagens Docker
	@echo "$(BLUE)Construindo imagens...$(NC)"
	@docker compose build

logs: ## Mostra os logs dos containers
	@docker compose logs -f

ps: ## Lista os containers em execução
	@docker compose ps

shell: ## Acessa o shell do container Laravel
	@docker compose exec app bash

migrate: ensure-vendor ## Executa as migrações do banco de dados
	@echo "$(BLUE)Executando migrações...$(NC)"
	@docker compose exec app php artisan migrate --force

fix-post-enum: ensure-vendor ## Ajusta ENUM status em posts (MySQL) para incluir "scheduled" sem rodar migrate inteiro
	@echo "$(BLUE)Ajustando coluna posts.status (MySQL)...$(NC)"
	@docker compose exec app php artisan posts:fix-status-enum

fresh: ensure-vendor ## Recria o banco de dados (apaga tudo e recria)
	@echo "$(YELLOW)Recriando banco de dados...$(NC)"
	@docker compose exec app php artisan migrate:fresh --force

seed: ensure-vendor ## Executa os seeders
	@echo "$(BLUE)Executando seeders...$(NC)"
	@docker compose exec app php artisan db:seed --force

test: ensure-vendor ## Executa os testes
	@docker compose exec app php artisan test

clean: ## Remove containers, volumes e imagens
	@echo "$(YELLOW)Limpando containers, volumes e imagens...$(NC)"
	@docker compose down -v --rmi local
