# -------------------------
# ETAPA 1: Base (Alpine)
# -------------------------
FROM php:8.4-fpm-alpine AS base

# Dependencias del sistema + runtime
RUN apk add --no-cache \
    nginx \
    curl \
    libpng \
    libjpeg-turbo \
    freetype \
    libzip \
    icu \
    oniguruma

# Dependencias de build (temporales)
RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev

# Configuración correcta de GD (clave)
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg

# Extensiones PHP
RUN docker-php-ext-install \
    pdo_mysql \
    bcmath \
    gd \
    zip \
    intl \
    opcache

# Limpiar deps de build (reduce tamaño)
RUN apk del .build-deps

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# -------------------------
# ETAPA 2: Build (vendor)
# -------------------------
FROM base AS build
WORKDIR /var/www

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction \
    --no-progress

# -------------------------
# ETAPA 3: Producción
# -------------------------
FROM base AS production
WORKDIR /var/www

# Copiar vendor optimizado
COPY --from=build /var/www/vendor ./vendor

# Copiar app
COPY . .

# Nginx config
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

# Usuario correcto en Alpine
RUN addgroup -g 1000 www && adduser -G www -g www -s /bin/sh -D www

# Permisos
RUN chown -R www:www storage bootstrap/cache

# Generar autoloader optimizado
RUN composer dump-autoload --optimize

# Nota: Los comandos de caché se moverán al entrypoint para permitir
# flexibilidad con las variables de entorno en AWS.

# Copiar el script de entrada
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Dar permisos de ejecución
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

# Usar el script como punto de entrada
ENTRYPOINT ["entrypoint.sh"]