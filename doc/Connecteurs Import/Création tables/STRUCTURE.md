### STRUCTURE

Liste des structures (composantes, etc.)

Colonnes nécessaires :

|Colonne                  |Type    |Longueur|Nullable|Commentaire                  |
|-------------------------|--------|--------|--------|-----------------------------|
|LIBELLE_LONG             |VARCHAR2|100     |Non     |                             |
|LIBELLE_COURT            |VARCHAR2|25      |Non     |                             |
|Z_SOURCE_ID              |NUMBER  |        |Non     |==> SOURCE.CODE              |
|SOURCE_CODE              |VARCHAR2|100     |Oui     |                             |
|ENSEIGNEMENT             |NUMBER  |        |Non     |  Flag (1 ou 0), 1 si composante avec une ODF |
|CODE                     |VARCHAR2|50      |Non     |                             |
|ADRESSE_CODE_POSTAL      |VARCHAR2|15      |Oui     |                             |
|ADRESSE_COMMUNE          |VARCHAR2|50      |Oui     |                             |
|ADRESSE_LIEU_DIT         |VARCHAR2|60      |Oui     |                             |
|ADRESSE_NUMERO           |VARCHAR2|4       |Oui     |                             |
|Z_ADRESSE_NUMERO_COMPL_ID|NUMBER  |        |Oui     |==> ADRESSE_NUMERO_COMPL.CODE|
|Z_ADRESSE_PAYS_ID        |NUMBER  |        |Oui     |==> PAYS.SOURCE_CODE         |
|ADRESSE_PRECISIONS       |VARCHAR2|240     |Oui     |                             |
|ADRESSE_VOIE             |VARCHAR2|60      |Oui     |                             |
|Z_ADRESSE_VOIRIE_ID      |NUMBER  |        |Oui     |==> VOIRIE.SOURCE_CODE       |


Exemple de requête :
[SRC_STRUCTURE](../Harpège/SRC_STRUCTURE.sql)