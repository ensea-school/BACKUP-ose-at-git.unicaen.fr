# VOLUME_HORAIRE

Liste des volumes horaires (nb d'heures de CM, TD, TP par ligne de service)

Colonnes nécessaires :

|Colonne                 |Type    |Longueur|Nullable|Commentaire                 |
|------------------------|--------|--------|--------|----------------------------|
|Z_TYPE_VOLUME_HORAIRE_ID|NUMBER  |        |Non     |==> TYPE_VOLUME_HORAIRE.CODE|
|Z_SERVICE_ID            |NUMBER  |        |Non     |==> SERVICE.SOURCE_CODE     |
|Z_PERIODE_ID            |NUMBER  |        |Non     |==> PERIODE.CODE            |
|Z_TYPE_INTERVENTION_ID  |NUMBER  |        |Non     |==> TYPE_INTERVENTION.CODE  |
|HEURES                  |FLOAT   |        |Non     |                            |
|Z_MOTIF_NON_PAIEMENT_ID |NUMBER  |        |Oui     |==> MOTIF_NON_PAIEMENT.CODE |
|Z_CONTRAT_ID            |NUMBER  |        |Oui     |==> CONTRAT.ID              |
|Z_SOURCE_ID             |NUMBER  |        |Non     |==> SOURCE.CODE             |
|SOURCE_CODE             |VARCHAR2|100     |Non     |                            |
|AUTO_VALIDATION         |NUMBER  |        |Non     | Flag (1 ou 0)              |
|HORAIRE_DEBUT           |DATE    |        |Oui     |                            |
|HORAIRE_FIN             |DATE    |        |Oui     |                            |
