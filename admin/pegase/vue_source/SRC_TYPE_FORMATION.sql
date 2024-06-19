CREATE OR REPLACE FORCE VIEW SRC_TYPE_FORMATION AS
SELECT
  ptf.libelle_long   libelle_long,
  ptf.libelle_court  libelle_court,
  s.id              source_id,
  ptf.source_code    source_code
FROM
        peg_type_formation ptf
       JOIN source                      s ON s.code = 'Pegase'