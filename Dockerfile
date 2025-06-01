FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files first for better layer caching
COPY composer.json composer.lock symfony.lock ./

# Create temporary .env file for composer install ONLY
# These values will be OVERRIDDEN by Railway environment variables
RUN echo "# Temporary .env for build process - values overridden by platform env vars" > .env && \
    echo "APP_ENV=prod" >> .env && \
    echo "APP_SECRET=temp-build-secret-replaced-by-railway-env-vars" >> .env && \
    echo "DATABASE_URL=sqlite:///%kernel.project_dir%/var/data.db" >> .env && \
    echo "MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0" >> .env

# Install dependencies - ignore platform requirements to avoid version conflicts
RUN echo "Force rebuild $(date)"
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --ignore-platform-reqs --no-scripts

# Copy the rest of the application
COPY . .

# Create necessary directories and set permissions
RUN mkdir -p var/cache var/log public/build && \
    chmod -R 777 var && \
    chmod -R 755 public

# Clear the temporary .env file - Railway will provide the real environment variables
RUN rm -f .env

# Clear and warm up cache for production
RUN php bin/console cache:clear --env=prod --no-debug || true

# The port that Railway will use
EXPOSE $PORT

# Start the application
CMD ["sh", "-c", "php -S 0.0.0.0:$PORT -t public"] 