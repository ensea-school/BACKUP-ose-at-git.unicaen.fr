CREATE OR REPLACE FORCE VIEW V_INDICATEUR_930 AS
SELECT DISTINCT
  w.intervenant_id,
  w.structure_id
FROM
  tbl_workflow w
WHERE
  w.etape_code = 'SAISIE_MEP'
  AND w.type_intervenant_code = 'E'
  AND w.atteignable = 1
  AND w.objectif > w.realisation