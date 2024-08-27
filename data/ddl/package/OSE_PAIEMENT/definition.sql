CREATE OR REPLACE PACKAGE "OSE_PAIEMENT" AS

  PROCEDURE set_mois_extraction_paie(mois_extraction_paie VARCHAR2);

  PROCEDURE set_annee_extraction_paie(annee_extraction_paie VARCHAR2);

  FUNCTION get_mois_extraction_paie RETURN VARCHAR2;

  FUNCTION get_annee_extraction_paie RETURN VARCHAR2;

  FUNCTION get_format_mois_du RETURN VARCHAR2;

  FUNCTION get_taux_horaire(id_in IN NUMBER, date_val IN DATE) RETURN FLOAT;

  FUNCTION get_taux_horaire_date(id_in IN NUMBER, date_val IN DATE) RETURN DATE;

END ose_paiement;