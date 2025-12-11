FROM php:7.4-apache

# Instalar extensiones PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Crear directorio uploads ANTES de cambiar permisos
RUN mkdir -p /var/www/html/uploads

# Configurar permisos (crear directorio primero)
RUN chmod -R 777 /var/www/html/uploads

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de la aplicación
COPY . .

# Configurar permisos del archivo config
RUN chmod 644 config.inc && \
    chown -R www-data:www-data /var/www/html

# Exponer puerto
EXPOSE 80

CMD ["apache2-foreground"]
