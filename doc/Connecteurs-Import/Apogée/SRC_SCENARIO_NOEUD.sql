CREATE OR REPLACE FORCE VIEW SRC_SCENARIO_NOEUD AS
SELECT
  s.id                         scenario_id,
  n.id                         noeud_id,
  src.id                       source_id,
  n.source_code || '_' || s.id source_code
FROM
            noeud            n
       JOIN scenario         s ON s.histo_destruction IS NULL
       JOIN source         src ON src.code = 'Apogee'
  LEFT JOIN scenario_noeud sno ON sno.scenario_id = s.id AND sno.noeud_id = n.id
  LEFT JOIN source         sns ON sns.id = sno.source_id
WHERE
  n.etape_id IS NOT NULL
  AND n.histo_destruction IS NULL
  AND COALESCE(sns.importable,1) = 1