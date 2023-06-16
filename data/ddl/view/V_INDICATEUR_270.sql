CREATE OR REPLACE FORCE VIEW V_INDICATEUR_270 AS
SELECT
  oe.id intervenant_id,
  oe.structure_id
FROM
  offre_emploi oe
  LEFT JOIN validation v ON v.id = oe.validation_id AND v.histo_destruction IS NULL
WHERE
  v.id IS NULL
  AND oe.histo_destruction IS NULL