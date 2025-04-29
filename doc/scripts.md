# Créer ses propres scripts en s'appuyant sur l'application

OSE propose un mécanisme qui permet de créer ses propres scripts PHP, ayant accès aux ressources de l'application.

Vous pourrez ainsi :
- accéder en direct à la base de données
- ainsi qu'à tous les services internes de l'application (workflow, etc.)

Vos scripts PHP devront être situés où vous voulez sur votre serveur, sauf un autre répertoire que celui de l'application où c'est interdit sous peine de bloquer les mises à jour futures de l'application.

Vous trouverez ci-dessous quelques exemples pouvant vous inspirer.
Dans ces exemples, nous supposerons que OSE est installé dans le répertoire /var/www/html de votre serveur.
Si ce n'est pas le cas, alors vous devrez remplacer dans les exemples ci-dessous /var/www/html par le répertoire OSE.

## Exemple 1 : requête simple montrant l'accès à la base de données

```php
<?php

// Récupération du container. Ce dernier permet d'accéder à tous les services de l'application
$container = require '/var/www/html/bin/loader.php';

// Récupération de la base de données à l'aide du container
// BddAdmin permet de faire toutes sortes d'opérations sur la base de données
// Documentation ici de BddAdmin : https://git.unicaen.fr/lib/unicaen/bddadmin
$bdd = $container->get(Unicaen\BddAdmin\Bdd::class);

// Lancement d'une requête SELECT, récupération du résultat et affichage
$res = $bdd->select("SELECT * FROM annee WHERE id = 2024");

var_dump($res);
```

Le résultat, après exécution de votre script en ligne de commande, sera :

```php
array(1) {
  [0] =>
  array(5) {
    'ID' =>
    string(4) "2024"
    'LIBELLE' =>
    string(9) "2024/2025"
    'DATE_DEBUT' =>
    string(19) "2024-09-01 00:00:00"
    'DATE_FIN' =>
    string(19) "2025-08-31 00:00:00"
    'ACTIVE' =>
    string(1) "1"
  }
}
```



## Exemple 2 : Re-calcul de la feuille de route d'un intervenant

```php
<?php

// Récupération du container. Ce dernier permet d'accéder à tous les services de l'application
$container = require '/var/www/html/bin/loader.php';

// Récupération de l'entityManager Doctrine
/** @var $entityManager Doctrine\ORM\EntityManager */
$entityManager = $container->get(Doctrine\ORM\EntityManager::class);


$intervenantId = 1138871;

// Récupération de l'entité de l'intervenant
/** @var Intervenant\Entity\Db\Intervenant $intervenant */
$intervenant = $entityManager->find(Intervenant\Entity\Db\Intervenant::class, $intervenantId);

/** @var \Workflow\Service\WorkflowService $serviceWorkflow */
$serviceWorkflow = $container->get(\Workflow\Service\WorkflowService::class);

// Calcul de la feuille de route complète de l'intervenant
// [] = pas de tableau de bord listé, donc tout est recalculé
$serviceWorkflow->calculerTableauxBord([], $intervenant);
```

Le script mettra quelques secondes à s'exécuter.