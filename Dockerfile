# Base image
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update

RUN apt-get install -y \
    libpng-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    nginx \
    supervisor \
    libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Set environment variables from copy of .env.example file
RUN cp .env.example .env

# Install PHP dependencies
RUN composer install --optimize-autoloader

# Install Node.js dependencies and build assets
# RUN npm install && npm run build

# Set permissions for storage and bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy Nginx configuration
COPY nginx.conf /etc/nginx/sites-enabled/default

# Copy Supervisor config file
COPY supervisord.conf /etc/supervisord.conf

# Expose ports for web and websocket
EXPOSE 80 6001

# Run all services with supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]