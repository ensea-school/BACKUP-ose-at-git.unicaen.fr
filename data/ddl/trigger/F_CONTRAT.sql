CREATE OR REPLACE TRIGGER "F_CONTRAT"
  AFTER DELETE OR UPDATE OF INTERVENANT_ID, HISTO_CREATION, HISTO_DESTRUCTION, STRUCTURE_ID, DATE_RETOUR_SIGNE, VALIDATION_ID ON CONTRAT
  REFERENCING FOR EACH ROW
  BEGIN

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      volume_horaire vh
      JOIN service s ON s.id = vh.service_id AND s.histo_destruction IS NULL
    WHERE
      vh.histo_destruction IS NULL
      AND (vh.contrat_id = :OLD.id OR vh.contrat_id = :NEW.id)

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );

  END LOOP;

END;