---
title: "Procédure de mise à jour de OSE"
author: Laurent Lécluse - DSI - Unicaen
titlepage: true
titlepage-color: 06386e
titlepage-text-color: ffffff
titlepage-rule-color: ffffff
titlepage-rule-height: 1
...

# Procédure de mise à jour

## Mode maintenance
Placez OSE en mode maintenance. Dans le fichier `config.local.php` :

* `maintenance/modeMaintenance` doit passer à `true`.
* `maintenance/messageInfo` peut être personnalisé pour informer les utilisateurs.

## Mise à jour des fichiers

* Si les fichiers sources de OSE ont été modifiés manuellement (hors fichier de configuration local), veillez à remettre la copie
de travail dans son état originel au moyen de la commande suivante :
`git reset --hard` (dans le répertoire de l'application).

* Dans le répertoire de l'application, exécutez `./bin/ose update`, puis suivez les instructions.

## Mise à jour de la base de données

* Dans le répertoire `data/Mises à jour`, si un fichier `.sql` corresopnd à la nouvelle version installée,
exécutez les requêtes dans SQL Developer pour mettre à jour la base de données.

## Tests

Le fichier `data/Déploiement/Changements.pdf` recense les changements apportés par les différentes versions de l'application.
Vous êtes invité à tester prioritairement ces changements.

## Passage en production

Sortez du mode maintenance. Dans le fichier `config.local.php` :

* `maintenance/modeMaintenance` doit passer à `false`.

C'est fini!