CREATE OR REPLACE FORCE VIEW V_INDICATEUR_350 AS
SELECT
  rownum id,
  t."INTERVENANT_ID",t."ANNEE_ID",t."STRUCTURE_ID",t."CONTRAT_ID"
FROM (
SELECT DISTINCT
  i.id intervenant_id,
  i.annee_id annee_id,
  c.structure_id structure_id,
  c.id contrat_id
FROM
  contrat                c
  JOIN contrat_fichier  cf ON cf.contrat_id = c.id
  JOIN fichier           f ON f.id = cf.fichier_id
                          AND f.histo_destruction IS NULL
  JOIN intervenant i ON i.id = c.intervenant_id
WHERE
  c.histo_destruction IS NULL
) t