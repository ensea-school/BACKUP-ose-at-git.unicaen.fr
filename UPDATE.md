# Procédure de mise à jour

## Avant de débuter

[La liste des changements](CHANGELOG.md) recense les changements apportés par les différentes versions de l'application.
Vous êtes invités à consulter cette page, car y sont mentionnées des "notes de mise à jour" par version qu'il
convient de lire attentivement avant de démarrer toute opération.

Si vous "sautez" plusieurs versions, attention à bien lire également les notes de mises à jour de **toutes les versions intermédiaires**, car des opérations
manuelles à effectuer peuvent y être consignées. Opérations sans lesquelles pourrait ne plus fonctionner.

## Mode maintenance

Le mode maintenance est utilisé pour couper l'accès à l'application durant les opérations de mise à jour.

Si la mise à jour concerne une ou plusieurs versions mineures, par exemple passage de la version 23.12 à la 23.14, alors il convient d'abord de tester
si l'opération fonctionne d'abord sur votre serveur de pré-production. Si cela se passe bien, vous pourrez mettre à jour votre instance de production 
sans la mettre en mode maintenance, donc sans avoir de coupure de service. Dans tous les autres cas ou bien si vous avez des doutes, le mode maintenance doit être
activé.

Placez OSE en mode maintenance. Dans le fichier `config.local.php` :

* `maintenance/modeMaintenance` doit passer à `true`.
* `maintenance/messageInfo` peut être personnalisé pour informer les utilisateurs.

## Mise à jour

* Dans le répertoire de l'application, exécutez `./bin/ose update`, puis suivez les instructions.

La base de données sera également mise à jour (structures et données).

Si au niveau base de données, vous rencontrez des erreurs, une nouvelle mise à jour de la base de données pourra être lancée
au moyen de la commande `./bin/ose update-bdd`. 

## Passage en production

Sortez du mode maintenance. Dans le fichier `config.local.php` :

* `maintenance/modeMaintenance` doit passer à `false`.

C'est fini!