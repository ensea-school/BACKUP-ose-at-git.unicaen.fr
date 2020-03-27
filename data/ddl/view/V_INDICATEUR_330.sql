CREATE OR REPLACE FORCE VIEW V_INDICATEUR_330 AS
WITH has_contrat AS (
  SELECT DISTINCT
    intervenant_id
  FROM
    tbl_contrat
  WHERE
    edite > 0
)
SELECT
  rownum id,
  w.annee_id,
  w.intervenant_id,
  w.structure_id
FROM
  tbl_workflow w
  JOIN has_contrat hc ON hc.intervenant_id = w.intervenant_id
WHERE
  w.atteignable = 1
  AND w.etape_code = 'CONTRAT'
  AND w.objectif > 0
  AND w.realisation < w.objectif