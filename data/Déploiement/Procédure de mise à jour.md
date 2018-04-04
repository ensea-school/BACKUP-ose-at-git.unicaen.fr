# Procédure de mise à jour

## Mode maintenance
Placez OSE en mode maintenance. Dans le fichier `config/application.config.php` :

* `maintenance/modeMaintenance` doit passer à `true`.
* `maintenance/messageInfo` peut être personnalisé pour informer les utilisateurs.

## Mise à jour des fichiers

* Dans le répertoire de l'application, exécutez `./bin/ose update`, puis suivez les instructions.

## Mise à jour de la base de données

* Dans le répertoire `data/Mises à jour`, si un fichier `.sql` corresopnd à la nouvelle version installée,
exécutez les requêtes dans SQL Developer pour mettre à jour la base de données.

## Tests

Le fichier `CHANGELOG` recense les changements apportés par les nouvelles versions de l'application.
Vous êtes invité à tester prioritairement ces changements.

## Passage en production

Sortez du mode maintenance. Dans le fichier `config/application.config.php` :

* `maintenance/modeMaintenance` doit passer à `false`.

C'est fini!