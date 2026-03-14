#!/bin/bash
set -e  # para no primeiro erro

echo "🚀 Iniciando deploy do Laravel..."

cd ~/laravel


# Docker parte
docker compose -f docker-compose.production.yml down --remove-orphans
docker compose -f docker-compose.production.yml pull
docker compose -f docker-compose.production.yml up -d --build  # --build só se tiver build local

# Opcional: restart queue workers se usar Horizon/Queue
# php artisan queue:restart

echo "✅ Deploy concluído!"
