### GROUPE_TYPE_FORMATION

Liste des groupes de type de formation

Colonnes nécessaires :


|Colonne          |Type    |Longueur|Nullable|Commentaire|
|-----------------|--------|--------|--------|-----------|
|LIBELLE_COURT    |VARCHAR2|20      |Non     |           |
|LIBELLE_LONG     |VARCHAR2|50      |Non     |           |
|ORDRE            |NUMBER  |        |Non     |           |
|PERTINENCE_NIVEAU|NUMBER  |        |Non     | Flag ( 1 ou 0 ) |
|Z_SOURCE_ID      |NUMBER  |        |Non     |==> SOURCE.CODE|
|SOURCE_CODE      |VARCHAR2|100     |Oui     |           |


Exemple de requête :
[SRC_GROUPE_TYPE_FORMATION](../Apogée/SRC_GROUPE_TYPE_FORMATION.sql)