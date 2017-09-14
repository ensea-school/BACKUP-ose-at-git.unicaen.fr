-- ********************************************************************* --
-- *          à faire AVANT avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"', force => TRUE); END; 
/

ALTER TABLE "OSE"."SEUIL_CHARGE" ADD ("ANNEE_ID" NUMBER(*,0));
ALTER TABLE "OSE"."SEUIL_CHARGE" ADD CONSTRAINT "SEUIL_CHARGE_ANNEE_FK" FOREIGN KEY ("ANNEE_ID") REFERENCES "OSE"."ANNEE"("ID") ENABLE;
UPDATE seuil_charge SET annee_id = 2017;
ALTER TABLE SEUIL_CHARGE MODIFY (ANNEE_ID NOT NULL);

  CREATE TABLE "OSE"."TBL" 
   (	"TBL_NAME" VARCHAR2(30 CHAR) NOT NULL ENABLE,
	"TABLE_NAME" VARCHAR2(30 CHAR),
	"VIEW_NAME" VARCHAR2(30 CHAR),
	"SEQUENCE_NAME" VARCHAR2(30 CHAR),
	"CONSTRAINT_NAME" VARCHAR2(30 CHAR),
	"CUSTOM_CALCUL_PROC" VARCHAR2(100 CHAR),
	CONSTRAINT "TBL_PK" PRIMARY KEY ("TBL_NAME") ENABLE
   );

  CREATE TABLE "OSE"."TBL_DEMS" 
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"TBL_NAME" VARCHAR2(30 CHAR) NOT NULL ENABLE,
	"C1" VARCHAR2(30 CHAR),
	"V1" VARCHAR2(80 CHAR),
	"C2" VARCHAR2(30 CHAR),
	"V2" VARCHAR2(80 CHAR),
	"C3" VARCHAR2(30 CHAR),
	"V3" VARCHAR2(80 CHAR),
	"C4" VARCHAR2(30 CHAR),
	"V4" VARCHAR2(80 CHAR),
	"C5" VARCHAR2(30 CHAR),
	"V5" VARCHAR2(80 CHAR),
	CONSTRAINT "TBL_DEMS_TBL_FK" FOREIGN KEY ("TBL_NAME")
	 REFERENCES "OSE"."TBL" ("TBL_NAME") ON DELETE CASCADE ENABLE
   );

  CREATE TABLE "OSE"."TBL_DEPS" 
   (	"TBL_NAME" VARCHAR2(30 CHAR) NOT NULL ENABLE,
	"TBL_DEP_NAME" VARCHAR2(30 CHAR) NOT NULL ENABLE,
	CONSTRAINT "TBL_DEPS_PK" PRIMARY KEY ("TBL_NAME","TBL_DEP_NAME") ENABLE
   );




  CREATE TABLE "OSE"."TBL_CHARGENS_SEUILS_DEF" 
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"ANNEE_ID" NUMBER(*,0) NOT NULL ENABLE,
	"SCENARIO_ID" NUMBER(*,0) NOT NULL ENABLE,
	"STRUCTURE_ID" NUMBER(*,0) NOT NULL ENABLE,
	"GROUPE_TYPE_FORMATION_ID" NUMBER(*,0) NOT NULL ENABLE,
	"TYPE_INTERVENTION_ID" NUMBER(*,0) NOT NULL ENABLE,
	"DEDOUBLEMENT" FLOAT(126) NOT NULL ENABLE,
	"TO_DELETE" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE,
	CONSTRAINT "TBL_CHARGENS_SEUILS_DEF_PK" PRIMARY KEY ("ID") ENABLE,
	CONSTRAINT "TBL_CHARGENS_SEUILS_DEF__UN" UNIQUE ("SCENARIO_ID","TYPE_INTERVENTION_ID","STRUCTURE_ID","GROUPE_TYPE_FORMATION_ID","ANNEE_ID") ENABLE,
	CONSTRAINT "TBL_CSD_ANNEE_FK" FOREIGN KEY ("ANNEE_ID")
	 REFERENCES "OSE"."ANNEE" ("ID") ENABLE,
	CONSTRAINT "TBL_CSD_GTF_FK" FOREIGN KEY ("GROUPE_TYPE_FORMATION_ID")
	 REFERENCES "OSE"."GROUPE_TYPE_FORMATION" ("ID") ENABLE,
	CONSTRAINT "TBL_CSD_SCENARIO_FK" FOREIGN KEY ("SCENARIO_ID")
	 REFERENCES "OSE"."SCENARIO" ("ID") ENABLE,
	CONSTRAINT "TBL_CSD_STRUCTURE_FK" FOREIGN KEY ("STRUCTURE_ID")
	 REFERENCES "OSE"."STRUCTURE" ("ID") ENABLE,
	CONSTRAINT "TBL_CSD_TYPE_INTERVENTION_FK" FOREIGN KEY ("TYPE_INTERVENTION_ID")
	 REFERENCES "OSE"."TYPE_INTERVENTION" ("ID") ENABLE
   );

ALTER TABLE "OSE"."TBL_PAIEMENT" ADD CONSTRAINT "TBL_PAIEMENT__UN" UNIQUE ("INTERVENANT_ID","TO_DELETE","MISE_EN_PAIEMENT_ID","FORMULE_RES_SERVICE_ID","FORMULE_RES_SERVICE_REF_ID") ENABLE;

ALTER TABLE "OSE"."TYPE_INTERVENTION" DROP ("ENSEIGNEMENT");
ALTER TABLE "OSE"."TYPE_INTERVENTION" DROP ("INTERVENTION_INDIVIDUALISEE");
ALTER TABLE "OSE"."TYPE_INTERVENTION" DROP ("REGLE_CHARGENS");
ALTER TABLE "OSE"."TYPE_INTERVENTION" DROP ("REGLE_VHENS");


---------------------------
--Nouveau SEQUENCE
--TBL_NOEUD_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."TBL_NOEUD_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 13737394 NOCACHE NOORDER NOCYCLE;
---------------------------
--Nouveau SEQUENCE
--TBL_DEPENDANCES_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."TBL_DEPENDANCES_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 14 NOCACHE NOORDER NOCYCLE;
---------------------------
--Nouveau SEQUENCE
--TBL_DEMS_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."TBL_DEMS_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 14677 NOCACHE NOORDER NOCYCLE;
---------------------------
--Nouveau SEQUENCE
--TBL_CONFIG_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."TBL_CONFIG_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 16 NOCACHE NOORDER NOCYCLE;
---------------------------
--Nouveau SEQUENCE
--TBL_CONFIG_CLES_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."TBL_CONFIG_CLES_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE NOORDER NOCYCLE;
---------------------------
--Nouveau SEQUENCE
--TBL_CHARGENS_SEUILS_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."TBL_CHARGENS_SEUILS_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1091727 NOCACHE NOORDER NOCYCLE;
---------------------------
--Nouveau SEQUENCE
--TBL_CHARGENS_SEUILS_DEF_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."TBL_CHARGENS_SEUILS_DEF_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 37148 NOCACHE NOORDER NOCYCLE;


CREATE UNIQUE INDEX "OSE"."TBL_NOEUD_PK" ON "OSE"."TBL_NOEUD" ("ID");
CREATE UNIQUE INDEX "OSE"."TBL_DEPS_PK" ON "OSE"."TBL_DEPS" ("TBL_NAME","TBL_DEP_NAME");
CREATE UNIQUE INDEX "OSE"."TBL_PAIEMENT__UN" ON "OSE"."TBL_PAIEMENT" ("INTERVENANT_ID","TO_DELETE","MISE_EN_PAIEMENT_ID","FORMULE_RES_SERVICE_ID","FORMULE_RES_SERVICE_REF_ID");
CREATE UNIQUE INDEX "OSE"."TBL_CHARGENS_SEUILS_DEF_PK" ON "OSE"."TBL_CHARGENS_SEUILS_DEF" ("ID");
CREATE UNIQUE INDEX "OSE"."TBL_PK" ON "OSE"."TBL" ("TBL_NAME");
CREATE UNIQUE INDEX "OSE"."TBL_CHARGENS_SEUILS_DEF__UN" ON "OSE"."TBL_CHARGENS_SEUILS_DEF" ("SCENARIO_ID","TYPE_INTERVENTION_ID","STRUCTURE_ID","GROUPE_TYPE_FORMATION_ID","ANNEE_ID");

/

CREATE OR REPLACE PACKAGE "OSE"."UNICAEN_TBL" AS 

  TYPE t_params IS TABLE OF VARCHAR2(100) INDEX BY VARCHAR2(30);

  CALCUL_PROC_PARAMS t_params;

  ACTIV_TRIGGERS BOOLEAN DEFAULT TRUE;
  ACTIV_CALCULS  BOOLEAN DEFAULT TRUE;

  FUNCTION MAKE_PARAMS(
    c1 VARCHAR2 DEFAULT NULL, v1 VARCHAR2 DEFAULT NULL,
    c2 VARCHAR2 DEFAULT NULL, v2 VARCHAR2 DEFAULT NULL,
    c3 VARCHAR2 DEFAULT NULL, v3 VARCHAR2 DEFAULT NULL,
    c4 VARCHAR2 DEFAULT NULL, v4 VARCHAR2 DEFAULT NULL,
    c5 VARCHAR2 DEFAULT NULL, v5 VARCHAR2 DEFAULT NULL
  ) RETURN t_params;

  FUNCTION MATCH_PARAM( PARAM_NAME VARCHAR2, PARAM_VALUE VARCHAR2 DEFAULT NULL ) RETURN NUMERIC;

  PROCEDURE DEMANDE_CALCUL_SANS_DEPS( TBL_NAME VARCHAR2, PARAMS t_params );
  PROCEDURE DEMANDE_CALCUL( TBL_NAME VARCHAR2, PARAMS t_params );

  PROCEDURE CALCULER_SANS_DEPS( TBL_NAME VARCHAR2, PARAMS t_params );
  PROCEDURE CALCULER( TBL_NAME VARCHAR2, PARAMS t_params, WITH_DEPS BOOLEAN DEFAULT FALSE, WITH_SUCS BOOLEAN DEFAULT TRUE );
  PROCEDURE CALCULER_DEMANDES;

  -- AUTOMATIC GENERATION --



  -- END OF AUTOMATIC GENERATION --

END UNICAEN_TBL;
/


CREATE OR REPLACE PACKAGE BODY "OSE"."UNICAEN_TBL" AS 
  TYPE t_dep IS TABLE OF VARCHAR2(30) INDEX BY PLS_INTEGER;
  TYPE t_deps IS TABLE OF t_dep INDEX BY VARCHAR2(80);
  TYPE t_flat_params IS RECORD (
    c1 VARCHAR2(30), v1 VARCHAR2(80),
    c2 VARCHAR2(30), v2 VARCHAR2(80),
    c3 VARCHAR2(30), v3 VARCHAR2(80),
    c4 VARCHAR2(30), v4 VARCHAR2(80),
    c5 VARCHAR2(30), v5 VARCHAR2(80)
  );



  FUNCTION MAKE_PARAMS(
    c1 VARCHAR2 DEFAULT NULL, v1 VARCHAR2 DEFAULT NULL,
    c2 VARCHAR2 DEFAULT NULL, v2 VARCHAR2 DEFAULT NULL,
    c3 VARCHAR2 DEFAULT NULL, v3 VARCHAR2 DEFAULT NULL,
    c4 VARCHAR2 DEFAULT NULL, v4 VARCHAR2 DEFAULT NULL,
    c5 VARCHAR2 DEFAULT NULL, v5 VARCHAR2 DEFAULT NULL
  ) RETURN t_params IS
    params t_params;
  BEGIN
    IF c1 IS NOT NULL THEN
      params(c1) := v1;
    END IF;
    IF c2 IS NOT NULL THEN
      params(c2) := v2;
    END IF;
    IF c3 IS NOT NULL THEN
      params(c3) := v3;
    END IF;
    IF c4 IS NOT NULL THEN
      params(c4) := v4;
    END IF;
    IF c5 IS NOT NULL THEN
      params(c5) := v5;
    END IF;

    RETURN params;
  END;



  FUNCTION MATCH_PARAM( PARAM_NAME VARCHAR2, PARAM_VALUE VARCHAR2 DEFAULT NULL ) RETURN NUMERIC IS
    val VARCHAR2(80);
  BEGIN
    val := UNICAEN_TBL.CALCUL_PROC_PARAMS(PARAM_NAME);

    IF UNICAEN_TBL.CALCUL_PROC_PARAMS(PARAM_NAME) = PARAM_VALUE THEN RETURN 1; ELSE RETURN 0; END IF;

  EXCEPTION WHEN NO_DATA_FOUND THEN
    RETURN 1;
  END;



  FUNCTION FLATTEN_PARAMS ( PARAMS t_params ) RETURN t_flat_params IS
    c VARCHAR2(30);
    i NUMERIC DEFAULT 1;
    res t_flat_params;
  BEGIN
    c := params.FIRST;
    LOOP EXIT WHEN c IS NULL;
      IF 1 = i THEN res.c1 := c; res.v1 := params(c); END IF;
      IF 2 = i THEN res.c2 := c; res.v2 := params(c); END IF;
      IF 3 = i THEN res.c3 := c; res.v3 := params(c); END IF;
      IF 4 = i THEN res.c4 := c; res.v4 := params(c); END IF;
      IF 5 = i THEN res.c5 := c; res.v5 := params(c); END IF;

      i := i + 1;
      c := params.NEXT(c);
    END LOOP;
    RETURN res;
  END;



  FUNCTION GET_DEPS RETURN t_deps IS 
    i PLS_INTEGER DEFAULT 1;
    deps t_deps;
  BEGIN
    FOR d IN (
      SELECT     CONNECT_BY_ROOT(tbl_name) tref, tbl_dep_name tdep, level
      FROM       tbl_deps
      CONNECT BY PRIOR tbl_dep_name = tbl_name
      ORDER BY   LEVEL DESC
    ) LOOP
      deps(d.tref)(i) := d.tdep;
      i := i + 1;
    END LOOP;

    RETURN deps;
  END;



  FUNCTION GET_SUCS RETURN t_deps IS 
    i PLS_INTEGER DEFAULT 1;
    sucs t_deps;
  BEGIN
    FOR d IN (
      SELECT     CONNECT_BY_ROOT(tbl_dep_name) tref, tbl_name tdep, level
      FROM       tbl_deps
      CONNECT BY PRIOR tbl_name = tbl_dep_name
      ORDER BY   LEVEL
    ) LOOP
        sucs(d.tref)(i) := d.tdep;
        i := i + 1;
    END LOOP;

    RETURN sucs;
  END;



  PROCEDURE DEMANDE_CALCUL_SANS_DEPS( TBL_NAME VARCHAR2, PARAMS t_params ) IS
    fp  t_flat_params;
    cx  NUMERIC DEFAULT 0;
  BEGIN
    fp := flatten_params( params );

    SELECT 
      count(*) INTO cx 
    FROM
      tbl_dems td
    WHERE
      td.tbl_name = DEMANDE_CALCUL_SANS_DEPS.TBL_NAME
      AND rownum = 1
      AND (
           ( td.c1 = fp.c1 AND td.v1 = fp.v1  AND  td.c2 IS NULL                    AND  td.c3 IS NULL                    AND  td.c4 IS NULL                    AND  td.c5 IS NULL                   )
        OR ( td.c1 = fp.c1 AND td.v1 = fp.v1  AND  td.c2 = fp.c2 AND td.v2 = fp.v2  AND  td.c3 IS NULL                    AND  td.c4 IS NULL                    AND  td.c5 IS NULL                   )
        OR ( td.c1 = fp.c1 AND td.v1 = fp.v1  AND  td.c2 = fp.c2 AND td.v2 = fp.v2  AND  td.c3 = fp.c3 AND td.v3 = fp.v3  AND  td.c4 IS NULL                    AND  td.c5 IS NULL                   )
        OR ( td.c1 = fp.c1 AND td.v1 = fp.v1  AND  td.c2 = fp.c2 AND td.v2 = fp.v2  AND  td.c3 = fp.c3 AND td.v3 = fp.v3  AND  td.c4 = fp.c4 AND td.v4 = fp.v4  AND  td.c5 IS NULL                   )
        OR ( td.c1 = fp.c1 AND td.v1 = fp.v1  AND  td.c2 = fp.c2 AND td.v2 = fp.v2  AND  td.c3 = fp.c3 AND td.v3 = fp.v3  AND  td.c4 = fp.c4 AND td.v4 = fp.v4  AND  td.c5 = fp.c5 AND td.v5 = fp.v5 )
      );

    IF cx = 0 THEN
      INSERT INTO tbl_dems (
        ID, 
        TBL_NAME,
        c1, v1,
        c2, v2,
        c3, v3,
        c4, v4,
        c5, v5
      ) VALUES (
        TBL_DEMS_ID_SEQ.NEXTVAL, 
        DEMANDE_CALCUL_SANS_DEPS.TBL_NAME,
        fp.c1, fp.v1,
        fp.c2, fp.v2,
        fp.c3, fp.v3,
        fp.c4, fp.v4,
        fp.c5, fp.v5
      );
    END IF;

  END;



  PROCEDURE DEMANDE_CALCUL( TBL_NAME VARCHAR2, PARAMS t_params ) IS
    sucs t_deps;
    i PLS_INTEGER;
  BEGIN
    DEMANDE_CALCUL_SANS_DEPS(TBL_NAME, PARAMS);

    sucs := GET_SUCS;
    IF sucs.exists(TBL_NAME) THEN
      i := sucs(TBL_NAME).FIRST;
      LOOP EXIT WHEN i IS NULL;
        DEMANDE_CALCUL_SANS_DEPS( sucs(TBL_NAME)(i), PARAMS );
        i := sucs(TBL_NAME).next(i);
      END LOOP;
    END IF;
  END;



  PROCEDURE CALCULER_SANS_DEPS( TBL_NAME VARCHAR2, PARAMS t_params ) IS
    calcul_proc VARCHAR2(100);
    empty_params t_params;
  BEGIN 
    IF NOT UNICAEN_TBL.ACTIV_CALCULS THEN RETURN; END IF;

    SELECT custom_calcul_proc INTO calcul_proc FROM tbl WHERE tbl_name = CALCULER_SANS_DEPS.TBL_NAME;

    IF calcul_proc IS NOT NULL THEN
      UNICAEN_TBL.CALCUL_PROC_PARAMS := PARAMS;
      EXECUTE IMMEDIATE 
        'BEGIN ' || calcul_proc || '(UNICAEN_TBL.CALCUL_PROC_PARAMS); END;'
      ;
      UNICAEN_TBL.CALCUL_PROC_PARAMS := empty_params;
    ELSE
      UNICAEN_TBL.CALCUL_PROC_PARAMS := PARAMS;
      EXECUTE IMMEDIATE 
        'BEGIN UNICAEN_TBL.C_' || TBL_NAME || '(UNICAEN_TBL.CALCUL_PROC_PARAMS); END;'
      ;
      UNICAEN_TBL.CALCUL_PROC_PARAMS := empty_params;
    END IF;
  END;



  PROCEDURE CALCULER( TBL_NAME VARCHAR2, PARAMS t_params, WITH_DEPS BOOLEAN DEFAULT FALSE, WITH_SUCS BOOLEAN DEFAULT TRUE ) IS
    deps t_deps;
    i PLS_INTEGER;
  BEGIN
    IF NOT UNICAEN_TBL.ACTIV_CALCULS THEN RETURN; END IF;

    IF WITH_DEPS THEN
      deps := GET_DEPS;
      IF deps.exists(TBL_NAME) THEN 
        i := deps(TBL_NAME).FIRST;
        LOOP EXIT WHEN i IS NULL;
          CALCULER_SANS_DEPS(deps(TBL_NAME)(i), PARAMS);
          i := deps(TBL_NAME).next(i);
        END LOOP;
      END IF;
    END IF;

    CALCULER_SANS_DEPS(TBL_NAME, PARAMS);

    IF WITH_SUCS THEN
      deps := GET_SUCS;
      IF deps.exists(TBL_NAME) THEN
        i := deps(TBL_NAME).FIRST;
        LOOP EXIT WHEN i IS NULL;
          CALCULER_SANS_DEPS(deps(TBL_NAME)(i), PARAMS);
          i := deps(TBL_NAME).next(i);
        END LOOP;
      END IF;
    END IF;
  END;



  PROCEDURE CALCULER_DEMANDES IS
  BEGIN
    FOR d IN (
      WITH t AS (
        SELECT
          tbl_name,
          max(o) o
        FROM
          (SELECT    CONNECT_BY_ROOT(tbl_name) tbl_name, level + 1 o
          FROM       tbl_deps
          CONNECT BY PRIOR tbl_dep_name = tbl_name
          ORDER BY   LEVEL DESC) t
        GROUP BY
          tbl_name
      )
      SELECT
        d.*,
        COALESCE( t.o, 1) o
      FROM
        tbl_dems d
        LEFT JOIN t ON t.tbl_name = d.tbl_name
      ORDER BY
        o, id
    )
    LOOP

      CALCULER_SANS_DEPS( d.tbl_name, make_params(d.c1, d.v1, d.c2, d.v2, d.c3, d.v3, d.c4, d.v4, d.c5, d.v5) );
      DELETE FROM tbl_dems WHERE id = d.id;
    END LOOP;
  END;





  -- AUTOMATIC GENERATION --



  -- END OF AUTOMATIC GENERATION --

