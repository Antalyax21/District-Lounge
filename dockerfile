FROM php:8.2-apache

# Mise à jour + installation de PDO MySQL
RUN apt-get update && apt-get upgrade -y && \
    docker-php-ext-install pdo_mysql && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Active mod_rewrite
RUN a2enmod rewrite

# Donne les bons droits à Apache pour /var/www/html
RUN echo '<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/docker-override.conf \
    && a2enconf docker-override.conf
