CREATE OR REPLACE PACKAGE BODY "UNICAEN_IMPORT" AS

  v_current_user INTEGER;
  v_current_annee INTEGER;



  FUNCTION get_current_user RETURN INTEGER IS
  BEGIN
    IF v_current_user IS NULL THEN
      v_current_user := OSE_PARAMETRE.GET_OSE_USER();
    END IF;
    RETURN v_current_user;
  END get_current_user;

  PROCEDURE set_current_user (p_current_user INTEGER) is
  BEGIN
    v_current_user := p_current_user;
  END set_current_user;



  FUNCTION get_current_annee RETURN INTEGER IS
  BEGIN
    IF v_current_annee IS NULL THEN
      v_current_annee := OSE_PARAMETRE.GET_ANNEE_IMPORT();
    END IF;
    RETURN v_current_annee;
  END get_current_annee;

  PROCEDURE set_current_annee (p_current_annee INTEGER) IS
  BEGIN
    v_current_annee := p_current_annee;
  END set_current_annee;



  PROCEDURE SYNCHRONISATION( table_name VARCHAR2, SYNC_FILRE CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '', FORCE_SYNC BOOLEAN DEFAULT FALSE ) IS
    ok NUMERIC(1);
    sync NUMERIC;
  BEGIN
    IF FORCE_SYNC THEN
      sync := 1;
    ELSE
      sync := 0;
    END IF;

    SELECT COUNT(*) INTO ok FROM import_tables it JOIN all_procedures p ON p.object_name = 'UNICAEN_IMPORT_AUTOGEN_PROCS__' AND p.procedure_name = it.table_name WHERE it.table_name = SYNCHRONISATION.table_name AND (it.sync_enabled = 1 OR sync=1) AND rownum = 1;

    IF 1 = ok THEN
      z__SYNC_FILRE__z      := SYNCHRONISATION.SYNC_FILRE;
      z__IGNORE_UPD_COLS__z := SYNCHRONISATION.IGNORE_UPD_COLS;
      EXECUTE IMMEDIATE 'BEGIN UNICAEN_IMPORT_AUTOGEN_PROCS__.' || table_name || '(); END;';
    END IF;
  END;



  PROCEDURE REFRESH_MV( mview_name varchar2 ) IS
  BEGIN
    DBMS_MVIEW.REFRESH(mview_name, 'C');
  EXCEPTION WHEN OTHERS THEN
    SYNC_LOG( SQLERRM, mview_name );
  END;



  PROCEDURE SYNC_LOG( message CLOB, table_name VARCHAR2 DEFAULT NULL, source_code VARCHAR2 DEFAULT NULL ) IS
  BEGIN
    INSERT INTO SYNC_LOG("ID","DATE_SYNC","MESSAGE","TABLE_NAME","SOURCE_CODE") VALUES (SYNC_LOG_ID_SEQ.NEXTVAL, SYSDATE, message,table_name,source_code);
  END SYNC_LOG;



  FUNCTION IN_COLUMN_LIST( VALEUR VARCHAR2, CHAMPS CLOB ) RETURN NUMERIC IS
  BEGIN
    IF REGEXP_LIKE(CHAMPS, '(^|,)[ \t\r\n\v\f]*' || VALEUR || '[ \t\r\n\v\f]*(,|$)') THEN RETURN 1; END IF;
    RETURN 0;
  END;


  PROCEDURE ADD_SOURCE( code VARCHAR2, libelle VARCHAR2, importable BOOLEAN DEFAULT TRUE ) IS
    sc NUMERIC;
    imnp NUMERIC;
  BEGIN
    SELECT count(*) into sc FROM source WHERE code = ADD_SOURCE.code;
    IF sc = 0 THEN
      IF importable THEN imnp := 1; ELSE imnp := 0; END IF;

      INSERT INTO SOURCE(
        ID, CODE, LIBELLE, IMPORTABLE
      ) VALUES (
        SOURCE_ID_SEQ.NEXTVAL, add_source.code, add_source.libelle, imnp
      );
    END IF;
  END;

END UNICAEN_IMPORT;