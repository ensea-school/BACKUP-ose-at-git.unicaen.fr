# dev-infra

## Usage

Dev-infra est un dispositif permettant d'installer sur une machine de développement 
tous les outils nécessaires au bon fonctionnement de OSE.

Il est possible d'en installer certains directement dans OSE, comme maildev, odconv ou postgresql.

Mais si ces services sont amenés à être partagés avec d'autres logiciels ou instances de OSE, 
il est plus pertinent de les installer via DevInfra.

Ainsi, vous n'aurez qu'un seul serveur Postgresql, une seul maildev sur votre machine, etc.

DevInfra fournit aussi un reverse proxy, Caddy, qui vous permettra d'utiliser OSE 
avec une URL "propre" et en HTTPS.


Pour installer DevInfra, vous trouverez plus d'infos ici :

https://git.unicaen.fr/open-source/docker/dev-infra

## Utilisation

Dans ce répertoire de documentation, vous trouverez 2 fichiers pour pouvoir utiliser OSE
avec Dev-Infra :

- [docker-compose.yaml](docker-compose.yaml)
- [Caddyfile](Caddyfile)

Le premier est un exemple de docker-compose.yaml que vous pourrez utiliser.
Fichier à copier/coller à la racine de votre répertoire OSE.

Le second est constitué d'une configuration qu'il vous faudra ajouter au CaddyFile de DevInfra
Pour pouvoir accéder à OSE. Configuration à adapter à vos besoins, bien entendu.