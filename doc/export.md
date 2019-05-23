# Export de données

## Introduction

L'objectif de cette documentation est de décrire la manière d'interroger OSE pour en extraire des données en vue d'intégration 
dans un outil de reporting ou vers une solution de Business Intelligence.

Il existe souvent plusieurs possibilités de récupérer les données dont vous aurez besoin.
L'objectif de cette documentation est de présenter les principales sources de données que OSE peut fournir par ordre
de préférence : celles présentées d'abord sont à privilégier. 
Viennent ensuite d'autres sources qui conplèteront les premières le cas échéant. 

Toutes les données sont à puiser dans la base de données de OSE.
Cependant, il n'est pas recommandé d'écrire vos propres requêtes à partir de n'importe qu'elle table, car :
* le modèle de données pourra évoluer au fil du temps et vos exports risqueront alors d'en être affectés.
* certaines données sont issues de calculs dont il vaut mieux récupérer les résultats déjà pré-calculés plutôt que
de les recalculer, avec le risque de ne pas prendre en compte tous les paramètres de gestion nécessaires.
* dans les structures de données présentées ici, les données historisées dans OSE ont été expurgées. C'est-à-dire que
vous n'aurez pas besoin de filtrer chaque données pour savoir si oui ou non la donnée a été supprimée.

## Les tableaux de bord

Les tableaux de bord sont des données issues de calculs compilées dans des tables. Leur lecture est donc très rapide,
puisqu'il ne s'agit pas de vues. Qui okus est, ces données sont toujours à jour, car actualisées en temps réel par
l'application. Par exemple, le tableau de bord "tbl_dossier" est mis à jour sitôt les données personnelles de l'intervenant 
renseignées.

Ils constituent donc un sous-ensemble simplifié du modèle de données OSE plus particulièrement centré sur 
toutes les opérations liées au workflow. En outre, ces tableaux de bord servent d'appui au calcul des indicateurs.

Liste des tableaux de bord : 
```sql
SELECT * FROM user_tables WHERE table_name LIKE 'TBL_%' OR table_name LIKE 'FORMULE_RESULTAT_%';
```

Ils sont composs en partie de colonnes comportant des identifiants vers des tables susceptibles d'apporter plus 
d'informations. Par exemple INTERVENANT_ID renverra un ID d'INTERVENANT.

## Les vues EXPORT

Les vues export sont des vues ou des vues matérialisées qui servent à produire des exports que l'on peut
télécharger au format CSV dans OSE.

Il en existe plusieurs qui peuvent être exploitées pour extraire des données de OSE : 

### Export des services
Vue V_EXPORT_SERVICE

### Export des charges d'enseignement
Vue V_CHARGENS_EXPORT_CSV

### Export de raprochement des services par rapport aux charges (dépassements)
Vue V_EXPORT_DEPASS_CHARGES

## Les indicateurs
OSE propose une cinquantaine d'indicateurs centrés sur les intervennts qui renseignent sur l'évolution de leur situation
ou sur l'offre de formation (éléments supprimés avec des services, etc.).

Les données les concernant sont accessibles sous forme de vues dans OSE.
Afin de les reconnaitre, leur nom comporte leur numéro.
En voici la liste :
```sql
SELECT * FROM user_views WHERE view_name LIKE 'V_INDICATEUR_%';
```
Chaque indicateur comporte au moins 3 colonnes d'identifiants qui sont :
* ANNEE_ID
* STRUCTURE_ID
* INTERVENANT_ID

## Divers

### Faire le lien entre l'export des services et les mises en paiement



   ==> Lien entre v_export_service et mise_en_paiement à documenter