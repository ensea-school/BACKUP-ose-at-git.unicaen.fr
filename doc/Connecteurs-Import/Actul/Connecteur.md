# Connecteur Actul+

Le connecteur Actul+ permet de synchtoniser dans OSE une offre de formation en provenance d'Actul+

Le connecteur fonctionne de la manière suivante :
* Des tables intermédiaires sont créées dans la base OSE
* Des vues sources spécifiques se basent sur les tables intermédiaires pour "présenter les données" à OSE. 
Un script PHP se connecte à la base de données Actul+, récupère les données nécessaires et les injecte dans les tables intermédiaires
* La synchro est déclenchée, mettant ainsi à jour OSE.



## Configuration

La base de données Actul+ doit être accessible depuis OSE.
Pour cela, vous devez renseigner les paramètres d'accès à la base de données Actul+ dans votre fichier config.local.php.
Vous trouverez un exemple de configuration ici : [config.local.php.default](../../../config.local.php.default), rubrique "actul".



## Installation

Lancez `./bin/ose actul install`.
Cette commande va vous créer des tables tampon et des vues `ACT_%` sur lesquelles s'appuieront les vues sources.



## Vue SRC_HARPEGE_STRUCTURE_CODES

Le connecteur Apogée exploite par défaut la vue `SRC_HARPEGE_STRUCTURE_CODES`, faisant partie du
[connecteur Harpège](../Harpège/Connecteur.md).

Elle liste les structures Harpège et pour chacune d'entres elles associé sa structure de niveau 2.
Elle ne correspond à aucune table OSE et ne contient aucune donnée à importer.
Elle est néanmoins indispensable au bon fonctionnement du connecteur.

Si vous n'utilisez pas le connecteur Harpège, il vous faudra créer une vue similaire et remplacer l'usage
de la vue [SRC_HARPEGE_STRUCTURE_CODES](../Harpège/SRC_HARPEGE_STRUCTURE_CODES.sql) par celle que vous aurez créé dans les vues sources qui l'utilisent.



## Récupération des groupes de types de formation

Les groupes de types de formation sont à récupérer depuis Apogée.

La vue du connecteur Apogée sera donc à utiliser ici.

Créez la vue [SRC_GROUPE_TYPE_FORMATION](../Apogée/SRC_GROUPE_TYPE_FORMATION.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).



## Récupération des groupes de types de formation

Ici aussi, les types de formation sont à récupérer depuis Apogée.

La vue du connecteur Apogée sera donc à utiliser également.

Créez la vue [SRC_TYPE_FORMATION](../Apogée/SRC_TYPE_FORMATION.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).



## Récupération des étapes

