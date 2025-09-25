CREATE OR REPLACE FORCE VIEW V_INDICATEUR_410 AS
WITH caok AS (
  SELECT
    w.intervenant_id
  FROM
    tbl_workflow w
  WHERE
    w.objectif > 0
    AND w.etape_code = 'conseil_academique'
    AND w.realisation = w.objectif
)
SELECT
  w.intervenant_id,
  w.structure_id
FROM
  tbl_workflow w
  JOIN caok ON caok.intervenant_id = w.intervenant_id
WHERE
  w.atteignable = 1
  AND w.etape_code = 'contrat'
  AND w.objectif > 0
  AND w.realisation < 1