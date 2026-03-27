FROM php:8.1-apache
# Install MySQL support
RUN docker-php-ext-install mysqli pdo pdo_mysql
# Move your code into the server
COPY . /var/www/html/
# Set permissions
RUN chown -R www-data:www-data /var/www/html
EXPOSE 80
