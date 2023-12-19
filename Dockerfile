FROM php:8.3.0-zts-bullseye

WORKDIR /dashboard
COPY . .
RUN apt-get update && apt-get upgrade -y
ENTRYPOINT ["php"]
CMD ["-S", "0.0.0.0:8000", "-t", "."]
