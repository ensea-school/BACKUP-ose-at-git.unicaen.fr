DECLARE
  prevu NUMERIC;
BEGIN                
  prevu := ose_test.get_type_volume_horaire('prevu').id;

  -- SET SERVEROUTPUT ON;
  DBMS_OUTPUT.ENABLE(99999999999999);

  --ose_test.show_succes;
  ose_test.hide_succes;
  ose_test.init;
  FOR i IN (
    SELECT id FROM intervenant
    where 
      histo_destruction IS NULL
      AND exists(select * from service where intervenant_id = intervenant.id)
      AND id=9999999
      AND rownum between 1 and 500
  ) LOOP
    ose_test.echo(' '); ose_test.echo('INTERVENANT_ID = ' || i.id);
    OSE_TEST_FORMULE.TEST_MODIFY_INTERVENANT(i.id);
    OSE_TEST_FORMULE.TEST_MODIFY_SERVICE_DU(i.id);
    OSE_TEST_FORMULE.TEST_MODIFY_SERVICE_DU_MODIF(i.id);
    OSE_TEST_FORMULE.TEST_MODIFY_MOTIF_MOD_SERV(i.id);
    OSE_TEST_FORMULE.TEST_MODIFY_REFERENTIEL(i.id);
    OSE_TEST_FORMULE.TEST_MODIFY_SERVICE(i.id);
    ose_divers.do_nothing;
  END LOOP;

  FOR s IN (
    SELECT id FROM service WHERE
      histo_destruction IS NULL
      AND id=9999999
      --AND id=468
      AND rownum between 1 and 500
  ) LOOP
    ose_test.echo(' ');ose_test.echo('SERVICE_ID = ' || s.id);
    OSE_TEST_FORMULE.TEST_MODIFY_ELEMENT( s.id );
    OSE_TEST_FORMULE.TEST_MODIFY_MODULATEUR( s.id );
    OSE_TEST_FORMULE.TEST_MODIFY_VOLUME_HORAIRE( s.id );
  END LOOP;

  FOR vh IN (
    SELECT id FROM volume_horaire WHERE
      histo_destruction IS NULL
      --AND id=765
      AND id=9999999
      AND rownum between 1 and 500
  ) LOOP
    ose_test.echo(' ');ose_test.echo('VOLUME_HORAIRE_ID = ' || vh.id);
    OSE_TEST_FORMULE.TEST_MODIFY_TYPE_INTERVENTION( vh.id );
    OSE_TEST_FORMULE.TEST_MODIFY_VALIDATION( vh.id );
    OSE_TEST_FORMULE.TEST_MODIFY_CONTRAT( vh.id );
  END LOOP;

  ose_test.show_stats;
END;


/

BEGIN
--  OSE_FORMULE.MAJ_ALL_IDT;

--  OSE_FORMULE.MAJ_RESULTAT( 25839, 2014 );
  OSE_FORMULE.MAJ_ALL;
END;

/

25839 ou 17599

select * from intervenant where source_code = '1058';
SELECT * FROM formule_service_du WHERE intervenant_id = 25839;
SELECT * FROM formule_referentiel WHERE intervenant_id = 25839;
SELECT * FROM formule_service WHERE intervenant_id = 25839;
SELECT * FROM formule_volume_horaire WHERE intervenant_id = 25839;

SELECT * FROM formule_referentiel WHERE intervenant_id = 25839;

SELECT * FROM formule_resultat WHERE intervenant_id = 25839;