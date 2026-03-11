FROM nginx:stable-alpine
COPY --from=node-builder /var/www/public /var/www/public
COPY docker/nginx/conf.d /etc/nginx/conf.d
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]