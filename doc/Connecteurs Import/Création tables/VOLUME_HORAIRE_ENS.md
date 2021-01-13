# VOLUME_HORAIRE_ENS

Liste des volumes horaires d'enseignement (charges)

Pour détertminer, par exemple, combien de groupes de TP sur un enseignement de Maths Fi et pour combien d'heures.

Colonnes nécessaires :

|Colonne                 |Type    |Longueur|Nullable|Commentaire                        |
|------------------------|--------|--------|--------|-----------------------------------|
|Z_TYPE_INTERVENTION_ID  |NUMBER  |        |Non     |==> TYPE_INTERVENTION.CODE         |
|HEURES                  |FLOAT   |        |Non     |                                   |
|Z_SOURCE_ID             |NUMBER  |        |Non     |==> SOURCE.CODE                    |
|SOURCE_CODE             |VARCHAR2|100     |Oui     |                                   |
|Z_ELEMENT_PEDAGOGIQUE_ID|NUMBER  |        |Non     |==> ELEMENT_PEDAGOGIQUE.SOURCE_CODE|
|GROUPES                 |FLOAT   |        |Oui     |                                   |


Exemple de requête :
[SRC_VOLUME_HORAIRE_ENS](../Apogée/SRC_VOLUME_HORAIRE_ENS.sql)