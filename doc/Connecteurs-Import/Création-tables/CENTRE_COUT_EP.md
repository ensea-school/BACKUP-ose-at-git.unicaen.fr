# CENTRE_COUT_EP

Liste des centres de coûts liés aux éléments pédagogiques

Il n'est pas nécessaire de fournir cette information : elle peut être saisie directement dans OSE.

Colonnes nécessaires :

|Colonne                 |Type    |Longueur|Nullable| Commentaire                                              |
|------------------------|--------|--------|--------|----------------------------------------------------------|
|Z_CENTRE_COUT_ID        |NUMBER  |        |Non     | ==> CENTRE_COUT.SOURCE_CODE                              |
|Z_ELEMENT_PEDAGOGIQUE_ID|NUMBER  |        |Non     | ==> ELEMENT_PEDAGOGIQUE.SOURCE_CODE                      |
|Z_TYPE_HEURES_ID        |NUMBER  |        |Non     | ==> TYPE_HEURES.CODE (fi, fa, fc, primes ou referentiel) |
|Z_SOURCE_ID             |NUMBER  |        |Non     | ==> SOURCE.CODE                                          |
|SOURCE_CODE             |VARCHAR2|100     |Non     |                                                          |

Les types d'heures sont FI, FC, FA, Référentiel.