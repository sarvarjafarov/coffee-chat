# Stage 1: install PHP dependencies with Composer
FROM composer:2 AS vendor
WORKDIR /app

# Copy composer manifest and minimal app structure early to leverage cache for dependencies
COPY composer.json composer.lock artisan ./
COPY bootstrap ./bootstrap
COPY app ./app
COPY config ./config
COPY routes ./routes
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# Copy full application source and rebuild optimised autoload map
COPY . .
RUN composer dump-autoload --no-dev --classmap-authoritative

# Stage 2: build front-end assets with Node
FROM node:18 AS frontend
WORKDIR /app

COPY package.json package-lock.json* yarn.lock* ./
RUN if [ -f package-lock.json ]; then npm ci; elif [ -f yarn.lock ]; then yarn install --frozen-lockfile; else npm install; fi

COPY resources ./resources
COPY vite.config.js postcss.config.js tailwind.config.js ./
COPY public ./public
RUN npm run build

# Stage 3: production image with PHP + Apache
FROM php:8.2-apache

ENV DEBIAN_FRONTEND=noninteractive \
    APP_ENV=production \
    APP_KEY=base64:omuuRFBmTBdST7sPtXXqpCbjOy98Yu2UHyNq++3MBJA= \
    APP_DEBUG=false

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libzip-dev \
        libpq-dev \
        libpng-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip \
    && rm -rf /var/lib/apt/lists/*

# Configure Apache to serve the Laravel public directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/000-default.conf \
    && sed -ri "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/default-ssl.conf \
    && sed -ri "s!<Directory /var/www/>!<Directory ${APACHE_DOCUMENT_ROOT}/>!g" /etc/apache2/apache2.conf \
    && printf '<Directory %s>\n    Options -Indexes +FollowSymLinks\n    AllowOverride All\n    Require all granted\n    DirectoryIndex index.php index.html\n</Directory>\n' "${APACHE_DOCUMENT_ROOT}" > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel \
    && a2enmod rewrite

WORKDIR /var/www/html

# Copy application code from composer stage
COPY --from=vendor /app /var/www/html

# Copy built assets from Node stage
COPY --from=frontend /app/public/build /var/www/html/public/build

# Ensure storage and cache directories are writable
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

ENV PORT=8080
EXPOSE 8080

# Render-specific entrypoint adjusts Apache port before starting
COPY docker/render-entrypoint.sh /usr/local/bin/render-entrypoint.sh
RUN chmod +x /usr/local/bin/render-entrypoint.sh

ENTRYPOINT ["render-entrypoint.sh"]
CMD ["apache2-foreground"]
