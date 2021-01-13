# CENTRE_COUT

Liste des centres de coûts

Colonnes nécessaires :

|Colonne            |Type    |Longueur|Nullable|Commentaire            |
|-------------------|--------|--------|--------|-----------------------|
|Z_PARENT_ID        |NUMBER  |        |Oui     |==> CENTRE_COUT.SOURCE_CODE |
|CODE               |VARCHAR2|50      |Non     |                       |
|LIBELLE            |VARCHAR2|200     |Non     |                       |
|Z_ACTIVITE_ID      |NUMBER  |        |Non     |==> CC_ACTIVITE.CODE   |
|Z_TYPE_RESSOURCE_ID|NUMBER  |        |Non     |==> TYPE_RESSOURCE.CODE|
|UNITE_BUDGETAIRE   |VARCHAR2|15      |Oui     |                       |
|Z_SOURCE_ID        |NUMBER  |        |Non     |==> SOURCE.CODE        |
|SOURCE_CODE        |VARCHAR2|100     |Non     |                       |

Il ne doit y avoir que deux niveaux de centres de coûts maximum.
Donc un enregistrement qui a un Z_PARENT_ID non NULL ne doit pas avoir de fils.

Généralement, les centres de coûts ont Z_PARENT_ID NULL et les EOTP dépendant du centre de coûts renseigné dans Z_PARENT_ID.

Exemple de requête :
[SRC_CENTRE_COUT](../Sifac/SRC_CENTRE_COUT.sql)