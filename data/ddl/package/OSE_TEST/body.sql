CREATE OR REPLACE PACKAGE BODY "OSE_TEST" AS
  TYPE OUT_LIST IS TABLE OF CLOB;
  HTS TIMESTAMP;

  SUCCES_SHOWN BOOLEAN DEFAULT TRUE;
  T_SUCCES_COUNT NUMERIC DEFAULT 0;
  T_ECHECS_COUNT NUMERIC DEFAULT 0;
  A_SUCCES_COUNT NUMERIC DEFAULT 0;
  A_ECHECS_COUNT NUMERIC DEFAULT 0;
  CURRENT_TEST CLOB;
  CURRENT_TEST_OUTPUT_BUFFER OUT_LIST := OUT_LIST();
  CURRENT_TEST_OUTPUT_BUFFER_ERR BOOLEAN;

  PROCEDURE SHOW_SUCCES IS
  BEGIN
    SUCCES_SHOWN := true;
  END SHOW_SUCCES;

  PROCEDURE HIDE_SUCCES IS
  BEGIN
    SUCCES_SHOWN := false;
  END HIDE_SUCCES;

  PROCEDURE DEBUT( TEST_NAME CLOB ) IS
  BEGIN
    CURRENT_TEST := TEST_NAME;
    CURRENT_TEST_OUTPUT_BUFFER_ERR := FALSE;
    echo (' '); echo('TEST ' || TEST_NAME || ' >>>>>>>>>>' );
  END;

  PROCEDURE FIN IS
    TEST_NAME CLOB;
  BEGIN
    IF CURRENT_TEST_OUTPUT_BUFFER_ERR THEN
      T_ECHECS_COUNT := T_ECHECS_COUNT + 1;
      echo('>>>>>>>>>> FIN DU TEST ' || CURRENT_TEST ); echo (' ');
      CURRENT_TEST := NULL;

      FOR i IN 1 .. CURRENT_TEST_OUTPUT_BUFFER.COUNT LOOP
        echo( CURRENT_TEST_OUTPUT_BUFFER(i) );
      END LOOP;
    ELSE
      T_SUCCES_COUNT := T_SUCCES_COUNT + 1;
      TEST_NAME := CURRENT_TEST;
      CURRENT_TEST := NULL;
      echo('SUCCÈS DU TEST : ' || TEST_NAME );
    END IF;
    CURRENT_TEST_OUTPUT_BUFFER.DELETE; -- clear buffer
  END;

  PROCEDURE ECHO( MSG CLOB ) IS
  BEGIN
    IF CURRENT_TEST IS NULL THEN
      dbms_output.put_line(MSG);
    ELSE
      CURRENT_TEST_OUTPUT_BUFFER.EXTEND;
      CURRENT_TEST_OUTPUT_BUFFER (CURRENT_TEST_OUTPUT_BUFFER.LAST) := MSG;
    END IF;
  END;

  PROCEDURE INIT IS
  BEGIN
    T_SUCCES_COUNT  := 0;
    T_ECHECS_COUNT  := 0;
    A_SUCCES_COUNT  := 0;
    A_ECHECS_COUNT  := 0;
    CURRENT_TEST    := NULL;
  END INIT;

  PROCEDURE SHOW_STATS IS
  BEGIN
    echo ( ' ' );
    echo ( '********************************* STATISTIQUES *********************************' );
    echo ( ' ' );
    echo ( '   - nombre de tests passés avec succès :       ' || T_SUCCES_COUNT );
    echo ( '   - nombre de tests ayant échoué :             ' || T_ECHECS_COUNT );
    echo ( ' ' );
    echo ( '   - nombre d''assertions passés avec succès :   ' || A_SUCCES_COUNT );
    echo ( '   - nombre d''assertions ayant échoué :         ' || A_ECHECS_COUNT );
    echo ( ' ' );
    echo ( '********************************************************************************' );
    echo ( ' ' );
  END;

  PROCEDURE ASSERT( condition BOOLEAN, MSG CLOB ) IS
  BEGIN
    IF condition THEN
      A_SUCCES_COUNT := A_SUCCES_COUNT + 1;
      IF SUCCES_SHOWN THEN
        ECHO('        SUCCÈS : ' || MSG );
      END IF;
    ELSE
      A_ECHECS_COUNT := A_ECHECS_COUNT + 1;
      CURRENT_TEST_OUTPUT_BUFFER_ERR := TRUE;
      ECHO('        ** ECHEC ** : ' || MSG );
    END IF;
  END;

  PROCEDURE HOROINIT IS
  BEGIN
    HTS := systimestamp;
  END;

  PROCEDURE HORODATAGE( msg VARCHAR2 ) IS
    diff INTERVAL DAY(9) TO SECOND(3);
  BEGIN
    IF HTS IS NULL THEN
      HTS := systimestamp;
      RETURN;
    END IF;

    diff := systimestamp - HTS;
    HTS := systimestamp;

    echo(msg || ' (' || diff || ')');
  END;

  FUNCTION GET_STRUCTURE_BY_ID( id NUMERIC ) RETURN structure%rowtype IS
    res structure%rowtype;
  BEGIN
    IF ID IS NULL THEN RETURN res; END IF;
    SELECT * INTO res FROM structure WHERE id = GET_STRUCTURE_BY_ID.id;
    RETURN res;
  END;

END OSE_TEST;