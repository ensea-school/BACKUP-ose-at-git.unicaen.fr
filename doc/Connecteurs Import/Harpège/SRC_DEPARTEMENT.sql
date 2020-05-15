CREATE OR REPLACE FORCE VIEW SRC_DEPARTEMENT AS
WITH harpege_query AS (
  SELECT
    c_departement  code,
    ll_departement libelle_long,
    lc_departement libelle_court,
    'Harpege'      z_source_id,
    c_departement  source_code
  FROM
    departement@harpprod d
)
SELECT
  hq.code          code,
  hq.libelle_long  libelle_long,
  hq.libelle_court libelle_court,
  s.id             source_id,
  hq.source_code   source_code
FROM
       harpege_query hq
  JOIN source         s ON s.code = hq.z_source_id;