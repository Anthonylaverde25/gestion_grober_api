#!/bin/sh
set -e

# Mensaje informativo
echo "---------------------------------------------------------"
echo "🚀 Iniciando Entorno de Producción Grober"
echo "---------------------------------------------------------"

# Configuración dinámica del puerto de Nginx
# Railway asigna un puerto aleatorio en la variable $PORT
export PORT=${PORT:-80}
echo "🌐 Configurando Nginx para escuchar en el puerto $PORT..."
sed -i "s/PORT_PLACEHOLDER/$PORT/g" /etc/nginx/http.d/default.conf

# Optimización de caché
echo "📦 Optimizando configuración y rutas..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecución de migraciones
if [ "$FRESH_DATABASE" = "true" ]; then
    echo "🧹 LIMPIEZA PROFUNDA: Recreando base de datos desde cero..."
    php artisan migrate:fresh --force
else
    if [ "$RUN_MIGRATIONS" = "true" ]; then
        echo "🗄️  Ejecutando migraciones de base de datos..."
        php artisan migrate --force
    fi
fi

# Ejecución de semillas (opcional)
if [ "$RUN_SEEDS" = "true" ]; then
    echo "🌱  Sembrando datos iniciales (seeds)..."
    php artisan db:seed --force
fi

echo "✅ Sistema preparado. Iniciando servicios..."
echo "---------------------------------------------------------"

# Iniciamos PHP-FPM en segundo plano
php-fpm -D

# Iniciamos Nginx en primer plano (esto mantiene el contenedor vivo)
nginx -g 'daemon off;'
