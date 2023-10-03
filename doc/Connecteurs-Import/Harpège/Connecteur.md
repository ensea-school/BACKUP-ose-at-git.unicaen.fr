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
  
## Mise en place du DbLink

Le lien avec Harpège se fait au moyen d'un DbLink que vous devrez créer.
Dans cet exemple, le DbLink s'appellera `harpprod`.


## Création de la vue SRC_HARPEGE_STRUCTURE_CODES

La vue `SRC_HARPEGE_STRUCTURE_CODES` liste les structures Harpège et pour chacune d'entres elles associé sa structure de niveau 2.
Elle ne correspond à aucune table OSE et ne contient aucune donnée à importer. Elle est néanmoins indispensable au bon fonctionnement du connecteur.

Créez la vue [SRC_HARPEGE_STRUCTURE_CODES](SRC_HARPEGE_STRUCTURE_CODES.sql).
    
## Déclaration du connecteur dans OSE  

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


## Import des pays

Les pays sont enregistrés dans la table PAYS.

Si vous venez d'installer OSE, alors l'application est livrée avec un jeu de données par défaut, parmi lesquelles une liste des pays.
Avant d'utiliser votre propre liste issue d'Harpège, vous devez impérativement vider la table PAYS, sans quoi vous vous 
retrouveriez avec des erreurs d'import pour cause de doublons.

```sql
DELETE FROM PAYS;
```

Ensuite, créez la vue source [SRC_PAYS](SRC_PAYS.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).

## Import des départements

Les départements sont enregistrés dans la table DEPARTEMENT.

Comme pour les pays, si vous venez d'installer OSE, alors l'application est livrée avec un jeu de données par défaut, 
parmi lesquelles une liste des départements.
Avant d'utiliser votre propre liste issue d'Harpège, vous devez impérativement vider la table DEPARTEMENT, sans quoi vous vous
retrouveriez avec des erreurs d'import pour cause de doublons.

```sql
DELETE FROM DEPARTEMENT;
```

Ensuite, créez la vue source [SRC_DEPARTEMENT](SRC_DEPARTEMENT.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).


## Import des voiries

Les voiries sont enregistrés dans la table VOIRIE.

Comme pour les pays, si vous venez d'installer OSE, alors l'application est livrée avec un jeu de données par défaut, 
parmi lesquelles une liste des voiries.
Avant d'utiliser votre propre liste issue d'Harpège, vous devez impérativement vider la table VOIRIE, sans quoi vous vous
retrouveriez avec des erreurs d'import pour cause de doublons.

```sql
DELETE FROM VOIRIE;
```

Ensuite, créez la vue source [SRC_VOIRIE](SRC_VOIRIE.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).


## Import des structures

Les structures dans OSE matérialisent des composantes ou des départements. Il n'y a qu'un seul niveau de structure dans OSE.
Les structures portent entres autres l'offre de formation, les intervenants mais aussi les affectations (droits d'accès).

Créez la vue source [SRC_STRUCTURE](SRC_STRUCTURE.sql).

Dans cette vue, on importe les structures Harpège de niveau 2 et la structure Université (UNIV) de niveau 1.

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).

## Import des corps et des grades

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

[Activez-les, puis tentez les synchronisations](../activer-synchroniser.md).

## Import des intervenants

Les intervenants peuvent être indifféremment des permanents ou des vacataires.

Compte tenu de la masse des données et pour des raisons d'optimisation aussi bien que de lisibilité, la vue source va s'appuyer sur une vue matérialisée qui va lui "préparer" le travail.

L'ensemble de la population active dans Harpège (ou plus exactement les individus actifs 6 mois avant leur date de début et 400 jours après leur date de fin d'activité)
se retrouve dans ce connecteur.

Voici la vue matérialisée qui remonte les données d'Harpège :

[MV_INTERVENANT](MV_INTERVENANT.sql)

Les données en sortie sont préparées pour être exploitées par la vue source.

[SRC_INTERVENANT](../Générique/SRC_INTERVENANT.sql)

La vue SRC_INTERVENANT remplit plusieurs rôles :
* Elle récupère les valeurs identifiantes pour les champs faisant références à d'autres tables à l'aide des valeurs (champ z_*) transmises à cet effet par la vue matérialisée
* Elle se charge de ne synchroniser les colonnes STATUT_ID et STRUCTURE_ID que si ces dernières sont synchronisables (les colonnes SYNC_* l'indiquant).
* Si les données personnelles sont saisies, alors le statut de l'intervenant sera celui renseigné par l'intervenant dans son dossier.
* La vue source synchronise les données des deux dernières années. 
* Pour l'année n-1, le statut ainsi que la structure de l'intervenant ne sont pas synchronisés.

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).

### Cas des intervenants ne remontant plus par le connecteur mais ayant des données de saisies dans OSE.

Il se peut que certains intervenants disparaissent d'Harpège, car leur date de fin d'affectation est passée par exemple.
Ceci peut poser problème si ces derniers ont des informations saisies dans OSE.
Pour éviter que la synchronisation ne supprime ces intervenants, il est nécessaire d'ajouter le filtre suivant :

```sql
WHERE import_action <> 'delete' OR (
      (NOT exists(SELECT intervenant_id FROM intervenant_dossier WHERE histo_destruction IS NULL AND intervenant_id = v_diff_intervenant.id))
  AND (NOT exists(SELECT intervenant_id FROM service WHERE histo_destruction IS NULL AND intervenant_id = v_diff_intervenant.id))
)
```
pour la table INTERVENANT.

Filtre à saisir dans Administration / Synchronisation / Tables / Table INTERVENANT / Modification / Champ "Filtre".

Le filtre laisse passer toutes les opérations, sauf la destruction si l'intervenant a un dossier et/ou des services de saisis.
Ces deux tests suffisent généralement, car il s'agit des deux premières étapes du workflow. A adapter le cas échéant.

## Import des affectations de recherche

Les affectations de recherche peuvent être intégrées à OSE.

En voici la vue source.

[SRC_AFFECTATION_RECHERCHE](SRC_AFFECTATION_RECHERCHE.sql)

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).