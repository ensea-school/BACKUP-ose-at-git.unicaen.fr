CREATE OR REPLACE PACKAGE BODY "OSE_PARAMETRE" AS

  oseuser NUMERIC;
  annee NUMERIC;

  annee_import NUMERIC;
  etablissement VARCHAR2(50);
  regle_paiement_annee_civile VARCHAR2(50);
  pourc_s1_pour_annee_civile FLOAT;
  taux_conges_payes FLOAT;



  FUNCTION get_etablissement return Numeric AS
    etab_id numeric;
  BEGIN
    IF etablissement IS NULL THEN
      select to_number(valeur) into etablissement from parametre where nom = 'etablissement';
    END IF;

    RETURN etablissement;
  END get_etablissement;



  FUNCTION get_annee return Numeric AS
  BEGIN
    IF annee IS NULL THEN
      SELECT to_number(valeur) into annee from parametre where nom = 'annee';
    END IF;

    RETURN annee;
  END get_annee;



  FUNCTION get_annee_import RETURN NUMERIC AS
    annee_id NUMERIC;
  BEGIN
    IF annee_import IS NULL THEN
      SELECT to_number(valeur) INTO annee_import FROM parametre WHERE nom = 'annee_import';
    END IF;

    RETURN annee_import;
  END get_annee_import;



  FUNCTION get_ose_user return NUMERIC AS
  BEGIN
    IF oseuser IS NULL THEN
      SELECT to_number(valeur) into oseuser from parametre where nom = 'oseuser';
    END IF;

    RETURN oseuser;
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



  FUNCTION get_pourc_s1_annee_civ RETURN FLOAT IS
  BEGIN
    IF pourc_s1_pour_annee_civile IS NULL THEN
      SELECT TO_NUMBER(valeur, '999999999D999999999', 'NLS_NUMERIC_CHARACTERS =.,') INTO pourc_s1_pour_annee_civile FROM parametre WHERE nom = 'pourc_s1_pour_annee_civile';
    END IF;

    RETURN pourc_s1_pour_annee_civile;
  END;



  FUNCTION get_taux_conges_payes RETURN FLOAT IS
  BEGIN
    IF taux_conges_payes IS NULL THEN
      SELECT COALESCE(TO_NUMBER(valeur, '999999999D999999999', 'NLS_NUMERIC_CHARACTERS =.,'),1) INTO taux_conges_payes FROM parametre WHERE nom = 'taux_conges_payes';
    END IF;

    RETURN taux_conges_payes;
  END;


  PROCEDURE CLEAR_CACHE IS
  BEGIN
    annee := NULL;
    oseuser := NULL;
    regle_paiement_annee_civile := NULL;
    pourc_s1_pour_annee_civile := NULL;
  END;

END OSE_PARAMETRE;