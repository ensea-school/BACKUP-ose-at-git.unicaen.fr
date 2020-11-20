### CHEMIN_PEDAGOGIQUE

Liste des chemins pédagogiques

Colonnes nécessaires :

|Colonne                 |Type    |Longueur|Nullable|Commentaire                        |
|------------------------|--------|--------|--------|-----------------------------------|
|Z_ETAPE_ID              |NUMBER  |        |Non     |==> ETAPE.SOURCE_CODE              |
|Z_ELEMENT_PEDAGOGIQUE_ID|NUMBER  |        |Non     |==> ELEMENT_PEDAGOGIQUE.SOURCE_CODE|
|ORDRE                   |NUMBER  |        |Non     |                                   |
|Z_SOURCE_ID             |NUMBER  |        |Non     |==> SOURCE.CODE                    |
|SOURCE_CODE             |VARCHAR2|100     |Oui     |                                   |


Exemple de requête :
[SRC_CHEMIN_PEDAGOGIQUE](../Apogée/SRC_CHEMIN_PEDAGOGIQUE.sql)