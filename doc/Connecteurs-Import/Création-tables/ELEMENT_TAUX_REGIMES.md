# ELEMENT_TAUX_REGIMES

Liste des éléments de taux de régime

Colonnes nécessaires :

|Colonne                 |Type    |Longueur|Nullable|Commentaire                        |
|------------------------|--------|--------|--------|-----------------------------------|
|Z_ELEMENT_PEDAGOGIQUE_ID|NUMBER  |        |Non     |==> ELEMENT_PEDAGOGIQUE.SOURCE_CODE|
|TAUX_FI                 |FLOAT   |        |Non     | Entre 0 et 1 inclus               |
|TAUX_FC                 |FLOAT   |        |Non     | Entre 0 et 1 inclus               |
|TAUX_FA                 |FLOAT   |        |Non     | Entre 0 et 1 inclus               |
|Z_SOURCE_ID             |NUMBER  |        |Non     |==> SOURCE.CODE                    |
|SOURCE_CODE             |VARCHAR2|100     |Non     |                                   |

Règles impératives
 * TAUX_FA + TAUX_FC + TAUX_FI = 1
 * Z_ELEMENT_PEDAGOGIQUE_ID doit être unique
 
 
Exemple de requête :
[SRC_ELEMENT_TAUX_REGIMES](../Apogée/SRC_ELEMENT_TAUX_REGIMES.sql)