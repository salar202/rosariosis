# Use the official PHP image as a base image
FROM php:8.0-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    && docker-php-ext-install pdo pdo_mysql

# Configure Nginx
RUN rm /etc/nginx/sites-enabled/default
COPY ./nginx/default /etc/nginx/sites-available/default
RUN ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Configure Supervisor
COPY ./supervisord.conf /etc/supervisor/supervisord.conf

# Copy the application code
COPY . /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Set the proper permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Start Nginx and PHP-FPM via Supervisor
CMD ["/usr/bin/supervisord"]

