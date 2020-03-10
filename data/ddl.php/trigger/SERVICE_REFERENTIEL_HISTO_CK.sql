CREATE OR REPLACE TRIGGER "SERVICE_REFERENTIEL_HISTO_CK"
BEFORE UPDATE OF
  FONCTION_ID,
  INTERVENANT_ID,
  STRUCTURE_ID,
  HISTO_DESTRUCTION
ON service_referentiel FOR EACH ROW
DECLARE
  has_validation integer;
BEGIN
  SELECT COUNT(*) INTO has_validation
  FROM
    VALIDATION_VOL_HORAIRE_REF vvh
    JOIN validation v ON v.id = VVH.VALIDATION_ID
    JOIN volume_horaire_ref vh ON vh.id = vvh.volume_horaire_ref_id
  WHERE
    V.HISTO_DESTRUCTION IS NULL
    AND vh.service_referentiel_id = :OLD.ID;

  IF 0 <> has_validation THEN
    raise_application_error(-20101, 'Il est impossible de modifier ou supprimer du référentiel dont des heures ont déjà été validées.');
  END IF;

END;