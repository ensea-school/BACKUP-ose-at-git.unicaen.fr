CREATE OR REPLACE FORCE VIEW SRC_VOLUME_HORAIRE_ENS AS
SELECT
  ti.id       type_intervention_id,
  pvh.heures  heures,
  s.id        source_id,
  pvh.z_element_pedagogique_id || '_' || pvh.z_type_intervention_id || '_' || ep.annee_id source_code,
  ep.id       element_pedagogique_id,
  pvh.groupes groupes
FROM
  peg_volume_horaire pvh
  JOIN annee a ON a.id BETWEEN pvh.annee_debut AND pvh.annee_fin
  LEFT JOIN type_intervention ti ON ti.source_code = pvh.z_type_intervention_id
  LEFT JOIN source s ON s.code = 'Pegase'
  LEFT JOIN element_pedagogique ep ON ep.source_code = pvh.z_element_pedagogique_id
                                   AND ep.annee_id = a.id