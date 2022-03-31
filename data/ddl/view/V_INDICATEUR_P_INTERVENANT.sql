CREATE OR REPLACE FORCE VIEW V_INDICATEUR_P_INTERVENANT AS
SELECT
  p.numero*100 + tp.type_volume_horaire_id * 10 numero,
  tp.intervenant_id,
  i.structure_id structure_id,
  pe.libelle etat,
  tp.heures,
  tp.plafond,
  tp.derogation
FROM
  tbl_plafond_intervenant tp
  JOIN plafond p ON p.id = tp.plafond_id
  JOIN plafond_etat pe ON pe.id = tp.plafond_etat_id
  JOIN intervenant i ON i.id = tp.intervenant_id
WHERE
  pe.code <> 'desactive'
  AND tp.depassement = 1