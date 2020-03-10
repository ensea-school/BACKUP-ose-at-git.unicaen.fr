CREATE OR REPLACE FORCE VIEW V_INDICATEUR_420 AS
SELECT rownum id, t."ANNEE_ID",t."INTERVENANT_ID",t."STRUCTURE_ID" FROM (
  SELECT DISTINCT
    i.annee_id annee_id,
    i.id intervenant_id,
    i.structure_id
  FROM
    indic_modif_dossier d
    JOIN intervenant i ON i.id = d.intervenant_id
  WHERE
    d.histo_destruction IS NULL
) t