
UPDATE service_du SET heures = 194 where intervenant_id = 534;
DELETE FROM "OSE"."SERVICE_DU" where intervenant_id = 534;

select * from service_referentiel where intervenant_id = 534;
select * from formule_referentiel where intervenant_id = 534;
select annee_id, heures, motif_id, histo_destruction from modification_service_du where intervenant_id = 534;
delete from modification_service_du where intervenant_id = 534 AND histo_destruction is not null;
delete from modification_service_du where intervenant_id = 534 AND heures = 9;

delete from formule_referentiel;

SET SERVEROUTPUT ON;

BEGIN
  --OSE_FORMULE.INIT_REFERENTIEL();
  --ose_formule.init_volume_horaire();
  
  --ose_formule.init_service();
  ose_test.echo( 'id=' || ose_test.get_civilite('MME').libelle_long );
  --ose_test.echo( ose_divers.STR_REDUCE('Mm' ) );
  
  --ose_test.echo( NLS_LOWER('Mm', 'NLS_SORT = BINARY_AI') );
  --OSE_TEST_FORMULE.RUN;
END;

/

BEGIN
  ose_test.init;
  ose_test.hide_succes;
  OSE_TEST_FORMULE.set_intervenant( 534 );
  OSE_TEST_FORMULE.RUN;
  ose_test.show_stats;
END;

/

BEGIN

  ose_formule.refresh_referentiel( 534, 2014 );

END;