### ETAPE

Liste des étapes (années de formation, L1 de Droit par exemple)

Colonnes nécessaires :

|Colonne                 |Type    |Longueur|Nullable|Commentaire                        |
|------------------------|--------|--------|--------|-----------------------------------|
|CODE                    |VARCHAR2|50      |Non     |                                   |
|LIBELLE                 |VARCHAR2|200     |Non     |                                   |
|Z_ANNEE_ID              |NUMBER  |        |Non     |==> ANNEE.ID (2020 pour 2020/2021) |
|Z_TYPE_FORMATION_ID     |NUMBER  |        |Non     |==> TYPE_FORMATION.SOURCE_CODE     |
|Z_STRUCTURE_ID          |NUMBER  |        |Non     |==> STRUCTURE.SOURCE_CODE          |
|NIVEAU                  |NUMBER  |        |Oui     | 1 si c'est une L1 ou un M1, etc. et NULL si non pertinent |
|SPECIFIQUE_ECHANGES     |NUMBER  |        |Non     | Flag (1 ou 0)                     |
|Z_DOMAINE_FONCTIONNEL_ID|NUMBER  |        |Non     |==> DOMAINE_FONCTIONNEL.SOURCE_CODE|
|Z_SOURCE_ID             |NUMBER  |        |Non     |==> SOURCE.CODE                    |
|SOURCE_CODE             |VARCHAR2|100     |Non     |                                   |


Exemple de requête :
[SRC_ETAPE](../Apogée/SRC_ETAPE.sql)