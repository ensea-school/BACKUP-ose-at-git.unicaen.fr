# ELEMENT_PEDAGOGIQUE

Liste des éléments pédagogiques

Colonnes nécessaires :

|Colonne        |Type    |Longueur|Nullable|Commentaire               |
|---------------|--------|--------|--------|--------------------------|
|CODE           |VARCHAR2|50      |Non     |                          |
|LIBELLE        |VARCHAR2|200     |Non     |                          |
|Z_ANNEE_ID     |NUMBER  |        |Non     |==> ANNEE.ID (2020 pour 2020/2021) |
|Z_ETAPE_ID     |NUMBER  |        |Non     |==> ETAPE.SOURCE_CODE     |
|Z_STRUCTURE_ID |NUMBER  |        |Non     |==> STRUCTURE.SOURCE_CODE |
|Z_PERIODE_ID   |NUMBER  |        |Oui     |==> PERIODE.CODE (S1,S2 ou NULL) |
|TAUX_FOAD      |FLOAT   |        |Non     | Flag (1 ou 0)            |
|FI             |NUMBER  |        |Non     | Flag (1 ou 0)            |
|FC             |NUMBER  |        |Non     | Flag (1 ou 0)            |
|FA             |NUMBER  |        |Non     | Flag (1 ou 0)            |
|TAUX_FA        |FLOAT   |        |Non     | Entre 0 et 1 inclus      |
|TAUX_FC        |FLOAT   |        |Non     | Entre 0 et 1 inclus      |
|TAUX_FI        |FLOAT   |        |Non     | Entre 0 et 1 inclus      |
|Z_DISCIPLINE_ID|NUMBER  |        |Oui     |==> DISCIPLINE.SOURCE_CODE|
|Z_SOURCE_ID    |NUMBER  |        |Non     |==> SOURCE.CODE           |
|SOURCE_CODE    |VARCHAR2|100     |Non     |                          |

Règles impératives
 * TAUX_FA + TAUX_FC + TAUX_FI = 1
 * Si FI = 0 alors TAUX_FI = 0
 * Si FA = 0 alors TAUX_FA = 0
 * Si FC = 0 alors TAUX_FC = 0


Exemple de requête :
[SRC_ELEMENT_PEDAGOGIQUE](../Apogée/SRC_ELEMENT_PEDAGOGIQUE.sql)