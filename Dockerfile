# Use official PHP CLI image
FROM php:8.2-cli

# Set working directory inside the container
WORKDIR /app

# Copy all files from repo into container
COPY . /app

# Expose a port (Render uses 10000 by default)
EXPOSE 10000

# Start PHP built-in server
CMD ["php", "-S", "0.0.0.0:10000", "index.php"]
