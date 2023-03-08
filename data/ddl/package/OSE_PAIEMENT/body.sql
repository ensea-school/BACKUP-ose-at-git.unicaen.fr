CREATE OR REPLACE PACKAGE BODY "OSE_PAIEMENT" AS

  code_taux_remu_legal 	CONSTANT VARCHAR2(3) := 'TLD';

  mois_extraction_paie VARCHAR2(50) := '01';
  annee_extraction_paie VARCHAR2(50) := '22';

  PROCEDURE check_bad_paiements(formule_res_service_id NUMERIC DEFAULT NULL, formule_res_service_ref_id NUMERIC DEFAULT NULL) IS
    cc NUMERIC;
  BEGIN
    SELECT COUNT(*)
    INTO cc
    FROM mise_en_paiement mep
    WHERE mep.histo_destruction IS NULL
      AND mep.formule_res_service_id = COALESCE(check_bad_paiements.formule_res_service_id, mep.formule_res_service_id)
      AND mep.formule_res_service_ref_id = COALESCE(check_bad_paiements.formule_res_service_ref_id, mep.formule_res_service_ref_id);

    IF (cc > 0) THEN
      RAISE_APPLICATION_ERROR(-20101, 'Il est impossible d''effectuer cette action : des demandes de mise en paiement ont été saisies et ne peuvent pas être modifiées');
    ELSE
      DELETE FROM
        mise_en_paiement
      WHERE
        histo_destruction IS NOT NULL
        AND formule_res_service_id = COALESCE(check_bad_paiements.formule_res_service_id, formule_res_service_id)
        AND formule_res_service_ref_id = COALESCE(check_bad_paiements.formule_res_service_ref_id, formule_res_service_ref_id);
    END IF;
  END;



  PROCEDURE set_mois_extraction_paie(mois_extraction_paie VARCHAR2) IS
  BEGIN
    ose_paiement.mois_extraction_paie := mois_extraction_paie;
  END;



  PROCEDURE set_annee_extraction_paie(annee_extraction_paie VARCHAR2) IS
  BEGIN
    ose_paiement.annee_extraction_paie := annee_extraction_paie;
  END;



  FUNCTION get_annee_extraction_paie RETURN VARCHAR2 IS
  BEGIN
    RETURN ose_paiement.annee_extraction_paie;
  END;



  FUNCTION get_mois_extraction_paie RETURN VARCHAR2 IS
  BEGIN
    RETURN ose_paiement.mois_extraction_paie;
  END;



  FUNCTION get_format_mois_du RETURN VARCHAR2 IS
  BEGIN
    RETURN '20' || ose_paiement.annee_extraction_paie || '-' || ose_paiement.mois_extraction_paie;
  END;

  Function get_taux_horaire (id_in IN NUMBER, date_val IN DATE) RETURN float IS
    valeur float;
    valeur_parent float;
  BEGIN

    SELECT valeur into valeur FROM
    (
    SELECT trv.valeur
    FROM taux_remu tr
    JOIN taux_remu_valeur trv ON tr.id = trv.taux_remu_id
    WHERE tr.id = id_in
    AND tr.histo_destruction IS NULL
    AND trv.date_effet <= date_val
    ORDER BY trv.date_effet DESC
    )
    WHERE rownum = 1;


    SELECT(
        SELECT valeur FROM
        (
            SELECT trv.valeur
            FROM taux_remu tr
            JOIN taux_remu_valeur trv ON tr.id = trv.taux_remu_id
            WHERE tr.id IN
            (
                SELECT tr.taux_remu_id
                FROM taux_remu tr
                WHERE tr.id = id_in
            )
            AND tr.histo_destruction IS NULL
            AND trv.date_effet <= date_val
            ORDER BY trv.date_effet DESC
        )
        WHERE rownum = 1
    ) into valeur_parent
    FROM dual;

    IF valeur_parent is NULL
    THEN
        RETURN valeur;
    ELSE
        RETURN valeur*valeur_parent;
    END IF;


    EXCEPTION
    WHEN OTHERS THEN
       return -1;
  END get_taux_horaire;

  Function get_taux_horaire_date (id_in IN NUMBER, date_val IN DATE) RETURN DATE IS
    date_valeur DATE;
    date_parent DATE;
  BEGIN
    SELECT date_effet into date_valeur FROM
    (
    SELECT trv.date_effet
    FROM taux_remu tr
    JOIN taux_remu_valeur trv ON tr.id = trv.taux_remu_id
    WHERE tr.id = id_in
    AND tr.histo_destruction IS NULL
    AND trv.date_effet <= date_val
    ORDER BY trv.date_effet DESC
    )
    WHERE rownum = 1;


    SELECT(
        SELECT date_effet FROM
        (
            SELECT trv.date_effet
            FROM taux_remu tr
            JOIN taux_remu_valeur trv ON tr.id = trv.taux_remu_id
            WHERE tr.id IN
            (
                SELECT tr.taux_remu_id
                FROM taux_remu tr
                WHERE tr.id = id_in
            )
            AND tr.histo_destruction IS NULL
            AND trv.date_effet <= date_val
            ORDER BY trv.date_effet DESC
        )
        WHERE rownum = 1
    ) into date_parent
    FROM dual;

        IF date_parent is NULL
    THEN
        RETURN date_valeur;
    ELSE
        RETURN GREATEST(date_valeur, date_parent);
    END IF;

    EXCEPTION
    WHEN OTHERS THEN
       return '00/00/0000';
  END get_taux_horaire_date;




  FUNCTION get_code_taux_remu_legal RETURN VARCHAR2 IS
  BEGIN
    RETURN code_taux_remu_legal;
  END;

END ose_paiement;