### TYPE_INTERVENTION_EP

Liste des types d'intervention (CM, TD, TP, ...) par élément pédagogique

Colonnes nécessaires :

|Colonne                 |Type    |Longueur|Nullable|Commentaire                        |
|------------------------|--------|--------|--------|-----------------------------------|
|Z_TYPE_INTERVENTION_ID  |NUMBER  |        |Non     |==> TYPE_INTERVENTION.CODE         |
|Z_ELEMENT_PEDAGOGIQUE_ID|NUMBER  |        |Non     |==> ELEMENT_PEDAGOGIQUE.SOURCE_CODE|
|Z_SOURCE_ID             |NUMBER  |        |Non     |==> SOURCE.CODE                    |
|SOURCE_CODE             |VARCHAR2|100     |Oui     |                                   |


Exemple de requête :
[SRC_TYPE_INTERVENTION_EP](../Calcul/SRC_TYPE_INTERVENTION_EP.sql)

Dans cet exemple, les données sont calculées à partir des charges d'enseignement renseignées dans OSE.
C'est pourquoi la source de données est "Calcul", un source qui indique les données proviennent de l'application même.

La vue en exemple conditionne la saisie de service au fait qu'il y ai des charges d'enseignements de renseignées.
Vous pouvez la réutiliser en l'état ou bien la personnaliser si vous souhaitez un autre fonctionnement, 
par exemple pouvoir saisir du service sans tenir compte des charges.