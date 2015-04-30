-- ********************************************************************* --
-- *          à faire AVANT avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"', force => TRUE); END;
/



UPDATE adresse_intervenant SET source_code = source_code || '_2014' WHERE source_code IS NOT NULL;
UPDATE chemin_pedagogique SET source_code = source_code || '_2014' WHERE source_code IS NOT NULL;
UPDATE affectation_recherche SET source_code = source_code || '_2014' WHERE source_code IS NOT NULL;
UPDATE element_discipline SET source_code = source_code || '_2014' WHERE source_code IS NOT NULL;
UPDATE element_taux_regimes SET source_code = source_code || '_2014' WHERE source_code IS NOT NULL;
UPDATE TYPE_INTERVENTION_EP SET source_code = source_code || '_2014' WHERE source_code IS NOT NULL;
UPDATE TYPE_MODULATEUR_EP SET source_code = source_code || '_2014' WHERE source_code IS NOT NULL;
UPDATE intervenant_exterieur SET source_code = id;
UPDATE intervenant_permanent SET source_code = id;


update effectifs set annee_id = to_number( substr( source_code, 1, 4) ), source_code = substr( source_code, 6 );

/
BEGIN DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"'); END;
/
BEGIN OSE_FORMULE.CALCULER_TOUT; END;
/