CREATE OR REPLACE FORCE VIEW V_TBL_MISSION AS
SELECT
  i.annee_id                                                                               annee_id,
  i.id                                                                                     intervenant_id,
  1                                                                                        actif,
  m.id                                                                                     mission_id,
  m.structure_id                                                                           structure_id,
  i.structure_id                                                                           intervenant_structure_id,
  CASE WHEN m.auto_validation = 1 OR vm.id IS NOT NULL THEN 1 ELSE 0 END                   valide,
  SUM(vhm.heures)                                                                          heures_realisees,
  SUM(CASE WHEN vvhm.id IS NOT NULL OR vhm.auto_validation = 1 THEN vhm.heures ELSE 0 END) heures_validees
FROM
            intervenant                     i
       JOIN statut                         si on si.id = i.statut_id
       JOIN type_validation               tvm on tvm.code = 'MISSION'
       JOIN type_validation              tvvh on tvvh.code = 'MISSION_REALISE'
  LEFT JOIN mission                         m on m.intervenant_id = i.id AND m.histo_destruction IS NULL
  LEFT JOIN validation_mission            vml on vml.mission_id = m.id
       JOIN validation                     vm on vm.id = vml.validation_id AND vm.histo_destruction IS NULL

  LEFT JOIN volume_horaire_mission        vhm on vhm.mission_id = m.id AND vhm.histo_destruction IS NULL
  LEFT JOIN validation_vol_horaire_miss vvhml on vvhml.volume_horaire_mission_id = vhm.id
       JOIN validation                   vvhm on vvhm.id = vvhml.validation_id AND vvhm.histo_destruction IS NULL

WHERE
  i.histo_destruction IS NULL
  AND si.mission = 1
GROUP BY
  i.annee_id,
  i.id,
  m.id,
  m.structure_id,
  i.structure_id,
  m.auto_validation,
  vm.id