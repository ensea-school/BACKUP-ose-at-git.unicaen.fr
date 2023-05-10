CREATE OR REPLACE FORCE VIEW V_TBL_MISSION AS
SELECT
  i.annee_id                                                                               annee_id,
  i.id                                                                                     intervenant_id,
  1                                                                                        actif,
  m.id                                                                                     mission_id,
  m.structure_id                                                                           structure_id,
  i.structure_id                                                                           intervenant_structure_id,
  CASE WHEN m.auto_validation = 1 OR vm.mission_id IS NOT NULL THEN 1 ELSE 0 END           valide,
  vm.validation_id                                                                         validation_id,
  0                                                                                        contractualise,
  null                                                                                     contrat_id,
  SUM(CASE WHEN tvh.code = 'PREVU' THEN COALESCE(vhm.heures,0) ELSE 0 END)                 heures_prevues_saisies,
  SUM(CASE WHEN tvh.code = 'PREVU' AND (vhm.auto_validation = 1 OR vvhm.volume_horaire_mission_id IS NOT NULL) THEN COALESCE(vhm.heures,0) ELSE 0 END) heures_prevues_validees,
  SUM(CASE WHEN tvh.code = 'REALISE' THEN COALESCE(vhm.heures,0) ELSE 0 END)               heures_realisees_saisies,
  SUM(CASE WHEN tvh.code = 'REALISE' AND (vhm.auto_validation = 1 OR vvhm.volume_horaire_mission_id IS NOT NULL) THEN COALESCE(vhm.heures,0) ELSE 0 END) heures_realisees_validees

FROM
            intervenant                     i
       JOIN statut                         si ON si.id = i.statut_id
       JOIN type_validation              tvvh ON tvvh.code = 'MISSION_REALISE'
  LEFT JOIN mission                         m ON m.intervenant_id = i.id AND m.histo_destruction IS NULL
  LEFT JOIN (SELECT vml.mission_id, v.id validation_id
             FROM validation v
             JOIN validation_mission vml ON vml.validation_id = v.id
             WHERE v.histo_destruction IS NULL
             GROUP BY vml.mission_id, v.id) vm ON vm.mission_id = m.id
  LEFT JOIN volume_horaire_mission        vhm ON vhm.mission_id = m.id AND vhm.histo_destruction IS NULL
  LEFT JOIN (SELECT vvhm.volume_horaire_mission_id
             FROM validation v
             JOIN validation_vol_horaire_miss vvhm ON vvhm.validation_id = v.id
             WHERE v.histo_destruction IS NULL
             GROUP BY vvhm.volume_horaire_mission_id
  ) vvhm ON vvhm.volume_horaire_mission_id = vhm.id
  LEFT JOIN type_volume_horaire           tvh ON tvh.id = vhm.type_volume_horaire_id
WHERE
  i.histo_destruction IS NULL
  AND si.mission = 1
GROUP BY
  i.annee_id,
  i.id,
  m.id,
  vm.validation_id,
  m.structure_id,
  i.structure_id,
  m.auto_validation,
  vm.mission_id