# SERVICE

Liste des services (éléments pédagogiques pour les intervenants)

Colonnes nécessaires :

|Colonne                 |Type    |Longueur|Nullable|Commentaire                        |
|------------------------|--------|--------|--------|-----------------------------------|
|Z_INTERVENANT_ID        |NUMBER  |        |Non     |==> INTERVENANT.SOURCE_CODE        |
|Z_ELEMENT_PEDAGOGIQUE_ID|NUMBER  |        |Oui     |==> ELEMENT_PEDAGOGIQUE.SOURCE_CODE|
|Z_ETABLISSEMENT_ID      |NUMBER  |        |Non     |==> ETABLISSEMENT.SOURCE_CODE      |
|Z_SOURCE_ID             |NUMBER  |        |Non     |==> SOURCE.CODE                    |
|SOURCE_CODE             |VARCHAR2|100     |Non     |                                   |
|DESCRIPTION             |VARCHAR2|4000    |Oui     |                                   |

Contrainte d'unicité sur [Z_INTERVENANT_ID,Z_ELEMENT_PEDAGOGIQUE_ID]
