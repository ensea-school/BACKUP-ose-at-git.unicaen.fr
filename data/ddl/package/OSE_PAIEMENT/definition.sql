CREATE
    OR REPLACE PACKAGE "OSE_PAIEMENT" AS

    PROCEDURE check_bad_paiements(formule_res_service_id NUMERIC DEFAULT NULL,
                                  formule_res_service_ref_id NUMERIC DEFAULT NULL);

    PROCEDURE set_mois_extraction_paie(mois_extraction_paie VARCHAR2);

    PROCEDURE set_annee_extraction_paie(annee_extraction_paie VARCHAR2);

    FUNCTION get_mois_extraction_paie RETURN VARCHAR2;

    FUNCTION get_annee_extraction_paie RETURN VARCHAR2;

    FUNCTION get_format_mois_du RETURN VARCHAR2;

END ose_paiement;