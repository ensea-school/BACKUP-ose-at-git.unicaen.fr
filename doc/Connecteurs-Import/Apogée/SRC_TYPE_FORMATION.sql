CREATE OR REPLACE FORCE VIEW SRC_TYPE_FORMATION AS
SELECT
  tf.libelle_long   libelle_long,
  tf.libelle_court  libelle_court,
  gtf.id            groupe_id,
  s.id              source_id,
  tf.source_code    source_code
FROM
            ose_type_formation@apoprod tf
       JOIN source                      s ON s.code = 'Apogee'
  LEFT JOIN groupe_type_formation     gtf ON gtf.source_code = tf.z_groupe_id