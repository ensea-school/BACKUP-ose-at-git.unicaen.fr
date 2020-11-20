### GRADE

Liste des grades

Colonnes nécessaires :


|Colonne      |Type    |Longueur|Nullable|Commentaire          |
|-------------|--------|--------|--------|---------------------|
|LIBELLE_LONG |VARCHAR2|100     |Non     |                     |
|LIBELLE_COURT|VARCHAR2|20      |Non     |                     |
|ECHELLE      |VARCHAR2|10      |Oui     |                     |
|Z_CORPS_ID   |NUMBER  |        |Non     |==> CORPS.SOURCE_CODE|
|Z_SOURCE_ID  |NUMBER  |        |Non     |==> SOURCE.CODE      |
|SOURCE_CODE  |VARCHAR2|100     |Oui     |                     |


Exemple de requête :
[SRC_GRADE](../Harpège/SRC_GRADE.sql)