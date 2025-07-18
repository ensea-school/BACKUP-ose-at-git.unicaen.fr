FROM php:8.4-fpm-alpine

ARG TZ
ARG APP_ENV
ARG HTTP_PROXY
ARG HTTPS_PROXY
ARG NO_PROXY

ENV TZ=$TZ
ENV HTTP_PROXY=$HTTP_PROXY
ENV HTTPS_PROXY=$HTTPS_PROXY
ENV NO_PROXY=$NO_PROXY


# Met à jour la liste des paquets
RUN apk update
RUN apk add --no-cache bash git

# Configuration du fuseau horaire sur Paris
RUN apk add --no-cache tzdata && \
    cp /usr/share/zoneinfo/$TZ /etc/localtime && \
    echo $TZ > /etc/timezone

# Lien pour l'exécutable interne de OSE
RUN ln -sf /var/www/html/bin/ose /usr/local/bin/ose
RUN ln -sf /var/www/html/bin/ose-test /usr/local/bin/ose-test
RUN ln -sf /var/www/html/bin/ose-code /usr/local/bin/ose-code

# Récupére install-php-extensions depuis GitHub
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions \
		@composer \
		apcu \
		intl \
		opcache \
		zip \
        pdo_pgsql \
        soap \
        bcmath \
        curl \
        gettext \
        oci8 \
        gd \
        ldap \
        mbstring \
        readline \
        intl \
        iconv \
	;

# Xdebug optionnel
RUN if [ "$APP_ENV" = "dev" ]; then \
      install-php-extensions xdebug; \
fi

# Copier configs PHP
COPY php.ini /usr/local/etc/php/php.ini
COPY fpm.conf /usr/local/etc/php-fpm.d/www.conf

# Fournit un script d'entrée pour lancer PHP-FPM
COPY --chmod=755 docker-entrypoint.sh /usr/local/bin/docker-entrypoint

ENTRYPOINT ["/usr/local/bin/docker-entrypoint"]
CMD ["php-fpm"]