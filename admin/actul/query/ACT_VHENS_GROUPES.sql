SELECT
  z_type_intervention_id,
  'Actul'      z_source_id,
  z_element_pedagogique_id,
  SUM(groupes) groupes
FROM
(
  SELECT
    peg.typ_heu     z_type_intervention_id,
    COALESCE(pep.prev_elp_reference_id,pep.id) z_element_pedagogique_id,
    peg.nb_gpes    groupes
  FROM
    PREV_ELP_CALC_NB_GPES peg
    JOIN PREV_ELEMENT_PEDAGOGI pep ON pep.id = peg.prev_elp_id
) t
GROUP BY
  z_type_intervention_id,
  z_element_pedagogique_id