CREATE OR REPLACE FORCE VIEW V_INDICATEUR_280 AS
SELECT
    tc.intervenant_id,
    tc.structure_id
FROM
    tbl_candidature tc
    JOIN tbl_workflow w ON tc.intervenant_id = w.intervenant_id
WHERE
  tc.candidature_id IS NOT NULL
  AND tc.validation_id IS NULL
  AND tc.refusee = 0
  AND w.etape_code = 'candidature_saisie'
  AND w.atteignable = 1
GROUP BY
  tc.intervenant_id,
  tc.annee_id,
  tc.structure_id