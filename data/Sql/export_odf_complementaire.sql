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
  ep.libelle      libelle,
  e.source_code   etape,
  str.source_code structure,
  p.libelle_court periode,
  ep.taux_foad    taux_foad,
  ep.fi           fi,
  ep.fc           fc,
  ep.fa           fa,
  ep.taux_fi      taux_fi,
  ep.taux_fc      taux_fc,
  ep.taux_fa      taux_fa,
  ep.source_code  source_code
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
  AND e.source_code IN ('PRL-I11','PRL-I12','PRL-I13','PRL-U01','PRL-U07','PRL-U09','PRL-U14','PRL-U24','PRL-U25')
  
  ;
  
Les étapes PRL-U** et éléments REMED-U** à prolonger sont :
PRL-U01 / REMED-U01
PRL-U07 / REMED-U07
--PRL-I11 / REMED-I11
--PRL-I12 / REMED-I12
--PRL-I13 / REMED-I13
PRL-U09 / REMED-U09
PRL-U14 / REMED-U14
PRL-U24 / REMED-U24
PRL-U25 / REMED-U25