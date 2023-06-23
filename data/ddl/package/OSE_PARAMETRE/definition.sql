CREATE OR REPLACE PACKAGE "OSE_PARAMETRE" AS

  FUNCTION get_etablissement RETURN Numeric;
  FUNCTION get_annee RETURN Numeric;
  FUNCTION get_annee_import RETURN Numeric;
  FUNCTION get_ose_user RETURN Numeric;
  FUNCTION get_formule RETURN formule%rowtype;
  FUNCTION get_regle_paiement_annee_civ RETURN VARCHAR2;
  FUNCTION get_pourc_s1_annee_civ RETURN FLOAT;
  FUNCTION get_taux_conges_payes RETURN FLOAT;
  FUNCTION get_horaire_nocturne RETURN VARCHAR2;

  PROCEDURE CLEAR_CACHE;

END OSE_PARAMETRE;