CREATE OR REPLACE FORCE VIEW SRC_ETAPE AS
SELECT
  e.cod_etp || '_' || e.cod_vrs_vet   code,
  e.libelle                           libelle,
  to_number(e.annee_id)               annee_id,
  tf.id                               type_formation_id,
  CASE WHEN gtf.pertinence_niveau = 0 THEN null ELSE to_number(e.niveau) END niveau,
  e.specifique_echanges               specifique_echanges,
  s.id                                structure_id,
  src.id                              source_id,
  e.source_code                       source_code,
  df.id                               domaine_fonctionnel_id
FROM
            ose_etape@apoprod            e
       JOIN source                     src ON src.code       = 'Apogee'
  LEFT JOIN type_formation              tf ON tf.source_code = e.z_type_formation_id
  LEFT JOIN groupe_type_formation      gtf ON gtf.id = tf.groupe_id
  LEFT JOIN SRC_HARPEGE_STRUCTURE_CODES sc ON sc.c_structure = e.z_structure_id
  LEFT JOIN structure                    s ON s.source_code  = sc.c_structure_n2
  LEFT JOIN domaine_fonctionnel         df ON df.source_code = e.domaine_fonctionnel