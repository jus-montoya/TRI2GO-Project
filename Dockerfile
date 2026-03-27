FROM php:8.1-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite

# --- ADD THESE 3 LINES BELOW ---
# 1. This "moves" your code from GitHub into the server
COPY . /var/www/html/

# 2. This makes sure the server has permission to read your files
RUN chown -R www-data:www-data /var/www/html

# 3. This tells the container to stay open on Port 80
EXPOSE 80
