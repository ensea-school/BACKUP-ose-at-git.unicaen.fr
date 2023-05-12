CREATE OR REPLACE FORCE VIEW V_INDICATEUR_P_MISSION AS
SELECT
  p.numero*100 + pm.type_volume_horaire_id * 10 numero,
  pm.intervenant_id,
  COALESCE(srs.structure_id, i.structure_id) structure_id,
  pe.libelle etat,
  tm.libelle type_mission,
  pm.heures,
  pm.plafond,
  pm.derogation
FROM
  tbl_plafond_mission pm
  JOIN plafond p ON p.id = pm.plafond_id
  JOIN plafond_etat pe ON pe.id = pm.plafond_etat_id
  JOIN intervenant i ON i.id = pm.intervenant_id
  JOIN type_mission tm ON tm.id = pm.type_mission_id
  LEFT JOIN (SELECT DISTINCT intervenant_id, type_mission_id, structure_id FROM mission) srs ON srs.intervenant_id = pm.intervenant_id AND srs.type_mission_id = pm.type_mission_id
WHERE
  pe.code <> 'desactive'
  AND pm.depassement = 1