FROM php:8.1-apache

# Copy virtual host into container
COPY ./docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable rewrite mode
RUN a2enmod rewrite
RUN docker-php-ext-install pdo pdo_mysql

# Install necessary packages
RUN apt-get update && \
    apt-get install -y --no-install-recommends \ 
    libmagickwand-dev \
    libzip-dev \
    wget \
    git \
    unzip \
    && pecl install imagick \
    && docker-php-ext-enable imagick 

# Install PHP Extensions
RUN docker-php-ext-install zip pdo_mysql

# Copy php.ini
COPY ./docker/php.ini /usr/local/etc/php/

# Install composer globally
RUN apt-get update && apt-get install -y wget && \
    wget https://getcomposer.org/installer -O composer-setup.php && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php && \
    apt-get purge -y wget && \
    apt-get autoremove -y && \
    rm -r /var/lib/apt/lists/*

    
# Set the working directory to the application's root
WORKDIR /var/www/grocery

# Copy application code into the container
COPY . /var/www/grocery
    
RUN composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-intl

# Install node
ENV NODE_VERSION=16.13.0
RUN apt-get update && apt-get install -y curl
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
ENV NVM_DIR=/root/.nvm
RUN . "$NVM_DIR/nvm.sh" && nvm install ${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm use v${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm alias default v${NODE_VERSION}
ENV PATH="/root/.nvm/versions/node/v${NODE_VERSION}/bin/:${PATH}"
WORKDIR /var/www/grocery/vue-grocery
RUN node --version
RUN npm --version
RUN npm install

# Change the owner of the container document root
RUN chown -R www-data:www-data /var/www/grocery/

# Expose both ports (80 for internal, 8081 for host)
EXPOSE 80
WORKDIR /var/www/grocery
# Start Apache in foreground
CMD ["apache2-foreground"]