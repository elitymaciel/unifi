FROM node:22-alpine AS node-builder
WORKDIR /var/www
COPY package*.json ./
RUN npm ci
COPY vite.config.js postcss.config.js tailwind.config.js ./
COPY resources resources
COPY public public
RUN npm run build

FROM nginx:stable-alpine
COPY --from=node-builder /var/www/public /var/www/public
COPY docker/nginx/conf.d /etc/nginx/conf.d
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]