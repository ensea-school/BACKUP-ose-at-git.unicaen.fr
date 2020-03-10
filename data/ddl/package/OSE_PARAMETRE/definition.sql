CREATE OR REPLACE PACKAGE "OSE_PARAMETRE" AS

  function get_etablissement return Numeric;
  function get_annee return Numeric;
  function get_annee_import return Numeric;
  function get_ose_user return Numeric;
  function get_formule RETURN formule%rowtype;

END OSE_PARAMETRE;