#!/bin/bash
set -e  # para no primeiro erro

echo "🚀 Iniciando deploy do Laravel..."

cd ~/laravel

docker system prune -f   # remove caches antigos
sync; echo 3 > /proc/sys/vm/drop_caches

# Docker parte
docker compose -f docker-compose.production.yml down --remove-orphans
docker compose -f docker-compose.production.yml pull
docker compose -f docker-compose.production.yml up -d --build  # --build só se tiver build local

docker compose -f docker-compose.production.yml exec app php artisan migrate --force
docker compose -f docker-compose.production.yml exec app php artisan config:cache
docker compose -f docker-compose.production.yml exec app php artisan route:cache
docker compose -f docker-compose.production.yml exec app php artisan view:cache
docker compose -f docker-compose.production.yml exec app php artisan optimize

# Opcional: restart queue workers se usar Horizon/Queue
# php artisan queue:restart

echo "✅ Deploy concluído!"
