CREATE OR REPLACE FORCE VIEW V_NIVEAU_FORMATION AS
SELECT DISTINCT
  CASE
    WHEN 1 <> gtf.pertinence_niveau OR e.niveau IS NULL OR e.niveau < 1 OR gtf.id < 1 THEN NULL
    ELSE gtf.id * 256 + niveau END id,
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
  AND CASE
    WHEN 1 <> gtf.pertinence_niveau OR e.niveau IS NULL OR e.niveau < 1 OR gtf.id < 1 THEN NULL
    ELSE gtf.id * 256 + niveau END IS NOT NULL
ORDER BY
  gtf.libelle_long, e.niveau