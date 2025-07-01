FROM php:8.2-apache

# Mettre à jour le système et installer pdo_mysql
RUN apt-get update && apt-get upgrade -y && \
    docker-php-ext-install pdo_mysql && \
    apt-get clean && rm -rf /var/lib/apt/lists/*
# Activer le module Apache rewrite
RUN a2enmod rewrite

