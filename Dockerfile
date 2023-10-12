FROM php:8.1-fpm

# Install system dependencies and clear cache
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    npm \
    libonig-dev \
    libpq-dev \
    libgmp-dev \
    libxml2-dev \
    zip \
    unzip && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql pdo_mysql mbstring exif pcntl bcmath gd gmp

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u 1000 -d /home/www www
RUN mkdir -p /home/www/.composer && \
    chown -R www:www /home/www

# Set working directory
WORKDIR /var/www

COPY . .

RUN npm install
RUN npm run build

USER www
