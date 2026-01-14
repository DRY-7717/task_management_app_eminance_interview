FROM php:8.3.29-fpm

WORKDIR /var/www/task_management_interview

RUN apt-get update && apt-get install -y \
    build-essential \
    mariadb-client \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd intl mbstring zip opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
    


RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - \
 && apt-get install -y nodejs \
 && npm install -g npm@latest


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


RUN  groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY . /var/www/task_management_interview

COPY --chown=www-data:www-data . /var/www/task_management_interview/

USER www

EXPOSE 9000

CMD ["php-fpm"]

