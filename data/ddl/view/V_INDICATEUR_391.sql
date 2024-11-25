CREATE OR REPLACE FORCE VIEW V_INDICATEUR_391 AS
SELECT
  i.id                intervenant_id,
  i.structure_id      structure_id
FROM
  mission m
  JOIN tbl_contrat tblc ON tblc.mission_id = m.id
  JOIN intervenant i    ON i.id = m.intervenant_id
  JOIN statut s         ON s.id = i.statut_id
WHERE tblc.signe = 1
  AND s.mission_indemnitees = 1
GROUP BY
  i.id,
  i.structure_id
HAVING SUM(m.prime_id) IS NULL