# Connecteur Harpège

Le connecteur Harpège permet de synchroniser en import :
  * les pays
  * les départements
  * les structures (composantes)
  * les corps
  * les grades
  * Les intervenants vacataires et permanents
  * les affectations de recherche

  
## Étape 1 : Mise en place du DbLink

Le lien avec Harpège se fait au moyen d'un DbLink que vous devrez créer.
Dans cet exemple, le DbLink s'appellera `harpprod`.

    
## Étape 2 : Déclaration du connecteur dans OSE  

OSE doit lister toutes ses sources de données.
Il faut donc y ajouter Harpège : 

```sql
BEGIN
  unicaen_import.add_source('Harpege', 'Harpège');
END;
```

La liste des sources de OSE est accessible ici (URL pointant vers l'instance de démonstration de OSE) :
[https://\<votre ose\>/demo/import/sources](https://ose.unicaen.fr/demo/import/sources)


## Étape 3 : Import des pays

Les pays sont enregistrés dans la table PAYS.

Si vous venez d'installer OSE, alors l'application est livrée avec un jeu de données par défaut, parmi lesquelles une liste des pays.
Avant d'utiliser votre propre liste de pays issue d'Harpège, vous devez impérativement vider la table PAYS, sans quoi vous vous 
retrouveriez avec des erreurs d'import pour cause de doublons.

```sql
DELETE FROM PAYS;
```

Ensuite, créez la vue source [SRC_PAYS](SRC_PAYS.sql).
