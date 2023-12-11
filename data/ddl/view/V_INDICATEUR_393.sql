CREATE OR REPLACE FORCE VIEW V_INDICATEUR_393 AS
SELECT
    tp.intervenant_id,
    tp.structure_id
FROM
    tbl_mission_prime tp
WHERE
  tp.declaration > tp.validation
GROUP BY
  tp.intervenant_id,
  tp.annee_id,
  tp.structure_id