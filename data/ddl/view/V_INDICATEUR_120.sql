CREATE OR REPLACE FORCE VIEW V_INDICATEUR_120 AS
SELECT DISTINCT
  i.id intervenant_id,
  i.structure_id,
  MAX(d.HISTO_MODIFICATION) AS "Date de modification"
FROM
  indic_modif_dossier d
  JOIN intervenant i ON i.id = d.intervenant_id
WHERE
  d.histo_destruction IS NULL
GROUP BY (i.id, i.STRUCTURE_ID)