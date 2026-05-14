#!/bin/sh
set -e

# Mensaje informativo
echo "---------------------------------------------------------"
echo "🚀 Iniciando Entorno de Producción Grober"
echo "---------------------------------------------------------"

# Optimización de caché
echo "📦 Optimizando configuración y rutas..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecución de migraciones
# Solo se ejecutan si la base de datos está configurada
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "🗄️  Ejecutando migraciones de base de datos..."
    php artisan migrate --force
fi

echo "✅ Sistema preparado. Iniciando servicios..."
echo "---------------------------------------------------------"

# Iniciamos PHP-FPM en segundo plano
php-fpm -D

# Iniciamos Nginx en primer plano (esto mantiene el contenedor vivo)
nginx -g 'daemon off;'
