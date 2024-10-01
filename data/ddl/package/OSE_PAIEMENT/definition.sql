CREATE OR REPLACE PACKAGE "OSE_PAIEMENT" AS

  FUNCTION get_taux_horaire(id_in IN NUMBER, date_val IN DATE) RETURN FLOAT;

  FUNCTION get_taux_horaire_date(id_in IN NUMBER, date_val IN DATE) RETURN DATE;

END ose_paiement;