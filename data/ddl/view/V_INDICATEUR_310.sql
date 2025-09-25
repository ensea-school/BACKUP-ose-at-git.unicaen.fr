CREATE OR REPLACE FORCE VIEW V_INDICATEUR_310 AS
SELECT
  w.intervenant_id,
  w.structure_id
FROM
  tbl_workflow w
WHERE
  w.atteignable = 1
  AND w.etape_code = 'conseil_restreint'
  AND w.objectif > 0
  AND w.realisation < 1