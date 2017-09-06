-- export
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
  AND str.source_code <> 'UNICAEN'
;


-- Duplication sur l'année suivante
INSERT INTO etape 
(
  id,
  code,
  libelle,
  annee_id,
  type_formation_id,
  niveau,
  specifique_echanges,
  structure_id,
  domaine_fonctionnel_id,
  source_id,
  source_code,
  histo_creation,
  histo_createur_id,
  histo_modification,
  histo_modificateur_id
)
SELECT 
  etape_id_seq.nextval                                  id,
  e.code                                                code,
  e.libelle                                             libelle,
  2017                                                  annee_id,
  e.type_formation_id                                   type_formation_id,
  e.niveau                                              niveau,
  e.specifique_echanges                                 specifique_echanges,
  e.structure_id                                        structure_id,
  e.domaine_fonctionnel_id                              domaine_fonctionnel_id,
  e.source_id                                           source_id,
  e.source_code                                         source_code,
  sysdate                                               histo_creation,
  (select id from utilisateur where username='lecluse') histo_createur_id,
  sysdate                                               histo_modification,
  (select id from utilisateur where username='lecluse') histo_modificateur_id
FROM 
  etape e
WHERE 
  histo_destruction IS NULL
  AND id IN (
1392,
1393,
1397,
1391,
1394,
1395,
1400,
1631
);

INSERT INTO element_pedagogique (
  id,
  code,
  libelle,
  etape_id,
  structure_id,
  periode_id,
  annee_id,
  discipline_id,
  taux_fi,
  taux_fc,
  taux_fa,
  taux_foad,
  fi,
  fc,
  fa,
  source_id,
  source_code,
  histo_creation,
  histo_createur_id,
  histo_modification,
  histo_modificateur_id
)
SELECT 
  element_pedagogique_id_seq.nextval                    id,
  ep.code                                               code,
  ep.libelle                                            libelle,
  (SELECT id FROM etape WHERE annee_id = a.id AND source_code = e.source_code) etape_id,
  ep.structure_id                                       structure_id,
  ep.periode_id                                         periode_id,
  a.id                                                  annee_id,
  ep.discipline_id                                      discipline_id,
  ep.taux_fi                                            taux_fi,
  ep.taux_fc                                            taux_fc,
  ep.taux_fa                                            taux_fa,
  ep.taux_foad                                          taux_foad,
  ep.fi                                                 fi,
  ep.fc                                                 fc,
  ep.fa                                                 fa,
  ep.source_id                                          source_id,
  ep.source_code                                        source_code,
  sysdate                                               histo_creation,
  (select id from utilisateur where username='lecluse') histo_createur_id,
  sysdate                                               histo_modification,
  (select id from utilisateur where username='lecluse') histo_modificateur_id
FROM 
  element_pedagogique ep
  JOIN etape e ON e.id = ep.etape_id
  JOIN (SELECT 2017 id FROM dual) a ON 1=1
WHERE
  ep.histo_destruction IS NULL
  AND ep.annee_id = a.id - 1
  AND etape_id IN (
1392,
1393,
1397,
1391,
1394,
1395,
1400,
1631
);
delete from element_pedagogique where annee_id = 2017 AND source_code IN (
'VAELICENCEPROS1',
'VAELICENCEPROS2',
'VAEDUTSES1',
'VAEDUTSES2',
'VAEDUTS1',
'VAEDUTS2',
'VAELICENCES1',
'VAELICENCES2',
'VAEMASTER1S1',
'VAEMASTER1S2',
'VAE IngenieurS1',
'VAE IngenieurS2',
'VAEMASTER2 S1',
'VAEMASTER2 S2',
'VAEDSCGS1',
'VAEDSCGS2'
)