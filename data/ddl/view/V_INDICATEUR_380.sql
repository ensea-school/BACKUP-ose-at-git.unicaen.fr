CREATE OR REPLACE FORCE VIEW V_INDICATEUR_380 AS
SELECT
  rownum id,
  t."INTERVENANT_ID",t."ANNEE_ID",t."STRUCTURE_ID"
FROM (
SELECT
	i.id   intervenant_id,
	i.annee_id annee_id,
	i.structure_id structure_id
FROM intervenant i
JOIN contrat c ON c.intervenant_id = i.id  AND c.histo_destruction IS NULL
WHERE i.export_date IS NULL
AND i.affectation_fin < sysdate
AND c.
) t