END UNICAEN_TBL;
/


CREATE OR REPLACE PACKAGE "OSE"."OSE_FORMULE" AS 

  PACKAGE_SUJET VARCHAR2(80) DEFAULT 'OSE_FORMULE';

  TYPE t_intervenant IS RECORD (
    structure_id                   NUMERIC,
    annee_id                       NUMERIC,
    heures_decharge                FLOAT DEFAULT 0,
    heures_service_statutaire      FLOAT DEFAULT 0,
    heures_service_modifie         FLOAT DEFAULT 0,
    depassement_service_du_sans_hc FLOAT DEFAULT 0
  );
  
  TYPE t_type_etat_vh IS RECORD (
    type_volume_horaire_id    NUMERIC,
    etat_volume_horaire_id    NUMERIC
  );
  TYPE t_lst_type_etat_vh   IS TABLE OF t_type_etat_vh INDEX BY PLS_INTEGER;
  
  TYPE t_service_ref IS RECORD (
    id                        NUMERIC,
    structure_id              NUMERIC
  );
  TYPE t_lst_service_ref      IS TABLE OF t_service_ref INDEX BY PLS_INTEGER;
  
  TYPE t_service IS RECORD (
    id                        NUMERIC,
    taux_fi                   FLOAT   DEFAULT 1,
    taux_fa                   FLOAT   DEFAULT 0,
    taux_fc                   FLOAT   DEFAULT 0,
    ponderation_service_du    FLOAT   DEFAULT 1,
    ponderation_service_compl FLOAT   DEFAULT 1,
    structure_aff_id          NUMERIC,
    structure_ens_id          NUMERIC
  );
  TYPE t_lst_service          IS TABLE OF t_service INDEX BY PLS_INTEGER;
  
  TYPE t_volume_horaire_ref IS RECORD (
    id                        NUMERIC,
    service_referentiel_id    NUMERIC,
    type_volume_horaire_id    NUMERIC,
    etat_volume_horaire_id    NUMERIC,
    etat_volume_horaire_ordre NUMERIC,
    heures                    FLOAT   DEFAULT 0
  );
  TYPE t_lst_volume_horaire_ref   IS TABLE OF t_volume_horaire_ref INDEX BY PLS_INTEGER;
  
  TYPE t_volume_horaire IS RECORD (
    id                        NUMERIC,
    service_id                NUMERIC,
    type_volume_horaire_id    NUMERIC,
    etat_volume_horaire_id    NUMERIC,
    etat_volume_horaire_ordre NUMERIC,
    heures                    FLOAT   DEFAULT 0,
    taux_service_du           FLOAT   DEFAULT 1,
    taux_service_compl        FLOAT   DEFAULT 1
  );
  TYPE t_lst_volume_horaire   IS TABLE OF t_volume_horaire INDEX BY PLS_INTEGER;



  TYPE t_resultat_hetd IS RECORD (
    service_fi                FLOAT DEFAULT 0,
    service_fa                FLOAT DEFAULT 0,
    service_fc                FLOAT DEFAULT 0,
    heures_compl_fi           FLOAT DEFAULT 0,
    heures_compl_fa           FLOAT DEFAULT 0,
    heures_compl_fc           FLOAT DEFAULT 0,
    heures_compl_fc_majorees  FLOAT DEFAULT 0
  );
  TYPE t_lst_resultat_hetd   IS TABLE OF t_resultat_hetd INDEX BY PLS_INTEGER;

  TYPE t_resultat_hetd_ref IS RECORD (
    service_referentiel       FLOAT DEFAULT 0,
    heures_compl_referentiel  FLOAT DEFAULT 0
  );
  TYPE t_lst_resultat_hetd_ref   IS TABLE OF t_resultat_hetd_ref INDEX BY PLS_INTEGER;

  TYPE t_resultat IS RECORD (
    intervenant_id            NUMERIC,
    type_volume_horaire_id    NUMERIC,
    etat_volume_horaire_id    NUMERIC,
    service_du                FLOAT DEFAULT 0,
    solde                     FLOAT DEFAULT 0,
    sous_service              FLOAT DEFAULT 0,
    heures_compl              FLOAT DEFAULT 0,
    volume_horaire            t_lst_resultat_hetd,
    volume_horaire_ref        t_lst_resultat_hetd_ref
  );

  d_intervenant         t_intervenant;
  d_type_etat_vh        t_lst_type_etat_vh;
  d_service_ref         t_lst_service_ref;
  d_service             t_lst_service;
  d_volume_horaire_ref  t_lst_volume_horaire_ref;
  d_volume_horaire      t_lst_volume_horaire;
  d_resultat            t_resultat;

  FUNCTION  GET_DATE_OBS RETURN DATE;
  FUNCTION  SET_DATE_OBS( DATE_OBS DATE DEFAULT NULL ) RETURN DATE;

  PROCEDURE SET_DEBUG_LEVEL( DEBUG_LEVEL NUMERIC );
  FUNCTION GET_DEBUG_LEVEL RETURN NUMERIC;

  FUNCTION GET_TAUX_HORAIRE_HETD( DATE_OBS DATE DEFAULT NULL ) RETURN FLOAT;

  PROCEDURE CALCULER( INTERVENANT_ID NUMERIC );
  PROCEDURE CALCULER_TOUT( ANNEE_ID NUMERIC DEFAULT NULL );        -- mise à jour de TOUTES les données ! ! ! !
  PROCEDURE CALCULER_TBL( PARAMS UNICAEN_TBL.T_PARAMS );
  
  PROCEDURE SET_INTERVENANT(INTERVENANT_ID NUMERIC DEFAULT NULL);
  FUNCTION GET_INTERVENANT RETURN NUMERIC;
  FUNCTION MATCH_INTERVENANT(INTERVENANT_ID NUMERIC DEFAULT NULL) RETURN NUMERIC;
END OSE_FORMULE;
/


CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_FORMULE" AS

  v_date_obs DATE;
  debug_level NUMERIC DEFAULT 0;
  d_all_volume_horaire_ref  t_lst_volume_horaire_ref;
  d_all_volume_horaire      t_lst_volume_horaire;
  arrondi NUMERIC DEFAULT 2;
  INTERVENANT_ID NUMERIC DEFAULT NULL;



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
      1 = OSE_DIVERS.COMPRISE_ENTRE( t.histo_creation, t.histo_destruction, DATE_OBS )
      AND t.histo_creation <= DATE_OBS
      AND rownum = 1
    ORDER BY
      histo_creation DESC;
    RETURN taux_hetd;
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
        1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction, ose_formule.get_date_obs )
        AND i.annee_id = a_id

      UNION

      SELECT DISTINCT
        intervenant_id
      FROM
        service_referentiel sr
        JOIN intervenant i ON i.id = sr.intervenant_id
      WHERE
        1 = ose_divers.comprise_entre( sr.histo_creation, sr.histo_destruction, ose_formule.get_date_obs )
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
      WHERE    OSE_DIVERS.COMPRISE_ENTRE( evh.histo_creation, evh.histo_destruction ) = 1
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

    DELETE FROM FORMULE_RESULTAT_SERVICE_REF WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
    DELETE FROM FORMULE_RESULTAT_SERVICE WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
    DELETE FROM FORMULE_RESULTAT_VH_REF WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
    DELETE FROM FORMULE_RESULTAT_VH WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
    DELETE FROM FORMULE_RESULTAT WHERE TO_DELETE = 1 AND intervenant_id = CALCULER.INTERVENANT_ID;
  END;



  PROCEDURE CALCULER_TBL( PARAMS UNICAEN_TBL.T_PARAMS ) IS
  BEGIN
    IF params.exists('INTERVENANT_ID') THEN
      CALCULER( params('INTERVENANT_ID') );
    ELSIF params.exists('ANNEE_ID') THEN
      CALCULER_TOUT( params('ANNEE_ID') );
    ELSE
      CALCULER_TOUT;
    END IF;
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
/

CREATE OR REPLACE PACKAGE "OSE"."OSE_EVENT" AS 

  PROCEDURE ON_BEFORE_FORMULE_RES_DELETE( INTERVENANT_ID NUMERIC );

END OSE_EVENT;
/


CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_EVENT" AS

  PROCEDURE ON_BEFORE_FORMULE_RES_DELETE( INTERVENANT_ID NUMERIC ) IS
  BEGIN

    -- recherche des services à payer prêts à être supprimés pour cet intervenant et check
    FOR sap IN (
      SELECT
        frs.id
      FROM 
        FORMULE_RESULTAT_SERVICE frs
        JOIN FORMULE_RESULTAT fr ON fr.id = frs.formule_resultat_id AND fr.intervenant_id = ON_BEFORE_FORMULE_RES_DELETE.intervenant_id
        JOIN TYPE_VOLUME_HORAIRE tvh ON tvh.id = fr.type_volume_horaire_id AND tvh.code = 'REALISE'
        JOIN ETAT_VOLUME_HORAIRE evh ON evh.id = fr.etat_volume_horaire_id AND evh.code = 'valide'
      WHERE
        frs.TO_DELETE = 1
    )
    LOOP
      OSE_PAIEMENT.CHECK_BAD_PAIEMENTS( sap.id );
    END LOOP;

    FOR sap IN (
      SELECT
        frs.id
      FROM 
        FORMULE_RESULTAT_SERVICE_REF frs
        JOIN FORMULE_RESULTAT fr ON fr.id = frs.formule_resultat_id AND fr.intervenant_id = ON_BEFORE_FORMULE_RES_DELETE.intervenant_id
        JOIN TYPE_VOLUME_HORAIRE tvh ON tvh.id = fr.type_volume_horaire_id AND tvh.code = 'REALISE'
        JOIN ETAT_VOLUME_HORAIRE evh ON evh.id = fr.etat_volume_horaire_id AND evh.code = 'valide'
      WHERE
        frs.TO_DELETE = 1
    )
    LOOP
      OSE_PAIEMENT.CHECK_BAD_PAIEMENTS( null, sap.id );
    END LOOP;

    DELETE FROM MISE_EN_PAIEMENT WHERE histo_destruction IS NOT NULL AND
      formule_res_service_id IN (
        SELECT frs.id 
        FROM 
          formule_resultat_service frs 
          JOIN formule_resultat fr ON fr.id = frs.formule_resultat_id
        WHERE 
          frs.to_delete = 1 
          AND fr.intervenant_id = ON_BEFORE_FORMULE_RES_DELETE.INTERVENANT_ID
      );

    DELETE FROM MISE_EN_PAIEMENT WHERE histo_destruction IS NOT NULL AND
      formule_res_service_ref_id IN (
        SELECT frsr.id 
        FROM 
          formule_resultat_service_ref frsr
          JOIN formule_resultat fr ON fr.id = frsr.formule_resultat_id
        WHERE 
          frsr.to_delete = 1 
          AND fr.intervenant_id = ON_BEFORE_FORMULE_RES_DELETE.INTERVENANT_ID
      );
  END;

END OSE_EVENT;
/


CREATE OR REPLACE PACKAGE "OSE"."OSE_PAIEMENT" AS 

  PROCEDURE CHECK_BAD_PAIEMENTS( FORMULE_RES_SERVICE_ID NUMERIC DEFAULT NULL, FORMULE_RES_SERVICE_REF_ID NUMERIC DEFAULT NULL );

END OSE_PAIEMENT;
/

CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_PAIEMENT" AS
  
  PROCEDURE CHECK_BAD_PAIEMENTS( FORMULE_RES_SERVICE_ID NUMERIC DEFAULT NULL, FORMULE_RES_SERVICE_REF_ID NUMERIC DEFAULT NULL ) IS
    cc NUMERIC;
  BEGIN
    SELECT count(*) INTO cc 
    FROM mise_en_paiement mep 
    WHERE
      mep.histo_destruction IS NULL
      AND mep.formule_res_service_id = NVL( CHECK_BAD_PAIEMENTS.FORMULE_RES_SERVICE_ID, mep.formule_res_service_id )
      AND mep.formule_res_service_ref_id = NVL( CHECK_BAD_PAIEMENTS.FORMULE_RES_SERVICE_REF_ID, mep.formule_res_service_ref_id )
  ;

    IF (cc > 0) THEN
      raise_application_error(-20101, 'Il est impossible d''effectuer cette action : des demandes de mise en paiement ont été saisies et ne peuvent pas être modifiées');
    ELSE
      DELETE FROM mise_en_paiement WHERE 
        histo_destruction IS NOT NULL
        AND formule_res_service_id = NVL( CHECK_BAD_PAIEMENTS.FORMULE_RES_SERVICE_ID, formule_res_service_id )
        AND formule_res_service_ref_id = NVL( CHECK_BAD_PAIEMENTS.FORMULE_RES_SERVICE_REF_ID, formule_res_service_ref_id )
      ;
    END IF;
  END;

END OSE_PAIEMENT;
/

CREATE OR REPLACE PACKAGE "OSE"."OSE_SERVICE" AS 
  PROCEDURE controle_plafond_fc_maj( intervenant_id NUMERIC, type_volume_horaire_id NUMERIC );
END OSE_SERVICE;
/

CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_SERVICE" AS
  
  /**
   * Retourne true si le test passe, false sinon
   */
  FUNCTION test_plafond_fc_maj( intervenant_id NUMERIC, type_volume_horaire_id NUMERIC ) RETURN BOOLEAN IS
    heures_restantes FLOAT;
  BEGIN
    BEGIN
      SELECT
        pla.plafond - pla.heures INTO heures_restantes 
      FROM
        v_plafond_fc_maj pla
        JOIN etat_volume_horaire evh ON evh.code = 'saisi' AND evh.id = pla.etat_volume_horaire_id
      WHERE
            intervenant_id         = test_plafond_fc_maj.intervenant_id
        AND type_volume_horaire_id = test_plafond_fc_maj.type_volume_horaire_id;
        
      RETURN heures_restantes >= 0;
    EXCEPTION
      WHEN NO_DATA_FOUND THEN RETURN TRUE;
    END;
  END;



  /**
   * Contrôle du plafond FC D714-60
   */
  PROCEDURE controle_plafond_fc_maj( intervenant_id NUMERIC, type_volume_horaire_id NUMERIC ) IS
  BEGIN
    IF test_plafond_fc_maj(intervenant_id, type_volume_horaire_id) THEN
      
      /* On dit que le contrôle a été effectué !! */
      UPDATE volume_horaire 
      SET tem_plafond_fc_maj = 1 
      WHERE 
        type_volume_horaire_id = controle_plafond_fc_maj.type_volume_horaire_id
        AND service_id IN (SELECT s.id FROM service s WHERE s.intervenant_id = controle_plafond_fc_maj.intervenant_id);
      
    ELSE
      
      /* Suppression des volumes horaires induement créés */
      DELETE FROM volume_horaire 
      WHERE
        tem_plafond_fc_maj <> 1
        AND buff_pfm_heures IS NULL -- on ne détruit que les nouvellement créés
        AND type_volume_horaire_id = controle_plafond_fc_maj.type_volume_horaire_id
        AND service_id IN (SELECT ID FROM service WHERE intervenant_id = controle_plafond_fc_maj.intervenant_id);

      /* remise à l'état antérieur des volumes horaires induement modifiés */
      UPDATE volume_horaire SET
        heures                         = buff_pfm_heures,
        motif_non_paiement_id          = buff_pfm_motif_non_paiement_id,
        histo_modification             = buff_pfm_histo_modification,
        histo_modificateur_id          = buff_pfm_histo_modificateur_id,
        buff_pfm_heures                = NULL,
        buff_pfm_motif_non_paiement_id = NULL,
        buff_pfm_histo_modification    = NULL,
        buff_pfm_histo_modificateur_id = NULL,
        tem_plafond_fc_maj             = 1
      WHERE
        tem_plafond_fc_maj <> 1
        AND buff_pfm_heures IS NOT NULL -- on ne met à jour que les anciennes données
        AND type_volume_horaire_id = controle_plafond_fc_maj.type_volume_horaire_id
        AND service_id IN (SELECT ID FROM service WHERE intervenant_id = controle_plafond_fc_maj.intervenant_id);
        
      /* Purge de la liste des services devenus inutiles (le cas échéant) */
      DELETE FROM service WHERE
        intervenant_id = controle_plafond_fc_maj.intervenant_id
        AND NOT EXISTS(SELECT * FROM volume_horaire WHERE service_id = service.id);
    
      COMMIT; 
      /* Renvoi de l'exception */
      raise_application_error(-20101, ose_divers.get_msg('service-pladond-fc-maj-depasse'));

    END IF;
  END;

END OSE_SERVICE;
/


CREATE OR REPLACE PACKAGE "OSE"."OSE_WORKFLOW" AS 

  PACKAGE_SUJET VARCHAR2(80) DEFAULT 'OSE_WORKFLOW';

  PROCEDURE CALCULER( INTERVENANT_ID NUMERIC );
  PROCEDURE CALCULER_TOUT( ANNEE_ID NUMERIC DEFAULT NULL );
  PROCEDURE CALCULER_TBL( PARAMS UNICAEN_TBL.T_PARAMS );

  PROCEDURE DEP_CHECK( etape_suiv_id NUMERIC, etape_prec_id NUMERIC );

  PROCEDURE SET_INTERVENANT(INTERVENANT_ID NUMERIC DEFAULT NULL);
  FUNCTION GET_INTERVENANT RETURN NUMERIC;
  FUNCTION MATCH_INTERVENANT(INTERVENANT_ID NUMERIC DEFAULT NULL) RETURN NUMERIC;
END OSE_WORKFLOW;
/


CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_WORKFLOW" AS
  INTERVENANT_ID NUMERIC DEFAULT NULL;

  TYPE t_workflow IS TABLE OF tbl_workflow%rowtype INDEX BY PLS_INTEGER;

  TYPE t_dep IS TABLE OF wf_etape_dep%rowtype INDEX BY PLS_INTEGER;
  TYPE t_deps IS TABLE OF t_dep INDEX BY PLS_INTEGER;
  TYPE t_deps_bloquantes IS TABLE OF wf_dep_bloquante%rowtype INDEX BY PLS_INTEGER;

  -- propre au calcul courant ! !
  etapes          t_workflow;
  deps            t_deps;
  deps_initialized boolean default false;
  deps_bloquantes t_deps_bloquantes;
  deps_bloquantes_index PLS_INTEGER DEFAULT 1;




  FUNCTION ETAPE_FRANCHIE( etape tbl_workflow%rowtype, need_done boolean default false ) RETURN FLOAT IS
    res FLOAT DEFAULT 0;
  BEGIN
    IF etape.objectif = 0 THEN 
      IF need_done THEN RETURN 0; ELSE RETURN 1; END IF;
    END IF;
    
    IF etape.atteignable = 0 THEN RETURN 0; END IF;

    IF etape.objectif > 0 THEN
      res := etape.realisation / etape.objectif;
    END IF;

    IF res > 1 THEN 
      res := 1; 
    END IF;

    RETURN res;
  END;



  PROCEDURE POPULATE_ETAPES( INTERVENANT_ID NUMERIC ) IS
    i NUMERIC DEFAULT 0;
  BEGIN
    etapes.delete; -- initialisation

    FOR wie IN (
      SELECT
        wep.annee_id                                          annee_id,
        e.id                                                  etape_id,
        w.structure_id                                        structure_id,
        NVL(w.objectif,0)                                     objectif,
        CASE WHEN w.intervenant_id IS NULL THEN 0 ELSE 1 END  atteignable,
        NVL(w.realisation,0)                                  realisation,
        wep.etape_code                                        etape_code,
        ti.id                                                 type_intervenant_id,
        ti.code                                               type_intervenant_code
      FROM
        v_workflow_etape_pertinente wep
        JOIN wf_etape                 e ON e.code = wep.etape_code
        JOIN intervenant              i ON i.id = wep.intervenant_id
        JOIN statut_intervenant      si ON si.id = i.statut_id
        JOIN type_intervenant        ti ON ti.id = si.type_intervenant_id
        LEFT JOIN v_tbl_workflow      w ON w.intervenant_id = wep.intervenant_id AND w.etape_code = wep.etape_code
      WHERE
        wep.intervenant_id = POPULATE_ETAPES.INTERVENANT_ID
        AND (e.obligatoire = 1 OR w.intervenant_id IS NOT NULL)
      ORDER BY
        e.ordre
    ) LOOP
      etapes( i ).annee_id              := wie.annee_id;
      etapes( i ).intervenant_id        := intervenant_id;
      etapes( i ).etape_id              := wie.etape_id;
      etapes( i ).structure_id          := wie.structure_id;
      etapes( i ).atteignable           := wie.atteignable;
      etapes( i ).objectif              := wie.objectif;
      etapes( i ).realisation           := wie.realisation;
      etapes( i ).etape_code            := wie.etape_code;
      etapes( i ).type_intervenant_id   := wie.type_intervenant_id;
      etapes( i ).type_intervenant_code := wie.type_intervenant_code;
      i := i + 1;
    END LOOP;
  END;



  -- peuple l'arbre des dépendances entre étapes de workflow
  PROCEDURE POPULATE_DEPS( INTERVENANT_ID NUMERIC ) IS
    s PLS_INTEGER; -- index de l'étape suivante
    p PLS_INTEGER; -- index de l'étape précédente
  BEGIN
    IF deps_initialized THEN RETURN; END IF;

    FOR d IN (
      SELECT
        wed.*
      FROM
        wf_etape_dep wed
        JOIN intervenant i ON i.id = POPULATE_DEPS.INTERVENANT_ID
        JOIN statut_intervenant si ON si.id = i.statut_id
      WHERE
        active = 1
        AND wed.type_intervenant_id IS NULL OR wed.type_intervenant_id = si.type_intervenant_id
    ) LOOP
      deps(d.etape_suiv_id)(d.etape_prec_id) := d;
    END LOOP;

    deps_initialized := true;
  END;



  PROCEDURE ADD_DEP_BLOQUANTE( wf_etape_dep_id NUMERIC, tbl_workflow_id NUMERIC ) IS
  BEGIN
    deps_bloquantes_index := deps_bloquantes_index + 1;
    deps_bloquantes(deps_bloquantes_index).wf_etape_dep_id := wf_etape_dep_id;
    deps_bloquantes(deps_bloquantes_index).tbl_workflow_id := tbl_workflow_id;
  END;



  PROCEDURE CALCUL_ATTEIGNABLE( s PLS_INTEGER, d wf_etape_dep%rowtype ) IS
    count_tested PLS_INTEGER DEFAULT 0;
    count_na     PLS_INTEGER DEFAULT 0;
    p PLS_INTEGER; -- index de l'étape précédente
  BEGIN

    p := etapes.FIRST;
    LOOP EXIT WHEN p IS NULL;
      IF etapes(p).etape_id = d.etape_prec_id THEN
        -- on restreint en fonction du périmètre visé :
        --  - si la dépendance n'est pas locale alors on teste
        --  - si les structures aussi bien de l'étape testée que de l'étape dépendante sont nulles alors on teste aussi car elles sont "universelles"
        --  - si les structures sont équivalentes alors on teste, sinon elles ne sont pas dans le périmètre local
        IF
          (d.locale = 0) 
          OR etapes(s).structure_id IS NULL 
          OR etapes(p).structure_id IS NULL 
          OR etapes(s).structure_id = etapes(p).structure_id 
        THEN
          count_tested := count_tested + 1;

          -- on teste le type de franchissement désiré et si ce n'est pas bon alors on déclare l'étape courante non atteignable

          --  - idem si on a besoin d'une dépendance partiellement franchie est qu'elle ne l'est pas
          IF d.partielle = 1 THEN
            IF ETAPE_FRANCHIE(etapes(p), d.obligatoire=1) = 0 THEN -- si le franchissement est totalement inexistant
              count_na := count_na + 1;
            END IF;
          --  - si on a besoin d'une dépendance complètement franchie est qu'elle ne l'est pas alors ce n'est pas atteignable  
          ELSE
            IF ETAPE_FRANCHIE(etapes(p), d.obligatoire=1) < 1 THEN
              count_na := count_na + 1;
            END IF;
          END IF;
        END IF;

      END IF;
      p := etapes.next(p);
    END LOOP;

    -- on applique le résultat uniquement si des étapes dépendantes ont été trouvées
    IF count_tested > 0 THEN
      
      -- si les étapes dépendantes ont été intégralement franchies
      IF d.integrale = 1 THEN
        -- si l'intégralité des étapes est atteignable = NON si au moins une ne l'est pas
        IF count_na > 0 THEN
          etapes(s).atteignable := 0;
          ADD_DEP_BLOQUANTE( d.id, s );
        END IF;

      -- sinon...
      ELSE
        -- si au moins une étape est atteignable = NON si toutes ne sont pas atteignables
        IF count_tested = count_na THEN 
          etapes(s).atteignable := 0;
          ADD_DEP_BLOQUANTE( d.id, s );
        END IF;
      END IF;
    END IF;
  END;



  -- calcule si les étapes sont atteignables ou non
  PROCEDURE CALCUL_ATTEIGNABLES IS
    e PLS_INTEGER; -- index de l'étape courante
    d PLS_INTEGER; -- ID de l'étape précédante
  BEGIN
    deps_bloquantes.delete;
    e := etapes.FIRST;
    LOOP EXIT WHEN e IS NULL;
      IF deps.exists(etapes(e).etape_id) THEN -- s'il n'y a aucune dépendance alors pas de test!!
        d := deps(etapes(e).etape_id).FIRST;
        LOOP EXIT WHEN d IS NULL;

          CALCUL_ATTEIGNABLE(e, deps(etapes(e).etape_id)(d));

          d := deps(etapes(e).etape_id).next(d);
        END LOOP;
      END IF;
      e := etapes.next(e);
    END LOOP;
  END;



  FUNCTION ENREGISTRER_ETAPE( e tbl_workflow%rowtype ) RETURN NUMERIC IS
    n_etape_id NUMERIC;
  BEGIN

    MERGE INTO tbl_workflow w USING dual ON (

          w.intervenant_id      = e.intervenant_id
      AND w.etape_id            = e.etape_id
      AND NVL(w.structure_id,0) = NVL(e.structure_id,0)

    ) WHEN MATCHED THEN UPDATE SET

      atteignable                  = e.atteignable,
      objectif                     = e.objectif,
      realisation                  = e.realisation,
      etape_code                   = e.etape_code,
      type_intervenant_id          = e.type_intervenant_id,
      type_intervenant_code        = e.type_intervenant_code,
      to_delete                    = 0

    WHEN NOT MATCHED THEN INSERT (

      id,
      annee_id,
      intervenant_id,
      etape_id,
      structure_id,
      atteignable,
      objectif,
      realisation,
      etape_code,
      type_intervenant_id,
      type_intervenant_code,
      to_delete

    ) VALUES (

      TBL_WORKFLOW_ID_SEQ.NEXTVAL,
      e.annee_id,
      e.intervenant_id,
      e.etape_id,
      e.structure_id,
      e.atteignable,
      e.objectif,
      e.realisation,
      e.etape_code,
      e.type_intervenant_id,
      e.type_intervenant_code,
      0

    );

    SELECT w.id INTO n_etape_id FROM tbl_workflow w WHERE
      w.intervenant_id          = e.intervenant_id
      AND w.etape_id            = e.etape_id
      AND NVL(w.structure_id,0) = NVL(e.structure_id,0)
    ;

    RETURN n_etape_id;
  END;



  PROCEDURE ENREGISTRER_DEP_BLOQUANTE( db wf_dep_bloquante%rowtype ) IS
  BEGIN
    MERGE INTO wf_dep_bloquante wdb USING dual ON (

          wdb.wf_etape_dep_id   = db.wf_etape_dep_id
      AND wdb.tbl_workflow_id   = db.tbl_workflow_id

    ) WHEN MATCHED THEN UPDATE SET

      to_delete                 = 0

    WHEN NOT MATCHED THEN INSERT (

      id,
      wf_etape_dep_id,
      tbl_workflow_id,
      to_delete

    ) VALUES (

      WF_DEP_BLOQUANTE_ID_SEQ.NEXTVAL,
      db.wf_etape_dep_id,
      db.tbl_workflow_id,
      0

    );  
  END;



  PROCEDURE ENREGISTRER( INTERVENANT_ID NUMERIC ) IS
    i PLS_INTEGER;
  BEGIN

    UPDATE tbl_workflow SET to_delete = 1 WHERE intervenant_id = ENREGISTRER.INTERVENANT_ID;
    UPDATE wf_dep_bloquante SET to_delete = 1 WHERE tbl_workflow_id IN (SELECT id FROM tbl_workflow WHERE intervenant_id = ENREGISTRER.INTERVENANT_ID);

    i := etapes.FIRST;
    LOOP EXIT WHEN i IS NULL;
      etapes(i).id := ENREGISTRER_ETAPE( etapes(i) );
      i := etapes.NEXT(i);
    END LOOP;

    i := deps_bloquantes.FIRST;
    LOOP EXIT WHEN i IS NULL;
      deps_bloquantes(i).tbl_workflow_id := etapes(deps_bloquantes(i).tbl_workflow_id).id;
      ENREGISTRER_DEP_BLOQUANTE( deps_bloquantes(i) );
      i := deps_bloquantes.NEXT(i);
    END LOOP;

    DELETE FROM tbl_workflow WHERE TO_DELETE = 1 AND intervenant_id = ENREGISTRER.INTERVENANT_ID;
    DELETE FROM wf_dep_bloquante WHERE TO_DELETE = 1;
  END;



  PROCEDURE DEP_CHECK( etape_suiv_id NUMERIC, etape_prec_id NUMERIC ) IS
    eso NUMERIC;
    epo NUMERIC;
  BEGIN
    SELECT ordre INTO eso FROM wf_etape WHERE id = etape_suiv_id;
    SELECT ordre INTO epo FROM wf_etape WHERE id = etape_prec_id;

    IF eso < epo THEN
      raise_application_error(-20101, 'Une étape de Workflow ne peut dépendre d''une étape située en aval');
    END IF;
    IF eso = epo THEN
      raise_application_error(-20101, 'Une étape de Workflow ne peut dépendre d''elle-même');
    END IF;
  END;



  PROCEDURE DEBUG_CALCUL( INTERVENANT_ID NUMERIC ) IS
    i PLS_INTEGER;
    d PLS_INTEGER;
    dep_desc VARCHAR2(200);
  BEGIN
    ose_test.echo('');
    ose_test.echo('-- DEBUG WORKFLOW ETAPE INTERVENANT_ID='|| INTERVENANT_ID ||' --');
    i := etapes.FIRST;
    LOOP EXIT WHEN i IS NULL;
      ose_test.echo(
               'etape='       || RPAD( ose_test.get_wf_etape_by_id(etapes(i).etape_id).code, 30, ' ' )
          || ', structure='   || RPAD( NVL(ose_test.get_structure_by_id(etapes(i).structure_id).libelle_court,' '), 20, ' ' )
          || ', ' || CASE WHEN etapes(i).atteignable=1 THEN 'atteignable' ELSE 'na' END
          || ', objectif= ' || ROUND(etapes(i).objectif)
          || ', realisation= ' || ROUND(etapes(i).realisation)
      );

      d := deps_bloquantes.FIRST;
      LOOP EXIT WHEN d IS NULL;
        IF deps_bloquantes(d).tbl_workflow_id = i THEN

          SELECT
            we.desc_non_franchie INTO dep_desc
          FROM
            wf_etape_dep wed
            JOIN wf_etape we ON we.id = wed.etape_prec_id
          WHERE
            wed.id = deps_bloquantes(d).wf_etape_dep_id;

          ose_test.echo('    CAUSE =' || dep_desc);
        END IF;
        d := deps_bloquantes.NEXT(d);
      END LOOP;

      i := etapes.NEXT(i);
    END LOOP;
    ose_test.echo('');
  END;



  -- calcul du workflow pour un intervenant
  PROCEDURE CALCULER( INTERVENANT_ID NUMERIC ) IS
  BEGIN
    set_intervenant(intervenant_id);
    POPULATE_ETAPES( INTERVENANT_ID );
    POPULATE_DEPS( INTERVENANT_ID );
    CALCUL_ATTEIGNABLES;
    IF OSE_TEST.DEBUG_ENABLED THEN
      DEBUG_CALCUL( INTERVENANT_ID );
    END IF;
    ENREGISTRER( INTERVENANT_ID );
    set_intervenant();
  END;



  PROCEDURE CALCULER_TOUT( ANNEE_ID NUMERIC DEFAULT NULL ) IS
  BEGIN
    FOR mp IN (
      SELECT
        id intervenant_id
      FROM 
        intervenant i
      WHERE
        1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
        AND (CALCULER_TOUT.ANNEE_ID IS NULL OR i.annee_id = CALCULER_TOUT.ANNEE_ID)
    )
    LOOP
      CALCULER( mp.intervenant_id );
    END LOOP;
  END;
  
  
  
  PROCEDURE CALCULER_TBL( PARAMS UNICAEN_TBL.T_PARAMS ) IS
  BEGIN
    IF params.exists('INTERVENANT_ID') THEN
      CALCULER( params('INTERVENANT_ID') );
    ELSIF params.exists('ANNEE_ID') THEN
      CALCULER_TOUT( params('ANNEE_ID') );
    ELSE
      CALCULER_TOUT;
    END IF;
  END;  
  
  
  
  FUNCTION GET_INTERVENANT RETURN NUMERIC IS
  BEGIN
    RETURN OSE_WORKFLOW.INTERVENANT_ID;
  END;
  
  PROCEDURE SET_INTERVENANT( INTERVENANT_ID NUMERIC DEFAULT NULL) IS
  BEGIN
    IF SET_INTERVENANT.INTERVENANT_ID = -1 THEN
      OSE_WORKFLOW.INTERVENANT_ID := NULL;
    ELSE
      OSE_WORKFLOW.INTERVENANT_ID := SET_INTERVENANT.INTERVENANT_ID;
    END IF;
  END;
    
  FUNCTION MATCH_INTERVENANT(INTERVENANT_ID NUMERIC DEFAULT NULL) RETURN NUMERIC IS
  BEGIN
    IF OSE_WORKFLOW.INTERVENANT_ID IS NULL OR OSE_WORKFLOW.INTERVENANT_ID = MATCH_INTERVENANT.INTERVENANT_ID THEN
      RETURN 1;
    ELSE
      RETURN 0;
    END IF;
  END;
