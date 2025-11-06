FROM php:8.2-apache

# Copy app to /var/www/html
COPY public/ /var/www/html/
COPY src/ /var/www/src/
COPY data/ /var/www/data/

# Ensure data directory writable
RUN chown -R www-data:www-data /var/www/data || true

EXPOSE 80
