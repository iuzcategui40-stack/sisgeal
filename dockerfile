FROM php:8.3-fpm

# Instalar dependencias del sistema y extensiones de PHP requeridas por Laravel y Filament
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libzip-dev \
    zip \
    unzip

# Limpiar caché del sistema para mantener la imagen ligera
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Configurar extensiones que requieren parámetros previos (GD con soporte de fuentes y formatos)
RUN docker-php-ext-configure intl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg

# Instalar extensiones de PHP (Se compilan xml, dom, zip e intl de forma nativa)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd xml dom intl zip

# Obtener Composer actualizado
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear el directorio de caché de Composer y darle permisos al usuario www-data
RUN mkdir -p /var/www/.composer && chown -R www-data:www-data /var/www/.composer

# Configurar el directorio de trabajo
WORKDIR /var/www/dsisgeal

USER www-data