CREATE OR REPLACE FORCE VIEW OSE.SRC_EFFECTIFS  AS
  SELECT
  ep.id                                           element_pedagogique_id,
  to_number(e.annee_id)                           annee_id,
  e.effectif_fi                                   fi,
  e.effectif_fc                                   fc,
  e.effectif_fa                                   fa,
  s.id                                            source_id,
  e.annee_id || '-' || e.z_element_pedagogique_id source_code
FROM
       act_element_effectifs e
  JOIN source                        s ON s.code = 'Actul'
  LEFT JOIN element_pedagogique     ep ON ep.source_code = e.z_element_pedagogique_id
                                      AND ep.annee_id = to_number(e.annee_id)
where ep.id is not null