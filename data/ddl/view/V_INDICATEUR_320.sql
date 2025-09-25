CREATE OR REPLACE FORCE VIEW V_INDICATEUR_320 AS
SELECT
  w.intervenant_id,
  i.structure_id
FROM
  tbl_workflow w
  JOIN intervenant i ON i.id = w.intervenant_id
WHERE
  w.atteignable = 1
  AND w.etape_code = 'conseil_academique'
  AND w.objectif > 0
  AND w.realisation < 1