### SCENARIO_LIEN

Liste des paramétrages de liens par scénarios

Colonnes nécessaires :


|Colonne      |Type    |Longueur|Nullable|Commentaire         |
|-------------|--------|--------|--------|--------------------|
|Z_SCENARIO_ID|NUMBER  |        |Non     |==> SCENARIO.ID     |
|Z_LIEN_ID    |NUMBER  |        |Non     |==> LIEN.SOURCE_CODE|
|ACTIF        |NUMBER  |        |Non     |                    |
|POIDS        |FLOAT   |        |Non     |                    |
|CHOIX_MINIMUM|NUMBER  |        |Oui     |                    |
|CHOIX_MAXIMUM|NUMBER  |        |Oui     |                    |
|Z_SOURCE_ID  |NUMBER  |        |Non     |==> SOURCE.CODE     |
|SOURCE_CODE  |VARCHAR2|100     |Non     |                    |


Exemple de requête :
[SRC_SCENARIO_LIEN](../Apogée/SRC_SCENARIO_LIEN.sql)