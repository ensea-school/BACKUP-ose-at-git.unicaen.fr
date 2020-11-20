### AFFECTATION_RECHERCHE

Liste des affectations de recherche

Colonnes nécessaires :

|Colonne         |Type    |Longueur|Nullable|Commentaire                |
|----------------|--------|--------|--------|---------------------------|
|Z_INTERVENANT_ID|NUMBER  |        |Non     |==> INTERVENANT.SOURCE_CODE|
|Z_STRUCTURE_ID  |NUMBER  |        |Non     |==> STRUCTURE.SOURCE_CODE  |
|LABO_LIBELLE    |VARCHAR2|300     |Oui     |                           |
|Z_SOURCE_ID     |NUMBER  |        |Non     |==> SOURCE.CODE            |
|SOURCE_CODE     |VARCHAR2|100     |Oui     |                           |

Exemple de requête :
[SRC_AFFECTATION_RECHERCHE](../Harpège/SRC_AFFECTATION_RECHERCHE.sql)