CREATE OR REPLACE TRIGGER "MISE_EN_PAIEMENT_CK"
  BEFORE INSERT OR UPDATE ON "MISE_EN_PAIEMENT"
  REFERENCING FOR EACH ROW
  DECLARE
  has_validation NUMERIC;
  has_mise_en_paiement NUMERIC;
BEGIN

  /* Initialisation des conditions */
  SELECT COUNT(*) INTO has_validation FROM validation v WHERE
    v.id = :NEW.validation_id
    AND v.histo_destruction IS NULL;

  IF :NEW.date_mise_en_paiement IS NULL THEN
    has_mise_en_paiement := 0;
  ELSE
    has_mise_en_paiement := 1;
  END IF;

  /* Mise en place des contraintes */
  IF :NEW.formule_res_service_id IS NULL AND :NEW.formule_res_service_ref_id IS NULL AND :NEW.mission_id IS NULL THEN
    raise_application_error(-20101, 'La mise en paiement ne correspond à aucun service.');
  END IF;

  IF 1 = has_validation AND :NEW.date_validation IS NULL THEN
    raise_application_error(-20101, 'La validation de la mise en paiement numéro ' || :NEW.id || ' est bien renseignée mais la date de validation n''est pas précisée.');
  END IF;

  IF :NEW.periode_paiement_id IS NOT NULL AND :NEW.date_mise_en_paiement IS NULL THEN
    raise_application_error(-20101, 'La mise en paiement numéro ' || :NEW.id || ' est bien effectuée mais la date de mise en paiement n''est pas précisée.');
  END IF;

--  IF 0 = has_validation AND 1 = has_mise_en_paiement THEN
--    raise_application_error(-20101, 'La demande de mise en paiement numéro ' || :NEW.id || ' ne peut faire l''objet d''une mise en paiement tant qu''elle n''est pas validée.');
--  END IF;

  IF
    :OLD.validation_id IS NOT NULL AND :OLD.histo_destruction IS NULL
    AND 1 = has_validation AND :NEW.histo_destruction IS NOT NULL
  THEN
    raise_application_error(-20101, 'Il est impossible de supprimer une mise en paiement validée.');
  END IF;
END;