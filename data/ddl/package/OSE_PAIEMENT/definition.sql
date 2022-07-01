CREATE OR REPLACE PACKAGE "OSE_PAIEMENT" AS

  PROCEDURE CHECK_BAD_PAIEMENTS( FORMULE_RES_SERVICE_ID NUMERIC DEFAULT NULL, FORMULE_RES_SERVICE_REF_ID NUMERIC DEFAULT NULL );

  PROCEDURE set_mois_extraction_paie(mois_extraction_paie VARCHAR2);

  PROCEDURE set_annee_extraction_paie(annee_extraction_paie VARCHAR2);

  FUNCTION get_mois_extraction_paie RETURN VARCHAR2;

  FUNCTION get_annee_extraction_paie RETURN VARCHAR2;

  FUNCTION get_format_mois_du RETURN VARCHAR2;

END ose_paiement;