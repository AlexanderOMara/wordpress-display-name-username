FROM php:7.4.2-apache

RUN apt-get update && \
	apt-get install -y  --no-install-recommends \
		sudo \
		nano \
		unzip \
		git \
		ssl-cert \
		mariadb-client \
		iputils-ping \
		zlib1g-dev \
		libpng-dev \
		libjpeg-dev \
		libfreetype6-dev \
		libzip-dev \
	&& \
	rm -r /var/lib/apt/lists/* && \
	docker-php-ext-configure gd --with-freetype --with-jpeg && \
	docker-php-ext-install -j "$(nproc)" \
		bcmath \
		exif \
		gd \
		mysqli \
		opcache \
		zip \
	&& \
	a2enmod rewrite && \
	a2enmod ssl && \
	a2ensite default-ssl && \
	curl -L -s \
		-o /usr/local/bin/wp \
		https://github.com/wp-cli/wp-cli/releases/download/v2.4.0/wp-cli-2.4.0.phar \
	&& \
	chmod +x /usr/local/bin/wp

COPY entrypoint entrypoint-www-data /usr/local/bin/

ENTRYPOINT ["entrypoint"]

EXPOSE 80
EXPOSE 443
