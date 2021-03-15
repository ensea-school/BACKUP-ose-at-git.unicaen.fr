CREATE OR REPLACE FORCE VIEW SRC_ETAPE AS
SELECT
  e.code                              code,
  e.libelle                           libelle,
  to_number(e.annee_id )              annee_id,
  tf.id                               type_formation_id,
  to_number(e.niveau)                 niveau,
  0                                   specifique_echanges,
  s.id                                structure_id,
  src.id                              source_id,
  e.source_code                       source_code,
  df.id                               domaine_fonctionnel_id
FROM
            fca.ose_etape@fcaprod        e
       JOIN source                     src ON src.code       = 'FCAManager'
  LEFT JOIN type_formation              tf ON tf.source_code = e.z_type_formation_id
  LEFT JOIN SRC_HARPEGE_STRUCTURE_CODES sc ON sc.c_structure = e.z_structure_id
  LEFT JOIN structure                    s ON s.source_code  = sc.c_structure_n2
  LEFT JOIN domaine_fonctionnel         df ON df.source_code = e.z_domaine_fonctionnel_id