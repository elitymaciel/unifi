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

# Garante que as pastas de storage e cache tenham as permissões certas para o appuser
docker compose -f docker-compose.production.yml exec app sh -c "chown -R appuser:appuser /var/www/storage /var/www/bootstrap/cache && chmod -R 775 /var/www/storage /var/www/bootstrap/cache"

docker compose -f docker-compose.production.yml exec app php artisan migrate --force
docker compose -f docker-compose.production.yml exec app php artisan config:cache
docker compose -f docker-compose.production.yml exec app php artisan route:cache
docker compose -f docker-compose.production.yml exec app php artisan view:cache
docker compose -f docker-compose.production.yml exec app php artisan optimize

# Opcional: restart queue workers se usar Horizon/Queue
# php artisan queue:restart

echo "✅ Deploy concluído!"
