-- ********************************************************************* --
-- *          à faire AVANT avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"', force => TRUE); END; 
/

update element_pedagogique set fi = 0 WHERE fi IS NULL;
update element_pedagogique set fc = 0 WHERE fc IS NULL;
update element_pedagogique set fa = 0 WHERE fa IS NULL;

INSERT INTO "OSE"."PARAMETRE" (ID, NOM, VALEUR, DESCRIPTION, VALIDITE_DEBUT, HISTO_CREATION, HISTO_CREATEUR_ID, HISTO_MODIFICATION, HISTO_MODIFICATEUR_ID) 
  VALUES ('9', 'formule_package_name', 'OSE_FORMULE', 'Nom du package contenant la formule de calcul', TO_DATE('2014-11-03 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), TO_DATE('2014-11-03 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), '1', TO_DATE('2014-11-03 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), '1');

INSERT INTO "OSE"."PARAMETRE" (ID, NOM, VALEUR, DESCRIPTION, VALIDITE_DEBUT, HISTO_CREATION, HISTO_CREATEUR_ID, HISTO_MODIFICATION, HISTO_MODIFICATEUR_ID) 
  VALUES ('10', 'formule_procedure_name', 'CALCUL_UNICAEN_V2', 'Nom de la procédure permettant d''exécuter la formule de calcul', TO_DATE('2014-11-03 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), TO_DATE('2014-11-03 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), '1', TO_DATE('2014-11-03 00:00:00', 'YYYY-MM-DD HH24:MI:SS'), '1');



-- ********************************************************************* --
-- *          à faire APRÈS avoir mis à jour le code source            * --
-- ********************************************************************* --


BEGIN DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"'); END; 
/