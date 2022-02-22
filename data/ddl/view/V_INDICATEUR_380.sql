CREATE OR REPLACE FORCE VIEW V_INDICATEUR_380 AS
SELECT
	i.id   intervenant_id,
	i.structure_id structure_id
FROM
  intervenant            i
  JOIN statut           si ON si.id = i.statut_id
  JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
  JOIN parametre p ON p.nom = 'export_rh_franchissement'
  JOIN tbl_workflow tw ON tw.intervenant_id = i.id AND tw.etape_id = p.valeur
  JOIN parametre p2 ON p2.nom='annee'
WHERE
  i.export_date IS NULL
  AND ti.code = 'E'
  AND si.code != 'BIATSS'
  AND p2.valeur = i.annee_id
  AND (COALESCE(i.affectation_fin, to_date('01/01/9999', 'dd/mm/YYYY')) < sysdate OR i.affectation_fin IS NULL )
  AND tw.realisation = tw.objectif
  AND tw.objectif > 0