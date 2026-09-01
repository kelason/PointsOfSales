FROM php:8.1-apache

# Copy virtual host into container
COPY ./docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable rewrite mode
RUN a2enmod rewrite

# Install necessary packages and PHP extensions
RUN apt-get update && \
    apt-get install -y --no-install-recommends \ 
    libmagickwand-dev \
    libzip-dev \
    git \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copy php.ini
COPY ./docker/php.ini /usr/local/etc/php/

# Install composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory to the application's root
WORKDIR /var/www/grocery

# Copy application code into the container
COPY . /var/www/grocery

RUN composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-intl

# Install Node.js and NPM (using NodeSource for cleaner installation)
RUN apt-get update && apt-get install -y curl \
    && curl -fsSL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs

# Install frontend dependencies and build production assets
WORKDIR /var/www/grocery/vue-grocery
RUN npm install
RUN npm run build && cp -r dist/* ../public/

# Revert working directory
WORKDIR /var/www/grocery

# Change the owner of the container document root
RUN chown -R www-data:www-data /var/www/grocery/

EXPOSE 80
CMD ["apache2-foreground"]