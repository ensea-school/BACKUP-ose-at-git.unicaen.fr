CREATE OR REPLACE FORCE VIEW V_INDICATEUR_650 AS
SELECT DISTINCT
  w.intervenant_id,
  w.structure_id
FROM
  tbl_workflow w
WHERE
  w.etape_code = 'SERVICE_VALIDATION_REALISE'
  AND w.type_intervenant_code = 'P'
  AND w.atteignable = 1
  AND w.objectif > w.realisation