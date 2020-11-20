### PAYS

Liste des pays

Colonnes nécessaires :

|Colonne       |Type    |Longueur|Nullable|Commentaire|
|--------------|--------|--------|--------|-----------|
|LIBELLE       |VARCHAR2|120     |Non     |           |
|TEMOIN_UE     |NUMBER  |        |Non     | Flag (1 ou 0) |
|VALIDITE_DEBUT|DATE    |        |Non     |           |
|VALIDITE_FIN  |DATE    |        |Oui     |           |
|Z_SOURCE_ID   |NUMBER  |        |Non     |==> SOURCE.CODE|
|SOURCE_CODE   |VARCHAR2|100     |Non     |           |
|CODE          |VARCHAR2|15      |Non     |           |


Exemple de requête :
[SRC_PAYS](../Harpège/SRC_PAYS.sql)