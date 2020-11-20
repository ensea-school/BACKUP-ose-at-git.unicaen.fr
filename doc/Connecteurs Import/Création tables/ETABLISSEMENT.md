### ETABLISSEMENT

Liste des établissements

Colonnes nécessaires :

|Colonne     |Type    |Longueur|Nullable|Commentaire|
|------------|--------|--------|--------|-----------|
|LIBELLE     |VARCHAR2|100     |Non     |           |
|LOCALISATION|VARCHAR2|60      |Oui     |           |
|DEPARTEMENT |VARCHAR2|3       |Oui     |           |
|Z_SOURCE_ID |NUMBER  |        |Non     |==> SOURCE.CODE|
|SOURCE_CODE |VARCHAR2|100     |Oui     |           |


Exemple de requête :
[SRC_ETABLISSEMENT](../Apogée/SRC_ETABLISSEMENT.sql)