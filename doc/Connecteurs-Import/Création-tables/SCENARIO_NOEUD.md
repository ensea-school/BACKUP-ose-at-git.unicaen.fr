# SCENARIO_NOEUD

Liste des paramétrages de noeuds par scénarios

Colonnes nécessaires :

|Colonne      |Type    |Longueur|Nullable|Commentaire          |
|-------------|--------|--------|--------|---------------------|
|Z_SCENARIO_ID|NUMBER  |        |Non     |==> SCENARIO.ID      |
|Z_NOEUD_ID   |NUMBER  |        |Non     |==> NOEUD.SOURCE_CODE|
|ASSIDUITE    |FLOAT   |        |Non     | Coëf multiplicateur, 1 par défaut) |
|Z_SOURCE_ID  |NUMBER  |        |Non     |==> SOURCE.CODE      |
|SOURCE_CODE  |VARCHAR2|100     |Non     |                     |
|HEURES       |FLOAT   |        |Oui     |                     |
