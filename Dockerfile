# 1. Base image with FrankenPHP + Composer
FROM dunglas/frankenphp:php8.3 AS base

# Set working directory
WORKDIR /app

# Update and install dependencies
RUN apt update && apt install -y \
    curl \
    gifsicle \
    git \
    htop \
    jpegoptim \
    nano \
    optipng \
    pngquant \
    procps \
    webp \
    zip

RUN install-php-extensions \
    exif \
    gd \
    imagick \
    pcntl \
    pdo_mysql \
    zip \
    @composer

# 2. Install system & PHP extensions + Composer
FROM base AS dependencies

RUN mkdir -p /temp/dev/app

# 3. Composer install tanpa scripts
WORKDIR /temp/dev

COPY composer.json composer.lock ./
COPY app/helpers.php /temp/dev/app/

# Install composer dependencies
RUN cd /temp/dev && composer install --optimize-autoloader --no-dev --no-scripts

# 4. Final image: copy vendor + seluruh kode, lalu run manual scripts
FROM base AS release

# Set working directory
WORKDIR /app

COPY --from=dependencies /temp/dev/vendor vendor

COPY . .

# Optimize autoloader and discover packages
RUN composer dump-autoload --optimize \
    && php artisan package:discover --ansi

# Storage Link
RUN php artisan storage:link

# Generate Docs (Error mesti manual)
#RUN php artisan scribe:generate

EXPOSE 80

# --watch Error Laravel 12
#CMD ["php", "artisan", "octane:frankenphp", "--workers=4", "--port=80", "--admin-port=2019"]
CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=80", "--admin-port=2019"]
