# CENTRE_COUT_STRUCTURE

Liste des centres de coûts liés aux structures

Colonnes nécessaires :

|Colonne         |Type    |Longueur|Nullable|Commentaire                |
|----------------|--------|--------|--------|---------------------------|
|Z_CENTRE_COUT_ID|NUMBER  |        |Non     |==> CENTRE_COUT.SOURCE_CODE|
|Z_STRUCTURE_ID  |NUMBER  |        |Non     |==> STRUCTURE.SOURCE_CODE  |
|UNITE_BUDGETAIRE|VARCHAR2|15      |Oui     |                           |
|Z_SOURCE_ID     |NUMBER  |        |Non     |==> SOURCE.CODE            |
|SOURCE_CODE     |VARCHAR2|100     |Oui     |                           |


Exemple de requête :
[SRC_CENTRE_COUT_STRUCTURE](../Sifac/SRC_CENTRE_COUT_STRUCTURE.sql)