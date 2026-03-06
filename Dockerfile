FROM php:8.2-apache

# Install PDO MySQL and Curl extensions
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    && docker-php-ext-install pdo pdo_mysql curl \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Update Apache config for directory access if needed
# (Standard apache image usually has these set correctly for /var/www/html)

EXPOSE 80
