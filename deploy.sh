#!/bin/bash
set -e  # para no primeiro erro

echo "🚀 Iniciando deploy do Laravel..."

cd ~/laravel

# Atualiza código se estiver usando git no servidor (opcional)
# git pull origin main

# Instala dependências (se não buildar imagem no CI)
composer install --no-dev --optimize-autoloader

# Migrações e cache (com --force em prod)
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Docker parte
docker compose -f docker-compose.production.yml down --remove-orphans
docker compose -f docker-compose.production.yml pull
docker compose -f docker-compose.production.yml up -d --build  # --build só se tiver build local

# Opcional: restart queue workers se usar Horizon/Queue
# php artisan queue:restart

echo "✅ Deploy concluído!"
