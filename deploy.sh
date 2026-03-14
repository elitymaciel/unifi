#!/bin/bash
set -e  # para no primeiro erro

echo "🚀 Iniciando deploy do Laravel..."

cd ~/laravel

docker system prune -a -f
docker builder prune -f

# Build dos assets frontend (sem precisar de Node instalado no host)
echo "📦 Compilando assets com npm..."
docker run --rm -v $(pwd):/app -w /app node:20-alpine sh -c "npm ci && npm run build"

# Docker parte --remove-orphans --volumes
docker compose -f docker-compose.production.yml down --remove-orphans --volumes
docker compose -f docker-compose.production.yml pull
docker compose -f docker-compose.production.yml up -d --build  # --build só se tiver build local

# Garante que o banco SQLite existe e tem permissões corretas
docker compose -f docker-compose.production.yml exec app sh -c "touch /var/www/database/database.sqlite && chown -R appuser:appuser /var/www/database && chmod -R 775 /var/www/database"

docker compose -f docker-compose.production.yml exec app php artisan migrate --force
docker compose -f docker-compose.production.yml exec app php artisan config:cache
docker compose -f docker-compose.production.yml exec app php artisan route:cache
docker compose -f docker-compose.production.yml exec app php artisan view:cache
docker compose -f docker-compose.production.yml exec app php artisan optimize
sleep 10
# Opcional: restart queue workers se usar Horizon/Queue
# php artisan queue:restart

echo "✅ Deploy concluído!"
