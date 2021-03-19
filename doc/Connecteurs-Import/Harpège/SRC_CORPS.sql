CREATE OR REPLACE FORCE VIEW SRC_CORPS AS
WITH harpege_query AS (
  SELECT
    c.ll_corps  libelle_long,
    c.lc_corps  libelle_court,
    'Harpege'   z_source_id,
    c.c_corps   source_code
  FROM
    corps@harpprod c
  WHERE
    SYSDATE BETWEEN COALESCE(c.d_ouverture_corps,SYSDATE) AND COALESCE(c.d_fermeture_corps+1,SYSDATE)
)
SELECT
  hq.libelle_long  libelle_long,
  hq.libelle_court libelle_court,
  s.id             source_id,
  hq.source_code   source_code
FROM
       harpege_query hq
  JOIN source         s ON s.code = hq.z_source_id;