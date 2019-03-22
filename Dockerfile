###########################################################################################
#
#                               Image pour le dev.
#
#         Montage des sources attendu dans le volume "/app" du container.
#
###########################################################################################

FROM unicaen-dev-php7.0-apache
LABEL maintainer="Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>"

ENV APACHE_CONF_DIR=/etc/apache2 \
    PHP_CONF_DIR=/etc/php/7.0

## Installation de packages requis.
RUN apt-get install -y unoconv

# Nettoyage
RUN apt-get autoremove -y && apt-get clean && rm -rf /tmp/* /var/tmp/*

# Symlink apache access and error logs to stdout/stderr so Docker logs shows them
RUN ln -sf /dev/stdout /var/log/apache2/access.log
RUN ln -sf /dev/stdout /var/log/apache2/other_vhosts_access.log
RUN ln -sf /dev/stderr /var/log/apache2/error.log
RUN ln -sf /var/www/app/bin/ose /usr/local/bin/ose

# Config PHP.
ADD docker/php.conf ${PHP_CONF_DIR}/fpm/conf.d/app.ini

# Configuration Apache et FPM
ADD docker/apache-ports.conf    ${APACHE_CONF_DIR}/ports.conf
ADD docker/apache-site.conf     ${APACHE_CONF_DIR}/sites-available/app.conf
ADD docker/apache-site-ssl.conf ${APACHE_CONF_DIR}/sites-available/app-ssl.conf
ADD docker/fpm/pool.d/app.conf  ${PHP_CONF_DIR}/fpm/pool.d/app.conf

COPY /docker/entrypoint.d/* /entrypoint.d/
ONBUILD COPY /docker/entrypoint.d/* /entrypoint.d/

RUN a2ensite app && \
    service php7.0-fpm reload