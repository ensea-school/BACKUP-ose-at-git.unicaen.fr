CREATE OR REPLACE FORCE VIEW SRC_ETAPE AS
SELECT
  ae.libelle              libelle,
  tf.id                   type_formation_id,
  ae.niveau               niveau,
  ae.specifique_echanges  specifique_echanges,
  str.id                  structure_id,
  s.id                    source_id,
  ae.source_code          source_code,
  df.id                   domaine_fonctionnel_id,
  ae.annee_id             annee_id,
  ae.code                 code
FROM
            act_etape                 ae
  LEFT JOIN type_formation            tf ON tf.source_code = ae.z_type_formation_id AND tf.histo_destruction IS NULL
  LEFT JOIN unicaen_structure_corresp sc ON sc.cod_cmp = ae.z_structure_id
  LEFT JOIN structure                str ON str.source_code = sc.c_structure_n2 AND str.HISTO_DESTRUCTION IS NULL
  LEFT JOIN source                     s ON s.code = ae.z_source_id
  LEFT JOIN domaine_fonctionnel       df ON df.source_code = ae.z_domaine_fonctionnel_id AND df.HISTO_DESTRUCTION IS NULL
