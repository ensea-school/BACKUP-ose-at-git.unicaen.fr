### TYPE_MODULATEUR_EP

Liste des types de modulateurs par éléments pédagogiques

Colonnes nécessaires :


|Colonne                 |Type    |Longueur|Nullable|Commentaire                        |
|------------------------|--------|--------|--------|-----------------------------------|
|Z_TYPE_MODULATEUR_ID    |NUMBER  |        |Non     |==> TYPE_MODULATEUR.CODE           |
|Z_ELEMENT_PEDAGOGIQUE_ID|NUMBER  |        |Non     |==> ELEMENT_PEDAGOGIQUE.SOURCE_CODE|
|Z_SOURCE_ID             |NUMBER  |        |Non     |==> SOURCE.CODE                    |
|SOURCE_CODE             |VARCHAR2|100     |Non     |                                   |


Exemple de requête :
[SRC_TYPE_MODULATEUR_EP](../Calcul/SRC_TYPE_MODULATEUR_EP.sql)

La vue fournie en exemple peut être réutilisée telle quelle, ou bien adaptée si vous en avez le besoin.
Elle prend ses données dans OSE et les réimporte dans l'application après transformation.