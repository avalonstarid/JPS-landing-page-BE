FROM dunglas/frankenphp:php8.4

# Install Dependencies, Extensions, & SUPERVISOR
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    gifsicle \
    jpegoptim \
    libavif-bin \
    optipng \
    pngquant \
    supervisor \
    unzip \
    zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP Extensions
RUN install-php-extensions \
    bcmath \
    exif \
    gd \
    igbinary \
    imagick \
    intl \
    opcache \
    pcntl \
    pdo_mysql \
    redis \
    sodium \
    zip

# Environment Variables
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV OCTANE_SERVER=frankenphp
ENV SERVER_NAME=":80"

# Config PHP
COPY ./docker/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY ./docker/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

# Config Supervisor
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./docker/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Setup Workdir
WORKDIR /app

# Install Vendor (Caching Layer)
COPY composer.json composer.lock ./
COPY app/helpers.php app/helpers.php
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Copy Code
COPY . .

# Finalize
RUN composer dump-autoload --optimize

# Setup Permission & Structure
RUN mkdir -p storage/framework/{sessions,views,cache/data} storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Entrypoint Script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

ENTRYPOINT ["entrypoint"]

# Default Command (Octane Start)
CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=80", "--admin-port=2019", "--workers=auto", "--max-requests=1000"]