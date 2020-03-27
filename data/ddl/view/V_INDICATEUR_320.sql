CREATE OR REPLACE FORCE VIEW V_INDICATEUR_320 AS
SELECT
  rownum id,
  t."ANNEE_ID",t."INTERVENANT_ID",t."STRUCTURE_ID"
FROM (
SELECT DISTINCT
  w.annee_id,
  w.intervenant_id,
  w.structure_id
FROM
  tbl_workflow w
  LEFT JOIN tbl_contrat c ON c.INTERVENANT_ID = w.intervenant_id AND w.structure_id = c.structure_id
WHERE
  w.atteignable = 1
  AND w.etape_code = 'CONTRAT'
  AND w.objectif > 0
  AND w.realisation = 0
  AND NVL(c.EDITE,0) <> 1
) t