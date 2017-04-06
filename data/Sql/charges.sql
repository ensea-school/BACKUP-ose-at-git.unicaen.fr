select * from etape where source_code like '%DEPHAR%';


select * from type_heures;
select * from scenario_lien;
select * from etape where id = 24137;

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

SELECT
  *
FROM
  etape e
WHERE 
  --e.histo_destruction is not null
  e.annee_id = 2016
  AND id in (select etape_id from element_pedagogique ep where ep.histo_destruction is null AND annee_id = 2016);