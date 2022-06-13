FROM php:8.1.6-fpm

#Symfony CLI
RUN echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list

#Install composer (accesed from docker container)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update && apt-get install -y \
    curl \ 
    zip \
    unzip \
    libzip-dev \
    vim \
    wget \
    symfony-cli \
    nodejs \
    npm\
    cron

#Configure docker
RUN docker-php-ext-install pdo pdo_mysql 
RUN docker-php-ext-install zip

WORKDIR /var/www

#Change owner of the container document root
RUN chown -R www-data:www-data /var/www

COPY ./app .