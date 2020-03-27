CREATE OR REPLACE TRIGGER "MISE_EN_PAIEMENT_DEL_CK"
  BEFORE DELETE ON "MISE_EN_PAIEMENT"
  REFERENCING FOR EACH ROW
  DECLARE
  has_validation NUMERIC;
BEGIN

  /* Initialisation des conditions */
  SELECT COUNT(*) INTO has_validation FROM validation v WHERE
    v.id = :NEW.validation_id
    AND v.histo_destruction IS NULL;

  /* Mise en place des contraintes */
  IF
    1 = has_validation AND :OLD.histo_destruction IS NOT NULL
  THEN
    raise_application_error(-20101, 'Il est impossible de supprimer une mise en paiement validée.');
  END IF;
END;