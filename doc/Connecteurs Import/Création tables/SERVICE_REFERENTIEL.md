### SERVICE_REFERENTIEL

Liste des servcies référentiels (fonctions référentiels pour les intervenants)

Colonnes nécessaires :

|Colonne         |Type    |Longueur|Nullable|Commentaire                |
|----------------|--------|--------|--------|---------------------------|
|Z_FONCTION_ID   |NUMBER  |        |Non     |==> FONCTION.              |
|Z_INTERVENANT_ID|NUMBER  |        |Non     |==> INTERVENANT.SOURCE_CODE|
|Z_STRUCTURE_ID  |NUMBER  |        |Non     |==> STRUCTURE.SOURCE_CODE  |
|COMMENTAIRES    |VARCHAR2|256     |Oui     |                           |
|FORMATION       |VARCHAR2|256     |Oui     |                           |
|Z_SOURCE_ID     |NUMBER  |        |Non     |==> SOURCE.CODE            |
|SOURCE_CODE     |VARCHAR2|100     |Non     |                           |

Contrainte d'unicité sur [Z_FONCTION_ID,Z_INTERVENANT_ID,Z_STRUCTURE_ID]