END OSE_WORKFLOW;
/



CREATE OR REPLACE FORCE VIEW "OSE"."V_CHARGENS_PRECALCUL_HEURES" 
 ( "ANNEE_ID", "NOEUD_ID", "SCENARIO_ID", "TYPE_HEURES_ID", "TYPE_INTERVENTION_ID", "ELEMENT_PEDAGOGIQUE_ID", "ETAPE_ID", "ETAPE_ENS_ID", "STRUCTURE_ID", "OUVERTURE", "DEDOUBLEMENT", "ASSIDUITE", "EFFECTIF", "HEURES_ENS", "GROUPES", "HEURES", "HETD"
  )  AS 
  WITH t AS (
SELECT
  n.annee_id          annee_id,
  n.id                noeud_id,
  sn.scenario_id      scenario_id,
  sne.type_heures_id  type_heures_id,
  ti.id               type_intervention_id,

  ep.id               element_pedagogique_id,
  ep.etape_id         etape_id,
  sne.etape_id        etape_ens_id,
  ep.structure_id     structure_id,
  
  vhe.heures          heures,
  vhe.heures * ti.taux_hetd_service hetd,
  
  GREATEST(COALESCE(sns.ouverture, 1),1)                      ouverture,
  GREATEST(COALESCE(sns.dedoublement, snsetp.dedoublement, csdd.dedoublement,1),1) dedoublement,
  COALESCE(sns.assiduite,1)                                   assiduite,
  sne.effectif*COALESCE(sns.assiduite,1)                      effectif,

  SUM(sne.effectif*COALESCE(sns.assiduite,1)) OVER (PARTITION BY n.id, sn.scenario_id, ti.id) t_effectif

FROM
            scenario_noeud_effectif    sne
            JOIN etape                        e ON e.id = sne.etape_id
                                          AND e.histo_destruction IS NULL
       
       JOIN scenario_noeud              sn ON sn.id = sne.scenario_noeud_id
                                          AND sn.histo_destruction IS NULL
       
       JOIN noeud                        n ON n.id = sn.noeud_id
                                          AND n.histo_destruction IS NULL
                                          
       JOIN element_pedagogique         ep ON ep.id = n.element_pedagogique_id
       JOIN volume_horaire_ens         vhe ON vhe.element_pedagogique_id = ep.id
                                          AND vhe.histo_destruction IS NULL 
                                          AND vhe.heures > 0

       JOIN type_intervention           ti ON ti.id = vhe.type_intervention_id

  LEFT JOIN noeud                     netp ON netp.etape_id = e.id
                                          AND netp.histo_destruction IS NULL
                                          
  LEFT JOIN scenario_noeud           snetp ON snetp.scenario_id = sn.scenario_id
                                          AND snetp.noeud_id = netp.id
                                          AND snetp.histo_destruction IS NULL
                                          
  LEFT JOIN scenario_noeud_seuil    snsetp ON snsetp.scenario_noeud_id = snetp.id
                                          AND snsetp.type_intervention_id = ti.id

  LEFT JOIN etape                      eep ON eep.id = COALESCE(n.etape_id, ep.etape_id)
       JOIN type_formation              tf ON tf.id = eep.type_formation_id
  LEFT JOIN tbl_chargens_seuils_def   csdd ON csdd.annee_id = n.annee_id
                                          AND csdd.scenario_id = sn.scenario_id
                                          AND csdd.type_intervention_id = ti.id
                                          AND csdd.groupe_type_formation_id = tf.groupe_id

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
  t;

CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_AGREMENT" 
 ( "ANNEE_ID", "TYPE_AGREMENT_ID", "INTERVENANT_ID", "STRUCTURE_ID", "OBLIGATOIRE", "AGREMENT_ID"
  )  AS 
  WITH i_s AS (
  SELECT DISTINCT
    fr.intervenant_id,
    ep.structure_id
  FROM
    formule_resultat fr
    JOIN type_volume_horaire  tvh ON tvh.code = 'PREVU' AND tvh.id = fr.type_volume_horaire_id
    JOIN etat_volume_horaire  evh ON evh.code = 'valide' AND evh.id = fr.etat_volume_horaire_id

    JOIN formule_resultat_service frs ON frs.formule_resultat_id = fr.id
    JOIN service s ON s.id = frs.service_id
    JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
  WHERE
    1 = UNICAEN_TBL.MATCH_PARAM('INTERVENANT_ID', fr.intervenant_id )
    AND frs.total > 0
)
SELECT
  i.annee_id              annee_id,
  tas.type_agrement_id    type_agrement_id,
  i.id                    intervenant_id,
  null                    structure_id,
  tas.obligatoire         obligatoire,
  a.id                    agrement_id
FROM
  type_agrement                  ta
  JOIN type_agrement_statut      tas ON tas.type_agrement_id = ta.id
                                    AND 1 = ose_divers.comprise_entre( tas.histo_creation, tas.histo_destruction )
                               
  JOIN intervenant                 i ON 
                                    1 = UNICAEN_TBL.MATCH_PARAM('INTERVENANT_ID', i.id )
                                    AND 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction )
                                    AND (tas.premier_recrutement IS NULL OR NVL(i.premier_recrutement,0) = tas.premier_recrutement)
                                    AND i.statut_id = tas.statut_intervenant_id
                            
  LEFT JOIN agrement               a ON a.type_agrement_id = ta.id 
                                    AND a.intervenant_id = i.id
                                    AND 1 = ose_divers.comprise_entre( a.histo_creation, a.histo_destruction )
WHERE
  ta.code = 'CONSEIL_ACADEMIQUE'

UNION ALL

SELECT
  i.annee_id              annee_id,
  tas.type_agrement_id    type_agrement_id,
  i.id                    intervenant_id,
  i_s.structure_id        structure_id,
  tas.obligatoire         obligatoire,
  a.id                    agrement_id
FROM
  type_agrement                   ta
  JOIN type_agrement_statut      tas ON tas.type_agrement_id = ta.id
                                    AND 1 = ose_divers.comprise_entre( tas.histo_creation, tas.histo_destruction )

  JOIN intervenant                 i ON 
                                    1 = UNICAEN_TBL.MATCH_PARAM('INTERVENANT_ID', i.id )
                                    AND 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction )
                                    AND (tas.premier_recrutement IS NULL OR NVL(i.premier_recrutement,0) = tas.premier_recrutement)
                                    AND i.statut_id = tas.statut_intervenant_id

  JOIN                           i_s ON i_s.intervenant_id = i.id

  LEFT JOIN agrement               a ON a.type_agrement_id = ta.id 
                                    AND a.intervenant_id = i.id
                                    AND a.structure_id = i_s.structure_id
                                    AND 1 = ose_divers.comprise_entre( a.histo_creation, a.histo_destruction )
WHERE
  ta.code = 'CONSEIL_RESTREINT';
  
  
  CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_CHARGENS_SEUILS_DEF" 
 ( "ANNEE_ID", "SCENARIO_ID", "STRUCTURE_ID", "GROUPE_TYPE_FORMATION_ID", "TYPE_INTERVENTION_ID", "DEDOUBLEMENT"
  )  AS 
  SELECT
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
    AND sc1.scenario_id              = sta.scenario_id
    AND sc1.type_intervention_id     = sta.type_intervention_id
    AND sc1.structure_id             = s.structure_id
    AND sc1.groupe_type_formation_id = gtf.groupe_type_formation_id
    
  LEFT JOIN seuil_charge sc2 ON 
    sc2.histo_destruction            IS NULL
    AND sc2.scenario_id              = sta.scenario_id
    AND sc2.type_intervention_id     = sta.type_intervention_id
    AND sc2.structure_id             = s.structure_id
    AND sc2.groupe_type_formation_id IS NULL
    
  LEFT JOIN seuil_charge sc3 ON 
    sc3.histo_destruction            IS NULL
    AND sc3.scenario_id              = sta.scenario_id
    AND sc3.type_intervention_id     = sta.type_intervention_id
    AND sc3.structure_id             IS NULL
    AND sc3.groupe_type_formation_id = gtf.groupe_type_formation_id
    
  LEFT JOIN seuil_charge sc4 ON 
    sc4.histo_destruction            IS NULL
    AND sc4.scenario_id              = sta.scenario_id
    AND sc4.type_intervention_id     = sta.type_intervention_id
    AND sc4.structure_id             IS NULL
    AND sc4.groupe_type_formation_id IS NULL
WHERE
  COALESCE(sc1.dedoublement, sc2.dedoublement, sc3.dedoublement, sc4.dedoublement, 1) <> 1;


CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_CLOTURE_REALISE" 
 ( "ANNEE_ID", "INTERVENANT_ID", "PEUT_CLOTURER_SAISIE", "CLOTURE"
  )  AS 
  WITH t AS (
  SELECT
    i.annee_id              annee_id,
    i.id                    intervenant_id,
    si.peut_cloturer_saisie peut_cloturer_saisie,
    CASE WHEN v.id IS NULL THEN 0 ELSE 1 END cloture
  FROM
              intervenant         i
         JOIN statut_intervenant si ON si.id = i.statut_id
         JOIN type_validation    tv ON tv.code = 'CLOTURE_REALISE'
         
    LEFT JOIN validation          v ON v.intervenant_id = i.id
                                   AND v.type_validation_id = tv.id
                                   AND 1 = ose_divers.comprise_entre( v.histo_creation, v.histo_destruction )

  WHERE
    1 = UNICAEN_TBL.MATCH_PARAM('INTERVENANT_ID', i.id )
    AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
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
  peut_cloturer_saisie;

CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_CONTRAT" 
 ( "ANNEE_ID", "INTERVENANT_ID", "PEUT_AVOIR_CONTRAT", "STRUCTURE_ID", "NBVH", "EDITE", "SIGNE"
  )  AS 
  WITH t AS (
  SELECT 
    i.annee_id                                                                annee_id,
    i.id                                                                      intervenant_id,
    si.peut_avoir_contrat                                                     peut_avoir_contrat,
    NVL(ep.structure_id, i.structure_id)                                      structure_id,
    CASE WHEN evh.code IN ('contrat-edite','contrat-signe') THEN 1 ELSE 0 END edite,
    CASE WHEN evh.code IN ('contrat-signe')                 THEN 1 ELSE 0 END signe
  FROM
              intervenant                 i
              
         JOIN statut_intervenant         si ON si.id = i.statut_id
         
         JOIN service                     s ON s.intervenant_id = i.id
                                           AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
         
         JOIN type_volume_horaire       tvh ON tvh.code = 'PREVU'
         
         JOIN volume_horaire             vh ON vh.service_id = s.id
                                           AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
                                           AND vh.heures <> 0
                                           AND vh.type_volume_horaire_id = tvh.id
    
         JOIN v_volume_horaire_etat     vhe ON vhe.volume_horaire_id = vh.id
         
         JOIN etat_volume_horaire       evh ON evh.id = vhe.etat_volume_horaire_id
                                           AND evh.code IN ('valide', 'contrat-edite', 'contrat-signe')
  
         JOIN element_pedagogique        ep ON ep.id = s.element_pedagogique_id
    
  WHERE
    1 = UNICAEN_TBL.MATCH_PARAM('INTERVENANT_ID', i.id )
    AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
    AND NOT (si.peut_avoir_contrat = 0 AND evh.code = 'valide')
    
  UNION ALL
  
  SELECT 
    i.annee_id                                                                annee_id,
    i.id                                                                      intervenant_id,
    si.peut_avoir_contrat                                                     peut_avoir_contrat,
    s.structure_id                                                            structure_id,
    CASE WHEN evh.code IN ('contrat-edite','contrat-signe') THEN 1 ELSE 0 END edite,
    CASE WHEN evh.code IN ('contrat-signe')                 THEN 1 ELSE 0 END signe
  FROM
              intervenant                 i
              
         JOIN statut_intervenant         si ON si.id = i.statut_id
         
         JOIN service_referentiel         s ON s.intervenant_id = i.id
                                           AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
         
         JOIN type_volume_horaire       tvh ON tvh.code = 'PREVU'
         
         JOIN volume_horaire_ref         vh ON vh.service_referentiel_id = s.id
                                           AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
                                           AND vh.heures <> 0
                                           AND vh.type_volume_horaire_id = tvh.id
    
         JOIN v_volume_horaire_ref_etat vhe ON vhe.volume_horaire_ref_id = vh.id
         
         JOIN etat_volume_horaire       evh ON evh.id = vhe.etat_volume_horaire_id
                                           AND evh.code IN ('valide', 'contrat-edite', 'contrat-signe')
  
  WHERE
    1 = UNICAEN_TBL.MATCH_PARAM('INTERVENANT_ID', i.id )
    AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
    AND NOT (si.peut_avoir_contrat = 0 AND evh.code = 'valide')
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
  structure_id;

CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_DMEP_LIQUIDATION" 
 ( "ANNEE_ID", "TYPE_RESSOURCE_ID", "STRUCTURE_ID", "HEURES"
  )  AS 
  SELECT
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
    1 = ose_divers.comprise_entre( mep.histo_creation, mep.histo_destruction )
    
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
    1 = ose_divers.comprise_entre( mep.histo_creation, mep.histo_destruction )

) t1
GROUP BY
  annee_id, type_ressource_id, structure_id;

CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_DOSSIER" 
 ( "ANNEE_ID", "INTERVENANT_ID", "PEUT_SAISIR_DOSSIER", "DOSSIER_ID", "VALIDATION_ID"
  )  AS 
  SELECT
  i.annee_id,
  i.id intervenant_id,
  si.peut_saisir_dossier,
  d.id dossier_id,
  v.id validation_id
FROM
            intervenant         i
       JOIN statut_intervenant si ON si.id = i.statut_id
  LEFT JOIN dossier             d ON d.intervenant_id = i.id
                              AND 1 = ose_divers.comprise_entre( d.histo_creation, d.histo_destruction )
  
       JOIN type_validation tv ON tv.code = 'DONNEES_PERSO_PAR_COMP'
  LEFT JOIN validation       v ON v.intervenant_id = i.id
                              AND v.type_validation_id = tv.id
                              AND 1 = ose_divers.comprise_entre( v.histo_creation, v.histo_destruction )
WHERE
  1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction );

CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_PAIEMENT" 
 ( "ANNEE_ID", "FORMULE_RES_SERVICE_ID", "FORMULE_RES_SERVICE_REF_ID", "INTERVENANT_ID", "STRUCTURE_ID", "MISE_EN_PAIEMENT_ID", "PERIODE_PAIEMENT_ID", "HEURES_A_PAYER", "HEURES_A_PAYER_POND", "HEURES_DEMANDEES", "HEURES_PAYEES"
  )  AS 
  SELECT
  i.annee_id                                  annee_id,
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
       JOIN type_volume_horaire             tvh ON tvh.code = 'REALISE'
       JOIN etat_volume_horaire             evh ON evh.code = 'valide'
       JOIN formule_resultat                 fr ON 1 = UNICAEN_TBL.MATCH_PARAM('INTERVENANT_ID', fr.intervenant_id )
                                               AND fr.id = frs.formule_resultat_id
                                               AND fr.type_volume_horaire_id = tvh.id
                                               AND fr.etat_volume_horaire_id = evh.id

       JOIN intervenant                       i ON i.id = fr.intervenant_id  
       JOIN service                           s ON s.id = frs.service_id
  LEFT JOIN element_pedagogique              ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN mise_en_paiement                mep ON mep.formule_res_service_id = frs.id
                                               AND 1 = ose_divers.comprise_entre( mep.histo_creation, mep.histo_destruction )

