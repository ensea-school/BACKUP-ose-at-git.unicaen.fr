CREATE OR REPLACE FORCE VIEW V_INDICATEUR_P_ELEMENT AS
SELECT
  p.numero*100 + tp.type_volume_horaire_id * 10 numero,
  tp.intervenant_id,
  ep.structure_id structure_id,
  pe.libelle etat,
  ep.libelle element,
  tp.heures,
  tp.plafond,
  tp.derogation
FROM
  tbl_plafond_element tp
  JOIN plafond p ON p.id = tp.plafond_id
  JOIN plafond_etat pe ON pe.id = tp.plafond_etat_id
  JOIN intervenant i ON i.id = tp.intervenant_id
  JOIN element_pedagogique ep ON ep.id = tp.element_pedagogique_id
WHERE
  pe.code <> 'desactive'
  AND tp.depassement = 1