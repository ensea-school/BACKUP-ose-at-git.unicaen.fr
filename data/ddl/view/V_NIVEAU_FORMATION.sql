CREATE OR REPLACE FORCE VIEW V_NIVEAU_FORMATION AS
SELECT DISTINCT
  ose_divers.niveau_formation_id_calc( gtf.id, gtf.pertinence_niveau, e.niveau ) id,
  gtf.libelle_court || e.niveau code,
  gtf.libelle_long,
  e.niveau,
  gtf.id groupe_type_formation_id
FROM
  etape e
  JOIN type_formation tf ON tf.id = e.type_formation_id AND tf.histo_destruction IS NULL
  JOIN groupe_type_formation gtf ON gtf.id = tf.groupe_id AND gtf.histo_destruction IS NULL
WHERE
  e.histo_destruction IS NULL
  AND ose_divers.niveau_formation_id_calc( gtf.id, gtf.pertinence_niveau, e.niveau ) IS NOT NULL
ORDER BY
  gtf.libelle_long, e.niveau