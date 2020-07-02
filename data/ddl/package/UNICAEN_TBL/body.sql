CREATE OR REPLACE PACKAGE BODY "UNICAEN_TBL" AS

  FUNCTION MAKE_WHERE(param VARCHAR2 DEFAULT NULL, value VARCHAR2 DEFAULT NULL, alias VARCHAR2 DEFAULT NULL) RETURN VARCHAR2 IS
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



  FUNCTION QUERY_APPLY_PARAM( sqlQuery VARCHAR2, param VARCHAR2, value VARCHAR2) RETURN CLOB IS
    pos NUMERIC;
    paramLen NUMERIC;
    paramComm VARCHAR2(200);
    debComm NUMERIC;
    endComm NUMERIC;
    debReal NUMERIC;
    realParam VARCHAR2(80);
    realValue VARCHAR2(120);
    q CLOB;
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
      pos := instr(q,'/*@' || param,1,1);
      EXIT WHEN pos = 0;

      debComm := pos-1;
      endComm := instr(q,'*/',pos,1);
      paramComm := substr(q,debComm, endComm-debComm);

      debReal := instr(paramComm,'=',1,1);

      realParam := trim(substr(paramComm,debReal+1));

      --realParam := 'AND ' || substr(q,pos + paramLen + 4,endComm-pos - paramLen - 4);
      realParam := 'AND ' || realParam || realValue;


      q := substr(q,1,debComm) || realParam || substr(q,endComm+2);
    END LOOP;
    RETURN q;
  END;



  PROCEDURE CALCULER( TBL_NAME VARCHAR2 ) IS
  BEGIN
    ANNULER_DEMANDES( TBL_NAME );
    CALCULER(TBL_NAME, null, null);
  END;



  PROCEDURE CALCULER( TBL_NAME VARCHAR2, param VARCHAR2, value VARCHAR2 ) IS
    calcul_proc varchar2(30);
  BEGIN
    IF NOT UNICAEN_TBL.ACTIV_CALCULS THEN RETURN; END IF;

    SELECT custom_calcul_proc INTO calcul_proc FROM tbl WHERE tbl_name = CALCULER.TBL_NAME;

    UNICAEN_TBL.CALCUL_PROC_PARAM := PARAM;
    UNICAEN_TBL.CALCUL_PROC_VALUE := VALUE;
    IF calcul_proc IS NOT NULL THEN
      EXECUTE IMMEDIATE
        'BEGIN ' || calcul_proc || '(UNICAEN_TBL.CALCUL_PROC_PARAM,UNICAEN_TBL.CALCUL_PROC_VALUE); END;'
      ;
    ELSE
      EXECUTE IMMEDIATE
        'BEGIN UNICAEN_TBL.C_' || TBL_NAME || '(UNICAEN_TBL.CALCUL_PROC_PARAM,UNICAEN_TBL.CALCUL_PROC_VALUE); END;'
      ;
    END IF;

  END;



  PROCEDURE DEMANDE_CALCUL( TBL_NAME VARCHAR2, param VARCHAR2, value VARCHAR2 ) IS
  BEGIN
    INSERT INTO tbl_dems (
      ID,
      TBL_NAME,
      PARAM, VALUE
    ) VALUES (
      TBL_DEMS_ID_SEQ.NEXTVAL,
      TBL_NAME,
      PARAM, VALUE
    );
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
  BEGIN
    FOR d IN (
      SELECT DISTINCT tbl_name, param, value FROM tbl_dems
    ) LOOP
      calculer( d.tbl_name, d.param, d.value );
    END LOOP;

    IF HAS_DEMANDES THEN -- pour les boucles !!
      CALCULER_DEMANDES;
    END IF;
  END;



  -- AUTOMATIC GENERATION --

  -- END OF AUTOMATIC GENERATION --

END UNICAEN_TBL;