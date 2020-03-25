CREATE OR REPLACE FORCE VIEW V_INDICATEUR_640 AS
SELECT rownum id, t."ANNEE_ID",t."INTERVENANT_ID",t."STRUCTURE_ID" FROM
(
SELECT DISTINCT
  w.annee_id,
  w.intervenant_id,
  w.structure_id
FROM
  tbl_workflow w
  JOIN tbl_workflow wc ON wc.intervenant_id = w.intervenant_id
WHERE
  w.etape_code = 'SERVICE_VALIDATION_REALISE'
  AND w.objectif > w.realisation
  AND w.atteignable = 1

  AND wc.etape_code = 'CLOTURE_REALISE'
  AND wc.objectif = wc.realisation
) t