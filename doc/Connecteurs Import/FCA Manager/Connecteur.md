# Connecteur FCA Manager

Le connecteur FCA Manager permet de synchroniser en import :
  * les étapes (formations)
  * les éléments pédagogiques ainsi que les chemins pédagogiques (liens étapes / éléments)
  * les volumes horaires d'enseignement, c'est-à-dire les charges d'enseignements renseignées dans Apogée

Les vues qui vont sont fournies ci-dessous ne représentent qu'un exemple. Il vous revient de les adapter à votre contexte afin que vous
retrouviez dans OSE les données dont vous avez besoin. 

Le connecteur FCA Manager est découpé en deux parties.
La première s'installe directement sur FCA Manager.
La seconde exploite la partie FCA Manager du connecteur et fournie les vues sources nécessaires pour les opérations de synchronisation. 

## Installation de la partie Apogée du connecteur

Il faut d'abord installer la partie FCA Manager du connecteur.

[Partie FCA Manager du connecteur](FCAManager-OSE-lisezMoi.md)
  
## Mise en place du DbLink

Le lien avec FCA Manager se fait au moyen d'un DbLink que vous devrez créer.
Dans cet exemple, le DbLink s'appellera `fcaprod`.

## Déclaration du connecteur dans OSE  

OSE doit lister toutes ses sources de données.
Il faut donc y ajouter FCA Manager : 

```sql
BEGIN
  unicaen_import.add_source('FCAManager', 'FCA Manager');
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

## Récupération des étapes

Créez la vue [SRC_ETAPE](SRC_ETAPE.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).

## Récupération des éléments pédagogiques

FCA Manager ne connait pas la notion de discipline ou de section CNU.
Pour pallier à cela, une table spécifique renseignée manuellement en base de données permet d'associer une discipline à un élément pédagogique.
Voici la définition de cette table : 

```sql
CREATE TABLE unicaen_element_discipline (
  element_source_code      VARCHAR2(30 CHAR) NOT NULL,
  discipline_source_code   VARCHAR2(30 CHAR) NOT NULL
)
LOGGING;

ALTER TABLE unicaen_element_discipline
  ADD CONSTRAINT unicaen_element_discipline_pk
  PRIMARY KEY ( element_source_code, discipline_source_code );
```

A vous d'adapter ceci à votre contexte en créant votre propre table, ou bien en créant une vue ou alors en ne positionnant pas de discipine sur les éléments pédagogiques
issus de FCA Manager. 


Créez la vue [SRC_ELEMENT_PEDAGOGIQUE](SRC_ELEMENT_PEDAGOGIQUE.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).

## Récupération des chemins pédagogiques

Créez la vue [SRC_CHEMIN_PEDAGOGIQUE](SRC_CHEMIN_PEDAGOGIQUE.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).

## Récupération des volumes horaires d'enseignement

Créez la vue [SRC_VOLUME_HORAIRE_ENS](SRC_VOLUME_HORAIRE_ENS.sql).

[Activez-là, puis tentez une synchronisation](../activer-synchroniser.md).