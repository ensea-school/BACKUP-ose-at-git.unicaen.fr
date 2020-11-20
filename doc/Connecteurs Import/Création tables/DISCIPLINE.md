### DISCIPLINE

Liste des disciplines (équivalent personnalisé des sections CNU, des sections du second degré, etc.)

Cette liste n'a pas nécessairement besoin d'être importée de votre système d'information : elle peut être gérée directement dans OSE.

Colonnes nécessaires :


|Colonne        |Type    |Longueur|Nullable|Commentaire|
|---------------|--------|--------|--------|-----------|
|LIBELLE_COURT  |VARCHAR2|20      |Oui     |           |
|LIBELLE_LONG   |VARCHAR2|200     |Non     |           |
|CODES_CORRESP_1|VARCHAR2|1000    |Oui     |           |
|CODES_CORRESP_2|VARCHAR2|1000    |Oui     |           |
|CODES_CORRESP_3|VARCHAR2|1000    |Oui     |           |
|CODES_CORRESP_4|VARCHAR2|1000    |Oui     |           |
|Z_SOURCE_ID    |NUMBER  |        |Non     |==> SOURCE.CODE|
|SOURCE_CODE    |VARCHAR2|100     |Non     |           |
