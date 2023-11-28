CREATE OR REPLACE FORCE VIEW V_INDICATEUR_430 AS
SELECT
  w.intervenant_id,
  CASE
    WHEN w.structure_id IS NOT NULL
    THEN w.structure_id
    ELSE i.structure_id
  END structure_id
FROM
  intervenant i
  JOIN tbl_workflow w ON w.intervenant_id = i.id
  JOIN tbl_contrat c ON c.intervenant_id = i.id
WHERE
  w.atteignable = 1
  AND w.etape_code = 'CONTRAT'
  AND c.edite > 0
  AND c.edite < c.NBVH