# Connecteur Octopus

Le connecteur Octopus permet de synchroniser en import :

* les pays
* les départements
* les structures (composantes)
* les corps et les grades
* Les intervenants vacataires et permanents

## Mise en place du DbLink

Le lien avec Octopus se fait au moyen d'un DbLink que vous devrez créer. Dans cet exemple, le DbLink s'appellera `octoprod`.

## Déclaration du connecteur dans OSE

OSE doit lister toutes ses sources de données. Il faut donc y ajouter Octopus :

```sql
BEGIN
  unicaen_import.add_source
('Octopus', 'Octopus');
commit;
END;
```

La liste des sources de OSE est accessible ici (URL pointant vers l'instance de démonstration de OSE) :
[https://\<votre ose\>/demo/import/sources](https://ose.unicaen.fr/demo/import/sources)

## Import des pays

Les pays sont enregistrés dans la table PAYS.

Si vous venez d'installer OSE, alors l'application est livrée avec un jeu de données par défaut, parmi lesquelles une liste
des pays. Avant d'utiliser votre propre liste issue d'Octopus, vous devez impérativement vider la table PAYS, sans quoi vous
vous retrouveriez avec des erreurs d'import pour cause de doublons.

```sql
DELETE
FROM PAYS;
```

Ensuite, créez la vue source [SRC_PAYS](SRC_PAYS.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).

## Import des départements

Les départements sont enregistrés dans la table DEPARTEMENT.

Comme pour les pays, si vous venez d'installer OSE, alors l'application est livrée avec un jeu de données par défaut, parmi
lesquelles une liste des départements. Avant d'utiliser votre propre liste issue d'Octopus, vous devez impérativement vider la
table DEPARTEMENT, sans quoi vous vous retrouveriez avec des erreurs d'import pour cause de doublons.

```sql
DELETE
FROM DEPARTEMENT;
```

Ensuite, créez la vue source [SRC_DEPARTEMENT](SRC_DEPARTEMENT.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).

## Import des structures

Les structures dans OSE matérialisent des composantes ou des départements. Il n'y a qu'un seul niveau de structure dans OSE.
Les structures portent entres autres l'offre de formation, les intervenants mais aussi les affectations (droits d'accès).

Créez la vue source [SRC_STRUCTURE](SRC_STRUCTURE.sql).

Dans cette vue, on importe les structures Octopus de niveau 2 et la structure Université (UNIV) de niveau 1.

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).

## Import des corps et des grades

Pour chaque corps nous pouvons avoir plusieurs grades.

Les corps sont enregistrés dans la table CORPS, les grades dnas la table GRADE.

Comme pour les pays, si vous venez d'installer OSE, alors l'application est livrée avec un jeu de données par défaut, parmi
lesquelles une liste des corps et une autre des grades. Avant d'utiliser vos propres listes de corps et de grades issues d'
Octopus, vous devez impérativement vider les table GRADE et CORPS, sans quoi vous vous retrouveriez avec des erreurs d'import
pour cause de doublons.

```sql
DELETE
FROM GRADE;
DELETE
FROM CORPS;
```

Ensuite, créez la vue source [SRC_CORPS](SRC_CORPS.sql), puis la vue source [SRC_GRADE](SRC_GRADE.sql).

[Activez-les, puis tentez les synchronisations](../activer-synchroniser.md).

## Import des intervenants

Les intervenants peuvent être indifféremment des permanents ou des vacataires.

Compte tenu de la masse des données et pour des raisons d'optimisation aussi bien que de lisibilité, la vue source va
s'appuyer sur une vue matérialisée qui va lui "préparer" le travail.

L'ensemble de la population active dans Octopus (ou plus exactement les individus actifs 6 mois avant leur date de début et
400 jours après leur date de fin d'activité)
se retrouve dans ce connecteur.

Voici la vue matérialisée qui remonte les données d'Octopus :

[MV_INTERVENANT](MV_INTERVENANT.sql)

Les données en sortie sont préparées pour être exploitées par la vue source.

[SRC_INTERVENANT](SRC_INTERVENANT.sql)

La vue SRC_INTERVENANT remplit plusieurs rôles :

* Elle récupère les valeurs identifiantes pour les champs faisant références à d'autres tables à l'aide des valeurs (champs
  z_*) transmises à cet effet par la vue matérialisée
