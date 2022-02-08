CREATE OR REPLACE TRIGGER "VOLUME_HORAIRE_CK"
BEFORE INSERT OR UPDATE ON volume_horaire
FOR EACH ROW
  DECLARE
    has_validation NUMERIC;
    modified       BOOLEAN;
    mnp_actif      NUMERIC;
  BEGIN
    IF :OLD.motif_non_paiement_id IS NULL AND :NEW.motif_non_paiement_id IS NOT NULL THEN
      SELECT si.motif_non_paiement INTO mnp_actif FROM service s JOIN intervenant i ON i.id = s.intervenant_id JOIN statut si ON si.id = i.statut_id WHERE s.id = :NEW.service_id;
      IF 0 = mnp_actif THEN
        raise_application_error(-20101, 'Il est impossible d''associer un motif de non paiement à cet intervenant.');
      END IF;
    END IF;

    IF :NEW.motif_non_paiement_id IS NOT NULL AND :NEW.contrat_id IS NOT NULL THEN
      raise_application_error(-20101, 'Les heures ayant un motif de non paiement ne peuvent faire l''objet d''une contractualisation');
    END IF;

    modified :=
      NVL(:NEW.id,0) <> NVL(:OLD.id,0)
      OR NVL(:NEW.type_volume_horaire_id,0) <> NVL(:OLD.type_volume_horaire_id,0)
      OR NVL(:NEW.service_id,0) <> NVL(:OLD.service_id,0)
      OR NVL(:NEW.periode_id,0) <> NVL(:OLD.periode_id,0)
      OR NVL(:NEW.type_intervention_id,0) <> NVL(:OLD.type_intervention_id,0)
      OR NVL(:NEW.heures,0) <> NVL(:OLD.heures,0)
      OR NVL(:NEW.motif_non_paiement_id,0) <> NVL(:OLD.motif_non_paiement_id,0)
      OR NVL(:NEW.histo_creation,SYSDATE) <> NVL(:OLD.histo_creation,SYSDATE)
      OR NVL(:NEW.histo_createur_id,0) <> NVL(:OLD.histo_createur_id,0)
      OR NVL(:NEW.histo_destruction,SYSDATE) <> NVL(:OLD.histo_destruction,SYSDATE)
      OR NVL(:NEW.histo_destructeur_id,0) <> NVL(:OLD.histo_destructeur_id,0);

    SELECT
      COUNT(*)
    INTO
      has_validation
    FROM
      VALIDATION_VOL_HORAIRE vvh
      JOIN validation v ON v.id = VVH.VALIDATION_ID
    WHERE
      V.HISTO_DESTRUCTION IS NULL
      AND vvh.VOLUME_HORAIRE_ID  = :NEW.ID;

    IF modified AND 0 <> has_validation THEN
      raise_application_error(-20101, 'Il est impossible de modifier des heures déjà validées.');
    END IF;
  END;