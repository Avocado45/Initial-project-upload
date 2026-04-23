FROM php:8.3-cli

RUN apt-get update \
    && apt-get install -y libxml2-dev \
    && docker-php-ext-install dom \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY index.php /app/index.php

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "index.php"]