FROM httpd:2.4-alpine

ARG TZ

# Met à jour la liste des paquets
RUN apk update

# Configuration du fuseau horaire sur Paris
ENV TZ=$TZ
RUN apk add --no-cache tzdata && \
    cp /usr/share/zoneinfo/$TZ /etc/localtime && \
    echo $TZ > /etc/timezone

# Installation de supervisor et apache2-utils
RUN apk add --no-cache supervisor

# Ajout de notre propre config Apache
RUN echo "Include conf/extra/docker.conf" >> /usr/local/apache2/conf/httpd.conf
RUN echo "Include conf/extra/security.conf" >> /usr/local/apache2/conf/security.conf

# Configuration de l'application
COPY supervisord.conf /usr/local/supervisord.conf
COPY httpd.conf /usr/local/apache2/conf/extra/docker.conf
COPY security.conf /usr/local/apache2/conf/extra/security.conf

# Entrée de l'application vers le superviseur
CMD ["supervisord", "-c", "/usr/local/supervisord.conf"]
