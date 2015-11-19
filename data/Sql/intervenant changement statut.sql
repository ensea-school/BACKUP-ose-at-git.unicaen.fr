select * from intervenant where id = 3679;
select * from intervenant where source_code = '34424' AND annee_id = 2014;


update intervenant set statut_id = 4, type_id=1 WHERE id = 3681;

select * from statut_intervenant;
select * from type_intervenant;