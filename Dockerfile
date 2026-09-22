# ─────────────────────────────────────────────
# Base: PHP 8.1 + Apache + extensiones comunes
# ─────────────────────────────────────────────
FROM php:8.1-apache AS base

RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpng-dev \
    libzip-dev \
    libonig-dev \
    unzip \
    git \
    && docker-php-ext-install \
        pdo_mysql \
        intl \
        gd \
        zip \
        opcache \
        mbstring \
    && rm -rf /var/lib/apt/lists/*

# opcache tuning para producción
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=4000'; \
    echo 'opcache.revalidate_freq=60'; \
    echo 'opcache.fast_shutdown=1'; \
} > /usr/local/etc/php/conf.d/opcache.ini

RUN a2enmod rewrite
COPY docker/apache.conf /etc/apache2/sites-enabled/000-default.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ─────────────────────────────────────────────
# Dev: código montado como volumen
# ─────────────────────────────────────────────
FROM base AS dev

COPY docker/entrypoint-dev.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENV COMPOSER_ALLOW_SUPERUSER=1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# ─────────────────────────────────────────────
# Prod: código copiado en la imagen, deps optimizadas
# ─────────────────────────────────────────────
FROM base AS prod

COPY htdocs/ .

RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && mkdir -p runtime/logs web/assets \
    && chown -R www-data:www-data runtime web/assets \
    && chmod -R 775 runtime web/assets

CMD ["apache2-foreground"]
