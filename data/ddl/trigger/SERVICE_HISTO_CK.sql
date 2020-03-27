CREATE OR REPLACE TRIGGER "SERVICE_HISTO_CK"
  BEFORE UPDATE OF intervenant_id, element_pedagogique_id, etablissement_id ON "SERVICE"
  REFERENCING FOR EACH ROW
  DECLARE
  has_validation integer;
BEGIN
  SELECT COUNT(*) INTO has_validation
  FROM
    VALIDATION_VOL_HORAIRE vvh
    JOIN validation v ON v.id = VVH.VALIDATION_ID
    JOIN volume_horaire vh ON vh.id = VVH.VOLUME_HORAIRE_ID
  WHERE
    V.HISTO_DESTRUCTION IS NULL
    AND vh.service_ID = :NEW.ID;

  IF 0 <> has_validation THEN
    raise_application_error(-20101, 'Il est impossible de modifier ou supprimer un service dont des heures ont déjà été validées.');
  END IF;

END;