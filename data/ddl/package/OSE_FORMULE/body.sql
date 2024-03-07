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



  FUNCTION GET_INTERVENANT_ID RETURN NUMERIC IS
  BEGIN
    RETURN intervenant.id;
  END;



  PROCEDURE LOAD_INTERVENANT_FROM_BDD IS
    TYPE t_formule_intervenant IS RECORD (
      intervenant_id                  NUMERIC,
      annee_id                        NUMERIC,
      type_intervenant_code           VARCHAR2(1),
      structure_code                  VARCHAR2(50),
      heures_service_statutaire       FLOAT,
      depassement_service_du_sans_hc  NUMERIC(1),
      heures_service_modifie          FLOAT,
      param_1                         VARCHAR2(100),
      param_2                         VARCHAR2(100),
      param_3                         VARCHAR2(100),
      param_4                         VARCHAR2(100),
      param_5                         VARCHAR2(100)
    );
    formule_intervenant t_formule_intervenant;
    cur SYS_REFCURSOR;
    query CLOB;
    i_dep_service_du_sans_hc NUMERIC DEFAULT 0;

  BEGIN
    intervenant.service_du := 0;
    intervenant.total      := NULL;
    intervenant.solde      := NULL;

    query := MAKE_INTERVENANT_QUERY();
    OPEN cur FOR query;

    LOOP
      FETCH cur INTO formule_intervenant; EXIT WHEN cur%NOTFOUND;
      intervenant.id                             := formule_intervenant.intervenant_id;
      intervenant.annee_id                       := formule_intervenant.annee_id;
      intervenant.structure_code                 := formule_intervenant.structure_code;
      intervenant.type_intervenant_code          := formule_intervenant.type_intervenant_code;
      intervenant.heures_service_statutaire      := formule_intervenant.heures_service_statutaire;
      intervenant.depassement_service_du_sans_hc := (formule_intervenant.depassement_service_du_sans_hc = 1);
      intervenant.heures_service_modifie         := formule_intervenant.heures_service_modifie;
      intervenant.param_1                        := formule_intervenant.param_1;
      intervenant.param_2                        := formule_intervenant.param_2;
      intervenant.param_3                        := formule_intervenant.param_3;
      intervenant.param_4                        := formule_intervenant.param_4;
      intervenant.param_5                        := formule_intervenant.param_5;

      intervenant.service_du := CASE
        WHEN intervenant.depassement_service_du_sans_hc
        THEN 9999
        ELSE intervenant.heures_service_statutaire + intervenant.heures_service_modifie
      END;
    END LOOP;
    CLOSE cur;

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
      fti.a_service_du,
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



  PROCEDURE LOAD_VH_FROM_BDD IS
    cur SYS_REFCURSOR;
    query CLOB;
    vh_ordre NUMERIC;
    vh_id NUMERIC;
    vh_type_intervention_id NUMERIC;
    vh_horaire_debut DATE;
    vh_horaire_fin DATE;
    vh_intervenant_id NUMERIC;
    vh_type_volume_horaire_id NUMERIC;
    vh_etat_volume_horaire_id NUMERIC;
    vh_structure_is_affectation NUMERIC;
    vh_structure_is_univ NUMERIC;
    vh_structure_is_exterieur NUMERIC;
    vh t_volume_horaire;
    etat_volume_horaire_id NUMERIC DEFAULT 1;
    LENGTH NUMERIC;
  BEGIN
    all_volumes_horaires.delete;

    query := MAKE_VOLUME_HORAIRE_QUERY();
    OPEN cur FOR query;

    LOOP
      FETCH cur INTO
        vh_ordre,
        vh_id,
        vh.volume_horaire_id,
        vh.volume_horaire_ref_id,
        vh.service_id,
        vh.service_referentiel_id,
        vh_intervenant_id,
        vh_type_intervention_id,
        vh_type_volume_horaire_id,
        vh_etat_volume_horaire_id,
        vh.type_volume_horaire_code,
        vh.taux_fi,
        vh.taux_fa,
        vh.taux_fc,
        LENGTH, -- on ignore ensuite
        vh.structure_code,
        vh_structure_is_affectation,
        vh_structure_is_univ,
        vh_structure_is_exterieur,
        vh.ponderation_service_du,
        vh.ponderation_service_compl,
        vh.service_statutaire,
        vh.heures,
        LENGTH, -- on ignore ensuite
        vh_horaire_debut,
        vh_horaire_fin,
        vh.type_intervention_code,
        vh.taux_service_du,
        vh.taux_service_compl,
        vh.param_1,
        vh.param_2,
        vh.param_3,
        vh.param_4,
        vh.param_5
      ;
      EXIT WHEN cur%NOTFOUND;

      vh.structure_is_affectation := vh_structure_is_affectation = 1;
      vh.structure_is_univ        := vh_structure_is_univ = 1;
      vh.structure_is_exterieur   := vh_structure_is_exterieur = 1;

      FOR etat_volume_horaire_id IN 1 .. vh_etat_volume_horaire_id LOOP
        BEGIN
          LENGTH := all_volumes_horaires(vh_intervenant_id)(vh_type_volume_horaire_id)(etat_volume_horaire_id).length;
        EXCEPTION WHEN NO_DATA_FOUND THEN
          LENGTH := 0;
        END;
        LENGTH := LENGTH + 1;
        all_volumes_horaires(vh_intervenant_id)(vh_type_volume_horaire_id)(etat_volume_horaire_id).length := LENGTH;
        all_volumes_horaires(vh_intervenant_id)(vh_type_volume_horaire_id)(etat_volume_horaire_id).items(LENGTH) := vh;
      END LOOP;
    END LOOP;
    CLOSE cur;
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
      WHERE  ftvh.intervenant_test_id = intervenant.id
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

  PROCEDURE DEBUG_TRES IS
    code varchar2(15);
    TABLE_NAME varchar2(30);
    fr formule_resultat%rowtype;
    frs formule_resultat_service%rowtype;
    frsr formule_resultat_service_ref%rowtype;
    frvh formule_resultat_vh%rowtype;
    frvhr formule_resultat_vh_ref%rowtype;
  BEGIN
    code := t_res.FIRST;
    LOOP EXIT WHEN code IS NULL;
      TABLE_NAME := CASE
        WHEN code LIKE '%-s-%' THEN 'FORMULE_RESULTAT_SERVICE'
        WHEN code LIKE '%-sr-%' THEN 'FORMULE_RESULTAT_SERVICE_REF'
        WHEN code LIKE '%-vh-%' THEN 'FORMULE_RESULTAT_VH'
        WHEN code LIKE '%-vhr-%' THEN 'FORMULE_RESULTAT_VH_REF'
        ELSE 'FORMULE_RESULTAT'
      END;

      ose_test.echo('T_RES( ' || code || ' - Table ' || TABLE_NAME || ' ) ');
      ose_test.echo('  id = ' || t_res(code).id);
      ose_test.echo('  formule_resultat_id      = ' || t_res(code).formule_resultat_id);
      ose_test.echo('  type_volume_horaire_id   = ' || t_res(code).type_volume_horaire_id);
      ose_test.echo('  etat_volume_horaire_id   = ' || t_res(code).etat_volume_horaire_id);
      ose_test.echo('  volume_horaire_id        = ' || t_res(code).volume_horaire_id);
      ose_test.echo('  volume_horaire_ref_id    = ' || t_res(code).volume_horaire_ref_id);
      ose_test.echo('  service_id               = ' || t_res(code).service_id);
      ose_test.echo('  service_referentiel_id   = ' || t_res(code).service_referentiel_id);
      ose_test.echo('  service_fi               = ' || t_res(code).service_fi);
      ose_test.echo('  service_fa               = ' || t_res(code).service_fa);
      ose_test.echo('  service_fc               = ' || t_res(code).service_fc);
      ose_test.echo('  service_referentiel      = ' || t_res(code).service_referentiel);
      ose_test.echo('  heures_compl_fi          = ' || t_res(code).heures_compl_fi);
      ose_test.echo('  heures_compl_fa          = ' || t_res(code).heures_compl_fa);
      ose_test.echo('  heures_compl_fc          = ' || t_res(code).heures_compl_fc);
      ose_test.echo('  heures_compl_referentiel = ' || t_res(code).heures_compl_referentiel);
      ose_test.echo('  heures_primes            = ' || t_res(code).heures_primes);

      code := t_res.NEXT(code);
    END LOOP;
  END;

  PROCEDURE SAVE_TO_BDD IS
    bcode VARCHAR(15);
    code VARCHAR(15);
    type_volume_horaire_id NUMERIC;
    etat_volume_horaire_id NUMERIC;
    vh t_volume_horaire;
    fr formule_resultat%rowtype;
    frs formule_resultat_service%rowtype;
    frsr formule_resultat_service_ref%rowtype;
    frvh formule_resultat_vh%rowtype;
    frvhr formule_resultat_vh_ref%rowtype;
  BEGIN
    t_res.delete;

    /* On préinitialise avec ce qui existe déjà */
    FOR d IN (
      SELECT
        fr.type_volume_horaire_id || '-' || fr.etat_volume_horaire_id code,
        fr.id                       id,
        fr.id                       formule_resultat_id,
        fr.type_volume_horaire_id   type_volume_horaire_id,
        fr.etat_volume_horaire_id   etat_volume_horaire_id,
        NULL                        service_id,
        NULL                        service_referentiel_id,
        NULL                        volume_horaire_id,
        NULL                        volume_horaire_ref_id

      FROM
        formule_resultat fr
      WHERE
        fr.intervenant_id = intervenant.id

      UNION ALL SELECT
        fr.type_volume_horaire_id || '-' || fr.etat_volume_horaire_id || '-s-' || frs.service_id code,
        frs.id                      id,
        fr.id                       formule_resultat_id,
        fr.type_volume_horaire_id   type_volume_horaire_id,
        fr.etat_volume_horaire_id   etat_volume_horaire_id,
        frs.service_id              service_id,
        NULL                        service_referentiel_id,
        NULL                        volume_horaire_id,
        NULL                        volume_horaire_ref_id
      FROM
        formule_resultat_service frs
        JOIN formule_resultat fr ON fr.id = frs.formule_resultat_id
      WHERE
        fr.intervenant_id = intervenant.id

      UNION ALL SELECT
        fr.type_volume_horaire_id || '-' || fr.etat_volume_horaire_id || '-sr-' || frsr.service_referentiel_id code,
        frsr.id                     id,
        fr.id                       formule_resultat_id,
        fr.type_volume_horaire_id   type_volume_horaire_id,
        fr.etat_volume_horaire_id   etat_volume_horaire_id,
        NULL                        service_id,
        frsr.service_referentiel_id service_referentiel_id,
        NULL                        volume_horaire_id,
        NULL                        volume_horaire_ref_id
      FROM
        formule_resultat_service_ref frsr
        JOIN formule_resultat fr ON fr.id = frsr.formule_resultat_id
      WHERE
        fr.intervenant_id = intervenant.id

      UNION ALL SELECT
        fr.type_volume_horaire_id || '-' || fr.etat_volume_horaire_id || '-vh-' || frvh.volume_horaire_id code,
        frvh.id                     id,
        fr.id                       formule_resultat_id,
        fr.type_volume_horaire_id   type_volume_horaire_id,
        fr.etat_volume_horaire_id   etat_volume_horaire_id,
        NULL                        service_id,
        NULL                        service_referentiel_id,
        frvh.volume_horaire_id      volume_horaire_id,
        NULL                        volume_horaire_ref_id
      FROM
        formule_resultat_vh frvh
        JOIN formule_resultat fr ON fr.id = frvh.formule_resultat_id
      WHERE
        fr.intervenant_id = intervenant.id

      UNION ALL SELECT
        fr.type_volume_horaire_id || '-' || fr.etat_volume_horaire_id || '-vhr-' || frvhr.volume_horaire_ref_id code,
        frvhr.id                    id,
        fr.id                       formule_resultat_id,
        fr.type_volume_horaire_id   type_volume_horaire_id,
        fr.etat_volume_horaire_id   etat_volume_horaire_id,
        NULL                        service_id,
        NULL                        service_referentiel_id,
        NULL                        volume_horaire_id,
        frvhr.volume_horaire_ref_id volume_horaire_ref_id
      FROM
        formule_resultat_vh_ref frvhr
        JOIN formule_resultat fr ON fr.id = frvhr.formule_resultat_id
      WHERE
        fr.intervenant_id = intervenant.id
    ) LOOP
      t_res(d.code).id                     := d.id;
      t_res(d.code).formule_resultat_id    := d.formule_resultat_id;
      t_res(d.code).type_volume_horaire_id := d.type_volume_horaire_id;
      t_res(d.code).etat_volume_horaire_id := d.etat_volume_horaire_id;
      t_res(d.code).service_id             := d.service_id;
      t_res(d.code).service_referentiel_id := d.service_referentiel_id;
      t_res(d.code).volume_horaire_id      := d.volume_horaire_id;
      t_res(d.code).volume_horaire_ref_id  := d.volume_horaire_ref_id;
    END LOOP;

    /* On charge avec les résultats de formule */
    IF all_volumes_horaires.exists(intervenant.id) THEN
      type_volume_horaire_id := all_volumes_horaires(intervenant.id).FIRST;
      LOOP EXIT WHEN type_volume_horaire_id IS NULL;
        etat_volume_horaire_id := all_volumes_horaires(intervenant.id)(type_volume_horaire_id).FIRST;
        LOOP EXIT WHEN etat_volume_horaire_id IS NULL;
          FOR i IN 1 .. all_volumes_horaires(intervenant.id)(type_volume_horaire_id)(etat_volume_horaire_id).length LOOP
            vh := all_volumes_horaires(intervenant.id)(type_volume_horaire_id)(etat_volume_horaire_id).items(i);
            bcode := type_volume_horaire_id || '-' || etat_volume_horaire_id;

            -- formule_resultat
            code := bcode;
            tres_add_heures(code,vh, type_volume_horaire_id, etat_volume_horaire_id);

            -- formule_resultat_service
            IF vh.service_id IS NOT NULL THEN
              code := bcode || '-s-' || vh.service_id;
              t_res(code).service_id := vh.service_id;
              tres_add_heures(code,vh, type_volume_horaire_id, etat_volume_horaire_id);
            END IF;

            -- formule_resultat_service_ref
            IF vh.service_referentiel_id IS NOT NULL THEN
              code := bcode || '-sr-' || vh.service_referentiel_id;
              t_res(code).service_referentiel_id := vh.service_referentiel_id;
              tres_add_heures(code,vh, type_volume_horaire_id, etat_volume_horaire_id);
            END IF;

            -- formule_resultat_volume_horaire
            IF vh.volume_horaire_id IS NOT NULL THEN
              code := bcode || '-vh-' || vh.volume_horaire_id;
              t_res(code).volume_horaire_id := vh.volume_horaire_id;
              tres_add_heures(code,vh, type_volume_horaire_id, etat_volume_horaire_id);
            END IF;

            -- formule_resultat_volume_horaire_ref
            IF vh.volume_horaire_ref_id IS NOT NULL THEN
              code := bcode || '-vhr-' || vh.volume_horaire_ref_id;
              t_res(code).volume_horaire_ref_id := vh.volume_horaire_ref_id;
              tres_add_heures(code,vh, type_volume_horaire_id, etat_volume_horaire_id);
            END IF;

          END LOOP;
          etat_volume_horaire_id := all_volumes_horaires(intervenant.id)(type_volume_horaire_id).NEXT(etat_volume_horaire_id);
        END LOOP;
        type_volume_horaire_id := all_volumes_horaires(intervenant.id).NEXT(type_volume_horaire_id);
      END LOOP;
    END IF;

    /* On fait la sauvegarde en BDD */
    /* D'abord le formule_resultat */
    code := t_res.FIRST;
    LOOP EXIT WHEN code IS NULL;
      IF code = (t_res(code).type_volume_horaire_id || '-' || t_res(code).etat_volume_horaire_id) THEN
        fr.id                       := t_res(code).id;
        fr.intervenant_id           := intervenant.id;
        fr.type_volume_horaire_id   := t_res(code).type_volume_horaire_id;
        fr.etat_volume_horaire_id   := t_res(code).etat_volume_horaire_id;
        fr.service_fi               := ROUND(t_res(code).service_fi,2);
        fr.service_fa               := ROUND(t_res(code).service_fa,2);
        fr.service_fc               := ROUND(t_res(code).service_fc,2);
        fr.service_referentiel      := ROUND(t_res(code).service_referentiel,2);
        fr.heures_compl_fi          := ROUND(t_res(code).heures_compl_fi,2);
        fr.heures_compl_fa          := ROUND(t_res(code).heures_compl_fa,2);
        fr.heures_compl_fc          := ROUND(t_res(code).heures_compl_fc,2);
        fr.heures_primes            := ROUND(t_res(code).heures_primes,2);
        fr.heures_compl_referentiel := ROUND(t_res(code).heures_compl_referentiel,2);
        fr.total := COALESCE(intervenant.total,
              ROUND(
                t_res(code).service_fi + t_res(code).service_fa + t_res(code).service_fc + t_res(code).service_referentiel
                + t_res(code).heures_compl_fi + t_res(code).heures_compl_fa + t_res(code).heures_compl_fc
                + t_res(code).heures_primes + t_res(code).heures_primes
              ,2)
        );

        fr.service_du := ROUND(CASE
          WHEN intervenant.depassement_service_du_sans_hc
          THEN GREATEST(fr.total, intervenant.heures_service_statutaire + intervenant.heures_service_modifie)
          ELSE intervenant.heures_service_statutaire + intervenant.heures_service_modifie
        END,2);

        fr.solde                    := COALESCE(intervenant.solde,fr.total - fr.service_du);
        IF fr.solde >= 0 THEN
          fr.heures_compl           := fr.solde;
        ELSE
          fr.heures_compl           := 0;
        END IF;
        fr.type_intervenant_code    := intervenant.type_intervenant_code;

        IF fr.id IS NULL THEN
          fr.id := formule_resultat_id_seq.nextval;
          t_res(code).id := fr.id;
          INSERT INTO formule_resultat VALUES fr;
        ELSE
          IF fr.SERVICE_DU               IS NULL THEN fr.SERVICE_DU               := 0;  END IF;
          IF fr.HEURES_COMPL_FI          IS NULL THEN fr.HEURES_COMPL_FI          := 0;  END IF;
          IF fr.HEURES_COMPL_FA          IS NULL THEN fr.HEURES_COMPL_FA          := 0;  END IF;
          IF fr.HEURES_COMPL_FC          IS NULL THEN fr.HEURES_COMPL_FC          := 0;  END IF;
          IF fr.HEURES_COMPL_REFERENTIEL IS NULL THEN fr.HEURES_COMPL_REFERENTIEL := 0;  END IF;
          IF fr.HEURES_PRIMES            IS NULL THEN fr.HEURES_PRIMES            := 0;  END IF;
          IF fr.HEURES_COMPL             IS NULL THEN fr.HEURES_COMPL             := 0;  END IF;
          IF fr.SERVICE_FI               IS NULL THEN fr.SERVICE_FI               := 0;  END IF;
          IF fr.SERVICE_FC               IS NULL THEN fr.SERVICE_FC               := 0;  END IF;
          IF fr.SERVICE_FA               IS NULL THEN fr.SERVICE_FA               := 0;  END IF;
          IF fr.SERVICE_REFERENTIEL      IS NULL THEN fr.SERVICE_REFERENTIEL      := 0;  END IF;
          IF fr.TOTAL                    IS NULL THEN fr.TOTAL                    := 0;  END IF;
          IF fr.SOLDE                    IS NULL THEN fr.SOLDE                    := 0;  END IF;
          UPDATE formule_resultat SET ROW = fr WHERE id = fr.id;
        END IF;
      END IF;
      code := t_res.NEXT(code);
    END LOOP;

    --DEBUG_TRES;

    /* Ensuite toutes les dépendances... */
    code := t_res.FIRST;
    LOOP EXIT WHEN code IS NULL;
      bcode := t_res(code).type_volume_horaire_id || '-' || t_res(code).etat_volume_horaire_id;
      CASE
        WHEN code LIKE '%-s-%' THEN -- formule_resultat_service
          frs.id                         := t_res(code).id;
          frs.formule_resultat_id        := t_res(bcode).id;
          frs.service_id                 := t_res(code).service_id;
          frs.service_fi                 := ROUND(t_res(code).service_fi, 2);
          frs.service_fa                 := ROUND(t_res(code).service_fa, 2);
          frs.service_fc                 := ROUND(t_res(code).service_fc, 2);
          frs.heures_compl_fi            := ROUND(t_res(code).heures_compl_fi, 2);
          frs.heures_compl_fa            := ROUND(t_res(code).heures_compl_fa, 2);
          frs.heures_compl_fc            := ROUND(t_res(code).heures_compl_fc, 2);
          frs.heures_primes              := ROUND(t_res(code).heures_primes, 2);
          frs.total                      := ROUND(
                t_res(code).service_fi + t_res(code).service_fa + t_res(code).service_fc
                + t_res(code).heures_compl_fi + t_res(code).heures_compl_fa + t_res(code).heures_compl_fc
                + t_res(code).heures_primes
              ,2);
          IF frs.id IS NULL THEN
            frs.id := formule_resultat_servic_id_seq.nextval;
            INSERT INTO formule_resultat_service VALUES frs;
          ELSE
            IF frs.SERVICE_FI               IS NULL THEN frs.SERVICE_FI               := 0;  END IF;
            IF frs.SERVICE_FC               IS NULL THEN frs.SERVICE_FC               := 0;  END IF;
            IF frs.SERVICE_FA               IS NULL THEN frs.SERVICE_FA               := 0;  END IF;
            IF frs.HEURES_COMPL_FI          IS NULL THEN frs.HEURES_COMPL_FI          := 0;  END IF;
            IF frs.HEURES_COMPL_FA          IS NULL THEN frs.HEURES_COMPL_FA          := 0;  END IF;
            IF frs.HEURES_COMPL_FC          IS NULL THEN frs.HEURES_COMPL_FC          := 0;  END IF;
            IF frs.HEURES_PRIMES            IS NULL THEN frs.HEURES_PRIMES            := 0;  END IF;
            IF frs.TOTAL                    IS NULL THEN frs.TOTAL                    := 0;  END IF;
            UPDATE formule_resultat_service SET ROW = frs WHERE id = frs.id;
          END IF;
        WHEN code LIKE '%-sr-%' THEN -- formule_resultat_service_ref
          frsr.id                        := t_res(code).id;
          frsr.formule_resultat_id       := t_res(bcode).id;
          frsr.service_referentiel_id    := t_res(code).service_referentiel_id;
          frsr.service_referentiel       := ROUND(t_res(code).service_referentiel, 2);
          frsr.heures_compl_referentiel  := ROUND(t_res(code).heures_compl_referentiel, 2);
          frsr.total                     := ROUND(t_res(code).service_referentiel + t_res(code).heures_compl_referentiel,2);
          IF frsr.id IS NULL THEN
            frsr.id := formule_resultat_servic_id_seq.nextval;
            INSERT INTO formule_resultat_service_ref VALUES frsr;
          ELSE
            UPDATE formule_resultat_service_ref SET ROW = frsr WHERE id = frsr.id;
          END IF;
        WHEN code LIKE '%-vh-%' THEN -- formule_resultat_vh
          frvh.id := t_res(code).id;
          frvh.formule_resultat_id       := t_res(bcode).id;
          frvh.volume_horaire_id         := t_res(code).volume_horaire_id;
          frvh.service_fi                := ROUND(t_res(code).service_fi, 2);
          frvh.service_fa                := ROUND(t_res(code).service_fa, 2);
          frvh.service_fc                := ROUND(t_res(code).service_fc, 2);
          frvh.heures_compl_fi           := ROUND(t_res(code).heures_compl_fi, 2);
          frvh.heures_compl_fa           := ROUND(t_res(code).heures_compl_fa, 2);
          frvh.heures_compl_fc           := ROUND(t_res(code).heures_compl_fc, 2);
          frvh.heures_primes             := ROUND(t_res(code).heures_primes, 2);
          frvh.total                     := ROUND(
                t_res(code).service_fi + t_res(code).service_fa + t_res(code).service_fc
                + t_res(code).heures_compl_fi + t_res(code).heures_compl_fa + t_res(code).heures_compl_fc
                + t_res(code).heures_primes
              ,2);
          IF frvh.id IS NULL THEN
            frvh.id := formule_resultat_vh_id_seq.nextval;
            INSERT INTO formule_resultat_vh VALUES frvh;
          ELSE
            IF frvh.SERVICE_FI               IS NULL THEN frvh.SERVICE_FI               := 0;  END IF;
            IF frvh.SERVICE_FC               IS NULL THEN frvh.SERVICE_FC               := 0;  END IF;
            IF frvh.SERVICE_FA               IS NULL THEN frvh.SERVICE_FA               := 0;  END IF;
            IF frvh.HEURES_COMPL_FI          IS NULL THEN frvh.HEURES_COMPL_FI          := 0;  END IF;
            IF frvh.HEURES_COMPL_FA          IS NULL THEN frvh.HEURES_COMPL_FA          := 0;  END IF;
            IF frvh.HEURES_COMPL_FC          IS NULL THEN frvh.HEURES_COMPL_FC          := 0;  END IF;
            IF frvh.HEURES_PRIMES            IS NULL THEN frvh.HEURES_PRIMES            := 0;  END IF;
            IF frvh.TOTAL                    IS NULL THEN frvh.TOTAL                    := 0;  END IF;
            UPDATE formule_resultat_vh SET ROW = frvh WHERE id = frvh.id;
          END IF;
        WHEN code LIKE '%-vhr-%' THEN -- formule_resultat_vh_ref
          frvhr.id := t_res(code).id;
          frvhr.formule_resultat_id      := t_res(bcode).id;
          frvhr.volume_horaire_ref_id    := t_res(code).volume_horaire_ref_id;
          frvhr.service_referentiel      := ROUND(t_res(code).service_referentiel, 2);
          frvhr.heures_compl_referentiel := ROUND(t_res(code).heures_compl_referentiel, 2);
          frvhr.total                    := ROUND(t_res(code).service_referentiel + t_res(code).heures_compl_referentiel,2);
          IF frvhr.id IS NULL THEN
            frvhr.id := formule_resultat_vh_ref_id_seq.nextval;
            INSERT INTO formule_resultat_vh_ref VALUES frvhr;
          ELSE
            UPDATE formule_resultat_vh_ref SET ROW = frvhr WHERE id = frvhr.id;
          END IF;
        ELSE code := code;
      END CASE;
      code := t_res.NEXT(code);
    END LOOP;
  END;



  PROCEDURE SAVE_TO_TEST(passed NUMERIC) IS
    vh t_volume_horaire;
  BEGIN
    UPDATE formule_test_intervenant SET
      c_service_du = CASE WHEN passed = 1 THEN intervenant.service_du ELSE NULL END,
      debug_info = intervenant.debug_info
    WHERE id = intervenant.id;

    FOR i IN 1 .. volumes_horaires.length LOOP
      vh := volumes_horaires.items(i);
      UPDATE formule_test_volume_horaire SET
        c_service_fi               = CASE WHEN passed = 1 THEN vh.service_fi ELSE NULL END,
        c_service_fa               = CASE WHEN passed = 1 THEN vh.service_fa ELSE NULL END,
        c_service_fc               = CASE WHEN passed = 1 THEN vh.service_fc ELSE NULL END,
        c_service_referentiel      = CASE WHEN passed = 1 THEN vh.service_referentiel ELSE NULL END,
        c_heures_compl_fi          = CASE WHEN passed = 1 THEN vh.heures_compl_fi ELSE NULL END,
        c_heures_compl_fa          = CASE WHEN passed = 1 THEN vh.heures_compl_fa ELSE NULL END,
        c_heures_compl_fc          = CASE WHEN passed = 1 THEN vh.heures_compl_fc ELSE NULL END,
        c_heures_primes            = CASE WHEN passed = 1 THEN vh.heures_primes ELSE NULL END,
        c_heures_compl_referentiel = CASE WHEN passed = 1 THEN vh.heures_compl_referentiel ELSE NULL END,
        debug_info                 = vh.debug_info
      WHERE
        id = COALESCE(vh.volume_horaire_id,vh.volume_horaire_ref_id);
    END LOOP;
  END;



  PROCEDURE CALCULER( INTERVENANT_ID NUMERIC ) IS
    type_volume_horaire_id NUMERIC;
    etat_volume_horaire_id NUMERIC;
  BEGIN
    intervenant.id := intervenant_id;

    IF NOT in_calculer_tout THEN
      formule_definition := ose_parametre.get_formule;
    END IF;

    LOAD_INTERVENANT_FROM_BDD;
    IF intervenant.id IS NULL THEN -- intervenant non trouvé
      RETURN;
    END IF;
    IF NOT in_calculer_tout THEN
      LOAD_VH_FROM_BDD;
    END IF;

    debug_actif := FALSE;
    IF all_volumes_horaires.exists(intervenant.id) THEN
      type_volume_horaire_id := all_volumes_horaires(intervenant.id).FIRST;
      LOOP EXIT WHEN type_volume_horaire_id IS NULL;
        intervenant.type_volume_horaire_id := type_volume_horaire_id;
        etat_volume_horaire_id := all_volumes_horaires(intervenant.id)(type_volume_horaire_id).FIRST;
        LOOP EXIT WHEN etat_volume_horaire_id IS NULL;
          intervenant.etat_volume_horaire_id := etat_volume_horaire_id;
          volumes_horaires := all_volumes_horaires(intervenant.id)(type_volume_horaire_id)(etat_volume_horaire_id);
          EXECUTE IMMEDIATE 'BEGIN ' || formule_definition.code || '.CALCUL_RESULTAT; END;';
          all_volumes_horaires(intervenant.id)(type_volume_horaire_id)(etat_volume_horaire_id) := volumes_horaires;
          etat_volume_horaire_id := all_volumes_horaires(intervenant.id)(type_volume_horaire_id).NEXT(etat_volume_horaire_id);
        END LOOP;
        type_volume_horaire_id := all_volumes_horaires(intervenant.id).NEXT(type_volume_horaire_id);
      END LOOP;
    END IF;

    SAVE_TO_BDD;

    OSE_EVENT.ON_AFTER_FORMULE_CALC( CALCULER.INTERVENANT_ID );
  END;

  PROCEDURE CALCULER_TOUT( ANNEE_ID NUMERIC DEFAULT NULL ) IS
    i_id NUMERIC;
  BEGIN
    formule_definition := ose_parametre.get_formule;
    intervenant.id := NULL;
    LOAD_VH_FROM_BDD;

    in_calculer_tout := TRUE;
    i_id := all_volumes_horaires.FIRST;
    LOOP EXIT WHEN i_id IS NULL;
      CALCULER( i_id );
      COMMIT;
      i_id := all_volumes_horaires.NEXT(i_id);
    END LOOP;
    in_calculer_tout := FALSE;
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
    debug_actif := TRUE;

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



  PROCEDURE CALCULER_TBL(param VARCHAR2 DEFAULT NULL, VALUE VARCHAR2 DEFAULT NULL) IS
    intervenant_id NUMERIC;
    TYPE r_cursor IS REF CURSOR;
    diff_cur r_cursor;
  BEGIN
    OPEN diff_cur FOR 'SELECT id FROM intervenant WHERE '
      || unicaen_tbl.MAKE_WHERE( CASE param WHEN 'INTERVENANT_ID' THEN 'ID' ELSE param END, VALUE );
    LOOP
      FETCH diff_cur INTO intervenant_id; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN
        CALCULER( intervenant_id );
      END;
    END LOOP;
    CLOSE diff_cur;
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