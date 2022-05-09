CREATE OR REPLACE PACKAGE BODY     "OSE_PAIEMENT" AS

  MOIS_EXTRACTION_PAIE NUMERIC DEFAULT 1;

  PROCEDURE CHECK_BAD_PAIEMENTS( FORMULE_RES_SERVICE_ID NUMERIC DEFAULT NULL, FORMULE_RES_SERVICE_REF_ID NUMERIC DEFAULT NULL ) IS
    cc NUMERIC;
  BEGIN
    SELECT count(*) INTO cc
    FROM mise_en_paiement mep
    WHERE
      mep.histo_destruction IS NULL
      AND mep.formule_res_service_id = NVL( CHECK_BAD_PAIEMENTS.FORMULE_RES_SERVICE_ID, mep.formule_res_service_id )
      AND mep.formule_res_service_ref_id = NVL( CHECK_BAD_PAIEMENTS.FORMULE_RES_SERVICE_REF_ID, mep.formule_res_service_ref_id )
  ;

    IF (cc > 0) THEN
      raise_application_error(-20101, 'Il est impossible d''effectuer cette action : des demandes de mise en paiement ont été saisies et ne peuvent pas être modifiées');
    ELSE
      DELETE FROM mise_en_paiement WHERE
        histo_destruction IS NOT NULL
        AND formule_res_service_id = NVL( CHECK_BAD_PAIEMENTS.FORMULE_RES_SERVICE_ID, formule_res_service_id )
        AND formule_res_service_ref_id = NVL( CHECK_BAD_PAIEMENTS.FORMULE_RES_SERVICE_REF_ID, formule_res_service_ref_id )
      ;
    END IF;
  END;

  PROCEDURE SET_MOIS_EXTRACTION_PAIE( MOIS_EXTRACTION_PAIE NUMERIC) IS
  	BEGIN
		OSE_PAIEMENT.MOIS_EXTRACTION_PAIE := MOIS_EXTRACTION_PAIE;
	END;

  FUNCTION GET_MOIS_EXTRACTION_PAIE RETURN NUMERIC IS
  	BEGIN
		RETURN OSE_PAIEMENT.MOIS_EXTRACTION_PAIE;
	END;


END OSE_PAIEMENT;