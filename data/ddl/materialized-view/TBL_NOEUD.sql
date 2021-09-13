SELECT
  n.id                     noeud_id,
  en.id                    noeud_etape_id,
  n.code                   code,
  n.libelle                libelle,
  n.annee_id               annee_id,
  n.etape_id               etape_id,
  n.element_pedagogique_id element_pedagogique_id,
  etp.id                   element_pedagogique_etape_id,
  n.structure_id           structure_id,
  tf.groupe_id             groupe_type_formation_id,
  etp.structure_id         structure_etape_id
FROM
            noeud                n
  LEFT JOIN element_pedagogique ep ON ep.id = n.element_pedagogique_id
  LEFT JOIN etape              etp ON etp.id = COALESCE(n.etape_id,ep.etape_id)
  LEFT JOIN type_formation      tf ON tf.id = etp.type_formation_id
  LEFT JOIN noeud               en ON en.etape_id = etp.id
WHERE
  n.histo_destruction IS NULL