# Use official PHP image
FROM php:8.1-cli

# Set working directory
WORKDIR /app

# Install MySQLi extension
RUN docker-php-ext-install mysqli

# Copy application files
COPY . /app

# Expose port 8000
EXPOSE 8000

# Start PHP server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "/app"]
