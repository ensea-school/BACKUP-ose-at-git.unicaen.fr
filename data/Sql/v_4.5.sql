-- ********************************************************************* --
-- *          à faire AVANT avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"', force => TRUE); END; 
/


UPDATE departement SET source_code = '0' || source_code WHERE length(source_code) = 2;

update intervenant set pays_naissance_id = 
  (select id from pays where source_code = pays_naissance_code_insee) 
where pays_naissance_code_insee is not null;

update intervenant set pays_nationalite_id = 
  (select id from pays where source_code = pays_nationalite_code_insee) 
where pays_nationalite_code_insee is not null;

update intervenant set dep_naissance_id = 
  (select id from departement where source_code = dep_naissance_code_insee) 
where dep_naissance_code_insee is not null;


-- ********************************************************************* --
-- *          à faire APRÈS avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"'); END;
/
BEGIN OSE_FORMULE.CALCULER_TOUT; END;
/