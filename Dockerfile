# Choose base image
FROM php:8.0.5-apache

# Set the main working directory
WORKDIR /var/www/html

# Install required software
RUN apt-get update -y && apt-get install -y openssl zip unzip git libonig-dev libzip-dev
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add php extensions
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install zip
RUN docker-php-ext-install mbstring

# Setup Apache
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
COPY start-apache /usr/local/bin
RUN chmod +x /usr/local/bin/start-apache

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy all your files to the new image
COPY . /var/www/html

# Setup permissions
RUN chown -R www-data:www-data /var/www/html 

# Enable apache modules
RUN a2enmod rewrite \
    && a2enmod deflate \
    && a2enmod expires \
    && a2enmod headers

# Clear the caches etc
RUN php artisan config:clear
RUN php artisan cache:clear
RUN composer dump-autoload
RUN php artisan view:clear
RUN php artisan route:clear
