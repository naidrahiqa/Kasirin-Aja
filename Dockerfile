# ============================================
# Dockerfile untuk Kasirin Aja (Laravel 10)
# ============================================
# Base image: PHP 8.3 FPM (FastCGI Process Manager)
# FPM dipakai karena kita akan serve lewat Nginx
FROM php:8.3-fpm

# ---- Install Dependencies Sistem ----
# Ini library-library yang dibutuhkan oleh PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ---- Install Composer ----
# Composer = package manager PHP (kayak npm untuk Node.js)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ---- Install Node.js & NPM ----
# Dibutuhkan untuk compile asset frontend (Vite + Tailwind)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean

# ---- Set Working Directory ----
# Semua perintah setelah ini akan dijalankan dari /var/www/html
WORKDIR /var/www/html

# ---- Copy File Project ----
# Copy semua file project ke dalam container
COPY . .

# ---- Install PHP Dependencies ----
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# ---- Install Node Dependencies & Build Assets ----
RUN npm ci && npm run build

# ---- Set Permissions ----
# Laravel butuh write access ke folder storage dan bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ---- Expose Port ----
# PHP-FPM listen di port 9000
EXPOSE 9000

# ---- Start PHP-FPM ----
CMD ["php-fpm"]
