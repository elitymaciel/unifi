#!/bin/bash
set -e  # para no primeiro erro

echo "🚀 Iniciando deploy do Laravel..."

cd ~/laravel

docker system prune -a -f
docker builder prune -f

# Derruba tudo e remove volumes para deploy limpo
docker compose -f docker-compose.production.yml down --remove-orphans

# Baixa as imagens mais recentes do GHCR
docker compose -f docker-compose.production.yml pull

# Sobe os containers
docker compose -f docker-compose.production.yml up -d

# Garante que o banco SQLite existe e tem permissões corretas
docker compose -f docker-compose.production.yml exec app sh -c "touch /var/www/database/database.sqlite && chown -R appuser:appuser /var/www/database && chmod -R 775 /var/www/database"

docker compose -f docker-compose.production.yml exec app php artisan migrate --force
docker compose -f docker-compose.production.yml exec app php artisan config:cache
docker compose -f docker-compose.production.yml exec app php artisan route:cache
docker compose -f docker-compose.production.yml exec app php artisan view:cache
docker compose -f docker-compose.production.yml exec app php artisan optimize

# Opcional: restart queue workers se usar Horizon/Queue
# php artisan queue:restart

echo "✅ Deploy concluído!"
