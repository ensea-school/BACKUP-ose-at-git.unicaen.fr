CREATE OR REPLACE VIEW V_INDICATEUR_361
(ID,INTERVENANT_ID,ANNEE_ID,STRUCTURE_ID,CONTRAT_ID)
AS
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
  JOIN intervenant i ON i.id = c.intervenant_id
  JOIN tbl_workflow w ON w.intervenant_id = i.id AND w.structure_id = c.structure_id AND w.etape_code = 'CONTRAT' AND w.atteignable = 1
  JOIN validation v ON v.id = c.validation_id AND v.histo_destruction IS NULL
  LEFT JOIN contrat_fichier cf ON cf.contrat_id = c.id
  LEFT JOIN fichier f ON f.id = cf.fichier_id AND f.histo_destruction IS NULL
WHERE
  c.histo_destruction IS NULL
  AND f.id IS NULL
  AND c.date_envoi_email IS NOT NULL
) t