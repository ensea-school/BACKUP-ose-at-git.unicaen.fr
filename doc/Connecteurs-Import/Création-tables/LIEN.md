# LIEN

Liste des liens

Colonnes nécessaires :

|Colonne       |Type    |Longueur|Nullable|Commentaire              |
|--------------|--------|--------|--------|-------------------------|
|Z_NOEUD_SUP_ID|NUMBER  |        |Non     |==> NOEUD.SOURCE_CODE    |
|Z_NOEUD_INF_ID|NUMBER  |        |Non     |==> NOEUD.SOURCE_CODE    |
|Z_STRUCTURE_ID|NUMBER  |        |Oui     |==> STRUCTURE.SOURCE_CODE|
|Z_SOURCE_ID   |NUMBER  |        |Non     |==> SOURCE.CODE          |
|SOURCE_CODE   |VARCHAR2|100     |Non     |                         |


Exemple de requête :
[SRC_LIEN](../Apogée/SRC_LIEN.sql)