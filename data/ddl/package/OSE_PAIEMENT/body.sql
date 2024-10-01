CREATE OR REPLACE PACKAGE BODY "OSE_PAIEMENT" AS

  FUNCTION get_taux_horaire (id_in IN NUMBER, date_val IN DATE) RETURN FLOAT IS
    valeur FLOAT;
    valeur_parent FLOAT;
  BEGIN

    SELECT(
        SELECT valeur FROM
        (
            SELECT trv.valeur
            FROM taux_remu tr
            JOIN taux_remu_valeur trv ON tr.id = trv.taux_remu_id
            WHERE tr.id = id_in
            AND tr.histo_destruction IS NULL
            AND trv.date_effet <= date_val
            ORDER BY trv.date_effet DESC
        )
        WHERE rownum = 1
    ) INTO valeur
    from dual;

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
    ) INTO valeur_parent
    FROM dual;

    IF valeur_parent IS NULL
    THEN
        RETURN valeur;
    ELSE
        RETURN valeur*valeur_parent;
    END IF;

    EXCEPTION
    WHEN OTHERS THEN
       RETURN 1;
  END get_taux_horaire;



  FUNCTION get_taux_horaire_date (id_in IN NUMBER, date_val IN DATE) RETURN DATE IS
    date_valeur DATE;
    date_parent DATE;
  BEGIN
    SELECT(
        SELECT date_effet FROM
        (
            SELECT trv.date_effet
            FROM taux_remu tr
            JOIN taux_remu_valeur trv ON tr.id = trv.taux_remu_id
            WHERE tr.id = id_in
            AND tr.histo_destruction IS NULL
            AND trv.date_effet <= date_val
            ORDER BY trv.date_effet DESC
        )
        WHERE rownum = 1
    ) INTO date_valeur
    FROM dual;

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
    ) INTO date_parent
    FROM dual;

        IF date_parent IS NULL
    THEN
        RETURN date_valeur;
    ELSE
        RETURN GREATEST(date_valeur, date_parent);
    END IF;

    EXCEPTION
    WHEN OTHERS THEN
       RETURN to_date('01/01/0001', 'dd/mm/YYYY');
  END get_taux_horaire_date;

END ose_paiement;