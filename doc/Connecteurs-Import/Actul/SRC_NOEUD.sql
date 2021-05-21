CREATE OR REPLACE FORCE VIEW SRC_NOEUD AS
SELECT
  n.code                code,
  n.libelle_court       libelle,
  n.liste               liste,
  TO_NUMBER(n.annee_id) annee_id,
  e.id                  etape_id,
  ep.id                 element_pedagogique_id,
  str.id                structure_id,
  s.id                  source_id,
  n.z_source_code       source_code
FROM
            act_noeud           n
       JOIN source                      s ON s.code          = 'Actul'
  LEFT JOIN etape                       e ON e.source_code   = n.z_etape_id
                                         AND e.annee_id      = n.annee_id
  LEFT JOIN element_pedagogique        ep ON ep.source_code  = n.z_element_pedagogique_id
                                         AND ep.annee_id     = n.annee_id
  LEFT JOIN mv_unicaen_structure_codes sc ON sc.c_structure  = n.z_structure_id
  LEFT JOIN structure                 str ON str.source_code = sc.c_structure_n2