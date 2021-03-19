CREATE OR REPLACE FORCE VIEW SRC_GROUPE_TYPE_FORMATION AS
SELECT
  gtf.libelle_court     libelle_court,
  gtf.libelle_long      libelle_long,
  gtf.ordre             ordre,
  gtf.pertinence_niveau pertinence_niveau,
  s.id                  source_id,
  gtf.source_code       source_code
FROM
  ose_groupe_type_formation@apoprod gtf
  JOIN source s ON s.code = 'Apogee';