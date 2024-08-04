# Dockerfile
FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    zip

# Install PHP extensions
RUN docker-php-ext-install intl zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Set working directory
WORKDIR /var/www/symfony

# Copy existing application directory contents
COPY . /var/www/symfony

# Install Symfony dependencies
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php", "-S", "0.0.0.0:9000", "-t", "public"]