# NOEUD

Liste des nœuds

Colonnes nécessaires :

|Colonne                 |Type    |Longueur|Nullable|Commentaire                        |
|------------------------|--------|--------|--------|-----------------------------------|
|CODE                    |VARCHAR2|50      |Non     |                                   |
|LIBELLE                 |VARCHAR2|255     |Non     |                                   |
|LISTE                   |NUMBER  |        |Non     | Flag (1 ou 0)                     |
|Z_ANNEE_ID              |NUMBER  |        |Non     |==> ANNEE.ID (2020 pour 2020/2021) |
|Z_ETAPE_ID              |NUMBER  |        |Oui     |==> ETAPE.SOURCE_CODE              |
|Z_ELEMENT_PEDAGOGIQUE_ID|NUMBER  |        |Oui     |==> ELEMENT_PEDAGOGIQUE.SOURCE_CODE|
|Z_SOURCE_ID             |NUMBER  |        |Non     |==> SOURCE.CODE                    |
|SOURCE_CODE             |VARCHAR2|100     |Non     |                                   |
|Z_STRUCTURE_ID          |NUMBER  |        |Oui     |==> STRUCTURE.SOURCE_CODE          |


Exemple de requête :
[SRC_NOEUD](../Apogée/SRC_NOEUD.sql)