CREATE OR REPLACE PACKAGE BODY "OSE_CHARGENS" AS
  SCENARIO NUMERIC;
  NOEUD NUMERIC;
  old_enable BOOLEAN DEFAULT TRUE;

  TYPE T_PRECALC_HEURES_PARAMS IS RECORD (
    annee_id                       NUMERIC DEFAULT NULL,
    structure_id                   NUMERIC DEFAULT NULL,
    scenario_id                    NUMERIC DEFAULT NULL,
    type_heures_id                 NUMERIC DEFAULT NULL,
    etape_id                       NUMERIC DEFAULT NULL,
    noeud_ids                      tnoeud_ids DEFAULT NULL
  );

  PRECALC_HEURES_PARAMS T_PRECALC_HEURES_PARAMS;


  FUNCTION GET_SCENARIO RETURN NUMERIC IS
  BEGIN
    RETURN OSE_CHARGENS.SCENARIO;
  END;

  PROCEDURE SET_SCENARIO( SCENARIO NUMERIC ) IS
  BEGIN
    OSE_CHARGENS.SCENARIO := SET_SCENARIO.SCENARIO;
  END;



  FUNCTION GET_NOEUD RETURN NUMERIC IS
  BEGIN
    RETURN OSE_CHARGENS.NOEUD;
  END;

  PROCEDURE SET_NOEUD( NOEUD NUMERIC ) IS
  BEGIN
    OSE_CHARGENS.NOEUD := SET_NOEUD.NOEUD;
  END;





  FUNCTION CALC_COEF( choix_min NUMERIC, choix_max NUMERIC, poids NUMERIC, max_poids NUMERIC, total_poids NUMERIC, nb_choix NUMERIC ) RETURN FLOAT IS
    cmin NUMERIC;
    cmax NUMERIC;
    coef_choix FLOAT;
    coef_poids FLOAT;
    max_coef_poids FLOAT;
    correcteur FLOAT DEFAULT 1;
    res FLOAT;
  BEGIN
    cmin := choix_min;
    cmax := choix_max;

    IF total_poids = 0 THEN RETURN 0; END IF;

    IF cmax IS NULL OR cmax > nb_choix THEN
      cmax := nb_choix;
    END IF;
    IF cmin IS NULL THEN
      cmin := nb_choix;
    ELSIF cmin > cmax THEN
      cmin := cmax;
    END IF;

      coef_choix := (cmin + cmax) / 2 / nb_choix;

      coef_poids := poids / total_poids;

      max_coef_poids := max_poids / total_poids;

      IF (coef_choix * nb_choix * max_coef_poids) <= 1 THEN
        res := coef_choix * nb_choix * coef_poids;
      ELSE
        correcteur := 1;
        res := coef_choix * nb_choix * (coef_poids + (((1/nb_choix)-coef_poids)*correcteur));
      END IF;

      --ose_test.echo('choix_min= ' || cmin || ', choix_max= ' || cmax || ', poids = ' || poids || ', max_poids = ' || max_poids || ', total_poids = ' || total_poids || ', nb_choix = ' || nb_choix || ', RES = ' || res);
      RETURN res;
  END;


  PROCEDURE DEM_CALC_SUB_EFFECTIF( scenario_noeud_id NUMERIC, type_heures_id NUMERIC, etape_id NUMERIC, effectif FLOAT ) IS
  BEGIN
    INSERT INTO TMP_scenario_noeud_effectif(
      scenario_noeud_id, type_heures_id, etape_id, effectif
    ) VALUES(
      scenario_noeud_id, type_heures_id, etape_id, effectif
    );
  END;



  PROCEDURE CALC_SUB_EFFECTIF_DEM IS
  BEGIN
    DELETE FROM TMP_scenario_noeud_effectif;
  END;


  PROCEDURE CALC_ALL_EFFECTIFS IS
  BEGIN
    FOR p IN (

      SELECT
        sn.noeud_id,
        sn.scenario_id,
        sne.type_heures_id,
        sne.etape_id
      FROM
        scenario_noeud_effectif sne
        JOIN scenario_noeud sn ON sn.id = sne.scenario_noeud_id
        JOIN noeud n ON n.id = sn.noeud_id
      WHERE
        n.etape_id IS NOT NULL

    ) LOOP

      CALC_SUB_EFFECTIF2( p.noeud_id, p.scenario_id, p.type_heures_id, p.etape_id );
    END LOOP;

  END;



  PROCEDURE CALC_EFFECTIF(
    noeud_id       NUMERIC,
    scenario_id    NUMERIC,
    type_heures_id NUMERIC DEFAULT NULL,
    etape_id       NUMERIC DEFAULT NULL
  ) IS
    snid  NUMERIC;
  BEGIN
    UPDATE scenario_noeud_effectif SET effectif = 0
    WHERE
      scenario_noeud_id = (
        SELECT id FROM scenario_noeud WHERE noeud_id = CALC_EFFECTIF.noeud_id AND scenario_id = CALC_EFFECTIF.scenario_id
      )
      AND (type_heures_id = CALC_EFFECTIF.type_heures_id OR CALC_EFFECTIF.type_heures_id IS NULL)
      AND (etape_id = CALC_EFFECTIF.etape_id OR CALC_EFFECTIF.etape_id IS NULL)
    ;

    FOR p IN (

      SELECT
        *
      FROM
        v_chargens_calc_effectif cce
      WHERE
        cce.noeud_id = CALC_EFFECTIF.noeud_id
        AND cce.scenario_id = CALC_EFFECTIF.scenario_id
        AND (cce.type_heures_id = CALC_EFFECTIF.type_heures_id OR CALC_EFFECTIF.type_heures_id IS NULL)
        AND (cce.etape_id = CALC_EFFECTIF.etape_id OR CALC_EFFECTIF.etape_id IS NULL)

    ) LOOP
      snid := OSE_CHARGENS.GET_SCENARIO_NOEUD_ID( p.scenario_id, p.noeud_id );
      IF snid IS NULL THEN
        snid := OSE_CHARGENS.CREER_SCENARIO_NOEUD( p.scenario_id, p.noeud_id );
      END IF;
      ADD_SCENARIO_NOEUD_EFFECTIF( snid, p.type_heures_id, p.etape_id, p.effectif );
    END LOOP;
    CALC_SUB_EFFECTIF2( noeud_id, scenario_id, type_heures_id, etape_id );
  END;



  PROCEDURE CALC_SUB_EFFECTIF2( noeud_id NUMERIC, scenario_id NUMERIC, type_heures_id NUMERIC DEFAULT NULL, etape_id NUMERIC DEFAULT NULL) IS
  BEGIN
    FOR p IN (

      SELECT *
      FROM   V_CHARGENS_GRANDS_LIENS cgl
      WHERE  cgl.noeud_sup_id = CALC_SUB_EFFECTIF2.noeud_id

    ) LOOP
      CALC_EFFECTIF( p.noeud_inf_id, scenario_id, type_heures_id, etape_id );
    END LOOP;
  END;



  PROCEDURE DUPLIQUER( source_id NUMERIC, destination_id NUMERIC, utilisateur_id NUMERIC, structure_id NUMERIC, noeuds VARCHAR2 DEFAULT '', liens VARCHAR2 DEFAULT '' ) IS
  BEGIN

    /* Destruction de tous les liens antérieurs de la destination */
    DELETE FROM
      scenario_lien
    WHERE
      scenario_id = DUPLIQUER.destination_id
      AND histo_destruction IS NULL
      AND (DUPLIQUER.LIENS IS NULL OR DUPLIQUER.LIENS LIKE '%,' || lien_id || ',%' )
      AND (DUPLIQUER.STRUCTURE_ID IS NULL OR lien_id IN (
        SELECT id FROM lien WHERE lien.structure_id = DUPLIQUER.STRUCTURE_ID
      ))
    ;

    /* Duplication des liens */
    INSERT INTO scenario_lien (
      id,
      scenario_id, lien_id,
      actif, poids,
      choix_minimum, choix_maximum,
      source_id, source_code,
      histo_creation, histo_createur_id,
      histo_modification, histo_modificateur_id
    ) SELECT
      scenario_lien_id_seq.nextval,
      DUPLIQUER.destination_id, sl.lien_id,
      sl.actif, sl.poids,
      sl.choix_minimum, sl.choix_maximum,
      source.id, 'dupli_' || sl.id || '_' || sl.lien_id || '_' || trunc(dbms_random.value(1,10000000000000)),
      sysdate, DUPLIQUER.utilisateur_id,
      sysdate, DUPLIQUER.utilisateur_id
    FROM
      scenario_lien sl
      JOIN lien l ON l.id = sl.lien_id
      JOIN source ON source.code = 'OSE'
    WHERE
      sl.scenario_id = DUPLIQUER.source_id
      AND sl.histo_destruction IS NULL
      AND (DUPLIQUER.LIENS IS NULL OR DUPLIQUER.LIENS LIKE '%,' || lien_id || ',%' )
      AND (DUPLIQUER.STRUCTURE_ID IS NULL OR l.structure_id = DUPLIQUER.STRUCTURE_ID)
    ;


    /* Destruction de tous les noeuds antérieurs de la destination */
    DELETE FROM
      scenario_noeud
    WHERE
      scenario_id = DUPLIQUER.destination_id
      AND histo_destruction IS NULL
      AND (DUPLIQUER.NOEUDS IS NULL OR DUPLIQUER.NOEUDS LIKE '%,' || noeud_id || ',%' )
      AND (DUPLIQUER.STRUCTURE_ID IS NULL OR scenario_noeud.noeud_id IN (
        SELECT id FROM noeud WHERE noeud.structure_id = DUPLIQUER.STRUCTURE_ID
      ))
    ;

    /* Duplication des noeuds */
    INSERT INTO scenario_noeud (
      id,
      scenario_id, noeud_id,
      assiduite,
      source_id, source_code,
      histo_creation, histo_createur_id,
      histo_modification, histo_modificateur_id
    ) SELECT
      scenario_noeud_id_seq.nextval,
      DUPLIQUER.destination_id, sn.noeud_id,
      sn.assiduite,
      source.id, 'dupli_' || sn.id || '_' || sn.noeud_id || '_' || trunc(dbms_random.value(1,10000000000000)),
      sysdate, DUPLIQUER.utilisateur_id,
      sysdate, DUPLIQUER.utilisateur_id
    FROM
      scenario_noeud sn
      JOIN noeud n ON n.id = sn.noeud_id
      JOIN source ON source.code = 'OSE'
    WHERE
      sn.scenario_id = DUPLIQUER.source_id
      AND sn.histo_destruction IS NULL
      AND (DUPLIQUER.NOEUDS IS NULL OR DUPLIQUER.NOEUDS LIKE '%,' || noeud_id || ',%' )
      AND (DUPLIQUER.STRUCTURE_ID IS NULL OR n.structure_id = DUPLIQUER.STRUCTURE_ID)
    ;

    /* Duplication des effectifs */
    INSERT INTO scenario_noeud_effectif (
      id,
      scenario_noeud_id,
      type_heures_id,
      effectif,
      etape_id
    ) SELECT
      scenario_noeud_effectif_id_seq.nextval,
      sn_dst.id,
      sne.type_heures_id,
      sne.effectif,
      sne.etape_id
    FROM
      scenario_noeud_effectif sne
      JOIN scenario_noeud sn_src ON sn_src.id = sne.scenario_noeud_id
      JOIN scenario_noeud sn_dst ON sn_dst.scenario_id = DUPLIQUER.destination_id AND sn_dst.noeud_id = sn_src.noeud_id
      JOIN noeud n ON n.id = sn_src.noeud_id
    WHERE
      sn_src.scenario_id = DUPLIQUER.source_id
      AND sn_src.histo_destruction IS NULL
      AND (DUPLIQUER.NOEUDS IS NULL OR DUPLIQUER.NOEUDS LIKE '%,' || sn_src.noeud_id || ',%' )
      AND (DUPLIQUER.STRUCTURE_ID IS NULL OR n.structure_id = DUPLIQUER.STRUCTURE_ID)
    ;

    /* Duplication des seuils */
    INSERT INTO scenario_noeud_seuil (
      id,
      scenario_noeud_id,
      type_intervention_id,
      ouverture,
      dedoublement
    ) SELECT
      scenario_noeud_seuil_id_seq.nextval,
      sn_dst.id,
      sns.type_intervention_id,
      sns.ouverture,
      sns.dedoublement
    FROM
      scenario_noeud_seuil sns
      JOIN scenario_noeud sn_src ON sn_src.id = sns.scenario_noeud_id
      JOIN scenario_noeud sn_dst ON sn_dst.scenario_id = DUPLIQUER.destination_id AND sn_dst.noeud_id = sn_src.noeud_id
      JOIN noeud n ON n.id = sn_src.noeud_id
    WHERE
      sn_src.scenario_id = DUPLIQUER.source_id
      AND sn_src.histo_destruction IS NULL
      AND (DUPLIQUER.NOEUDS IS NULL OR DUPLIQUER.NOEUDS LIKE '%,' || sn_src.noeud_id || ',%' )
      AND (DUPLIQUER.STRUCTURE_ID IS NULL OR n.structure_id = DUPLIQUER.STRUCTURE_ID)
    ;
  END;



  PROCEDURE CONTROLE_SEUIL( ouverture NUMERIC, dedoublement NUMERIC ) IS
  BEGIN
    IF ouverture IS NOT NULL THEN
      IF ouverture < 1 THEN
        raise_application_error(-20101, 'Le seuil d''ouverture doit être supérieur ou égal à 1');
      END IF;
    END IF;

    IF dedoublement IS NOT NULL THEN
      IF dedoublement < 1 THEN
        raise_application_error(-20101, 'Le seuil de dédoublement doit être supérieur ou égal à 1');
      END IF;
    END IF;

    IF ouverture IS NOT NULL AND dedoublement IS NOT NULL THEN
      IF dedoublement < ouverture THEN
        raise_application_error(-20101, 'Le seuil de dédoublement doit être supérieur ou égal au seuil d''ouverture');
      END IF;
    END IF;
  END;


  FUNCTION CREER_SCENARIO_NOEUD( scenario_id NUMERIC, noeud_id NUMERIC, assiduite FLOAT DEFAULT 1 ) RETURN NUMERIC IS
    new_id NUMERIC;
  BEGIN
    new_id := SCENARIO_NOEUD_ID_SEQ.NEXTVAL;
