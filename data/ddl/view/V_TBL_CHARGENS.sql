CREATE OR REPLACE FORCE VIEW V_TBL_CHARGENS AS
SELECT
  annee_id,
  noeud_id,
  scenario_id,
  type_heures_id,
  type_intervention_id,

  element_pedagogique_id,
  etape_id,
  etape_ens_id,
  structure_id,
  groupe_type_formation_id,

  ouverture,
  dedoublement,
  assiduite,
  effectif,
  heures_ens,
  --t_effectif,t_dedoublement,

  CASE WHEN t_effectif < ouverture THEN 0 ELSE
    (CEIL(t_effectif / dedoublement) * effectif) / t_effectif
  END groupes,

  CASE WHEN t_effectif < ouverture THEN 0 ELSE
    ((CEIL(t_effectif / dedoublement) * effectif) / t_effectif) * heures_ens
  END heures,

  CASE WHEN t_effectif < ouverture THEN 0 ELSE
    ((CEIL(t_effectif / dedoublement) * effectif) / t_effectif) * hetd
  END  hetd

FROM
  (
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
    n.annee_id                                                                       annee_id,
    n.noeud_id                                                                       noeud_id,
    sn.scenario_id                                                                   scenario_id,
    sne.type_heures_id                                                               type_heures_id,
    ti.id                                                                            type_intervention_id,

    n.element_pedagogique_id                                                         element_pedagogique_id,
    n.element_pedagogique_etape_id                                                   etape_id,
    sne.etape_id                                                                     etape_ens_id,
    n.structure_id                                                                   structure_id,
    n.groupe_type_formation_id                                                       groupe_type_formation_id,

    vhe.heures                                                                       heures_ens,
    vhe.heures * ti.taux_hetd_service                                                hetd,

    COALESCE(sep.ouverture, se.ouverture,1)                                          ouverture,
    COALESCE(sep.dedoublement, se.dedoublement, sd.dedoublement,1)                   dedoublement,
    COALESCE(sep.assiduite,1)                                                        assiduite,
    sne.effectif*COALESCE(sep.assiduite,1)                                           effectif,
    SUM(sne.effectif*COALESCE(sep.assiduite,1))
      OVER (PARTITION BY n.noeud_id, sn.scenario_id, ti.id)                          t_effectif
FROM
              scenario_noeud_effectif sne

         JOIN scenario_noeud           sn ON sn.id = sne.scenario_noeud_id
                                         AND sn.histo_destruction IS NULL
                                         /*@NOEUD_ID=sn.noeud_id*/
                                         /*@SCENARIO_ID=sn.scenario_id*/

         JOIN tbl_noeud                 n ON n.noeud_id = sn.noeud_id
                                         /*@ANNEE_ID=n.annee_id*/
                                         /*@ELEMENT_PEDAGOGIQUE_ID=n.element_pedagogique_id*/
                                         /*@ETAPE_ID=n.element_pedagogique_etape_id*/

         JOIN volume_horaire_ens      vhe ON vhe.element_pedagogique_id = n.element_pedagogique_id
                                         AND vhe.histo_destruction IS NULL
                                         AND vhe.heures > 0

         JOIN type_intervention        ti ON ti.id = vhe.type_intervention_id

    LEFT JOIN seuils_perso            sep ON sep.element_pedagogique_id = n.element_pedagogique_id
                                         AND sep.scenario_id = sn.scenario_id
                                         AND sep.type_intervention_id = ti.id

    LEFT JOIN seuils_perso             se ON se.etape_id = n.element_pedagogique_etape_id
                                         AND sep.scenario_id = sn.scenario_id
                                         AND sep.type_intervention_id = ti.id

    LEFT JOIN tbl_chargens_seuils_def  sd ON sd.annee_id = n.annee_id
                                         AND sd.scenario_id = sn.scenario_id
                                         AND sd.structure_id = n.structure_etape_id
                                         AND sd.groupe_type_formation_id = n.groupe_type_formation_id
                                         AND sd.type_intervention_id = ti.id
  WHERE
    1=1
    /*@ETAPE_ENS_ID=sne.etape_id*/
  ) t