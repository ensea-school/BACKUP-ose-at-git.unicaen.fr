### VOLUME_HORAIRE_CHARGE

Table non exploitée : à ignorer

Colonnes nécessaires :

|Colonne                 |Type    |Longueur|Nullable|Commentaire                        |
|------------------------|--------|--------|--------|-----------------------------------|
|Z_SCENARIO_ID           |NUMBER  |        |Non     |==> SCENARIO.                      |
|Z_ELEMENT_PEDAGOGIQUE_ID|NUMBER  |        |Non     |==> ELEMENT_PEDAGOGIQUE.SOURCE_CODE|
|Z_TYPE_INTERVENTION_ID  |NUMBER  |        |Non     |==> TYPE_INTERVENTION.CODE         |
|GROUPES                 |NUMBER  |        |Non     |                                   |
|Z_SOURCE_ID             |NUMBER  |        |Non     |==> SOURCE.CODE                    |
|SOURCE_CODE             |VARCHAR2|100     |Oui     |                                   |
