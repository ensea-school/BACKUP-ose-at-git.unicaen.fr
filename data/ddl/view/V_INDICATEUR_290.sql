CREATE OR REPLACE FORCE VIEW V_INDICATEUR_290 AS
SELECT
    tp.intervenant_id,
    tp.structure_id
FROM
    tbl_prime tp
WHERE
  tp.declaration IS NOT NULL
  AND tp.validation_id IS NULL
GROUP BY
  tp.intervenant_id,
  tp.annee_id,
  tp.structure_id