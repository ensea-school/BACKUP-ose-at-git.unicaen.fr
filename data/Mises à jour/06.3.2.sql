-- Script de migration de la version 6.3 à la 6.3.2

CREATE TABLE version (
  numero   NUMBER NOT NULL
)
LOGGING;

ALTER TABLE version ADD CONSTRAINT version_pk PRIMARY KEY ( numero );

INSERT INTO version(numero) VALUES (1);

DROP VIEW V_INTERVENANT_RECHERCHE;

/

create or replace PACKAGE BODY       "OSE_FORMULE" AS

  v_date_obs DATE;
  debug_level NUMERIC DEFAULT 0;
  d_all_volume_horaire_ref  t_lst_volume_horaire_ref;
  d_all_volume_horaire      t_lst_volume_horaire;
  arrondi NUMERIC DEFAULT 2;

  INTERVENANT_ID NUMERIC DEFAULT NULL;

  FUNCTION GET_INTERVENANT_ID RETURN NUMERIC IS
  BEGIN
    RETURN INTERVENANT_ID;
  END;

  FUNCTION GET_DATE_OBS RETURN DATE AS
  BEGIN
    RETURN COALESCE( v_date_obs, SYSDATE );
  END;

  FUNCTION SET_DATE_OBS( DATE_OBS DATE DEFAULT NULL ) RETURN DATE IS
  BEGIN
    v_date_obs := DATE_OBS;
    RETURN v_date_obs;
  END;

  PROCEDURE SET_DEBUG_LEVEL( DEBUG_LEVEL NUMERIC ) IS
  BEGIN
    ose_formule.debug_level := SET_DEBUG_LEVEL.DEBUG_LEVEL;
  END;

  FUNCTION GET_DEBUG_LEVEL RETURN NUMERIC IS
  BEGIN
    RETURN ose_formule.debug_level;
  END;

  FUNCTION GET_TAUX_HORAIRE_HETD( DATE_OBS DATE DEFAULT NULL ) RETURN FLOAT IS
    taux_hetd FLOAT;
  BEGIN
    SELECT valeur INTO taux_hetd
    FROM taux_horaire_hetd t
    WHERE
      DATE_OBS BETWEEN t.histo_creation AND COALESCE(t.histo_destruction,GREATEST(SYSDATE,DATE_OBS))
      AND rownum = 1
    ORDER BY
      histo_creation DESC;
    RETURN taux_hetd;
  END;

  PROCEDURE UPDATE_ANNEE_TAUX_HETD IS
  BEGIN
    UPDATE annee SET taux_hetd = GET_TAUX_HORAIRE_HETD(date_fin);
  END;



  PROCEDURE CALCULER_TOUT( ANNEE_ID NUMERIC DEFAULT NULL ) IS
    a_id NUMERIC;
  BEGIN
    a_id := NVL(CALCULER_TOUT.ANNEE_ID, OSE_PARAMETRE.GET_ANNEE);
    FOR mp IN (
      SELECT DISTINCT
        intervenant_id
      FROM
        service s
        JOIN intervenant i ON i.id = s.intervenant_id
      WHERE
        s.histo_destruction IS NULL
        AND i.annee_id = a_id

      UNION

      SELECT DISTINCT
        intervenant_id
      FROM
        service_referentiel sr
        JOIN intervenant i ON i.id = sr.intervenant_id
      WHERE
        sr.histo_destruction IS NULL
        AND i.annee_id = a_id

    )
    LOOP
      CALCULER( mp.intervenant_id );
    END LOOP;
  END;



  FUNCTION ENREGISTRER_RESULTAT( fr formule_resultat%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
    ti_code VARCHAR(5);
  BEGIN

    SELECT
      ti.code INTO ti_code
    FROM
      type_intervenant        ti
      JOIN statut_intervenant si ON si.type_intervenant_id = ti.id
      JOIN intervenant         i ON i.statut_id = si.id
    WHERE
      i.id = fr.intervenant_id;



    MERGE INTO formule_resultat tfr USING dual ON (

          tfr.intervenant_id         = fr.intervenant_id
      AND tfr.type_volume_horaire_id = fr.type_volume_horaire_id
      AND tfr.etat_volume_horaire_id = fr.etat_volume_horaire_id

    ) WHEN MATCHED THEN UPDATE SET

      service_du                     = ROUND( fr.service_du, arrondi ),
      service_fi                     = ROUND( fr.service_fi, arrondi ),
      service_fa                     = ROUND( fr.service_fa, arrondi ),
      service_fc                     = ROUND( fr.service_fc, arrondi ),
      service_referentiel            = ROUND( fr.service_referentiel, arrondi ),
      heures_compl_fi                = ROUND( fr.heures_compl_fi, arrondi ),
      heures_compl_fa                = ROUND( fr.heures_compl_fa, arrondi ),
      heures_compl_fc                = ROUND( fr.heures_compl_fc, arrondi ),
      heures_compl_fc_majorees       = ROUND( fr.heures_compl_fc_majorees, arrondi ),
      heures_compl_referentiel       = ROUND( fr.heures_compl_referentiel, arrondi ),
      total                          = ROUND( fr.total, arrondi ),
      solde                          = ROUND( fr.solde, arrondi ),
      sous_service                   = ROUND( fr.sous_service, arrondi ),
      heures_compl                   = ROUND( fr.heures_compl, arrondi ),
      to_delete                      = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      INTERVENANT_ID,
      TYPE_VOLUME_HORAIRE_ID,
      ETAT_VOLUME_HORAIRE_ID,
      SERVICE_DU,
      SERVICE_FI,
      SERVICE_FA,
      SERVICE_FC,
      SERVICE_REFERENTIEL,
      HEURES_COMPL_FI,
      HEURES_COMPL_FA,
      HEURES_COMPL_FC,
      HEURES_COMPL_FC_majorees,
      HEURES_COMPL_REFERENTIEL,
      TOTAL,
      SOLDE,
      SOUS_SERVICE,
      HEURES_COMPL,
      TO_DELETE,
      type_intervenant_code

    ) VALUES (

      FORMULE_RESULTAT_ID_SEQ.NEXTVAL,
      fr.intervenant_id,
      fr.type_volume_horaire_id,
      fr.etat_volume_horaire_id,
      ROUND( fr.service_du, arrondi ),
      ROUND( fr.service_fi, arrondi ),
      ROUND( fr.service_fa, arrondi ),
      ROUND( fr.service_fc, arrondi ),
      ROUND( fr.service_referentiel, arrondi ),
      ROUND( fr.heures_compl_fi, arrondi ),
      ROUND( fr.heures_compl_fa, arrondi ),
      ROUND( fr.heures_compl_fc, arrondi ),
      ROUND( fr.heures_compl_fc_majorees, arrondi ),
      ROUND( fr.heures_compl_referentiel, arrondi ),
      ROUND( fr.total, arrondi ),
      ROUND( fr.solde, arrondi ),
      ROUND( fr.sous_service, arrondi ),
      ROUND( fr.heures_compl, arrondi ),
      0,
      ti_code
    );

    SELECT id INTO id FROM formule_resultat tfr WHERE
          tfr.intervenant_id         = fr.intervenant_id
      AND tfr.type_volume_horaire_id = fr.type_volume_horaire_id
      AND tfr.etat_volume_horaire_id = fr.etat_volume_horaire_id;
    RETURN id;
  END;


  FUNCTION ENREGISTRER_RESULTAT_SERVICE( fs formule_resultat_service%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
  BEGIN
    MERGE INTO formule_resultat_service tfs USING dual ON (

          tfs.formule_resultat_id    = fs.formule_resultat_id
      AND tfs.service_id             = fs.service_id

    ) WHEN MATCHED THEN UPDATE SET

      service_fi                     = ROUND( fs.service_fi, arrondi ),
      service_fa                     = ROUND( fs.service_fa, arrondi ),
      service_fc                     = ROUND( fs.service_fc, arrondi ),
      heures_compl_fi                = ROUND( fs.heures_compl_fi, arrondi ),
      heures_compl_fa                = ROUND( fs.heures_compl_fa, arrondi ),
      heures_compl_fc                = ROUND( fs.heures_compl_fc, arrondi ),
      heures_compl_fc_majorees       = ROUND( fs.heures_compl_fc_majorees, arrondi ),
      total                          = ROUND( fs.total, arrondi ),
      TO_DELETE                      = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      FORMULE_RESULTAT_ID,
      SERVICE_ID,
      SERVICE_FI,
      SERVICE_FA,
      SERVICE_FC,
      HEURES_COMPL_FI,
      HEURES_COMPL_FA,
      HEURES_COMPL_FC,
      HEURES_COMPL_FC_majorees,
      TOTAL,
      TO_DELETE

    ) VALUES (

      FORMULE_RESULTAT_SERVIC_ID_SEQ.NEXTVAL,
      fs.formule_resultat_id,
      fs.service_id,
      ROUND( fs.service_fi, arrondi ),
      ROUND( fs.service_fa, arrondi ),
      ROUND( fs.service_fc, arrondi ),
      ROUND( fs.heures_compl_fi, arrondi ),
      ROUND( fs.heures_compl_fa, arrondi ),
      ROUND( fs.heures_compl_fc, arrondi ),
      ROUND( fs.heures_compl_fc_majorees, arrondi ),
      ROUND( fs.total, arrondi ),
      0

    );

    SELECT id INTO id FROM formule_resultat_service tfs WHERE
          tfs.formule_resultat_id    = fs.formule_resultat_id
      AND tfs.service_id             = fs.service_id;
    RETURN id;
  END;


  FUNCTION ENREGISTRER_RESULTAT_VH( fvh formule_resultat_vh%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
  BEGIN
    MERGE INTO formule_resultat_vh tfvh USING dual ON (

          tfvh.formule_resultat_id    = fvh.formule_resultat_id
      AND tfvh.volume_horaire_id      = fvh.volume_horaire_id

    ) WHEN MATCHED THEN UPDATE SET

      service_fi                     = ROUND( fvh.service_fi, arrondi ),
      service_fa                     = ROUND( fvh.service_fa, arrondi ),
      service_fc                     = ROUND( fvh.service_fc, arrondi ),
      heures_compl_fi                = ROUND( fvh.heures_compl_fi, arrondi ),
      heures_compl_fa                = ROUND( fvh.heures_compl_fa, arrondi ),
      heures_compl_fc                = ROUND( fvh.heures_compl_fc, arrondi ),
      heures_compl_fc_majorees       = ROUND( fvh.heures_compl_fc_majorees, arrondi ),
      total                          = ROUND( fvh.total, arrondi ),
      TO_DELETE                      = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      FORMULE_RESULTAT_ID,
      VOLUME_HORAIRE_ID,
      SERVICE_FI,
      SERVICE_FA,
      SERVICE_FC,
      HEURES_COMPL_FI,
      HEURES_COMPL_FA,
      HEURES_COMPL_FC,
      HEURES_COMPL_FC_MAJOREES,
      TOTAL,
      TO_DELETE

    ) VALUES (

      FORMULE_RESULTAT_VH_ID_SEQ.NEXTVAL,
      fvh.formule_resultat_id,
      fvh.volume_horaire_id,
      ROUND( fvh.service_fi, arrondi ),
      ROUND( fvh.service_fa, arrondi ),
      ROUND( fvh.service_fc, arrondi ),
      ROUND( fvh.heures_compl_fi, arrondi ),
      ROUND( fvh.heures_compl_fa, arrondi ),
      ROUND( fvh.heures_compl_fc, arrondi ),
      ROUND( fvh.heures_compl_fc_majorees, arrondi ),
      ROUND( fvh.total, arrondi ),
      0

    );

    SELECT id INTO id FROM formule_resultat_vh tfvh WHERE
          tfvh.formule_resultat_id    = fvh.formule_resultat_id
      AND tfvh.volume_horaire_id      = fvh.volume_horaire_id;
    RETURN id;
  END;


  FUNCTION ENREGISTRER_RESULTAT_SERV_REF( fr formule_resultat_service_ref%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
  BEGIN
    MERGE INTO formule_resultat_service_ref tfr USING dual ON (

          tfr.formule_resultat_id    = fr.formule_resultat_id
      AND tfr.service_referentiel_id = fr.service_referentiel_id

    ) WHEN MATCHED THEN UPDATE SET

      service_referentiel            = ROUND( fr.service_referentiel, arrondi ),
      heures_compl_referentiel       = ROUND( fr.heures_compl_referentiel, arrondi ),
      TO_DELETE                      = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      FORMULE_RESULTAT_ID,
      SERVICE_REFERENTIEL_ID,
      SERVICE_REFERENTIEL,
      HEURES_COMPL_REFERENTIEL,
      TOTAL,
      TO_DELETE

    ) VALUES (

      FORMULE_RESULTAT_SERVIC_ID_SEQ.NEXTVAL,
      fr.formule_resultat_id,
      fr.service_referentiel_id,
      ROUND( fr.service_referentiel, arrondi ),
      ROUND( fr.heures_compl_referentiel, arrondi ),
      fr.total,
      0

    );

    SELECT id INTO id FROM formule_resultat_service_ref tfr WHERE
          tfr.formule_resultat_id    = fr.formule_resultat_id
      AND tfr.service_referentiel_id = fr.service_referentiel_id;

    RETURN id;
  END;


  FUNCTION ENREGISTRER_RESULTAT_VH_REF( fvh formule_resultat_vh_ref%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
  BEGIN
    MERGE INTO formule_resultat_vh_ref tfvh USING dual ON (

          tfvh.formule_resultat_id    = fvh.formule_resultat_id
      AND tfvh.volume_horaire_ref_id      = fvh.volume_horaire_ref_id

    ) WHEN MATCHED THEN UPDATE SET

      service_referentiel            = ROUND( fvh.service_referentiel, arrondi ),
      heures_compl_referentiel       = ROUND( fvh.heures_compl_referentiel, arrondi ),
      total                          = fvh.total,
      TO_DELETE                      = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      FORMULE_RESULTAT_ID,
      VOLUME_HORAIRE_REF_ID,
      SERVICE_REFERENTIEL,
      HEURES_COMPL_REFERENTIEL,
      TOTAL,
      TO_DELETE

    ) VALUES (

      FORMULE_RESULTAT_VH_ID_SEQ.NEXTVAL,
      fvh.formule_resultat_id,
      fvh.volume_horaire_ref_id,
      ROUND( fvh.service_referentiel, arrondi ),
      ROUND( fvh.heures_compl_referentiel, arrondi ),
      fvh.total,
      0

    );

    SELECT id INTO id FROM formule_resultat_vh_ref tfvh WHERE
          tfvh.formule_resultat_id    = fvh.formule_resultat_id
      AND tfvh.volume_horaire_ref_id  = fvh.volume_horaire_ref_id;
    RETURN id;
  END;


  PROCEDURE POPULATE_INTERVENANT( INTERVENANT_ID NUMERIC, d_intervenant OUT t_intervenant ) IS
  BEGIN
    SELECT
      structure_id,
      annee_id,
      heures_service_statutaire,
      depassement_service_du_sans_hc
    INTO
      d_intervenant.structure_id,
      d_intervenant.annee_id,
      d_intervenant.heures_service_statutaire,
      d_intervenant.depassement_service_du_sans_hc
    FROM
      v_formule_intervenant fi
    WHERE
      fi.id = POPULATE_INTERVENANT.INTERVENANT_ID;

    SELECT
      NVL( SUM(heures), 0),
      NVL( SUM(heures_decharge), 0)
    INTO
      d_intervenant.heures_service_modifie,
      d_intervenant.heures_decharge
    FROM
      v_formule_service_modifie fsm
    WHERE
      fsm.intervenant_id = POPULATE_INTERVENANT.INTERVENANT_ID;

  EXCEPTION WHEN NO_DATA_FOUND THEN
    d_intervenant.structure_id := null;
    d_intervenant.annee_id := null;
    d_intervenant.heures_service_statutaire := 0;
    d_intervenant.depassement_service_du_sans_hc := 0;
    d_intervenant.heures_service_modifie := 0;
    d_intervenant.heures_decharge := 0;
  END;


  PROCEDURE POPULATE_SERVICE_REF( INTERVENANT_ID NUMERIC, d_service_ref OUT t_lst_service_ref ) IS
    i PLS_INTEGER;
  BEGIN
    d_service_ref.delete;

    FOR d IN (
      SELECT
        fr.id,
        fr.structure_id
      FROM
        v_formule_service_ref fr
      WHERE
        fr.intervenant_id = POPULATE_SERVICE_REF.INTERVENANT_ID
    ) LOOP
      d_service_ref( d.id ).id           := d.id;
      d_service_ref( d.id ).structure_id := d.structure_id;
    END LOOP;
  END;


  PROCEDURE POPULATE_SERVICE( INTERVENANT_ID NUMERIC, d_service OUT t_lst_service ) IS
  BEGIN
    d_service.delete;

    FOR d IN (
      SELECT
        id,
        taux_fi,
        taux_fa,
        taux_fc,
        structure_aff_id,
        structure_ens_id,
        ponderation_service_du,
        ponderation_service_compl
      FROM
        v_formule_service fs
      WHERE
        fs.intervenant_id = POPULATE_SERVICE.INTERVENANT_ID
    ) LOOP
      d_service( d.id ).id                        := d.id;
      d_service( d.id ).taux_fi                   := d.taux_fi;
      d_service( d.id ).taux_fa                   := d.taux_fa;
      d_service( d.id ).taux_fc                   := d.taux_fc;
      d_service( d.id ).ponderation_service_du    := d.ponderation_service_du;
      d_service( d.id ).ponderation_service_compl := d.ponderation_service_compl;
      d_service( d.id ).structure_aff_id          := d.structure_aff_id;
      d_service( d.id ).structure_ens_id          := d.structure_ens_id;
    END LOOP;
  END;

  PROCEDURE POPULATE_VOLUME_HORAIRE_REF( INTERVENANT_ID NUMERIC, d_volume_horaire_ref OUT t_lst_volume_horaire_ref ) IS
  BEGIN
    d_volume_horaire_ref.delete;

    FOR d IN (
      SELECT
        id,
        service_referentiel_id,
        heures,
        fvh.type_volume_horaire_id,
        fvh.etat_volume_horaire_id,
        fvh.etat_volume_horaire_ordre
      FROM
        v_formule_volume_horaire_ref fvh
      WHERE
        fvh.intervenant_id                = POPULATE_VOLUME_HORAIRE_REF.INTERVENANT_ID
    ) LOOP
      d_volume_horaire_ref( d.id ).id                        := d.id;
      d_volume_horaire_ref( d.id ).service_referentiel_id    := d.service_referentiel_id;
      d_volume_horaire_ref( d.id ).heures                    := d.heures;
      d_volume_horaire_ref( d.id ).type_volume_horaire_id    := d.type_volume_horaire_id;
      d_volume_horaire_ref( d.id ).etat_volume_horaire_id    := d.etat_volume_horaire_id;
      d_volume_horaire_ref( d.id ).etat_volume_horaire_ordre := d.etat_volume_horaire_ordre;
    END LOOP;
  END;

  PROCEDURE POPULATE_VOLUME_HORAIRE( INTERVENANT_ID NUMERIC, d_volume_horaire OUT t_lst_volume_horaire ) IS
  BEGIN
    d_volume_horaire.delete;

    FOR d IN (
      SELECT
        id,
        service_id,
        heures,
        taux_service_du,
        taux_service_compl,
        fvh.type_volume_horaire_id,
        fvh.etat_volume_horaire_id,
        fvh.etat_volume_horaire_ordre
      FROM
        v_formule_volume_horaire fvh
      WHERE
        fvh.intervenant_id                = POPULATE_VOLUME_HORAIRE.INTERVENANT_ID
    ) LOOP
      d_volume_horaire( d.id ).id                        := d.id;
      d_volume_horaire( d.id ).service_id                := d.service_id;
      d_volume_horaire( d.id ).heures                    := d.heures;
      d_volume_horaire( d.id ).taux_service_du           := d.taux_service_du;
      d_volume_horaire( d.id ).taux_service_compl        := d.taux_service_compl;
      d_volume_horaire( d.id ).type_volume_horaire_id    := d.type_volume_horaire_id;
      d_volume_horaire( d.id ).etat_volume_horaire_id    := d.etat_volume_horaire_id;
      d_volume_horaire( d.id ).etat_volume_horaire_ordre := d.etat_volume_horaire_ordre;
    END LOOP;
  END;


  PROCEDURE POPULATE_TYPE_ETAT_VH( d_volume_horaire t_lst_volume_horaire, d_volume_horaire_ref t_lst_volume_horaire_ref, d_type_etat_vh OUT t_lst_type_etat_vh ) IS
    TYPE t_ordres IS TABLE OF NUMERIC INDEX BY PLS_INTEGER;

    ordres_found t_ordres;
    ordres_exists t_ordres;
    type_volume_horaire_id PLS_INTEGER;
    etat_volume_horaire_ordre PLS_INTEGER;
    id PLS_INTEGER;
  BEGIN
    d_type_etat_vh.delete;

    -- récupération des ID et ordres de volumes horaires
    FOR evh IN (
      SELECT   id, ordre
      FROM     etat_volume_horaire evh
      ORDER BY ordre
    ) LOOP
      ordres_exists( evh.ordre ) := evh.id;
    END LOOP;

    -- récupération des ordres maximum par type d'intervention
    id := d_volume_horaire.FIRST;
    LOOP EXIT WHEN id IS NULL;
      IF NOT ordres_found.EXISTS(d_volume_horaire(id).type_volume_horaire_id) THEN
        ordres_found( d_volume_horaire(id).type_volume_horaire_id ) := d_volume_horaire(id).etat_volume_horaire_ordre;
      ELSIF ordres_found( d_volume_horaire(id).type_volume_horaire_id ) < d_volume_horaire(id).etat_volume_horaire_ordre THEN
        ordres_found( d_volume_horaire(id).type_volume_horaire_id ) := d_volume_horaire(id).etat_volume_horaire_ordre;
      END IF;
      id := d_volume_horaire.NEXT(id);
    END LOOP;

    id := d_volume_horaire_ref.FIRST;
    LOOP EXIT WHEN id IS NULL;
      IF NOT ordres_found.EXISTS(d_volume_horaire_ref(id).type_volume_horaire_id) THEN
        ordres_found( d_volume_horaire_ref(id).type_volume_horaire_id ) := d_volume_horaire_ref(id).etat_volume_horaire_ordre;
      ELSIF ordres_found( d_volume_horaire_ref(id).type_volume_horaire_id ) < d_volume_horaire_ref(id).etat_volume_horaire_ordre THEN
        ordres_found( d_volume_horaire_ref(id).type_volume_horaire_id ) := d_volume_horaire_ref(id).etat_volume_horaire_ordre;
      END IF;
      id := d_volume_horaire_ref.NEXT(id);
    END LOOP;

    -- peuplement des t_lst_type_etat_vh
    type_volume_horaire_id := ordres_found.FIRST;
    LOOP EXIT WHEN type_volume_horaire_id IS NULL;
      etat_volume_horaire_ordre := ordres_exists.FIRST;
      LOOP EXIT WHEN etat_volume_horaire_ordre IS NULL;
        IF etat_volume_horaire_ordre <= ordres_found(type_volume_horaire_id) THEN
          d_type_etat_vh( type_volume_horaire_id + 100000 * etat_volume_horaire_ordre ).type_volume_horaire_id := type_volume_horaire_id;
          d_type_etat_vh( type_volume_horaire_id + 100000 * etat_volume_horaire_ordre ).etat_volume_horaire_id := ordres_exists( etat_volume_horaire_ordre );
        END IF;
        etat_volume_horaire_ordre := ordres_exists.NEXT(etat_volume_horaire_ordre);
      END LOOP;

      type_volume_horaire_id := ordres_found.NEXT(type_volume_horaire_id);
    END LOOP;

  END;


  PROCEDURE POPULATE( INTERVENANT_ID NUMERIC ) IS
  BEGIN
    OSE_FORMULE.INTERVENANT_ID := POPULATE.INTERVENANT_ID;

    POPULATE_INTERVENANT    ( INTERVENANT_ID, d_intervenant );
    IF d_intervenant.heures_service_statutaire IS NOT NULL THEN -- sinon rien n'est à faire!!
      POPULATE_SERVICE_REF        ( INTERVENANT_ID, d_service_ref         );
      POPULATE_SERVICE            ( INTERVENANT_ID, d_service             );
      POPULATE_VOLUME_HORAIRE_REF ( INTERVENANT_ID, d_all_volume_horaire_ref  );
      POPULATE_VOLUME_HORAIRE     ( INTERVENANT_ID, d_all_volume_horaire      );
      POPULATE_TYPE_ETAT_VH       ( d_all_volume_horaire, d_all_volume_horaire_ref, d_type_etat_vh );
    END IF;
  END;


  PROCEDURE POPULATE_FILTER( TYPE_VOLUME_HORAIRE_ID NUMERIC, ETAT_VOLUME_HORAIRE_ID NUMERIC ) IS
    EVH_ORDRE NUMERIC;
    id PLS_INTEGER;
  BEGIN
    d_volume_horaire.delete;
    d_volume_horaire_ref.delete;

    SELECT ordre INTO EVH_ORDRE FROM etat_volume_horaire WHERE ID = ETAT_VOLUME_HORAIRE_ID;

    id := d_all_volume_horaire.FIRST;
    LOOP EXIT WHEN id IS NULL;
      IF
        d_all_volume_horaire(id).type_volume_horaire_id = TYPE_VOLUME_HORAIRE_ID
        AND d_all_volume_horaire(id).etat_volume_horaire_ordre >= EVH_ORDRE
      THEN
        d_volume_horaire(id) := d_all_volume_horaire(id);
      END IF;
      id := d_all_volume_horaire.NEXT(id);
    END LOOP;

    id := d_all_volume_horaire_ref.FIRST;
    LOOP EXIT WHEN id IS NULL;
      IF
        d_all_volume_horaire_ref(id).type_volume_horaire_id = TYPE_VOLUME_HORAIRE_ID
        AND d_all_volume_horaire_ref(id).etat_volume_horaire_ordre >= EVH_ORDRE
      THEN
        d_volume_horaire_ref(id) := d_all_volume_horaire_ref(id);
      END IF;
      id := d_all_volume_horaire_ref.NEXT(id);
    END LOOP;
  END;


  PROCEDURE INIT_RESULTAT ( INTERVENANT_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, ETAT_VOLUME_HORAIRE_ID NUMERIC ) IS
  BEGIN
    d_resultat.intervenant_id         := INTERVENANT_ID;
    d_resultat.type_volume_horaire_id := TYPE_VOLUME_HORAIRE_ID;
    d_resultat.etat_volume_horaire_id := ETAT_VOLUME_HORAIRE_ID;
    d_resultat.service_du             := 0;
    d_resultat.solde                  := 0;
    d_resultat.sous_service           := 0;
    d_resultat.heures_compl           := 0;
    d_resultat.volume_horaire.delete;
    d_resultat.volume_horaire_ref.delete;
  END;


  PROCEDURE CALC_RESULTAT IS
    function_name VARCHAR2(30);
    package_name VARCHAR2(30);
  BEGIN
    package_name  := OSE_PARAMETRE.GET_FORMULE_PACKAGE_NAME;
    function_name := OSE_PARAMETRE.GET_FORMULE_FUNCTION_NAME;

    EXECUTE IMMEDIATE
      'BEGIN ' || package_name || '.' || function_name || '( :1, :2, :3 ); END;'
    USING
      d_resultat.intervenant_id, d_resultat.type_volume_horaire_id, d_resultat.etat_volume_horaire_id;

  END;


  PROCEDURE SAVE_RESULTAT IS
    res             t_resultat_hetd;
    res_ref         t_resultat_hetd_ref;
    res_service     t_lst_resultat_hetd;
    res_service_ref t_lst_resultat_hetd_ref;
    id              PLS_INTEGER;
    sid             PLS_INTEGER;
    fr              formule_resultat%rowtype;
    frs             formule_resultat_service%rowtype;
    frsr            formule_resultat_service_ref%rowtype;
    frvh            formule_resultat_vh%rowtype;
    frvhr           formule_resultat_vh_ref%rowtype;
    dev_null        PLS_INTEGER;
  BEGIN
    -- Calcul des données pour les services et le résultat global
    fr.service_fi := 0;
    fr.service_fa := 0;
    fr.service_fc := 0;
    fr.service_referentiel := 0;
    fr.heures_compl_fi := 0;
    fr.heures_compl_fa := 0;
    fr.heures_compl_fc := 0;
    fr.heures_compl_fc_majorees := 0;
    fr.heures_compl_referentiel := 0;

    id := d_resultat.volume_horaire.FIRST;
    LOOP EXIT WHEN id IS NULL;
      sid := d_volume_horaire(id).service_id;
      IF NOT res_service.exists(sid) THEN res_service(sid).service_fi := 0; END IF;

      res_service(sid).service_fi               := res_service(sid).service_fi               + d_resultat.volume_horaire(id).service_fi;
      res_service(sid).service_fa               := res_service(sid).service_fa               + d_resultat.volume_horaire(id).service_fa;
      res_service(sid).service_fc               := res_service(sid).service_fc               + d_resultat.volume_horaire(id).service_fc;
      res_service(sid).heures_compl_fi          := res_service(sid).heures_compl_fi          + d_resultat.volume_horaire(id).heures_compl_fi;
      res_service(sid).heures_compl_fa          := res_service(sid).heures_compl_fa          + d_resultat.volume_horaire(id).heures_compl_fa;
      res_service(sid).heures_compl_fc          := res_service(sid).heures_compl_fc          + d_resultat.volume_horaire(id).heures_compl_fc;
      res_service(sid).heures_compl_fc_majorees := res_service(sid).heures_compl_fc_majorees + d_resultat.volume_horaire(id).heures_compl_fc_majorees;

      fr.service_fi                             := fr.service_fi                             + d_resultat.volume_horaire(id).service_fi;
      fr.service_fa                             := fr.service_fa                             + d_resultat.volume_horaire(id).service_fa;
      fr.service_fc                             := fr.service_fc                             + d_resultat.volume_horaire(id).service_fc;
      fr.heures_compl_fi                        := fr.heures_compl_fi                        + d_resultat.volume_horaire(id).heures_compl_fi;
      fr.heures_compl_fa                        := fr.heures_compl_fa                        + d_resultat.volume_horaire(id).heures_compl_fa;
      fr.heures_compl_fc                        := fr.heures_compl_fc                        + d_resultat.volume_horaire(id).heures_compl_fc;
      fr.heures_compl_fc_majorees               := fr.heures_compl_fc_majorees               + d_resultat.volume_horaire(id).heures_compl_fc_majorees;
      id := d_resultat.volume_horaire.NEXT(id);
    END LOOP;

    id := d_resultat.volume_horaire_ref.FIRST;
    LOOP EXIT WHEN id IS NULL;
      sid := d_volume_horaire_ref(id).service_referentiel_id;
      IF NOT res_service_ref.exists(sid) THEN res_service_ref(sid).service_referentiel := 0; END IF;

      res_service_ref(sid).service_referentiel      := res_service_ref(sid).service_referentiel      + d_resultat.volume_horaire_ref(id).service_referentiel;
      res_service_ref(sid).heures_compl_referentiel := res_service_ref(sid).heures_compl_referentiel + d_resultat.volume_horaire_ref(id).heures_compl_referentiel;

      fr.service_referentiel                        := fr.service_referentiel                        + d_resultat.volume_horaire_ref(id).service_referentiel;
      fr.heures_compl_referentiel                   := fr.heures_compl_referentiel                   + d_resultat.volume_horaire_ref(id).heures_compl_referentiel;
      id := d_resultat.volume_horaire_ref.NEXT(id);
    END LOOP;

    -- Sauvegarde du résultat global
    fr.id                       := NULL;
    fr.intervenant_id           := d_resultat.intervenant_id;
    fr.type_volume_horaire_id   := d_resultat.type_volume_horaire_id;
    fr.etat_volume_horaire_id   := d_resultat.etat_volume_horaire_id;
    fr.service_du               := d_resultat.service_du;
    fr.total                    := fr.service_fi
                                 + fr.service_fa
                                 + fr.service_fc
                                 + fr.service_referentiel
                                 + fr.heures_compl_fi
                                 + fr.heures_compl_fa
                                 + fr.heures_compl_fc
                                 + fr.heures_compl_fc_majorees
                                 + fr.heures_compl_referentiel;
    fr.solde                    := d_resultat.solde;
    fr.sous_service             := d_resultat.sous_service;
    fr.heures_compl             := d_resultat.heures_compl;
    fr.id := OSE_FORMULE.ENREGISTRER_RESULTAT( fr );

    -- sauvegarde des services
    id := res_service.FIRST;
    LOOP EXIT WHEN id IS NULL;
      frs.id                       := NULL;
      frs.formule_resultat_id      := fr.id;
      frs.service_id               := id;
      frs.service_fi               := res_service(id).service_fi;
      frs.service_fa               := res_service(id).service_fa;
      frs.service_fc               := res_service(id).service_fc;
      frs.heures_compl_fi          := res_service(id).heures_compl_fi;
      frs.heures_compl_fa          := res_service(id).heures_compl_fa;
      frs.heures_compl_fc          := res_service(id).heures_compl_fc;
      frs.heures_compl_fc_majorees := res_service(id).heures_compl_fc_majorees;
      frs.total                    := frs.service_fi
                                    + frs.service_fa
                                    + frs.service_fc
                                    + frs.heures_compl_fi
                                    + frs.heures_compl_fa
                                    + frs.heures_compl_fc
                                    + frs.heures_compl_fc_majorees;
      dev_null := OSE_FORMULE.ENREGISTRER_RESULTAT_SERVICE( frs );
      id := res_service.NEXT(id);
    END LOOP;

    -- sauvegarde des services référentiels
    id := res_service_ref.FIRST;
    LOOP EXIT WHEN id IS NULL;
      frsr.id                       := NULL;
      frsr.formule_resultat_id      := fr.id;
      frsr.service_referentiel_id   := id;
      frsr.service_referentiel      := res_service_ref(id).service_referentiel;
      frsr.heures_compl_referentiel := res_service_ref(id).heures_compl_referentiel;
      frsr.total                    := res_service_ref(id).service_referentiel
                                     + res_service_ref(id).heures_compl_referentiel;
      dev_null := OSE_FORMULE.ENREGISTRER_RESULTAT_SERV_REF( frsr );
      id := res_service_ref.NEXT(id);
    END LOOP;

    -- sauvegarde des volumes horaires
    id := d_resultat.volume_horaire.FIRST;
    LOOP EXIT WHEN id IS NULL;
      frvh.id                        := NULL;
      frvh.formule_resultat_id       := fr.id;
      frvh.volume_horaire_id         := id;
      frvh.service_fi                := d_resultat.volume_horaire(id).service_fi;
      frvh.service_fa                := d_resultat.volume_horaire(id).service_fa;
      frvh.service_fc                := d_resultat.volume_horaire(id).service_fc;
      frvh.heures_compl_fi           := d_resultat.volume_horaire(id).heures_compl_fi;
      frvh.heures_compl_fa           := d_resultat.volume_horaire(id).heures_compl_fa;
      frvh.heures_compl_fc           := d_resultat.volume_horaire(id).heures_compl_fc;
      frvh.heures_compl_fc_majorees  := d_resultat.volume_horaire(id).heures_compl_fc_majorees;
      frvh.total                     := frvh.service_fi
                                      + frvh.service_fa
                                      + frvh.service_fc
                                      + frvh.heures_compl_fi
                                      + frvh.heures_compl_fa
                                      + frvh.heures_compl_fc
                                      + frvh.heures_compl_fc_majorees;
      dev_null := OSE_FORMULE.ENREGISTRER_RESULTAT_VH( frvh );
      id := d_resultat.volume_horaire.NEXT(id);
    END LOOP;

    -- sauvegarde des volumes horaires référentiels
    id := d_resultat.volume_horaire_ref.FIRST;
    LOOP EXIT WHEN id IS NULL;
      frvhr.id                       := NULL;
      frvhr.formule_resultat_id      := fr.id;
      frvhr.volume_horaire_ref_id    := id;
      frvhr.service_referentiel      := d_resultat.volume_horaire_ref(id).service_referentiel;
      frvhr.heures_compl_referentiel := d_resultat.volume_horaire_ref(id).heures_compl_referentiel;
      frvhr.total                    := frvhr.service_referentiel
                                      + frvhr.heures_compl_referentiel;
      dev_null := OSE_FORMULE.ENREGISTRER_RESULTAT_VH_REF( frvhr );
      id := d_resultat.volume_horaire_ref.NEXT(id);
    END LOOP;
  END;

  PROCEDURE DEBUG_INTERVENANT IS
  BEGIN
    ose_test.echo('d_intervenant');
    ose_test.echo('      .structure_id                   = ' || d_intervenant.structure_id || ' (' || ose_test.get_structure_by_id(d_intervenant.structure_id).libelle_court || ')' );
    ose_test.echo('      .heures_service_statutaire      = ' || d_intervenant.heures_service_statutaire );
    ose_test.echo('      .heures_service_modifie         = ' || d_intervenant.heures_service_modifie );
    ose_test.echo('      .depassement_service_du_sans_hc = ' || d_intervenant.depassement_service_du_sans_hc );
    ose_test.echo('');
  END;

  PROCEDURE DEBUG_SERVICE( SERVICE_ID PLS_INTEGER ) IS
  BEGIN
    ose_test.echo('d_service(' || SERVICE_ID || ')' );
    ose_test.echo('      .taux_fi                   = ' || d_service(SERVICE_ID).taux_fi );
    ose_test.echo('      .taux_fa                   = ' || d_service(SERVICE_ID).taux_fa );
    ose_test.echo('      .taux_fc                   = ' || d_service(SERVICE_ID).taux_fc );
    ose_test.echo('      .ponderation_service_du    = ' || d_service(SERVICE_ID).ponderation_service_du );
    ose_test.echo('      .ponderation_service_compl = ' || d_service(SERVICE_ID).ponderation_service_compl );
    ose_test.echo('      .structure_aff_id          = ' || d_service(SERVICE_ID).structure_aff_id || ' (' || ose_test.get_structure_by_id(d_service(SERVICE_ID).structure_aff_id).libelle_court || ')' );
    ose_test.echo('      .structure_ens_id          = ' || d_service(SERVICE_ID).structure_ens_id || ' (' || CASE WHEN d_service(SERVICE_ID).structure_ens_id IS NOT NULL THEN ose_test.get_structure_by_id(d_service(SERVICE_ID).structure_ens_id).libelle_court ELSE 'null' END || ')' );
    ose_test.echo('');
  END;

  PROCEDURE DEBUG_SERVICE_REF( SERVICE_REF_ID PLS_INTEGER ) IS
  BEGIN
    ose_test.echo('d_service_ref(' || SERVICE_REF_ID || ')' );
    ose_test.echo('      .structure_id          = ' || d_service_ref(SERVICE_REF_ID).structure_id || ' (' || ose_test.get_structure_by_id(d_service_ref(SERVICE_REF_ID).structure_id).libelle_court || ')' );
    ose_test.echo('');
  END;

  PROCEDURE DEBUG_VOLUME_HORAIRE( VH_ID PLS_INTEGER ) IS
  BEGIN
    ose_test.echo('d_volume_horaire(' || VH_ID || ')' );
    ose_test.echo('      .service_id                = ' || d_volume_horaire(VH_ID).service_id );
    ose_test.echo('      .type_volume_horaire_id    = ' || d_volume_horaire(VH_ID).type_volume_horaire_id );
    ose_test.echo('      .etat_volume_horaire_id    = ' || d_volume_horaire(VH_ID).etat_volume_horaire_id );
    ose_test.echo('      .etat_volume_horaire_ordre = ' || d_volume_horaire(VH_ID).etat_volume_horaire_ordre );
    ose_test.echo('      .heures                    = ' || d_volume_horaire(VH_ID).heures );
    ose_test.echo('      .taux_service_du           = ' || d_volume_horaire(VH_ID).taux_service_du );
    ose_test.echo('      .taux_service_compl        = ' || d_volume_horaire(VH_ID).taux_service_compl );
    ose_test.echo('');
  END;

  PROCEDURE DEBUG_VOLUME_HORAIRE_REF( VH_REF_ID PLS_INTEGER ) IS
  BEGIN
    ose_test.echo('d_volume_horaire_ref(' || VH_REF_ID || ')' );
    ose_test.echo('      .service_referentiel_id    = ' || d_volume_horaire_ref(VH_REF_ID).service_referentiel_id );
    ose_test.echo('      .type_volume_horaire_id    = ' || d_volume_horaire_ref(VH_REF_ID).type_volume_horaire_id );
    ose_test.echo('      .etat_volume_horaire_id    = ' || d_volume_horaire_ref(VH_REF_ID).etat_volume_horaire_id );
    ose_test.echo('      .etat_volume_horaire_ordre = ' || d_volume_horaire_ref(VH_REF_ID).etat_volume_horaire_ordre );
    ose_test.echo('      .heures                    = ' || d_volume_horaire_ref(VH_REF_ID).heures );
    ose_test.echo('');
  END;

  PROCEDURE DEBUG_RESULTAT IS
  BEGIN
    ose_test.echo('d_resultat' );
    ose_test.echo('      .service_du   = ' || d_resultat.service_du );
    ose_test.echo('      .solde        = ' || d_resultat.solde );
    ose_test.echo('      .sous_service = ' || d_resultat.sous_service );
    ose_test.echo('      .heures_compl = ' || d_resultat.heures_compl );
    ose_test.echo('');
  END;

  PROCEDURE DEBUG_RESULTAT_VH( VH_ID PLS_INTEGER ) IS
  BEGIN
    ose_test.echo('d_resultat.volume_horaire(' || VH_ID || ')' );
    ose_test.echo('      .service_fi                = ' || d_resultat.volume_horaire(VH_ID).service_fi );
    ose_test.echo('      .service_fa                = ' || d_resultat.volume_horaire(VH_ID).service_fa );
    ose_test.echo('      .service_fc                = ' || d_resultat.volume_horaire(VH_ID).service_fc );
    ose_test.echo('      .heures_compl_fi           = ' || d_resultat.volume_horaire(VH_ID).heures_compl_fi );
    ose_test.echo('      .heures_compl_fa           = ' || d_resultat.volume_horaire(VH_ID).heures_compl_fa );
    ose_test.echo('      .heures_compl_fc           = ' || d_resultat.volume_horaire(VH_ID).heures_compl_fc );
    ose_test.echo('      .heures_compl_fc_majorees  = ' || d_resultat.volume_horaire(VH_ID).heures_compl_fc_majorees );
    ose_test.echo('');
  END;

  PROCEDURE DEBUG_RESULTAT_VH_REF( VH_REF_ID PLS_INTEGER ) IS
  BEGIN
    ose_test.echo('d_resultat.volume_horaire_ref(' || VH_REF_ID || ')' );
    ose_test.echo('      .service_referentiel                = ' || d_resultat.volume_horaire_ref(VH_REF_ID).service_referentiel );
    ose_test.echo('      .heures_compl_referentiel           = ' || d_resultat.volume_horaire_ref(VH_REF_ID).heures_compl_referentiel );
    ose_test.echo('');
  END;

  PROCEDURE DEBUG_ALL( INTERVENANT_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, ETAT_VOLUME_HORAIRE_ID NUMERIC ) IS
    id  PLS_INTEGER;
    i   intervenant%rowtype;
    a   annee%rowtype;
    tvh type_volume_horaire%rowtype;
    evh etat_volume_horaire%rowtype;
  BEGIN
    IF GET_DEBUG_LEVEL >= 1 THEN
      SELECT * INTO   i FROM intervenant         WHERE id = INTERVENANT_ID;
      SELECT * INTO   a FROM annee               WHERE id = i.annee_id;
      SELECT * INTO tvh FROM type_volume_horaire WHERE id = TYPE_VOLUME_HORAIRE_ID;
      SELECT * INTO evh FROM etat_volume_horaire WHERE id = ETAT_VOLUME_HORAIRE_ID;

      ose_test.echo('');
      ose_test.echo('---------------------------------------------------------------------');
      ose_test.echo('Intervenant: ' || INTERVENANT_ID || ' : ' || i.prenom || ' ' || i.nom_usuel || ' (n° harp. ' || i.source_code || ')' );
      ose_test.echo(
                  'Année: ' || a.libelle
               || ', type ' || tvh.libelle
               || ', état ' || evh.libelle
      );
      ose_test.echo('');
    END IF;
    IF GET_DEBUG_LEVEL >= 2 THEN
      DEBUG_INTERVENANT;
    END IF;

    IF GET_DEBUG_LEVEL >= 5 THEN
      id := d_service.FIRST;
      LOOP EXIT WHEN id IS NULL;
        DEBUG_SERVICE( id );
        id := d_service.NEXT(id);
      END LOOP;

      id := d_service_ref.FIRST;
      LOOP EXIT WHEN id IS NULL;
        DEBUG_SERVICE_REF( id );
        id := d_service_ref.NEXT(id);
      END LOOP;
    END IF;

    IF GET_DEBUG_LEVEL >= 6 THEN
      id := d_volume_horaire.FIRST;
      LOOP EXIT WHEN id IS NULL;
        DEBUG_VOLUME_HORAIRE( id );
        id := d_volume_horaire.NEXT(id);
      END LOOP;

      id := d_volume_horaire_ref.FIRST;
      LOOP EXIT WHEN id IS NULL;
        DEBUG_VOLUME_HORAIRE_REF( id );
        id := d_volume_horaire_ref.NEXT(id);
      END LOOP;
    END IF;

    IF GET_DEBUG_LEVEL >= 3 THEN
      DEBUG_RESULTAT;
    END IF;

    IF GET_DEBUG_LEVEL >= 4 THEN
      id := d_resultat.volume_horaire.FIRST;
      LOOP EXIT WHEN id IS NULL;
        DEBUG_RESULTAT_VH( id );
        id := d_resultat.volume_horaire.NEXT(id);
      END LOOP;

      id := d_resultat.volume_horaire_ref.FIRST;
      LOOP EXIT WHEN id IS NULL;
        DEBUG_RESULTAT_VH_REF( id );
        id := d_resultat.volume_horaire_ref.NEXT(id);
      END LOOP;
    END IF;
  END;



  PROCEDURE CALCULER( INTERVENANT_ID NUMERIC ) IS
    id PLS_INTEGER;
    found BOOLEAN;
    function_name VARCHAR2(30);
    package_name VARCHAR2(30);
  BEGIN
    package_name  := OSE_PARAMETRE.GET_FORMULE_PACKAGE_NAME;
    function_name := OSE_PARAMETRE.GET_FORMULE_FUNCTION_NAME;

    -- détection de suppression des lignes de résultat obsolètes
    UPDATE formule_resultat SET TO_DELETE = 1 WHERE intervenant_id = CALCULER.INTERVENANT_ID;
    UPDATE FORMULE_RESULTAT_SERVICE_REF SET TO_DELETE = 1 WHERE formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
    UPDATE FORMULE_RESULTAT_SERVICE     SET TO_DELETE = 1 WHERE formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
    UPDATE FORMULE_RESULTAT_VH_REF      SET TO_DELETE = 1 WHERE formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
    UPDATE FORMULE_RESULTAT_VH          SET TO_DELETE = 1 WHERE formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);

    POPULATE( INTERVENANT_ID );
    IF d_intervenant.heures_service_statutaire IS NOT NULL THEN -- sinon rien n'est à faire!!
      -- lancement du calcul sur les nouvelles lignes ou sur les lignes existantes
      id := d_type_etat_vh.FIRST;
      LOOP EXIT WHEN id IS NULL;
        POPULATE_FILTER( d_type_etat_vh(id).type_volume_horaire_id, d_type_etat_vh(id).etat_volume_horaire_id );
        DEBUG_ALL( INTERVENANT_ID, d_type_etat_vh(id).type_volume_horaire_id, d_type_etat_vh(id).etat_volume_horaire_id );
        OSE_FORMULE.INIT_RESULTAT( INTERVENANT_ID, d_type_etat_vh(id).type_volume_horaire_id, d_type_etat_vh(id).etat_volume_horaire_id );
        OSE_FORMULE.CALC_RESULTAT;
        OSE_FORMULE.SAVE_RESULTAT;
        id := d_type_etat_vh.NEXT(id);
      END LOOP;
    END IF;

    -- suppression des données devenues obsolètes
    OSE_EVENT.ON_BEFORE_FORMULE_RES_DELETE( CALCULER.INTERVENANT_ID );

    UPDATE FORMULE_RESULTAT_SERVICE SET
      to_delete = 0,
      service_fi = 0,
      service_fa = 0,
      service_fc = 0,
      heures_compl_fi = 0,
      heures_compl_fa = 0,
      heures_compl_fc = 0,
      heures_compl_fc_majorees = 0,
      total = 0
    WHERE
      TO_DELETE = 1
      AND 0 < (SELECT COUNT(*) FROM mise_en_paiement mep WHERE mep.formule_res_service_id = FORMULE_RESULTAT_SERVICE.id)
      AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);

    DELETE FROM FORMULE_RESULTAT_SERVICE_REF WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
    DELETE FROM FORMULE_RESULTAT_SERVICE WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
    DELETE FROM FORMULE_RESULTAT_VH_REF WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
    DELETE FROM FORMULE_RESULTAT_VH WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
    DELETE FROM FORMULE_RESULTAT WHERE TO_DELETE = 1 AND intervenant_id = CALCULER.INTERVENANT_ID;

    OSE_EVENT.ON_AFTER_FORMULE_CALC( CALCULER.INTERVENANT_ID );
  END;



  PROCEDURE CALCULER_TBL( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    intervenant_id NUMERIC;
    TYPE r_cursor IS REF CURSOR;
    diff_cur r_cursor;
  BEGIN
    OPEN diff_cur FOR 'WITH interv AS (SELECT id intervenant_id, intervenant.* FROM intervenant)
    SELECT intervenant_id FROM interv WHERE ' || unicaen_tbl.PARAMS_TO_CONDS( params );
    LOOP
      FETCH diff_cur INTO intervenant_id; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN
        CALCULER( intervenant_id );
      END;
    END LOOP;
    CLOSE diff_cur;
  END;



  FUNCTION GET_INTERVENANT RETURN NUMERIC IS
  BEGIN
    RETURN OSE_FORMULE.INTERVENANT_ID;
  END;

  PROCEDURE SET_INTERVENANT( INTERVENANT_ID NUMERIC DEFAULT NULL) IS
  BEGIN
    IF SET_INTERVENANT.INTERVENANT_ID = -1 THEN
      OSE_FORMULE.INTERVENANT_ID := NULL;
    ELSE
      OSE_FORMULE.INTERVENANT_ID := SET_INTERVENANT.INTERVENANT_ID;
    END IF;
  END;

  FUNCTION MATCH_INTERVENANT(INTERVENANT_ID NUMERIC DEFAULT NULL) RETURN NUMERIC IS
  BEGIN
    IF OSE_FORMULE.INTERVENANT_ID IS NULL OR OSE_FORMULE.INTERVENANT_ID = MATCH_INTERVENANT.INTERVENANT_ID THEN
      RETURN 1;
    ELSE
      RETURN 0;
    END IF;
  END;
END OSE_FORMULE;
