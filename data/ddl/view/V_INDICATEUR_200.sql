CREATE OR REPLACE FORCE VIEW V_INDICATEUR_200 AS
SELECT
  i.id intervenant_id,
  i.structure_id
FROM
  intervenant i
WHERE
  i.irrecevable = 1