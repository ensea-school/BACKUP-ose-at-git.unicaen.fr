-- ********************************************************************* --
-- *          à faire AVANT avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"', force => TRUE); END; 
/

update element_pedagogique set fi = 0 WHERE fi IS NULL;
update element_pedagogique set fc = 0 WHERE fc IS NULL;
update element_pedagogique set fa = 0 WHERE fa IS NULL;



-- ********************************************************************* --
-- *          à faire APRÈS avoir mis à jour le code source            * --
-- ********************************************************************* --


BEGIN DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"'); END; 
/