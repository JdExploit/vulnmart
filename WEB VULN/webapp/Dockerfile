FROM php:7.4-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN apt-get update && apt-get install -y git && rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de la aplicación
COPY . .

# Configurar permisos vulnerables
RUN chmod -R 777 /var/www/html/uploads
RUN chmod 644 config.inc

# Exponer puerto
EXPOSE 80

CMD ["apache2-foreground"]