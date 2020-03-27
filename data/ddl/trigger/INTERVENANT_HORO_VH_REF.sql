CREATE OR REPLACE TRIGGER "INTERVENANT_HORO_VH_REF"
AFTER INSERT OR UPDATE OR DELETE ON volume_horaire_ref
FOR EACH ROW
DECLARE
  intervenant_id NUMERIC;
BEGIN



  IF DELETING THEN
    SELECT s.intervenant_id INTO intervenant_id FROM service_referentiel s WHERE s.id = :OLD.service_referentiel_id;

    ose_divers.intervenant_horodatage_service(
      intervenant_id,
      :OLD.type_volume_horaire_id,
      1,
      :OLD.histo_modificateur_id,
      :OLD.histo_modification
    );

  ELSE
    SELECT s.intervenant_id INTO intervenant_id FROM service_referentiel s WHERE s.id = :NEW.service_referentiel_id;

    ose_divers.intervenant_horodatage_service(
      intervenant_id,
      :NEW.type_volume_horaire_id,
      1,
      :NEW.histo_modificateur_id,
      :NEW.histo_modification
    );

  END IF;

END;