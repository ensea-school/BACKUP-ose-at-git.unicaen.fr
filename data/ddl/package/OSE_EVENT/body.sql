CREATE OR REPLACE PACKAGE BODY "OSE_EVENT" AS

  PROCEDURE ON_AFTER_FORMULE_CALC( INTERVENANT_ID NUMERIC ) IS
    p unicaen_tbl.t_params;
  BEGIN
    p := UNICAEN_TBL.make_params('INTERVENANT_ID', ON_AFTER_FORMULE_CALC.intervenant_id);
  END;

END OSE_EVENT;