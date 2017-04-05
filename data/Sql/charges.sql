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
  csdd.scenario_id  scenario_id,
  --COALESCE(sns.ouverture, 1) ouverture,
  --COALESCE(sns.dedoublement, csdd.dedoublement,1) dedoublement,
  --COALESCE(sne.effectif,0) effectif,
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
       JOIN volume_horaire_ens         vhe ON vhe.element_pedagogique_id = nep.element_pedagogique_id 
                                          AND vhe.histo_destruction IS NULL 
                                          AND vhe.heures > 0

       JOIN type_intervention           ti ON ti.id = vhe.type_intervention_id
       JOIN element_pedagogique         ep ON ep.id = vhe.element_pedagogique_id
  LEFT JOIN noeud                     netp ON netp.etape_id = ep.etape_id
  LEFT JOIN v_chargens_seuils_ded_def csdd ON csdd.noeud_id = nep.id 
                                          AND csdd.type_intervention_id = ti.id

  LEFT JOIN scenario_noeud              sn ON sn.noeud_id = nep.id 
                                          AND sn.scenario_id = csdd.scenario_id

  LEFT JOIN scenario_noeud_seuil       sns ON sns.scenario_noeud_id = sn.id 
                                          AND sns.type_intervention_id = ti.id

  LEFT JOIN                            sne ON sne.scenario_noeud_id = sn.id 
                                          AND sne.etape_id = ep.etape_id
WHERE
  nep.histo_destruction IS NULL
;






-- contrôles  
select 'select' import_action, count(*) from etape where histo_destruction is null
union select import_action, count(*) from v_diff_etape group by import_action;

select 'select' import_action, annee_id, count(*) from element_pedagogique where histo_destruction is null group by annee_id
union select import_action, annee_id, count(*) from v_diff_element_pedagogique group by import_action, annee_id;

select 'select' import_action, count(*) from chemin_pedagogique where histo_destruction is null
union select import_action, count(*) from v_diff_chemin_pedagogique group by import_action;

select 'select' import_action, count(*) from volume_horaire_ens where histo_destruction is null
union select import_action, count(*) from v_diff_volume_horaire_ens group by import_action;

select 'select' import_action, count(*) from lien where histo_destruction is null
union select import_action, count(*) from v_diff_lien group by import_action;

select 'select' import_action, count(*) from noeud where histo_destruction is null
union select import_action, count(*) from v_diff_noeud group by import_action;



select * from v_diff_element_pedagogique where import_action = 'delete';

select * from src_element_pedagogique WHERE source_code like '%MOB341%';
select * from element_pedagogique WHERE source_code like '%MOB341%';
select annee_id, count(*) from ose_element_pedagogique2@apoprod group by annee_id;
select annee_id, count(*) from element_pedagogique where histo_destruction is null group by annee_id;