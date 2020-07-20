CREATE OR REPLACE VIEW SRC_VOIRIE AS
WITH harpege_query AS (
  SELECT
    c_voie    code,
    l_voie    libelle,
    'Harpege' z_source_id,
    c_voie    source_code
  FROM
    voirie@harpprod str
)
SELECT
  hq.code          code,
  hq.libelle       libelle,
  src.id           source_id,
  hq.source_code   source_code
FROM
       harpege_query hq
  JOIN source       src ON src.code = hq.z_source_id;