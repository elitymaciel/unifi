#!/bin/bash
set -e  # para no primeiro erro

echo "🚀 Iniciando deploy do Laravel..."

cd ~/laravel

docker system prune -a -f
docker builder prune -f

# Derruba tudo e remove volumes para deploy limpo
docker compose -f docker-compose.production.yml down --remove-orphans --volumes

# Baixa as imagens mais recentes do GHCR
docker compose -f docker-compose.production.yml pull

# Semeia o volume laravel_public com os assets da imagem nginx
# Necessário porque o volume mount sobrescreve os arquivos baked na imagem
echo "📦 Semeando volume public com assets do nginx..."
NGINX_IMAGE="ghcr.io/elitymaciel/laravel-nginx:latest"
TMP_CONTAINER=$(docker create "$NGINX_IMAGE")
docker run --rm \
  --volumes-from "$TMP_CONTAINER":ro \
  -v laravel_laravel_public:/target \
  busybox sh -c "cp -r /var/www/public/. /target/"
docker rm "$TMP_CONTAINER"

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
