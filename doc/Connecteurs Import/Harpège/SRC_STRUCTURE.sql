CREATE OR REPLACE FORCE VIEW SRC_STRUCTURE AS
WITH harpege_query AS (
  SELECT
    str.c_structure  code,
    str.lc_structure libelle_court,
    str.ll_structure libelle_long,
    'Harpege'        z_source_id,
    str.c_structure  source_code
  FROM
    structure@harpprod str
  WHERE
    SYSDATE BETWEEN str.date_ouverture AND COALESCE( str.date_fermeture, SYSDATE )
    AND (str.c_structure = 'UNIV' OR str.c_structure_pere = 'UNIV') -- UNIV = structure "Université" de niveau 1
)
SELECT
  hq.code          code,
  hq.libelle_court libelle_court,
  hq.libelle_long  libelle_long,
  src.id           source_id,
  hq.source_code   source_code
FROM
       harpege_query hq
  JOIN source       src ON src.code = hq.z_source_id;