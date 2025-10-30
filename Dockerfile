# Imagen base con PHP y Composer
FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Generar la clave de aplicación
RUN php artisan key:generate

# Exponer el puerto 8000
EXPOSE 8000

# Comando para ejecutar Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000
