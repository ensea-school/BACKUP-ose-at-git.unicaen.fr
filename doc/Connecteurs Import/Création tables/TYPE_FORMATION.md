# TYPE_FORMATION

Liste des types de formation

Colonnes nécessaires :

|Colonne           |Type    |Longueur|Nullable|Commentaire|
|------------------|--------|--------|--------|-----------|
|LIBELLE_LONG      |VARCHAR2|80      |Non     |           |
|LIBELLE_COURT     |VARCHAR2|15      |Non     |           |
|Z_GROUPE_ID       |NUMBER  |        |Non     |==> GROUPE_TYPE_FORMATION.SOURCE_CODE |
|Z_SOURCE_ID       |NUMBER  |        |Non     |==> SOURCE.CODE|
|SOURCE_CODE       |VARCHAR2|100     |Oui     |               |
|SERVICE_STATUTAIRE|NUMBER  |        |Non     | Flag (1 ou 0) |


Exemple de requête :
[SRC_TYPE_FORMATION](../Apogée/SRC_TYPE_FORMATION.sql)