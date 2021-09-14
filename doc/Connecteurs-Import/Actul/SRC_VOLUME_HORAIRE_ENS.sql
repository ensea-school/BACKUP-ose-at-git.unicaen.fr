CREATE OR REPLACE FORCE VIEW SRC_VOLUME_HORAIRE_ENS AS
WITH act AS (
SELECT
  COALESCE( h.z_type_intervention_id, g.z_type_intervention_id)    z_type_intervention_id,
  COALESCE(h.heures,0)                                             heures,
  COALESCE(h.z_source_id, g.z_source_id)                           z_source_id,
  COALESCE(h.z_element_pedagogique_id, g.z_element_pedagogique_id) z_element_pedagogique_id,
  COALESCE(g.groupes,0)                                            groupes
FROM
            act_vhens_heures  h
  FULL JOIN act_vhens_groupes g ON g.z_source_id = h.z_source_id
                               AND g.z_type_intervention_id = h.z_type_intervention_id
                               AND g.z_element_pedagogique_id = h.z_element_pedagogique_id
)
SELECT
  ti.id       type_intervention_id,
  act.heures  heures,
  s.id        source_id,
  act.z_element_pedagogique_id || '_' || act.z_type_intervention_id source_code,
  ep.id       element_pedagogique_id,
  act.groupes groupes
FROM
  act
  LEFT JOIN type_intervention ti ON ti.code = act.z_type_intervention_id
  LEFT JOIN source s ON s.code = act.z_source_id
  LEFT JOIN element_pedagogique ep ON ep.source_code = act.z_element_pedagogique_id