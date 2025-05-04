FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_pgsql bcmath

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set workdir
WORKDIR /var/www/html

# Salin file .env contoh (optional)
COPY .env.example .env

# Copy seluruh project Laravel
COPY . .

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port
EXPOSE 8000

# Jalankan Laravel server
CMD php artisan serve --host=0.0.0.0 --port=8000
