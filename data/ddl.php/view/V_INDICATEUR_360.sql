CREATE OR REPLACE FORCE VIEW V_INDICATEUR_360 AS
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
WHERE
  c.histo_destruction IS NULL
  AND c.date_retour_signe IS NULL
) t