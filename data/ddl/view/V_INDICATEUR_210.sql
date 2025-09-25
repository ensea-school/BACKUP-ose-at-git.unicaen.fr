CREATE OR REPLACE FORCE VIEW V_INDICATEUR_210 AS
SELECT DISTINCT
  w.intervenant_id,
  i.structure_id
FROM
  tbl_workflow w
  JOIN intervenant i ON i.id = w.intervenant_id
WHERE
  w.etape_code = 'pj_saisie'
  AND w.type_intervenant_code = 'E'
  AND w.atteignable = 1
  AND w.objectif > w.realisation