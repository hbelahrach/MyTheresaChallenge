FROM php:8.1-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    libicu-dev \
    zlib1g-dev \
    unzip \
    iputils-ping \
    libpq-dev \
    netcat

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) \
    intl \
    pdo_pgsql \
    opcache

# Copy the application code
COPY . /app

# Set up permissions
RUN chown -R www-data:www-data /app/var

# Set the working directory
WORKDIR /app

# Install dependencies
RUN composer install

# Expose port 8000
EXPOSE 8000

# Copy the entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

# Start the Symfony server
ENTRYPOINT ["docker-entrypoint"]
CMD ["symfony", "server:start", "--no-tls"]


