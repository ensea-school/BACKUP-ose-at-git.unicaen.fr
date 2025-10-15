--INDICATEUR A REVOIR AVEC LE NOUVEAU WORKFLOW / Vérifier l'étape export rh
CREATE OR REPLACE FORCE VIEW V_INDICATEUR_490 AS
SELECT i.id           intervenant_id,
       i.structure_id structure_id
FROM intervenant i
         JOIN statut si ON si.id = i.statut_id
         JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
WHERE i.export_date IS NULL
  AND ti.code = 'E'
  AND si.code != 'BIATSS'
  AND (COALESCE(i.affectation_fin, to_date('01/01/9999', 'dd/mm/YYYY')) < sysdate OR i.affectation_fin IS NULL )
