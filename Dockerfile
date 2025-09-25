# Use official PHP + Apache image
FROM php:8.2-apache

# Enable Apache mod_rewrite (for pretty URLs if needed)
RUN a2enmod rewrite

# Copy project files into Apache web root
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Expose port
EXPOSE 80