UNION ALL

SELECT
  i.annee_id                                  annee_id,
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
       JOIN type_volume_horaire             tvh ON tvh.code = 'REALISE'
       JOIN etat_volume_horaire             evh ON evh.code = 'valide'
       JOIN formule_resultat                 fr ON 1 = UNICAEN_TBL.MATCH_PARAM('INTERVENANT_ID', fr.intervenant_id )
                                               AND fr.id = frs.formule_resultat_id
                                               AND fr.type_volume_horaire_id = tvh.id
                                               AND fr.etat_volume_horaire_id = evh.id

       JOIN intervenant                       i ON i.id = fr.intervenant_id  
       JOIN service_referentiel               s ON s.id = frs.service_referentiel_id
  LEFT JOIN mise_en_paiement                mep ON mep.formule_res_service_ref_id = frs.id
                                               AND 1 = ose_divers.comprise_entre( mep.histo_creation, mep.histo_destruction );

CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_PIECE_JOINTE" 
 ( "ANNEE_ID", "TYPE_PIECE_JOINTE_ID", "INTERVENANT_ID", "DEMANDEE", "FOURNIE", "VALIDEE", "HEURES_POUR_SEUIL"
  )  AS 
  WITH pjf AS (
  SELECT
    pjf.annee_id,
    pjf.type_piece_jointe_id,
    pjf.intervenant_id,
    COUNT(*) count,
    SUM(CASE WHEN validation_id IS NULL THEN 0 ELSE 1 END) validation,
    SUM(CASE WHEN fichier_id IS NULL THEN 0 ELSE 1 END) fichier
  FROM
    tbl_piece_jointe_fournie pjf
  WHERE
    1 = UNICAEN_TBL.MATCH_PARAM('INTERVENANT_ID', pjf.intervenant_id )
  GROUP BY
    pjf.annee_id,
    pjf.type_piece_jointe_id,
    pjf.intervenant_id
)
SELECT
  NVL( pjd.annee_id, pjf.annee_id ) annee_id,
  NVL( pjd.type_piece_jointe_id, pjf.type_piece_jointe_id ) type_piece_jointe_id,
  NVL( pjd.intervenant_id, pjf.intervenant_id ) intervenant_id,
  CASE WHEN pjd.intervenant_id IS NULL THEN 0 ELSE 1 END demandee,
  CASE WHEN pjf.fichier = pjf.count THEN 1 ELSE 0 END fournie,
  CASE WHEN pjf.validation = pjf.count THEN 1 ELSE 0 END validee,
  NVL(pjd.heures_pour_seuil,0) heures_pour_seuil
FROM
  tbl_piece_jointe_demande pjd
  FULL JOIN pjf ON pjf.type_piece_jointe_id = pjd.type_piece_jointe_id AND pjf.intervenant_id = pjd.intervenant_id;

CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_PIECE_JOINTE_DEMANDE" 
 ( "ANNEE_ID", "INTERVENANT_ID", "TYPE_PIECE_JOINTE_ID", "HEURES_POUR_SEUIL"
  )  AS 
  WITH i_h AS (
  SELECT
    s.intervenant_id,
    sum(vh.heures) heures
  FROM
         service               s
    JOIN type_volume_horaire tvh ON tvh.code = 'PREVU'
    JOIN volume_horaire       vh ON vh.service_id = s.id 
                                AND vh.type_volume_horaire_id = tvh.id
                                AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
  WHERE
    1 = UNICAEN_TBL.MATCH_PARAM('INTERVENANT_ID', s.intervenant_id )
    AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
    AND s.element_pedagogique_id IS NOT NULL -- Service sur l'établissement
    AND vh.motif_non_paiement_id IS NULL -- pas de motif de non paiement
  GROUP BY
    s.intervenant_id
)
SELECT DISTINCT
  i.annee_id                      annee_id,
  i.id                            intervenant_id,
  tpj.id                          type_piece_jointe_id,
  NVL(i_h.heures, 0)              heures_pour_seuil   
FROM
            intervenant                 i

  LEFT JOIN dossier                     d ON d.intervenant_id = i.id
                                         AND 1 = ose_divers.comprise_entre( d.histo_creation, d.histo_destruction )
                                        
       JOIN type_piece_jointe_statut tpjs ON tpjs.statut_intervenant_id = i.statut_id
                                         AND 1 = ose_divers.comprise_entre( tpjs.histo_creation, tpjs.histo_destruction )
                                         
       JOIN type_piece_jointe         tpj ON (tpj.id = tpjs.type_piece_jointe_id OR tpj.code='RIB')
                                         AND 1 = ose_divers.comprise_entre( tpj.histo_creation, tpj.histo_destruction )
                                         
  LEFT JOIN                           i_h ON i_h.intervenant_id = i.id
WHERE
  1=1
  
  -- Gestion de l'historique
  AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
  
  -- le nb d'heures doit être au moins égal au seuil
  AND 1 = CASE WHEN tpj.code = 'RIB' THEN 1 WHEN tpjs.seuil_hetd IS NOT NULL THEN

    CASE WHEN i_h.heures > tpjs.seuil_hetd THEN 1 ELSE 0 END
    
  ELSE 1 END
  
  -- En fonction du premier recrutement ou non
  AND (tpjs.premier_recrutement IS NULL OR NVL(i.premier_recrutement,0) = tpjs.premier_recrutement)

  -- Le RIB n'est demandé QUE s'il est différent!!  
  AND (
    tpj.code <> 'RIB' OR
    replace(i.bic, ' ', '') || '-' || replace(i.iban, ' ', '') <> d.rib
  );

CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_PIECE_JOINTE_FOURNIE" 
 ( "ANNEE_ID", "TYPE_PIECE_JOINTE_ID", "INTERVENANT_ID", "PIECE_JOINTE_ID", "VALIDATION_ID", "FICHIER_ID"
  )  AS 
  SELECT 
  i.annee_id,
  pj.type_piece_jointe_id,
  pj.intervenant_id,
  pj.id piece_jointe_id,
  v.id validation_id,
  f.id fichier_id
FROM
            piece_jointe          pj
       JOIN intervenant            i ON i.id = pj.intervenant_id
                                    AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
       
       JOIN piece_jointe_fichier pjf ON pjf.piece_jointe_id = pj.id
       JOIN fichier                f ON f.id = pjf.fichier_id
                                    AND 1 = ose_divers.comprise_entre( f.histo_creation, f.histo_destruction )
                                    
  LEFT JOIN validation             v ON v.id = pj.validation_id
                                    AND 1 = ose_divers.comprise_entre( v.histo_creation, v.histo_destruction )
WHERE
  1 = ose_divers.comprise_entre( pj.histo_creation, pj.histo_destruction );

CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_SERVICE" 
 ( "ANNEE_ID", "INTERVENANT_ID", "INTERVENANT_STRUCTURE_ID", "STRUCTURE_ID", "TYPE_INTERVENANT_ID", "TYPE_INTERVENANT_CODE", "PEUT_SAISIR_SERVICE", "ELEMENT_PEDAGOGIQUE_ID", "SERVICE_ID", "ELEMENT_PEDAGOGIQUE_PERIODE_ID", "ETAPE_ID", "TYPE_VOLUME_HORAIRE_ID", "TYPE_VOLUME_HORAIRE_CODE", "ELEMENT_PEDAGOGIQUE_HISTO", "ETAPE_HISTO", "HAS_HEURES_MAUVAISE_PERIODE", "NBVH", "HEURES", "VALIDE"
  )  AS 
  WITH t AS (
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

  ose_divers.comprise_entre( ep.histo_creation, ep.histo_destruction )                      element_pedagogique_histo,
  CASE WHEN 1 = ose_divers.comprise_entre( etp.histo_creation, etp.histo_destruction ) OR cp.id IS NOT NULL THEN 1 ELSE 0 END etape_histo,

  CASE WHEN ep.periode_id IS NOT NULL THEN
    SUM( CASE WHEN vh.periode_id <> ep.periode_id THEN 1 ELSE 0 END ) OVER( PARTITION BY vh.service_id, vh.periode_id, vh.type_volume_horaire_id, vh.type_intervention_id )
  ELSE 0 END has_heures_mauvaise_periode,

  CASE WHEN v.id IS NULL THEN 0 ELSE 1 END valide
FROM
  service                                       s
  LEFT JOIN element_pedagogique                ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN etape                             etp ON etp.id = ep.etape_id
  LEFT JOIN chemin_pedagogique                 cp ON cp.etape_id = etp.id
                                                 AND cp.element_pedagogique_id = ep.id
                                                 AND 1 = ose_divers.comprise_entre( cp.histo_creation, cp.histo_destruction )

  LEFT JOIN volume_horaire                     vh ON vh.service_id = s.id
                                                 AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )

  LEFT JOIN type_volume_horaire               tvh ON tvh.id = vh.type_volume_horaire_id

  LEFT JOIN validation_vol_horaire            vvh ON vvh.volume_horaire_id = vh.id

  LEFT JOIN validation                          v ON v.id = vvh.validation_id
                                                 AND 1 = ose_divers.comprise_entre( v.histo_creation, v.histo_destruction )
WHERE
  1 = UNICAEN_TBL.MATCH_PARAM('INTERVENANT_ID', s.intervenant_id )
  AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
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
  t.etape_histo;

CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_SERVICE_REFERENTIEL" 
 ( "ANNEE_ID", "INTERVENANT_ID", "PEUT_SAISIR_SERVICE", "TYPE_VOLUME_HORAIRE_ID", "STRUCTURE_ID", "NBVH", "VALIDE"
  )  AS 
  WITH t AS (

  SELECT
    i.annee_id,
    i.id intervenant_id,
    si.peut_saisir_referentiel peut_saisir_service,
    vh.type_volume_horaire_id,
    s.structure_id,
    CASE WHEN v.id IS NULL THEN 0 ELSE 1 END valide
  FROM
              intervenant                     i
              
         JOIN statut_intervenant          si ON si.id = i.statut_id
              
    LEFT JOIN service_referentiel          s ON s.intervenant_id = i.id
                                            AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
                                        
    LEFT JOIN volume_horaire_ref          vh ON vh.service_referentiel_id = s.id 
                                            AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
                                        
    LEFT JOIN validation_vol_horaire_ref vvh ON vvh.volume_horaire_ref_id = vh.id
    
    LEFT JOIN validation                   v ON v.id = vvh.validation_id
                                            AND 1 = ose_divers.comprise_entre( v.histo_creation, v.histo_destruction )
  WHERE
    1 = UNICAEN_TBL.MATCH_PARAM('INTERVENANT_ID', i.id )
    AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )

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
  structure_id;


CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_SERVICE_SAISIE" 
 ( "ANNEE_ID", "INTERVENANT_ID", "PEUT_SAISIR_SERVICE", "PEUT_SAISIR_REFERENTIEL", "HEURES_SERVICE_PREV", "HEURES_REFERENTIEL_PREV", "HEURES_SERVICE_REAL", "HEURES_REFERENTIEL_REAL"
  )  AS 
  SELECT
  i.annee_id,
  i.id intervenant_id,
  si.peut_saisir_service,
  si.peut_saisir_referentiel,
  SUM( CASE WHEN tvhs.code = 'PREVU'   THEN NVL(vh .heures,0) ELSE 0 END ) heures_service_prev,
  SUM( CASE WHEN tvhs.code = 'PREVU'   THEN NVL(vhr.heures,0) ELSE 0 END ) heures_referentiel_prev,
  SUM( CASE WHEN tvhs.code = 'REALISE' THEN NVL(vh .heures,0) ELSE 0 END ) heures_service_real,
  SUM( CASE WHEN tvhs.code = 'REALISE' THEN NVL(vhr.heures,0) ELSE 0 END ) heures_referentiel_real
FROM
  intervenant i
  JOIN statut_intervenant si ON si.id = i.statut_id
  LEFT JOIN service s ON s.intervenant_id = i.id AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
  LEFT JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
  LEFT JOIN type_volume_horaire tvhs ON tvhs.id = vh.type_volume_horaire_id

  LEFT JOIN service_referentiel sr ON sr.intervenant_id = i.id AND 1 = ose_divers.comprise_entre( sr.histo_creation, sr.histo_destruction )
  LEFT JOIN volume_horaire_ref vhr ON vhr.service_referentiel_id = sr.id AND 1 = ose_divers.comprise_entre( vhr.histo_creation, vhr.histo_destruction )
  LEFT JOIN type_volume_horaire tvhrs ON tvhrs.id = vhr.type_volume_horaire_id
WHERE
  1 = UNICAEN_TBL.MATCH_PARAM('INTERVENANT_ID', i.id )
  AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
GROUP BY
  i.annee_id,
  i.id,
  si.peut_saisir_service,
  si.peut_saisir_referentiel;


CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_VALIDATION_ENSEIGNEMENT" 
 ( "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "TYPE_VOLUME_HORAIRE_ID", "SERVICE_ID", "VOLUME_HORAIRE_ID", "VALIDATION_ID"
  )  AS 
  SELECT DISTINCT
  i.annee_id,
  i.id intervenant_id,
  CASE WHEN rsv.priorite = 'affectation' THEN
    COALESCE( i.structure_id, ep.structure_id, str.id )
  ELSE
    COALESCE( ep.structure_id, i.structure_id, str.id )
  END structure_id,
  vh.type_volume_horaire_id,
  s.id service_id,
  vh.id volume_horaire_id,
  v.id validation_id
FROM
  service s
  JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
  JOIN intervenant i ON i.id = s.intervenant_id AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
  JOIN statut_intervenant si ON si.id = i.statut_id
  JOIN regle_structure_validation rsv ON rsv.type_intervenant_id = si.type_intervenant_id AND rsv.type_volume_horaire_id = vh.type_volume_horaire_id
  LEFT JOIN structure str ON str.niveau = 1 AND 1 = ose_divers.comprise_entre( str.histo_creation, str.histo_destruction )
  LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN validation_vol_horaire vvh ON vvh.volume_horaire_id = vh.id
  LEFT JOIN validation v ON v.id = vvh.validation_id AND 1 = ose_divers.comprise_entre( v.histo_creation, v.histo_destruction )
WHERE
  1 = UNICAEN_TBL.MATCH_PARAM('INTERVENANT_ID', s.intervenant_id )
  AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction );



CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_VALIDATION_REFERENTIEL" 
 ( "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "TYPE_VOLUME_HORAIRE_ID", "SERVICE_REFERENTIEL_ID", "VOLUME_HORAIRE_REF_ID", "VALIDATION_ID"
  )  AS 
  SELECT DISTINCT
  i.annee_id,
  i.id intervenant_id,
  CASE WHEN rsv.priorite = 'affectation' THEN
    COALESCE( i.structure_id, s.structure_id, str.id )
  ELSE
    COALESCE( s.structure_id, i.structure_id, str.id )
  END structure_id,
  vh.type_volume_horaire_id,
  s.id service_referentiel_id,
  vh.id volume_horaire_ref_id,
  v.id validation_id
FROM
  service_referentiel s
  JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
  JOIN intervenant i ON i.id = s.intervenant_id AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
  JOIN statut_intervenant si ON si.id = i.statut_id
  JOIN regle_structure_validation rsv ON rsv.type_intervenant_id = si.type_intervenant_id AND rsv.type_volume_horaire_id = vh.type_volume_horaire_id
  LEFT JOIN structure str ON str.niveau = 1 AND 1 = ose_divers.comprise_entre( str.histo_creation, str.histo_destruction )
  LEFT JOIN validation_vol_horaire_ref vvh ON vvh.volume_horaire_ref_id = vh.id
  LEFT JOIN validation v ON v.id = vvh.validation_id AND 1 = ose_divers.comprise_entre( v.histo_creation, v.histo_destruction )
WHERE
  1 = UNICAEN_TBL.MATCH_PARAM('INTERVENANT_ID', s.intervenant_id )
  AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction );

