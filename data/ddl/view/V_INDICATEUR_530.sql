CREATE OR REPLACE FORCE VIEW V_INDICATEUR_530 AS
SELECT DISTINCT
  w.intervenant_id,
  i.structure_id
FROM
  tbl_workflow w
  JOIN intervenant i ON i.id = w.intervenant_id
WHERE
  w.etape_code = 'CLOTURE_REALISE'
  AND w.atteignable = 1
  AND w.objectif > w.realisation