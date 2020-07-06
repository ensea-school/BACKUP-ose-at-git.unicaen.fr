# Connecteur Sifac

Le connecteur Sifac permet de synchroniser en import :
  * les domaines fonctionnels
  * les centres de coûts et les EOTP
  * les relations entre les centres de coûts et les structures

Les vues qui vont sont fournies ci-dessous ne représentent qu'un exemple. Il vous revient de les adapter à votre contexte afin que vous
retrouviez dans OSE les données dont vous avez besoin. 


  
## Mise en place du DbLink

Le lien avec Sifac se fait au moyen d'un DbLink que vous devrez créer.
Dans cet exemple, le DbLink s'appellera `sifacp`.



## Déclaration du connecteur dans OSE  

OSE doit lister toutes ses sources de données.
Il faut donc y ajouter Harpège : 

```sql
BEGIN
  unicaen_import.add_source('SIFAC', 'SIFAC');
  commit;
END;
```


## Récupération des domaines fonctionnels

La liste des domaines fonctionnels est écrite directement dans la requête.
La vue source ne sert ici qu'à en récupérer les libellés de SIFAC.

[SRC_DOMAINE_FONCTIONNEL](SRC_DOMAINE_FONCTIONNEL.sql).



## Import des centres de coûts et des EOTP

Dans OSE, un EOTP est considéré comme un "sous-centre de coûts".
A un EOTP correspond un unique centre de coûts, son parent.
A un centre de coûts peut correspondre de 0 à n EOTP.
Ces deux types de données sont importés dans une même vue.

A chaque centre de coûts sont associés :
* Un type d'activité (pilotage, enseignement ou accueil)
* Un type de ressources (Paie état ou ressources propres)

Ces données sont déduites de l'analyse du code du centre de coûts.
Ces derniers sont en effet nommés en respectant certaines conventions spécifiques à l'Université de Caen.
Vous devrez donc adapter la vue ci-dessous à vos propres conventions.
Du code du centre de coûts nous déduisons également l'unité budgétaire. Cette information nous permettra ensuite
de savoir à quelle composante il peut être associé.

Créez la vue [SRC_CENTRE_COUT](SRC_CENTRE_COUT.sql). 


## Import de la relation centres de coûts / structures

Une stucture peut avoir plusieurs centres de coûts.
Un centre de coûts peut être partagé par plusieurs structures.

Avant de pouvoir lier les centres de coûts aux structures, il est nécessaire d'établir la correspondance
entre les unités budgétaires SIFAC et les codes des structures que nous utilisons.

Cette information n'étant pas présente dans le système d'information de l'Université de Caen, nous
avons dû créer une table qui la porte.
En voici la définition à titre d'exemple. A vous d'adapter ce dispositif à votre contexte. 

Créez et peuplez la table [UNICAEN_CORRESP_STRUCTURE_CC](UNICAEN_CORRESP_STRUCTURE_CC.sql).

Ensuite, la vue Import ci-dessous va se baser sur cette table.
Notez que dans cette vue import, la source de données n'est pas Sifac puisque l'information n'y est pas présente, mais "Calcul".
"Calcul" est utilisé par convention pour désigner des données calculées à partir d'autres données présentes dans OSE.
Source "Calcul" à bien distinguer de la source "OSE", cette dernière signifiant que la donnée a été directement saisie dans OSE, sans qu'elle ne soit importée.

Créez la vue [SRC_CENTRE_COUT_STRUCTURE](SRC_CENTRE_COUT_STRUCTURE.sql).