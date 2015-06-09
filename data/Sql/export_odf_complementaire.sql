SELECT
  a.libelle         "annee",
  str.libelle_court "structure",
  tf.libelle_court  "type_formation",
  e.source_code     "code_etape",
  e.libelle         "etape",
  e.niveau          "niveau",
  p.libelle_court   "periode",
  ep.source_code    "code_element",
  ep.libelle        "element",
  ep.taux_foad      "taux_foad",
  ep.fi             "fi",
  ep.fc             "fc",
  ep.fa             "fa"
FROM
  source s
       JOIN element_pedagogique ep ON ep.source_id = s.id AND 1 = ose_divers.comprise_entre(ep.histo_creation,ep.histo_destruction)
       JOIN annee                a ON a.id = ep.annee_id
       JOIN structure          str ON str.id = ep.structure_id
       JOIN etape                e ON e.id = ep.etape_id
       JOIN type_formation      tf ON tf.id = e.type_formation_id
  LEFT JOIN periode              p ON p.id = ep.periode_id
WHERE
  s.code = 'OSE'
  AND ep.annee_id = 2014
;




SELECT
  a.libelle         "annee",
  str.source_code str_code,
  str.libelle_court "structure",
  tf.libelle_court  "type_formation",
  e.source_code     "code_etape",
  e.libelle         "etape",
  e.niveau          "niveau",
  p.libelle_court   "periode",
  ep.source_code    "code_element",
  ep.libelle        "element",
  ep.taux_foad      "taux_foad",
  ep.fi             "fi",
  ep.fc             "fc",
  ep.fa             "fa"
FROM
  source s
       JOIN element_pedagogique ep ON ep.source_id = s.id AND 1 = ose_divers.comprise_entre(ep.histo_creation,ep.histo_destruction)
       JOIN annee                a ON a.id = ep.annee_id
       JOIN structure          str ON str.id = ep.structure_id
       JOIN etape                e ON e.id = ep.etape_id
       JOIN type_formation      tf ON tf.id = e.type_formation_id
  LEFT JOIN periode              p ON p.id = ep.periode_id
WHERE
  s.code = 'OSE'
  AND ep.annee_id = 2014
  AND str.source_code <> 'UNICAEN'
;