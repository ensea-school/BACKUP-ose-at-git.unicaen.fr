CREATE OR REPLACE PACKAGE BODY "UNICAEN_TBL" AS
  TYPE t_dems_values IS TABLE OF BOOLEAN INDEX BY VARCHAR2(80);
  TYPE t_dems_params IS TABLE OF t_dems_values INDEX BY VARCHAR2(30);
  TYPE t_dems IS TABLE OF t_dems_params INDEX BY VARCHAR2(30);

  dems t_dems;



  FUNCTION MAKE_WHERE(param VARCHAR2 DEFAULT NULL, VALUE VARCHAR2 DEFAULT NULL,
                      alias VARCHAR2 DEFAULT NULL) RETURN VARCHAR2 IS
    res VARCHAR2(120) DEFAULT '';
  BEGIN
    IF param IS NULL THEN
      RETURN '1=1';
    END IF;
    IF alias IS NOT NULL THEN
      res := alias || '.';
    END IF;
    IF VALUE IS NULL THEN
      RETURN res || param || ' IS NULL';
    END IF;
   RETURN res || param || ' = q''[' || VALUE || ']''';
  END;



  FUNCTION QUERY_APPLY_PARAM(sqlQuery VARCHAR2, param VARCHAR2, VALUE VARCHAR2) RETURN CLOB IS
    pos       NUMERIC;
    paramLen  NUMERIC;
    paramComm VARCHAR2(200);
    debComm   NUMERIC;
    endComm   NUMERIC;
    debReal   NUMERIC;
    realParam VARCHAR2(80);
    realValue VARCHAR2(120);
    q         CLOB;
  BEGIN
    q := sqlQuery;
    IF param IS NULL THEN
      RETURN q;
    END IF;

    paramlen := LENGTH(param);
    IF VALUE IS NULL THEN
      realValue := ' IS NULL';
    ELSE
      BEGIN
        realValue := TO_NUMBER(VALUE);
      EXCEPTION
        WHEN VALUE_ERROR THEN
          realValue := 'q''[' || VALUE || ']''';
      END;
     realValue := '=' || realValue;
    END IF;

    LOOP
      pos := instr(q, '/*@' || param, 1, 1);
      EXIT WHEN pos = 0;
     debComm := pos - 1;
      endComm := instr(q, '*/', pos, 1);
      paramComm := substr(q, debComm, endComm - debComm);
     debReal := instr(paramComm, '=', 1, 1);
     realParam := TRIM(substr(paramComm, debReal + 1));
     --realParam := 'AND ' || substr(q,pos + paramLen + 4,endComm-pos - paramLen - 4);
      realParam := 'AND ' || realParam || realValue;
     q := substr(q, 1, debComm) || realParam || substr(q, endComm + 2);
    END LOOP;

    RETURN q;
  END;


  FUNCTION QUERY_APPLY_PARAMS(sqlQuery VARCHAR2, useParams BOOLEAN DEFAULT FALSE) RETURN CLOB IS
    q CLOB;
  BEGIN
    q := sqlQuery;

    IF NOT useParams THEN
      RETURN q;
    END IF;

    IF UNICAEN_TBL.CALCUL_PROC_PARAMS.p1 IS NOT NULL THEN
      q := QUERY_APPLY_PARAM(q, UNICAEN_TBL.CALCUL_PROC_PARAMS.p1, UNICAEN_TBL.CALCUL_PROC_PARAMS.v1);
    END IF;

    IF UNICAEN_TBL.CALCUL_PROC_PARAMS.p2 IS NOT NULL THEN
      q := QUERY_APPLY_PARAM(q, UNICAEN_TBL.CALCUL_PROC_PARAMS.p2, UNICAEN_TBL.CALCUL_PROC_PARAMS.v2);
    END IF;

    IF UNICAEN_TBL.CALCUL_PROC_PARAMS.p3 IS NOT NULL THEN
      q := QUERY_APPLY_PARAM(q, UNICAEN_TBL.CALCUL_PROC_PARAMS.p3, UNICAEN_TBL.CALCUL_PROC_PARAMS.v3);
    END IF;

    IF UNICAEN_TBL.CALCUL_PROC_PARAMS.p4 IS NOT NULL THEN
      q := QUERY_APPLY_PARAM(q, UNICAEN_TBL.CALCUL_PROC_PARAMS.p4, UNICAEN_TBL.CALCUL_PROC_PARAMS.v4);
    END IF;

    IF UNICAEN_TBL.CALCUL_PROC_PARAMS.p5 IS NOT NULL THEN
      q := QUERY_APPLY_PARAM(q, UNICAEN_TBL.CALCUL_PROC_PARAMS.p5, UNICAEN_TBL.CALCUL_PROC_PARAMS.v5);
    END IF;

    RETURN q;
  END;


  FUNCTION PARAMS_MAKE_FILTER(useParams BOOLEAN DEFAULT FALSE) RETURN VARCHAR2 IS
    FILTER VARCHAR2(4000) DEFAULT '';
  BEGIN
    IF NOT useParams THEN
      RETURN '1=1';
    END IF;

    IF unicaen_tbl.calcul_proc_params.p1 IS NOT NULL THEN
      IF FILTER IS NOT NULL THEN
        FILTER := FILTER || ' AND ';
      END IF;
      FILTER := FILTER || 'COALESCE(v.' || unicaen_tbl.calcul_proc_params.p1 || ', t.' || unicaen_tbl.calcul_proc_params.p1 || ') ';
      IF unicaen_tbl.calcul_proc_params.v1 IS NULL THEN
        FILTER := FILTER || 'IS NULL';
      ELSE
        FILTER := FILTER || '= q''[' || unicaen_tbl.calcul_proc_params.v1 || ']''';
      END IF;
    END IF;

    IF unicaen_tbl.calcul_proc_params.p2 IS NOT NULL THEN
      IF FILTER IS NOT NULL THEN
        FILTER := FILTER || ' AND ';
      END IF;
      FILTER := FILTER || 'COALESCE(v.' || unicaen_tbl.calcul_proc_params.p2 || ', t.' || unicaen_tbl.calcul_proc_params.p2 || ') ';
      IF unicaen_tbl.calcul_proc_params.v2 IS NULL THEN
        FILTER := FILTER || 'IS NULL';
      ELSE
        FILTER := FILTER || '= q''[' || unicaen_tbl.calcul_proc_params.v2 || ']''';
      END IF;
    END IF;

    IF unicaen_tbl.calcul_proc_params.p3 IS NOT NULL THEN
      IF FILTER IS NOT NULL THEN
        FILTER := FILTER || ' AND ';
      END IF;
      FILTER := FILTER || 'COALESCE(v.' || unicaen_tbl.calcul_proc_params.p3 || ', t.' || unicaen_tbl.calcul_proc_params.p3 || ') ';
      IF unicaen_tbl.calcul_proc_params.v3 IS NULL THEN
        FILTER := FILTER || 'IS NULL';
      ELSE
        FILTER := FILTER || '= q''[' || unicaen_tbl.calcul_proc_params.v3 || ']''';
      END IF;
    END IF;

    IF unicaen_tbl.calcul_proc_params.p4 IS NOT NULL THEN
      IF FILTER IS NOT NULL THEN
        FILTER := FILTER || ' AND ';
      END IF;
      FILTER := FILTER || 'COALESCE(v.' || unicaen_tbl.calcul_proc_params.p4 || ', t.' || unicaen_tbl.calcul_proc_params.p4 || ') ';
      IF unicaen_tbl.calcul_proc_params.v4 IS NULL THEN
        FILTER := FILTER || 'IS NULL';
      ELSE
        FILTER := FILTER || '= q''[' || unicaen_tbl.calcul_proc_params.v4 || ']''';
      END IF;
    END IF;

    IF unicaen_tbl.calcul_proc_params.p5 IS NOT NULL THEN
      IF FILTER IS NOT NULL THEN
        FILTER := FILTER || ' AND ';
      END IF;
      FILTER := FILTER || 'COALESCE(v.' || unicaen_tbl.calcul_proc_params.p5 || ', t.' || unicaen_tbl.calcul_proc_params.p5 || ') ';
      IF unicaen_tbl.calcul_proc_params.v5 IS NULL THEN
        FILTER := FILTER || 'IS NULL';
      ELSE
        FILTER := FILTER || '= q''[' || unicaen_tbl.calcul_proc_params.v5 || ']''';
      END IF;
    END IF;

    IF FILTER IS NULL OR FILTER = '' THEN
      RETURN '1=1';
    END IF;

    RETURN FILTER;
  END;



  PROCEDURE CALCULER(TBL_NAME VARCHAR2) IS
    params t_params;
  BEGIN
    ANNULER_DEMANDES(TBL_NAME);
    CALCULER(TBL_NAME, params);
  END;



  PROCEDURE CALCULER(TBL_NAME VARCHAR2, param VARCHAR2, VALUE VARCHAR2) IS
    params t_params;
  BEGIN
    IF NOT UNICAEN_TBL.ACTIV_CALCULS THEN RETURN; END IF;

    params.p1 := param;
    params.v1 := VALUE;

    unicaen_tbl.calcul_proc_params := params;

    EXECUTE IMMEDIATE 'BEGIN UNICAEN_TBL.C_' || TBL_NAME || '(TRUE); END;';

  END;



  PROCEDURE CALCULER(TBL_NAME VARCHAR2, params t_params) IS
  BEGIN
    IF NOT UNICAEN_TBL.ACTIV_CALCULS THEN RETURN; END IF;

    unicaen_tbl.calcul_proc_params := params;

      EXECUTE IMMEDIATE 'BEGIN UNICAEN_TBL.C_' || TBL_NAME || '(TRUE); END;';
  END;



  PROCEDURE DEMANDE_CALCUL(TBL_NAME VARCHAR2, param VARCHAR2, VALUE VARCHAR2) IS
  BEGIN
    dems(TBL_NAME)(param)(VALUE) := TRUE;
  END;



  PROCEDURE ANNULER_DEMANDES IS
  BEGIN
    dems.delete;
  END;



  PROCEDURE ANNULER_DEMANDES(TBL_NAME VARCHAR2) IS
  BEGIN
    IF dems.exists(tbl_name) THEN
      dems(tbl_name).delete;
    END IF;
  END;



  FUNCTION HAS_DEMANDES RETURN BOOLEAN IS
  BEGIN
    RETURN dems.count > 0;
  END;



  PROCEDURE CALCULER_DEMANDES IS
    d t_dems;
    tbl_name VARCHAR2(30);
    param VARCHAR2(30);
    VALUE VARCHAR2(80);
  BEGIN
    d := dems;
    dems.delete;

    tbl_name := d.FIRST;
    LOOP EXIT WHEN tbl_name IS NULL;
      param := d(tbl_name).FIRST;
      LOOP EXIT WHEN param IS NULL;
        VALUE := d(tbl_name)(param).FIRST;
        LOOP EXIT WHEN VALUE IS NULL;
          calculer(tbl_name, param, VALUE);
          VALUE := d(tbl_name)(param).NEXT(VALUE);
        END LOOP;
        param := d(tbl_name).NEXT(param);
      END LOOP;
      tbl_name := d.NEXT(tbl_name);
    END LOOP;

    IF HAS_DEMANDES THEN -- pour les boucles !!
      CALCULER_DEMANDES;
    END IF;
  END;



  PROCEDURE C_CHARGENS(useParams BOOLEAN DEFAULT FALSE) IS
  TYPE r_cursor IS REF CURSOR;
  c r_cursor;
  d TBL_CHARGENS%rowtype;
  viewQuery CLOB;
  BEGIN
    viewQuery := 'SELECT
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
          heures_ens,
          --t_effectif,t_dedoublement,

          CASE WHEN t_effectif < ouverture OR dedoublement = 0 OR t_effectif = 0 THEN 0 ELSE
            (CEIL(t_effectif / dedoublement) * effectif) / t_effectif
          END groupes,

          CASE WHEN t_effectif < ouverture OR dedoublement = 0 OR t_effectif = 0 THEN 0 ELSE
            ((CEIL(t_effectif / dedoublement) * effectif) / t_effectif) * heures_ens
          END heures,

          CASE WHEN t_effectif < ouverture OR dedoublement = 0 OR t_effectif = 0 THEN 0 ELSE
            ((CEIL(t_effectif / dedoublement) * effectif) / t_effectif) * hetd
          END  hetd

        FROM
          (
          WITH seuils_perso AS (
          SELECT
            n.element_pedagogique_id,
            n.etape_id,
            sn.scenario_id,
            sns.type_intervention_id,
            sns.ouverture,
            sns.dedoublement,
            sns.assiduite
          FROM
            scenario_noeud_seuil sns
            JOIN scenario_noeud sn ON sn.id = sns.scenario_noeud_id AND sn.histo_destruction IS NULL
            JOIN noeud n ON n.id = sn.noeud_id
          WHERE
            sns.dedoublement IS NOT NULL
        )
          SELECT
            n.annee_id                                                                       annee_id,
            n.id                                                                             noeud_id,
            sn.scenario_id                                                                   scenario_id,
            sne.type_heures_id                                                               type_heures_id,
            ti.id                                                                            type_intervention_id,

            n.element_pedagogique_id                                                         element_pedagogique_id,
            etp.id                                                                           etape_id,
            sne.etape_id                                                                     etape_ens_id,
            n.structure_id                                                                   structure_id,
            tf.groupe_id                                                                     groupe_type_formation_id,

            vhe.heures                                                                       heures_ens,
            vhe.heures * ti.taux_hetd_service                                                hetd,

            COALESCE(sep.ouverture, se.ouverture,1)                                          ouverture,
            COALESCE(sep.dedoublement, se.dedoublement, sd.dedoublement,1)                   dedoublement,
            COALESCE(sep.assiduite,1)                                                        assiduite,
            sne.effectif*COALESCE(sep.assiduite,1)                                           effectif,
            SUM(sne.effectif*COALESCE(sep.assiduite,1)) OVER (PARTITION BY n.id, sn.scenario_id, ti.id) t_effectif
        FROM
                      scenario_noeud_effectif sne

                 JOIN scenario_noeud           sn ON sn.id = sne.scenario_noeud_id
                                                 AND sn.histo_destruction IS NULL
                                                 /*@NOEUD_ID=sn.noeud_id*/
                                                 /*@SCENARIO_ID=sn.scenario_id*/



                 JOIN noeud                     n ON n.id = sn.noeud_id
                                                 AND n.histo_destruction IS NULL
                                                 /*@ANNEE_ID=n.annee_id*/
                                                 /*@ELEMENT_PEDAGOGIQUE_ID=n.element_pedagogique_id*/

                 JOIN volume_horaire_ens      vhe ON vhe.element_pedagogique_id = n.element_pedagogique_id
                                                 AND vhe.histo_destruction IS NULL
                                                 AND vhe.heures > 0

                 JOIN type_intervention        ti ON ti.id = vhe.type_intervention_id
            LEFT JOIN element_pedagogique      ep ON ep.id = n.element_pedagogique_id
            LEFT JOIN etape                   etp ON etp.id = COALESCE(n.etape_id,ep.etape_id)
                                                 /*@ETAPE_ID=etp.id*/

            LEFT JOIN type_formation           tf ON tf.id = etp.type_formation_id

            LEFT JOIN seuils_perso            sep ON sep.element_pedagogique_id = n.element_pedagogique_id
                                                 AND sep.scenario_id = sn.scenario_id
                                                 AND sep.type_intervention_id = ti.id

            LEFT JOIN seuils_perso             se ON se.etape_id = etp.id
                                                 AND se.scenario_id = sn.scenario_id
                                                 AND se.type_intervention_id = ti.id

            LEFT JOIN tbl_chargens_seuils_def  sd ON sd.annee_id = n.annee_id
                                                 AND sd.scenario_id = sn.scenario_id
                                                 AND sd.structure_id = etp.structure_id
                                                 AND sd.groupe_type_formation_id = tf.groupe_id
                                                 AND sd.type_intervention_id = ti.id
          WHERE
            1=1
            /*@ETAPE_ENS_ID=sne.etape_id*/
          ) t';

    OPEN c FOR '
    SELECT
      CASE WHEN
            t.ANNEE_ID                             = v.ANNEE_ID
        AND t.NOEUD_ID                             = v.NOEUD_ID
        AND t.SCENARIO_ID                          = v.SCENARIO_ID
        AND t.TYPE_HEURES_ID                       = v.TYPE_HEURES_ID
        AND t.TYPE_INTERVENTION_ID                 = v.TYPE_INTERVENTION_ID
        AND t.ELEMENT_PEDAGOGIQUE_ID               = v.ELEMENT_PEDAGOGIQUE_ID
        AND t.ETAPE_ID                             = v.ETAPE_ID
        AND t.ETAPE_ENS_ID                         = v.ETAPE_ENS_ID
        AND t.STRUCTURE_ID                         = v.STRUCTURE_ID
        AND t.GROUPE_TYPE_FORMATION_ID             = v.GROUPE_TYPE_FORMATION_ID
        AND t.OUVERTURE                            = v.OUVERTURE
        AND t.DEDOUBLEMENT                         = v.DEDOUBLEMENT
        AND t.ASSIDUITE                            = v.ASSIDUITE
        AND t.EFFECTIF                             = v.EFFECTIF
        AND t.HEURES_ENS                           = v.HEURES_ENS
        AND t.GROUPES                              = v.GROUPES
        AND t.HEURES                               = v.HEURES
        AND t.HETD                                 = v.HETD
      THEN -1 ELSE t.ID END ID,
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
      v.HETD
    FROM
      (' || QUERY_APPLY_PARAMS(viewQuery, useParams) || ') v
      FULL JOIN TBL_CHARGENS t ON
            t.ANNEE_ID                             = v.ANNEE_ID
        AND t.NOEUD_ID                             = v.NOEUD_ID
        AND t.SCENARIO_ID                          = v.SCENARIO_ID
        AND t.TYPE_HEURES_ID                       = v.TYPE_HEURES_ID
        AND t.TYPE_INTERVENTION_ID                 = v.TYPE_INTERVENTION_ID
        AND t.ELEMENT_PEDAGOGIQUE_ID               = v.ELEMENT_PEDAGOGIQUE_ID
        AND t.ETAPE_ID                             = v.ETAPE_ID
        AND t.ETAPE_ENS_ID                         = v.ETAPE_ENS_ID
        AND t.STRUCTURE_ID                         = v.STRUCTURE_ID
        AND t.GROUPE_TYPE_FORMATION_ID             = v.GROUPE_TYPE_FORMATION_ID
    WHERE ' || PARAMS_MAKE_FILTER(useParams);
    LOOP
      FETCH c INTO d; EXIT WHEN c%NOTFOUND;

      IF d.id IS NULL THEN
        d.id := TBL_CHARGENS_ID_SEQ.NEXTVAL;
        INSERT INTO TBL_CHARGENS values d;
      ELSIF
            d.ANNEE_ID IS NULL
        AND d.NOEUD_ID IS NULL
        AND d.SCENARIO_ID IS NULL
        AND d.TYPE_HEURES_ID IS NULL
        AND d.TYPE_INTERVENTION_ID IS NULL
        AND d.ELEMENT_PEDAGOGIQUE_ID IS NULL
        AND d.ETAPE_ID IS NULL
        AND d.ETAPE_ENS_ID IS NULL
        AND d.STRUCTURE_ID IS NULL
        AND d.GROUPE_TYPE_FORMATION_ID IS NULL
      THEN
        DELETE FROM TBL_CHARGENS WHERE id = d.id;
      ELSIF d.id <> -1 THEN
        UPDATE TBL_CHARGENS SET row = d WHERE id = d.id;
      END IF;
    END LOOP;
    CLOSE c;
  END;

END UNICAEN_TBL;