select * from noeud where code = '2MSOC1_711';
select 
  n.id n_id, n.code, n.libelle, ti.code ti_code, vhe.heures
from 
  noeud n
  LEFT JOIN element_pedagogique ep on ep.id = n.element_pedagogique_id
  LEFT JOIN volume_horaire_ens         vhe ON vhe.element_pedagogique_id = ep.id
                                          AND vhe.histo_destruction IS NULL 
                                          AND vhe.heures > 0 
  LEFT JOIN type_intervention           ti ON ti.id = vhe.type_intervention_id 
where 
  n.id IN (257933,239198,244766,246574)
ORDER BY
  code, ti_code;

--CREATE OR REPLACE VIEW V_CHARGENS_PRECALCUL_HEURES
AS WITH sne AS (
  SELECT
    sne.scenario_noeud_id,
    sne.etape_id,
    SUM(sne.effectif) effectif
  FROM
    scenario_noeud_effectif sne
    JOIN type_heures th ON th.id = sne.type_heures_id AND th.code = 'fi'
  GROUP BY
    sne.scenario_noeud_id,
    sne.etape_id
)
SELECT
  nep.annee_id      annee_id,
  nep.id            noeud_ep_id,
  netp.id           noeud_etape_id,
  ep.id             element_pedagogique_id,
  sne.etape_id      etape_id,
  ep.structure_id   structure_id,
  ti.id             type_intervention_id,
  sn.scenario_id    scenario_id,
  --COALESCE(sns.ouverture, 1) ouverture,
  --COALESCE(sns.dedoublement, csdd.dedoublement,1) dedoublement,
  --COALESCE(sne.effectif,0)*COALESCE(sns.assiduite,1) effectif,
  --CASE WHEN COALESCE(sne.effectif,0) < COALESCE(sns.ouverture, 1) THEN 0 ELSE
  --  CEIL( COALESCE(sne.effectif,0) / GREATEST(COALESCE(sns.dedoublement, csdd.dedoublement,1),1) )
  --END groupes,
  CASE WHEN COALESCE(sne.effectif,0)*COALESCE(sns.assiduite,1) < COALESCE(sns.ouverture, 1) THEN 0 ELSE
    CEIL( COALESCE(sne.effectif,0)*COALESCE(sns.assiduite,1) / GREATEST(COALESCE(sns.dedoublement, csdd.dedoublement,1),1) )
  END * vhe.heures                        heures,
  CASE WHEN COALESCE(sne.effectif,0)*COALESCE(sns.assiduite,1) < COALESCE(sns.ouverture, 1) THEN 0 ELSE
    CEIL( COALESCE(sne.effectif,0)*COALESCE(sns.assiduite,1) / GREATEST(COALESCE(sns.dedoublement, csdd.dedoublement,1),1) )
  END * vhe.heures * ti.taux_hetd_service hetd
FROM
            noeud                      nep
       JOIN element_pedagogique         ep ON ep.id = nep.element_pedagogique_id
       JOIN volume_horaire_ens         vhe ON vhe.element_pedagogique_id = ep.id
                                          AND vhe.histo_destruction IS NULL 
                                          AND vhe.heures > 0

       JOIN type_intervention           ti ON ti.id = vhe.type_intervention_id

       JOIN scenario_noeud              sn ON sn.noeud_id = nep.id 
                                          AND sn.histo_destruction IS NULL

  LEFT JOIN sne                       sne  ON sne.scenario_noeud_id = sn.id
                                          AND sne.etape_id = ep.etape_id
  
  LEFT JOIN v_chargens_seuils_ded_def csdd ON csdd.noeud_id = nep.id 
                                          AND csdd.scenario_id = sn.scenario_id
                                          AND csdd.type_intervention_id = ti.id

  LEFT JOIN scenario_noeud_seuil       sns ON sns.scenario_noeud_id = sn.id 
                                          AND sns.type_intervention_id = ti.id

  LEFT JOIN noeud                     netp ON netp.etape_id = ep.etape_id
