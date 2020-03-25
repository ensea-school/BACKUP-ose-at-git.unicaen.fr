CREATE OR REPLACE PACKAGE "OSE_TEST" AS

  DEBUG_ENABLED BOOLEAN DEFAULT FALSE;

  -- SET SERVEROUTPUT ON

  PROCEDURE SHOW_SUCCES;

  PROCEDURE HIDE_SUCCES;

  PROCEDURE ECHO( MSG CLOB );

  PROCEDURE INIT;

  PROCEDURE SHOW_STATS;

  PROCEDURE DEBUT( TEST_NAME CLOB );

  PROCEDURE FIN;

  PROCEDURE ASSERT( condition BOOLEAN, MSG CLOB );

  PROCEDURE HOROINIT;

  PROCEDURE HORODATAGE( msg VARCHAR2 );

  FUNCTION GET_STRUCTURE_BY_ID( id NUMERIC ) RETURN structure%rowtype;

END OSE_TEST;