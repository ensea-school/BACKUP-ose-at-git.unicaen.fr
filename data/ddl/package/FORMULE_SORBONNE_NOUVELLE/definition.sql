CREATE OR REPLACE PACKAGE FORMULE_SORBONNE_NOUVELLE AS

  PROCEDURE CALCUL_RESULTAT;

  FUNCTION calcCell( c VARCHAR2, l NUMERIC ) RETURN FLOAT;

  FUNCTION INTERVENANT_QUERY RETURN CLOB;
  FUNCTION VOLUME_HORAIRE_QUERY RETURN CLOB;

END FORMULE_SORBONNE_NOUVELLE;