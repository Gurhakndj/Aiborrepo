# Use stable Apache + PHP image
FROM php:8.2-apache

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite

# Enable Apache rewrite (important for PHP routing)
RUN a2enmod rewrite

# Copy your project files into Apache web root
COPY . /var/www/html/

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose default web port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]