---------------------------
--Modifié TRIGGER
--T_VAR_VOLUME_HORAIRE_REF_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAR_VOLUME_HORAIRE_REF_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE_REF"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_VAR_VOLUME_HORAIRE_REF
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAR_VOLUME_HORAIRE_REF"
  AFTER INSERT OR DELETE OR UPDATE OF TYPE_VOLUME_HORAIRE_ID, SERVICE_REFERENTIEL_ID, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."VOLUME_HORAIRE_REF"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service_referentiel s
    WHERE
      s.id = :NEW.service_referentiel_id
      OR s.id = :OLD.service_referentiel_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_referentiel', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_VAR_VAL_VOL_HORAIRE_REF_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAR_VAL_VOL_HORAIRE_REF_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION_VOL_HORAIRE_REF"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_VAR_VAL_VOL_HORAIRE_REF
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAR_VAL_VOL_HORAIRE_REF"
  AFTER INSERT OR DELETE OR UPDATE OF VALIDATION_ID, VOLUME_HORAIRE_REF_ID ON "OSE"."VALIDATION_VOL_HORAIRE_REF"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service_referentiel s
      JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id
    WHERE
      vh.id = :NEW.volume_horaire_ref_id
      OR vh.id = :OLD.volume_horaire_ref_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_referentiel', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_VAR_VALIDATION_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAR_VALIDATION_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_VAR_VALIDATION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAR_VALIDATION"
  AFTER INSERT OR DELETE OR UPDATE OF HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."VALIDATION"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_referentiel', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_referentiel', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_VAR_STRUCTURE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAR_STRUCTURE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."STRUCTURE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_VAR_STRUCTURE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAR_STRUCTURE"
  AFTER UPDATE OF NIVEAU, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."STRUCTURE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      tve.intervenant_id
    FROM
      tbl_validation_enseignement tve
    WHERE
         tve.structure_id = :NEW.id
      OR tve.structure_id = :OLD.id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_referentiel', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_VAR_STATUT_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAR_STATUT_INTERVENANT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."STATUT_INTERVENANT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_VAR_STATUT_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAR_STATUT_INTERVENANT"
  AFTER UPDATE OF TYPE_INTERVENANT_ID ON "OSE"."STATUT_INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      i.id intervenant_id
    FROM
      intervenant i
    WHERE
         i.statut_id = :NEW.id
      OR i.statut_id = :OLD.id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_referentiel', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_VAR_SERVICE_REFERENTIEL_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAR_SERVICE_REFERENTIEL_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE_REFERENTIEL"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_VAR_SERVICE_REFERENTIEL
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAR_SERVICE_REFERENTIEL"
  AFTER INSERT OR DELETE OR UPDATE OF INTERVENANT_ID, STRUCTURE_ID, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."SERVICE_REFERENTIEL"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_referentiel', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_referentiel', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_VAR_REGLE_STRUCTURE_VAL_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAR_REGLE_STRUCTURE_VAL_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."REGLE_STRUCTURE_VALIDATION"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_VAR_REGLE_STRUCTURE_VAL
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAR_REGLE_STRUCTURE_VAL"
  AFTER UPDATE OF TYPE_VOLUME_HORAIRE_ID, TYPE_INTERVENANT_ID, PRIORITE ON "OSE"."REGLE_STRUCTURE_VALIDATION"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      i.id intervenant_id
    FROM
      intervenant i
      JOIN statut_intervenant si ON si.id = i.id
    WHERE
         si.type_intervenant_id = :NEW.type_intervenant_id
      OR si.type_intervenant_id = :OLD.type_intervenant_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_referentiel', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
      JOIN volume_horaire vh ON vh.service_id = s.id
    WHERE
         vh.type_volume_horaire_id = :NEW.type_volume_horaire_id
      OR vh.type_volume_horaire_id = :OLD.type_volume_horaire_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_referentiel', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_VAR_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAR_INTERVENANT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."INTERVENANT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_VAR_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAR_INTERVENANT"
  AFTER INSERT OR DELETE OR UPDATE OF STATUT_ID, STRUCTURE_ID, HISTO_CREATION, HISTO_DESTRUCTION, ANNEE_ID ON "OSE"."INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_referentiel', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.id ) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_referentiel', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_VAE_VOLUME_HORAIRE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAE_VOLUME_HORAIRE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_VAE_VOLUME_HORAIRE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAE_VOLUME_HORAIRE"
  AFTER INSERT OR DELETE OR UPDATE OF TYPE_VOLUME_HORAIRE_ID, SERVICE_ID, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."VOLUME_HORAIRE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
    WHERE
      s.id = :NEW.service_id
      OR s.id = :OLD.service_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_VAE_VALIDATION_VOL_HORAIRE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAE_VALIDATION_VOL_HORAIRE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION_VOL_HORAIRE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_VAE_VALIDATION_VOL_HORAIRE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAE_VALIDATION_VOL_HORAIRE"
  AFTER INSERT OR DELETE OR UPDATE OF VALIDATION_ID, VOLUME_HORAIRE_ID ON "OSE"."VALIDATION_VOL_HORAIRE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
      JOIN volume_horaire vh ON vh.service_id = s.id
    WHERE
      vh.id = :NEW.volume_horaire_id
      OR vh.id = :OLD.volume_horaire_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_VAE_VALIDATION_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAE_VALIDATION_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_VAE_VALIDATION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAE_VALIDATION"
  AFTER INSERT OR DELETE OR UPDATE OF HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."VALIDATION"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_VAE_STRUCTURE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAE_STRUCTURE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."STRUCTURE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_VAE_STRUCTURE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAE_STRUCTURE"
  AFTER UPDATE OF NIVEAU, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."STRUCTURE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      tve.intervenant_id
    FROM
      tbl_validation_enseignement tve
    WHERE
         tve.structure_id = :NEW.id
      OR tve.structure_id = :OLD.id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_VAE_STATUT_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAE_STATUT_INTERVENANT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."STATUT_INTERVENANT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_VAE_STATUT_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAE_STATUT_INTERVENANT"
  AFTER UPDATE OF TYPE_INTERVENANT_ID ON "OSE"."STATUT_INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      i.id intervenant_id
    FROM
      intervenant i
    WHERE
         i.statut_id = :NEW.id
      OR i.statut_id = :OLD.id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_VAE_SERVICE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAE_SERVICE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_VAE_SERVICE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAE_SERVICE"
  AFTER INSERT OR DELETE OR UPDATE OF INTERVENANT_ID, ELEMENT_PEDAGOGIQUE_ID, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."SERVICE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_VAE_REGLE_STRUCTURE_VAL_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAE_REGLE_STRUCTURE_VAL_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."REGLE_STRUCTURE_VALIDATION"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_VAE_REGLE_STRUCTURE_VAL
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAE_REGLE_STRUCTURE_VAL"
  AFTER UPDATE OF TYPE_VOLUME_HORAIRE_ID, TYPE_INTERVENANT_ID, PRIORITE ON "OSE"."REGLE_STRUCTURE_VALIDATION"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      i.id intervenant_id
    FROM
      intervenant i
      JOIN statut_intervenant si ON si.id = i.id
    WHERE
         si.type_intervenant_id = :NEW.type_intervenant_id
      OR si.type_intervenant_id = :OLD.type_intervenant_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
      JOIN volume_horaire vh ON vh.service_id = s.id
    WHERE
         vh.type_volume_horaire_id = :NEW.type_volume_horaire_id
      OR vh.type_volume_horaire_id = :OLD.type_volume_horaire_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_VAE_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAE_INTERVENANT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."INTERVENANT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_VAE_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAE_INTERVENANT"
  AFTER INSERT OR DELETE OR UPDATE OF STATUT_ID, STRUCTURE_ID, HISTO_CREATION, HISTO_DESTRUCTION, ANNEE_ID ON "OSE"."INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.id ) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_VAE_ELEMENT_PEDAGOGIQUE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAE_ELEMENT_PEDAGOGIQUE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."ELEMENT_PEDAGOGIQUE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_VAE_ELEMENT_PEDAGOGIQUE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_VAE_ELEMENT_PEDAGOGIQUE"
  AFTER INSERT OR DELETE OR UPDATE OF STRUCTURE_ID ON "OSE"."ELEMENT_PEDAGOGIQUE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
    WHERE
         s.element_pedagogique_id = :NEW.id
      OR s.element_pedagogique_id = :OLD.id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_SRV_VOLUME_HORAIRE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRV_VOLUME_HORAIRE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_SRV_VOLUME_HORAIRE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRV_VOLUME_HORAIRE"
  AFTER INSERT OR DELETE OR UPDATE OF TYPE_VOLUME_HORAIRE_ID, SERVICE_ID, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."VOLUME_HORAIRE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
    WHERE
      s.id = :NEW.service_id
      OR s.id = :OLD.service_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'service', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_SRV_VALIDATION_VOL_HORAIRE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRV_VALIDATION_VOL_HORAIRE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION_VOL_HORAIRE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_SRV_VALIDATION_VOL_HORAIRE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRV_VALIDATION_VOL_HORAIRE"
  AFTER INSERT OR DELETE OR UPDATE OF VALIDATION_ID, VOLUME_HORAIRE_ID ON "OSE"."VALIDATION_VOL_HORAIRE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
      JOIN volume_horaire vh ON vh.service_id = s.id
    WHERE
      vh.id = :NEW.volume_horaire_id
      OR vh.id = :OLD.volume_horaire_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'service', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_SRV_VALIDATION_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRV_VALIDATION_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_SRV_VALIDATION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRV_VALIDATION"
  AFTER INSERT OR DELETE OR UPDATE OF HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."VALIDATION"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_SRV_STATUT_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRV_STATUT_INTERVENANT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."STATUT_INTERVENANT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_SRV_STATUT_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRV_STATUT_INTERVENANT"
  AFTER UPDATE OF PEUT_SAISIR_SERVICE ON "OSE"."STATUT_INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      i.id intervenant_id
    FROM
      intervenant i
    WHERE
         i.statut_id = :NEW.id
      OR i.statut_id = :OLD.id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'service', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_SRV_SERVICE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRV_SERVICE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_SRV_SERVICE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRV_SERVICE"
  AFTER INSERT OR DELETE OR UPDATE OF INTERVENANT_ID, ELEMENT_PEDAGOGIQUE_ID, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."SERVICE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_SRV_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRV_INTERVENANT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."INTERVENANT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_SRV_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRV_INTERVENANT"
  AFTER INSERT OR DELETE OR UPDATE OF STATUT_ID, STRUCTURE_ID, HISTO_CREATION, HISTO_DESTRUCTION, ANNEE_ID ON "OSE"."INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.id ) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_SRV_ELEMENT_PEDAGOGIQUE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRV_ELEMENT_PEDAGOGIQUE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."ELEMENT_PEDAGOGIQUE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_SRV_ELEMENT_PEDAGOGIQUE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRV_ELEMENT_PEDAGOGIQUE"
  AFTER INSERT OR DELETE OR UPDATE OF STRUCTURE_ID ON "OSE"."ELEMENT_PEDAGOGIQUE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
    WHERE
         s.element_pedagogique_id = :NEW.id
      OR s.element_pedagogique_id = :OLD.id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'service', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_SRS_VOLUME_HORAIRE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRS_VOLUME_HORAIRE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_SRS_VOLUME_HORAIRE_REF_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRS_VOLUME_HORAIRE_REF_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE_REF"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_SRS_VOLUME_HORAIRE_REF
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRS_VOLUME_HORAIRE_REF"
  AFTER INSERT OR DELETE OR UPDATE OF TYPE_VOLUME_HORAIRE_ID, SERVICE_REFERENTIEL_ID, HEURES, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."VOLUME_HORAIRE_REF"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service_referentiel s
    WHERE
      s.id = :NEW.service_referentiel_id
      OR s.id = :OLD.service_referentiel_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'service_saisie', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_SRS_VOLUME_HORAIRE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRS_VOLUME_HORAIRE"
  AFTER INSERT OR DELETE OR UPDATE OF TYPE_VOLUME_HORAIRE_ID, SERVICE_ID, HEURES, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."VOLUME_HORAIRE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
    WHERE
      s.id = :NEW.service_id
      OR s.id = :OLD.service_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'service_saisie', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_SRS_STATUT_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRS_STATUT_INTERVENANT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."STATUT_INTERVENANT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_SRS_STATUT_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRS_STATUT_INTERVENANT"
  AFTER UPDATE OF PEUT_SAISIR_SERVICE, PEUT_SAISIR_REFERENTIEL ON "OSE"."STATUT_INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      i.id intervenant_id
    FROM
      intervenant i
    WHERE
         i.statut_id = :NEW.id
      OR i.statut_id = :OLD.id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'service_saisie', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_SRS_SERVICE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRS_SERVICE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_SRS_SERVICE_REFERENTIEL_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRS_SERVICE_REFERENTIEL_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE_REFERENTIEL"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_SRS_SERVICE_REFERENTIEL
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRS_SERVICE_REFERENTIEL"
  AFTER INSERT OR DELETE OR UPDATE OF INTERVENANT_ID, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."SERVICE_REFERENTIEL"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service_saisie', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service_saisie', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_SRS_SERVICE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRS_SERVICE"
  AFTER INSERT OR DELETE OR UPDATE OF INTERVENANT_ID, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."SERVICE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service_saisie', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service_saisie', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_SRS_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRS_INTERVENANT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."INTERVENANT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_SRS_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRS_INTERVENANT"
  AFTER INSERT OR DELETE OR UPDATE OF STATUT_ID, HISTO_CREATION, HISTO_DESTRUCTION, ANNEE_ID ON "OSE"."INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service_saisie', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.id ) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service_saisie', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_SRR_VOLUME_HORAIRE_REF_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRR_VOLUME_HORAIRE_REF_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE_REF"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_SRR_VOLUME_HORAIRE_REF
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRR_VOLUME_HORAIRE_REF"
  AFTER INSERT OR DELETE OR UPDATE OF TYPE_VOLUME_HORAIRE_ID, SERVICE_REFERENTIEL_ID, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."VOLUME_HORAIRE_REF"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service_referentiel s
    WHERE
      s.id = :NEW.service_referentiel_id
      OR s.id = :OLD.service_referentiel_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'service_referentiel', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_SRR_VAL_VOL_HORAIRE_REF_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRR_VAL_VOL_HORAIRE_REF_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION_VOL_HORAIRE_REF"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_SRR_VAL_VOL_HORAIRE_REF
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRR_VAL_VOL_HORAIRE_REF"
  AFTER INSERT OR DELETE OR UPDATE OF VALIDATION_ID, VOLUME_HORAIRE_REF_ID ON "OSE"."VALIDATION_VOL_HORAIRE_REF"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
      JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id
    WHERE
      vh.id = :NEW.volume_horaire_ref_id
      OR vh.id = :OLD.volume_horaire_ref_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'service_referentiel', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_SRR_VALIDATION_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRR_VALIDATION_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_SRR_VALIDATION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRR_VALIDATION"
  AFTER INSERT OR DELETE OR UPDATE OF HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."VALIDATION"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service_referentiel', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service_referentiel', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_SRR_STATUT_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRR_STATUT_INTERVENANT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."STATUT_INTERVENANT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_SRR_STATUT_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRR_STATUT_INTERVENANT"
  AFTER UPDATE OF PEUT_SAISIR_REFERENTIEL ON "OSE"."STATUT_INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      i.id intervenant_id
    FROM
      intervenant i
    WHERE
         i.statut_id = :NEW.id
      OR i.statut_id = :OLD.id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'service_referentiel', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_SRR_SERVICE_REFERENTIEL_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRR_SERVICE_REFERENTIEL_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE_REFERENTIEL"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_SRR_SERVICE_REFERENTIEL
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRR_SERVICE_REFERENTIEL"
  AFTER INSERT OR DELETE OR UPDATE OF INTERVENANT_ID, STRUCTURE_ID, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."SERVICE_REFERENTIEL"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service_referentiel', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service_referentiel', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_SRR_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRR_INTERVENANT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."INTERVENANT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_SRR_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_SRR_INTERVENANT"
  AFTER INSERT OR DELETE OR UPDATE OF STATUT_ID, HISTO_CREATION, HISTO_DESTRUCTION, ANNEE_ID ON "OSE"."INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service_referentiel', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.id ) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service_referentiel', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_PJF_VALIDATION_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJF_VALIDATION_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_PJF_VALIDATION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJF_VALIDATION"
  AFTER INSERT OR DELETE OR UPDATE OF HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."VALIDATION"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_fournie', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_fournie', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_PJF_PIECE_JOINTE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJF_PIECE_JOINTE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."PIECE_JOINTE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_PJF_PIECE_JOINTE_FICHER_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJF_PIECE_JOINTE_FICHER_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."PIECE_JOINTE_FICHIER"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_PJF_PIECE_JOINTE_FICHER
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJF_PIECE_JOINTE_FICHER"
  AFTER INSERT OR DELETE OR UPDATE OF PIECE_JOINTE_ID, FICHIER_ID ON "OSE"."PIECE_JOINTE_FICHIER"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      pj.intervenant_id
    FROM
      piece_jointe pj
    WHERE
         pj.id = :NEW.piece_jointe_id
      OR pj.id = :OLD.piece_jointe_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_fournie', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_PJF_PIECE_JOINTE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJF_PIECE_JOINTE"
  AFTER INSERT OR DELETE OR UPDATE OF TYPE_PIECE_JOINTE_ID, INTERVENANT_ID, HISTO_CREATION, HISTO_DESTRUCTION, VALIDATION_ID ON "OSE"."PIECE_JOINTE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_fournie', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_fournie', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_PJF_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJF_INTERVENANT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."INTERVENANT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_PJF_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJF_INTERVENANT"
  AFTER INSERT OR DELETE OR UPDATE OF HISTO_CREATION, HISTO_DESTRUCTION, ANNEE_ID ON "OSE"."INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_fournie', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.id ) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_fournie', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_PJF_FICHER_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJF_FICHER_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."FICHIER"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_PJF_FICHER
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJF_FICHER"
  AFTER INSERT OR DELETE OR UPDATE OF HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."FICHIER"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      pj.intervenant_id
    FROM
      piece_jointe pj
      JOIN piece_jointe_fichier pjf ON pjf.piece_jointe_id = pj.id
    WHERE
         pjf.fichier_id = :NEW.id
      OR pjf.fichier_id = :OLD.id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_fournie', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_PJF_DOSSIER_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJF_DOSSIER_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."DOSSIER"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_PJF_DOSSIER
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJF_DOSSIER"
  AFTER INSERT OR DELETE OR UPDATE OF INTERVENANT_ID ON "OSE"."DOSSIER"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_fournie', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_fournie', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_PJD_VOLUME_HORAIRE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJD_VOLUME_HORAIRE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_PJD_VOLUME_HORAIRE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJD_VOLUME_HORAIRE"
  AFTER INSERT OR DELETE OR UPDATE OF TYPE_VOLUME_HORAIRE_ID, SERVICE_ID, HEURES, MOTIF_NON_PAIEMENT_ID, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."VOLUME_HORAIRE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
    WHERE
      s.id = :NEW.service_id
      OR s.id = :OLD.service_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_demande', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_PJD_TYPE_PIECE_JOINTE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJD_TYPE_PIECE_JOINTE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."TYPE_PIECE_JOINTE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_PJD_TYPE_PIECE_JOINTE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJD_TYPE_PIECE_JOINTE"
  AFTER INSERT OR DELETE OR UPDATE OF CODE, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."TYPE_PIECE_JOINTE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      i.id intervenant_id
    FROM
      intervenant i
      JOIN statut_intervenant si ON si.id = i.statut_id
      JOIN type_piece_jointe_statut tpjs ON tpjs.statut_intervenant_id = si.id
    WHERE
         TPJS.TYPE_PIECE_JOINTE_ID = :NEW.id
      OR TPJS.TYPE_PIECE_JOINTE_ID = :OLD.id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_demande', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_PJD_T_PIECE_JOINTE_STATUT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJD_T_PIECE_JOINTE_STATUT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."TYPE_PIECE_JOINTE_STATUT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_PJD_T_PIECE_JOINTE_STATUT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJD_T_PIECE_JOINTE_STATUT"
  AFTER INSERT OR DELETE OR UPDATE OF TYPE_PIECE_JOINTE_ID, STATUT_INTERVENANT_ID, SEUIL_HETD, PREMIER_RECRUTEMENT, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."TYPE_PIECE_JOINTE_STATUT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      i.id intervenant_id
    FROM
      intervenant i
    WHERE
         i.statut_id = :NEW.statut_intervenant_id
      OR i.statut_id = :OLD.statut_intervenant_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_demande', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_PJD_SERVICE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJD_SERVICE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_PJD_SERVICE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJD_SERVICE"
  AFTER INSERT OR DELETE OR UPDATE OF INTERVENANT_ID, ELEMENT_PEDAGOGIQUE_ID, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."SERVICE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_demande', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_demande', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_PJD_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJD_INTERVENANT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."INTERVENANT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_PJD_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJD_INTERVENANT"
  AFTER INSERT OR DELETE OR UPDATE OF STATUT_ID, IBAN, BIC, HISTO_CREATION, HISTO_DESTRUCTION, PREMIER_RECRUTEMENT, ANNEE_ID ON "OSE"."INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_demande', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.id ) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_demande', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_PJD_DOSSIER_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJD_DOSSIER_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."DOSSIER"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_PJD_DOSSIER
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PJD_DOSSIER"
  AFTER INSERT OR DELETE OR UPDATE OF RIB, HISTO_CREATION, HISTO_DESTRUCTION, INTERVENANT_ID ON "OSE"."DOSSIER"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_demande', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_demande', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_PAI_SERVICE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PAI_SERVICE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_PAI_SERVICE_REFERENTIEL_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PAI_SERVICE_REFERENTIEL_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE_REFERENTIEL"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_PAI_SERVICE_REFERENTIEL
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PAI_SERVICE_REFERENTIEL"
  AFTER INSERT OR DELETE OR UPDATE OF STRUCTURE_ID ON "OSE"."SERVICE_REFERENTIEL"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'paiement', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'paiement', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_PAI_SERVICE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PAI_SERVICE"
  AFTER INSERT OR DELETE OR UPDATE OF ELEMENT_PEDAGOGIQUE_ID ON "OSE"."SERVICE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'paiement', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'paiement', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_PAI_MISE_EN_PAIEMENT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PAI_MISE_EN_PAIEMENT_S"
  AFTER INSERT OR UPDATE ON "OSE"."MISE_EN_PAIEMENT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Nouveau TRIGGER
