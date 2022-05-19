CREATE OR REPLACE FORCE VIEW V_INDICATEUR_920 AS
SELECT DISTINCT
  w.intervenant_id,
  w.structure_id
FROM
  tbl_workflow w
WHERE
  w.etape_code = 'DEMANDE_MEP'
  AND w.type_intervenant_code = 'P'
  AND w.atteignable = 1
  AND w.objectif > w.realisation