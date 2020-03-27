CREATE OR REPLACE FORCE VIEW V_INDICATEUR_910 AS
SELECT rownum id, t."ANNEE_ID",t."INTERVENANT_ID",t."STRUCTURE_ID" FROM
(
SELECT DISTINCT
  w.annee_id,
  w.intervenant_id,
  w.structure_id
FROM
  tbl_workflow w
WHERE
  w.etape_code = 'DEMANDE_MEP'
  AND w.type_intervenant_code = 'E'
  AND w.atteignable = 1
  AND w.objectif > w.realisation
) t