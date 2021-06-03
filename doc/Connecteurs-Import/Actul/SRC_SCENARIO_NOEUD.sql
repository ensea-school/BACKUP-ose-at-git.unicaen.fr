CREATE OR REPLACE FORCE VIEW SRC_SCENARIO_NOEUD AS
WITH a AS (
  SELECT
    'net_' || ae.source_code z_noeud_id,
    'Actul'                  z_source_id
  FROM
    act_etape ae
  WHERE
    -- on n'importe les scénario_noeud que pour les effectifs, donc si null on oublie
    ae.effectif IS NOT NULL
)
SELECT
  sn.id scenario_id,
  n.id noeud_id,
  s.id source_id,
  n.source_code || '_' || sn.id source_code
FROM
  a
  JOIN scenario sn ON sn.histo_destruction IS NULL
  LEFT JOIN noeud n ON n.source_code = a.z_noeud_id
  LEFT JOIN source s ON s.code = a.z_source_id