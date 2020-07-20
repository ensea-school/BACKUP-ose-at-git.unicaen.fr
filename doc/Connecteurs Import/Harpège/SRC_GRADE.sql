CREATE OR REPLACE FORCE VIEW SRC_GRADE AS
WITH harpege_query AS (
  SELECT
    g.ll_grade  libelle_long,
    g.lc_grade  libelle_court,
    'Harpege'   z_source_id,
    g.c_grade   source_code,
    g.echelle   echelle,
    g.c_corps   z_corps_id
  FROM
    grade@harpprod g
  WHERE
    SYSDATE BETWEEN COALESCE(g.d_ouverture,SYSDATE) AND COALESCE(g.d_fermeture+1,SYSDATE)
)
SELECT
  hq.libelle_long   libelle_long,
  hq.libelle_court  libelle_court,
  s.id              source_id,
  hq.source_code    source_code,
  hq.echelle        echelle,
  c.id              corps_id
FROM
       harpege_query hq
  JOIN source         s ON s.code        = hq.z_source_id
  JOIN corps          c ON c.source_code = hq.z_corps_id;