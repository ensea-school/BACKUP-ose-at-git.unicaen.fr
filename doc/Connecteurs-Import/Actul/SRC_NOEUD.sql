CREATE OR REPLACE FORCE VIEW src_noeud AS
-- noeuds & listes pour les étapes
SELECT
  nl.code || 'et_' || ae.code        code,
  ae.libelle                         libelle,
  nl.liste                           liste,
  ae.annee_id                        annee_id,
  CASE WHEN nl.liste = 1 THEN NULL ELSE e.id END etape_id,
  null                               element_pedagogique_id,
  s.id                               source_id,
  nl.code || 'et_' || ae.source_code source_code,
  str.id                             structure_id
FROM
       act_etape                      ae
  LEFT JOIN etape                      e ON e.source_code = ae.source_code
  LEFT JOIN unicaen_structure_corresp sc ON sc.cod_cmp = ae.z_structure_id
  LEFT JOIN structure                str ON str.source_code = sc.c_structure_n2
  JOIN source                          s ON s.code = ae.z_source_id
  JOIN (
    SELECT 'n' code, 0 liste FROM dual UNION ALL SELECT 'l' code, 1 liste FROM dual
  )                                   nl ON 1=1

UNION ALL

-- noeuds & listes pour les éléments pédagogiques branches, noeuds sans listes pour les feuilles, le tout hors références
SELECT
  nl.code || 'ep_' || ao.code        code,
  ao.libelle                         libelle,
  nl.liste                           liste,
  ao.annee_id                        annee_id,
  null                               etape_id,
  ep.id                              element_pedagogique_id,
  s.id                               source_id,
  nl.code || 'ep_' || ao.source_code source_code,
  str.id                             structure_id
FROM
  act_odf                             ao
  LEFT JOIN element_pedagogique       ep ON ep.source_code = ao.source_code
  LEFT JOIN unicaen_structure_corresp sc ON sc.cod_cmp = ao.z_structure_id
  LEFT JOIN structure                str ON str.source_code = sc.c_structure_n2
  JOIN source                          s ON s.code = ao.z_source_id
  JOIN (
    SELECT 'n' code, 0 liste FROM dual UNION ALL SELECT 'l' code, 1 liste FROM dual
  )                                   nl ON nl.code = 'n' OR ep.id IS NULL
WHERE
  element_ref_id IS NULL