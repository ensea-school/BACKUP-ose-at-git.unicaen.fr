CREATE OR REPLACE TRIGGER "INTERVENANT_HORO_SERVICE_REF"
AFTER INSERT OR UPDATE OR DELETE ON service_referentiel
FOR EACH ROW
BEGIN

  IF DELETING THEN

    ose_divers.intervenant_horodatage_service(
      :OLD.intervenant_id,
      null,
      1,
      :OLD.histo_modificateur_id,
      :OLD.histo_modification
    );

  ELSE

    ose_divers.intervenant_horodatage_service(
      :NEW.intervenant_id,
      null,
      1,
      :NEW.histo_modificateur_id,
      :NEW.histo_modification
    );

  END IF;

END;