CREATE OR REPLACE FORCE VIEW src_noeud AS
-- noeuds pour les étapes
SELECT
  ae.code                            code,
  ae.libelle                         libelle,
  0                                  liste,
  ae.annee_id                        annee_id,
  e.id                               etape_id,
  null                               element_pedagogique_id,
  s.id                               source_id,
  'net_' || ae.source_code           source_code,
  str.id                             structure_id
FROM
       act_etape                      ae
  LEFT JOIN etape                      e ON e.source_code = ae.source_code
  LEFT JOIN unicaen_structure_corresp sc ON sc.cod_cmp = ae.z_structure_id
  LEFT JOIN structure                str ON str.source_code = sc.c_structure_n2
  JOIN source                          s ON s.code = ae.z_source_id

UNION ALL

-- listes pour les étapes
SELECT
  'let_' || ae.code                  code,
  ae.libelle                         libelle,
  1                                  liste,
  ae.annee_id                        annee_id,
  null                               etape_id,
  null                               element_pedagogique_id,
  s.id                               source_id,
  'let_' || ae.source_code           source_code,
  str.id                             structure_id
FROM
       act_etape                      ae
  LEFT JOIN etape                      e ON e.source_code = ae.source_code
  LEFT JOIN unicaen_structure_corresp sc ON sc.cod_cmp = ae.z_structure_id
  LEFT JOIN structure                str ON str.source_code = sc.c_structure_n2
  JOIN source                          s ON s.code = ae.z_source_id

UNION ALL

-- tous les noeuds
SELECT
  an.code                            code,
  an.libelle                         libelle,
  0                                  liste,
  an.annee_id                        annee_id,
  null                               etape_id,
  ep.id                              element_pedagogique_id,
  s.id                               source_id,
  'nep_' || an.source_code           source_code,
  str.id                             structure_id
FROM
  act_noeud                           an
  LEFT JOIN element_pedagogique       ep ON ep.source_code = an.source_code
  LEFT JOIN unicaen_structure_corresp sc ON sc.cod_cmp = an.z_structure_id
  LEFT JOIN structure                str ON str.source_code = sc.c_structure_n2
  JOIN source                          s ON s.code = an.z_source_id

UNION ALL

-- listes pour les éléments pédagogiques branches, noeuds sans listes pour les feuilles
SELECT
  'lep_' || an.code                  code,
  an.libelle                         libelle,
  1                                  liste,
  an.annee_id                        annee_id,
  null                               etape_id,
  null                               element_pedagogique_id,
  s.id                               source_id,
  'lep_' || an.source_code           source_code,
  str.id                             structure_id
FROM
  act_noeud                           an
  JOIN (SELECT DISTINCT z_noeud_sup_id FROM act_lien) al ON al.z_noeud_sup_id = an.source_code
  LEFT JOIN unicaen_structure_corresp sc ON sc.cod_cmp = an.z_structure_id
  LEFT JOIN structure                str ON str.source_code = sc.c_structure_n2
  JOIN source                          s ON s.code = an.z_source_id