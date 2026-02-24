# Blog Jornal a Borda

Sistema de blog desenvolvido em Laravel com MySQL usando Docker.

## 🚀 Tecnologias

- Laravel 12
- PHP 8.2
- MySQL 8.0
- Docker & Docker Compose
- Bootstrap 5

## 📋 Funcionalidades

- Sistema de posts com categorias e tags
- Páginas de autor
- Sistema de comentários
- Navegação por categorias
- Design responsivo

## 🛠️ Instalação

1. Clone o repositório
2. Execute `make up` para subir os containers
3. Acesse http://localhost:3098

## 📝 Comandos Make

- `make up` - Sobe os containers e mostra informações de acesso
- `make down` - Para os containers
- `make migrate` - Executa as migrações
- `make seed` - Popula o banco com dados de exemplo
- `make shell` - Acessa o shell do container Laravel

## 🔌 Portas

- Laravel: http://localhost:3098
- MySQL: localhost:3307

## 📦 Estrutura

- Models: Post, Category, Tag, Comment, User
- Controllers: HomeController, PostController, CategoryController, AuthorController
- Views: home, post, category, author

## 👥 Autores

Desenvolvido com muito carinho pela equipe de T.I do Jornal a Borda ♥
