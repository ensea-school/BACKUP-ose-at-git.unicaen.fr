CREATE OR REPLACE FORCE VIEW V_CHARGENS_SEUILS_DED_DEF AS
WITH tisc AS (
  SELECT DISTINCT
    sc.type_intervention_id,
    sc.scenario_id
  FROM
    seuil_charge sc
  WHERE
    sc.histo_destruction IS NULL
)
SELECT
  n.noeud_id noeud_id,
  tisc.scenario_id,
  tisc.type_intervention_id,
  COALESCE(snsetp.dedoublement, tcsd.dedoublement) dedoublement
FROM
            tbl_noeud                  n
       JOIN                         tisc ON 1=1

  LEFT JOIN scenario_noeud         snetp ON snetp.noeud_id = n.noeud_etape_id
                                        AND snetp.scenario_id = tisc.scenario_id
                                        AND snetp.histo_destruction IS NULL

  LEFT JOIN scenario_noeud_seuil  snsetp ON snsetp.scenario_noeud_id = snetp.id
                                        AND snsetp.type_intervention_id = tisc.type_intervention_id

  LEFT JOIN tbl_chargens_seuils_def tcsd ON tcsd.annee_id = n.annee_id
                                        AND tcsd.scenario_id = tisc.scenario_id
                                        AND tcsd.groupe_type_formation_id = n.groupe_type_formation_id
                                        AND tcsd.type_intervention_id = tisc.type_intervention_id
WHERE
  COALESCE(snsetp.dedoublement, tcsd.dedoublement)  IS NOT NULL