FROM php:7.4-apache

# Usar mirror más confiable y reducir tamaño
RUN echo 'deb http://deb.debian.org/debian bullseye main' > /etc/apt/sources.list && \
    echo 'deb http://deb.debian.org/debian bullseye-updates main' >> /etc/apt/sources.list && \
    echo 'deb http://security.debian.org/debian-security bullseye-security main' >> /etc/apt/sources.list

# Instalar extensiones PHP primero
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Instalar Git con reintentos y sin documentación
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    git \
    ca-certificates && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de la aplicación
COPY . .

# Configurar permisos (vulnerables intencionalmente)
RUN chmod -R 777 /var/www/html/uploads && \
    chmod 644 config.inc && \
    chown -R www-data:www-data /var/www/html

# Crear directorio para sesiones PHP
RUN mkdir -p /var/lib/php/sessions && \
    chown -R www-data:www-data /var/lib/php/sessions

# Exponer puerto
EXPOSE 80

CMD ["apache2-foreground"]
