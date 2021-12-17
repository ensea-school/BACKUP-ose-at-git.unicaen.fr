CREATE OR REPLACE FORCE VIEW V_INDICATEUR_420 AS
SELECT DISTINCT
  i.id intervenant_id,
  i.structure_id
FROM
  indic_modif_dossier d
  JOIN intervenant i ON i.id = d.intervenant_id
WHERE
  d.histo_destruction IS NULL