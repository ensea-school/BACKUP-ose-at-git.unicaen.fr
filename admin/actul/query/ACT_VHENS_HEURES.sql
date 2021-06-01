SELECT
  z_type_intervention_id,
  SUM(heures) heures,
  'Actul'     z_source_id,
  z_element_pedagogique_id
FROM
(
  SELECT
    ph.typ_heu     z_type_intervention_id,
    ph.nb_heures   heures,
    COALESCE(pep.prev_elp_reference_id,pep.id) z_element_pedagogique_id
  FROM
    PREV_HEUS ph
    JOIN PREV_ELEMENT_PEDAGOGI pep ON pep.id = ph.prev_elp_id
) t
GROUP BY
  z_type_intervention_id,
  z_element_pedagogique_id