--T_PAI_MISE_EN_PAIEMENT_DEL
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PAI_MISE_EN_PAIEMENT_DEL"
  AFTER DELETE ON "OSE"."MISE_EN_PAIEMENT"
  REFERENCING FOR EACH ROW
  BEGIN

  DELETE FROM TBL_PAIEMENT WHERE mise_en_paiement_id = :OLD.id;

END;
/
---------------------------
--Modifié TRIGGER
--T_PAI_MISE_EN_PAIEMENT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PAI_MISE_EN_PAIEMENT"
  AFTER INSERT OR UPDATE OF FORMULE_RES_SERVICE_ID, FORMULE_RES_SERVICE_REF_ID, PERIODE_PAIEMENT_ID, HEURES, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."MISE_EN_PAIEMENT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      fr.intervenant_id
    FROM
      formule_resultat fr
      LEFT JOIN formule_resultat_service frs ON frs.formule_resultat_id = fr.id
      LEFT JOIN formule_resultat_service_ref frsr ON frsr.formule_resultat_id = fr.id
    WHERE
         (frs.id  IS NOT NULL AND (frs.id  = :OLD.formule_res_service_id     OR frs.id  = :NEW.formule_res_service_id    ))
      OR (frsr.id IS NOT NULL AND (frsr.id = :OLD.formule_res_service_ref_id OR frsr.id = :NEW.formule_res_service_ref_id))

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'paiement', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_PAI_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PAI_INTERVENANT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."INTERVENANT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_PAI_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PAI_INTERVENANT"
  AFTER INSERT OR DELETE OR UPDATE OF STRUCTURE_ID, ANNEE_ID ON "OSE"."INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'paiement', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.id ) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'paiement', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_PAI_ELEMENT_PEDAGOGIQUE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PAI_ELEMENT_PEDAGOGIQUE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."ELEMENT_PEDAGOGIQUE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_PAI_ELEMENT_PEDAGOGIQUE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_PAI_ELEMENT_PEDAGOGIQUE"
  AFTER INSERT OR DELETE OR UPDATE OF STRUCTURE_ID ON "OSE"."ELEMENT_PEDAGOGIQUE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
    WHERE
         s.element_pedagogique_id = :NEW.id
      OR s.element_pedagogique_id = :OLD.id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'paiement', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_DOS_VALIDATION_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_DOS_VALIDATION_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_DOS_VALIDATION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_DOS_VALIDATION"
  AFTER INSERT OR DELETE OR UPDATE OF TYPE_VALIDATION_ID, INTERVENANT_ID, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."VALIDATION"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'dossier', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'dossier', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_DOS_STATUT_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_DOS_STATUT_INTERVENANT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."STATUT_INTERVENANT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_DOS_STATUT_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_DOS_STATUT_INTERVENANT"
  AFTER INSERT OR DELETE OR UPDATE OF PEUT_SAISIR_DOSSIER ON "OSE"."STATUT_INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      i.id intervenant_id
    FROM
      intervenant i
    WHERE
         i.statut_id = :NEW.id
      OR i.statut_id = :OLD.id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'dossier', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_DOS_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_DOS_INTERVENANT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."INTERVENANT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_DOS_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_DOS_INTERVENANT"
  AFTER INSERT OR DELETE OR UPDATE OF STATUT_ID, HISTO_CREATION, HISTO_DESTRUCTION, ANNEE_ID ON "OSE"."INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'dossier', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.id ) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'dossier', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_DOS_DOSSIER_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_DOS_DOSSIER_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_DOS_DOSSIER
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_DOS_DOSSIER"
  AFTER INSERT OR DELETE OR UPDATE OF HISTO_CREATION, HISTO_DESTRUCTION, INTERVENANT_ID ON "OSE"."DOSSIER"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'dossier', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'dossier', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_CRT_VOLUME_HORAIRE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRT_VOLUME_HORAIRE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_CRT_VOLUME_HORAIRE_REF_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRT_VOLUME_HORAIRE_REF_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE_REF"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_CRT_VOLUME_HORAIRE_REF
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRT_VOLUME_HORAIRE_REF"
  AFTER INSERT OR DELETE OR UPDATE OF SERVICE_REFERENTIEL_ID, HEURES, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."VOLUME_HORAIRE_REF"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service_referentiel s
    WHERE
      s.id = :NEW.service_referentiel_id
      OR s.id = :OLD.service_referentiel_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_CRT_VOLUME_HORAIRE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRT_VOLUME_HORAIRE"
  AFTER INSERT OR DELETE OR UPDATE OF SERVICE_ID, HEURES, CONTRAT_ID, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."VOLUME_HORAIRE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
    WHERE
      s.id = :NEW.service_id
      OR s.id = :OLD.service_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_CRT_VAL_VOL_HORAIRE_REF_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRT_VAL_VOL_HORAIRE_REF_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION_VOL_HORAIRE_REF"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_CRT_VAL_VOL_HORAIRE_REF
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRT_VAL_VOL_HORAIRE_REF"
  AFTER INSERT OR DELETE OR UPDATE OF VALIDATION_ID, VOLUME_HORAIRE_REF_ID ON "OSE"."VALIDATION_VOL_HORAIRE_REF"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
      JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id
    WHERE
      vh.id = :NEW.volume_horaire_ref_id
      OR vh.id = :OLD.volume_horaire_ref_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_CRT_VALIDATION_VOL_HORAIRE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRT_VALIDATION_VOL_HORAIRE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION_VOL_HORAIRE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_CRT_VALIDATION_VOL_HORAIRE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRT_VALIDATION_VOL_HORAIRE"
  AFTER INSERT OR DELETE OR UPDATE OF VALIDATION_ID, VOLUME_HORAIRE_ID ON "OSE"."VALIDATION_VOL_HORAIRE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
      JOIN volume_horaire vh ON vh.service_id = s.id
    WHERE
      vh.id = :NEW.volume_horaire_id
      OR vh.id = :OLD.volume_horaire_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_CRT_VALIDATION_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRT_VALIDATION_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_CRT_VALIDATION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRT_VALIDATION"
  AFTER INSERT OR DELETE OR UPDATE OF HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."VALIDATION"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_CRT_STATUT_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRT_STATUT_INTERVENANT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."STATUT_INTERVENANT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_CRT_STATUT_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRT_STATUT_INTERVENANT"
  AFTER UPDATE OF PEUT_AVOIR_CONTRAT ON "OSE"."STATUT_INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      i.id intervenant_id
    FROM
      intervenant i
    WHERE
         i.statut_id = :NEW.id
      OR i.statut_id = :OLD.id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_CRT_SERVICE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRT_SERVICE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_CRT_SERVICE_REFERENTIEL_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRT_SERVICE_REFERENTIEL_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE_REFERENTIEL"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_CRT_SERVICE_REFERENTIEL
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRT_SERVICE_REFERENTIEL"
  AFTER INSERT OR DELETE OR UPDATE OF INTERVENANT_ID, STRUCTURE_ID, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."SERVICE_REFERENTIEL"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_CRT_SERVICE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRT_SERVICE"
  AFTER INSERT OR DELETE OR UPDATE OF INTERVENANT_ID, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."SERVICE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_CRT_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRT_INTERVENANT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."INTERVENANT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_CRT_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRT_INTERVENANT"
  AFTER INSERT OR DELETE OR UPDATE OF STATUT_ID, STRUCTURE_ID, HISTO_CREATION, HISTO_DESTRUCTION, ANNEE_ID ON "OSE"."INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.id ) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_CRT_ELEMENT_PEDAGOGIQUE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRT_ELEMENT_PEDAGOGIQUE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."ELEMENT_PEDAGOGIQUE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_CRT_ELEMENT_PEDAGOGIQUE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRT_ELEMENT_PEDAGOGIQUE"
  AFTER INSERT OR DELETE OR UPDATE OF STRUCTURE_ID ON "OSE"."ELEMENT_PEDAGOGIQUE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
    WHERE
         s.element_pedagogique_id = :NEW.id
      OR s.element_pedagogique_id = :OLD.id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_CRT_CONTRAT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRT_CONTRAT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."CONTRAT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_CRT_CONTRAT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRT_CONTRAT"
  AFTER INSERT OR DELETE OR UPDATE OF VALIDATION_ID, DATE_RETOUR_SIGNE, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."CONTRAT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_CLO_VALIDATION_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CLO_VALIDATION_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_CLO_VALIDATION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CLO_VALIDATION"
  AFTER INSERT OR DELETE OR UPDATE OF TYPE_VALIDATION_ID, INTERVENANT_ID, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."VALIDATION"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'cloture_realise', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'cloture_realise', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_CLO_STATUT_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CLO_STATUT_INTERVENANT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."STATUT_INTERVENANT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_CLO_STATUT_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CLO_STATUT_INTERVENANT"
  AFTER INSERT OR DELETE OR UPDATE OF PEUT_CLOTURER_SAISIE ON "OSE"."STATUT_INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      i.id intervenant_id
    FROM
      intervenant i
    WHERE
         i.statut_id = :NEW.id
      OR i.statut_id = :OLD.id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'cloture_realise', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_CLO_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CLO_INTERVENANT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."INTERVENANT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_CLO_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CLO_INTERVENANT"
  AFTER INSERT OR DELETE OR UPDATE OF STATUT_ID, HISTO_CREATION, HISTO_DESTRUCTION, ANNEE_ID ON "OSE"."INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'cloture_realise', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.id) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'cloture_realise', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.id) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_AGR_TA_STATUT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_AGR_TA_STATUT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."TYPE_AGREMENT_STATUT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_AGR_TA_STATUT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_AGR_TA_STATUT"
  AFTER INSERT OR DELETE OR UPDATE OF TYPE_AGREMENT_ID, STATUT_INTERVENANT_ID, OBLIGATOIRE, PREMIER_RECRUTEMENT, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."TYPE_AGREMENT_STATUT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      i.id intervenant_id
    FROM
      statut_intervenant si
      JOIN intervenant i ON i.statut_id = si.id
    WHERE
         si.id = :NEW.statut_intervenant_id
      OR si.id = :OLD.statut_intervenant_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'agrement', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_AGR_SERVICE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_AGR_SERVICE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_AGR_SERVICE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_AGR_SERVICE"
  AFTER INSERT OR DELETE OR UPDATE OF ELEMENT_PEDAGOGIQUE_ID ON "OSE"."SERVICE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'agrement', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'agrement', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_AGR_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_AGR_INTERVENANT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."INTERVENANT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_AGR_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_AGR_INTERVENANT"
  AFTER INSERT OR DELETE OR UPDATE OF STATUT_ID, HISTO_CREATION, HISTO_DESTRUCTION, PREMIER_RECRUTEMENT, ANNEE_ID ON "OSE"."INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'agrement', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.id) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'agrement', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.id) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--T_AGR_ELEMENT_PEDAGOGIQUE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_AGR_ELEMENT_PEDAGOGIQUE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."ELEMENT_PEDAGOGIQUE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_AGR_ELEMENT_PEDAGOGIQUE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_AGR_ELEMENT_PEDAGOGIQUE"
  AFTER INSERT OR DELETE OR UPDATE OF STRUCTURE_ID ON "OSE"."ELEMENT_PEDAGOGIQUE"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
    WHERE
         s.element_pedagogique_id = :NEW.id
      OR s.element_pedagogique_id = :OLD.id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'agrement', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--T_AGR_AGREMENT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_AGR_AGREMENT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."AGREMENT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--T_AGR_AGREMENT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_AGR_AGREMENT"
  AFTER INSERT OR DELETE OR UPDATE OF TYPE_AGREMENT_ID, INTERVENANT_ID, HISTO_CREATION, HISTO_DESTRUCTION, STRUCTURE_ID ON "OSE"."AGREMENT"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'agrement', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'agrement', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--F_VOLUME_HORAIRE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VOLUME_HORAIRE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--F_VOLUME_HORAIRE_REF_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VOLUME_HORAIRE_REF_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE_REF"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--F_VOLUME_HORAIRE_REF
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VOLUME_HORAIRE_REF"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE_REF"
  REFERENCING FOR EACH ROW
  BEGIN

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service_referentiel s
    WHERE
      1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
      AND (s.id = :NEW.service_referentiel_id OR s.id = :OLD.service_referentiel_id)

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );
  END LOOP;
END;
/
---------------------------
--Modifié TRIGGER
--F_VOLUME_HORAIRE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VOLUME_HORAIRE"
  AFTER INSERT OR DELETE OR UPDATE OF TYPE_VOLUME_HORAIRE_ID, SERVICE_ID, PERIODE_ID, TYPE_INTERVENTION_ID, HEURES, MOTIF_NON_PAIEMENT_ID, CONTRAT_ID, HISTO_CREATION, HISTO_MODIFICATION, HISTO_DESTRUCTION ON "OSE"."VOLUME_HORAIRE"
  REFERENCING FOR EACH ROW
  BEGIN

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
    WHERE
      1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
      AND (s.id = :NEW.service_id OR s.id = :OLD.service_id)

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );

  END LOOP;
END;
/
---------------------------
--Modifié TRIGGER
--F_VALIDATION_VOL_HORAIRE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION_VOL_HORAIRE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION_VOL_HORAIRE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--F_VALIDATION_VOL_HORAIRE_REF_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION_VOL_HORAIRE_REF_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION_VOL_HORAIRE_REF"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--F_VALIDATION_VOL_HORAIRE_REF
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION_VOL_HORAIRE_REF"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION_VOL_HORAIRE_REF"
  REFERENCING FOR EACH ROW
  BEGIN

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      volume_horaire_ref vh
      JOIN service_referentiel s ON s.id = vh.service_referentiel_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
    WHERE
      1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      AND (vh.id = :NEW.volume_horaire_ref_id OR vh.id = :OLD.volume_horaire_ref_id)

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );

  END LOOP;
END;
/
---------------------------
--Modifié TRIGGER
--F_VALIDATION_VOL_HORAIRE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION_VOL_HORAIRE"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION_VOL_HORAIRE"
  REFERENCING FOR EACH ROW
  BEGIN

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      volume_horaire vh
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
    WHERE
      1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      AND (vh.id = :NEW.volume_horaire_id OR vh.id = :OLD.volume_horaire_id)

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );

  END LOOP;
