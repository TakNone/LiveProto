FROM php:8.4-cli

ENV DEBIAN_FRONTEND=noninteractive

RUN set -eux; \
	apt-get update; \
	apt-get install -y --no-install-recommends \
	  build-essential autoconf pkg-config ca-certificates gnupg wget curl \
	  git unzip lsb-release apt-transport-https \
	  libzip-dev zlib1g-dev libicu-dev libgmp-dev libcurl4-openssl-dev \
	  libxml2-dev libonig-dev libsqlite3-dev libmagic-dev; \
	rm -rf /var/lib/apt/lists/*

RUN set -eux; \
	docker-php-ext-configure zip; \
	docker-php-ext-install -j"$(nproc)" xml; \
	docker-php-ext-install -j"$(nproc)" gmp; \
	docker-php-ext-install -j"$(nproc)" zip; \
	docker-php-ext-install -j"$(nproc)" sockets; \
	docker-php-ext-install -j"$(nproc)" mbstring; \
	docker-php-ext-install -j"$(nproc)" fileinfo; \
	docker-php-ext-install -j"$(nproc)" curl; \
	docker-php-ext-install -j"$(nproc)" intl; \
	docker-php-ext-install -j"$(nproc)" pdo pdo_sqlite;

RUN set -eux; \
	curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY .github/composer.json /app/
RUN set -eux; \
	composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

COPY . /app

CMD ["php", "-v"]