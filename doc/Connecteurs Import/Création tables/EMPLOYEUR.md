# EMPLOYEUR

Liste des employeurs

Colonnes nécessaires :

|Colonne                |Type    |Longueur|Nullable|Commentaire|
|-----------------------|--------|--------|--------|-----------|
|SIREN                  |VARCHAR2|100     |Non     |           |
|RAISON_SOCIALE         |VARCHAR2|250     |Non     |           |
|NOM_COMMERCIAL         |VARCHAR2|250     |Oui     |           |
|IDENTIFIANT_ASSOCIATION|VARCHAR2|250     |Oui     |           |
|Z_SOURCE_ID            |NUMBER  |        |Non     |==> SOURCE.CODE|
|SOURCE_CODE            |VARCHAR2|100     |Oui     |           |

Voici ci-dessous un prototype de vue qui pourra vous inspirer :

```sql
CREATE OR REPLACE FORCE VIEW SRC_EMPLOYEUR AS
WITH source_query AS (
  SELECT
    'votre SIREN'                   siren,
    'votre RAISON_SOCIALE'          raison_sociale,
    'votre NOM_COMMERCIAL'          nom_commercial,
    'votre IDENTIFIANT_ASSOCIATION' identifiant_association,
    'votre Z_SOURCE_ID'             z_source_id,
    'votre SOURCE_CODE'             source_code
  FROM
    dual
)
SELECT
  sq.siren                   siren,
  sq.raison_sociale          raison_sociale,
  sq.nom_commercial          nom_commercial,
  sq.identifiant_association identifiant_association,
  -- optimisation pour accélérer les recherches
  ose_divers.str_reduce( sq.raison_sociale || ' ' || sq.nom_commercial || ' ' || sq.identifiant_association ) critere_recherche,
  s.id                       source_id,
  sq.source_code             source_code
FROM
  source_query sq
  JOIN source   s ON s.code        = sq.z_source_id
```