WHERE
  nep.histo_destruction IS NULL;


--CREATE OR REPLACE VIEW V_CHARGENS_PRECALCUL_HEURES AS
WITH t AS (
SELECT
  nep.annee_id                                        annee_id,
  nep.id                                              noeud_ep_id,
  netp.id                                             noeud_etape_id,
  ep.id                                               element_pedagogique_id,
  sne.etape_id                                        etape_id,
  ep.structure_id                                     structure_id,
  ti.id                                               type_intervention_id,
  sn.scenario_id                                      scenario_id,
  ti.taux_hetd_service                                taux_hetd,
--  COALESCE(sns.ouverture, 1)                          ouverture,
  GREATEST(COALESCE(sns.dedoublement, csdd.dedoublement,1),1) dedoublement,
--  COALESCE(sne.effectif,0)*COALESCE(sns.assiduite,1)  effectif,

--  ROUND(SUM(COALESCE(sne.effectif,0)*COALESCE(sns.assiduite,1)) OVER (PARTITION BY nep.id, ti.id)) sum_effectif,

  vhe.heures                                          vhe_heures,

  CASE WHEN COALESCE(sne.effectif,0)*COALESCE(sns.assiduite,1) < COALESCE(sns.ouverture, 1) THEN 0 ELSE
    CEIL( COALESCE(sne.effectif,0)*COALESCE(sns.assiduite,1) / GREATEST(COALESCE(sns.dedoublement, csdd.dedoublement,1),1) )
  END                                                 groupes_etp,

  SUM(CASE WHEN COALESCE(sne.effectif,0)*COALESCE(sns.assiduite,1) < COALESCE(sns.ouverture, 1) THEN 0 ELSE
    CEIL( COALESCE(sne.effectif,0)*COALESCE(sns.assiduite,1) / GREATEST(COALESCE(sns.dedoublement, csdd.dedoublement,1),1) )
  END) OVER (PARTITION BY nep.id, ti.id,sn.scenario_id)              sum_groupes_etp,

  CEIL(SUM(COALESCE(sne.effectif,0)*COALESCE(sns.assiduite,1)) OVER (PARTITION BY nep.id, ti.id,sn.scenario_id)) effectifs_total
FROM
            noeud                      nep
       JOIN element_pedagogique         ep ON ep.id = nep.element_pedagogique_id
       JOIN volume_horaire_ens         vhe ON vhe.element_pedagogique_id = ep.id
                                          AND vhe.histo_destruction IS NULL 
                                          AND vhe.heures > 0

       JOIN type_intervention           ti ON ti.id = vhe.type_intervention_id

       JOIN scenario_noeud              sn ON sn.noeud_id = nep.id 
                                          AND sn.histo_destruction IS NULL

  LEFT JOIN (
    SELECT sne.scenario_noeud_id, sne.etape_id, SUM(sne.effectif) effectif
    FROM scenario_noeud_effectif sne
    JOIN type_heures th ON th.id = sne.type_heures_id AND th.code = 'fi'
    GROUP BY sne.scenario_noeud_id, sne.etape_id
  )                                    sne ON sne.scenario_noeud_id = sn.id

  LEFT JOIN v_chargens_seuils_ded_def csdd ON csdd.noeud_id = nep.id 
                                          AND csdd.scenario_id = sn.scenario_id
                                          AND csdd.type_intervention_id = ti.id

  LEFT JOIN scenario_noeud_seuil       sns ON sns.scenario_noeud_id = sn.id 
                                          AND sns.type_intervention_id = ti.id

  LEFT JOIN noeud                     netp ON netp.etape_id = ep.etape_id
WHERE
  nep.histo_destruction IS NULL
)
SELECT
  annee_id,
  noeud_ep_id,
  noeud_etape_id,
  element_pedagogique_id,
  etape_id,
  structure_id,
  type_intervention_id,
  scenario_id,
  groupes_etp * vhe_heures * CASE WHEN sum_groupes_etp = 0 THEN 1 ELSE effectifs_total / LEAST(dedoublement,effectifs_total) / sum_groupes_etp END heures,
  groupes_etp * vhe_heures * taux_hetd * CASE WHEN sum_groupes_etp = 0 THEN 1 ELSE effectifs_total / LEAST(dedoublement,effectifs_total) / sum_groupes_etp END hetd
  ,groupes_etp, vhe_heures, effectifs_total, dedoublement, sum_groupes_etp
