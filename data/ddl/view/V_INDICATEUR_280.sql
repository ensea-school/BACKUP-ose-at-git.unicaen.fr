CREATE OR REPLACE FORCE VIEW V_INDICATEUR_280 AS
SELECT
    tc.intervenant_id,
    tc.structure_id
FROM
    tbl_candidature tc
WHERE
  tc.candidature_id IS NOT NULL
  AND tc.validation_id IS NULL
GROUP BY
  tc.intervenant_id,
  tc.annee_id,
  tc.structure_id