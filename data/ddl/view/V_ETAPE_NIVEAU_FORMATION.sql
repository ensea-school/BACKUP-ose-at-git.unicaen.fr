CREATE OR REPLACE FORCE VIEW V_ETAPE_NIVEAU_FORMATION AS
SELECT
  e.id etape_id,
  nf.id niveau_formation_id
FROM
  etape e
  JOIN type_formation tf ON tf.id = e.type_formation_id AND tf.histo_destruction IS NULL
  JOIN groupe_type_formation gtf ON gtf.id = tf.groupe_id AND gtf.histo_destruction IS NULL
  JOIN v_niveau_formation nf ON nf.code = gtf.libelle_court || e.niveau
WHERE
  e.histo_destruction IS NULL
  AND gtf.pertinence_niveau = 1
  AND e.niveau IS NOT NULL