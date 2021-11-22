CREATE OR REPLACE PACKAGE BODY "UNICAEN_TBL" AS
  TYPE t_dems_values IS TABLE OF BOOLEAN INDEX BY VARCHAR2(80);
  TYPE t_dems_params IS TABLE OF t_dems_values INDEX BY VARCHAR2(30);
  TYPE t_dems IS TABLE OF t_dems_params INDEX BY VARCHAR2(30);

  dems t_dems;



  FUNCTION MAKE_WHERE(param VARCHAR2 DEFAULT NULL, value VARCHAR2 DEFAULT NULL,
                      alias VARCHAR2 DEFAULT NULL) RETURN VARCHAR2 IS
    res VARCHAR2(120) DEFAULT '';
  BEGIN
    IF param IS NULL THEN
      RETURN '1=1';
    END IF;
    IF alias IS NOT NULL THEN
      res := alias || '.';
    END IF;
    IF value IS NULL THEN
      RETURN res || param || ' IS NULL';
    END IF;
   RETURN res || param || ' = q''[' || value || ']''';
  END;



  FUNCTION QUERY_APPLY_PARAM(sqlQuery VARCHAR2, param VARCHAR2, value VARCHAR2) RETURN CLOB IS
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

    paramlen := length(param);
    IF value IS NULL THEN
      realValue := ' IS NULL';
    ELSE
      BEGIN
        realValue := TO_NUMBER(value);
      EXCEPTION
        WHEN VALUE_ERROR THEN
          realValue := 'q''[' || value || ']''';
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
     realParam := trim(substr(paramComm, debReal + 1));
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
    filter VARCHAR2(4000) DEFAULT '';
  BEGIN
    IF NOT useParams THEN
      RETURN '1=1';
    END IF;

    IF unicaen_tbl.calcul_proc_params.p1 IS NOT NULL THEN
      IF filter IS NOT NULL THEN
        filter := filter || ' AND ';
      END IF;
      filter := filter || 'COALESCE(v.' || unicaen_tbl.calcul_proc_params.p1 || ', t.' || unicaen_tbl.calcul_proc_params.p1 || ') ';
      IF unicaen_tbl.calcul_proc_params.v1 IS NULL THEN
        filter := filter || 'IS NULL';
      ELSE
        filter := filter || '= q''[' || unicaen_tbl.calcul_proc_params.v1 || ']''';
      END IF;
    END IF;

    IF unicaen_tbl.calcul_proc_params.p2 IS NOT NULL THEN
      IF filter IS NOT NULL THEN
        filter := filter || ' AND ';
      END IF;
      filter := filter || 'COALESCE(v.' || unicaen_tbl.calcul_proc_params.p2 || ', t.' || unicaen_tbl.calcul_proc_params.p2 || ') ';
      IF unicaen_tbl.calcul_proc_params.v2 IS NULL THEN
        filter := filter || 'IS NULL';
      ELSE
        filter := filter || '= q''[' || unicaen_tbl.calcul_proc_params.v2 || ']''';
      END IF;
    END IF;

    IF unicaen_tbl.calcul_proc_params.p3 IS NOT NULL THEN
      IF filter IS NOT NULL THEN
        filter := filter || ' AND ';
      END IF;
      filter := filter || 'COALESCE(v.' || unicaen_tbl.calcul_proc_params.p3 || ', t.' || unicaen_tbl.calcul_proc_params.p3 || ') ';
      IF unicaen_tbl.calcul_proc_params.v3 IS NULL THEN
        filter := filter || 'IS NULL';
      ELSE
        filter := filter || '= q''[' || unicaen_tbl.calcul_proc_params.v3 || ']''';
      END IF;
    END IF;

    IF unicaen_tbl.calcul_proc_params.p4 IS NOT NULL THEN
      IF filter IS NOT NULL THEN
        filter := filter || ' AND ';
      END IF;
      filter := filter || 'COALESCE(v.' || unicaen_tbl.calcul_proc_params.p4 || ', t.' || unicaen_tbl.calcul_proc_params.p4 || ') ';
      IF unicaen_tbl.calcul_proc_params.v4 IS NULL THEN
        filter := filter || 'IS NULL';
      ELSE
        filter := filter || '= q''[' || unicaen_tbl.calcul_proc_params.v4 || ']''';
      END IF;
    END IF;

    IF unicaen_tbl.calcul_proc_params.p5 IS NOT NULL THEN
      IF filter IS NOT NULL THEN
        filter := filter || ' AND ';
      END IF;
      filter := filter || 'COALESCE(v.' || unicaen_tbl.calcul_proc_params.p5 || ', t.' || unicaen_tbl.calcul_proc_params.p5 || ') ';
      IF unicaen_tbl.calcul_proc_params.v5 IS NULL THEN
        filter := filter || 'IS NULL';
      ELSE
        filter := filter || '= q''[' || unicaen_tbl.calcul_proc_params.v5 || ']''';
      END IF;
    END IF;

    IF filter = '' THEN
      RETURN '1=1';
    END IF;

    RETURN filter;
  END;



  PROCEDURE CALCULER(TBL_NAME VARCHAR2) IS
    params t_params;
  BEGIN
    ANNULER_DEMANDES(TBL_NAME);
    CALCULER(TBL_NAME, params);
  END;



  PROCEDURE CALCULER(TBL_NAME VARCHAR2, param VARCHAR2, value VARCHAR2) IS
    calcul_proc varchar2(30);
    params t_params;
  BEGIN
    IF NOT UNICAEN_TBL.ACTIV_CALCULS THEN RETURN; END IF;

    SELECT custom_calcul_proc INTO calcul_proc FROM tbl WHERE tbl_name = CALCULER.TBL_NAME;

    params.p1 := param;
    params.v1 := value;

    unicaen_tbl.calcul_proc_params := params;

    IF calcul_proc IS NOT NULL THEN
      EXECUTE IMMEDIATE
        'BEGIN ' || calcul_proc || '(UNICAEN_TBL.CALCUL_PROC_PARAMS.p1, UNICAEN_TBL.CALCUL_PROC_PARAMS.v1); END;';
    ELSE
      EXECUTE IMMEDIATE
        'BEGIN UNICAEN_TBL.C_' || TBL_NAME || '(TRUE); END;';
    END IF;
  END;



  PROCEDURE CALCULER(TBL_NAME VARCHAR2, params t_params) IS
    calcul_proc varchar2(30);
  BEGIN
    IF NOT UNICAEN_TBL.ACTIV_CALCULS THEN RETURN; END IF;

    SELECT custom_calcul_proc INTO calcul_proc FROM tbl WHERE tbl_name = CALCULER.TBL_NAME;

    unicaen_tbl.calcul_proc_params := params;

    IF calcul_proc IS NOT NULL THEN
      EXECUTE IMMEDIATE
              'BEGIN ' || calcul_proc || '(UNICAEN_TBL.CALCUL_PROC_PARAMS.p1, UNICAEN_TBL.CALCUL_PROC_PARAMS.v1); END;';
    ELSE
      EXECUTE IMMEDIATE
              'BEGIN UNICAEN_TBL.C_' || TBL_NAME || '(TRUE); END;';
    END IF;
  END;



  PROCEDURE DEMANDE_CALCUL(TBL_NAME VARCHAR2, param VARCHAR2, value VARCHAR2) IS
  BEGIN
    dems(TBL_NAME)(param)(value) := TRUE;
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
    value VARCHAR2(80);
  BEGIN
    d := dems;
    dems.delete;

    tbl_name := d.FIRST;
    LOOP EXIT WHEN tbl_name IS NULL;
      param := d(tbl_name).FIRST;
      LOOP EXIT WHEN param IS NULL;
        value := d(tbl_name)(param).FIRST;
        LOOP EXIT WHEN value IS NULL;
          calculer(tbl_name, param, value);
          value := d(tbl_name)(param).NEXT(value);
        END LOOP;
        param := d(tbl_name).NEXT(param);
      END LOOP;
      tbl_name := d.NEXT(tbl_name);
    END LOOP;

    IF HAS_DEMANDES THEN -- pour les boucles !!
      CALCULER_DEMANDES;
    END IF;
  END;



  -- AUTOMATIC GENERATION --

  PROCEDURE C_AGREMENT(useParams BOOLEAN DEFAULT FALSE) IS
  TYPE r_cursor IS REF CURSOR;
  c r_cursor;
  d TBL_AGREMENT%rowtype;
  viewQuery CLOB;
  BEGIN
    viewQuery := 'WITH i_s AS (
          SELECT
            fr.intervenant_id,
            ep.structure_id structure_id
          FROM
            formule_resultat fr
            JOIN type_volume_horaire  tvh ON tvh.code = ''PREVU'' AND tvh.id = fr.type_volume_horaire_id
            JOIN etat_volume_horaire  evh ON evh.code = ''valide'' AND evh.id = fr.etat_volume_horaire_id

            JOIN formule_resultat_service frs ON frs.formule_resultat_id = fr.id
            JOIN service s ON s.id = frs.service_id
            JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
          WHERE
            frs.total > 0
            /*@INTERVENANT_ID=fr.intervenant_id*/
        ),
        avi AS (
            SELECT
                i.code                intervenant_code,
                i.annee_id            annee_id,
                a.type_agrement_id    type_agrement_id,
                a.id                  agrement_id,
                a.structure_id        structure_id
            FROM intervenant i
            	JOIN agrement a ON a.intervenant_id = i.id
            WHERE
            	a.histo_destruction IS NULL
        )
        SELECT DISTINCT "ANNEE_ID","ANNEE_AGREMENT","TYPE_AGREMENT_ID","INTERVENANT_ID","CODE_INTERVENANT","STRUCTURE_ID","OBLIGATOIRE","AGREMENT_ID","DUREE_VIE" FROM (
            SELECT
              i.annee_id                     annee_id,
              CASE
                WHEN COALESCE (avi.agrement_id,0) = 0
                THEN NULL
                ELSE NVL(avi.annee_id, i.annee_id) END   annee_agrement,
              tas.type_agrement_id                       type_agrement_id,
              i.id                                       intervenant_id,
              i.code                                     code_intervenant,
              null                                       structure_id,
              tas.obligatoire                            obligatoire,
              avi.agrement_id			                 agrement_id,
              tas.duree_vie                              duree_vie,
              RANK() OVER(
                PARTITION BY i.code,i.annee_id ORDER BY
                CASE
                WHEN COALESCE (avi.agrement_id,0) = 0
                THEN NULL
                ELSE NVL(avi.annee_id, i.annee_id) END DESC
              ) rank
            FROM
              type_agrement                  ta
              JOIN type_agrement_statut      tas ON tas.type_agrement_id = ta.id
                                                AND tas.histo_destruction IS NULL

              JOIN intervenant                 i ON i.histo_destruction IS NULL
                                                AND i.statut_id = tas.statut_intervenant_id

              JOIN                           i_s ON i_s.intervenant_id = i.id

              LEFT JOIN                      avi ON i.code = avi.intervenant_code
              							                		AND avi.type_agrement_id = tas.type_agrement_id
                                                AND i.annee_id < avi.annee_id + tas.duree_vie
                                                AND i.annee_id >= avi.annee_id


            WHERE
              ta.code = ''CONSEIL_ACADEMIQUE''
              /*@INTERVENANT_ID=i.id*/
              /*@ANNEE_ID=i.annee_id*/
          )
        WHERE
          rank = 1

        UNION ALL
        SELECT DISTINCT "ANNEE_ID","ANNEE_AGREMENT","TYPE_AGREMENT_ID","INTERVENANT_ID","CODE_INTERVENANT","STRUCTURE_ID","OBLIGATOIRE","AGREMENT_ID","DUREE_VIE" FROM (
            SELECT
              i.annee_id                                  annee_id,
              CASE
                WHEN COALESCE (avi.agrement_id,0) = 0
                THEN NULL
                ELSE NVL(avi.annee_id, i.annee_id) END    annee_agrement,
              tas.type_agrement_id                        type_agrement_id,
              i.id                                        intervenant_id,
              i.code                                      code_intervenant,
              i_s.structure_id							  structure_id,
              tas.obligatoire                             obligatoire,
              avi.agrement_id 			                  agrement_id,
              tas.duree_vie                               duree_vie,
              RANK() OVER(
                PARTITION BY i.code,i.annee_id,i_s.structure_id ORDER BY
                CASE
                WHEN COALESCE (avi.agrement_id,0) = 0
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

              LEFT JOIN                      avi ON i.code = avi.intervenant_code
                							                	AND avi.type_agrement_id = tas.type_agrement_id
        										                    AND COALESCE(avi.structure_id,0) = COALESCE(i_s.structure_id,0)
                                                AND i.annee_id < avi.annee_id + tas.duree_vie
                                                AND i.annee_id >= avi.annee_id


            WHERE
              ta.code = ''CONSEIL_RESTREINT''
              /*@INTERVENANT_ID=i.id*/
              /*@ANNEE_ID=i.annee_id*/
          )
        WHERE
          rank = 1';

    OPEN c FOR '
    SELECT
      CASE WHEN
            t.ANNEE_ID                     = v.ANNEE_ID
        AND COALESCE(t.ANNEE_AGREMENT,0)   = COALESCE(v.ANNEE_AGREMENT,0)
        AND t.TYPE_AGREMENT_ID             = v.TYPE_AGREMENT_ID
        AND t.INTERVENANT_ID               = v.INTERVENANT_ID
        AND t.CODE_INTERVENANT             = v.CODE_INTERVENANT
        AND COALESCE(t.STRUCTURE_ID,0)     = COALESCE(v.STRUCTURE_ID,0)
        AND t.OBLIGATOIRE                  = v.OBLIGATOIRE
        AND COALESCE(t.AGREMENT_ID,0)      = COALESCE(v.AGREMENT_ID,0)
        AND t.DUREE_VIE                    = v.DUREE_VIE
      THEN -1 ELSE t.ID END ID,
      v.ANNEE_ID,
      v.ANNEE_AGREMENT,
      v.TYPE_AGREMENT_ID,
      v.INTERVENANT_ID,
      v.CODE_INTERVENANT,
      v.STRUCTURE_ID,
      v.OBLIGATOIRE,
      v.AGREMENT_ID,
      v.DUREE_VIE
    FROM
      (' || QUERY_APPLY_PARAMS(viewQuery, useParams) || ') v
      FULL JOIN TBL_AGREMENT t ON
            COALESCE(t.ANNEE_AGREMENT,0)   = COALESCE(v.ANNEE_AGREMENT,0)
        AND t.TYPE_AGREMENT_ID             = v.TYPE_AGREMENT_ID
        AND t.INTERVENANT_ID               = v.INTERVENANT_ID
        AND COALESCE(t.STRUCTURE_ID,0)     = COALESCE(v.STRUCTURE_ID,0)
    WHERE ' || PARAMS_MAKE_FILTER(useParams);
    LOOP
      FETCH c INTO d; EXIT WHEN c%NOTFOUND;

      IF d.id IS NULL THEN
        d.id := TBL_AGREMENT_ID_SEQ.NEXTVAL;
        INSERT INTO TBL_AGREMENT values d;
      ELSIF
            d.ANNEE_AGREMENT IS NULL
        AND d.TYPE_AGREMENT_ID IS NULL
        AND d.INTERVENANT_ID IS NULL
        AND d.STRUCTURE_ID IS NULL
      THEN
        DELETE FROM TBL_AGREMENT WHERE id = d.id;
      ELSIF d.id <> -1 THEN
        UPDATE TBL_AGREMENT SET row = d WHERE id = d.id;
      END IF;
    END LOOP;
    CLOSE c;
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
            n.noeud_id                                                                       noeud_id,
            sn.scenario_id                                                                   scenario_id,
            sne.type_heures_id                                                               type_heures_id,
            ti.id                                                                            type_intervention_id,

            n.element_pedagogique_id                                                         element_pedagogique_id,
            n.element_pedagogique_etape_id                                                   etape_id,
            sne.etape_id                                                                     etape_ens_id,
            n.structure_id                                                                   structure_id,
            n.groupe_type_formation_id                                                       groupe_type_formation_id,

            vhe.heures                                                                       heures_ens,
            vhe.heures * ti.taux_hetd_service                                                hetd,

            COALESCE(sep.ouverture, se.ouverture,1)                                          ouverture,
            COALESCE(sep.dedoublement, se.dedoublement, sd.dedoublement,1)                   dedoublement,
            COALESCE(sep.assiduite,1)                                                        assiduite,
            sne.effectif*COALESCE(sep.assiduite,1)                                           effectif,
            SUM(sne.effectif*COALESCE(sep.assiduite,1))
              OVER (PARTITION BY n.noeud_id, sn.scenario_id, ti.id)                          t_effectif
        FROM
                      scenario_noeud_effectif sne

                 JOIN scenario_noeud           sn ON sn.id = sne.scenario_noeud_id
                                                 AND sn.histo_destruction IS NULL
                                                 /*@NOEUD_ID=sn.noeud_id*/
                                                 /*@SCENARIO_ID=sn.scenario_id*/

                 JOIN tbl_noeud                 n ON n.noeud_id = sn.noeud_id
                                                 /*@ANNEE_ID=n.annee_id*/
                                                 /*@ELEMENT_PEDAGOGIQUE_ID=n.element_pedagogique_id*/
                                                 /*@ETAPE_ID=n.element_pedagogique_etape_id*/

                 JOIN volume_horaire_ens      vhe ON vhe.element_pedagogique_id = n.element_pedagogique_id
                                                 AND vhe.histo_destruction IS NULL
                                                 AND vhe.heures > 0

                 JOIN type_intervention        ti ON ti.id = vhe.type_intervention_id

            LEFT JOIN seuils_perso            sep ON sep.element_pedagogique_id = n.element_pedagogique_id
                                                 AND sep.scenario_id = sn.scenario_id
                                                 AND sep.type_intervention_id = ti.id

            LEFT JOIN seuils_perso             se ON se.etape_id = n.element_pedagogique_etape_id
                                                 AND se.scenario_id = sn.scenario_id
                                                 AND se.type_intervention_id = ti.id

            LEFT JOIN tbl_chargens_seuils_def  sd ON sd.annee_id = n.annee_id
                                                 AND sd.scenario_id = sn.scenario_id
                                                 AND sd.structure_id = n.structure_etape_id
                                                 AND sd.groupe_type_formation_id = n.groupe_type_formation_id
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




  PROCEDURE C_CHARGENS_SEUILS_DEF(useParams BOOLEAN DEFAULT FALSE) IS
  TYPE r_cursor IS REF CURSOR;
  c r_cursor;
  d TBL_CHARGENS_SEUILS_DEF%rowtype;
  viewQuery CLOB;
  BEGIN
    viewQuery := 'SELECT
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
          COALESCE(sc1.dedoublement, sc2.dedoublement, sc3.dedoublement, sc4.dedoublement, 1) <> 1';

    OPEN c FOR '
    SELECT
      CASE WHEN
            t.ANNEE_ID                             = v.ANNEE_ID
        AND t.SCENARIO_ID                          = v.SCENARIO_ID
        AND t.STRUCTURE_ID                         = v.STRUCTURE_ID
        AND t.GROUPE_TYPE_FORMATION_ID             = v.GROUPE_TYPE_FORMATION_ID
        AND t.TYPE_INTERVENTION_ID                 = v.TYPE_INTERVENTION_ID
        AND t.DEDOUBLEMENT                         = v.DEDOUBLEMENT
      THEN -1 ELSE t.ID END ID,
      v.ANNEE_ID,
      v.SCENARIO_ID,
      v.STRUCTURE_ID,
      v.GROUPE_TYPE_FORMATION_ID,
      v.TYPE_INTERVENTION_ID,
      v.DEDOUBLEMENT
    FROM
      (' || QUERY_APPLY_PARAMS(viewQuery, useParams) || ') v
      FULL JOIN TBL_CHARGENS_SEUILS_DEF t ON
            t.ANNEE_ID                             = v.ANNEE_ID
        AND t.SCENARIO_ID                          = v.SCENARIO_ID
        AND t.STRUCTURE_ID                         = v.STRUCTURE_ID
        AND t.GROUPE_TYPE_FORMATION_ID             = v.GROUPE_TYPE_FORMATION_ID
        AND t.TYPE_INTERVENTION_ID                 = v.TYPE_INTERVENTION_ID
    WHERE ' || PARAMS_MAKE_FILTER(useParams);
    LOOP
      FETCH c INTO d; EXIT WHEN c%NOTFOUND;

      IF d.id IS NULL THEN
        d.id := TBL_CHARGENS_SEUILS_DEF_ID_SEQ.NEXTVAL;
        INSERT INTO TBL_CHARGENS_SEUILS_DEF values d;
      ELSIF
            d.ANNEE_ID IS NULL
        AND d.SCENARIO_ID IS NULL
        AND d.STRUCTURE_ID IS NULL
        AND d.GROUPE_TYPE_FORMATION_ID IS NULL
        AND d.TYPE_INTERVENTION_ID IS NULL
      THEN
        DELETE FROM TBL_CHARGENS_SEUILS_DEF WHERE id = d.id;
      ELSIF d.id <> -1 THEN
        UPDATE TBL_CHARGENS_SEUILS_DEF SET row = d WHERE id = d.id;
      END IF;
    END LOOP;
    CLOSE c;
  END;




  PROCEDURE C_CLOTURE_REALISE(useParams BOOLEAN DEFAULT FALSE) IS
  TYPE r_cursor IS REF CURSOR;
  c r_cursor;
  d TBL_CLOTURE_REALISE%rowtype;
  viewQuery CLOB;
  BEGIN
    viewQuery := 'WITH t AS (
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
            /*@INTERVENANT_ID=i.id*/
            /*@ANNEE_ID=i.annee_id*/
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
          peut_cloturer_saisie';

    OPEN c FOR '
    SELECT
      CASE WHEN
            t.ANNEE_ID                         = v.ANNEE_ID
        AND t.INTERVENANT_ID                   = v.INTERVENANT_ID
        AND t.PEUT_CLOTURER_SAISIE             = v.PEUT_CLOTURER_SAISIE
        AND t.CLOTURE                          = v.CLOTURE
      THEN -1 ELSE t.ID END ID,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_CLOTURER_SAISIE,
      v.CLOTURE
    FROM
      (' || QUERY_APPLY_PARAMS(viewQuery, useParams) || ') v
      FULL JOIN TBL_CLOTURE_REALISE t ON
            t.INTERVENANT_ID                   = v.INTERVENANT_ID
    WHERE ' || PARAMS_MAKE_FILTER(useParams);
    LOOP
      FETCH c INTO d; EXIT WHEN c%NOTFOUND;

      IF d.id IS NULL THEN
        d.id := TBL_CLOTURE_REALISE_ID_SEQ.NEXTVAL;
        INSERT INTO TBL_CLOTURE_REALISE values d;
      ELSIF
            d.INTERVENANT_ID IS NULL
      THEN
        DELETE FROM TBL_CLOTURE_REALISE WHERE id = d.id;
      ELSIF d.id <> -1 THEN
        UPDATE TBL_CLOTURE_REALISE SET row = d WHERE id = d.id;
      END IF;
    END LOOP;
    CLOSE c;
  END;




  PROCEDURE C_CONTRAT(useParams BOOLEAN DEFAULT FALSE) IS
  TYPE r_cursor IS REF CURSOR;
  c r_cursor;
  d TBL_CONTRAT%rowtype;
  viewQuery CLOB;
  BEGIN
    viewQuery := 'WITH t AS (
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
            /*@INTERVENANT_ID=i.id*/
            /*@ANNEE_ID=i.annee_id*/
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
          structure_id';

    OPEN c FOR '
    SELECT
      CASE WHEN
            t.ANNEE_ID                       = v.ANNEE_ID
        AND t.INTERVENANT_ID                 = v.INTERVENANT_ID
        AND t.PEUT_AVOIR_CONTRAT             = v.PEUT_AVOIR_CONTRAT
        AND COALESCE(t.STRUCTURE_ID,0)       = COALESCE(v.STRUCTURE_ID,0)
        AND t.NBVH                           = v.NBVH
        AND t.EDITE                          = v.EDITE
        AND t.SIGNE                          = v.SIGNE
      THEN -1 ELSE t.ID END ID,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_AVOIR_CONTRAT,
      v.STRUCTURE_ID,
      v.NBVH,
      v.EDITE,
      v.SIGNE
    FROM
      (' || QUERY_APPLY_PARAMS(viewQuery, useParams) || ') v
      FULL JOIN TBL_CONTRAT t ON
            t.INTERVENANT_ID                 = v.INTERVENANT_ID
        AND COALESCE(t.STRUCTURE_ID,0)       = COALESCE(v.STRUCTURE_ID,0)
    WHERE ' || PARAMS_MAKE_FILTER(useParams);
    LOOP
      FETCH c INTO d; EXIT WHEN c%NOTFOUND;

      IF d.id IS NULL THEN
        d.id := TBL_CONTRAT_ID_SEQ.NEXTVAL;
        INSERT INTO TBL_CONTRAT values d;
      ELSIF
            d.INTERVENANT_ID IS NULL
        AND d.STRUCTURE_ID IS NULL
      THEN
        DELETE FROM TBL_CONTRAT WHERE id = d.id;
      ELSIF d.id <> -1 THEN
        UPDATE TBL_CONTRAT SET row = d WHERE id = d.id;
      END IF;
    END LOOP;
    CLOSE c;
  END;




  PROCEDURE C_DMEP_LIQUIDATION(useParams BOOLEAN DEFAULT FALSE) IS
  TYPE r_cursor IS REF CURSOR;
  c r_cursor;
  d TBL_DMEP_LIQUIDATION%rowtype;
  viewQuery CLOB;
  BEGIN
    viewQuery := 'SELECT
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
            /*@INTERVENANT_ID=i.id*/
            /*@ANNEE_ID=i.annee_id*/

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
            /*@INTERVENANT_ID=i.id*/
            /*@ANNEE_ID=i.annee_id*/

        ) t1
        GROUP BY
          annee_id, type_ressource_id, structure_id';

    OPEN c FOR '
    SELECT
      CASE WHEN
            t.ANNEE_ID                      = v.ANNEE_ID
        AND t.TYPE_RESSOURCE_ID             = v.TYPE_RESSOURCE_ID
        AND t.STRUCTURE_ID                  = v.STRUCTURE_ID
        AND t.HEURES                        = v.HEURES
      THEN -1 ELSE t.ID END ID,
      v.ANNEE_ID,
      v.TYPE_RESSOURCE_ID,
      v.STRUCTURE_ID,
      v.HEURES
    FROM
      (' || QUERY_APPLY_PARAMS(viewQuery, useParams) || ') v
      FULL JOIN TBL_DMEP_LIQUIDATION t ON
            t.ANNEE_ID                      = v.ANNEE_ID
        AND t.TYPE_RESSOURCE_ID             = v.TYPE_RESSOURCE_ID
        AND t.STRUCTURE_ID                  = v.STRUCTURE_ID
    WHERE ' || PARAMS_MAKE_FILTER(useParams);
    LOOP
      FETCH c INTO d; EXIT WHEN c%NOTFOUND;

      IF d.id IS NULL THEN
        d.id := TBL_DMEP_LIQUIDATION_ID_SEQ.NEXTVAL;
        INSERT INTO TBL_DMEP_LIQUIDATION values d;
      ELSIF
            d.ANNEE_ID IS NULL
        AND d.TYPE_RESSOURCE_ID IS NULL
        AND d.STRUCTURE_ID IS NULL
      THEN
        DELETE FROM TBL_DMEP_LIQUIDATION WHERE id = d.id;
      ELSIF d.id <> -1 THEN
        UPDATE TBL_DMEP_LIQUIDATION SET row = d WHERE id = d.id;
      END IF;
    END LOOP;
    CLOSE c;
  END;




  PROCEDURE C_DOSSIER(useParams BOOLEAN DEFAULT FALSE) IS
  TYPE r_cursor IS REF CURSOR;
  c r_cursor;
  d TBL_DOSSIER%rowtype;
  viewQuery CLOB;
  BEGIN
    viewQuery := 'SELECT
          i.annee_id,
          i.id intervenant_id,
          si.peut_saisir_dossier,
          d.id dossier_id,
          v.id validation_id,
          /*Complétude statut*/
          CASE WHEN si.code = ''AUTRES'' THEN 0
          ELSE 1 END completude_statut,
          /*Complétude identité*/
          CASE WHEN
            (
              d.civilite_id IS NOT NULL
              AND d.nom_usuel IS NOT NULL
              AND d.prenom IS NOT NULL
            ) THEN 1 ELSE 0 END completude_identite,
           /*Complétude identité complémentaire*/
          CASE WHEN si.dossier_identite_comp = 0 THEN 1
          ELSE
          	  	CASE WHEN
          	  	(
          	  	   d.date_naissance IS NOT NULL
        		   AND NOT (OSE_DIVERS.str_reduce(pn.LIBELLE) = ''france'' AND d.departement_naissance_id IS NULL)
                   AND d.pays_naissance_id IS NOT NULL
                   AND d.pays_nationalite_id IS NOT NULL
                   AND d.commune_naissance IS NOT NULL
          	  	) THEN 1 ELSE 0 END
           END completude_identite_comp,
           /*Complétude contact*/
           CASE WHEN si.dossier_contact = 0 THEN 1
           ELSE
           (
                CASE WHEN
                (
                  (CASE WHEN si.dossier_email_perso = 1 THEN
                     CASE WHEN d.email_perso IS NOT NULL THEN 1 ELSE 0 END
                   ELSE
                     CASE WHEN d.email_pro IS NOT NULL OR d.email_perso IS NOT NULL THEN 1 ELSE 0 END
                   END) = 1
                   AND
                  (CASE WHEN si.dossier_tel_perso = 1 THEN
              	     CASE WHEN d.tel_perso IS NOT NULL AND d.tel_pro IS NOT NULL THEN 1 ELSE 0 END
                   ELSE
              	     CASE WHEN d.tel_pro IS NOT NULL OR d.tel_perso IS NOT NULL THEN 1 ELSE 0 END
                   END) = 1
                ) THEN 1 ELSE 0 END
           ) END completude_contact,
           /*Complétude adresse*/
           CASE WHEN si.dossier_adresse = 0 THEN 1
           ELSE
           (
            	CASE WHEN
        	    (
        	       d.adresse_precisions IS NOT NULL
        	       OR d.adresse_lieu_dit IS NOT NULL
        	       OR (d.adresse_voie IS NOT NULL AND d.adresse_numero IS NOT NULL)
        	    ) AND
        	    (
        		   d.adresse_commune IS NOT NULL
        	       AND d.adresse_code_postal IS NOT NULL
        	    ) THEN 1 ELSE 0 END
            ) END completude_adresse,
             /*Complétude INSEE*/
             CASE WHEN si.dossier_insee = 0 THEN 1
             ELSE
             (
             	CASE WHEN
             	(
             	d.numero_insee IS NOT NULL OR COALESCE(d.numero_insee_provisoire,0) = 1
             	) THEN 1 ELSE 0 END
             ) END completude_insee,
             /*Complétude IBAN*/
             CASE WHEN si.dossier_iban = 0 THEN 1
             ELSE
             (
             	CASE WHEN
             	(
             		(d.iban IS NOT NULL
            		AND d.bic IS NOT NULL)
            		OR COALESCE(d.rib_hors_sepa,0) = 1
             	) THEN 1 ELSE 0 END
             ) END completude_iban,
             /*Complétude employeur*/
             CASE WHEN si.dossier_employeur = 0 THEN 1
             ELSE
             (
             	CASE WHEN
             	(
             		d.employeur_id IS NOT NULL
             	) THEN 1 ELSE 0 END
             ) END completude_employeur,
             /*Complétude champs autres*/
             CASE WHEN
             (
               NOT (d.autre_1 IS NULL AND COALESCE(dca1.obligatoire,0) = 1)
               AND NOT (d.autre_2 IS NULL AND COALESCE(dca2.obligatoire,0) = 1)
               AND NOT (d.autre_3 IS NULL AND COALESCE(dca3.obligatoire,0) = 1)
               AND NOT (d.autre_4 IS NULL AND COALESCE(dca4.obligatoire,0) = 1)
               AND NOT (d.autre_5 IS NULL AND COALESCE(dca5.obligatoire,0) = 1)
             ) THEN 1 ELSE 0 END completude_autres

        FROM
                    intervenant         i
               JOIN statut_intervenant si ON si.id = i.statut_id
          LEFT JOIN intervenant_dossier d ON d.intervenant_id = i.id
                                         AND d.histo_destruction IS NULL
          LEFT JOIN pays               pn ON pn.id = d.pays_naissance_id

               JOIN type_validation tv ON tv.code = ''DONNEES_PERSO_PAR_COMP''
          LEFT JOIN validation       v ON v.intervenant_id = i.id
                                      AND v.type_validation_id = tv.id
                                      AND v.histo_destruction IS NULL
          /*Champs autre 1*/
          LEFT JOIN dossier_champ_autre_par_statut dcas1 ON dcas1.dossier_champ_autre_id = 1 AND dcas1.statut_id = si.id
          LEFT JOIN dossier_champ_autre dca1 ON dca1.id = 1 AND dcas1.dossier_champ_autre_id = dca1.id
         /*Champs autre 2*/
          LEFT JOIN dossier_champ_autre_par_statut dcas2 ON dcas2.dossier_champ_autre_id = 2 AND dcas2.statut_id = si.id
          LEFT JOIN dossier_champ_autre dca2 ON dca2.id = 2 AND dcas2.dossier_champ_autre_id = dca2.id
         /*Champs autre 3*/
          LEFT JOIN dossier_champ_autre_par_statut dcas3 ON dcas3.dossier_champ_autre_id = 3 AND dcas3.statut_id = si.id
          LEFT JOIN dossier_champ_autre dca3 ON dca3.id = 3 AND dcas3.dossier_champ_autre_id = dca3.id
         /*Champs autre 4*/
          LEFT JOIN dossier_champ_autre_par_statut dcas4 ON dcas4.dossier_champ_autre_id = 4 AND dcas4.statut_id = si.id
          LEFT JOIN dossier_champ_autre dca4 ON dca4.id = 4 AND dcas4.dossier_champ_autre_id = dca4.id
         /*Champs autre 5*/
          LEFT JOIN dossier_champ_autre_par_statut dcas5 ON dcas5.dossier_champ_autre_id = 5 AND dcas5.statut_id = si.id
          LEFT JOIN dossier_champ_autre dca5 ON dca5.id = 5 AND dcas5.dossier_champ_autre_id = dca5.id
        WHERE
          i.histo_destruction IS NULL
           /*@INTERVENANT_ID=i.id*/
          /*@ANNEE_ID=i.annee_id*/';

    OPEN c FOR '
    SELECT
      CASE WHEN
            t.ANNEE_ID                             = v.ANNEE_ID
        AND t.INTERVENANT_ID                       = v.INTERVENANT_ID
        AND t.PEUT_SAISIR_DOSSIER                  = v.PEUT_SAISIR_DOSSIER
        AND COALESCE(t.DOSSIER_ID,0)               = COALESCE(v.DOSSIER_ID,0)
        AND COALESCE(t.VALIDATION_ID,0)            = COALESCE(v.VALIDATION_ID,0)
        AND COALESCE(t.COMPLETUDE_STATUT,0)        = COALESCE(v.COMPLETUDE_STATUT,0)
        AND COALESCE(t.COMPLETUDE_IDENTITE,0)      = COALESCE(v.COMPLETUDE_IDENTITE,0)
        AND COALESCE(t.COMPLETUDE_IDENTITE_COMP,0) = COALESCE(v.COMPLETUDE_IDENTITE_COMP,0)
        AND COALESCE(t.COMPLETUDE_CONTACT,0)       = COALESCE(v.COMPLETUDE_CONTACT,0)
        AND COALESCE(t.COMPLETUDE_ADRESSE,0)       = COALESCE(v.COMPLETUDE_ADRESSE,0)
        AND COALESCE(t.COMPLETUDE_INSEE,0)         = COALESCE(v.COMPLETUDE_INSEE,0)
        AND COALESCE(t.COMPLETUDE_IBAN,0)          = COALESCE(v.COMPLETUDE_IBAN,0)
        AND COALESCE(t.COMPLETUDE_EMPLOYEUR,0)     = COALESCE(v.COMPLETUDE_EMPLOYEUR,0)
        AND COALESCE(t.COMPLETUDE_AUTRES,0)        = COALESCE(v.COMPLETUDE_AUTRES,0)
      THEN -1 ELSE t.ID END ID,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_SAISIR_DOSSIER,
      v.DOSSIER_ID,
      v.VALIDATION_ID,
      v.COMPLETUDE_STATUT,
      v.COMPLETUDE_IDENTITE,
      v.COMPLETUDE_IDENTITE_COMP,
      v.COMPLETUDE_CONTACT,
      v.COMPLETUDE_ADRESSE,
      v.COMPLETUDE_INSEE,
      v.COMPLETUDE_IBAN,
      v.COMPLETUDE_EMPLOYEUR,
      v.COMPLETUDE_AUTRES
    FROM
      (' || QUERY_APPLY_PARAMS(viewQuery, useParams) || ') v
      FULL JOIN TBL_DOSSIER t ON
            t.INTERVENANT_ID                       = v.INTERVENANT_ID
    WHERE ' || PARAMS_MAKE_FILTER(useParams);
    LOOP
      FETCH c INTO d; EXIT WHEN c%NOTFOUND;

      IF d.id IS NULL THEN
        d.id := TBL_DOSSIER_ID_SEQ.NEXTVAL;
        INSERT INTO TBL_DOSSIER values d;
      ELSIF
            d.INTERVENANT_ID IS NULL
      THEN
        DELETE FROM TBL_DOSSIER WHERE id = d.id;
      ELSIF d.id <> -1 THEN
        UPDATE TBL_DOSSIER SET row = d WHERE id = d.id;
      END IF;
    END LOOP;
    CLOSE c;
  END;




  PROCEDURE C_PAIEMENT(useParams BOOLEAN DEFAULT FALSE) IS
  TYPE r_cursor IS REF CURSOR;
  c r_cursor;
  d TBL_PAIEMENT%rowtype;
  viewQuery CLOB;
  BEGIN
    viewQuery := 'SELECT
          annee_id,
          service_id,
          service_referentiel_id,
          formule_res_service_id,
          formule_res_service_ref_id,
          intervenant_id,
          structure_id,
          mise_en_paiement_id,
          periode_paiement_id,
          domaine_fonctionnel_id,
          heures_a_payer,
          heures_a_payer_pond,
          heures_demandees,
          heures_payees,
          ROUND(pourc_exercice_aa,2)            pourc_exercice_aa,
          1 - ROUND(pourc_exercice_aa,2)        pourc_exercice_ac,
          ROUND(heures_aa,2)                    heures_aa,
          heures_demandees - ROUND(heures_aa,2) heures_ac
        FROM
        (
        SELECT
          i.annee_id                                  annee_id,
          frs.service_id                              service_id,
          NULL                                        service_referentiel_id,
          frs.id                                      formule_res_service_id,
          NULL                                        formule_res_service_ref_id,
          i.id                                        intervenant_id,
          COALESCE( ep.structure_id, i.structure_id ) structure_id,
          mep.id                                      mise_en_paiement_id,
          mep.periode_paiement_id                     periode_paiement_id,
          COALESCE(mep.domaine_fonctionnel_id, e.domaine_fonctionnel_id, to_number(p.valeur)) domaine_fonctionnel_id,
          frs.heures_compl_fi + frs.heures_compl_fc + frs.heures_compl_fa + frs.heures_compl_fc_majorees heures_a_payer,
          COUNT(*) OVER(PARTITION BY frs.id)          heures_a_payer_pond,
          COALESCE(mep.heures,0)                      heures_demandees,
          CASE WHEN mep.periode_paiement_id IS NULL THEN 0 ELSE mep.heures END heures_payees,
          pea.pourc_exercice_aa                       pourc_exercice_aa,
          SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id)  total_heures,
          SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id) * pea.pourc_exercice_aa  total_heures_aa,
          SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id ORDER BY mep.id) cumul_heures,
          CASE WHEN p2.valeur = ''prorata'' THEN COALESCE(mep.heures,0) * pea.pourc_exercice_aa ELSE ose_divers.CALC_HEURES_AA(
            COALESCE(mep.heures,0), -- heures
            pea.pourc_exercice_aa, -- pourc_exercice_aa
            SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id), -- total_heures
            SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id ORDER BY mep.id) -- cumul_heures
          ) END heures_aa
        FROM
                    formule_resultat_service        frs
               JOIN type_volume_horaire             tvh ON tvh.code = ''REALISE''
               JOIN etat_volume_horaire             evh ON evh.code = ''valide''
               JOIN parametre                         p ON p.nom = ''domaine_fonctionnel_ens_ext''
               JOIN parametre                        p2 ON p2.nom = ''regle_repartition_annee_civile''
               JOIN formule_resultat                 fr ON fr.id = frs.formule_resultat_id
                                                       AND fr.type_volume_horaire_id = tvh.id
                                                       AND fr.etat_volume_horaire_id = evh.id

               JOIN intervenant                       i ON i.id = fr.intervenant_id /*@INTERVENANT_ID=i.id*/ /*@ANNEE_ID=a.annee_id*/
               JOIN service                           s ON s.id = frs.service_id
               JOIN (
                 SELECT
                   frvh.formule_resultat_id,
                   vh.service_id,
                   CASE WHEN SUM(vh.heures) > 0 THEN
                     SUM(ose_divers.CALC_POURC_AA(vh.periode_id, vh.horaire_debut, vh.horaire_fin, i.annee_id) * vh.heures) / SUM(vh.heures)
                   ELSE
                     SUM(ose_divers.CALC_POURC_AA(vh.periode_id, vh.horaire_debut, vh.horaire_fin, i.annee_id))
                   END pourc_exercice_aa
                 FROM
                   volume_horaire             vh
                   JOIN service                s ON s.id = vh.service_id
                   JOIN intervenant            i ON i.id = s.intervenant_id /*@INTERVENANT_ID=i.id*/ /*@ANNEE_ID=a.annee_id*/
                   JOIN formule_resultat_vh frvh ON frvh.volume_horaire_id = vh.id
                 GROUP BY
                   frvh.formule_resultat_id,
                   vh.service_id
                 )                                  pea ON pea.formule_resultat_id = fr.id AND pea.service_id = s.id
          LEFT JOIN element_pedagogique              ep ON ep.id = s.element_pedagogique_id
          LEFT JOIN etape                             e ON e.id = ep.etape_id
          LEFT JOIN mise_en_paiement                mep ON mep.formule_res_service_id = frs.id
                                                       AND mep.histo_destruction IS NULL

        UNION ALL

        SELECT
          i.annee_id                                  annee_id,
          NULL                                        service_id,
          frs.service_referentiel_id                  service_referentiel_id,
          NULL                                        formule_res_service_id,
          frs.id                                      formule_res_service_ref_id,
          i.id                                        intervenant_id,
          sr.structure_id                             structure_id,
          mep.id                                      mise_en_paiement_id,
          mep.periode_paiement_id                     periode_paiement_id,
          COALESCE(mep.domaine_fonctionnel_id, fncr.domaine_fonctionnel_id) domaine_fonctionnel_id,
          frs.heures_compl_referentiel                heures_a_payer,
          COUNT(*) OVER(PARTITION BY frs.id)          heures_a_payer_pond,
          COALESCE(mep.heures,0)                           heures_demandees,
          CASE WHEN mep.periode_paiement_id IS NULL THEN 0 ELSE mep.heures END heures_payees,
          pea.pourc_exercice_aa                       pourc_exercice_aa,
          SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id)  total_heures,
          SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id) * pea.pourc_exercice_aa  total_heures_aa,
          SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id ORDER BY mep.id) cumul_heures,
          CASE WHEN p2.valeur = ''prorata'' THEN COALESCE(mep.heures,0) * pea.pourc_exercice_aa ELSE ose_divers.CALC_HEURES_AA(
            COALESCE(mep.heures,0), -- heures
            pea.pourc_exercice_aa, -- pourc_exercice_aa
            SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id), -- total_heures
            SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id ORDER BY mep.id) -- cumul_heures
          ) END heures_aa
        FROM
                    formule_resultat_service_ref    frs
               JOIN type_volume_horaire             tvh ON tvh.code = ''REALISE''
               JOIN etat_volume_horaire             evh ON evh.code = ''valide''
               JOIN parametre                        p2 ON p2.nom = ''regle_repartition_annee_civile''
               JOIN formule_resultat                 fr ON fr.id = frs.formule_resultat_id
                                                       AND fr.type_volume_horaire_id = tvh.id
                                                       AND fr.etat_volume_horaire_id = evh.id

               JOIN intervenant                       i ON i.id = fr.intervenant_id /*@INTERVENANT_ID=i.id*/ /*@ANNEE_ID=a.annee_id*/
               JOIN service_referentiel              sr ON sr.id = frs.service_referentiel_id
               JOIN (
                 SELECT
                   frvhr.formule_resultat_id,
                   vhr.service_referentiel_id,
                   CASE WHEN SUM(vhr.heures) > 0 THEN
                     SUM(ose_divers.CALC_POURC_AA(NULL, vhr.horaire_debut, vhr.horaire_fin, i.annee_id) * vhr.heures) / SUM(vhr.heures)
                   ELSE
                     SUM(ose_divers.CALC_POURC_AA(NULL, vhr.horaire_debut, vhr.horaire_fin, i.annee_id))
                   END pourc_exercice_aa
                 FROM
                   volume_horaire_ref vhr
                   JOIN service_referentiel        sr ON sr.id = vhr.service_referentiel_id
                   JOIN intervenant                 i ON i.id = sr.intervenant_id /*@INTERVENANT_ID=i.id*/ /*@ANNEE_ID=a.annee_id*/
                   JOIN formule_resultat_vh_ref frvhr ON frvhr.volume_horaire_ref_id = vhr.id
                 GROUP BY
                   frvhr.formule_resultat_id,
                   vhr.service_referentiel_id
                 ) pea ON pea.formule_resultat_id = fr.id AND pea.service_referentiel_id = sr.id
               JOIN fonction_referentiel           fncr ON fncr.id = sr.fonction_id
          LEFT JOIN mise_en_paiement                mep ON mep.formule_res_service_ref_id = frs.id
                                                       AND mep.histo_destruction IS NULL
        ) t';

    OPEN c FOR '
    SELECT
      CASE WHEN
            t.ANNEE_ID                               = v.ANNEE_ID
        AND COALESCE(t.SERVICE_ID,0)                 = COALESCE(v.SERVICE_ID,0)
        AND COALESCE(t.SERVICE_REFERENTIEL_ID,0)     = COALESCE(v.SERVICE_REFERENTIEL_ID,0)
        AND COALESCE(t.FORMULE_RES_SERVICE_ID,0)     = COALESCE(v.FORMULE_RES_SERVICE_ID,0)
        AND COALESCE(t.FORMULE_RES_SERVICE_REF_ID,0) = COALESCE(v.FORMULE_RES_SERVICE_REF_ID,0)
        AND t.INTERVENANT_ID                         = v.INTERVENANT_ID
        AND t.STRUCTURE_ID                           = v.STRUCTURE_ID
        AND COALESCE(t.MISE_EN_PAIEMENT_ID,0)        = COALESCE(v.MISE_EN_PAIEMENT_ID,0)
        AND COALESCE(t.PERIODE_PAIEMENT_ID,0)        = COALESCE(v.PERIODE_PAIEMENT_ID,0)
        AND COALESCE(t.DOMAINE_FONCTIONNEL_ID,0)     = COALESCE(v.DOMAINE_FONCTIONNEL_ID,0)
        AND t.HEURES_A_PAYER                         = v.HEURES_A_PAYER
        AND t.HEURES_A_PAYER_POND                    = v.HEURES_A_PAYER_POND
        AND t.HEURES_DEMANDEES                       = v.HEURES_DEMANDEES
        AND t.HEURES_PAYEES                          = v.HEURES_PAYEES
        AND t.POURC_EXERCICE_AA                      = v.POURC_EXERCICE_AA
        AND t.POURC_EXERCICE_AC                      = v.POURC_EXERCICE_AC
        AND t.HEURES_AA                              = v.HEURES_AA
        AND t.HEURES_AC                              = v.HEURES_AC
      THEN -1 ELSE t.ID END ID,
      v.ANNEE_ID,
      v.SERVICE_ID,
      v.SERVICE_REFERENTIEL_ID,
      v.FORMULE_RES_SERVICE_ID,
      v.FORMULE_RES_SERVICE_REF_ID,
      v.INTERVENANT_ID,
      v.STRUCTURE_ID,
      v.MISE_EN_PAIEMENT_ID,
      v.PERIODE_PAIEMENT_ID,
      v.DOMAINE_FONCTIONNEL_ID,
      v.HEURES_A_PAYER,
      v.HEURES_A_PAYER_POND,
      v.HEURES_DEMANDEES,
      v.HEURES_PAYEES,
      v.POURC_EXERCICE_AA,
      v.POURC_EXERCICE_AC,
      v.HEURES_AA,
      v.HEURES_AC
    FROM
      (' || QUERY_APPLY_PARAMS(viewQuery, useParams) || ') v
      FULL JOIN TBL_PAIEMENT t ON
            COALESCE(t.FORMULE_RES_SERVICE_ID,0)     = COALESCE(v.FORMULE_RES_SERVICE_ID,0)
        AND COALESCE(t.FORMULE_RES_SERVICE_REF_ID,0) = COALESCE(v.FORMULE_RES_SERVICE_REF_ID,0)
        AND t.INTERVENANT_ID                         = v.INTERVENANT_ID
        AND COALESCE(t.MISE_EN_PAIEMENT_ID,0)        = COALESCE(v.MISE_EN_PAIEMENT_ID,0)
    WHERE ' || PARAMS_MAKE_FILTER(useParams);
    LOOP
      FETCH c INTO d; EXIT WHEN c%NOTFOUND;

      IF d.id IS NULL THEN
        d.id := TBL_PAIEMENT_ID_SEQ.NEXTVAL;
        INSERT INTO TBL_PAIEMENT values d;
      ELSIF
            d.FORMULE_RES_SERVICE_ID IS NULL
        AND d.FORMULE_RES_SERVICE_REF_ID IS NULL
        AND d.INTERVENANT_ID IS NULL
        AND d.MISE_EN_PAIEMENT_ID IS NULL
      THEN
        DELETE FROM TBL_PAIEMENT WHERE id = d.id;
      ELSIF d.id <> -1 THEN
        UPDATE TBL_PAIEMENT SET row = d WHERE id = d.id;
      END IF;
    END LOOP;
    CLOSE c;
  END;




  PROCEDURE C_PIECE_JOINTE(useParams BOOLEAN DEFAULT FALSE) IS
  TYPE r_cursor IS REF CURSOR;
  c r_cursor;
  d TBL_PIECE_JOINTE%rowtype;
  viewQuery CLOB;
  BEGIN
    viewQuery := 'WITH t AS (
          SELECT
            pjd.annee_id                                                annee_id,
            pjd.type_piece_jointe_id                                    type_piece_jointe_id,
            pjd.intervenant_id                                          intervenant_id,
            CASE WHEN pjd.intervenant_id IS NULL THEN 0 ELSE 1 END      demandee,
            SUM(CASE WHEN pjf.id IS NULL THEN 0 ELSE 1 END)             fournie,
            MAX(pjf.validation_id) keep(DENSE_RANK FIRST ORDER BY pjf.annee_id DESC) validee,
            COALESCE(pjd.heures_pour_seuil,0)                           heures_pour_seuil,
            COALESCE(pjd.obligatoire,1)                                 obligatoire
          FROM
                      tbl_piece_jointe_demande  pjd
            LEFT JOIN tbl_piece_jointe_fournie  pjf ON pjf.code_intervenant = pjd.code_intervenant
                                                   AND pjf.type_piece_jointe_id = pjd.type_piece_jointe_id
                                                   AND pjd.annee_id BETWEEN pjf.annee_id AND COALESCE(pjf.date_archive - 1,pjf.date_validite - 1,(pjf.annee_id + pjf.duree_vie - 1))
          WHERE
            1=1
            /*@INTERVENANT_ID=pjd.intervenant_id*/
            /*@ANNEE_ID=pjd.annee_id*/
          GROUP BY
            pjd.annee_id, pjd.type_piece_jointe_id, pjd.intervenant_id, pjd.intervenant_id, pjd.heures_pour_seuil, pjd.obligatoire

          UNION ALL

          SELECT
            pjf.annee_id                                                annee_id,
            pjf.type_piece_jointe_id                                    type_piece_jointe_id,
            pjf.intervenant_id                                          intervenant_id,
            0                                                           demandee,
            1                                                           fournie,
            MAX(pjf.validation_id) keep(DENSE_RANK FIRST ORDER BY pjf.annee_id DESC) validee,
            0                                                           heures_pour_seuil,
            0                                                           obligatoire
          FROM
                      tbl_piece_jointe_fournie pjf
            LEFT JOIN tbl_piece_jointe_demande pjd ON pjd.intervenant_id = pjf.intervenant_id
                                                  AND pjd.type_piece_jointe_id = pjf.type_piece_jointe_id
          WHERE
            pjd.id IS NULL
            /*@INTERVENANT_ID=pjf.intervenant_id*/
            /*@ANNEE_ID=pjf.annee_id*/
          GROUP BY
            pjf.annee_id, pjf.type_piece_jointe_id, pjf.intervenant_id
        )
        SELECT
          annee_id,
          type_piece_jointe_id,
          intervenant_id,
          demandee,
          CASE WHEN fournie <> 0 THEN 1 ELSE 0 END fournie,
          CASE WHEN validee IS NULL THEN 0 ELSE 1 END validee,
          heures_pour_seuil,
          obligatoire
        FROM
          t';

    OPEN c FOR '
    SELECT
      CASE WHEN
            t.ANNEE_ID                         = v.ANNEE_ID
        AND t.TYPE_PIECE_JOINTE_ID             = v.TYPE_PIECE_JOINTE_ID
        AND t.INTERVENANT_ID                   = v.INTERVENANT_ID
        AND t.DEMANDEE                         = v.DEMANDEE
        AND t.FOURNIE                          = v.FOURNIE
        AND t.VALIDEE                          = v.VALIDEE
        AND t.HEURES_POUR_SEUIL                = v.HEURES_POUR_SEUIL
        AND t.OBLIGATOIRE                      = v.OBLIGATOIRE
      THEN -1 ELSE t.ID END ID,
      v.ANNEE_ID,
      v.TYPE_PIECE_JOINTE_ID,
      v.INTERVENANT_ID,
      v.DEMANDEE,
      v.FOURNIE,
      v.VALIDEE,
      v.HEURES_POUR_SEUIL,
      v.OBLIGATOIRE
    FROM
      (' || QUERY_APPLY_PARAMS(viewQuery, useParams) || ') v
      FULL JOIN TBL_PIECE_JOINTE t ON
            t.TYPE_PIECE_JOINTE_ID             = v.TYPE_PIECE_JOINTE_ID
        AND t.INTERVENANT_ID                   = v.INTERVENANT_ID
    WHERE ' || PARAMS_MAKE_FILTER(useParams);
    LOOP
      FETCH c INTO d; EXIT WHEN c%NOTFOUND;

      IF d.id IS NULL THEN
        d.id := TBL_PIECE_JOINTE_ID_SEQ.NEXTVAL;
        INSERT INTO TBL_PIECE_JOINTE values d;
      ELSIF
            d.TYPE_PIECE_JOINTE_ID IS NULL
        AND d.INTERVENANT_ID IS NULL
      THEN
        DELETE FROM TBL_PIECE_JOINTE WHERE id = d.id;
      ELSIF d.id <> -1 THEN
        UPDATE TBL_PIECE_JOINTE SET row = d WHERE id = d.id;
      END IF;
    END LOOP;
    CLOSE c;
  END;




  PROCEDURE C_PIECE_JOINTE_DEMANDE(useParams BOOLEAN DEFAULT FALSE) IS
  TYPE r_cursor IS REF CURSOR;
  c r_cursor;
  d TBL_PIECE_JOINTE_DEMANDE%rowtype;
  viewQuery CLOB;
  BEGIN
    viewQuery := 'WITH i_h AS (
          SELECT
            s.intervenant_id,
            SUM(CASE WHEN vh.MOTIF_NON_PAIEMENT_ID IS NULL THEN vh.heures ELSE 0 END) heures,
            SUM(CASE WHEN vh.MOTIF_NON_PAIEMENT_ID IS NOT NULL THEN vh.heures ELSE 0 END) heures_non_payables,
            sum(ep.taux_fc) fc
          FROM
                 service               s
            JOIN type_volume_horaire tvh ON tvh.code = ''PREVU''
            JOIN volume_horaire       vh ON vh.service_id = s.id
                                        AND vh.type_volume_horaire_id = tvh.id
                                        AND vh.histo_destruction IS NULL
            JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id -- Service sur l''établissement
          WHERE
            s.histo_destruction IS NULL
            /*@INTERVENANT_ID=s.intervenant_id*/
          GROUP BY
            s.intervenant_id
        )
        SELECT
          i.annee_id                      annee_id,
          i.code code_intervenant,
          i.id                            intervenant_id,
          tpj.id                          type_piece_jointe_id,
          MAX(COALESCE(i_h.heures, 0))    heures_pour_seuil,
          tpjs.obligatoire obligatoire
        FROM
                    intervenant                 i

          LEFT JOIN intervenant_dossier         d ON d.intervenant_id = i.id
                                                 AND d.histo_destruction IS NULL

               JOIN type_piece_jointe_statut tpjs ON tpjs.statut_intervenant_id = i.statut_id
                                                 AND tpjs.histo_destruction IS NULL
                                                 AND i.annee_id BETWEEN COALESCE(tpjs.annee_debut_id,i.annee_id) AND COALESCE(tpjs.annee_fin_id,i.annee_id)

               JOIN type_piece_jointe         tpj ON tpj.id = tpjs.type_piece_jointe_id
                                                 AND tpj.histo_destruction IS NULL

          LEFT JOIN                           i_h ON i_h.intervenant_id = i.id
        WHERE
          -- Gestion de l''historique
          i.histo_destruction IS NULL
          /*@INTERVENANT_ID=i.id*/
          /*@ANNEE_ID=i.annee_id*/

          -- Seuil HETD ou PJ obligatoire meme avec des heures non payables
          AND (COALESCE(i_h.heures,0) > COALESCE(tpjs.seuil_hetd,-1) OR (COALESCE(i_h.heures_non_payables,0) > 0 AND tpjs.obligatoire_hnp = 1 ))


          -- Le RIB n''est demandé QUE s''il est différent!!
          AND CASE
                WHEN tpjs.changement_rib = 0 OR d.id IS NULL THEN 1
                ELSE CASE WHEN replace(i.bic, '' '', '''') = replace(d.bic, '' '', '''') AND replace(i.iban, '' '', '''') = replace(d.iban, '' '', '''') THEN 0 ELSE 1 END
              END = 1

          -- Filtre FC
          AND (tpjs.fc = 0 OR i_h.fc > 0)
        GROUP BY
          i.annee_id,
          i.id,
          i.code,
          tpj.id,
          tpjs.obligatoire';

    OPEN c FOR '
    SELECT
      CASE WHEN
            t.ANNEE_ID                         = v.ANNEE_ID
        AND t.CODE_INTERVENANT                 = v.CODE_INTERVENANT
        AND t.INTERVENANT_ID                   = v.INTERVENANT_ID
        AND t.TYPE_PIECE_JOINTE_ID             = v.TYPE_PIECE_JOINTE_ID
        AND t.HEURES_POUR_SEUIL                = v.HEURES_POUR_SEUIL
        AND COALESCE(t.OBLIGATOIRE,0)          = COALESCE(v.OBLIGATOIRE,0)
      THEN -1 ELSE t.ID END ID,
      v.ANNEE_ID,
      v.CODE_INTERVENANT,
      v.INTERVENANT_ID,
      v.TYPE_PIECE_JOINTE_ID,
      v.HEURES_POUR_SEUIL,
      v.OBLIGATOIRE
    FROM
      (' || QUERY_APPLY_PARAMS(viewQuery, useParams) || ') v
      FULL JOIN TBL_PIECE_JOINTE_DEMANDE t ON
            t.INTERVENANT_ID                   = v.INTERVENANT_ID
        AND t.TYPE_PIECE_JOINTE_ID             = v.TYPE_PIECE_JOINTE_ID
    WHERE ' || PARAMS_MAKE_FILTER(useParams);
    LOOP
      FETCH c INTO d; EXIT WHEN c%NOTFOUND;

      IF d.id IS NULL THEN
        d.id := TBL_PIECE_JOINTE_DEMAND_ID_SEQ.NEXTVAL;
        INSERT INTO TBL_PIECE_JOINTE_DEMANDE values d;
      ELSIF
            d.INTERVENANT_ID IS NULL
        AND d.TYPE_PIECE_JOINTE_ID IS NULL
      THEN
        DELETE FROM TBL_PIECE_JOINTE_DEMANDE WHERE id = d.id;
      ELSIF d.id <> -1 THEN
        UPDATE TBL_PIECE_JOINTE_DEMANDE SET row = d WHERE id = d.id;
      END IF;
    END LOOP;
    CLOSE c;
  END;




  PROCEDURE C_PIECE_JOINTE_FOURNIE(useParams BOOLEAN DEFAULT FALSE) IS
  TYPE r_cursor IS REF CURSOR;
  c r_cursor;
  d TBL_PIECE_JOINTE_FOURNIE%rowtype;
  viewQuery CLOB;
  BEGIN
    viewQuery := 'SELECT
          i.annee_id,
          i.code code_intervenant,
          pj.type_piece_jointe_id,
          pj.intervenant_id,
          pj.id piece_jointe_id,
          v.id validation_id,
          f.id fichier_id,
        --  CASE WHEN MIN(COALESCE(tpjs.duree_vie,1)) IS NULL THEN 1 ELSE MIN(COALESCE(tpjs.duree_vie,1)) END duree_vie,
          --CASE WHEN MIN(COALESCE(tpjs.duree_vie,1)) IS NULL THEN i.annee_id+1 ELSE MIN(i.annee_id+COALESCE(tpjs.duree_vie,1)) END date_validite,
          MIN(COALESCE(tpjs.duree_vie,1)) duree_vie,
          MIN(COALESCE(tpjs.annee_fin_id+1, i.annee_id+COALESCE(tpjs.duree_vie,1))) date_validite,

          pj.date_archive date_archive
        FROM
                    piece_jointe          pj
               JOIN intervenant            i ON i.id = pj.intervenant_id
                                            AND i.histo_destruction IS NULL
               JOIN piece_jointe_fichier pjf ON pjf.piece_jointe_id = pj.id
               JOIN fichier                f ON f.id = pjf.fichier_id
                                            AND f.histo_destruction IS NULL
                LEFT JOIN type_piece_jointe_statut tpjs ON tpjs.statut_intervenant_id = i.statut_id
                                                   AND tpjs.type_piece_jointe_id = pj.type_piece_jointe_id
                                                   AND i.annee_id BETWEEN COALESCE(tpjs.annee_debut_id,i.annee_id) AND COALESCE(tpjs.annee_fin_id,i.annee_id)
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
          pj.date_archive';

    OPEN c FOR '
    SELECT
      CASE WHEN
            t.ANNEE_ID                         = v.ANNEE_ID
        AND t.CODE_INTERVENANT                 = v.CODE_INTERVENANT
        AND t.TYPE_PIECE_JOINTE_ID             = v.TYPE_PIECE_JOINTE_ID
        AND t.INTERVENANT_ID                   = v.INTERVENANT_ID
        AND t.PIECE_JOINTE_ID                  = v.PIECE_JOINTE_ID
        AND COALESCE(t.VALIDATION_ID,0)        = COALESCE(v.VALIDATION_ID,0)
        AND COALESCE(t.FICHIER_ID,0)           = COALESCE(v.FICHIER_ID,0)
        AND t.DUREE_VIE                        = v.DUREE_VIE
        AND COALESCE(t.DATE_VALIDITE,0)        = COALESCE(v.DATE_VALIDITE,0)
        AND COALESCE(t.DATE_ARCHIVE,0)         = COALESCE(v.DATE_ARCHIVE,0)
      THEN -1 ELSE t.ID END ID,
      v.ANNEE_ID,
      v.CODE_INTERVENANT,
      v.TYPE_PIECE_JOINTE_ID,
      v.INTERVENANT_ID,
      v.PIECE_JOINTE_ID,
      v.VALIDATION_ID,
      v.FICHIER_ID,
      v.DUREE_VIE,
      v.DATE_VALIDITE,
      v.DATE_ARCHIVE
    FROM
      (' || QUERY_APPLY_PARAMS(viewQuery, useParams) || ') v
      FULL JOIN TBL_PIECE_JOINTE_FOURNIE t ON
            t.TYPE_PIECE_JOINTE_ID             = v.TYPE_PIECE_JOINTE_ID
        AND t.INTERVENANT_ID                   = v.INTERVENANT_ID
        AND COALESCE(t.VALIDATION_ID,0)        = COALESCE(v.VALIDATION_ID,0)
        AND COALESCE(t.FICHIER_ID,0)           = COALESCE(v.FICHIER_ID,0)
    WHERE ' || PARAMS_MAKE_FILTER(useParams);
    LOOP
      FETCH c INTO d; EXIT WHEN c%NOTFOUND;

      IF d.id IS NULL THEN
        d.id := TBL_PIECE_JOINTE_FOURNI_ID_SEQ.NEXTVAL;
        INSERT INTO TBL_PIECE_JOINTE_FOURNIE values d;
      ELSIF
            d.TYPE_PIECE_JOINTE_ID IS NULL
        AND d.INTERVENANT_ID IS NULL
        AND d.VALIDATION_ID IS NULL
        AND d.FICHIER_ID IS NULL
      THEN
        DELETE FROM TBL_PIECE_JOINTE_FOURNIE WHERE id = d.id;
      ELSIF d.id <> -1 THEN
        UPDATE TBL_PIECE_JOINTE_FOURNIE SET row = d WHERE id = d.id;
      END IF;
    END LOOP;
    CLOSE c;
  END;




  PROCEDURE C_PLAFOND_ELEMENT(useParams BOOLEAN DEFAULT FALSE) IS
  TYPE r_cursor IS REF CURSOR;
  c r_cursor;
  d TBL_PLAFOND_ELEMENT%rowtype;
  viewQuery CLOB;
  BEGIN
    viewQuery := 'SELECT
          p.PLAFOND_ID,
          pa.PLAFOND_ETAT_ID,
          p.ANNEE_ID,
          p.TYPE_VOLUME_HORAIRE_ID,
          p.INTERVENANT_ID,
          p.ELEMENT_PEDAGOGIQUE_ID,
          p.HEURES,
          p.PLAFOND,
          p.DEROGATION,
          CASE WHEN p.heures > p.plafond + p.derogation + 0.05 THEN 1 ELSE 0 END depassement
        FROM
        (
          SELECT NULL PLAFOND_ID,NULL PLAFOND_ETAT_ID,NULL ANNEE_ID,NULL TYPE_VOLUME_HORAIRE_ID,NULL INTERVENANT_ID,NULL ELEMENT_PEDAGOGIQUE_ID,NULL HEURES,NULL PLAFOND,NULL DEROGATION FROM dual WHERE 0 = 1
        ) p
        JOIN plafond_application pa ON pa.plafond_id = p.plafond_id AND pa.type_volume_horaire_id = p.type_volume_horaire_id AND p.annee_id BETWEEN COALESCE(pa.annee_debut_id,p.annee_id) AND COALESCE(pa.annee_fin_id,p.annee_id)
        WHERE
          1=1
          /*@PLAFOND_ID=p.PLAFOND_ID*/
          /*@PLAFOND_ETAT_ID=p.PLAFOND_ETAT_ID*/
          /*@ANNEE_ID=p.ANNEE_ID*/
          /*@TYPE_VOLUME_HORAIRE_ID=p.TYPE_VOLUME_HORAIRE_ID*/
          /*@INTERVENANT_ID=p.INTERVENANT_ID*/
          /*@ELEMENT_PEDAGOGIQUE_ID=p.ELEMENT_PEDAGOGIQUE_ID*/';

    OPEN c FOR '
    SELECT
      CASE WHEN
            COALESCE(t.PLAFOND_ID,0)             = COALESCE(v.PLAFOND_ID,0)
        AND t.PLAFOND_ETAT_ID                    = v.PLAFOND_ETAT_ID
        AND t.ANNEE_ID                           = v.ANNEE_ID
        AND COALESCE(t.TYPE_VOLUME_HORAIRE_ID,0) = COALESCE(v.TYPE_VOLUME_HORAIRE_ID,0)
        AND t.INTERVENANT_ID                     = v.INTERVENANT_ID
        AND t.ELEMENT_PEDAGOGIQUE_ID             = v.ELEMENT_PEDAGOGIQUE_ID
        AND t.HEURES                             = v.HEURES
        AND t.PLAFOND                            = v.PLAFOND
        AND t.DEROGATION                         = v.DEROGATION
        AND t.DEPASSEMENT                        = v.DEPASSEMENT
      THEN -1 ELSE t.ID END ID,
      v.PLAFOND_ID,
      v.PLAFOND_ETAT_ID,
      v.ANNEE_ID,
      v.TYPE_VOLUME_HORAIRE_ID,
      v.INTERVENANT_ID,
      v.ELEMENT_PEDAGOGIQUE_ID,
      v.HEURES,
      v.PLAFOND,
      v.DEROGATION,
      v.DEPASSEMENT
    FROM
      (' || QUERY_APPLY_PARAMS(viewQuery, useParams) || ') v
      FULL JOIN TBL_PLAFOND_ELEMENT t ON
            COALESCE(t.PLAFOND_ID,0)             = COALESCE(v.PLAFOND_ID,0)
        AND t.ANNEE_ID                           = v.ANNEE_ID
        AND COALESCE(t.TYPE_VOLUME_HORAIRE_ID,0) = COALESCE(v.TYPE_VOLUME_HORAIRE_ID,0)
        AND t.INTERVENANT_ID                     = v.INTERVENANT_ID
        AND t.ELEMENT_PEDAGOGIQUE_ID             = v.ELEMENT_PEDAGOGIQUE_ID
    WHERE ' || PARAMS_MAKE_FILTER(useParams);
    LOOP
      FETCH c INTO d; EXIT WHEN c%NOTFOUND;

      IF d.id IS NULL THEN
        d.id := TBL_PLAFOND_ELEMENT_ID_SEQ.NEXTVAL;
        INSERT INTO TBL_PLAFOND_ELEMENT values d;
      ELSIF
            d.PLAFOND_ID IS NULL
        AND d.ANNEE_ID IS NULL
        AND d.TYPE_VOLUME_HORAIRE_ID IS NULL
        AND d.INTERVENANT_ID IS NULL
        AND d.ELEMENT_PEDAGOGIQUE_ID IS NULL
      THEN
        DELETE FROM TBL_PLAFOND_ELEMENT WHERE id = d.id;
      ELSIF d.id <> -1 THEN
        UPDATE TBL_PLAFOND_ELEMENT SET row = d WHERE id = d.id;
      END IF;
    END LOOP;
    CLOSE c;
  END;




  PROCEDURE C_PLAFOND_INTERVENANT(useParams BOOLEAN DEFAULT FALSE) IS
  TYPE r_cursor IS REF CURSOR;
  c r_cursor;
  d TBL_PLAFOND_INTERVENANT%rowtype;
  viewQuery CLOB;
  BEGIN
    viewQuery := 'SELECT
          p.PLAFOND_ID,
          pa.PLAFOND_ETAT_ID,
          p.ANNEE_ID,
          p.TYPE_VOLUME_HORAIRE_ID,
          p.INTERVENANT_ID,
          p.HEURES,
          p.PLAFOND,
          p.DEROGATION,
          CASE WHEN p.heures > p.plafond + p.derogation + 0.05 THEN 1 ELSE 0 END depassement
        FROM
        (
          SELECT 2 PLAFOND_ID, 0 DEROGATION, p.* FROM (
            SELECT
              i.annee_id                          annee_id,
              fr.type_volume_horaire_id           type_volume_horaire_id,
              i.id                                intervenant_id,
              fr.SERVICE_REFERENTIEL + fr.HEURES_COMPL_REFERENTIEL heures,
              si.plafond_referentiel              plafond
            FROM
              intervenant                     i
              JOIN etat_volume_horaire      evh ON evh.code = ''saisi''
              JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
              JOIN statut_intervenant        si ON si.id = i.statut_id
          ) p

          UNION ALL

          SELECT 4 PLAFOND_ID, 0 DEROGATION, p.* FROM (
            SELECT
              i.annee_id                             annee_id,
              fr.type_volume_horaire_id              type_volume_horaire_id,
              i.id                                   intervenant_id,
              fr.total - fr.heures_compl_fc_majorees heures,
              si.maximum_hetd                        plafond
            FROM
              intervenant                     i
              JOIN etat_volume_horaire      evh ON evh.code = ''saisi''
              JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
              JOIN statut_intervenant        si ON si.id = i.statut_id
          ) p

          UNION ALL

          SELECT 1 PLAFOND_ID, 0 DEROGATION, p.* FROM (
            SELECT
              i.annee_id                          annee_id,
              fr.type_volume_horaire_id           type_volume_horaire_id,
              i.id                                intervenant_id,
              fr.heures_compl_fc_majorees         heures,
              ROUND( (COALESCE(si.plafond_hc_remu_fc,0) - COALESCE(i.montant_indemnite_fc,0)) / a.taux_hetd, 2 ) plafond

            FROM
                   intervenant                i
              JOIN annee                      a ON a.id = i.annee_id
              JOIN statut_intervenant        si ON si.id = i.statut_id
              JOIN etat_volume_horaire      evh ON evh.code = ''saisi''
              JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
          ) p

          UNION ALL

          SELECT 5 PLAFOND_ID, 0 DEROGATION, p.* FROM (
            SELECT
              i.annee_id                          annee_id,
              fr.type_volume_horaire_id           type_volume_horaire_id,
              fr.intervenant_id                   intervenant_id,
              fr.heures_compl_fi + fr.heures_compl_fc + fr.heures_compl_fa + fr.heures_compl_referentiel heures,
              si.plafond_hc_hors_remu_fc          plafond
            FROM
                   intervenant                i
              JOIN statut_intervenant        si ON si.id = i.statut_id
              JOIN etat_volume_horaire      evh ON evh.code = ''saisi''
              JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
          ) p

          UNION ALL

          SELECT 8 PLAFOND_ID, 0 DEROGATION, p.* FROM (
            SELECT
              t.annee_id,
              t.type_volume_horaire_id,
              t.intervenant_id,
              t.heures,
              si.plafond_hc_fi_hors_ead           plafond
            FROM
              (
                SELECT
                  fr.type_volume_horaire_id           type_volume_horaire_id,
                  i.annee_id                          annee_id,
                  i.id                                intervenant_id,
                  i.statut_id                         statut_intervenant_id,
                  si.plafond_hc_fi_hors_ead           plafond,
                  SUM(frvh.heures_compl_fi)           heures
                FROM
                  intervenant                     i
                  JOIN etat_volume_horaire      evh ON evh.code = ''saisi''
                  JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
                  JOIN formule_resultat_vh     frvh ON frvh.formule_resultat_id = fr.id
                  JOIN volume_horaire            vh ON vh.id = frvh.volume_horaire_id
                  JOIN type_intervention         ti ON ti.id = vh.type_intervention_id
                  JOIN statut_intervenant        si ON si.id = i.statut_id
                WHERE
                  ti.regle_foad = 0
                GROUP BY
                  fr.type_volume_horaire_id,
                  i.annee_id,
                  i.id,
                  i.statut_id,
                  si.plafond_hc_fi_hors_ead
              ) t
                JOIN statut_intervenant si ON si.id = t.statut_intervenant_id
          ) p
        ) p
        JOIN plafond_application pa ON pa.plafond_id = p.plafond_id AND pa.type_volume_horaire_id = p.type_volume_horaire_id AND p.annee_id BETWEEN COALESCE(pa.annee_debut_id,p.annee_id) AND COALESCE(pa.annee_fin_id,p.annee_id)
        WHERE
          1=1
          /*@PLAFOND_ID=p.PLAFOND_ID*/
          /*@PLAFOND_ETAT_ID=p.PLAFOND_ETAT_ID*/
          /*@ANNEE_ID=p.ANNEE_ID*/
          /*@TYPE_VOLUME_HORAIRE_ID=p.TYPE_VOLUME_HORAIRE_ID*/
          /*@INTERVENANT_ID=p.INTERVENANT_ID*/';

    OPEN c FOR '
    SELECT
      CASE WHEN
            COALESCE(t.PLAFOND_ID,0)             = COALESCE(v.PLAFOND_ID,0)
        AND t.PLAFOND_ETAT_ID                    = v.PLAFOND_ETAT_ID
        AND t.ANNEE_ID                           = v.ANNEE_ID
        AND COALESCE(t.TYPE_VOLUME_HORAIRE_ID,0) = COALESCE(v.TYPE_VOLUME_HORAIRE_ID,0)
        AND t.INTERVENANT_ID                     = v.INTERVENANT_ID
        AND t.HEURES                             = v.HEURES
        AND t.PLAFOND                            = v.PLAFOND
        AND t.DEROGATION                         = v.DEROGATION
        AND t.DEPASSEMENT                        = v.DEPASSEMENT
      THEN -1 ELSE t.ID END ID,
      v.PLAFOND_ID,
      v.PLAFOND_ETAT_ID,
      v.ANNEE_ID,
      v.TYPE_VOLUME_HORAIRE_ID,
      v.INTERVENANT_ID,
      v.HEURES,
      v.PLAFOND,
      v.DEROGATION,
      v.DEPASSEMENT
    FROM
      (' || QUERY_APPLY_PARAMS(viewQuery, useParams) || ') v
      FULL JOIN TBL_PLAFOND_INTERVENANT t ON
            COALESCE(t.PLAFOND_ID,0)             = COALESCE(v.PLAFOND_ID,0)
        AND t.ANNEE_ID                           = v.ANNEE_ID
        AND COALESCE(t.TYPE_VOLUME_HORAIRE_ID,0) = COALESCE(v.TYPE_VOLUME_HORAIRE_ID,0)
        AND t.INTERVENANT_ID                     = v.INTERVENANT_ID
    WHERE ' || PARAMS_MAKE_FILTER(useParams);
    LOOP
      FETCH c INTO d; EXIT WHEN c%NOTFOUND;

      IF d.id IS NULL THEN
        d.id := TBL_PLAFOND_INTERVENANT_ID_SEQ.NEXTVAL;
        INSERT INTO TBL_PLAFOND_INTERVENANT values d;
      ELSIF
            d.PLAFOND_ID IS NULL
        AND d.ANNEE_ID IS NULL
        AND d.TYPE_VOLUME_HORAIRE_ID IS NULL
        AND d.INTERVENANT_ID IS NULL
      THEN
        DELETE FROM TBL_PLAFOND_INTERVENANT WHERE id = d.id;
      ELSIF d.id <> -1 THEN
        UPDATE TBL_PLAFOND_INTERVENANT SET row = d WHERE id = d.id;
      END IF;
    END LOOP;
    CLOSE c;
  END;




  PROCEDURE C_PLAFOND_REFERENTIEL(useParams BOOLEAN DEFAULT FALSE) IS
  TYPE r_cursor IS REF CURSOR;
  c r_cursor;
  d TBL_PLAFOND_REFERENTIEL%rowtype;
  viewQuery CLOB;
  BEGIN
    viewQuery := 'SELECT
          p.PLAFOND_ID,
          pa.PLAFOND_ETAT_ID,
          p.ANNEE_ID,
          p.TYPE_VOLUME_HORAIRE_ID,
          p.INTERVENANT_ID,
          p.FONCTION_REFERENTIEL_ID,
          p.HEURES,
          p.PLAFOND,
          p.DEROGATION,
          CASE WHEN p.heures > p.plafond + p.derogation + 0.05 THEN 1 ELSE 0 END depassement
        FROM
        (
          SELECT 3 PLAFOND_ID, 0 DEROGATION, p.* FROM (
            SELECT
              i.annee_id                        annee_id,
              vhr.type_volume_horaire_id        type_volume_horaire_id,
              i.id                              intervenant_id,
              fr.id                             fonction_referentiel_id,
              SUM(vhr.heures)                   heures,
              fr.plafond                        plafond
            FROM
                   service_referentiel       sr
              JOIN intervenant                i ON i.id = sr.intervenant_id
              JOIN fonction_referentiel      fr ON fr.id = sr.fonction_id
              JOIN volume_horaire_ref       vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
            WHERE
              sr.histo_destruction IS NULL
            GROUP BY
              i.annee_id, vhr.type_volume_horaire_id, i.id, fr.id, fr.plafond
          ) p

          UNION ALL

          SELECT 6 PLAFOND_ID, 0 DEROGATION, p.* FROM (
            SELECT
              i.annee_id                 annee_id,
              vhr.type_volume_horaire_id type_volume_horaire_id,
              i.id                       intervenant_id,
              fr.id                      fonction_referentiel_id,
              SUM(vhr.heures)            heures,
              fr.plafond                 plafond
            FROM
              service_referentiel       sr
              JOIN intervenant i ON i.id = sr.intervenant_id
              JOIN fonction_referentiel      frf ON frf.id = sr.fonction_id
              JOIN fonction_referentiel      fr ON fr.id = frf.parent_id
              JOIN volume_horaire_ref       vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
            WHERE
              sr.histo_destruction IS NULL
            GROUP BY
              i.annee_id, vhr.type_volume_horaire_id, i.id, fr.id, fr.plafond
          ) p
        ) p
        JOIN plafond_application pa ON pa.plafond_id = p.plafond_id AND pa.type_volume_horaire_id = p.type_volume_horaire_id AND p.annee_id BETWEEN COALESCE(pa.annee_debut_id,p.annee_id) AND COALESCE(pa.annee_fin_id,p.annee_id)
        WHERE
          1=1
          /*@PLAFOND_ID=p.PLAFOND_ID*/
          /*@PLAFOND_ETAT_ID=p.PLAFOND_ETAT_ID*/
          /*@ANNEE_ID=p.ANNEE_ID*/
          /*@TYPE_VOLUME_HORAIRE_ID=p.TYPE_VOLUME_HORAIRE_ID*/
          /*@INTERVENANT_ID=p.INTERVENANT_ID*/
          /*@FONCTION_REFERENTIEL_ID=p.FONCTION_REFERENTIEL_ID*/';

    OPEN c FOR '
    SELECT
      CASE WHEN
            COALESCE(t.PLAFOND_ID,0)              = COALESCE(v.PLAFOND_ID,0)
        AND t.PLAFOND_ETAT_ID                     = v.PLAFOND_ETAT_ID
        AND t.ANNEE_ID                            = v.ANNEE_ID
        AND COALESCE(t.TYPE_VOLUME_HORAIRE_ID,0)  = COALESCE(v.TYPE_VOLUME_HORAIRE_ID,0)
        AND t.INTERVENANT_ID                      = v.INTERVENANT_ID
        AND t.FONCTION_REFERENTIEL_ID             = v.FONCTION_REFERENTIEL_ID
        AND t.HEURES                              = v.HEURES
        AND t.PLAFOND                             = v.PLAFOND
        AND t.DEROGATION                          = v.DEROGATION
        AND t.DEPASSEMENT                         = v.DEPASSEMENT
      THEN -1 ELSE t.ID END ID,
      v.PLAFOND_ID,
      v.PLAFOND_ETAT_ID,
      v.ANNEE_ID,
      v.TYPE_VOLUME_HORAIRE_ID,
      v.INTERVENANT_ID,
      v.FONCTION_REFERENTIEL_ID,
      v.HEURES,
      v.PLAFOND,
      v.DEROGATION,
      v.DEPASSEMENT
    FROM
      (' || QUERY_APPLY_PARAMS(viewQuery, useParams) || ') v
      FULL JOIN TBL_PLAFOND_REFERENTIEL t ON
            COALESCE(t.PLAFOND_ID,0)              = COALESCE(v.PLAFOND_ID,0)
        AND t.ANNEE_ID                            = v.ANNEE_ID
        AND COALESCE(t.TYPE_VOLUME_HORAIRE_ID,0)  = COALESCE(v.TYPE_VOLUME_HORAIRE_ID,0)
        AND t.INTERVENANT_ID                      = v.INTERVENANT_ID
        AND t.FONCTION_REFERENTIEL_ID             = v.FONCTION_REFERENTIEL_ID
    WHERE ' || PARAMS_MAKE_FILTER(useParams);
    LOOP
      FETCH c INTO d; EXIT WHEN c%NOTFOUND;

      IF d.id IS NULL THEN
        d.id := TBL_PLAFOND_REFERENTIEL_ID_SEQ.NEXTVAL;
        INSERT INTO TBL_PLAFOND_REFERENTIEL values d;
      ELSIF
            d.PLAFOND_ID IS NULL
        AND d.ANNEE_ID IS NULL
        AND d.TYPE_VOLUME_HORAIRE_ID IS NULL
        AND d.INTERVENANT_ID IS NULL
        AND d.FONCTION_REFERENTIEL_ID IS NULL
      THEN
        DELETE FROM TBL_PLAFOND_REFERENTIEL WHERE id = d.id;
      ELSIF d.id <> -1 THEN
        UPDATE TBL_PLAFOND_REFERENTIEL SET row = d WHERE id = d.id;
      END IF;
    END LOOP;
    CLOSE c;
  END;




  PROCEDURE C_PLAFOND_STRUCTURE(useParams BOOLEAN DEFAULT FALSE) IS
  TYPE r_cursor IS REF CURSOR;
  c r_cursor;
  d TBL_PLAFOND_STRUCTURE%rowtype;
  viewQuery CLOB;
  BEGIN
    viewQuery := 'SELECT
          p.PLAFOND_ID,
          pa.PLAFOND_ETAT_ID,
          p.ANNEE_ID,
          p.TYPE_VOLUME_HORAIRE_ID,
          p.INTERVENANT_ID,
          p.STRUCTURE_ID,
          p.HEURES,
          p.PLAFOND,
          p.DEROGATION,
          CASE WHEN p.heures > p.plafond + p.derogation + 0.05 THEN 1 ELSE 0 END depassement
        FROM
        (
          SELECT 7 PLAFOND_ID, 0 DEROGATION, p.* FROM (
            SELECT
              i.annee_id                 annee_id,
              vhr.type_volume_horaire_id type_volume_horaire_id,
              i.id                       intervenant_id,
              s.id                       structure_id,
              SUM(vhr.heures)            heures,
              s.plafond_referentiel      plafond
            FROM
              service_referentiel       sr
              JOIN intervenant           i ON i.id = sr.intervenant_id
              JOIN structure             s ON s.id = sr.structure_id AND s.plafond_referentiel IS NOT NULL
              JOIN volume_horaire_ref  vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
            WHERE
              sr.histo_destruction IS NULL
            GROUP BY
              i.annee_id, vhr.type_volume_horaire_id, i.id, s.id, s.plafond_referentiel
          ) p
        ) p
        JOIN plafond_application pa ON pa.plafond_id = p.plafond_id AND pa.type_volume_horaire_id = p.type_volume_horaire_id AND p.annee_id BETWEEN COALESCE(pa.annee_debut_id,p.annee_id) AND COALESCE(pa.annee_fin_id,p.annee_id)
        WHERE
          1=1
          /*@PLAFOND_ID=p.PLAFOND_ID*/
          /*@PLAFOND_ETAT_ID=p.PLAFOND_ETAT_ID*/
          /*@ANNEE_ID=p.ANNEE_ID*/
          /*@TYPE_VOLUME_HORAIRE_ID=p.TYPE_VOLUME_HORAIRE_ID*/
          /*@INTERVENANT_ID=p.INTERVENANT_ID*/
          /*@STRUCTURE_ID=p.STRUCTURE_ID*/';

    OPEN c FOR '
    SELECT
      CASE WHEN
            COALESCE(t.PLAFOND_ID,0)             = COALESCE(v.PLAFOND_ID,0)
        AND t.PLAFOND_ETAT_ID                    = v.PLAFOND_ETAT_ID
        AND t.ANNEE_ID                           = v.ANNEE_ID
        AND COALESCE(t.TYPE_VOLUME_HORAIRE_ID,0) = COALESCE(v.TYPE_VOLUME_HORAIRE_ID,0)
        AND t.INTERVENANT_ID                     = v.INTERVENANT_ID
        AND t.STRUCTURE_ID                       = v.STRUCTURE_ID
        AND t.HEURES                             = v.HEURES
        AND t.PLAFOND                            = v.PLAFOND
        AND t.DEROGATION                         = v.DEROGATION
        AND t.DEPASSEMENT                        = v.DEPASSEMENT
      THEN -1 ELSE t.ID END ID,
      v.PLAFOND_ID,
      v.PLAFOND_ETAT_ID,
      v.ANNEE_ID,
      v.TYPE_VOLUME_HORAIRE_ID,
      v.INTERVENANT_ID,
      v.STRUCTURE_ID,
      v.HEURES,
      v.PLAFOND,
      v.DEROGATION,
      v.DEPASSEMENT
    FROM
      (' || QUERY_APPLY_PARAMS(viewQuery, useParams) || ') v
      FULL JOIN TBL_PLAFOND_STRUCTURE t ON
            COALESCE(t.PLAFOND_ID,0)             = COALESCE(v.PLAFOND_ID,0)
        AND t.ANNEE_ID                           = v.ANNEE_ID
        AND COALESCE(t.TYPE_VOLUME_HORAIRE_ID,0) = COALESCE(v.TYPE_VOLUME_HORAIRE_ID,0)
        AND t.INTERVENANT_ID                     = v.INTERVENANT_ID
        AND t.STRUCTURE_ID                       = v.STRUCTURE_ID
    WHERE ' || PARAMS_MAKE_FILTER(useParams);
    LOOP
      FETCH c INTO d; EXIT WHEN c%NOTFOUND;

      IF d.id IS NULL THEN
        d.id := TBL_PLAFOND_STRUCTURE_ID_SEQ.NEXTVAL;
        INSERT INTO TBL_PLAFOND_STRUCTURE values d;
      ELSIF
            d.PLAFOND_ID IS NULL
        AND d.ANNEE_ID IS NULL
        AND d.TYPE_VOLUME_HORAIRE_ID IS NULL
        AND d.INTERVENANT_ID IS NULL
        AND d.STRUCTURE_ID IS NULL
      THEN
        DELETE FROM TBL_PLAFOND_STRUCTURE WHERE id = d.id;
      ELSIF d.id <> -1 THEN
        UPDATE TBL_PLAFOND_STRUCTURE SET row = d WHERE id = d.id;
      END IF;
    END LOOP;
    CLOSE c;
  END;




  PROCEDURE C_PLAFOND_VOLUME_HORAIRE(useParams BOOLEAN DEFAULT FALSE) IS
  TYPE r_cursor IS REF CURSOR;
  c r_cursor;
  d TBL_PLAFOND_VOLUME_HORAIRE%rowtype;
  viewQuery CLOB;
  BEGIN
    viewQuery := 'SELECT
          p.PLAFOND_ID,
          pa.PLAFOND_ETAT_ID,
          p.ANNEE_ID,
          p.TYPE_VOLUME_HORAIRE_ID,
          p.INTERVENANT_ID,
          p.ELEMENT_PEDAGOGIQUE_ID,
          p.TYPE_INTERVENTION_ID,
          p.HEURES,
          p.PLAFOND,
          p.DEROGATION,
          CASE WHEN p.heures > p.plafond + p.derogation + 0.05 THEN 1 ELSE 0 END depassement
        FROM
        (
          SELECT 9 PLAFOND_ID, 0 DEROGATION, p.* FROM (
            WITH c AS (
              SELECT
                vhe.element_pedagogique_id,
                vhe.type_intervention_id,
                MAX(vhe.heures) heures,
                COALESCE( MAX(vhe.groupes), ROUND(SUM(t.groupes),10) ) groupes

              FROM
                volume_horaire_ens     vhe
                     JOIN parametre p ON p.nom = ''scenario_charges_services''
                LEFT JOIN tbl_chargens   t ON t.element_pedagogique_id = vhe.element_pedagogique_id
                                          AND t.type_intervention_id = vhe.type_intervention_id
                                          AND t.scenario_id = to_number(p.valeur)
              GROUP BY
                vhe.element_pedagogique_id,
                vhe.type_intervention_id
            ), s AS (
              SELECT
                i.annee_id,
                vh.type_volume_horaire_id,
                s.intervenant_id,
                s.element_pedagogique_id,
                vh.type_intervention_id,
                SUM(vh.heures) heures
              FROM
                volume_horaire vh
                JOIN service     s ON s.id = vh.service_id
                                  AND s.element_pedagogique_id IS NOT NULL
                                  AND s.histo_destruction IS NULL
                JOIN intervenant i ON i.id = s.intervenant_id
                                  AND i.histo_destruction IS NULL
              WHERE
                vh.histo_destruction IS NULL
              GROUP BY
                i.annee_id,
                vh.type_volume_horaire_id,
                s.intervenant_id,
                s.element_pedagogique_id,
                vh.type_intervention_id
            )
            SELECT
              s.annee_id                                  annee_id,
              s.type_volume_horaire_id                    type_volume_horaire_id,
              s.intervenant_id                            intervenant_id,
              s.element_pedagogique_id                    element_pedagogique_id,
              s.type_intervention_id                      type_intervention_id,
              s.heures                                    heures,
              COALESCE(c.heures * c.groupes,0)            plafond
            FROM
                        s
                   JOIN type_intervention ti ON ti.id = s.type_intervention_id
                   JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
              LEFT JOIN c ON c.element_pedagogique_id = s.element_pedagogique_id
                         AND c.type_intervention_id = COALESCE(ti.type_intervention_maquette_id,ti.id)
            WHERE
              s.heures - COALESCE(c.heures * c.groupes,0) > 0
          ) p
        ) p
        JOIN plafond_application pa ON pa.plafond_id = p.plafond_id AND pa.type_volume_horaire_id = p.type_volume_horaire_id AND p.annee_id BETWEEN COALESCE(pa.annee_debut_id,p.annee_id) AND COALESCE(pa.annee_fin_id,p.annee_id)
        WHERE
          1=1
          /*@PLAFOND_ID=p.PLAFOND_ID*/
          /*@PLAFOND_ETAT_ID=p.PLAFOND_ETAT_ID*/
          /*@ANNEE_ID=p.ANNEE_ID*/
          /*@TYPE_VOLUME_HORAIRE_ID=p.TYPE_VOLUME_HORAIRE_ID*/
          /*@INTERVENANT_ID=p.INTERVENANT_ID*/
          /*@ELEMENT_PEDAGOGIQUE_ID=p.ELEMENT_PEDAGOGIQUE_ID*/
          /*@TYPE_INTERVENTION_ID=p.TYPE_INTERVENTION_ID*/';

    OPEN c FOR '
    SELECT
      CASE WHEN
            COALESCE(t.PLAFOND_ID,0)             = COALESCE(v.PLAFOND_ID,0)
        AND t.PLAFOND_ETAT_ID                    = v.PLAFOND_ETAT_ID
        AND t.ANNEE_ID                           = v.ANNEE_ID
        AND COALESCE(t.TYPE_VOLUME_HORAIRE_ID,0) = COALESCE(v.TYPE_VOLUME_HORAIRE_ID,0)
        AND t.INTERVENANT_ID                     = v.INTERVENANT_ID
        AND t.ELEMENT_PEDAGOGIQUE_ID             = v.ELEMENT_PEDAGOGIQUE_ID
        AND COALESCE(t.TYPE_INTERVENTION_ID,0)   = COALESCE(v.TYPE_INTERVENTION_ID,0)
        AND t.HEURES                             = v.HEURES
        AND t.PLAFOND                            = v.PLAFOND
        AND t.DEROGATION                         = v.DEROGATION
        AND t.DEPASSEMENT                        = v.DEPASSEMENT
      THEN -1 ELSE t.ID END ID,
      v.PLAFOND_ID,
      v.PLAFOND_ETAT_ID,
      v.ANNEE_ID,
      v.TYPE_VOLUME_HORAIRE_ID,
      v.INTERVENANT_ID,
      v.ELEMENT_PEDAGOGIQUE_ID,
      v.TYPE_INTERVENTION_ID,
      v.HEURES,
      v.PLAFOND,
      v.DEROGATION,
      v.DEPASSEMENT
    FROM
      (' || QUERY_APPLY_PARAMS(viewQuery, useParams) || ') v
      FULL JOIN TBL_PLAFOND_VOLUME_HORAIRE t ON
            COALESCE(t.PLAFOND_ID,0)             = COALESCE(v.PLAFOND_ID,0)
        AND t.ANNEE_ID                           = v.ANNEE_ID
        AND COALESCE(t.TYPE_VOLUME_HORAIRE_ID,0) = COALESCE(v.TYPE_VOLUME_HORAIRE_ID,0)
        AND t.INTERVENANT_ID                     = v.INTERVENANT_ID
        AND t.ELEMENT_PEDAGOGIQUE_ID             = v.ELEMENT_PEDAGOGIQUE_ID
        AND COALESCE(t.TYPE_INTERVENTION_ID,0)   = COALESCE(v.TYPE_INTERVENTION_ID,0)
    WHERE ' || PARAMS_MAKE_FILTER(useParams);
    LOOP
      FETCH c INTO d; EXIT WHEN c%NOTFOUND;

      IF d.id IS NULL THEN
        d.id := TBL_PLAFOND_VOLUME_HORA_ID_SEQ.NEXTVAL;
        INSERT INTO TBL_PLAFOND_VOLUME_HORAIRE values d;
      ELSIF
            d.PLAFOND_ID IS NULL
        AND d.ANNEE_ID IS NULL
        AND d.TYPE_VOLUME_HORAIRE_ID IS NULL
        AND d.INTERVENANT_ID IS NULL
        AND d.ELEMENT_PEDAGOGIQUE_ID IS NULL
        AND d.TYPE_INTERVENTION_ID IS NULL
      THEN
        DELETE FROM TBL_PLAFOND_VOLUME_HORAIRE WHERE id = d.id;
      ELSIF d.id <> -1 THEN
        UPDATE TBL_PLAFOND_VOLUME_HORAIRE SET row = d WHERE id = d.id;
      END IF;
    END LOOP;
    CLOSE c;
  END;




  PROCEDURE C_SERVICE(useParams BOOLEAN DEFAULT FALSE) IS
  TYPE r_cursor IS REF CURSOR;
  c r_cursor;
  d TBL_SERVICE%rowtype;
  viewQuery CLOB;
  BEGIN
    viewQuery := 'WITH t AS (
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
          /*@INTERVENANT_ID=s.intervenant_id*/
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
        WHERE
          1=1
          /*@INTERVENANT_ID=i.id*/
          /*@ANNEE_ID=i.annee_id*/
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
          t.etape_histo';

    OPEN c FOR '
    SELECT
      CASE WHEN
            t.ANNEE_ID                                   = v.ANNEE_ID
        AND t.INTERVENANT_ID                             = v.INTERVENANT_ID
        AND COALESCE(t.INTERVENANT_STRUCTURE_ID,0)       = COALESCE(v.INTERVENANT_STRUCTURE_ID,0)
        AND COALESCE(t.STRUCTURE_ID,0)                   = COALESCE(v.STRUCTURE_ID,0)
        AND t.TYPE_INTERVENANT_ID                        = v.TYPE_INTERVENANT_ID
        AND t.TYPE_INTERVENANT_CODE                      = v.TYPE_INTERVENANT_CODE
        AND t.PEUT_SAISIR_SERVICE                        = v.PEUT_SAISIR_SERVICE
        AND COALESCE(t.ELEMENT_PEDAGOGIQUE_ID,0)         = COALESCE(v.ELEMENT_PEDAGOGIQUE_ID,0)
        AND t.SERVICE_ID                                 = v.SERVICE_ID
        AND COALESCE(t.ELEMENT_PEDAGOGIQUE_PERIODE_ID,0) = COALESCE(v.ELEMENT_PEDAGOGIQUE_PERIODE_ID,0)
        AND COALESCE(t.ETAPE_ID,0)                       = COALESCE(v.ETAPE_ID,0)
        AND COALESCE(t.TYPE_VOLUME_HORAIRE_ID,0)         = COALESCE(v.TYPE_VOLUME_HORAIRE_ID,0)
        AND t.TYPE_VOLUME_HORAIRE_CODE                   = v.TYPE_VOLUME_HORAIRE_CODE
        AND t.ELEMENT_PEDAGOGIQUE_HISTO                  = v.ELEMENT_PEDAGOGIQUE_HISTO
        AND t.ETAPE_HISTO                                = v.ETAPE_HISTO
        AND t.HAS_HEURES_MAUVAISE_PERIODE                = v.HAS_HEURES_MAUVAISE_PERIODE
        AND t.NBVH                                       = v.NBVH
        AND t.HEURES                                     = v.HEURES
        AND t.VALIDE                                     = v.VALIDE
      THEN -1 ELSE t.ID END ID,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.INTERVENANT_STRUCTURE_ID,
      v.STRUCTURE_ID,
      v.TYPE_INTERVENANT_ID,
      v.TYPE_INTERVENANT_CODE,
      v.PEUT_SAISIR_SERVICE,
      v.ELEMENT_PEDAGOGIQUE_ID,
      v.SERVICE_ID,
      v.ELEMENT_PEDAGOGIQUE_PERIODE_ID,
      v.ETAPE_ID,
      v.TYPE_VOLUME_HORAIRE_ID,
      v.TYPE_VOLUME_HORAIRE_CODE,
      v.ELEMENT_PEDAGOGIQUE_HISTO,
      v.ETAPE_HISTO,
      v.HAS_HEURES_MAUVAISE_PERIODE,
      v.NBVH,
      v.HEURES,
      v.VALIDE
    FROM
      (' || QUERY_APPLY_PARAMS(viewQuery, useParams) || ') v
      FULL JOIN TBL_SERVICE t ON
            t.SERVICE_ID                                 = v.SERVICE_ID
        AND COALESCE(t.TYPE_VOLUME_HORAIRE_ID,0)         = COALESCE(v.TYPE_VOLUME_HORAIRE_ID,0)
    WHERE ' || PARAMS_MAKE_FILTER(useParams);
    LOOP
      FETCH c INTO d; EXIT WHEN c%NOTFOUND;

      IF d.id IS NULL THEN
        d.id := TBL_SERVICE_ID_SEQ.NEXTVAL;
        INSERT INTO TBL_SERVICE values d;
      ELSIF
            d.SERVICE_ID IS NULL
        AND d.TYPE_VOLUME_HORAIRE_ID IS NULL
      THEN
        DELETE FROM TBL_SERVICE WHERE id = d.id;
      ELSIF d.id <> -1 THEN
        UPDATE TBL_SERVICE SET row = d WHERE id = d.id;
      END IF;
    END LOOP;
    CLOSE c;
  END;




  PROCEDURE C_SERVICE_REFERENTIEL(useParams BOOLEAN DEFAULT FALSE) IS
  TYPE r_cursor IS REF CURSOR;
  c r_cursor;
  d TBL_SERVICE_REFERENTIEL%rowtype;
  viewQuery CLOB;
  BEGIN
    viewQuery := 'WITH t AS (

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
            /*@INTERVENANT_ID=i.id*/
            /*@ANNEE_ID=i.annee_id*/
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
          structure_id';

    OPEN c FOR '
    SELECT
      CASE WHEN
            t.ANNEE_ID                           = v.ANNEE_ID
        AND t.INTERVENANT_ID                     = v.INTERVENANT_ID
        AND t.PEUT_SAISIR_SERVICE                = v.PEUT_SAISIR_SERVICE
        AND COALESCE(t.TYPE_VOLUME_HORAIRE_ID,0) = COALESCE(v.TYPE_VOLUME_HORAIRE_ID,0)
        AND COALESCE(t.STRUCTURE_ID,0)           = COALESCE(v.STRUCTURE_ID,0)
        AND t.NBVH                               = v.NBVH
        AND t.VALIDE                             = v.VALIDE
      THEN -1 ELSE t.ID END ID,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_SAISIR_SERVICE,
      v.TYPE_VOLUME_HORAIRE_ID,
      v.STRUCTURE_ID,
      v.NBVH,
      v.VALIDE
    FROM
      (' || QUERY_APPLY_PARAMS(viewQuery, useParams) || ') v
      FULL JOIN TBL_SERVICE_REFERENTIEL t ON
            t.INTERVENANT_ID                     = v.INTERVENANT_ID
        AND COALESCE(t.TYPE_VOLUME_HORAIRE_ID,0) = COALESCE(v.TYPE_VOLUME_HORAIRE_ID,0)
        AND COALESCE(t.STRUCTURE_ID,0)           = COALESCE(v.STRUCTURE_ID,0)
    WHERE ' || PARAMS_MAKE_FILTER(useParams);
    LOOP
      FETCH c INTO d; EXIT WHEN c%NOTFOUND;

      IF d.id IS NULL THEN
        d.id := TBL_SERVICE_REFERENTIEL_ID_SEQ.NEXTVAL;
        INSERT INTO TBL_SERVICE_REFERENTIEL values d;
      ELSIF
            d.INTERVENANT_ID IS NULL
        AND d.TYPE_VOLUME_HORAIRE_ID IS NULL
        AND d.STRUCTURE_ID IS NULL
      THEN
        DELETE FROM TBL_SERVICE_REFERENTIEL WHERE id = d.id;
      ELSIF d.id <> -1 THEN
        UPDATE TBL_SERVICE_REFERENTIEL SET row = d WHERE id = d.id;
      END IF;
    END LOOP;
    CLOSE c;
  END;




  PROCEDURE C_SERVICE_SAISIE(useParams BOOLEAN DEFAULT FALSE) IS
  TYPE r_cursor IS REF CURSOR;
  c r_cursor;
  d TBL_SERVICE_SAISIE%rowtype;
  viewQuery CLOB;
  BEGIN
    viewQuery := 'SELECT
          i.annee_id,
          i.id intervenant_id,
          si.peut_saisir_service,
          si.peut_saisir_referentiel,
          SUM( CASE WHEN tvhs.code = ''PREVU''   THEN NVL(vh .heures,0) ELSE 0 END ) heures_service_prev,
          SUM( CASE WHEN tvhrs.code = ''PREVU''   THEN NVL(vhr.heures,0) ELSE 0 END ) heures_referentiel_prev,
          SUM( CASE WHEN tvhs.code = ''REALISE'' THEN NVL(vh .heures,0) ELSE 0 END ) heures_service_real,
          SUM( CASE WHEN tvhrs.code = ''REALISE'' THEN NVL(vhr.heures,0) ELSE 0 END ) heures_referentiel_real
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
          /*@INTERVENANT_ID=i.id*/
          /*@ANNEE_ID=i.annee_id*/
        GROUP BY
          i.annee_id,
          i.id,
          si.peut_saisir_service,
          si.peut_saisir_referentiel';

    OPEN c FOR '
    SELECT
      CASE WHEN
            t.ANNEE_ID                            = v.ANNEE_ID
        AND t.INTERVENANT_ID                      = v.INTERVENANT_ID
        AND t.PEUT_SAISIR_SERVICE                 = v.PEUT_SAISIR_SERVICE
        AND t.PEUT_SAISIR_REFERENTIEL             = v.PEUT_SAISIR_REFERENTIEL
        AND t.HEURES_SERVICE_PREV                 = v.HEURES_SERVICE_PREV
        AND t.HEURES_REFERENTIEL_PREV             = v.HEURES_REFERENTIEL_PREV
        AND t.HEURES_SERVICE_REAL                 = v.HEURES_SERVICE_REAL
        AND t.HEURES_REFERENTIEL_REAL             = v.HEURES_REFERENTIEL_REAL
      THEN -1 ELSE t.ID END ID,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_SAISIR_SERVICE,
      v.PEUT_SAISIR_REFERENTIEL,
      v.HEURES_SERVICE_PREV,
      v.HEURES_REFERENTIEL_PREV,
      v.HEURES_SERVICE_REAL,
      v.HEURES_REFERENTIEL_REAL
    FROM
      (' || QUERY_APPLY_PARAMS(viewQuery, useParams) || ') v
      FULL JOIN TBL_SERVICE_SAISIE t ON
            t.INTERVENANT_ID                      = v.INTERVENANT_ID
    WHERE ' || PARAMS_MAKE_FILTER(useParams);
    LOOP
      FETCH c INTO d; EXIT WHEN c%NOTFOUND;

      IF d.id IS NULL THEN
        d.id := TBL_SERVICE_SAISIE_ID_SEQ.NEXTVAL;
        INSERT INTO TBL_SERVICE_SAISIE values d;
      ELSIF
            d.INTERVENANT_ID IS NULL
      THEN
        DELETE FROM TBL_SERVICE_SAISIE WHERE id = d.id;
      ELSIF d.id <> -1 THEN
        UPDATE TBL_SERVICE_SAISIE SET row = d WHERE id = d.id;
      END IF;
    END LOOP;
    CLOSE c;
  END;




  PROCEDURE C_VALIDATION_ENSEIGNEMENT(useParams BOOLEAN DEFAULT FALSE) IS
  TYPE r_cursor IS REF CURSOR;
  c r_cursor;
  d TBL_VALIDATION_ENSEIGNEMENT%rowtype;
  viewQuery CLOB;
  BEGIN
    viewQuery := 'SELECT DISTINCT
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
          AND NOT (vvh.validation_id IS NOT NULL AND v.id IS NULL)
          /*@INTERVENANT_ID=i.id*/
          /*@ANNEE_ID=i.annee_id*/';

    OPEN c FOR '
    SELECT
      CASE WHEN
            t.ANNEE_ID                           = v.ANNEE_ID
        AND t.INTERVENANT_ID                     = v.INTERVENANT_ID
        AND t.STRUCTURE_ID                       = v.STRUCTURE_ID
        AND t.TYPE_VOLUME_HORAIRE_ID             = v.TYPE_VOLUME_HORAIRE_ID
        AND t.SERVICE_ID                         = v.SERVICE_ID
        AND t.VOLUME_HORAIRE_ID                  = v.VOLUME_HORAIRE_ID
        AND t.AUTO_VALIDATION                    = v.AUTO_VALIDATION
        AND COALESCE(t.VALIDATION_ID,0)          = COALESCE(v.VALIDATION_ID,0)
      THEN -1 ELSE t.ID END ID,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.STRUCTURE_ID,
      v.TYPE_VOLUME_HORAIRE_ID,
      v.SERVICE_ID,
      v.VOLUME_HORAIRE_ID,
      v.AUTO_VALIDATION,
      v.VALIDATION_ID
    FROM
      (' || QUERY_APPLY_PARAMS(viewQuery, useParams) || ') v
      FULL JOIN TBL_VALIDATION_ENSEIGNEMENT t ON
            t.INTERVENANT_ID                     = v.INTERVENANT_ID
        AND t.STRUCTURE_ID                       = v.STRUCTURE_ID
        AND t.TYPE_VOLUME_HORAIRE_ID             = v.TYPE_VOLUME_HORAIRE_ID
        AND t.SERVICE_ID                         = v.SERVICE_ID
        AND t.VOLUME_HORAIRE_ID                  = v.VOLUME_HORAIRE_ID
        AND COALESCE(t.VALIDATION_ID,0)          = COALESCE(v.VALIDATION_ID,0)
    WHERE ' || PARAMS_MAKE_FILTER(useParams);
    LOOP
      FETCH c INTO d; EXIT WHEN c%NOTFOUND;

      IF d.id IS NULL THEN
        d.id := TBL_VALIDATION_ENSEIGNE_ID_SEQ.NEXTVAL;
        INSERT INTO TBL_VALIDATION_ENSEIGNEMENT values d;
      ELSIF
            d.INTERVENANT_ID IS NULL
        AND d.STRUCTURE_ID IS NULL
        AND d.TYPE_VOLUME_HORAIRE_ID IS NULL
        AND d.SERVICE_ID IS NULL
        AND d.VOLUME_HORAIRE_ID IS NULL
        AND d.VALIDATION_ID IS NULL
      THEN
        DELETE FROM TBL_VALIDATION_ENSEIGNEMENT WHERE id = d.id;
      ELSIF d.id <> -1 THEN
        UPDATE TBL_VALIDATION_ENSEIGNEMENT SET row = d WHERE id = d.id;
      END IF;
    END LOOP;
    CLOSE c;
  END;




  PROCEDURE C_VALIDATION_REFERENTIEL(useParams BOOLEAN DEFAULT FALSE) IS
  TYPE r_cursor IS REF CURSOR;
  c r_cursor;
  d TBL_VALIDATION_REFERENTIEL%rowtype;
  viewQuery CLOB;
  BEGIN
    viewQuery := 'SELECT DISTINCT
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
          s.histo_destruction IS NULL
          /*@INTERVENANT_ID=i.id*/
          /*@ANNEE_ID=i.annee_id*/';

    OPEN c FOR '
    SELECT
      CASE WHEN
            t.ANNEE_ID                           = v.ANNEE_ID
        AND t.INTERVENANT_ID                     = v.INTERVENANT_ID
        AND t.STRUCTURE_ID                       = v.STRUCTURE_ID
        AND t.TYPE_VOLUME_HORAIRE_ID             = v.TYPE_VOLUME_HORAIRE_ID
        AND t.SERVICE_REFERENTIEL_ID             = v.SERVICE_REFERENTIEL_ID
        AND t.VOLUME_HORAIRE_REF_ID              = v.VOLUME_HORAIRE_REF_ID
        AND t.AUTO_VALIDATION                    = v.AUTO_VALIDATION
        AND COALESCE(t.VALIDATION_ID,0)          = COALESCE(v.VALIDATION_ID,0)
      THEN -1 ELSE t.ID END ID,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.STRUCTURE_ID,
      v.TYPE_VOLUME_HORAIRE_ID,
      v.SERVICE_REFERENTIEL_ID,
      v.VOLUME_HORAIRE_REF_ID,
      v.AUTO_VALIDATION,
      v.VALIDATION_ID
    FROM
      (' || QUERY_APPLY_PARAMS(viewQuery, useParams) || ') v
      FULL JOIN TBL_VALIDATION_REFERENTIEL t ON
            t.INTERVENANT_ID                     = v.INTERVENANT_ID
        AND t.STRUCTURE_ID                       = v.STRUCTURE_ID
        AND t.TYPE_VOLUME_HORAIRE_ID             = v.TYPE_VOLUME_HORAIRE_ID
        AND t.SERVICE_REFERENTIEL_ID             = v.SERVICE_REFERENTIEL_ID
        AND t.VOLUME_HORAIRE_REF_ID              = v.VOLUME_HORAIRE_REF_ID
        AND COALESCE(t.VALIDATION_ID,0)          = COALESCE(v.VALIDATION_ID,0)
    WHERE ' || PARAMS_MAKE_FILTER(useParams);
    LOOP
      FETCH c INTO d; EXIT WHEN c%NOTFOUND;

      IF d.id IS NULL THEN
        d.id := TBL_VALIDATION_REFERENT_ID_SEQ.NEXTVAL;
        INSERT INTO TBL_VALIDATION_REFERENTIEL values d;
      ELSIF
            d.INTERVENANT_ID IS NULL
        AND d.STRUCTURE_ID IS NULL
        AND d.TYPE_VOLUME_HORAIRE_ID IS NULL
        AND d.SERVICE_REFERENTIEL_ID IS NULL
        AND d.VOLUME_HORAIRE_REF_ID IS NULL
        AND d.VALIDATION_ID IS NULL
      THEN
        DELETE FROM TBL_VALIDATION_REFERENTIEL WHERE id = d.id;
      ELSIF d.id <> -1 THEN
        UPDATE TBL_VALIDATION_REFERENTIEL SET row = d WHERE id = d.id;
      END IF;
    END LOOP;
    CLOSE c;
  END;


  -- END OF AUTOMATIC GENERATION --

END UNICAEN_TBL;