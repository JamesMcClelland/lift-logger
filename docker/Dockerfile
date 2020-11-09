FROM php:7.4-cli

RUN apt-get update && docker-php-ext-install pdo_mysql && apt-get -qy install netcat git unzip

WORKDIR /app
COPY . /app

CMD [ "php", "-S", "0.0.0.0:9010" ]