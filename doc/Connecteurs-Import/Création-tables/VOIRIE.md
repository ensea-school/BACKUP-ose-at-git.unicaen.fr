# VOIRIE

Liste des voiries (rue, allée, boulevard, etc.)

Colonnes nécessaires :

|Colonne    |Type    |Longueur|Nullable|Commentaire|
|-----------|--------|--------|--------|-----------|
|CODE       |VARCHAR2|5       |Non     |           |
|LIBELLE    |VARCHAR2|120     |Non     |           |
|Z_SOURCE_ID|NUMBER  |        |Non     |==> SOURCE.CODE|
|SOURCE_CODE|VARCHAR2|100     |Non     |           |



Exemple de requête :
[SRC_VOIRIE](../Harpège/SRC_VOIRIE.sql)