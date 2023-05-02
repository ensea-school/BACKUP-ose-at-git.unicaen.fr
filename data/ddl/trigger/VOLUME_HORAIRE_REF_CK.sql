CREATE OR REPLACE TRIGGER "VOLUME_HORAIRE_REF_CK" BEFORE UPDATE OF id,type_volume_horaire_id,service_referentiel_id,heures,histo_creation,histo_createur_id,source_id,source_code,horaire_debut,horaire_fin ON volume_horaire_REF FOR EACH ROW
  DECLARE
    has_validation NUMERIC;
  BEGIN

  SELECT
    COUNT(*)
  INTO
    has_validation
  FROM
    VALIDATION_VOL_HORAIRE_REF vvh
    JOIN validation v ON v.id = VVH.VALIDATION_ID
  WHERE
    V.HISTO_DESTRUCTION IS NULL
    AND vvh.VOLUME_HORAIRE_REF_ID  = :NEW.ID;

  IF 0 <> has_validation THEN
    raise_application_error(-20101, 'Il est impossible de modifier des heures référentiel déjà validées.');
  END IF;
END;