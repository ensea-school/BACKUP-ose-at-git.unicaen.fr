CREATE OR REPLACE PACKAGE BODY "OSE_FORMULE" AS

  TYPE t_lst_vh_etats        IS TABLE OF t_volumes_horaires INDEX BY PLS_INTEGER;
  TYPE t_lst_vh_types        IS TABLE OF t_lst_vh_etats INDEX BY PLS_INTEGER;
  TYPE t_lst_vh_intervenants IS TABLE OF t_lst_vh_types INDEX BY PLS_INTEGER;

  TYPE t_resultat IS RECORD (
    id                         NUMERIC,
    formule_resultat_id        NUMERIC,
    type_volume_horaire_id     NUMERIC,
    etat_volume_horaire_id     NUMERIC,
    service_id                 NUMERIC,
    service_referentiel_id     NUMERIC,
    volume_horaire_id          NUMERIC,
    volume_horaire_ref_id      NUMERIC,

    service_fi                 FLOAT DEFAULT 0,
    service_fa                 FLOAT DEFAULT 0,
    service_fc                 FLOAT DEFAULT 0,
    service_referentiel        FLOAT DEFAULT 0,
    heures_compl_fi            FLOAT DEFAULT 0,
    heures_compl_fa            FLOAT DEFAULT 0,
    heures_compl_fc            FLOAT DEFAULT 0,
    heures_primes              FLOAT DEFAULT 0,
    heures_compl_referentiel   FLOAT DEFAULT 0,

    changed                    BOOLEAN DEFAULT FALSE,
    debug_info                 CLOB
  );

  TYPE t_resultats IS TABLE OF t_resultat INDEX BY VARCHAR2(15);

  all_volumes_horaires t_lst_vh_intervenants;
  arrondi NUMERIC DEFAULT 2;
  t_res t_resultats;
  formule_definition formule%rowtype;
  in_calculer_tout BOOLEAN DEFAULT FALSE;
  view_intervenant CLOB;
  view_volume_horaire CLOB;



  FUNCTION GET_VIEW_INTERVENANT RETURN CLOB IS
  BEGIN
    IF view_intervenant IS NULL THEN
      view_intervenant := ose_divers.GET_VIEW_QUERY('V_FORMULE_INTERVENANT');
    END IF;

    RETURN view_intervenant;
  END;


  FUNCTION GET_VIEW_VOLUME_HORAIRE RETURN CLOB IS
  BEGIN
    IF view_volume_horaire IS NULL THEN
      view_volume_horaire := ose_divers.GET_VIEW_QUERY('V_FORMULE_VOLUME_HORAIRE');
    END IF;

    RETURN view_volume_horaire;
  END;


  FUNCTION MAKE_INTERVENANT_QUERY RETURN CLOB IS
    query CLOB;
  BEGIN
    EXECUTE IMMEDIATE 'SELECT ' || formule_definition.code || '.intervenant_query FROM DUAL' INTO query;
    --query := REPLACE( query, 'V_FORMULE_INTERVENANT', '(' || GET_VIEW_INTERVENANT || ')');

    RETURN query;
  END;


  FUNCTION MAKE_VOLUME_HORAIRE_QUERY RETURN CLOB IS
    query CLOB;
  BEGIN
    EXECUTE IMMEDIATE 'SELECT ' || formule_definition.code || '.volume_horaire_query FROM DUAL' INTO query;
    --query := REPLACE( query, 'V_FORMULE_VOLUME_HORAIRE', '(' || GET_VIEW_VOLUME_HORAIRE || ')');

    RETURN query;
  END;



  PROCEDURE LOAD_INTERVENANT_FROM_TEST IS
    dsdushc NUMERIC DEFAULT 0;
  BEGIN
    intervenant.total := NULL;
    intervenant.solde := NULL;

    SELECT
      fti.id,
      fti.annee_id,
      fti.structure_code,
      fti.type_volume_horaire_id,
      fti.etat_volume_horaire_id,
      fti.heures_service_statutaire,
      fti.heures_service_modifie,
      fti.depassement_service_du_sans_hc,
      fti.service_du,
      fti.param_1,
      fti.param_2,
      fti.param_3,
      fti.param_4,
      fti.param_5,
      ti.code
    INTO
      intervenant.id,
      intervenant.annee_id,
      intervenant.structure_code,
      intervenant.type_volume_horaire_id,
      intervenant.etat_volume_horaire_id,
      intervenant.heures_service_statutaire,
      intervenant.heures_service_modifie,
      dsdushc,
      intervenant.service_du,
      intervenant.param_1,
      intervenant.param_2,
      intervenant.param_3,
      intervenant.param_4,
      intervenant.param_5,
      intervenant.type_intervenant_code
    FROM
      formule_test_intervenant fti
      JOIN type_intervenant ti ON ti.id = fti.type_intervenant_id
    WHERE
      fti.id = intervenant.id;

    intervenant.depassement_service_du_sans_hc := (dsdushc = 1);
    intervenant.service_du := CASE
      WHEN intervenant.depassement_service_du_sans_hc
      THEN 9999
      ELSE intervenant.heures_service_statutaire + intervenant.heures_service_modifie
    END;

    EXCEPTION WHEN NO_DATA_FOUND THEN
      intervenant.id                             := NULL;
      intervenant.annee_id                       := NULL;
      intervenant.structure_code                 := NULL;
      intervenant.heures_service_statutaire      := 0;
      intervenant.depassement_service_du_sans_hc := FALSE;
      intervenant.heures_service_modifie         := 0;
      intervenant.type_intervenant_code          := 'E';
      intervenant.service_du                     := 0;
      intervenant.param_1                        := NULL;
      intervenant.param_2                        := NULL;
      intervenant.param_3                        := NULL;
      intervenant.param_4                        := NULL;
      intervenant.param_5                        := NULL;
  END;



  PROCEDURE LOAD_VH_FROM_TEST IS
    vh t_volume_horaire;
    etat_volume_horaire_id NUMERIC DEFAULT 1;
    LENGTH NUMERIC;
  BEGIN
    volumes_horaires.items.delete;
    LENGTH := 0;

    FOR d IN (
      SELECT
        ftvh.*,
        CASE ftvh.type_intervention_code
          WHEN 'CM' THEN COALESCE(fti.taux_cm_service_du,1.5)
          WHEN 'TP' THEN COALESCE(fti.taux_tp_service_du,1)
          WHEN fti.taux_autre_1_code THEN COALESCE(fti.taux_autre_1_service_du,1)
          WHEN fti.taux_autre_2_code THEN COALESCE(fti.taux_autre_2_service_du,1)
          WHEN fti.taux_autre_3_code THEN COALESCE(fti.taux_autre_3_service_du,1)
          WHEN fti.taux_autre_4_code THEN COALESCE(fti.taux_autre_4_service_du,1)
          WHEN fti.taux_autre_5_code THEN COALESCE(fti.taux_autre_5_service_du,1)
          ELSE 1
        END taux_service_du,
        CASE ftvh.type_intervention_code
          WHEN 'CM' THEN COALESCE(fti.taux_cm_service_compl,1.5)
          WHEN 'TP' THEN COALESCE(fti.taux_tp_service_compl,2/3)
          WHEN fti.taux_autre_1_code THEN COALESCE(fti.taux_autre_1_service_compl,1)
          WHEN fti.taux_autre_2_code THEN COALESCE(fti.taux_autre_2_service_compl,1)
          WHEN fti.taux_autre_3_code THEN COALESCE(fti.taux_autre_3_service_compl,1)
          WHEN fti.taux_autre_4_code THEN COALESCE(fti.taux_autre_4_service_compl,1)
          WHEN fti.taux_autre_5_code THEN COALESCE(fti.taux_autre_5_service_compl,1)
          ELSE 1
        END taux_service_compl,
        tvh.code type_volume_horaire_code
      FROM
        formule_test_volume_horaire ftvh
        JOIN formule_test_intervenant fti ON fti.id = intervenant.id
        JOIN type_volume_horaire tvh ON tvh.id = fti.type_volume_horaire_id
      WHERE  ftvh.formule_intervenant_test_id = intervenant.id
      ORDER BY ftvh.id
    ) LOOP
      LENGTH := LENGTH + 1;
      volumes_horaires.length := LENGTH;

      IF d.referentiel = 0 THEN
        volumes_horaires.items(LENGTH).volume_horaire_id       := d.id;
        volumes_horaires.items(LENGTH).service_id              := d.id;
      ELSE
        volumes_horaires.items(LENGTH).volume_horaire_ref_id   := d.id;
        volumes_horaires.items(LENGTH).service_referentiel_id  := d.id;
      END IF;
      volumes_horaires.items(LENGTH).taux_fi                   := d.taux_fi;
      volumes_horaires.items(LENGTH).taux_fa                   := d.taux_fa;
      volumes_horaires.items(LENGTH).taux_fc                   := d.taux_fc;
      volumes_horaires.items(LENGTH).ponderation_service_du    := d.ponderation_service_du;
      volumes_horaires.items(LENGTH).ponderation_service_compl := d.ponderation_service_compl;
      volumes_horaires.items(LENGTH).structure_is_affectation  := COALESCE(d.structure_code,' ') = COALESCE(intervenant.structure_code,' ');
      volumes_horaires.items(LENGTH).structure_is_univ         := d.structure_code = '__UNIV__';
      volumes_horaires.items(LENGTH).structure_is_exterieur    := d.structure_code = '__EXTERIEUR__';
      volumes_horaires.items(LENGTH).service_statutaire        := d.service_statutaire = 1;
      volumes_horaires.items(LENGTH).heures                    := d.heures;
      volumes_horaires.items(LENGTH).type_volume_horaire_code  := d.type_volume_horaire_code;
      volumes_horaires.items(LENGTH).type_intervention_code    := CASE WHEN d.referentiel = 1 THEN NULL ELSE d.type_intervention_code END;
      volumes_horaires.items(LENGTH).structure_code            := CASE WHEN d.structure_code IN ('__EXTERIEUR__', '__UNIV__') THEN NULL ELSE d.structure_code END;
      volumes_horaires.items(LENGTH).taux_service_du           := d.taux_service_du;
      volumes_horaires.items(LENGTH).taux_service_compl        := d.taux_service_compl;
      volumes_horaires.items(LENGTH).param_1                   := d.param_1;
      volumes_horaires.items(LENGTH).param_2                   := d.param_2;
      volumes_horaires.items(LENGTH).param_3                   := d.param_3;
      volumes_horaires.items(LENGTH).param_4                   := d.param_4;
      volumes_horaires.items(LENGTH).param_5                   := d.param_5;
    END LOOP;
  END;



  PROCEDURE tres_add_heures( code VARCHAR2, vh t_volume_horaire, tvh NUMERIC, evh NUMERIC) IS
  BEGIN
    IF NOT t_res.exists(code) THEN
      t_res(code).service_fi               := 0;
      t_res(code).service_fa               := 0;
      t_res(code).service_fc               := 0;
      t_res(code).service_referentiel      := 0;
      t_res(code).heures_compl_fi          := 0;
      t_res(code).heures_compl_fa          := 0;
      t_res(code).heures_compl_fc          := 0;
      t_res(code).heures_primes            := 0;
      t_res(code).heures_compl_referentiel := 0;
    END IF;

    t_res(code).service_fi               := t_res(code).service_fi               + vh.service_fi;
    t_res(code).service_fa               := t_res(code).service_fa               + vh.service_fa;
    t_res(code).service_fc               := t_res(code).service_fc               + vh.service_fc;
    t_res(code).service_referentiel      := t_res(code).service_referentiel      + vh.service_referentiel;
    t_res(code).heures_compl_fi          := t_res(code).heures_compl_fi          + vh.heures_compl_fi;
    t_res(code).heures_compl_fa          := t_res(code).heures_compl_fa          + vh.heures_compl_fa;
    t_res(code).heures_compl_fc          := t_res(code).heures_compl_fc          + vh.heures_compl_fc;
    t_res(code).heures_primes            := t_res(code).heures_primes            + vh.heures_primes;
    t_res(code).heures_compl_referentiel := t_res(code).heures_compl_referentiel + vh.heures_compl_referentiel;

    t_res(code).type_volume_horaire_id := tvh;
    t_res(code).etat_volume_horaire_id := evh;
  END;





  PROCEDURE SAVE_TO_TEST(passed NUMERIC) IS
    vh t_volume_horaire;
  BEGIN
    UPDATE formule_test_intervenant SET
      service_du = CASE WHEN passed = 1 THEN intervenant.service_du ELSE NULL END
    WHERE id = intervenant.id;

    FOR i IN 1 .. volumes_horaires.length LOOP
      vh := volumes_horaires.items(i);
      UPDATE formule_test_volume_horaire SET
        heures_attendues_service_fi               = CASE WHEN passed = 1 THEN vh.service_fi ELSE NULL END,
        heures_attendues_service_fa               = CASE WHEN passed = 1 THEN vh.service_fa ELSE NULL END,
        heures_attendues_service_fc               = CASE WHEN passed = 1 THEN vh.service_fc ELSE NULL END,
        heures_attendues_service_referentiel      = CASE WHEN passed = 1 THEN vh.service_referentiel ELSE NULL END,
        heures_attendues_compl_fi          = CASE WHEN passed = 1 THEN vh.heures_compl_fi ELSE NULL END,
        heures_attendues_compl_fa          = CASE WHEN passed = 1 THEN vh.heures_compl_fa ELSE NULL END,
        heures_attendues_compl_fc          = CASE WHEN passed = 1 THEN vh.heures_compl_fc ELSE NULL END,
        heures_attendues_primes            = CASE WHEN passed = 1 THEN vh.heures_primes ELSE NULL END,
        heures_attendues_compl_referentiel = CASE WHEN passed = 1 THEN vh.heures_compl_referentiel ELSE NULL END
      WHERE
        id = COALESCE(vh.volume_horaire_id,vh.volume_horaire_ref_id);
    END LOOP;
  END;



  PROCEDURE TEST( INTERVENANT_TEST_ID NUMERIC ) IS
    code VARCHAR2(30);
  BEGIN
    intervenant.id := INTERVENANT_TEST_ID;

    SELECT
      code INTO code
    FROM
      formule f JOIN formule_test_intervenant fti ON fti.formule_id = f.id
    WHERE
      fti.id = intervenant.id;

    LOAD_INTERVENANT_FROM_TEST;
    LOAD_VH_FROM_TEST;
    debug_actif := FALSE;

    BEGIN
      EXECUTE IMMEDIATE 'BEGIN ' || code || '.CALCUL_RESULTAT; END;';
      SAVE_TO_TEST(1);
    EXCEPTION WHEN OTHERS THEN
      SAVE_TO_TEST(0);
      RAISE_APPLICATION_ERROR(-20001, dbms_utility.format_error_backtrace,TRUE);
    END;
  END;



  PROCEDURE TEST_TOUT IS
  BEGIN
    FOR d IN (SELECT id FROM formule_test_intervenant)
    LOOP
      TEST( d.id );
    END LOOP;
  END;




  PROCEDURE DEBUG_INTERVENANT IS
  BEGIN
    ose_test.echo('OSE Formule DEBUG Intervenant');
    ose_test.echo('id                             = ' || intervenant.id);
    ose_test.echo('annee_id                       = ' || intervenant.annee_id);
    ose_test.echo('type_volume_horaire_id         = ' || intervenant.type_volume_horaire_id);
    ose_test.echo('heures_service_statutaire      = ' || intervenant.heures_service_statutaire);
    ose_test.echo('heures_service_modifie         = ' || intervenant.heures_service_modifie);
    ose_test.echo('depassement_service_du_sans_hc = ' || CASE WHEN intervenant.depassement_service_du_sans_hc THEN 'OUI' ELSE 'NON' END);
    ose_test.echo('service_du                     = ' || intervenant.service_du);
  END;

  PROCEDURE DEBUG_VOLUMES_HORAIRES(VOLUME_HORAIRE_ID NUMERIC DEFAULT NULL) IS
    type_volume_horaire_id NUMERIC;
    etat_volume_horaire_id NUMERIC;
    vh t_volume_horaire;
  BEGIN
    ose_test.echo('OSE Formule DEBUG Intervenant');

    type_volume_horaire_id := all_volumes_horaires(intervenant.id).FIRST;
    LOOP EXIT WHEN type_volume_horaire_id IS NULL;
      etat_volume_horaire_id := all_volumes_horaires(intervenant.id)(type_volume_horaire_id).FIRST;
      LOOP EXIT WHEN etat_volume_horaire_id IS NULL;
        ose_test.echo('tvh=' || type_volume_horaire_id || ', evh=' || etat_volume_horaire_id);
        FOR i IN 1 .. all_volumes_horaires(intervenant.id)(type_volume_horaire_id)(etat_volume_horaire_id).length LOOP
          vh := all_volumes_horaires(intervenant.id)(type_volume_horaire_id)(etat_volume_horaire_id).items(i);
          IF VOLUME_HORAIRE_ID IS NULL OR VOLUME_HORAIRE_ID = vh.volume_horaire_id OR VOLUME_HORAIRE_ID = vh.volume_horaire_ref_id THEN
            ose_test.echo('volume_horaire_id         = ' || vh.volume_horaire_id);
            ose_test.echo('volume_horaire_ref_id     = ' || vh.volume_horaire_ref_id);
            ose_test.echo('service_id                = ' || vh.service_id);
            ose_test.echo('service_referentiel_id    = ' || vh.service_referentiel_id);
            ose_test.echo('taux_fi                   = ' || vh.taux_fi);
            ose_test.echo('taux_fa                   = ' || vh.taux_fa);
            ose_test.echo('taux_fc                   = ' || vh.taux_fc);
            ose_test.echo('ponderation_service_du    = ' || vh.ponderation_service_du);
            ose_test.echo('ponderation_service_compl = ' || vh.ponderation_service_compl);
            ose_test.echo('structure_is_affectation  = ' || CASE WHEN vh.structure_is_affectation THEN 'OUI' ELSE 'NON' END);
            ose_test.echo('structure_is_univ         = ' || CASE WHEN vh.structure_is_univ THEN 'OUI' ELSE 'NON' END);
            ose_test.echo('service_statutaire        = ' || CASE WHEN vh.service_statutaire THEN 'OUI' ELSE 'NON' END);
            ose_test.echo('heures                    = ' || vh.heures);
            ose_test.echo('taux_service_du           = ' || vh.taux_service_du);
            ose_test.echo('taux_service_compl        = ' || vh.taux_service_compl);
            ose_test.echo('param_1                   = ' || vh.param_1);
            ose_test.echo('param_2                   = ' || vh.param_2);
            ose_test.echo('param_3                   = ' || vh.param_3);
            ose_test.echo('param_4                   = ' || vh.param_4);
            ose_test.echo('param_5                   = ' || vh.param_5);
            ose_test.echo('service_fi                = ' || vh.service_fi);
            ose_test.echo('service_fa                = ' || vh.service_fa);
            ose_test.echo('service_fc                = ' || vh.service_fc);
            ose_test.echo('service_referentiel       = ' || vh.service_referentiel);
            ose_test.echo('heures_compl_fi           = ' || vh.heures_compl_fi);
            ose_test.echo('heures_compl_fa           = ' || vh.heures_compl_fa);
            ose_test.echo('heures_compl_fc           = ' || vh.heures_compl_fc);
            ose_test.echo('heures_compl_referentiel  = ' || vh.heures_compl_referentiel);
            ose_test.echo('heures_primes             = ' || vh.heures_primes);
            ose_test.echo('');
          END IF;
        END LOOP;
        etat_volume_horaire_id := all_volumes_horaires(intervenant.id)(type_volume_horaire_id).NEXT(etat_volume_horaire_id);
      END LOOP;
      type_volume_horaire_id := all_volumes_horaires(intervenant.id).NEXT(type_volume_horaire_id);
    END LOOP;
  END;

END OSE_FORMULE;