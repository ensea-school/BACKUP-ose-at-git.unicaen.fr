CREATE OR REPLACE FORCE VIEW V_INDICATEUR_210 AS
SELECT DISTINCT
  w.intervenant_id,
  i.structure_id
FROM
  tbl_workflow w
  JOIN tbl_workflow wc ON wc.intervenant_id = w.intervenant_id
  JOIN intervenant i ON i.id = w.intervenant_id
WHERE
  w.etape_code = 'PJ_SAISIE'
  AND wc.etape_code = 'SERVICE_SAISIE'
  AND w.type_intervenant_code = 'E'
  AND wc.realisation > 0
  AND w.atteignable = 1
  AND w.objectif > w.realisation