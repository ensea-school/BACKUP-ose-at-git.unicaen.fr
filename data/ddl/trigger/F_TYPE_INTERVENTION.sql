CREATE OR REPLACE TRIGGER "F_TYPE_INTERVENTION"
AFTER UPDATE OF
  taux_hetd_service,
  taux_hetd_complementaire
ON type_intervention
FOR EACH ROW
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
      AND (vh.type_intervention_id = :NEW.id OR vh.type_intervention_id = :OLD.id)

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );

  END LOOP;
END;