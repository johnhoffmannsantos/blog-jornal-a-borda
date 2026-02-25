# Jornal a Borda

Sistema de blog desenvolvido com Laravel e MySQL, inspirado no "Jornal a Borda" - A Voz das Periferias de Osasco.

## 📋 Requisitos

- PHP 8.2 ou superior
- Composer
- MySQL 8.0 ou superior
- Node.js e NPM (opcional, para assets)
- Docker e Docker Compose (opcional, para ambiente containerizado)

## 🚀 Instalação

### Opção 1: Com Docker (Recomendado)

#### 1. Clone o repositório
```bash
git clone <url-do-repositorio>
cd jornal-a-borda
```

#### 2. Configure o ambiente
```bash
cp .env.example .env
```

Edite o arquivo `.env` e configure as seguintes variáveis:
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_password

APP_URL=http://localhost:3098
```

#### 3. Inicie os containers
```bash
docker compose up -d
```

#### 4. Instale as dependências
```bash
docker compose exec app composer install
```

#### 5. Gere a chave da aplicação
```bash
docker compose exec app php artisan key:generate
```

#### 6. Execute as migrations e seeders
```bash
docker compose exec app php artisan migrate:fresh --seed
```

Ou use o Makefile:
```bash
make fresh
```

#### 7. Crie o link simbólico para storage
```bash
docker compose exec app php artisan storage:link
```

#### 8. Acesse a aplicação
Abra seu navegador em: `http://localhost:3098`

### Opção 2: Sem Docker (Instalação Local)

#### Método A: Script Automatizado (Recomendado)

1. Clone o repositório:
```bash
git clone <url-do-repositorio>
cd jornal-a-borda
```

2. Configure o arquivo `.env`:
```bash
cp .env.example .env
```

Edite o arquivo `.env` e configure suas credenciais do banco de dados:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jornal_db
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

APP_URL=http://localhost:8000
```

3. Execute o script de instalação:
```bash
chmod +x install.sh
./install.sh
```

O script irá:
- Verificar dependências (PHP, Composer)
- Instalar dependências do Composer
- Gerar chave da aplicação
- Verificar conexão com banco de dados
- Executar migrations
- Executar seeders
- Criar link simbólico para storage
- Ajustar permissões
- Limpar caches

4. Inicie o servidor:
```bash
php artisan serve
```

Acesse: `http://localhost:8000`

#### Método B: Instalação Manual

1. Clone o repositório:
```bash
git clone <url-do-repositorio>
cd jornal-a-borda
```

2. Instale as dependências do PHP:
```bash
composer install
```

3. Configure o ambiente:
```bash
cp .env.example .env
```

Edite o arquivo `.env` e configure:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jornal_db
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

APP_URL=http://localhost:8000
```

4. Gere a chave da aplicação:
```bash
php artisan key:generate
```

5. Configure o banco de dados:
Crie o banco de dados MySQL:
```sql
CREATE DATABASE jornal_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

6. Execute as migrations e seeders:
```bash
php artisan migrate:fresh --seed
```

7. Crie o link simbólico para storage:
```bash
php artisan storage:link
```

8. Ajuste as permissões:
```bash
chmod -R 775 storage bootstrap/cache
```

9. Limpe os caches:
```bash
php artisan optimize:clear
```

10. Inicie o servidor de desenvolvimento:
```bash
php artisan serve
```

Acesse: `http://localhost:8000`

## 📦 Comandos Úteis

### Com Docker

#### Migrations
```bash
# Executar migrations
docker compose exec app php artisan migrate

# Reverter última migration
docker compose exec app php artisan migrate:rollback

# Recriar banco (apaga tudo e recria)
docker compose exec app php artisan migrate:fresh

# Recriar banco com seeders
docker compose exec app php artisan migrate:fresh --seed
```

#### Seeders
```bash
# Executar todos os seeders
docker compose exec app php artisan db:seed

# Executar seeder específico
docker compose exec app php artisan db:seed --class=BlogSeeder
```

#### Cache
```bash
# Limpar cache de configuração
docker compose exec app php artisan config:clear

# Limpar cache de rotas
docker compose exec app php artisan route:clear

# Limpar cache de views
docker compose exec app php artisan view:clear

# Limpar todos os caches
docker compose exec app php artisan optimize:clear
```

#### Storage
```bash
# Criar link simbólico
docker compose exec app php artisan storage:link

# Verificar permissões
docker compose exec app chmod -R 775 storage bootstrap/cache
```

#### Outros
```bash
# Acessar shell do container
docker compose exec app bash

# Acessar MySQL
docker compose exec mysql mysql -u laravel_user -p laravel_db

# Ver logs
docker compose logs -f app
```

### Sem Docker

#### Migrations
```bash
# Executar migrations
php artisan migrate

# Reverter última migration
php artisan migrate:rollback

# Recriar banco (apaga tudo e recria)
php artisan migrate:fresh

# Recriar banco com seeders
php artisan migrate:fresh --seed
```

#### Seeders
```bash
# Executar todos os seeders
php artisan db:seed

# Executar seeder específico
php artisan db:seed --class=BlogSeeder
```

#### Cache
```bash
# Limpar cache de configuração
php artisan config:clear

# Limpar cache de rotas
php artisan route:clear

# Limpar cache de views
php artisan view:clear

# Limpar todos os caches
php artisan optimize:clear
```

#### Storage
```bash
# Criar link simbólico
php artisan storage:link

# Verificar permissões
chmod -R 775 storage bootstrap/cache
```

