CREATE OR REPLACE FORCE VIEW V_TBL_CHARGENS AS
WITH t AS (
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

    vhe.heures                                                                       heures,
    vhe.heures * ti.taux_hetd_service                                                hetd,

    GREATEST(COALESCE(sns.ouverture, 1),1)                                           ouverture,
    GREATEST(COALESCE(sns.dedoublement, snsetp.dedoublement, csdd.dedoublement,1),1) dedoublement,
    COALESCE(sns.assiduite,1)                                                        assiduite,
    sne.effectif*COALESCE(sns.assiduite,1)                                           effectif,
    SUM(sne.effectif*COALESCE(sns.assiduite,1))
      OVER (PARTITION BY n.noeud_id, sn.scenario_id, ti.id)                          t_effectif,
    AVG(GREATEST(COALESCE(sns.dedoublement, snsetp.dedoublement, csdd.dedoublement,1),1))
      OVER (PARTITION BY n.noeud_id, sn.scenario_id, ti.id)                          t_dedoublement

  FROM
              scenario_noeud_effectif    sne
         JOIN etape                        e ON e.id = sne.etape_id
                                            AND e.histo_destruction IS NULL

         JOIN scenario_noeud              sn ON sn.id = sne.scenario_noeud_id
                                            AND sn.histo_destruction IS NULL
                                            /*@NOEUD_ID=sn.noeud_id*/
                                            /*@SCENARIO_ID=sn.scenario_id*/

         JOIN tbl_noeud                       n ON n.noeud_id = sn.noeud_id
                                            /*@ANNEE_ID=n.annee_id*/
                                            /*@ELEMENT_PEDAGOGIQUE_ID=n.element_pedagogique_id*/
                                            /*@ETAPE_ID=n.element_pedagogique_etape_id*/

         JOIN volume_horaire_ens         vhe ON vhe.element_pedagogique_id = n.element_pedagogique_id
                                            AND vhe.histo_destruction IS NULL
                                            AND vhe.heures > 0

         JOIN type_intervention           ti ON ti.id = vhe.type_intervention_id

    LEFT JOIN scenario_noeud           snetp ON snetp.scenario_id = sn.scenario_id
                                            AND snetp.noeud_id = n.noeud_etape_id
                                            AND snetp.histo_destruction IS NULL

    LEFT JOIN scenario_noeud_seuil    snsetp ON snsetp.scenario_noeud_id = snetp.id
                                            AND snsetp.type_intervention_id = ti.id

    LEFT JOIN tbl_chargens_seuils_def   csdd ON csdd.annee_id = n.annee_id
                                            AND csdd.scenario_id = sn.scenario_id
                                            AND csdd.type_intervention_id = ti.id
                                            AND csdd.groupe_type_formation_id = n.groupe_type_formation_id
                                            AND csdd.structure_id = n.structure_etape_id

    LEFT JOIN scenario_noeud_seuil       sns ON sns.scenario_noeud_id = sn.id
                                            AND sns.type_intervention_id = ti.id
  WHERE
    1=1
    /*@ETAPE_ENS_ID=sne.etape_id*/
)
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
  heures heures_ens,
  --t_effectif,t_dedoublement,

  CASE WHEN t_effectif < ouverture THEN 0 ELSE
    (CEIL(t_effectif / t_dedoublement) * effectif) / t_effectif
  END groupes,

  CASE WHEN t_effectif < ouverture THEN 0 ELSE
    ((CEIL(t_effectif / t_dedoublement) * effectif) / t_effectif) * heures
  END heures,

  CASE WHEN t_effectif < ouverture THEN 0 ELSE
    ((CEIL(t_effectif / t_dedoublement) * effectif) / t_effectif) * hetd
  END  hetd

FROM
  t