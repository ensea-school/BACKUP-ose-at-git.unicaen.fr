CREATE OR REPLACE FORCE VIEW SRC_EFFECTIFS AS
SELECT
  ep.id                                           element_pedagogique_id,
  to_number(e.annee_id)                           annee_id,
  e.effectif_fi                                   fi,
  e.effectif_fc                                   fc,
  e.effectif_fa                                   fa,
  s.id                                            source_id,
  e.annee_id || '-' || e.z_element_pedagogique_id source_code
FROM
       ose_element_effectifs@apoprod e
  JOIN source                        s ON s.code = 'Apogee'
  LEFT JOIN element_pedagogique     ep ON ep.source_code = e.z_element_pedagogique_id
                                      AND ep.annee_id = to_number(e.annee_id)