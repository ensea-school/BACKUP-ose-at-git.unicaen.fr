CREATE OR REPLACE FORCE VIEW V_INDICATEUR_380 AS
SELECT
	i.id   intervenant_id,
	i.structure_id structure_id
FROM
  intervenant            i
  JOIN contrat           c ON c.intervenant_id = i.id  AND c.histo_destruction IS NULL
  JOIN statut           si ON si.id = i.statut_id
  JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
  JOIN parametre         p ON p.nom='annee'
WHERE
  i.export_date IS NULL
  AND ti.code = 'E'
  AND si.code != 'BIATSS'
  AND p.valeur = i.annee_id
  AND (COALESCE(i.affectation_fin, to_date('01/01/9999', 'dd/mm/YYYY')) < sysdate OR i.affectation_fin IS NULL )
  AND c.date_retour_signe IS NOT NULL