## 👥 Usuários Padrão

Após executar os seeders, os seguintes usuários estarão disponíveis:

### Administrador
- **Email:** admin@jornalaborda.com.br
- **Senha:** password
- **Role:** ADMIN

### Editor
- **Email:** editor@jornalaborda.com.br
- **Senha:** password
- **Role:** EDITOR

### Redatora
- **Email:** redatora@jornalaborda.com.br
- **Senha:** password
- **Role:** REDATORA

### Login Rápido (Desenvolvimento)
No ambiente local, você pode usar os seguintes links para login rápido:
- `/quick-login/admin` - Login como Admin
- `/quick-login/editor` - Login como Editor
- `/quick-login/redatora` - Login como Redatora

## 🎯 Funcionalidades

### Público
- ✅ Homepage com posts em destaque
- ✅ Página de post individual
- ✅ Página de categoria
- ✅ Página de tag
- ✅ Página de autor
- ✅ Página "Sobre Nós"
- ✅ Página "Nossa Equipe"
- ✅ Jornal Digital (edições em PDF)
- ✅ Widgets de sidebar (Newsletter, Posts mais lidos, Tags em alta, Redes sociais)
- ✅ Seção de parceiros (carrossel)
- ✅ Sistema de comentários

### Painel Administrativo
- ✅ Dashboard com estatísticas
- ✅ Gerenciamento de Posts (CRUD completo)
- ✅ Gerenciamento de Categorias
- ✅ Gerenciamento de Tags
- ✅ Gerenciamento de Comentários
- ✅ Gerenciamento de Usuários (apenas ADMIN)
- ✅ Gerenciamento de Parceiros (apenas ADMIN)
- ✅ Gerenciamento de Jornal Digital (apenas ADMIN)
- ✅ Configurações do Site
- ✅ Configurações SMTP
- ✅ Configurações de Redes Sociais
- ✅ Perfil do usuário
- ✅ Editor WYSIWYG (TinyMCE) para posts
- ✅ Upload de imagens (featured image e imagens no editor)
- ✅ Sistema de notificações (Toasts)
- ✅ Modais de confirmação para exclusões

## 📁 Estrutura de Diretórios

```
jornal-a-borda/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Admin/          # Controllers do painel admin
│   │       └── ...             # Controllers públicos
│   └── Models/                 # Models Eloquent
├── database/
│   ├── migrations/             # Migrations do banco
│   └── seeders/                # Seeders para popular banco
├── public/
│   ├── storage/                # Arquivos públicos (imagens, PDFs)
│   └── tinymce/                # Editor TinyMCE (self-hosted)
├── resources/
│   └── views/
│       ├── admin/              # Views do painel admin
│       ├── layouts/             # Layouts (app.blade.php, admin.blade.php)
│       ├── partials/           # Partials (sidebar, etc)
│       └── ...                 # Views públicas
├── routes/
│   └── web.php                 # Rotas da aplicação
├── docker-compose.yml          # Configuração Docker
├── Dockerfile                  # Imagem Docker do Laravel
├── Makefile                    # Comandos úteis
└── install.sh                  # Script de instalação (sem Docker)
```

## 🔧 Configuração do Docker

O projeto usa Docker Compose com os seguintes serviços:

- **app** (Laravel): Porta 3098
- **mysql**: Porta 3307

Para alterar as portas, edite o arquivo `docker-compose.yml`.

## 📝 Makefile

O projeto inclui um Makefile com comandos úteis:

```bash
make fresh          # Recria o banco e executa seeders
make migrate        # Executa migrations
make seed           # Executa seeders
make clear          # Limpa todos os caches
```

## 🚀 Script de Instalação (Sem Docker)

O projeto inclui um script `install.sh` que automatiza toda a instalação sem Docker:

```bash
chmod +x install.sh
./install.sh
```

O script verifica dependências, instala pacotes, configura o ambiente, executa migrations e seeders automaticamente.

## 🗄️ Banco de Dados

### Tabelas Principais
- `users` - Usuários do sistema
- `posts` - Posts do blog
- `categories` - Categorias
- `tags` - Tags
- `comments` - Comentários
- `settings` - Configurações do site
- `partners` - Parceiros
- `journal_editions` - Edições do jornal digital

## 🔐 Segurança

- Autenticação com middleware
- Autorização baseada em roles (ADMIN, EDITOR, REDATORA)
- Validação de uploads de arquivos
- Proteção CSRF em todos os formulários
- Sanitização de inputs

## 📦 Dependências Principais

- Laravel 12
- Bootstrap 5
- Bootstrap Icons
- TinyMCE 8.3.2 (self-hosted)
- Chart.js (para gráficos)

## 🐛 Troubleshooting

### Problema: Erro ao executar migrations
```bash
# Solução: Recriar o banco
docker compose exec app php artisan migrate:fresh --seed
```

### Problema: Imagens não aparecem
```bash
# Solução: Criar link simbólico
docker compose exec app php artisan storage:link
docker compose exec app chmod -R 775 storage bootstrap/cache
```

### Problema: Cache não atualiza
```bash
# Solução: Limpar todos os caches
docker compose exec app php artisan optimize:clear
```

### Problema: Erro de permissões
```bash
# Solução: Ajustar permissões
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

## 📄 Licença

Este projeto é privado e de uso exclusivo do Jornal a Borda.

## 👨‍💻 Desenvolvimento

Desenvolvido com muito carinho pela equipe de T.I do Jornal a Borda ♥
