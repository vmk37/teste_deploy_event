FROM php:8.2-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    zip \
    && docker-php-ext-configure gd --with-fpm --with-jpeg --with-freetype \
    && docker-php-ext-install gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar Node.js e npm
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs

# Definir o diretório de trabalho
WORKDIR /var/www

# Copiar os arquivos do projeto
COPY . .

# Instalar dependências do Composer
RUN composer install --optimize-autoloader --no-dev

# Instalar dependências do Node.js e compilar assets
RUN npm install
RUN npm run build

# Configurar permissões
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage

# Expor a porta
EXPOSE 9000

# Comando para iniciar o PHP-FPM
CMD ["php-fpm"]