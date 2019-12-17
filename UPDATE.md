# Procédure de mise à jour

## Avant de débuter

[La liste des changements](CHANGELOG.md) recense les changements apportés par les différentes versions de l'application.
Vous êtes invités à consulter cette page, car y sont mentionnées des "notes de mise à jour" par version qu'il
convient de lire attentivement avant de démarrer toute opération.

## Mode maintenance
Placez OSE en mode maintenance. Dans le fichier `config.local.php` :

* `maintenance/modeMaintenance` doit passer à `true`.
* `maintenance/messageInfo` peut être personnalisé pour informer les utilisateurs.

## Mise à jour

* Si les fichiers sources de OSE ont été modifiés manuellement (hors fichier de configuration local), veillez à remettre la copie
de travail dans son état originel au moyen de la commande suivante :
`git reset --hard` (dans le répertoire de l'application).

* Dans le répertoire de l'application, exécutez `./bin/ose update`, puis suivez les instructions.

La base de données sera également mise à jour (structures et données).

Si au niveau base de données vous rencontrez des erreurs, une nouvelle mise à jour de la base de données pourra être lancée
au moyen de la commande `./bin/ose update-bdd`. 

## Passage en production

Sortez du mode maintenance. Dans le fichier `config.local.php` :

* `maintenance/modeMaintenance` doit passer à `false`.

C'est fini!