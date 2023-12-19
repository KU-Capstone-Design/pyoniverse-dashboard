FROM php:8.3.0-zts-bullseye

WORKDIR /dashboard
COPY . .
RUN apt-get update && apt-get upgrade -y
RUN docker-php-ext-install mysqli
ENTRYPOINT ["php"]
CMD ["-S", "0.0.0.0:8000", "-t", "."]
