CREATE OR REPLACE FORCE VIEW SRC_SCENARIO_NOEUD_EFFECTIF AS
WITH a AS (
  SELECT
    'net_' || ae.source_code z_noeud_id,
    CASE
      -- effectifs en fi, à défaut fc à défaut fa
      WHEN ae.fi = 1 THEN 'fi'
      WHEN ae.fc = 1 THEN 'fc'
      WHEN ae.fa = 1 THEN 'fa'
      ELSE 'fi'
    END                      z_type_heures_id,
    ae.effectif              effectif,
    ae.source_code           z_etape_id,
    'Actul'                  z_source_id
  FROM
    act_etape ae
  WHERE
    ae.effectif IS NOT NULL
)
SELECT
  sn.id scenario_noeud_id,
  th.id type_heures_id,
  a.effectif,
  e.id etape_id,
  src.id source_id,
  a.z_noeud_id || '_' || s.id || '_' || th.code source_code
FROM
                            a
       JOIN scenario        s ON s.histo_destruction IS NULL
  LEFT JOIN scenario_noeud sn ON sn.source_code = a.z_noeud_id || '_' || s.id
  LEFT JOIN source        src ON src.code = a.z_source_id
  LEFT JOIN etape           e ON e.source_code = a.z_etape_id
  LEFT JOIN type_heures th ON th.code = a.z_type_heures_id