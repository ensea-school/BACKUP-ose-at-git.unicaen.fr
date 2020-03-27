CREATE OR REPLACE PACKAGE BODY "OSE_PARAMETRE" AS

  cache_ose_user NUMERIC;
  cache_annee_id NUMERIC;

  FUNCTION get_etablissement return Numeric AS
    etab_id numeric;
  BEGIN
    select to_number(valeur) into etab_id from parametre where nom = 'etablissement';
    RETURN etab_id;
  END get_etablissement;

  FUNCTION get_annee return Numeric AS
    annee_id numeric;
  BEGIN
    IF cache_annee_id IS NOT NULL THEN RETURN cache_annee_id; END IF;
    select to_number(valeur) into annee_id from parametre where nom = 'annee';
    cache_annee_id := annee_id;
    RETURN cache_annee_id;
  END get_annee;

  FUNCTION get_annee_import RETURN NUMERIC AS
    annee_id NUMERIC;
  BEGIN
    SELECT to_number(valeur) INTO annee_id FROM parametre WHERE nom = 'annee_import';
    RETURN annee_id;
  END get_annee_import;

  FUNCTION get_ose_user return NUMERIC AS
    ose_user_id numeric;
  BEGIN
    IF cache_ose_user IS NOT NULL THEN RETURN cache_ose_user; END IF;
    select to_number(valeur) into ose_user_id from parametre where nom = 'oseuser';
    cache_ose_user := ose_user_id;
    RETURN cache_ose_user;
  END get_ose_user;

  FUNCTION get_formule RETURN formule%rowtype IS
    fdata formule%rowtype;
  BEGIN
    SELECT
      f.* INTO fdata
    FROM
      formule f
      JOIN parametre p ON f.id = to_number(p.valeur)
    WHERE p.nom = 'formule';
    RETURN fdata;
  END;

END OSE_PARAMETRE;