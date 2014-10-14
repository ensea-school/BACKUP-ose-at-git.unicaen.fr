
UPDATE service_du SET heures = 194 where intervenant_id = 534;
DELETE FROM "OSE"."SERVICE_DU" where intervenant_id = 534;

select * from service_du where intervenant_id = 534;
select * from formule_referentiel where intervenant_id = 534;
select * from modification_service_du where intervenant_id = 534;
select modification_service_du_id_seq.nextval from dual;

SET SERVEROUTPUT ON;

/
BEGIN
  OSE_FORMULE.REFRESH_REFERENTIEL_LINE(534,2014);
END;
/

BEGIN
  OSE_TEST_FORMULE.RUN;
END;

/
