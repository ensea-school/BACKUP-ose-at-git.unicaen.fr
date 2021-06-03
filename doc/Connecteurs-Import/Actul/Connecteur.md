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

## Mise en place des vues sources

Vous devez ensuite mettre en place vos vues sources. Cette opération n'est pas automatique, car il vous revient de les adapter à vos besoin
si nécessaire.

### Récupération des structures & correspondance avec les composantes

Vous devez récupérer une liste des structures, car Actul s'appuie sur les composantes d'Apogée.

A vous, donc, de créer votre propre vue SRC_STRUCTURE.

Pour chaque composante Apogée, nous avons besoin de récupérer le code de la structure de niveau 2 correspondant.

A Caen, nous utilisons la vue V_UNICAEN_STRUCTURE_CORRESP, elle-même basée sur des tables qui nous sont spécifiques.
Cette vue renvoie deux colonnes :
 - COD_CMP : Code Apogée de la composante
 - C_STRUCTURE_N2 : Code de structure de niveau 2

La voici à titre d'exemple :
```sql
CREATE OR REPLACE FORCE VIEW V_UNICAEN_STRUCTURE_CORRESP AS  
SELECT 
  c.cod_cmp,
  vs2.code c_structure_n2
FROM 
  ucbn_composante_ldap@apoprod c
  JOIN octo.v_structure@octoprod vs ON vs.code = c.cod_str
  JOIN octo.v_structure@octoprod vs2 ON vs2.id = vs.NIV2_ID
```

A vous, donc, de développer votre propre vue répondant aux mêmes critères.

Le connecteur Apogée exploite par défaut la vue `SRC_HARPEGE_STRUCTURE_CODES`, faisant partie du
[connecteur Harpège](../Harpège/Connecteur.md).

Elle liste les structures Harpège et pour chacune d'entres elles associé sa structure de niveau 2.
Elle ne correspond à aucune table OSE et ne contient aucune donnée à importer.
Elle est néanmoins indispensable au bon fonctionnement du connecteur.

Si vous n'utilisez pas le connecteur Harpège, il vous faudra créer une vue similaire et remplacer l'usage
de la vue [SRC_HARPEGE_STRUCTURE_CODES](../Harpège/SRC_HARPEGE_STRUCTURE_CODES.sql) par celle que vous aurez créé dans les vues sources qui l'utilisent.


### 





### Récupération des groupes de types de formation

Les groupes de types de formation sont à récupérer depuis Apogée.

La vue du connecteur Apogée sera donc à utiliser ici.

Créez la vue [SRC_GROUPE_TYPE_FORMATION](../Apogée/SRC_GROUPE_TYPE_FORMATION.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).



### Récupération des groupes de types de formation

Ici aussi, les types de formation sont à récupérer depuis Apogée.

La vue du connecteur Apogée sera donc à utiliser également.

Créez la vue [SRC_TYPE_FORMATION](../Apogée/SRC_TYPE_FORMATION.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).



### Récupération des étapes

Créez la vue [SRC_ETAPE](SRC_ETAPE.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).



### Récupération des éléments pédagogiques

Créez la vue [SRC_ELEMENT_PEDAGOGIQUE](SRC_ELEMENT_PEDAGOGIQUE.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).



### Récupération des chemins pédagogiques

Lien entre les étapes et les éléments pédagogiques.
Utile pour visualiser l'offre de formation dans le menu homonyme.

Créez la vue [SRC_CHEMIN_PEDAGOGIQUE](SRC_CHEMIN_PEDAGOGIQUE.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).



### Récupération des volumes horaires d'enseignement

Récupère les heures et groupes calculé par Actul.
Cette information est nécessaire, car elle permet de savoir si les éléments pédagogiques ont dsu CM, du TD, du TP, etc.

Créez la vue [SRC_VOLUME_HORAIRE_ENS](SRC_VOLUME_HORAIRE_ENS.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).



### Récupération des noeuds

Les noeuds sont tous les constituants d'une formation :
- les étapes
- les éléments pédagogiques
- tous les niveaux intermédiaires (semestres, UE, listes, etc.)

Créez la vue [SRC_NOEUD](SRC_NOEUD.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).



### Récupération des liens

Les liens font la correspondance entre les noeuds.

Créez la vue [SRC_LIEN](SRC_LIEN.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).



### Récupération des paramétrages de noeuds par scénarios

Aucune information ne transite ici. On a seulement besoin de cela pour pouvoir synchroniser les effectifs par étape par scénario.

Créez la vue [SRC_SCENARIO_NOEUD](SRC_SCENARIO_NOEUD.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).



### Récupération des effectifs par étape par scénario

Initialise les effectifs FI/FA/FC par étape.
Actul+ ne permet pas de panacher entre ces trois types d'heures.
Les effectifs sont donc positionnés intégralement en FI, à défaut en FC, et à défaut en FA.

Créez la vue [SRC_SCENARIO_NOEUD_EFFECTIF](SRC_SCENARIO_NOEUD_EFFECTIF.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).



### Récupération des paramétrages de liens par scénarios

Concerne les choix minimum / maximum (entre 1 et 1 UE à choisir parmis la liste ci-dessous, etc.).

Créez la vue [SRC_SCENARIO_LIEN](SRC_SCENARIO_LIEN.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).



