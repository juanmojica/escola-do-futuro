FROM php:7.3-cli

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev

# Instalar extensões PHP necessárias para Laravel 5.8
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    zip \
    exif \
    pcntl

# Instalar Composer 2.2 (última versão compatível com PHP 7.3)
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD ["bash"]
