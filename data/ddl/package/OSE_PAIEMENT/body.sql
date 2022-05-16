CREATE
    OR REPLACE PACKAGE BODY "OSE_PAIEMENT" AS

    mois_extraction_paie VARCHAR2(50) := '01';
    annee_extraction_paie VARCHAR2(50) := '22';

    PROCEDURE check_bad_paiements(formule_res_service_id NUMERIC DEFAULT NULL,
                                  formule_res_service_ref_id NUMERIC DEFAULT NULL)
        IS
        cc NUMERIC;
    BEGIN
        SELECT COUNT(*)
        INTO cc
        FROM mise_en_paiement mep
        WHERE mep.histo_destruction IS NULL
          AND mep.formule_res_service_id = NVL(check_bad_paiements.formule_res_service_id, mep.formule_res_service_id)
          AND mep.formule_res_service_ref_id =
              NVL(check_bad_paiements.formule_res_service_ref_id, mep.formule_res_service_ref_id);

        IF
            (cc > 0) THEN
            RAISE_APPLICATION_ERROR(-20101,
                                    'Il est impossible d''effectuer cette action : des demandes de mise en paiement ont été saisies et ne peuvent pas être modifiées');
        ELSE
            DELETE
            FROM mise_en_paiement
            WHERE histo_destruction IS NOT NULL
              AND formule_res_service_id = NVL(check_bad_paiements.formule_res_service_id, formule_res_service_id)
              AND formule_res_service_ref_id =
                  NVL(check_bad_paiements.formule_res_service_ref_id, formule_res_service_ref_id);
        END IF;
    END;

    PROCEDURE set_mois_extraction_paie(mois_extraction_paie VARCHAR2)
        IS
    BEGIN
        ose_paiement.mois_extraction_paie
            := mois_extraction_paie;
    END;

    PROCEDURE set_annee_extraction_paie(annee_extraction_paie VARCHAR2)
        IS
    BEGIN
        ose_paiement.annee_extraction_paie
            := annee_extraction_paie;
    END;

    FUNCTION
        get_annee_extraction_paie RETURN VARCHAR2 IS
    BEGIN
        RETURN ose_paiement.annee_extraction_paie;
    END;

    FUNCTION
        get_mois_extraction_paie RETURN VARCHAR2 IS
    BEGIN
        RETURN ose_paiement.mois_extraction_paie;
    END;

    FUNCTION
        get_format_mois_du RETURN VARCHAR2 IS
    BEGIN
        RETURN '20' || ose_paiement.annee_extraction_paie || '-' || ose_paiement.mois_extraction_paie;
    END;


END ose_paiement;