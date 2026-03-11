#!/bin/bash
set -e
echo "🚀 Iniciando deploy..."

docker compose pull
docker compose down
docker compose up -d

docker compose exec -T app php artisan optimize:clear
docker compose exec -T app php artisan optimize
docker compose exec -T app php artisan storage:link
docker compose exec -T app php artisan migrate --force
docker compose exec -T app php artisan queue:restart  # se usar queue

echo "✅ Deploy concluído com sucesso!"