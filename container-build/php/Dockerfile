FROM php:8.0

# Instalando pacotes complementares
RUN apt-get update \
 && apt-get install -y libpq-dev \
 && docker-php-ext-install pdo pdo_pgsql pdo_mysql opcache

# Instalando XDebug
RUN yes | pecl install xdebug \
 && docker-php-ext-enable xdebug

# Limpado cache da imagem
RUN apt-get clean \
 && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Override with custom opcache settings
COPY php.ini $PHP_INI_DIR/conf.d/

WORKDIR /var/www