# Connecteur Apogée

Le connecteur Apogée permet de synchroniser en import :
  * les étapes (formations) réparties en types de formation
  * les éléments pédagogiques ainsi que les chemins pédagogiques (liens étapes / éléments)
  * les volumes horaires d'enseignement, c'est-à-dire les charges d'enseignements renseignées dans Apogée
  * Les effectifs, par élément pédagogique et bientôt par étape
  * Les noeuds, liens et paramétrages par scénario, utile pour l'exploitation du module Charges de OSE

Les vues qui vont sont fournies ci-dessous ne représentent qu'un exemple. Il vous revient de les adapter à votre contexte afin que vous
retrouviez dans OSE les données dont vous avez besoin. 

Le connecteur Apogée est découpé en deux parties.
La première s'installe directement sur Apogée.
La seconde exploite la partie Apogée du connecteur et fournie les vues sources nécessaires pour les opérations de synchronisation. 


## Dernières modifications intervenues

### 22/07/2025

- Modifications du connecteur côté Apogée
  - Suppression des tables tampon ose_groupe_type_formation et ose_type_formation
  - modification requête alimentation de ose_etape
- Suppression de la vue source src_groupe_type_formation : les groupes de type de formation sont maintenant gérés directement dans OSE
- Modification de src_type_formation : la vue va puiser ses données dans la table Apogée `typ_diplome`
- Modification de src_etape : la vue puise dans la table OSE des groupes de type de formation pour détecter si le niveau est pertinent ou non


## Première partie : installation de la partie Apogée du connecteur
[Partie Apogée du connecteur](Apogee-OSE-lisezMoi.md)
  
## Mise en place du DbLink

Le lien avec Apogée se fait au moyen d'un DbLink que vous devrez créer.
Dans cet exemple, le DbLink s'appellera `apoprod`.

## Déclaration du connecteur dans OSE  

OSE doit lister toutes ses sources de données.
Il faut donc y ajouter Apogée : 

```sql
BEGIN
  unicaen_import.add_source('Apogee', 'Apogée');
  commit;
END;
```

## Vue SRC_HARPEGE_STRUCTURE_CODES

Le connecteur Apogée exploite par défaut la vue `SRC_HARPEGE_STRUCTURE_CODES`, faisant partie du
[connecteur Harpège](../Harpège/Connecteur.md). 

Elle liste les structures Harpège et pour chacune d'entres elles associé sa structure de niveau 2.
Elle ne correspond à aucune table OSE et ne contient aucune donnée à importer. 
Elle est néanmoins indispensable au bon fonctionnement du connecteur.

Si vous n'utilisez pas le connecteur Harpège, il vous faudra créer une vue similaire et remplacer l'usage
de la vue [SRC_HARPEGE_STRUCTURE_CODES](../Harpège/SRC_HARPEGE_STRUCTURE_CODES.sql) par celle que vous aurez créé dans les vues sources qui l'utilisent.


## Récupération des établissements

La nomenclature des établissement est récupérée d'Apogée.

Si vous venez d'installer OSE, alors l'application est livrée avec un jeu de données par défaut, parmi lesquelles une liste des établissements.
Avant d'utiliser votre propre liste issue d'Apogée, vous devez impérativement vider la table ETABLISSEMENT, sans quoi vous vous 
retrouveriez avec des erreurs d'import pour cause de doublons.

```sql
DELETE FROM ETABLISSEMENT;
```

Créez ensuite la vue [SRC_ETABLISSEMENT](SRC_ETABLISSEMENT.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).


## Récupération des types de formation

Créez la vue [SRC_TYPE_FORMATION](SRC_TYPE_FORMATION.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).

## Récupération des étapes

Créez la vue [SRC_ETAPE](SRC_ETAPE.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).

## Récupération des éléments pédagogiques

Créez la vue [SRC_ELEMENT_PEDAGOGIQUE](SRC_ELEMENT_PEDAGOGIQUE.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).

## Récupération des chemins pédagogiques

Créez la vue [SRC_CHEMIN_PEDAGOGIQUE](SRC_CHEMIN_PEDAGOGIQUE.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).

## Récupération des volumes horaires d'enseignement

Créez la vue [SRC_VOLUME_HORAIRE_ENS](SRC_VOLUME_HORAIRE_ENS.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).

## Récupération des taux de mixité par élément pédagogique

Pour rappel, les taux de mixité permettent de savoir quel pourcentage des effectifs étudiants est en FI, en FC et en FA.
Dans l'exemple proposé ici, les taux de mixité sont calculés à partir des effectifs 
de l'année précédente et au bout de deux mois, ce sont les effectifs de l'année en  cours qui sont utilisés.
Nous avons fait ce choix, car des taux de mixité doivent être aussi stables que possibles au cours d'une année : les mises en paiement en dépendent.

Vous trouverez [ici une documentation détaillée](../taux-repartition.md) sur le fonctionnement des taux de mixité.

Créez la vue [SRC_ELEMENT_TAUX_REGIMES](SRC_ELEMENT_TAUX_REGIMES.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).

## Récupération des effectifs

Créez la vue [SRC_EFFECTIFS](SRC_EFFECTIFS.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).

## Récupération des effectifs par étape

Créez la vue [SRC_EFFECTIFS_ETAPE](SRC_EFFECTIFS_ETAPE.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).

## Récupération des noeuds

La table NOEUD, ainsi que les deux tables suivantes, doit être peuplée si vous utilisez le module
charges de OSE. Dans le cas contraire, il vous est inutile de vous en occuper.

Créez la vue [SRC_NOEUD](SRC_NOEUD.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).

## Récupération des liens

Créez la vue [SRC_LIEN](SRC_LIEN.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).

## Récupération des paramétrages par scénario pour les liens

Créez la vue [SRC_SCENARIO_LIEN](SRC_SCENARIO_LIEN.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).

## Récupération des paramétrages par scénarios pour les noeuds

Créez la vue [SRC_SCENARIO_NOEUD](SRC_SCENARIO_NOEUD.sql).

Cette vue ne peuple que les noeuds correspondant aux étapes.
Il sont injectés sans aucun paramètre.
L'objectif est de pouvoir s'appuyer dessus pour injecter plus tard les effectifs.

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).

## Récupération des paramétrages des effectifs par scénarios pour les noeuds d'étapes

Créez la vue [SRC_SCENARIO_NOEUD_EFFECTIF](SRC_SCENARIO_NOEUD_EFFECTIF.sql).

Les effectifs ne sont pas mis à jour si un quelqu'un les a modifié manuellement dans OSE.
La synchronisation ne modifie jamais des données saisies à la main.

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).