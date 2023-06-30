FROM php:latest

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    mariadb-client

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . /var/www/html

RUN composer install

ENV MYSQL_HOST=database \
    MYSQL_PORT=3306 \
    MYSQL_DATABASE=your_database_name \
    MYSQL_USER=your_mysql_user \
    MYSQL_PASSWORD=your_mysql_password

ENV REDIS_HOST=redis \
    REDIS_PORT=6379

CMD php artisan serve --host=0.0.0.0 --port=80
