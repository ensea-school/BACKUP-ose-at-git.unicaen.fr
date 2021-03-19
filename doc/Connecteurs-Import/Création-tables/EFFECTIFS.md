# EFFECTIFS

Liste des effectifs

Colonnes nécessaires :

|Colonne                 |Type    |Longueur|Nullable|Commentaire                        |
|------------------------|--------|--------|--------|-----------------------------------|
|Z_ELEMENT_PEDAGOGIQUE_ID|NUMBER  |        |Non     |==> ELEMENT_PEDAGOGIQUE.SOURCE_CODE|
|Z_ANNEE_ID              |NUMBER  |        |Non     |==> ANNEE.ID (2020 pour 2020/2021) |
|FI                      |NUMBER  |        |Non     |                                   |
|FC                      |NUMBER  |        |Non     |                                   |
|FA                      |NUMBER  |        |Non     |                                   |
|Z_SOURCE_ID             |NUMBER  |        |Non     |==> SOURCE.CODE                    |
|SOURCE_CODE             |VARCHAR2|100     |Non     |                                   |


Exemple de requête :
[SRC_EFFECTIFS](../Apogée/SRC_EFFECTIFS.sql)