END;
/
---------------------------
--Modifié TRIGGER
--F_VALIDATION_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION_S"
  AFTER UPDATE ON "OSE"."VALIDATION"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--F_VALIDATION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION"
  AFTER UPDATE ON "OSE"."VALIDATION"
  REFERENCING FOR EACH ROW
  BEGIN

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN ( -- validations de volume horaire

    SELECT DISTINCT
      s.intervenant_id
    FROM
      validation_vol_horaire vvh
      JOIN volume_horaire vh ON vh.id = vvh.volume_horaire_id AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
    WHERE
      (vvh.validation_id = :OLD.ID OR vvh.validation_id = :NEW.id)

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );

  END LOOP;

  FOR p IN ( -- validations de contrat

    SELECT DISTINCT
      s.intervenant_id
    FROM
      contrat c
      JOIN volume_horaire vh ON vh.contrat_id = c.id AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
    WHERE
      (c.validation_id = :OLD.ID OR c.validation_id = :NEW.id)

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--F_TYPE_INTERVENTION_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_TYPE_INTERVENTION_S"
  AFTER UPDATE ON "OSE"."TYPE_INTERVENTION"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--F_TYPE_INTERVENTION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_TYPE_INTERVENTION"
  AFTER UPDATE OF TAUX_HETD_SERVICE, TAUX_HETD_COMPLEMENTAIRE ON "OSE"."TYPE_INTERVENTION"
  REFERENCING FOR EACH ROW
  BEGIN

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      volume_horaire vh
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
    WHERE
      1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      AND (vh.type_intervention_id = :NEW.id OR vh.type_intervention_id = :OLD.id)

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );

  END LOOP;
END;
/
---------------------------
--Modifié TRIGGER
--F_STATUT_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_STATUT_INTERVENANT_S"
  AFTER UPDATE ON "OSE"."STATUT_INTERVENANT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--F_STATUT_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_STATUT_INTERVENANT"
  AFTER UPDATE ON "OSE"."STATUT_INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      fr.intervenant_id
    FROM
      intervenant i
      JOIN formule_resultat fr ON fr.intervenant_id = i.id
    WHERE
      (i.statut_id = :NEW.id OR i.statut_id = :OLD.id)
      AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );

  END LOOP;
END;
/
---------------------------
--Modifié TRIGGER
--F_SERVICE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_SERVICE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--F_SERVICE_REFERENTIEL_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_SERVICE_REFERENTIEL_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE_REFERENTIEL"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--F_SERVICE_REFERENTIEL
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_SERVICE_REFERENTIEL"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE_REFERENTIEL"
  REFERENCING FOR EACH ROW
  BEGIN

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF DELETING OR UPDATING THEN
    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id) );
  END IF;
  IF INSERTING OR UPDATING THEN
    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--F_SERVICE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_SERVICE"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE"
  REFERENCING FOR EACH ROW
  BEGIN

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF DELETING OR UPDATING THEN
    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id) );
  END IF;
  IF INSERTING OR UPDATING THEN
    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id) );
  END IF;
END;
/
---------------------------
--Modifié TRIGGER
--F_MOTIF_MODIFICATION_SERVICE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_MOTIF_MODIFICATION_SERVICE_S"
  AFTER DELETE OR UPDATE ON "OSE"."MOTIF_MODIFICATION_SERVICE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--F_MOTIF_MODIFICATION_SERVICE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_MOTIF_MODIFICATION_SERVICE"
  AFTER DELETE OR UPDATE ON "OSE"."MOTIF_MODIFICATION_SERVICE"
  REFERENCING FOR EACH ROW
  BEGIN

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      intervenant_id
    FROM
      modification_service_du msd
    WHERE
      1 = OSE_DIVERS.COMPRISE_ENTRE( msd.histo_creation, msd.histo_destruction )
      AND (msd.motif_id = :NEW.id OR msd.motif_id = :OLD.id)

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--F_MODULATEUR_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_MODULATEUR_S"
  AFTER DELETE OR UPDATE ON "OSE"."MODULATEUR"
  BEGIN
    UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--F_MODULATEUR
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_MODULATEUR"
  AFTER DELETE OR UPDATE ON "OSE"."MODULATEUR"
  REFERENCING FOR EACH ROW
  BEGIN

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
      JOIN element_modulateur em ON
        em.element_id   = s.element_pedagogique_id
        AND 1 = ose_divers.comprise_entre( em.histo_creation, em.histo_destruction )
    WHERE
      1 = OSE_DIVERS.COMPRISE_ENTRE( s.histo_creation, s.histo_destruction )
      AND (em.modulateur_id = :OLD.id OR em.modulateur_id = :NEW.id)

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );

  END LOOP;
END;
/
---------------------------
--Modifié TRIGGER
--F_MODIF_SERVICE_DU_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_MODIF_SERVICE_DU_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."MODIFICATION_SERVICE_DU"
  BEGIN
    UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--F_MODIF_SERVICE_DU
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_MODIF_SERVICE_DU"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."MODIFICATION_SERVICE_DU"
  REFERENCING FOR EACH ROW
  BEGIN

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF DELETING OR UPDATING THEN
    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id) );
  END IF;
  IF INSERTING OR UPDATING THEN
    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id) );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--F_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_INTERVENANT_S"
  AFTER UPDATE ON "OSE"."INTERVENANT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--F_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_INTERVENANT"
  AFTER UPDATE OF ID, DATE_NAISSANCE, STATUT_ID, STRUCTURE_ID, HISTO_CREATION, HISTO_DESTRUCTION, PREMIER_RECRUTEMENT, ANNEE_ID ON "OSE"."INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      fr.intervenant_id
    FROM
      formule_resultat fr
    WHERE
      fr.intervenant_id = :NEW.id OR fr.intervenant_id = :OLD.id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--F_ELEMENT_PEDAGOGIQUE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_ELEMENT_PEDAGOGIQUE_S"
  AFTER DELETE OR UPDATE ON "OSE"."ELEMENT_PEDAGOGIQUE"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--F_ELEMENT_PEDAGOGIQUE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_ELEMENT_PEDAGOGIQUE"
  AFTER DELETE OR UPDATE OF ID, STRUCTURE_ID, PERIODE_ID, TAUX_FI, TAUX_FC, TAUX_FA, TAUX_FOAD, FI, FC, FA, HISTO_CREATION, HISTO_DESTRUCTION, ANNEE_ID ON "OSE"."ELEMENT_PEDAGOGIQUE"
  REFERENCING FOR EACH ROW
  BEGIN

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN
    ( SELECT DISTINCT s.intervenant_id
    FROM service s
    WHERE (s.element_pedagogique_id = :NEW.id
    OR s.element_pedagogique_id     = :OLD.id)
    AND 1                           = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
    ) LOOP UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );
END LOOP;
END;
/
---------------------------
--Modifié TRIGGER
--F_ELEMENT_MODULATEUR_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_ELEMENT_MODULATEUR_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."ELEMENT_MODULATEUR"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--F_ELEMENT_MODULATEUR
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_ELEMENT_MODULATEUR"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."ELEMENT_MODULATEUR"
  REFERENCING FOR EACH ROW
  BEGIN

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
    WHERE
      1 = OSE_DIVERS.COMPRISE_ENTRE( s.histo_creation, s.histo_destruction )
      AND (s.element_pedagogique_id = :OLD.element_id OR s.element_pedagogique_id = :NEW.element_id)

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--F_CONTRAT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_CONTRAT_S"
  AFTER DELETE OR UPDATE ON "OSE"."CONTRAT"
  BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
/
---------------------------
--Modifié TRIGGER
--F_CONTRAT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_CONTRAT"
  AFTER DELETE OR UPDATE OF INTERVENANT_ID, STRUCTURE_ID, VALIDATION_ID, DATE_RETOUR_SIGNE, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."CONTRAT"
  REFERENCING FOR EACH ROW
  BEGIN

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      volume_horaire vh
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
    WHERE
      1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      AND (vh.contrat_id = :OLD.id OR vh.contrat_id = :NEW.id)

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );

  END LOOP;

END;
/
---------------------------
--Nouveau TRIGGER
--CHARGENS_MAJ_EFFECTIFS
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."CHARGENS_MAJ_EFFECTIFS"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SCENARIO_NOEUD_EFFECTIF"
  REFERENCING FOR EACH ROW
  BEGIN 
RETURN;
  return;
  IF NOT ose_chargens.ENABLE_TRIGGER_EFFECTIFS THEN RETURN; END IF;
  IF DELETING THEN
    ose_chargens.DEM_CALC_SUB_EFFECTIF( :OLD.scenario_noeud_id, :OLD.type_heures_id, :OLD.etape_id, 0 );
  ELSE
    ose_chargens.DEM_CALC_SUB_EFFECTIF( :NEW.scenario_noeud_id, :NEW.type_heures_id, :NEW.etape_id, :NEW.effectif );
  END IF;

END;
/


-- ********************************************************************* --
-- *          à faire APRÈS avoir mis à jour le code source            * --
-- ********************************************************************* --

Insert into OSE.TBL (TBL_NAME,TABLE_NAME,VIEW_NAME,SEQUENCE_NAME,CONSTRAINT_NAME,CUSTOM_CALCUL_PROC) values ('chargens_seuils_def','TBL_CHARGENS_SEUILS_DEF','V_TBL_CHARGENS_SEUILS_DEF',null,'TBL_CHARGENS_SEUILS_DEF__UN',null);
Insert into OSE.TBL (TBL_NAME,TABLE_NAME,VIEW_NAME,SEQUENCE_NAME,CONSTRAINT_NAME,CUSTOM_CALCUL_PROC) values ('noeud','TBL_NOEUD','V_TBL_NOEUD',null,'TBL_NOEUD__UN',null);
Insert into OSE.TBL (TBL_NAME,TABLE_NAME,VIEW_NAME,SEQUENCE_NAME,CONSTRAINT_NAME,CUSTOM_CALCUL_PROC) values ('agrement','TBL_AGREMENT','V_TBL_AGREMENT',null,'TBL_AGREMENT__UN',null);
Insert into OSE.TBL (TBL_NAME,TABLE_NAME,VIEW_NAME,SEQUENCE_NAME,CONSTRAINT_NAME,CUSTOM_CALCUL_PROC) values ('cloture_realise','TBL_CLOTURE_REALISE','V_TBL_CLOTURE_REALISE',null,'TBL_CLOTURE_REALISE__UN',null);
Insert into OSE.TBL (TBL_NAME,TABLE_NAME,VIEW_NAME,SEQUENCE_NAME,CONSTRAINT_NAME,CUSTOM_CALCUL_PROC) values ('contrat','TBL_CONTRAT','V_TBL_CONTRAT',null,'TBL_CONTRAT__UN',null);
Insert into OSE.TBL (TBL_NAME,TABLE_NAME,VIEW_NAME,SEQUENCE_NAME,CONSTRAINT_NAME,CUSTOM_CALCUL_PROC) values ('dmep_liquidation','TBL_DMEP_LIQUIDATION','V_TBL_DMEP_LIQUIDATION',null,'TBL_DMEP_LIQUIDATION__UN',null);
Insert into OSE.TBL (TBL_NAME,TABLE_NAME,VIEW_NAME,SEQUENCE_NAME,CONSTRAINT_NAME,CUSTOM_CALCUL_PROC) values ('dossier','TBL_DOSSIER','V_TBL_DOSSIER',null,'TBL_DOSSIER__UN',null);
Insert into OSE.TBL (TBL_NAME,TABLE_NAME,VIEW_NAME,SEQUENCE_NAME,CONSTRAINT_NAME,CUSTOM_CALCUL_PROC) values ('paiement','TBL_PAIEMENT','V_TBL_PAIEMENT',null,'TBL_PAIEMENT__UN',null);
Insert into OSE.TBL (TBL_NAME,TABLE_NAME,VIEW_NAME,SEQUENCE_NAME,CONSTRAINT_NAME,CUSTOM_CALCUL_PROC) values ('piece_jointe','TBL_PIECE_JOINTE','V_TBL_PIECE_JOINTE',null,'TBL_PIECE_JOINTE__UN',null);
Insert into OSE.TBL (TBL_NAME,TABLE_NAME,VIEW_NAME,SEQUENCE_NAME,CONSTRAINT_NAME,CUSTOM_CALCUL_PROC) values ('piece_jointe_demande','TBL_PIECE_JOINTE_DEMANDE','V_TBL_PIECE_JOINTE_DEMANDE',null,'TBL_PIECE_JOINTE_DEMANDE__UN',null);
Insert into OSE.TBL (TBL_NAME,TABLE_NAME,VIEW_NAME,SEQUENCE_NAME,CONSTRAINT_NAME,CUSTOM_CALCUL_PROC) values ('piece_jointe_fournie','TBL_PIECE_JOINTE_FOURNIE','V_TBL_PIECE_JOINTE_FOURNIE',null,'TBL_PIECE_JOINTE_FOURNIE__UN',null);
Insert into OSE.TBL (TBL_NAME,TABLE_NAME,VIEW_NAME,SEQUENCE_NAME,CONSTRAINT_NAME,CUSTOM_CALCUL_PROC) values ('service','TBL_SERVICE','V_TBL_SERVICE',null,'TBL_SERVICE__UN',null);
Insert into OSE.TBL (TBL_NAME,TABLE_NAME,VIEW_NAME,SEQUENCE_NAME,CONSTRAINT_NAME,CUSTOM_CALCUL_PROC) values ('service_referentiel','TBL_SERVICE_REFERENTIEL','V_TBL_SERVICE_REFERENTIEL',null,'TBL_SERVICE_REFERENTIEL__UN',null);
Insert into OSE.TBL (TBL_NAME,TABLE_NAME,VIEW_NAME,SEQUENCE_NAME,CONSTRAINT_NAME,CUSTOM_CALCUL_PROC) values ('service_saisie','TBL_SERVICE_SAISIE','V_TBL_SERVICE_SAISIE',null,'TBL_SERVICE_SAISIE__UN',null);
Insert into OSE.TBL (TBL_NAME,TABLE_NAME,VIEW_NAME,SEQUENCE_NAME,CONSTRAINT_NAME,CUSTOM_CALCUL_PROC) values ('validation_referentiel','TBL_VALIDATION_REFERENTIEL','V_TBL_VALIDATION_REFERENTIEL',null,'TBL_VALIDATION_REFERENTIEL__UN',null);
Insert into OSE.TBL (TBL_NAME,TABLE_NAME,VIEW_NAME,SEQUENCE_NAME,CONSTRAINT_NAME,CUSTOM_CALCUL_PROC) values ('workflow',null,null,null,null,'OSE_WORKFLOW.CALCULER_TBL');
Insert into OSE.TBL (TBL_NAME,TABLE_NAME,VIEW_NAME,SEQUENCE_NAME,CONSTRAINT_NAME,CUSTOM_CALCUL_PROC) values ('validation_enseignement','TBL_VALIDATION_ENSEIGNEMENT','V_TBL_VALIDATION_ENSEIGNEMENT',null,'TBL_VALIDATION_ENSEIGNEMENT_UN',null);
Insert into OSE.TBL (TBL_NAME,TABLE_NAME,VIEW_NAME,SEQUENCE_NAME,CONSTRAINT_NAME,CUSTOM_CALCUL_PROC) values ('formule',null,null,null,null,'OSE_FORMULE.CALCULER_TBL');

Insert into OSE.TBL_DEPS (TBL_NAME,TBL_DEP_NAME) values ('agrement','formule');
Insert into OSE.TBL_DEPS (TBL_NAME,TBL_DEP_NAME) values ('paiement','formule');
Insert into OSE.TBL_DEPS (TBL_NAME,TBL_DEP_NAME) values ('piece_jointe','piece_jointe_demande');
Insert into OSE.TBL_DEPS (TBL_NAME,TBL_DEP_NAME) values ('piece_jointe','piece_jointe_fournie');
Insert into OSE.TBL_DEPS (TBL_NAME,TBL_DEP_NAME) values ('workflow','agrement');
Insert into OSE.TBL_DEPS (TBL_NAME,TBL_DEP_NAME) values ('workflow','cloture_realise');
Insert into OSE.TBL_DEPS (TBL_NAME,TBL_DEP_NAME) values ('workflow','contrat');
Insert into OSE.TBL_DEPS (TBL_NAME,TBL_DEP_NAME) values ('workflow','dossier');
Insert into OSE.TBL_DEPS (TBL_NAME,TBL_DEP_NAME) values ('workflow','paiement');
Insert into OSE.TBL_DEPS (TBL_NAME,TBL_DEP_NAME) values ('workflow','piece_jointe');
Insert into OSE.TBL_DEPS (TBL_NAME,TBL_DEP_NAME) values ('workflow','service_saisie');
Insert into OSE.TBL_DEPS (TBL_NAME,TBL_DEP_NAME) values ('workflow','validation_enseignement');
Insert into OSE.TBL_DEPS (TBL_NAME,TBL_DEP_NAME) values ('workflow','validation_referentiel');


/* Suppressions */
drop table "OSE"."TMP_CALCUL";
drop table PACKAGE_DEPS;
drop table SYS_EXPORT_SCHEMA_01;

drop index AP_TBL_PAIEMENT_FRS_FK_IDX;
drop index MV_INTERVENANT_RECHERCHE_PK;
drop index MV_DOMAINE_FONCTIONNEL_PK;
drop index AP_TBL_PAIEMENT_FRSR_FK_IDX;
drop index TBL_VALIDATION_REFERENTIEL__UN;

drop trigger T_LIE_LIEN;
drop trigger T_LIE_LIEN_S;
drop trigger T_LIE_SCENARIO;
drop trigger T_LIE_SCENARIO_LIEN;
drop trigger T_LIE_SCENARIO_LIEN_S;
drop trigger T_LIE_SCENARIO_S;

drop package OSE_AGREMENT;
drop package OSE_CLOTURE_REALISE;
drop package OSE_CONTRAT;
drop package OSE_DOSSIER;
drop package OSE_PIECE_JOINTE;
drop package OSE_PIECE_JOINTE_DEMANDE;
drop package OSE_PIECE_JOINTE_FOURNIE;
drop package OSE_SERVICE_REFERENTIEL;
drop package OSE_SERVICE_SAISIE;
drop package OSE_VALIDATION_ENSEIGNEMENT;
drop package OSE_VALIDATION_REFERENTIEL;


BEGIN DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"'); END;
/
BEGIN OSE_FORMULE.CALCULER_TOUT; END;
/