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

  CASE WHEN t_effectif < ouverture OR dedoublement = 0 OR t_effectif = 0 THEN 0 ELSE
    ROUND((CEIL(t_effectif / dedoublement) * effectif) / t_effectif,10)
  END groupes,

  CASE WHEN t_effectif < ouverture OR dedoublement = 0 OR t_effectif = 0 THEN 0 ELSE
    ROUND(((CEIL(t_effectif / dedoublement) * effectif) / t_effectif) * heures_ens,10)
  END heures,

  CASE WHEN t_effectif < ouverture OR dedoublement = 0 OR t_effectif = 0 THEN 0 ELSE
    ROUND(((CEIL(t_effectif / dedoublement) * effectif) / t_effectif) * hetd,10)
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
    n.id                                                                             noeud_id,
    sn.scenario_id                                                                   scenario_id,
    sne.type_heures_id                                                               type_heures_id,
    ti.id                                                                            type_intervention_id,

    n.element_pedagogique_id                                                         element_pedagogique_id,
    etp.id                                                                           etape_id,
    sne.etape_id                                                                     etape_ens_id,
    n.structure_id                                                                   structure_id,
    tf.groupe_id                                                                     groupe_type_formation_id,

    vhe.heures                                                                       heures_ens,
    vhe.heures * ti.taux_hetd_service                                                hetd,

    COALESCE(sep.ouverture, se.ouverture,1)                                          ouverture,
    COALESCE(sep.dedoublement, se.dedoublement, sd.dedoublement,1)                   dedoublement,
    COALESCE(sep.assiduite,1)                                                        assiduite,
    sne.effectif*COALESCE(sep.assiduite,1)                                           effectif,
    SUM(sne.effectif*COALESCE(sep.assiduite,1)) OVER (PARTITION BY n.id, sn.scenario_id, ti.id) t_effectif
FROM
              scenario_noeud_effectif sne

         JOIN scenario_noeud           sn ON sn.id = sne.scenario_noeud_id
                                         AND sn.histo_destruction IS NULL
                                         /*@noeud_id=sn.noeud_id*/
                                         /*@scenario_id=sn.scenario_id*/



         JOIN noeud                     n ON n.id = sn.noeud_id
                                         AND n.histo_destruction IS NULL
                                         /*@annee_id=n.annee_id*/
                                         /*@element_pedagogique_id=n.element_pedagogique_id*/

         JOIN volume_horaire_ens      vhe ON vhe.element_pedagogique_id = n.element_pedagogique_id
                                         AND vhe.histo_destruction IS NULL
                                         AND vhe.heures > 0

         JOIN type_intervention        ti ON ti.id = vhe.type_intervention_id
    LEFT JOIN element_pedagogique      ep ON ep.id = n.element_pedagogique_id
    LEFT JOIN etape                   etp ON etp.id = COALESCE(n.etape_id,ep.etape_id)
                                         /*@etape_id=etp.id*/

    LEFT JOIN type_formation           tf ON tf.id = etp.type_formation_id

    LEFT JOIN seuils_perso            sep ON sep.element_pedagogique_id = n.element_pedagogique_id
                                         AND sep.scenario_id = sn.scenario_id
                                         AND sep.type_intervention_id = ti.id

    LEFT JOIN seuils_perso             se ON se.etape_id = etp.id
                                         AND se.scenario_id = sn.scenario_id
                                         AND se.type_intervention_id = ti.id

    LEFT JOIN tbl_chargens_seuils_def  sd ON sd.annee_id = n.annee_id
                                         AND sd.scenario_id = sn.scenario_id
                                         AND sd.structure_id = etp.structure_id
                                         AND sd.groupe_type_formation_id = tf.groupe_id
                                         AND sd.type_intervention_id = ti.id
  WHERE
    1=1
    /*@etape_ens_id=sne.etape_id*/
    /*@etape_id=etp.id*/
  ) t