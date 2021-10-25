CREATE OR REPLACE FORCE VIEW SRC_SCENARIO_NOEUD_EFFECTIF AS
WITH a AS (
            SELECT z_etape_id, annee_id, 'fi' z_type_heures_id, effectif_fi effectif FROM OSE_ETAPE_EFFECTIFS@apoprod etp WHERE effectif_fi > 0
  UNION ALL SELECT z_etape_id, annee_id, 'fa' z_type_heures_id, effectif_fa effectif FROM OSE_ETAPE_EFFECTIFS@apoprod etp WHERE effectif_fa > 0
  UNION ALL SELECT z_etape_id, annee_id, 'fc' z_type_heures_id, effectif_fc effectif FROM OSE_ETAPE_EFFECTIFS@apoprod etp WHERE effectif_fc > 0
), snem as (
  SELECT
    scenario_noeud_id
  FROM
    scenario_noeud_effectif sne
    JOIN source src ON src.id = sne.source_id
  WHERE
    src.importable = 0
    AND sne.histo_destruction IS NULL
)
SELECT
  sn.id scenario_noeud_id,
  th.id type_heures_id,
  a.effectif,
  e.id etape_id,
  src.id source_id,
  e.annee_id || '_' || e.source_code || '_' || th.code || '_' || s.id source_code
FROM
  a
  JOIN source src ON src.code = 'Apogee'
  JOIN scenario        s ON s.histo_destruction IS NULL
  JOIN type_heures th ON th.code = a.z_type_heures_id
  JOIN etape e ON e.source_code = a.z_etape_id AND e.annee_id = a.annee_id AND e.histo_destruction IS NULL
  JOIN noeud n ON n.etape_id = e.id
  LEFT JOIN scenario_noeud sn ON sn.noeud_id = n.id AND sn.scenario_id = s.id
  LEFT JOIN snem ON snem.scenario_noeud_id = sn.id
WHERE
  snem.scenario_noeud_id IS NULL