select id,annee_id,code, libelle,structure_id from element_pedagogique order by id desc;
delete from element_pedagogique where id = 187015;
select * from structure where niveau = 2;
SELECT
  *
FROM element_pedagogique

WHERE
  annee_id = 2016
  AND source_code in (
'REMED-11',
'REMED-12',
'REMED-13',
'REMED-I11',
'REMED-I12',
'REMED-I13',
'REMED-U01',
'REMED-U07',
'REMED-U24'

  )
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
2494,
2495,
1465,
1472,
1473,
1460,
1463,
1464,
1471
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
2494,
2495,
1465,
1472,
1473,
1460,
1463,
1464,
1471
);

select * from chemin_pedagogique;


-- création des chemins!!
INSERT INTO chemin_pedagogique (
  id,
  element_pedagogique_id,
  etape_id,
  ordre,
  source_id,
  source_code,
  histo_creation,
  histo_createur_id,
  histo_modification,
  histo_modificateur_id
)
SELECT
  chemin_pedagogique_id_seq.nextval                             id,
  ep.id                                                         element_pedagogique_id,
  e.id                                                          etape_id,
  rownum                                                        ordre,
  s.id                                                          source_id,
  e.source_code || '_' || ep.source_code || '_' || ep.annee_id  source_code,
  sysdate                                                       histo_creation,
  (SELECT id FROM utilisateur WHERE username = 'lecluse')       histo_createur_id,
  sysdate                                                       histo_modification,
  (SELECT id FROM utilisateur WHERE username = 'lecluse')       histo_modificateur_id
FROM
       source                   s
  JOIN element_pedagogique     ep ON ep.histo_destruction IS NULL AND ep.source_id = s.id
  JOIN etape                    e ON e.id = ep.etape_id AND e.histo_destruction IS NULL
  LEFT JOIN chemin_pedagogique cp ON cp.element_pedagogique_id = ep.id
WHERE
  s.code = 'OSE'
  AND cp.id IS NULL
;
  
  
