CREATE OR REPLACE FORCE VIEW V_INDICATEUR_380 AS
SELECT
  rownum id,
  t."INTERVENANT_ID",t."ANNEE_ID",t."STRUCTURE_ID"
FROM (
SELECT
	i.id   intervenant_id,
	MAX(i.annee_id) annee_id,
	MAX(i.structure_id) structure_id
FROM intervenant i
JOIN contrat c ON c.intervenant_id = i.id  AND c.histo_destruction IS NULL
JOIN statut_intervenant si ON si.id = i.statut_id
WHERE i.export_date IS NULL
AND si.code != 'BIATSS'
AND si.type_intervenant_id = 2
AND (COALESCE(i.affectation_fin, to_date('01/01/9999', 'dd/mm/YYYY')) < sysdate OR i.affectation_fin IS NULL )
AND c.date_retour_signe IS NOT NULL
AND i.annee_id = (SELECT valeur FROM parametre p WHERE nom = 'annee')
GROUP BY i.id
) t
