CREATE OR REPLACE FORCE VIEW V_INDICATEUR_P_STRUCTURE AS
SELECT
  p.numero*100 + tp.type_volume_horaire_id * 10 numero,
  tp.intervenant_id,
  tp.structure_id,
  pe.libelle etat,
  tp.heures,
  tp.plafond,
  tp.derogation
FROM
  tbl_plafond_structure tp
  JOIN plafond p ON p.id = tp.plafond_id
  JOIN plafond_etat pe ON pe.id = tp.plafond_etat_id
WHERE
  pe.code <> 'desactive'
  AND tp.depassement = 1