# Connecteur Harpège

Le connecteur Harpège permet de synchroniser en import :
  * les pays
  * les départements
  * les voiries
  * les structures (composantes)
  * les corps et les grades
  * Les intervenants vacataires et permanents
  * les affectations de recherche

Les vues qui vont sont fournies ci-dessous ne représentent qu'un exemple. Il vous revient de les adapter à vos besoins afin que vous
retrouviez dans OSE les données dont vous avez besoin. 
  
  !! MV_UNICAEN_STRUCTURE_CODES à voir quoi en faire
  
## Étape 1 : Mise en place du DbLink

Le lien avec Harpège se fait au moyen d'un DbLink que vous devrez créer.
Dans cet exemple, le DbLink s'appellera `harpprod`.

    
## Étape 2 : Déclaration du connecteur dans OSE  

OSE doit lister toutes ses sources de données.
Il faut donc y ajouter Harpège : 

```sql
BEGIN
  unicaen_import.add_source('Harpege', 'Harpège');
  commit;
END;
```

La liste des sources de OSE est accessible ici (URL pointant vers l'instance de démonstration de OSE) :
[https://\<votre ose\>/demo/import/sources](https://ose.unicaen.fr/demo/import/sources)


## Étape 3 : Import des pays

Les pays sont enregistrés dans la table PAYS.

Si vous venez d'installer OSE, alors l'application est livrée avec un jeu de données par défaut, parmi lesquelles une liste des pays.
Avant d'utiliser votre propre liste issue d'Harpège, vous devez impérativement vider la table PAYS, sans quoi vous vous 
retrouveriez avec des erreurs d'import pour cause de doublons.

```sql
DELETE FROM PAYS;
```

Ensuite, créez la vue source [SRC_PAYS](SRC_PAYS.sql).


## Étape 4 : Import des départements

Les départements sont enregistrés dans la table DEPARTEMENT.

Comme pour les pays, si vous venez d'installer OSE, alors l'application est livrée avec un jeu de données par défaut, 
parmi lesquelles une liste des départements.
Avant d'utiliser votre propre liste issue d'Harpège, vous devez impérativement vider la table DEPARTEMENT, sans quoi vous vous
retrouveriez avec des erreurs d'import pour cause de doublons.

```sql
DELETE FROM DEPARTEMENT;
```

Ensuite, créez la vue source [SRC_DEPARTEMENT](SRC_DEPARTEMENT.sql).


## Étape 5 : Import des voiries

Les voiries sont enregistrés dans la table VOIRIE.

Comme pour les pays, si vous venez d'installer OSE, alors l'application est livrée avec un jeu de données par défaut, 
parmi lesquelles une liste des voiries.
Avant d'utiliser votre propre liste issue d'Harpège, vous devez impérativement vider la table VOIRIE, sans quoi vous vous
retrouveriez avec des erreurs d'import pour cause de doublons.

```sql
DELETE FROM VOIRIE;
```

Ensuite, créez la vue source [SRC_VOIRIE](SRC_VOIRIE.sql).


## Étape 6 : Import des structures

Les structures dans OSE matérialisent des composantes ou des départements. Il n'y a qu'un seul niveau de structure dans OSE.
Les structures portent entres autres l'offre de formation, les intervenants mais aussi les affectations (droits d'accès).

Créez la vue source [SRC_STRUCTURE](SRC_STRUCTURE.sql).

Dans cette vue, on importe les structures Harpège de niveau 2 et la structure Université (UNIV) de niveau 1.


## Étape 7 : Import des corps et des grades

Pour chaque corps nous pouvons avoir plusieurs grades.

Les corps sont enregistrés dans la table CORPS, les grades dnas la table GRADE.

Comme pour les pays, si vous venez d'installer OSE, alors l'application est livrée avec un jeu de données par défaut, 
parmi lesquelles une liste des corps et une autre des grades.
Avant d'utiliser vos propres listes de corps et de grades issues d'Harpège, vous devez impérativement vider les table GRADE et CORPS, sans quoi vous vous
retrouveriez avec des erreurs d'import pour cause de doublons.


```sql
DELETE FROM GRADE;
DELETE FROM CORPS;
```

Ensuite, créez la vue source [SRC_CORPS](SRC_CORPS.sql), puis la vue source [SRC_GRADE](SRC_GRADE.sql).


## Étape 8 : Import des intervenants

Les intervenants peuvent être indifféremment des permanents ou des vacataires.


-- Liste de tous les intervenants pouvant potentiellement saisir des services dans OSE
-- La table "chercheur" est parcourue car chez nous les comptes d'accès au système d'information sont listés dans cette table.
-- Nous retrouvons donc ici tous les comptes d'accès au système d'information valides hormis des comptes invités pour usages spécifiques
-- car tout le monde peut potentiellement déclarer des services.
-- Dans cette vue, on synchronise toutes les données des intervenants de l'année en cours, et la plupart des données des intervenants de l'année prédédente