--ose_test.echo(scenario_id || '-' || noeud_id);
    INSERT INTO SCENARIO_NOEUD(
      ID,
      SCENARIO_ID,
      NOEUD_ID,
      ASSIDUITE,
      SOURCE_ID,
      SOURCE_CODE,
      HEURES,
      HISTO_CREATION,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATION,
      HISTO_MODIFICATEUR_ID
    ) VALUES (
      new_id,
      CREER_SCENARIO_NOEUD.scenario_id,
      CREER_SCENARIO_NOEUD.noeud_id,
      CREER_SCENARIO_NOEUD.assiduite,
      OSE_DIVERS.GET_OSE_SOURCE_ID,
      'OSE_NEW_SN_' || new_id,
      null,
      SYSDATE,
      OSE_DIVERS.GET_OSE_UTILISATEUR_ID,
      SYSDATE,
      OSE_DIVERS.GET_OSE_UTILISATEUR_ID
    );
    RETURN new_id;
  END;


  FUNCTION GET_SCENARIO_NOEUD_ID(scenario_id NUMERIC, noeud_id NUMERIC) RETURN NUMERIC IS
    res NUMERIC;
  BEGIN
    SELECT
      sn.id INTO res
    FROM
      scenario_noeud sn
    WHERE
      sn.noeud_id = GET_SCENARIO_NOEUD_ID.noeud_id
      AND sn.scenario_id = GET_SCENARIO_NOEUD_ID.scenario_id
      AND sn.histo_destruction IS NULL;

    RETURN res;

  EXCEPTION WHEN NO_DATA_FOUND THEN
    RETURN NULL;
  END;


  PROCEDURE ADD_SCENARIO_NOEUD_EFFECTIF( scenario_noeud_id NUMERIC, type_heures_id NUMERIC, etape_id NUMERIC, effectif FLOAT ) IS
    old_enable BOOLEAN;
  BEGIN
    old_enable := ose_chargens.ENABLE_TRIGGER_EFFECTIFS;
    ose_chargens.ENABLE_TRIGGER_EFFECTIFS := false;

    MERGE INTO scenario_noeud_effectif sne USING dual ON (

          sne.scenario_noeud_id = ADD_SCENARIO_NOEUD_EFFECTIF.scenario_noeud_id
      AND sne.type_heures_id = ADD_SCENARIO_NOEUD_EFFECTIF.type_heures_id
      AND sne.etape_id = ADD_SCENARIO_NOEUD_EFFECTIF.etape_id

    ) WHEN MATCHED THEN UPDATE SET

      effectif = effectif + ADD_SCENARIO_NOEUD_EFFECTIF.effectif

    WHEN NOT MATCHED THEN INSERT (

      ID,
      SCENARIO_NOEUD_ID,
      TYPE_HEURES_ID,
      ETAPE_ID,
      EFFECTIF

    ) VALUES (

      SCENARIO_NOEUD_EFFECTIF_ID_SEQ.NEXTVAL,
      ADD_SCENARIO_NOEUD_EFFECTIF.scenario_noeud_id,
      ADD_SCENARIO_NOEUD_EFFECTIF.type_heures_id,
      ADD_SCENARIO_NOEUD_EFFECTIF.etape_id,
      ADD_SCENARIO_NOEUD_EFFECTIF.effectif

    );

    DELETE FROM scenario_noeud_effectif WHERE effectif = 0;

    ose_chargens.ENABLE_TRIGGER_EFFECTIFS := old_enable;
  END;



  PROCEDURE INIT_SCENARIO_NOEUD_EFFECTIF(
    etape_id NUMERIC,
    scenario_id NUMERIC,
    type_heures_id NUMERIC,
    effectif FLOAT,
    surcharge BOOLEAN DEFAULT FALSE
  ) IS
    noeud_id NUMERIC;
    scenario_noeud_id NUMERIC;
    scenario_noeud_effectif_id NUMERIC;
  BEGIN
    SELECT
      n.id, sn.id, sne.id
    INTO
      noeud_id, scenario_noeud_id, scenario_noeud_effectif_id
    FROM
                noeud                     n
      LEFT JOIN scenario_noeud           sn ON sn.noeud_id = n.id
                                           AND sn.histo_destruction IS NULL
                                           AND sn.scenario_id = INIT_SCENARIO_NOEUD_EFFECTIF.scenario_id

      LEFT JOIN scenario_noeud_effectif sne ON sne.scenario_noeud_id = sn.id
                                           AND sne.type_heures_id = INIT_SCENARIO_NOEUD_EFFECTIF.type_heures_id
    WHERE
      n.etape_id = INIT_SCENARIO_NOEUD_EFFECTIF.etape_id
      AND n.histo_destruction IS NULL
    ;

    IF noeud_id IS NULL THEN RETURN; END IF;

    IF scenario_noeud_id IS NULL THEN
      scenario_noeud_id := CREER_SCENARIO_NOEUD( scenario_id, noeud_id );
    END IF;

    IF scenario_noeud_effectif_id IS NULL THEN
      scenario_noeud_effectif_id := SCENARIO_NOEUD_EFFECTIF_ID_SEQ.NEXTVAL;
      INSERT INTO scenario_noeud_effectif (
        id,
        scenario_noeud_id,
        type_heures_id,
        effectif,
        etape_id
      ) VALUES (
        scenario_noeud_effectif_id,
        scenario_noeud_id,
        INIT_SCENARIO_NOEUD_EFFECTIF.type_heures_id,
        INIT_SCENARIO_NOEUD_EFFECTIF.effectif,
        INIT_SCENARIO_NOEUD_EFFECTIF.etape_id
      );
    ELSIF surcharge THEN
      UPDATE scenario_noeud_effectif SET effectif = INIT_SCENARIO_NOEUD_EFFECTIF.effectif WHERE id = scenario_noeud_effectif_id;
    END IF;

    CALC_SUB_EFFECTIF2( noeud_id, scenario_id, type_heures_id, etape_id );

  EXCEPTION WHEN NO_DATA_FOUND THEN
    RETURN;
  END;



  PROCEDURE SET_PRECALC_HEURES_PARAMS(
    annee_id                       NUMERIC DEFAULT NULL,
    structure_id                   NUMERIC DEFAULT NULL,
    scenario_id                    NUMERIC DEFAULT NULL,
    type_heures_id                 NUMERIC DEFAULT NULL,
    etape_id                       NUMERIC DEFAULT NULL,
    noeud_ids                      tnoeud_ids DEFAULT NULL
  ) IS
  BEGIN
    PRECALC_HEURES_PARAMS.ANNEE_ID       := ANNEE_ID;
    PRECALC_HEURES_PARAMS.STRUCTURE_ID   := STRUCTURE_ID;
    PRECALC_HEURES_PARAMS.SCENARIO_ID    := SCENARIO_ID;
    PRECALC_HEURES_PARAMS.TYPE_HEURES_ID := TYPE_HEURES_ID;
    PRECALC_HEURES_PARAMS.ETAPE_ID       := ETAPE_ID;
    PRECALC_HEURES_PARAMS.NOEUD_IDS      := noeud_ids;
  END;



  FUNCTION MATCH_PRECALC_HEURES_PARAMS(
    annee_id                       NUMERIC DEFAULT NULL,
    structure_id                   NUMERIC DEFAULT NULL,
    scenario_id                    NUMERIC DEFAULT NULL,
    type_heures_id                 NUMERIC DEFAULT NULL,
    etape_id                       NUMERIC DEFAULT NULL,
    noeud_id                       NUMERIC DEFAULT NULL
  ) RETURN NUMERIC IS
  BEGIN

    IF PRECALC_HEURES_PARAMS.noeud_ids IS NOT NULL THEN
      IF NOT (noeud_id MEMBER OF PRECALC_HEURES_PARAMS.noeud_ids) THEN
        RETURN 0;
      END IF;
    END IF;

    IF annee_id <> COALESCE(PRECALC_HEURES_PARAMS.annee_id, annee_id) THEN
      RETURN 0;
    END IF;

    IF structure_id <> COALESCE(PRECALC_HEURES_PARAMS.structure_id, structure_id) THEN
      RETURN 0;
    END IF;

    IF scenario_id <> COALESCE(PRECALC_HEURES_PARAMS.scenario_id, scenario_id) THEN
      RETURN 0;
    END IF;

    IF type_heures_id <> COALESCE(PRECALC_HEURES_PARAMS.type_heures_id, type_heures_id) THEN
      RETURN 0;
    END IF;

    IF etape_id <> COALESCE(PRECALC_HEURES_PARAMS.etape_id, etape_id) THEN
      RETURN 0;
    END IF;

    RETURN 1;
  END;


  FUNCTION GET_PRECALC_HEURES_ANNEE RETURN NUMERIC IS
  BEGIN
    RETURN PRECALC_HEURES_PARAMS.ANNEE_ID;
  END;



  FUNCTION GET_PRECALC_HEURES_STRUCTURE RETURN NUMERIC IS
  BEGIN
    RETURN PRECALC_HEURES_PARAMS.STRUCTURE_ID;
  END;



  FUNCTION GET_PRECALC_HEURES_SCENARIO RETURN NUMERIC IS
  BEGIN
    RETURN PRECALC_HEURES_PARAMS.SCENARIO_ID;
  END;



  FUNCTION GET_PRECALC_HEURES_TYPE_HEURES RETURN NUMERIC IS
  BEGIN
    RETURN PRECALC_HEURES_PARAMS.TYPE_HEURES_ID;
  END;



  FUNCTION GET_PRECALC_HEURES_ETAPE RETURN NUMERIC IS
  BEGIN
    RETURN PRECALC_HEURES_PARAMS.ETAPE_ID;
  END;

--  FUNCTION GET_PRECALC_HEURES_NOEUD RETURN NUMERIC IS
--  BEGIN

--  END;

END OSE_CHARGENS;