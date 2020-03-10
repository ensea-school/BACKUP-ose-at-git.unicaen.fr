CREATE OR REPLACE FORCE VIEW V_INDICATEUR_410 AS
SELECT
  rownum id,
  d.annee_id,
  d.intervenant_id,
  i.structure_id
FROM
  tbl_dossier d
  JOIN intervenant i ON i.id = d.intervenant_id
WHERE
  d.dossier_id IS NOT NULL
  AND d.validation_id IS NULL
  AND d.peut_saisir_dossier = 1