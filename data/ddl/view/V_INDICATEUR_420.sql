CREATE OR REPLACE FORCE VIEW V_INDICATEUR_420 AS
SELECT DISTINCT
  i.id intervenant_id,
  i.structure_id,
  TO_CHAR(MAX( d.HISTO_MODIFICATION),'YYYY-MM-DD HH24:MI:SS') AS "Date modif"
FROM
  indic_modif_dossier d
  JOIN intervenant i ON i.id = d.intervenant_id
WHERE
  d.histo_destruction IS NULL
GROUP BY (i.id, i.STRUCTURE_ID)