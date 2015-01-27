-- ********************************************************************* --
-- *          à faire AVANT avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"', force => TRUE); END; 
/





-- ********************************************************************* --
-- *          à faire APRÈS avoir mis à jour le code source            * --
-- ********************************************************************* --

drop view "OSE"."V_FORMULE_REFERENTIEL";

INSERT INTO VOLUME_HORAIRE_REF (
    ID,
    TYPE_VOLUME_HORAIRE_ID,
    SERVICE_REFERENTIEL_ID,
    HEURES,
    HISTO_CREATION, HISTO_CREATEUR_ID,
    HISTO_MODIFICATION, HISTO_MODIFICATEUR_ID,
    HISTO_DESTRUCTION, HISTO_DESTRUCTEUR_ID
)
SELECT volume_horaire_ref_id_seq.nextval, 1, id, heures, sysdate, 1, sysdate, 1, histo_destruction, histo_destructeur_id FROM service_referentiel;


/
BEGIN DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"'); END;
/
BEGIN OSE_FORMULE.CALCULER_TOUT; END;
/