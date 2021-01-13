# CORPS

Liste des corps

Colonnes nécessaires :

|Colonne      |Type    |Longueur|Nullable|Commentaire|
|-------------|--------|--------|--------|-----------|
|LIBELLE_LONG |VARCHAR2|100     |Non     |           |
|LIBELLE_COURT|VARCHAR2|20      |Non     |           |
|Z_SOURCE_ID  |NUMBER  |        |Non     |==> SOURCE.CODE|
|SOURCE_CODE  |VARCHAR2|100     |Oui     |           |


Exemple de requête :
[SRC_CORPS](../Harpège/SRC_CORPS.sql)