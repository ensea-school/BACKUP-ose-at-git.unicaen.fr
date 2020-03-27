CREATE OR REPLACE TRIGGER "INTERVENANT_HORO_SERVICE"
AFTER INSERT OR UPDATE OR DELETE ON service
FOR EACH ROW
BEGIN

  IF DELETING THEN

    ose_divers.intervenant_horodatage_service(
      :OLD.intervenant_id,
      null,
      0,
      :OLD.histo_modificateur_id,
      :OLD.histo_modification
    );

  ELSE

    ose_divers.intervenant_horodatage_service(
      :NEW.intervenant_id,
      null,
      0,
      :NEW.histo_modificateur_id,
      :NEW.histo_modification
    );

  END IF;

END;