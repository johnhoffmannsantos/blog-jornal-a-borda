.PHONY: help up down restart build logs ps shell migrate fresh seed test clean

# Cores para output
GREEN  := \033[0;32m
YELLOW := \033[0;33m
BLUE   := \033[0;34m
NC     := \033[0m # No Color

help: ## Mostra esta mensagem de ajuda
	@echo "$(BLUE)Comandos disponíveis:$(NC)"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  $(GREEN)%-15s$(NC) %s\n", $$1, $$2}'

up: ## Sobe os containers Docker e mostra informações de acesso
	@echo "$(BLUE)🚀 Subindo containers Docker...$(NC)"
	@docker compose up -d
	@echo "$(YELLOW)⏳ Aguardando containers iniciarem...$(NC)"
	@sleep 5
	@echo ""
	@echo "$(GREEN)✅ Containers iniciados com sucesso!$(NC)"
	@echo ""
	@echo "$(BLUE)════════════════════════════════════════════════════════════$(NC)"
	@echo "$(GREEN)📋 INFORMAÇÕES DE ACESSO$(NC)"
	@echo "$(BLUE)════════════════════════════════════════════════════════════$(NC)"
	@echo ""
	@echo "$(YELLOW)🌐 Laravel:$(NC)"
	@echo "   URL: $(GREEN)http://localhost:3098$(NC)"
	@echo ""
	@echo "$(YELLOW)🗄️  MySQL:$(NC)"
	@echo "   Host: $(GREEN)localhost$(NC)"
	@echo "   Porta: $(GREEN)3307$(NC)"
	@echo "   Database: $(GREEN)laravel_db$(NC)"
	@echo "   Usuário: $(GREEN)laravel_user$(NC)"
	@echo "   Senha: $(GREEN)laravel_password$(NC)"
	@echo ""
	@echo "$(YELLOW)📦 Containers:$(NC)"
	@docker compose ps
	@echo ""
	@echo "$(BLUE)════════════════════════════════════════════════════════════$(NC)"
	@echo "$(GREEN)💡 Comandos úteis:$(NC)"
	@echo "   make logs     - Ver logs dos containers"
	@echo "   make shell    - Acessar shell do container Laravel"
	@echo "   make migrate  - Executar migrações"
	@echo "   make down     - Parar containers"
	@echo "$(BLUE)════════════════════════════════════════════════════════════$(NC)"

down: ## Para os containers Docker
	@echo "$(YELLOW)🛑 Parando containers...$(NC)"
	@docker compose down

restart: ## Reinicia os containers
	@echo "$(YELLOW)🔄 Reiniciando containers...$(NC)"
	@docker compose restart

build: ## Constrói as imagens Docker
	@echo "$(BLUE)🔨 Construindo imagens...$(NC)"
	@docker compose build

logs: ## Mostra os logs dos containers
	@docker compose logs -f

ps: ## Lista os containers em execução
	@docker compose ps

shell: ## Acessa o shell do container Laravel
	@docker compose exec app bash

migrate: ## Executa as migrações do banco de dados
	@echo "$(BLUE)📊 Executando migrações...$(NC)"
	@docker compose exec app php artisan migrate --force

fresh: ## Recria o banco de dados (apaga tudo e recria)
	@echo "$(YELLOW)⚠️  Recriando banco de dados...$(NC)"
	@docker compose exec app php artisan migrate:fresh --force

seed: ## Executa os seeders
	@echo "$(BLUE)🌱 Executando seeders...$(NC)"
	@docker compose exec app php artisan db:seed --force

test: ## Executa os testes
	@docker compose exec app php artisan test

clean: ## Remove containers, volumes e imagens
	@echo "$(YELLOW)🧹 Limpando containers, volumes e imagens...$(NC)"
	@docker compose down -v --rmi local

