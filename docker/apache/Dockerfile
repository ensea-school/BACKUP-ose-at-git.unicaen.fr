FROM httpd:2.4-alpine

ARG APP_TZ

# Met à jour la liste des paquets
RUN apk update

# Configuration du fuseau horaire sur Paris
ENV TZ=$APP_TZ
RUN apk add --no-cache tzdata && \
    cp /usr/share/zoneinfo/$APP_TZ /etc/localtime && \
    echo $APP_TZ > /etc/timezone

# Installation de supervisor et apache2-utils
RUN apk add --no-cache supervisor

# Ajout de notre propre config Apache
RUN echo "Include conf/extra/docker.conf" >> /usr/local/apache2/conf/httpd.conf

# Configuration de l'application
COPY supervisord.conf /usr/local/supervisord.conf
COPY httpd.conf /usr/local/apache2/conf/extra/docker.conf

# Entrée de l'application vers le superviseur
CMD ["supervisord", "-c", "/usr/local/supervisord.conf"]
