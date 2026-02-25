#!/bin/bash

# Script de Deploy - Limpa caches do Laravel
# Execute este script após fazer deploy no servidor de produção

echo "Limpando caches do Laravel..."

php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo "Caches limpos com sucesso!"

