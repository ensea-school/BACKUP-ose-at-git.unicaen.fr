select * from noeud where code = 'M.1SE61';

WITH sne AS (
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
  nep.id            noeud_ep_id,
  netp.id           noeud_etape_id,
  ep.id             element_pedagogique_id,
  ep.etape_id       etape_id,
  ep.structure_id   structure_id,
  ti.id             type_intervention_id,
  sn.scenario_id    scenario_id,
  --COALESCE(sns.ouverture, 1) ouverture,
  --COALESCE(sns.dedoublement, csdd.dedoublement,1) dedoublement,
  COALESCE(snep.effectif,0) effectif,
  --CASE WHEN COALESCE(sne.effectif,0) < COALESCE(sns.ouverture, 1) THEN 0 ELSE
  --  CEIL( COALESCE(sne.effectif,0) / COALESCE(sns.dedoublement, csdd.dedoublement,1) )
  --END groupes,
  CASE WHEN COALESCE(sne.effectif,0) < COALESCE(sns.ouverture, 1) THEN 0 ELSE
    CEIL( COALESCE(sne.effectif,0) / COALESCE(sns.dedoublement, csdd.dedoublement,1) )
  END * vhe.heures                        heures,
  CASE WHEN COALESCE(sne.effectif,0) < COALESCE(sns.ouverture, 1) THEN 0 ELSE
    CEIL( COALESCE(sne.effectif,0) / COALESCE(sns.dedoublement, csdd.dedoublement,1) )
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

  LEFT JOIN noeud                     netp ON netp.etape_id = ep.etape_id

  LEFT JOIN scenario_noeud           snetp ON snetp.noeud_id = netp.id 
                                          AND snetp.histo_destruction IS NULL
                                          AND snetp.scenario_id = sn.scenario_id

  LEFT JOIN scenario_noeud_seuil    snsetp ON snsetp.scenario_noeud_id = snetp.id 
                                          AND snsetp.type_intervention_id = ti.id

WHERE
  nep.histo_destruction IS NULL
  AND nep.id = 245981;
  
  select * from noeud where id = 245981;