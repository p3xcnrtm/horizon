# Use official PHP + Apache image
FROM php:8.2-apache

# Enable Apache rewrite and install mysqli + pdo_mysql
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo_mysql

# Enable Apache mod_rewrite (if you want pretty URLs later)
RUN a2enmod rewrite

# Copy project files into Apache web root
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Expose port
EXPOSE 80
