# VOLUME_HORAIRE_REF

Liste des volume horaires de référentiel (nb d'heures par service référentiel)

Colonnes nécessaires :

|Colonne                 |Type    |Longueur|Nullable|Commentaire                        |
|------------------------|--------|--------|--------|-----------------------------------|
|Z_TYPE_VOLUME_HORAIRE_ID|NUMBER  |        |Non     |==> TYPE_VOLUME_HORAIRE.CODE       |
|Z_SERVICE_REFERENTIEL_ID|NUMBER  |        |Non     |==> SERVICE_REFERENTIEL.SOURCE_CODE|
|HEURES                  |FLOAT   |        |Non     |                                   |
|Z_SOURCE_ID             |NUMBER  |        |Non     |==> SOURCE.CODE                    |
|SOURCE_CODE             |VARCHAR2|100     |Non     |                                   |
|AUTO_VALIDATION         |NUMBER  |        |Non     | Flag (1 ou 0)                     |
|HORAIRE_DEBUT           |DATE    |        |Oui     |                                   |
|HORAIRE_FIN             |DATE    |        |Oui     |                                   |
