CREATE OR REPLACE FORCE VIEW V_INDICATEUR_210 AS
SELECT
  w.intervenant_id,
  w.structure_id
FROM
  tbl_workflow w
WHERE
  w.atteignable = 1
  AND w.etape_code = 'CONSEIL_RESTREINT'
  AND w.objectif > 0
  AND w.realisation < 1