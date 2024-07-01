CREATE OR REPLACE FORCE VIEW V_INDICATEUR_393 AS
SELECT
    tp.intervenant_id,
    tp.structure_id
FROM
    tbl_mission_prime tp
    JOIN intervenant i ON i.id = tp.intervenant_id
    JOIN statut s ON s.id = i.statut_id
WHERE
  tp.declaration > tp.validation
  AND s.mission_indemnitees = 1

GROUP BY
  tp.intervenant_id,
  tp.annee_id,
  tp.structure_id