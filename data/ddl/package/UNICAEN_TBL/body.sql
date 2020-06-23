CREATE OR REPLACE PACKAGE BODY "UNICAEN_TBL" AS

  FUNCTION MAKE_PARAMS(
    c1 VARCHAR2 DEFAULT NULL, v1 VARCHAR2 DEFAULT NULL,
    c2 VARCHAR2 DEFAULT NULL, v2 VARCHAR2 DEFAULT NULL,
    c3 VARCHAR2 DEFAULT NULL, v3 VARCHAR2 DEFAULT NULL,
    c4 VARCHAR2 DEFAULT NULL, v4 VARCHAR2 DEFAULT NULL,
    c5 VARCHAR2 DEFAULT NULL, v5 VARCHAR2 DEFAULT NULL,
    sqlcond CLOB DEFAULT NULL
  ) RETURN t_params IS
    params t_params;
  BEGIN
    IF c1 IS NOT NULL THEN
      params.c1 := c1;
      params.v1 := v1;
    END IF;
    IF c2 IS NOT NULL THEN
      params.c2 := c2;
      params.v2 := v2;
    END IF;
    IF c3 IS NOT NULL THEN
      params.c3 := c3;
      params.v3 := v3;
    END IF;
    IF c4 IS NOT NULL THEN
      params.c4 := c4;
      params.v4 := v4;
    END IF;
    IF c5 IS NOT NULL THEN
      params.c5 := c5;
      params.v5 := v5;
    END IF;
    params.sqlcond := sqlcond;

    RETURN params;
  END;



  PROCEDURE DEMANDE_CALCUL( TBL_NAME VARCHAR2 ) IS
    p t_params;
  BEGIN
    DEMANDE_CALCUL( tbl_name, p );
  END;



  PROCEDURE DEMANDE_CALCUL( TBL_NAME VARCHAR2, CONDS CLOB ) IS
    p t_params;
  BEGIN
    p.sqlcond := CONDS;
    DEMANDE_CALCUL( tbl_name, p );
  END;



  PROCEDURE DEMANDE_CALCUL( TBL_NAME VARCHAR2, PARAMS t_params ) IS
  BEGIN
    INSERT INTO tbl_dems (
      ID,
      TBL_NAME,
      c1, v1,
      c2, v2,
      c3, v3,
      c4, v4,
      c5, v5,
      sqlcond
    ) VALUES (
      TBL_DEMS_ID_SEQ.NEXTVAL,
      TBL_NAME,
      PARAMS.c1, PARAMS.v1,
      PARAMS.c2, PARAMS.v2,
      PARAMS.c3, PARAMS.v3,
      PARAMS.c4, PARAMS.v4,
      PARAMS.c5, PARAMS.v5,
      PARAMS.sqlcond
    );
  END;



  FUNCTION PARAMS_FROM_DEMS( TBL_NAME VARCHAR2 ) RETURN t_params IS
    res t_params;
    conds CLOB := '';
    cond CLOB;
  BEGIN
    FOR d IN (
      SELECT *
      FROM   tbl_dems
      WHERE  tbl_name = PARAMS_FROM_DEMS.TBL_NAME
    )
    LOOP

      cond := '';

      IF d.c1 IS NOT NULL THEN
        IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF;
        IF d.v1 IS NULL THEN
          cond := cond || d.c1 || ' IS NULL';
        ELSE
          cond := cond || d.c1 || '=' || d.v1;
        END IF;
      END IF;

      IF d.c2 IS NOT NULL THEN
        IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF;
        IF d.v2 IS NULL THEN
          cond := cond || d.c2 || ' IS NULL';
        ELSE
          cond := cond || d.c2 || '=' || d.v2;
        END IF;
      END IF;

      IF d.c3 IS NOT NULL THEN
        IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF;
        IF d.v3 IS NULL THEN
          cond := cond || d.c3 || ' IS NULL';
        ELSE
          cond := cond || d.c3 || '=' || d.v3;
        END IF;
      END IF;

      IF d.c4 IS NOT NULL THEN
        IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF;
        IF d.v4 IS NULL THEN
          cond := cond || d.c4 || ' IS NULL';
        ELSE
          cond := cond || d.c4 || '=' || d.v4;
        END IF;
      END IF;

      IF d.c5 IS NOT NULL THEN
        IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF;
        IF d.v5 IS NULL THEN
          cond := cond || d.c5 || ' IS NULL';
        ELSE
          cond := cond || d.c5 || '=' || d.v5;
        END IF;
      END IF;

      IF d.sqlcond IS NOT NULL THEN
        IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF;
        cond := cond || '(' || d.sqlcond || ')';
      END IF;

      IF conds IS NOT NULL THEN
        conds := conds || ' OR ';
      END IF;
      conds := conds || '(' || cond || ')';
    END LOOP;

    res.sqlcond := conds;
    DELETE FROM tbl_dems WHERE tbl_name = PARAMS_FROM_DEMS.TBL_NAME;
    RETURN res;
  END;



  FUNCTION PARAMS_TO_CONDS ( PARAMS UNICAEN_TBL.T_PARAMS, alias VARCHAR2 DEFAULT NULL ) RETURN CLOB IS
    cond CLOB;
    a VARCHAR2(30);
  BEGIN
    IF alias IS NULL THEN
      a := '';
    ELSE
      a := alias || '.';
    END IF;
    IF params.c1 IS NOT NULL THEN
      IF params.v1 IS NULL THEN
        cond := cond || a || params.c1 || ' IS NULL';
      ELSE
        cond := cond || a || params.c1 || '=' || params.v1;
      END IF;
    END IF;

    IF params.c2 IS NOT NULL THEN
      IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF;
      IF params.v2 IS NULL THEN
        cond := cond || a || params.c2 || ' IS NULL';
      ELSE
        cond := cond || a || params.c2 || '=' || params.v2;
      END IF;
    END IF;

    IF params.c3 IS NOT NULL THEN
      IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF;
      IF params.v3 IS NULL THEN
        cond := cond || a || params.c3 || ' IS NULL';
      ELSE
        cond := cond || a || params.c3 || '=' || params.v3;
      END IF;
    END IF;

    IF params.c4 IS NOT NULL THEN
      IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF;
      IF params.v4 IS NULL THEN
        cond := cond || a || params.c4 || ' IS NULL';
      ELSE
        cond := cond || a || params.c4 || '=' || params.v4;
      END IF;
    END IF;

    IF params.c5 IS NOT NULL THEN
      IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF;
      IF params.v5 IS NULL THEN
        cond := cond || a || params.c5 || ' IS NULL';
      ELSE
        cond := cond || a || params.c5 || '=' || params.v5;
      END IF;
    END IF;

    IF params.sqlcond IS NOT NULL THEN
      IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF;
      cond := cond || '(' || params.sqlcond || ')';
    END IF;

    IF cond IS NULL THEN cond := '1=1'; END IF;

    RETURN cond;
  END;



  PROCEDURE CALCULER( TBL_NAME VARCHAR2 ) IS
    p t_params;
  BEGIN
    ANNULER_DEMANDES( TBL_NAME );
    CALCULER(TBL_NAME, p);
  END;



  PROCEDURE CALCULER( TBL_NAME VARCHAR2, CONDS CLOB ) IS
    p t_params;
  BEGIN
    p.sqlcond := CONDS;
    CALCULER(TBL_NAME, p);
  END;



  PROCEDURE CALCULER( TBL_NAME VARCHAR2, PARAMS t_params ) IS
    calcul_proc varchar2(30);
  BEGIN
    IF NOT UNICAEN_TBL.ACTIV_CALCULS THEN RETURN; END IF;

    SELECT custom_calcul_proc INTO calcul_proc FROM tbl WHERE tbl_name = CALCULER.TBL_NAME;

    UNICAEN_TBL.CALCUL_PROC_PARAMS := PARAMS;
    IF calcul_proc IS NOT NULL THEN
      EXECUTE IMMEDIATE
        'BEGIN ' || calcul_proc || '(UNICAEN_TBL.CALCUL_PROC_PARAMS); END;'
      ;
    ELSE
      EXECUTE IMMEDIATE
        'BEGIN UNICAEN_TBL.C_' || TBL_NAME || '(UNICAEN_TBL.CALCUL_PROC_PARAMS); END;'
      ;
    END IF;

  END;



  PROCEDURE ANNULER_DEMANDES IS
  BEGIN
    DELETE FROM tbl_dems;
  END;



  PROCEDURE ANNULER_DEMANDES( TBL_NAME VARCHAR2 ) IS
  BEGIN
    DELETE FROM tbl_dems WHERE tbl_name = ANNULER_DEMANDES.tbl_name;
  END;



  FUNCTION HAS_DEMANDES RETURN BOOLEAN IS
    has_dems NUMERIC;
  BEGIN
    SELECT count(*) INTO has_dems from tbl_dems where rownum = 1;

    RETURN has_dems = 1;
  END;



  PROCEDURE CALCULER_DEMANDES IS
    dems t_params;
  BEGIN
    FOR d IN (
      SELECT DISTINCT tbl_name FROM tbl_dems
    ) LOOP
      dems := PARAMS_FROM_DEMS( d.tbl_name );
      calculer( d.tbl_name, dems );
    END LOOP;

    IF HAS_DEMANDES THEN -- pour les boucles !!
      CALCULER_DEMANDES;
    END IF;
  END;



  -- AUTOMATIC GENERATION --

  PROCEDURE C_AGREMENT( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_AGREMENT SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_AGREMENT t
    USING (

      SELECT
        tv.*
      FROM
        (WITH i_s AS (
          SELECT
            fr.intervenant_id,
            ep.structure_id ep_structure_id
          FROM
            formule_resultat fr
            JOIN type_volume_horaire  tvh ON tvh.code = ''PREVU'' AND tvh.id = fr.type_volume_horaire_id
            JOIN etat_volume_horaire  evh ON evh.code = ''valide'' AND evh.id = fr.etat_volume_horaire_id

            JOIN formule_resultat_service frs ON frs.formule_resultat_id = fr.id
            JOIN service s ON s.id = frs.service_id
            JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
          WHERE
            frs.total > 0
        ),
        avi AS (
            SELECT
                i.code                code_intervenant,
                i.annee_id            annee_id,
                a.type_agrement_id    type_agrement,
                a.id             agrement_id,
                tas.duree_vie         duree_vie,
                i.annee_id+duree_vie date_validite
            FROM intervenant i
            JOIN type_agrement_statut tas ON tas.statut_intervenant_id = i.statut_id
            JOIN agrement a ON a.intervenant_id = i.id AND tas.type_agrement_id = a.type_agrement_id AND a.histo_destruction IS NULL
        )
        SELECT DISTINCT "ANNEE_ID","ANNEE_AGREMENT","TYPE_AGREMENT_ID","INTERVENANT_ID","CODE_INTERVENANT","STRUCTURE_ID","OBLIGATOIRE","AGREMENT_ID","DUREE_VIE","RANK" FROM (
            SELECT
              i.annee_id                     annee_id,
              CASE
                WHEN NVL(NVL(a.id, avi.agrement_id),0) = 0
                THEN NULL
                ELSE NVL(avi.annee_id, i.annee_id) END   annee_agrement,
              tas.type_agrement_id                       type_agrement_id,
              i.id                                       intervenant_id,
              i.code                                     code_intervenant,
              null                                       structure_id,
              tas.obligatoire                            obligatoire,
              NVL(a.id, avi.agrement_id)                 agrement_id,
              tas.duree_vie                              duree_vie,
              RANK() OVER(
                PARTITION BY i.code,i.annee_id ORDER BY
                CASE
                WHEN NVL(NVL(a.id, avi.agrement_id),0) = 0
                THEN NULL
                ELSE NVL(avi.annee_id, i.annee_id) END DESC
              ) rank
            FROM
              type_agrement                  ta
              JOIN type_agrement_statut      tas ON tas.type_agrement_id = ta.id
                                                AND tas.histo_destruction IS NULL

              JOIN intervenant                 i ON i.histo_destruction IS NULL
                                               -- AND (tas.premier_recrutement IS NULL OR NVL(i.premier_recrutement,0) = tas.premier_recrutement)
                                                AND i.statut_id = tas.statut_intervenant_id

              JOIN                           i_s ON i_s.intervenant_id = i.id


              LEFT JOIN agrement               a ON a.type_agrement_id = ta.id
                                                AND a.intervenant_id = i.id
                                                AND a.histo_destruction IS NULL

              LEFT JOIN                      avi ON i.code = avi.code_intervenant
                                                AND tas.type_agrement_id = avi.type_agrement
                                                AND i.annee_id < avi.date_validite
                                                AND i.annee_id >= avi.annee_id

            WHERE
              ta.code = ''CONSEIL_ACADEMIQUE'')
        WHERE
          rank = 1

        UNION ALL
        SELECT DISTINCT "ANNEE_ID","ANNEE_AGREMENT","TYPE_AGREMENT_ID","INTERVENANT_ID","CODE_INTERVENANT","STRUCTURE_ID","OBLIGATOIRE","AGREMENT_ID","DUREE_VIE","RANK" FROM (
            SELECT
              i.annee_id                                  annee_id,
              CASE
                WHEN NVL(NVL(a.id, avi.agrement_id),0) = 0
                THEN NULL
                ELSE NVL(avi.annee_id, i.annee_id) END    annee_agrement,
              tas.type_agrement_id                        type_agrement_id,
              i.id                                        intervenant_id,
              i.code                                      code_intervenant,
              a.structure_id                            structure_id,
              tas.obligatoire                             obligatoire,
              NVL(a.id, avi.agrement_id)                  agrement_id,
              tas.duree_vie                               duree_vie,
              RANK() OVER(
                PARTITION BY i.code,i.annee_id,i_s.ep_structure_id ORDER BY
                CASE
                WHEN NVL(NVL(a.id, avi.agrement_id),0) = 0
                THEN NULL
                ELSE NVL(avi.annee_id, i.annee_id) END DESC
              ) rank
            FROM
              type_agrement                   ta
              JOIN type_agrement_statut      tas ON tas.type_agrement_id = ta.id
                                                AND tas.histo_destruction IS NULL

              JOIN intervenant                 i ON i.histo_destruction IS NULL
                                                AND i.statut_id = tas.statut_intervenant_id

              JOIN                           i_s ON i_s.intervenant_id = i.id

              LEFT JOIN agrement               a ON a.type_agrement_id = ta.id
                                                AND a.structure_id = i_s.ep_structure_id
                                                AND a.intervenant_id = i.id
                                                AND a.histo_destruction IS NULL

              LEFT JOIN                      avi ON i.code = avi.code_intervenant
                                                AND tas.type_agrement_id = avi.type_agrement
                                                AND a.id = avi.agrement_id
                                                AND i.annee_id < avi.date_validite
                                                AND i.annee_id >= avi.annee_id


            WHERE
              ta.code = ''CONSEIL_RESTREINT'')
        WHERE
          rank = 1) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.TYPE_AGREMENT_ID = v.TYPE_AGREMENT_ID
        AND t.INTERVENANT_ID   = v.INTERVENANT_ID
        AND COALESCE(t.STRUCTURE_ID,0) = COALESCE(v.STRUCTURE_ID,0)
        AND COALESCE(t.ANNEE_AGREMENT,0) = COALESCE(v.ANNEE_AGREMENT,0)

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID         = v.ANNEE_ID,
      OBLIGATOIRE      = v.OBLIGATOIRE,
      AGREMENT_ID      = v.AGREMENT_ID,
      CODE_INTERVENANT = v.CODE_INTERVENANT,
      DUREE_VIE        = v.DUREE_VIE,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      TYPE_AGREMENT_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      OBLIGATOIRE,
      AGREMENT_ID,
      ANNEE_AGREMENT,
      CODE_INTERVENANT,
      DUREE_VIE,
      TO_DELETE

    ) VALUES (

      TBL_AGREMENT_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.TYPE_AGREMENT_ID,
      v.INTERVENANT_ID,
      v.STRUCTURE_ID,
      v.OBLIGATOIRE,
      v.AGREMENT_ID,
      v.ANNEE_AGREMENT,
      v.CODE_INTERVENANT,
      v.DUREE_VIE,
      0

    );

    DELETE TBL_AGREMENT WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_CHARGENS( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_CHARGENS SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_CHARGENS t
    USING (

      SELECT
        tv.*
      FROM
        (WITH t AS (
        SELECT
          n.annee_id                        annee_id,
          n.noeud_id                        noeud_id,
          sn.scenario_id                    scenario_id,
          sne.type_heures_id                type_heures_id,
          ti.id                             type_intervention_id,

          n.element_pedagogique_id          element_pedagogique_id,
          n.element_pedagogique_etape_id    etape_id,
          sne.etape_id                      etape_ens_id,
          n.structure_id                    structure_id,
          n.groupe_type_formation_id        groupe_type_formation_id,

          vhe.heures                        heures,
          vhe.heures * ti.taux_hetd_service hetd,

          GREATEST(COALESCE(sns.ouverture, 1),1)                                           ouverture,
          GREATEST(COALESCE(sns.dedoublement, snsetp.dedoublement, csdd.dedoublement,1),1) dedoublement,
          COALESCE(sns.assiduite,1)                                                        assiduite,
          sne.effectif*COALESCE(sns.assiduite,1)                                           effectif,

          SUM(sne.effectif*COALESCE(sns.assiduite,1)) OVER (PARTITION BY n.noeud_id, sn.scenario_id, ti.id) t_effectif

        FROM
                    scenario_noeud_effectif    sne
               JOIN etape                        e ON e.id = sne.etape_id
                                                  AND e.histo_destruction IS NULL

               JOIN scenario_noeud              sn ON sn.id = sne.scenario_noeud_id
                                                  AND sn.histo_destruction IS NULL

               JOIN tbl_noeud                       n ON n.noeud_id = sn.noeud_id

               JOIN volume_horaire_ens         vhe ON vhe.element_pedagogique_id = n.element_pedagogique_id
                                                  AND vhe.histo_destruction IS NULL
                                                  AND vhe.heures > 0

               JOIN type_intervention           ti ON ti.id = vhe.type_intervention_id

          LEFT JOIN tbl_noeud                 netp ON netp.etape_id = e.id

          LEFT JOIN scenario_noeud           snetp ON snetp.scenario_id = sn.scenario_id
                                                  AND snetp.noeud_id = netp.noeud_id
                                                  AND snetp.histo_destruction IS NULL

          LEFT JOIN scenario_noeud_seuil    snsetp ON snsetp.scenario_noeud_id = snetp.id
                                                  AND snsetp.type_intervention_id = ti.id

          LEFT JOIN tbl_chargens_seuils_def   csdd ON csdd.annee_id = n.annee_id
                                                  AND csdd.scenario_id = sn.scenario_id
                                                  AND csdd.type_intervention_id = ti.id
                                                  AND csdd.groupe_type_formation_id = n.groupe_type_formation_id
                                                  AND csdd.structure_id = n.structure_id

          LEFT JOIN scenario_noeud_seuil       sns ON sns.scenario_noeud_id = sn.id
                                                  AND sns.type_intervention_id = ti.id
        )
        SELECT
          annee_id,
          noeud_id,
          scenario_id,
          type_heures_id,
          type_intervention_id,

          element_pedagogique_id,
          etape_id,
          etape_ens_id,
          structure_id,
          groupe_type_formation_id,

          ouverture,
          dedoublement,
          assiduite,
          effectif,
          heures heures_ens,
          --t_effectif,

          CASE WHEN t_effectif < ouverture THEN 0 ELSE
            CEIL( t_effectif / dedoublement ) * effectif / t_effectif
          END groupes,

          CASE WHEN t_effectif < ouverture THEN 0 ELSE
            CEIL( t_effectif / dedoublement ) * heures * effectif / t_effectif
          END heures,

          CASE WHEN t_effectif < ouverture THEN 0 ELSE
            CEIL( t_effectif / dedoublement ) * hetd * effectif / t_effectif
          END  hetd

        FROM
          t) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.ANNEE_ID                 = v.ANNEE_ID
        AND t.NOEUD_ID                 = v.NOEUD_ID
        AND t.SCENARIO_ID              = v.SCENARIO_ID
        AND t.TYPE_HEURES_ID           = v.TYPE_HEURES_ID
        AND t.TYPE_INTERVENTION_ID     = v.TYPE_INTERVENTION_ID
        AND t.ELEMENT_PEDAGOGIQUE_ID   = v.ELEMENT_PEDAGOGIQUE_ID
        AND t.ETAPE_ID                 = v.ETAPE_ID
        AND t.ETAPE_ENS_ID             = v.ETAPE_ENS_ID
        AND t.STRUCTURE_ID             = v.STRUCTURE_ID
        AND t.GROUPE_TYPE_FORMATION_ID = v.GROUPE_TYPE_FORMATION_ID

    ) WHEN MATCHED THEN UPDATE SET

      OUVERTURE                = v.OUVERTURE,
      DEDOUBLEMENT             = v.DEDOUBLEMENT,
      ASSIDUITE                = v.ASSIDUITE,
      EFFECTIF                 = v.EFFECTIF,
      HEURES_ENS               = v.HEURES_ENS,
      GROUPES                  = v.GROUPES,
      HEURES                   = v.HEURES,
      HETD                     = v.HETD,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      NOEUD_ID,
      SCENARIO_ID,
      TYPE_HEURES_ID,
      TYPE_INTERVENTION_ID,
      ELEMENT_PEDAGOGIQUE_ID,
      ETAPE_ID,
      ETAPE_ENS_ID,
      STRUCTURE_ID,
      GROUPE_TYPE_FORMATION_ID,
      OUVERTURE,
      DEDOUBLEMENT,
      ASSIDUITE,
      EFFECTIF,
      HEURES_ENS,
      GROUPES,
      HEURES,
      HETD,
      TO_DELETE

    ) VALUES (

      TBL_CHARGENS_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.NOEUD_ID,
      v.SCENARIO_ID,
      v.TYPE_HEURES_ID,
      v.TYPE_INTERVENTION_ID,
      v.ELEMENT_PEDAGOGIQUE_ID,
      v.ETAPE_ID,
      v.ETAPE_ENS_ID,
      v.STRUCTURE_ID,
      v.GROUPE_TYPE_FORMATION_ID,
      v.OUVERTURE,
      v.DEDOUBLEMENT,
      v.ASSIDUITE,
      v.EFFECTIF,
      v.HEURES_ENS,
      v.GROUPES,
      v.HEURES,
      v.HETD,
      0

    );

    DELETE TBL_CHARGENS WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_CHARGENS_SEUILS_DEF( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_CHARGENS_SEUILS_DEF SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_CHARGENS_SEUILS_DEF t
    USING (

      SELECT
        tv.*
      FROM
        (SELECT
          sta.annee_id,
          sta.scenario_id,
          s.structure_id,
          gtf.groupe_type_formation_id,
          sta.type_intervention_id,
          COALESCE(sc1.dedoublement, sc2.dedoublement, sc3.dedoublement, sc4.dedoublement) dedoublement
        FROM
          (SELECT DISTINCT scenario_id, type_intervention_id, annee_id FROM seuil_charge WHERE histo_destruction IS NULL) sta
          JOIN (SELECT DISTINCT structure_id FROM noeud WHERE structure_id IS NOT NULL) s ON 1=1
          JOIN (SELECT id groupe_type_formation_id FROM groupe_type_formation) gtf ON 1=1

          LEFT JOIN seuil_charge sc1 ON
            sc1.histo_destruction            IS NULL
            AND sc1.annee_id                 = sta.annee_id
            AND sc1.scenario_id              = sta.scenario_id
            AND sc1.type_intervention_id     = sta.type_intervention_id
            AND sc1.structure_id             = s.structure_id
            AND sc1.groupe_type_formation_id = gtf.groupe_type_formation_id

          LEFT JOIN seuil_charge sc2 ON
            sc2.histo_destruction            IS NULL
            AND sc2.annee_id                 = sta.annee_id
            AND sc2.scenario_id              = sta.scenario_id
            AND sc2.type_intervention_id     = sta.type_intervention_id
            AND sc2.structure_id             = s.structure_id
            AND sc2.groupe_type_formation_id IS NULL

          LEFT JOIN seuil_charge sc3 ON
            sc3.histo_destruction            IS NULL
            AND sc3.annee_id                 = sta.annee_id
            AND sc3.scenario_id              = sta.scenario_id
            AND sc3.type_intervention_id     = sta.type_intervention_id
            AND sc3.structure_id             IS NULL
            AND sc3.groupe_type_formation_id = gtf.groupe_type_formation_id

          LEFT JOIN seuil_charge sc4 ON
            sc4.histo_destruction            IS NULL
            AND sc4.annee_id                 = sta.annee_id
            AND sc4.scenario_id              = sta.scenario_id
            AND sc4.type_intervention_id     = sta.type_intervention_id
            AND sc4.structure_id             IS NULL
            AND sc4.groupe_type_formation_id IS NULL
        WHERE
          COALESCE(sc1.dedoublement, sc2.dedoublement, sc3.dedoublement, sc4.dedoublement, 1) <> 1) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.ANNEE_ID                 = v.ANNEE_ID
        AND t.SCENARIO_ID              = v.SCENARIO_ID
        AND t.STRUCTURE_ID             = v.STRUCTURE_ID
        AND t.GROUPE_TYPE_FORMATION_ID = v.GROUPE_TYPE_FORMATION_ID
        AND t.TYPE_INTERVENTION_ID     = v.TYPE_INTERVENTION_ID

    ) WHEN MATCHED THEN UPDATE SET

      DEDOUBLEMENT             = v.DEDOUBLEMENT,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      SCENARIO_ID,
      STRUCTURE_ID,
      GROUPE_TYPE_FORMATION_ID,
      TYPE_INTERVENTION_ID,
      DEDOUBLEMENT,
      TO_DELETE

    ) VALUES (

      TBL_CHARGENS_SEUILS_DEF_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.SCENARIO_ID,
      v.STRUCTURE_ID,
      v.GROUPE_TYPE_FORMATION_ID,
      v.TYPE_INTERVENTION_ID,
      v.DEDOUBLEMENT,
      0

    );

    DELETE TBL_CHARGENS_SEUILS_DEF WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_CLOTURE_REALISE( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_CLOTURE_REALISE SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_CLOTURE_REALISE t
    USING (

      SELECT
        tv.*
      FROM
        (WITH t AS (
          SELECT
            i.annee_id              annee_id,
            i.id                    intervenant_id,
            si.peut_cloturer_saisie peut_cloturer_saisie,
            CASE WHEN v.id IS NULL THEN 0 ELSE 1 END cloture
          FROM
                      intervenant         i
                 JOIN statut_intervenant si ON si.id = i.statut_id
                 JOIN type_validation    tv ON tv.code = ''CLOTURE_REALISE''

            LEFT JOIN validation          v ON v.intervenant_id = i.id
                                           AND v.type_validation_id = tv.id
                                           AND v.histo_destruction IS NULL

          WHERE
            i.histo_destruction IS NULL
        )
        SELECT
          annee_id,
          intervenant_id,
          peut_cloturer_saisie,
          CASE WHEN sum(cloture) = 0 THEN 0 ELSE 1 END cloture
        FROM
          t
        GROUP BY
          annee_id,
          intervenant_id,
          peut_cloturer_saisie) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID = v.INTERVENANT_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID             = v.ANNEE_ID,
      PEUT_CLOTURER_SAISIE = v.PEUT_CLOTURER_SAISIE,
      CLOTURE              = v.CLOTURE,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      PEUT_CLOTURER_SAISIE,
      CLOTURE,
      TO_DELETE

    ) VALUES (

      TBL_CLOTURE_REALISE_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_CLOTURER_SAISIE,
      v.CLOTURE,
      0

    );

    DELETE TBL_CLOTURE_REALISE WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_CONTRAT( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_CONTRAT SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_CONTRAT t
    USING (

      SELECT
        tv.*
      FROM
        (WITH t AS (
          SELECT
            i.annee_id                                                                annee_id,
            i.id                                                                      intervenant_id,
            si.peut_avoir_contrat                                                     peut_avoir_contrat,
            NVL(ep.structure_id, i.structure_id)                                      structure_id,
            CASE WHEN evh.code IN (''contrat-edite'',''contrat-signe'') THEN 1 ELSE 0 END edite,
            CASE WHEN evh.code IN (''contrat-signe'')                 THEN 1 ELSE 0 END signe
          FROM
                      intervenant                 i

                 JOIN statut_intervenant         si ON si.id = i.statut_id

                 JOIN service                     s ON s.intervenant_id = i.id
                                                   AND s.histo_destruction IS NULL

                 JOIN type_volume_horaire       tvh ON tvh.code = ''PREVU''

                 JOIN volume_horaire             vh ON vh.service_id = s.id
                                                   AND vh.histo_destruction IS NULL
                                                   AND vh.heures <> 0
                                                   AND vh.type_volume_horaire_id = tvh.id
                                                   AND vh.motif_non_paiement_id IS NULL

                 JOIN v_volume_horaire_etat     vhe ON vhe.volume_horaire_id = vh.id

                 JOIN etat_volume_horaire       evh ON evh.id = vhe.etat_volume_horaire_id
                                                   AND evh.code IN (''valide'', ''contrat-edite'', ''contrat-signe'')

                 JOIN element_pedagogique        ep ON ep.id = s.element_pedagogique_id

          WHERE
            i.histo_destruction IS NULL
            AND NOT (si.peut_avoir_contrat = 0 AND evh.code = ''valide'')

          UNION ALL

          SELECT
            i.annee_id                                                                annee_id,
            i.id                                                                      intervenant_id,
            si.peut_avoir_contrat                                                     peut_avoir_contrat,
            s.structure_id                                                            structure_id,
            CASE WHEN evh.code IN (''contrat-edite'',''contrat-signe'') THEN 1 ELSE 0 END edite,
            CASE WHEN evh.code IN (''contrat-signe'')                 THEN 1 ELSE 0 END signe
          FROM
                      intervenant                 i

                 JOIN statut_intervenant         si ON si.id = i.statut_id

                 JOIN service_referentiel         s ON s.intervenant_id = i.id
                                                   AND s.histo_destruction IS NULL

                 JOIN type_volume_horaire       tvh ON tvh.code = ''PREVU''

                 JOIN volume_horaire_ref         vh ON vh.service_referentiel_id = s.id
                                                   AND vh.histo_destruction IS NULL
                                                   AND vh.heures <> 0
                                                   AND vh.type_volume_horaire_id = tvh.id

                 JOIN v_volume_horaire_ref_etat vhe ON vhe.volume_horaire_ref_id = vh.id

                 JOIN etat_volume_horaire       evh ON evh.id = vhe.etat_volume_horaire_id
                                                   AND evh.code IN (''valide'', ''contrat-edite'', ''contrat-signe'')

          WHERE
            i.histo_destruction IS NULL
            AND NOT (si.peut_avoir_contrat = 0 AND evh.code = ''valide'')
        )
        SELECT
          annee_id,
          intervenant_id,
          peut_avoir_contrat,
          structure_id,
          count(*) as nbvh,
          sum(edite) as edite,
          sum(signe) as signe
        FROM
          t
        GROUP BY
          annee_id,
          intervenant_id,
          peut_avoir_contrat,
          structure_id) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID = v.INTERVENANT_ID
        AND COALESCE(t.STRUCTURE_ID,0) = COALESCE(v.STRUCTURE_ID,0)

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID           = v.ANNEE_ID,
      PEUT_AVOIR_CONTRAT = v.PEUT_AVOIR_CONTRAT,
      NBVH               = v.NBVH,
      EDITE              = v.EDITE,
      SIGNE              = v.SIGNE,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      PEUT_AVOIR_CONTRAT,
      STRUCTURE_ID,
      NBVH,
      EDITE,
      SIGNE,
      TO_DELETE

    ) VALUES (

      TBL_CONTRAT_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_AVOIR_CONTRAT,
      v.STRUCTURE_ID,
      v.NBVH,
      v.EDITE,
      v.SIGNE,
      0

    );

    DELETE TBL_CONTRAT WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_DMEP_LIQUIDATION( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_DMEP_LIQUIDATION SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_DMEP_LIQUIDATION t
    USING (

      SELECT
        tv.*
      FROM
        (SELECT
          annee_id,
          type_ressource_id,
          structure_id,
          SUM(heures) heures
        FROM
        (
          SELECT
            i.annee_id,
            cc.type_ressource_id,
            COALESCE( ep.structure_id, i.structure_id ) structure_id,
            mep.heures
          FROM
                      mise_en_paiement         mep
                 JOIN centre_cout               cc ON cc.id = mep.centre_cout_id
                 JOIN formule_resultat_service frs ON frs.id = mep.formule_res_service_id
                 JOIN service                    s ON s.id = frs.service_id
                 JOIN intervenant                i ON i.id = s.intervenant_id
            LEFT JOIN element_pedagogique       ep ON ep.id = s.element_pedagogique_id
          WHERE
            mep.histo_destruction IS NULL

          UNION ALL

          SELECT
            i.annee_id,
            cc.type_ressource_id,
            sr.structure_id structure_id,
            heures
          FROM
                      mise_en_paiement              mep
                 JOIN centre_cout                    cc ON cc.id = mep.centre_cout_id
                 JOIN formule_resultat_service_ref frsr ON frsr.id = mep.formule_res_service_ref_id
                 JOIN service_referentiel            sr ON sr.id = frsr.service_referentiel_id
                 JOIN intervenant                     i ON i.id = sr.intervenant_id

          WHERE
            mep.histo_destruction IS NULL

        ) t1
        GROUP BY
          annee_id, type_ressource_id, structure_id) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.ANNEE_ID          = v.ANNEE_ID
        AND t.TYPE_RESSOURCE_ID = v.TYPE_RESSOURCE_ID
        AND t.STRUCTURE_ID      = v.STRUCTURE_ID

    ) WHEN MATCHED THEN UPDATE SET

      HEURES            = v.HEURES,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      TYPE_RESSOURCE_ID,
      STRUCTURE_ID,
      HEURES,
      TO_DELETE

    ) VALUES (

      TBL_DMEP_LIQUIDATION_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.TYPE_RESSOURCE_ID,
      v.STRUCTURE_ID,
      v.HEURES,
      0

    );

    DELETE TBL_DMEP_LIQUIDATION WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_DOSSIER( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_DOSSIER SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_DOSSIER t
    USING (

      SELECT
        tv.*
      FROM
        (SELECT
          i.annee_id,
          i.id intervenant_id,
          si.peut_saisir_dossier,
          d.id dossier_id,
          v.id validation_id
        FROM
                    intervenant         i
               JOIN statut_intervenant si ON si.id = i.statut_id
          LEFT JOIN dossier             d ON d.intervenant_id = i.id
                                      AND d.histo_destruction IS NULL

               JOIN type_validation tv ON tv.code = ''DONNEES_PERSO_PAR_COMP''
          LEFT JOIN validation       v ON v.intervenant_id = i.id
                                      AND v.type_validation_id = tv.id
                                      AND v.histo_destruction IS NULL
        WHERE
          i.histo_destruction IS NULL) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID = v.INTERVENANT_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID            = v.ANNEE_ID,
      PEUT_SAISIR_DOSSIER = v.PEUT_SAISIR_DOSSIER,
      DOSSIER_ID          = v.DOSSIER_ID,
      VALIDATION_ID       = v.VALIDATION_ID,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      PEUT_SAISIR_DOSSIER,
      DOSSIER_ID,
      VALIDATION_ID,
      TO_DELETE

    ) VALUES (

      TBL_DOSSIER_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_SAISIR_DOSSIER,
      v.DOSSIER_ID,
      v.VALIDATION_ID,
      0

    );

    DELETE TBL_DOSSIER WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_PAIEMENT( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_PAIEMENT SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_PAIEMENT t
    USING (

      SELECT
        tv.*
      FROM
        (SELECT
          i.annee_id                                  annee_id,
          frs.service_id                              service_id,
          null                                        service_referentiel_id,
          frs.id                                      formule_res_service_id,
          null                                        formule_res_service_ref_id,
          i.id                                        intervenant_id,
          COALESCE( ep.structure_id, i.structure_id ) structure_id,
          mep.id                                      mise_en_paiement_id,
          mep.periode_paiement_id                     periode_paiement_id,
          frs.heures_compl_fi + frs.heures_compl_fc + frs.heures_compl_fa + frs.heures_compl_fc_majorees heures_a_payer,
          count(*) OVER(PARTITION BY frs.id)          heures_a_payer_pond,
          NVL(mep.heures,0)                           heures_demandees,
          CASE WHEN mep.periode_paiement_id IS NULL THEN 0 ELSE mep.heures END heures_payees
        FROM
                    formule_resultat_service        frs
               JOIN type_volume_horaire             tvh ON tvh.code = ''REALISE''
               JOIN etat_volume_horaire             evh ON evh.code = ''valide''
               JOIN formule_resultat                 fr ON fr.id = frs.formule_resultat_id
                                                       AND fr.type_volume_horaire_id = tvh.id
                                                       AND fr.etat_volume_horaire_id = evh.id

               JOIN intervenant                       i ON i.id = fr.intervenant_id
               JOIN service                           s ON s.id = frs.service_id
          LEFT JOIN element_pedagogique              ep ON ep.id = s.element_pedagogique_id
          LEFT JOIN mise_en_paiement                mep ON mep.formule_res_service_id = frs.id
                                                       AND mep.histo_destruction IS NULL

        UNION ALL

        SELECT
          i.annee_id                                  annee_id,
          null                                        service_id,
          frs.service_referentiel_id                  service_referentiel_id,
          null                                        formule_res_service_id,
          frs.id                                      formule_res_service_ref_id,
          i.id                                        intervenant_id,
          s.structure_id                              structure_id,
          mep.id                                      mise_en_paiement_id,
          mep.periode_paiement_id                     periode_paiement_id,
          frs.heures_compl_referentiel                heures_a_payer,
          count(*) OVER(PARTITION BY frs.id)          heures_a_payer_pond,
          NVL(mep.heures,0)                           heures_demandees,
          CASE WHEN mep.periode_paiement_id IS NULL THEN 0 ELSE mep.heures END heures_payees
        FROM
                    formule_resultat_service_ref    frs
               JOIN type_volume_horaire             tvh ON tvh.code = ''REALISE''
               JOIN etat_volume_horaire             evh ON evh.code = ''valide''
               JOIN formule_resultat                 fr ON fr.id = frs.formule_resultat_id
                                                       AND fr.type_volume_horaire_id = tvh.id
                                                       AND fr.etat_volume_horaire_id = evh.id

               JOIN intervenant                       i ON i.id = fr.intervenant_id
               JOIN service_referentiel               s ON s.id = frs.service_referentiel_id
          LEFT JOIN mise_en_paiement                mep ON mep.formule_res_service_ref_id = frs.id
                                                       AND mep.histo_destruction IS NULL) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID             = v.INTERVENANT_ID
        AND COALESCE(t.MISE_EN_PAIEMENT_ID,0) = COALESCE(v.MISE_EN_PAIEMENT_ID,0)
        AND COALESCE(t.FORMULE_RES_SERVICE_ID,0) = COALESCE(v.FORMULE_RES_SERVICE_ID,0)
        AND COALESCE(t.FORMULE_RES_SERVICE_REF_ID,0) = COALESCE(v.FORMULE_RES_SERVICE_REF_ID,0)

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID                   = v.ANNEE_ID,
      STRUCTURE_ID               = v.STRUCTURE_ID,
      PERIODE_PAIEMENT_ID        = v.PERIODE_PAIEMENT_ID,
      HEURES_A_PAYER             = v.HEURES_A_PAYER,
      HEURES_A_PAYER_POND        = v.HEURES_A_PAYER_POND,
      HEURES_DEMANDEES           = v.HEURES_DEMANDEES,
      HEURES_PAYEES              = v.HEURES_PAYEES,
      SERVICE_ID                 = v.SERVICE_ID,
      SERVICE_REFERENTIEL_ID     = v.SERVICE_REFERENTIEL_ID,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      MISE_EN_PAIEMENT_ID,
      PERIODE_PAIEMENT_ID,
      HEURES_A_PAYER,
      HEURES_A_PAYER_POND,
      HEURES_DEMANDEES,
      HEURES_PAYEES,
      FORMULE_RES_SERVICE_ID,
      FORMULE_RES_SERVICE_REF_ID,
      SERVICE_ID,
      SERVICE_REFERENTIEL_ID,
      TO_DELETE

    ) VALUES (

      TBL_PAIEMENT_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.STRUCTURE_ID,
      v.MISE_EN_PAIEMENT_ID,
      v.PERIODE_PAIEMENT_ID,
      v.HEURES_A_PAYER,
      v.HEURES_A_PAYER_POND,
      v.HEURES_DEMANDEES,
      v.HEURES_PAYEES,
      v.FORMULE_RES_SERVICE_ID,
      v.FORMULE_RES_SERVICE_REF_ID,
      v.SERVICE_ID,
      v.SERVICE_REFERENTIEL_ID,
      0

    );

    DELETE TBL_PAIEMENT WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_PIECE_JOINTE( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_PIECE_JOINTE SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_PIECE_JOINTE t
    USING (

      SELECT
        *
      FROM
        v_tbl_piece_jointe
      WHERE
        ' || conds || '

    ) v ON (
            t.TYPE_PIECE_JOINTE_ID = v.TYPE_PIECE_JOINTE_ID
        AND t.INTERVENANT_ID       = v.INTERVENANT_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID             = v.ANNEE_ID,
      DEMANDEE             = v.DEMANDEE,
      FOURNIE              = v.FOURNIE,
      VALIDEE              = v.VALIDEE,
      HEURES_POUR_SEUIL    = v.HEURES_POUR_SEUIL,
      OBLIGATOIRE          = v.OBLIGATOIRE,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      TYPE_PIECE_JOINTE_ID,
      INTERVENANT_ID,
      DEMANDEE,
      FOURNIE,
      VALIDEE,
      HEURES_POUR_SEUIL,
      OBLIGATOIRE,
      TO_DELETE

    ) VALUES (

      TBL_PIECE_JOINTE_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.TYPE_PIECE_JOINTE_ID,
      v.INTERVENANT_ID,
      v.DEMANDEE,
      v.FOURNIE,
      v.VALIDEE,
      v.HEURES_POUR_SEUIL,
      v.OBLIGATOIRE,
      0

    );

    DELETE TBL_PIECE_JOINTE WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_PIECE_JOINTE_DEMANDE( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_PIECE_JOINTE_DEMANDE SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_PIECE_JOINTE_DEMANDE t
    USING (
      SELECT
        *
      FROM
        v_tbl_piece_jointe_demande
      WHERE
        ' || conds || '

    ) v ON (
            t.TYPE_PIECE_JOINTE_ID = v.TYPE_PIECE_JOINTE_ID
        AND t.INTERVENANT_ID       = v.INTERVENANT_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID             = v.ANNEE_ID,
      HEURES_POUR_SEUIL    = v.HEURES_POUR_SEUIL,
      OBLIGATOIRE          = v.OBLIGATOIRE,
      CODE_INTERVENANT     = v.CODE_INTERVENANT,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      TYPE_PIECE_JOINTE_ID,
      INTERVENANT_ID,
      HEURES_POUR_SEUIL,
      OBLIGATOIRE,
      CODE_INTERVENANT,
      TO_DELETE

    ) VALUES (

      TBL_PIECE_JOINTE_DEMAND_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.TYPE_PIECE_JOINTE_ID,
      v.INTERVENANT_ID,
      v.HEURES_POUR_SEUIL,
      v.OBLIGATOIRE,
      v.CODE_INTERVENANT,
      0

    );

    DELETE TBL_PIECE_JOINTE_DEMANDE WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_PIECE_JOINTE_FOURNIE( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_PIECE_JOINTE_FOURNIE SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_PIECE_JOINTE_FOURNIE t
    USING (

      SELECT
        tv.*
      FROM
        (SELECT
          i.annee_id,
          i.code code_intervenant,
          pj.type_piece_jointe_id,
          pj.intervenant_id,
          pj.id piece_jointe_id,
          v.id validation_id,
          f.id fichier_id,
          MIN(tpjs.duree_vie) duree_vie,
          MIN(i.annee_id+tpjs.duree_vie) date_validite,
          pj.date_archive date_archive
        FROM
                    piece_jointe          pj
               JOIN intervenant            i ON i.id = pj.intervenant_id
                                            AND i.histo_destruction IS NULL
               JOIN piece_jointe_fichier pjf ON pjf.piece_jointe_id = pj.id
               JOIN fichier                f ON f.id = pjf.fichier_id
                                            AND f.histo_destruction IS NULL
                JOIN type_piece_jointe_statut tpjs ON tpjs.statut_intervenant_id = i.statut_id
                                                   AND tpjs.type_piece_jointe_id = pj.type_piece_jointe_id
                                                   AND tpjs.HISTO_DESTRUCTION IS NULL

         LEFT JOIN validation             v ON v.id = pj.validation_id
                                            AND v.histo_destruction IS NULL
        WHERE
          pj.histo_destruction IS NULL
        GROUP BY
        i.annee_id,
          i.code,
          pj.type_piece_jointe_id,
          pj.intervenant_id,
          pj.id,
          v.id,
          f.id,
          pj.date_archive) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.TYPE_PIECE_JOINTE_ID = v.TYPE_PIECE_JOINTE_ID
        AND t.INTERVENANT_ID       = v.INTERVENANT_ID
        AND COALESCE(t.VALIDATION_ID,0) = COALESCE(v.VALIDATION_ID,0)
        AND COALESCE(t.FICHIER_ID,0) = COALESCE(v.FICHIER_ID,0)

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID             = v.ANNEE_ID,
      PIECE_JOINTE_ID      = v.PIECE_JOINTE_ID,
      DUREE_VIE            = v.DUREE_VIE,
      CODE_INTERVENANT     = v.CODE_INTERVENANT,
      DATE_VALIDITE        = v.DATE_VALIDITE,
      DATE_ARCHIVE         = v.DATE_ARCHIVE,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      TYPE_PIECE_JOINTE_ID,
      INTERVENANT_ID,
      VALIDATION_ID,
      FICHIER_ID,
      PIECE_JOINTE_ID,
      DUREE_VIE,
      CODE_INTERVENANT,
      DATE_VALIDITE,
      DATE_ARCHIVE,
      TO_DELETE

    ) VALUES (

      TBL_PIECE_JOINTE_FOURNI_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.TYPE_PIECE_JOINTE_ID,
      v.INTERVENANT_ID,
      v.VALIDATION_ID,
      v.FICHIER_ID,
      v.PIECE_JOINTE_ID,
      v.DUREE_VIE,
      v.CODE_INTERVENANT,
      v.DATE_VALIDITE,
      v.DATE_ARCHIVE,
      0

    );

    DELETE TBL_PIECE_JOINTE_FOURNIE WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_SERVICE( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_SERVICE SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_SERVICE t
    USING (

      SELECT
        tv.*
      FROM
        (WITH t AS (
        SELECT
          s.id                                                                                      service_id,
          s.intervenant_id                                                                          intervenant_id,
          ep.structure_id                                                                           structure_id,
          ep.id                                                                                     element_pedagogique_id,
          ep.periode_id                                                                             element_pedagogique_periode_id,
          etp.id                                                                                    etape_id,

          vh.type_volume_horaire_id                                                                 type_volume_horaire_id,
          vh.heures                                                                                 heures,
          tvh.code                                                                                  type_volume_horaire_code,

          CASE WHEN ep.histo_destruction IS NULL THEN 1 ELSE 0 END                                  element_pedagogique_histo,
          CASE WHEN etp.histo_destruction IS NULL OR cp.id IS NOT NULL THEN 1 ELSE 0 END            etape_histo,

          CASE WHEN ep.periode_id IS NOT NULL THEN
            SUM( CASE WHEN vh.periode_id <> ep.periode_id THEN 1 ELSE 0 END ) OVER( PARTITION BY vh.service_id, vh.periode_id, vh.type_volume_horaire_id, vh.type_intervention_id )
          ELSE 0 END has_heures_mauvaise_periode,

          CASE WHEN v.id IS NULL AND vh.auto_validation=0 THEN 0 ELSE 1 END valide
        FROM
          service                                       s
          LEFT JOIN element_pedagogique                ep ON ep.id = s.element_pedagogique_id
          LEFT JOIN etape                             etp ON etp.id = ep.etape_id
          LEFT JOIN chemin_pedagogique                 cp ON cp.etape_id = etp.id
                                                         AND cp.element_pedagogique_id = ep.id
                                                         AND cp.histo_destruction IS NULL

               JOIN volume_horaire                     vh ON vh.service_id = s.id
                                                         AND vh.histo_destruction IS NULL

               JOIN type_volume_horaire               tvh ON tvh.id = vh.type_volume_horaire_id

          LEFT JOIN validation_vol_horaire            vvh ON vvh.volume_horaire_id = vh.id

          LEFT JOIN validation                          v ON v.id = vvh.validation_id
                                                         AND v.histo_destruction IS NULL
        WHERE
          s.histo_destruction IS NULL
        )
        SELECT
          i.annee_id                                                                                annee_id,
          i.id                                                                                      intervenant_id,
          i.structure_id                                                                            intervenant_structure_id,
          NVL( t.structure_id, i.structure_id )                                                     structure_id,
          ti.id                                                                                     type_intervenant_id,
          ti.code                                                                                   type_intervenant_code,
          si.peut_saisir_service                                                                    peut_saisir_service,

          t.element_pedagogique_id,
          t.service_id,
          t.element_pedagogique_periode_id,
          t.etape_id,
          t.type_volume_horaire_id,
          t.type_volume_horaire_code,
          t.element_pedagogique_histo,
          t.etape_histo,

          CASE WHEN SUM(t.has_heures_mauvaise_periode) > 0 THEN 1 ELSE 0 END has_heures_mauvaise_periode,

          CASE WHEN type_volume_horaire_id IS NULL THEN 0 ELSE count(*) END nbvh,
          CASE WHEN type_volume_horaire_id IS NULL THEN 0 ELSE sum(t.heures) END heures,
          sum(valide) valide
        FROM
          t
          JOIN intervenant                              i ON i.id = t.intervenant_id
          JOIN statut_intervenant                      si ON si.id = i.statut_id
          JOIN type_intervenant                        ti ON ti.id = si.type_intervenant_id
        GROUP BY
          i.annee_id,
          i.id,
          i.structure_id,
          t.structure_id,
          i.structure_id,
          ti.id,
          ti.code,
          si.peut_saisir_service,
          t.element_pedagogique_id,
          t.service_id,
          t.element_pedagogique_periode_id,
          t.etape_id,
          t.type_volume_horaire_id,
          t.type_volume_horaire_code,
          t.element_pedagogique_histo,
          t.etape_histo) tv
      WHERE
        ' || conds || '

    ) v ON (
            COALESCE(t.TYPE_VOLUME_HORAIRE_ID,0) = COALESCE(v.TYPE_VOLUME_HORAIRE_ID,0)
        AND t.SERVICE_ID             = v.SERVICE_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID                       = v.ANNEE_ID,
      INTERVENANT_ID                 = v.INTERVENANT_ID,
      PEUT_SAISIR_SERVICE            = v.PEUT_SAISIR_SERVICE,
      STRUCTURE_ID                   = v.STRUCTURE_ID,
      NBVH                           = v.NBVH,
      VALIDE                         = v.VALIDE,
      ELEMENT_PEDAGOGIQUE_ID         = v.ELEMENT_PEDAGOGIQUE_ID,
      ELEMENT_PEDAGOGIQUE_PERIODE_ID = v.ELEMENT_PEDAGOGIQUE_PERIODE_ID,
      ETAPE_ID                       = v.ETAPE_ID,
      ELEMENT_PEDAGOGIQUE_HISTO      = v.ELEMENT_PEDAGOGIQUE_HISTO,
      ETAPE_HISTO                    = v.ETAPE_HISTO,
      HAS_HEURES_MAUVAISE_PERIODE    = v.HAS_HEURES_MAUVAISE_PERIODE,
      INTERVENANT_STRUCTURE_ID       = v.INTERVENANT_STRUCTURE_ID,
      TYPE_INTERVENANT_ID            = v.TYPE_INTERVENANT_ID,
      TYPE_INTERVENANT_CODE          = v.TYPE_INTERVENANT_CODE,
      TYPE_VOLUME_HORAIRE_CODE       = v.TYPE_VOLUME_HORAIRE_CODE,
      HEURES                         = v.HEURES,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      PEUT_SAISIR_SERVICE,
      TYPE_VOLUME_HORAIRE_ID,
      STRUCTURE_ID,
      NBVH,
      VALIDE,
      ELEMENT_PEDAGOGIQUE_ID,
      ELEMENT_PEDAGOGIQUE_PERIODE_ID,
      ETAPE_ID,
      ELEMENT_PEDAGOGIQUE_HISTO,
      ETAPE_HISTO,
      HAS_HEURES_MAUVAISE_PERIODE,
      SERVICE_ID,
      INTERVENANT_STRUCTURE_ID,
      TYPE_INTERVENANT_ID,
      TYPE_INTERVENANT_CODE,
      TYPE_VOLUME_HORAIRE_CODE,
      HEURES,
      TO_DELETE

    ) VALUES (

      TBL_SERVICE_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_SAISIR_SERVICE,
      v.TYPE_VOLUME_HORAIRE_ID,
      v.STRUCTURE_ID,
      v.NBVH,
      v.VALIDE,
      v.ELEMENT_PEDAGOGIQUE_ID,
      v.ELEMENT_PEDAGOGIQUE_PERIODE_ID,
      v.ETAPE_ID,
      v.ELEMENT_PEDAGOGIQUE_HISTO,
      v.ETAPE_HISTO,
      v.HAS_HEURES_MAUVAISE_PERIODE,
      v.SERVICE_ID,
      v.INTERVENANT_STRUCTURE_ID,
      v.TYPE_INTERVENANT_ID,
      v.TYPE_INTERVENANT_CODE,
      v.TYPE_VOLUME_HORAIRE_CODE,
      v.HEURES,
      0

    );

    DELETE TBL_SERVICE WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_SERVICE_REFERENTIEL( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_SERVICE_REFERENTIEL SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_SERVICE_REFERENTIEL t
    USING (

      SELECT
        tv.*
      FROM
        (WITH t AS (

          SELECT
            i.annee_id,
            i.id intervenant_id,
            si.peut_saisir_referentiel peut_saisir_service,
            vh.type_volume_horaire_id,
            s.structure_id,
            CASE WHEN v.id IS NULL AND vh.auto_validation=0 THEN 0 ELSE 1 END valide
          FROM
                      intervenant                     i

                 JOIN statut_intervenant          si ON si.id = i.statut_id

            LEFT JOIN service_referentiel          s ON s.intervenant_id = i.id
                                                    AND s.histo_destruction IS NULL

            LEFT JOIN volume_horaire_ref          vh ON vh.service_referentiel_id = s.id
                                                    AND vh.histo_destruction IS NULL

            LEFT JOIN validation_vol_horaire_ref vvh ON vvh.volume_horaire_ref_id = vh.id

            LEFT JOIN validation                   v ON v.id = vvh.validation_id
                                                    AND v.histo_destruction IS NULL
          WHERE
            i.histo_destruction IS NULL

        )
        SELECT
          annee_id,
          intervenant_id,
          peut_saisir_service,
          type_volume_horaire_id,
          structure_id,
          CASE WHEN type_volume_horaire_id IS NULL THEN 0 ELSE count(*) END nbvh,
          sum(valide) valide
        FROM
          t
        WHERE
          NOT (structure_id IS NOT NULL AND type_volume_horaire_id IS NULL)
        GROUP BY
          annee_id,
          intervenant_id,
          peut_saisir_service,
          type_volume_horaire_id,
          structure_id) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID         = v.INTERVENANT_ID
        AND COALESCE(t.TYPE_VOLUME_HORAIRE_ID,0) = COALESCE(v.TYPE_VOLUME_HORAIRE_ID,0)
        AND COALESCE(t.STRUCTURE_ID,0) = COALESCE(v.STRUCTURE_ID,0)

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID               = v.ANNEE_ID,
      PEUT_SAISIR_SERVICE    = v.PEUT_SAISIR_SERVICE,
      NBVH                   = v.NBVH,
      VALIDE                 = v.VALIDE,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      PEUT_SAISIR_SERVICE,
      TYPE_VOLUME_HORAIRE_ID,
      STRUCTURE_ID,
      NBVH,
      VALIDE,
      TO_DELETE

    ) VALUES (

      TBL_SERVICE_REFERENTIEL_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_SAISIR_SERVICE,
      v.TYPE_VOLUME_HORAIRE_ID,
      v.STRUCTURE_ID,
      v.NBVH,
      v.VALIDE,
      0

    );

    DELETE TBL_SERVICE_REFERENTIEL WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_SERVICE_SAISIE( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_SERVICE_SAISIE SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_SERVICE_SAISIE t
    USING (

      SELECT
        tv.*
      FROM
        (SELECT
          i.annee_id,
          i.id intervenant_id,
          si.peut_saisir_service,
          si.peut_saisir_referentiel,
          SUM( CASE WHEN tvhs.code = ''PREVU''   THEN NVL(vh .heures,0) ELSE 0 END ) heures_service_prev,
          SUM( CASE WHEN tvhs.code = ''PREVU''   THEN NVL(vhr.heures,0) ELSE 0 END ) heures_referentiel_prev,
          SUM( CASE WHEN tvhs.code = ''REALISE'' THEN NVL(vh .heures,0) ELSE 0 END ) heures_service_real,
          SUM( CASE WHEN tvhs.code = ''REALISE'' THEN NVL(vhr.heures,0) ELSE 0 END ) heures_referentiel_real
        FROM
          intervenant i
          JOIN statut_intervenant si ON si.id = i.statut_id
          LEFT JOIN service s ON s.intervenant_id = i.id AND s.histo_destruction IS NULL
          LEFT JOIN volume_horaire vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL
          LEFT JOIN type_volume_horaire tvhs ON tvhs.id = vh.type_volume_horaire_id

          LEFT JOIN service_referentiel sr ON sr.intervenant_id = i.id AND sr.histo_destruction IS NULL
          LEFT JOIN volume_horaire_ref vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
          LEFT JOIN type_volume_horaire tvhrs ON tvhrs.id = vhr.type_volume_horaire_id
        WHERE
          i.histo_destruction IS NULL
        GROUP BY
          i.annee_id,
          i.id,
          si.peut_saisir_service,
          si.peut_saisir_referentiel) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID = v.INTERVENANT_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID                = v.ANNEE_ID,
      PEUT_SAISIR_SERVICE     = v.PEUT_SAISIR_SERVICE,
      PEUT_SAISIR_REFERENTIEL = v.PEUT_SAISIR_REFERENTIEL,
      HEURES_SERVICE_PREV     = v.HEURES_SERVICE_PREV,
      HEURES_REFERENTIEL_PREV = v.HEURES_REFERENTIEL_PREV,
      HEURES_SERVICE_REAL     = v.HEURES_SERVICE_REAL,
      HEURES_REFERENTIEL_REAL = v.HEURES_REFERENTIEL_REAL,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      PEUT_SAISIR_SERVICE,
      PEUT_SAISIR_REFERENTIEL,
      HEURES_SERVICE_PREV,
      HEURES_REFERENTIEL_PREV,
      HEURES_SERVICE_REAL,
      HEURES_REFERENTIEL_REAL,
      TO_DELETE

    ) VALUES (

      TBL_SERVICE_SAISIE_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_SAISIR_SERVICE,
      v.PEUT_SAISIR_REFERENTIEL,
      v.HEURES_SERVICE_PREV,
      v.HEURES_REFERENTIEL_PREV,
      v.HEURES_SERVICE_REAL,
      v.HEURES_REFERENTIEL_REAL,
      0

    );

    DELETE TBL_SERVICE_SAISIE WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_VALIDATION_ENSEIGNEMENT( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_VALIDATION_ENSEIGNEMENT SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_VALIDATION_ENSEIGNEMENT t
    USING (

      SELECT
        tv.*
      FROM
        (SELECT DISTINCT
          i.annee_id,
          i.id intervenant_id,
          CASE WHEN rsv.priorite = ''affectation'' THEN
            COALESCE( i.structure_id, ep.structure_id )
          ELSE
            COALESCE( ep.structure_id, i.structure_id )
          END structure_id,
          vh.type_volume_horaire_id,
          s.id service_id,
          vh.id volume_horaire_id,
          vh.auto_validation,
          v.id validation_id
        FROM
          service s
          JOIN volume_horaire vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL
          JOIN intervenant i ON i.id = s.intervenant_id AND i.histo_destruction IS NULL
          JOIN statut_intervenant si ON si.id = i.statut_id
          JOIN regle_structure_validation rsv ON rsv.type_intervenant_id = si.type_intervenant_id AND rsv.type_volume_horaire_id = vh.type_volume_horaire_id
          LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
          LEFT JOIN validation_vol_horaire vvh ON vvh.volume_horaire_id = vh.id
          LEFT JOIN validation v ON v.id = vvh.validation_id AND v.histo_destruction IS NULL
        WHERE
          s.histo_destruction IS NULL
          AND NOT (vvh.validation_id IS NOT NULL AND v.id IS NULL)) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID         = v.INTERVENANT_ID
        AND t.STRUCTURE_ID           = v.STRUCTURE_ID
        AND t.TYPE_VOLUME_HORAIRE_ID = v.TYPE_VOLUME_HORAIRE_ID
        AND t.SERVICE_ID             = v.SERVICE_ID
        AND COALESCE(t.VALIDATION_ID,0) = COALESCE(v.VALIDATION_ID,0)
        AND t.VOLUME_HORAIRE_ID      = v.VOLUME_HORAIRE_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID               = v.ANNEE_ID,
      AUTO_VALIDATION        = v.AUTO_VALIDATION,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      TYPE_VOLUME_HORAIRE_ID,
      SERVICE_ID,
      VALIDATION_ID,
      VOLUME_HORAIRE_ID,
      AUTO_VALIDATION,
      TO_DELETE

    ) VALUES (

      TBL_VALIDATION_ENSEIGNE_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.STRUCTURE_ID,
      v.TYPE_VOLUME_HORAIRE_ID,
      v.SERVICE_ID,
      v.VALIDATION_ID,
      v.VOLUME_HORAIRE_ID,
      v.AUTO_VALIDATION,
      0

    );

    DELETE TBL_VALIDATION_ENSEIGNEMENT WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_VALIDATION_REFERENTIEL( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_VALIDATION_REFERENTIEL SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO
      TBL_VALIDATION_REFERENTIEL t
    USING (

      SELECT
        tv.*
      FROM
        (SELECT DISTINCT
          i.annee_id,
          i.id intervenant_id,
          CASE WHEN rsv.priorite = ''affectation'' THEN
            COALESCE( i.structure_id, s.structure_id )
          ELSE
            COALESCE( s.structure_id, i.structure_id )
          END structure_id,
          vh.type_volume_horaire_id,
          s.id service_referentiel_id,
          vh.id volume_horaire_ref_id,
          vh.auto_validation,
          v.id validation_id
        FROM
          service_referentiel s
          JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id AND vh.histo_destruction IS NULL
          JOIN intervenant i ON i.id = s.intervenant_id AND i.histo_destruction IS NULL
          JOIN statut_intervenant si ON si.id = i.statut_id
          JOIN regle_structure_validation rsv ON rsv.type_intervenant_id = si.type_intervenant_id AND rsv.type_volume_horaire_id = vh.type_volume_horaire_id
          LEFT JOIN validation_vol_horaire_ref vvh ON vvh.volume_horaire_ref_id = vh.id
          LEFT JOIN validation v ON v.id = vvh.validation_id AND v.histo_destruction IS NULL
        WHERE
          s.histo_destruction IS NULL) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID         = v.INTERVENANT_ID
        AND t.STRUCTURE_ID           = v.STRUCTURE_ID
        AND t.TYPE_VOLUME_HORAIRE_ID = v.TYPE_VOLUME_HORAIRE_ID
        AND t.SERVICE_REFERENTIEL_ID = v.SERVICE_REFERENTIEL_ID
        AND COALESCE(t.VALIDATION_ID,0) = COALESCE(v.VALIDATION_ID,0)
        AND t.VOLUME_HORAIRE_REF_ID  = v.VOLUME_HORAIRE_REF_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID               = v.ANNEE_ID,
      AUTO_VALIDATION        = v.AUTO_VALIDATION,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      TYPE_VOLUME_HORAIRE_ID,
      SERVICE_REFERENTIEL_ID,
      VALIDATION_ID,
      VOLUME_HORAIRE_REF_ID,
      AUTO_VALIDATION,
      TO_DELETE

    ) VALUES (

      TBL_VALIDATION_REFERENT_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.STRUCTURE_ID,
      v.TYPE_VOLUME_HORAIRE_ID,
      v.SERVICE_REFERENTIEL_ID,
      v.VALIDATION_ID,
      v.VOLUME_HORAIRE_REF_ID,
      v.AUTO_VALIDATION,
      0

    );

    DELETE TBL_VALIDATION_REFERENTIEL WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;

  -- END OF AUTOMATIC GENERATION --

END UNICAEN_TBL;