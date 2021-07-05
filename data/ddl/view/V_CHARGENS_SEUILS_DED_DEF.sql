CREATE OR REPLACE FORCE VIEW V_CHARGENS_SEUILS_DED_DEF AS
WITH seuils_perso AS (
  SELECT
    n.element_pedagogique_id,
    n.etape_id,
    sn.scenario_id,
    sns.type_intervention_id,
    sns.ouverture,
    sns.dedoublement,
    sns.assiduite
  FROM
    scenario_noeud_seuil sns
    JOIN scenario_noeud sn ON sn.id = sns.scenario_noeud_id AND sn.histo_destruction IS NULL
    JOIN noeud n ON n.id = sn.noeud_id
  WHERE
    sns.dedoublement IS NOT NULL
)
SELECT
  n.noeud_id                                                   noeud_id,
  s.id                                                         scenario_id,
  ti.id                                                        type_intervention_id,
  COALESCE(sep.ouverture, se.ouverture)                        ouverture,
  COALESCE(sep.dedoublement, se.dedoublement, sd.dedoublement) dedoublement,
  sep.assiduite                                                assiduite
FROM
            tbl_noeud n
       JOIN scenario                  s ON s.histo_destruction IS NULL
       JOIN type_intervention        ti ON ti.histo_destruction IS NULL
  LEFT JOIN seuils_perso            sep ON sep.element_pedagogique_id = n.element_pedagogique_id
                                       AND sep.scenario_id = s.id
                                       AND sep.type_intervention_id = ti.id

  LEFT JOIN seuils_perso             se ON se.etape_id = n.element_pedagogique_etape_id
                                       AND sep.scenario_id = s.id
                                       AND sep.type_intervention_id = ti.id

  LEFT JOIN tbl_chargens_seuils_def  sd ON sd.annee_id = n.annee_id
                                       AND sd.scenario_id = s.id
                                       AND sd.structure_id = n.structure_etape_id
                                       AND sd.groupe_type_formation_id = n.groupe_type_formation_id
                                       AND sd.type_intervention_id = ti.id