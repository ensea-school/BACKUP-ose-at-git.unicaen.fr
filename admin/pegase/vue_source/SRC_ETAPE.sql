CREATE OR REPLACE FORCE VIEW SRC_ETAPE AS
SELECT
  pe.libelle              libelle,
  tf.id                   type_formation_id,
  pe.niveau               niveau,
  str.id                  structure_id,
  s.id                    source_id,
  pe.source_code          source_code,
  df.id                   domaine_fonctionnel_id,
  a.id             annee_id,
  pe.code                 code
FROM
             peg_etape                   pe
  JOIN       source s ON s.code = 'Pegase'
  JOIN       annee a ON a.id between pe.annee_debut and pe.annee_fin
  LEFT JOIN type_formation              tf ON tf.source_code = pe.z_type_formation_id AND tf.histo_destruction IS NULL
  LEFT JOIN structure                  str ON str.autre_1 = pe.z_structure_id AND str.HISTO_DESTRUCTION IS NULL
  LEFT JOIN domaine_fonctionnel         df ON df.source_code = pe.z_domaine_fonctionnel_id AND df.HISTO_DESTRUCTION IS NULL

