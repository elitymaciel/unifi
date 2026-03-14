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

# docker cp extrai arquivos de diretórios normais da imagem (não precisa de VOLUME)
docker cp "$TMP_CONTAINER:/var/www/public/." /tmp/laravel_public_seed/

docker run --rm \
  -v laravel_laravel_public:/target \
  -v /tmp/laravel_public_seed:/source:ro \
  busybox sh -c "cp -r /source/. /target/"

docker rm "$TMP_CONTAINER"
rm -rf /tmp/laravel_public_seed

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
