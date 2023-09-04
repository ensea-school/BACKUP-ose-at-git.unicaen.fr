###########################################################################################
#
#                               Image pour le dev.
#
#         Montage des sources attendu dans le volume "/app" du container.
#
###########################################################################################

ARG PHP_VERSION

FROM unicaen-dev-php${PHP_VERSION}-apache

ARG APPLICATION_ENV

LABEL maintainer="Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>"

ENV APACHE_CONF_DIR=/etc/apache2 \
    PHP_CONF_DIR=/etc/php/${PHP_VERSION}

## Installation de packages requis.
RUN apt-get update -y
RUN apt-get upgrade -y
RUN apt-get install -y unoconv gawk

# Nettoyage
RUN apt-get autoremove -y && apt-get clean && rm -rf /tmp/* /var/tmp/*

# Symlink apache access and error logs to stdout/stderr so Docker logs shows them
RUN ln -sf /dev/stdout /var/log/apache2/access.log
RUN ln -sf /dev/stdout /var/log/apache2/other_vhosts_access.log
RUN ln -sf /dev/stderr /var/log/apache2/error.log

# Lien pour l'exécutable interne de OSE
RUN ln -sf /app/bin/ose /usr/local/bin/ose
RUN ln -sf /app/bin/ose-test /usr/local/bin/ose-test

# Config PHP.
ADD docker/${APPLICATION_ENV}/php.conf ${PHP_CONF_DIR}/fpm/conf.d/app.ini
ADD docker/${APPLICATION_ENV}/php.conf ${PHP_CONF_DIR}/cli/conf.d/app.ini

# Configuration Apache et FPM
ADD docker/${APPLICATION_ENV}/apache-ports.conf    ${APACHE_CONF_DIR}/ports.conf
ADD docker/${APPLICATION_ENV}/apache-site.conf     ${APACHE_CONF_DIR}/sites-available/app.conf
ADD docker/${APPLICATION_ENV}/fpm/pool.d/app.conf  ${PHP_CONF_DIR}/fpm/pool.d/app.conf

COPY /docker/${APPLICATION_ENV}/entrypoint.d /entrypoint.d/

RUN a2ensite app && \
    service php${PHP_VERSION}-fpm reload