FROM t
WHERE noeud_ep_id = 242635
;

 -- AND nep.id IN (257933,239198,244766,246574)
--  AND sn.scenario_id = 1

SELECT
  sne.*
FROM
  scenario_noeud_effectif sne
  JOIN scenario_noeud sn ON sn.id = sne.scenario_noeud_id
WHERE
  sn.histo_destruction IS NULL
  AND sn.scenario_id = 1
  --AND sn.noeud_id = 257933
  AND sn.noeud_id = 239198
;




















CREATE OR REPLACE VIEW V_CHARGENS_PRECALCUL_HEURES AS 
WITH t AS (
SELECT
  n.annee_id          annee_id,
  n.id                noeud_id,
  sn.scenario_id      scenario_id,
  sne.type_heures_id  type_heures_id,
  ti.id               type_intervention_id,

  ep.id               element_pedagogique_id,
  ep.etape_id         etape_id,
  ep.structure_id     structure_id,

  vhe.heures          heures,
  vhe.heures * ti.taux_hetd_service hetd,

  GREATEST(COALESCE(sns.ouverture, 1),1)                      ouverture,
  GREATEST(COALESCE(sns.dedoublement, csdd.dedoublement,1),1) dedoublement,
  sne.effectif*COALESCE(sns.assiduite,1)                      effectif,

  SUM(sne.effectif*COALESCE(sns.assiduite,1)) OVER (PARTITION BY n.id, sn.scenario_id, ti.id) t_effectif

FROM
            scenario_noeud_effectif    sne
       JOIN type_heures                 th ON th.id = sne.type_heures_id
                                          AND th.code = 'fi' 

       JOIN scenario_noeud              sn ON sn.id = sne.scenario_noeud_id
                                          AND sn.histo_destruction IS NULL

       JOIN noeud                        n ON n.id = sn.noeud_id
                                          AND n.histo_destruction IS NULL

       JOIN element_pedagogique         ep ON ep.id = n.element_pedagogique_id
       JOIN volume_horaire_ens         vhe ON vhe.element_pedagogique_id = ep.id
                                          AND vhe.histo_destruction IS NULL 
                                          AND vhe.heures > 0

       JOIN type_intervention           ti ON ti.id = vhe.type_intervention_id

  LEFT JOIN v_chargens_seuils_ded_def csdd ON csdd.noeud_id = n.id 
                                          AND csdd.scenario_id = sn.scenario_id
                                          AND csdd.type_intervention_id = ti.id

  LEFT JOIN scenario_noeud_seuil       sns ON sns.scenario_noeud_id = sn.id 
                                          AND sns.type_intervention_id = ti.id
)
SELECT
  annee_id,
  noeud_id,
  scenario_id,
  type_heures_id,
  type_intervention_id,

  element_pedagogique_id,
  etape_id,
  structure_id,

  --ouverture,
  --dedoublement,
  --effectif,
  --t_effectif,

  --CASE WHEN t_effectif < ouverture THEN 0 ELSE
  --  CEIL( t_effectif / dedoublement ) * effectif / t_effectif
  --END groupes,

  CASE WHEN t_effectif < ouverture THEN 0 ELSE
    CEIL( t_effectif / dedoublement ) * heures * effectif / t_effectif
  END heures,

  CASE WHEN t_effectif < ouverture THEN 0 ELSE
    CEIL( t_effectif / dedoublement ) * hetd * effectif / t_effectif
  END  hetd

FROM
  t
;