CREATE OR REPLACE PACKAGE BODY "OSE_PARAMETRE" AS

  cache_ose_user NUMERIC;
  cache_annee_id NUMERIC;
  regle_paiement_annee_civile VARCHAR2(50);
  pourc_s1_pour_annee_civile FLOAT;



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



  FUNCTION get_regle_paiement_annee_civ RETURN VARCHAR2 IS
  BEGIN
    IF regle_paiement_annee_civile IS NULL THEN
      SELECT valeur INTO regle_paiement_annee_civile FROM parametre WHERE nom = 'regle_paiement_annee_civile';
    END IF;

    RETURN regle_paiement_annee_civile;
  END;



  FUNCTION get_pourc_s1_annee_civ RETURN VARCHAR2 IS
  BEGIN
    IF pourc_s1_pour_annee_civile IS NULL THEN
      SELECT TO_NUMBER(valeur, '999999999D999999999', 'NLS_NUMERIC_CHARACTERS =.,') INTO pourc_s1_pour_annee_civile FROM parametre WHERE nom = 'pourc_s1_pour_annee_civile';
    END IF;

    RETURN pourc_s1_pour_annee_civile;
  END;

END OSE_PARAMETRE;