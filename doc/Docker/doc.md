# Docker

OSE peut fonctionner avec Docker.

La configuration Docker est pour le moment réservés aux environnements de développement.

Mais ses paramétrages ont été en partie adaptés pour un usage sur serveur de préprod.

Le fichier docker-compose.yaml n'est pas fourni. Seuls des exemples de configuration 
vous sont proposés.

Le principe est le suivant :
- Les besoins OSE sont exprimés en config Docker dans le répertoire [docker](../../docker).
- Le fichier ```docker-compose.yaml``` est considéré comme un paramétrage qui vous permettra 
de configurer l'application selon vos besoins.

Dans le fichier ```docker-compose.yaml```, à placer à la racine du répertoire OSE, vous devrez :
- déclarer quels services de OSe vous utiliserez (services disponibles dans [répertoire docker](../../docker));
- gérer vous-même les ouvertures de ports ;
- gérer vous-même les aspects réseau.


En mode développement, je conseille d'utiliser dev-infra, bien que ce dernier ne soit pas obligatoire.

[Documentation OSE pour DevInfra](dev-infra/dev-infra.md)

Mais vous pourrez tout aussi bien vous en passer, en copiant-collant 
[docker-compose.yaml.example](../../docker-compose.yaml.example)
vers
```../../docker-compose.yaml```
dans votre répertoire OSE.

Dans tous les cas, la fonfiguration sera à adapter à vos besoins.