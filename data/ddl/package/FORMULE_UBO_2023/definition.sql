CREATE OR REPLACE PACKAGE "FORMULE_UBO_2023" AS

  PROCEDURE CALCUL_RESULTAT;

  FUNCTION calcCell( c VARCHAR2, l NUMERIC ) RETURN FLOAT;

END FORMULE_UBO_2023;