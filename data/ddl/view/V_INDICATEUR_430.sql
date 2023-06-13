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
  JOIN (
    SELECT DISTINCT
      c.intervenant_id,
      c.structure_id
    FROM
      contrat c
    WHERE
      c.type_contrat_id = 1 --a déjà un contrat de type 'CONTRAT'
      AND c.histo_destruction IS NULL
  ) hc ON hc.intervenant_id = i.id
WHERE
  w.atteignable = 1
  AND w.type_intervenant_code = 'E'
  AND w.etape_code = 'CONTRAT'
  AND w.objectif > 0
  AND w.realisation < w.objectif