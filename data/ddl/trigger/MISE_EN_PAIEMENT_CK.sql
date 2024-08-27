CREATE OR REPLACE TRIGGER "MISE_EN_PAIEMENT_CK"
  BEFORE INSERT OR UPDATE ON "MISE_EN_PAIEMENT"
  REFERENCING FOR EACH ROW
BEGIN

  /* Mise en place des contraintes */
  IF :NEW.service_id IS NULL AND :NEW.service_referentiel_id IS NULL AND :NEW.mission_id IS NULL THEN
    raise_application_error(-20101, 'La mise en paiement ne correspond à aucun service.');
  END IF;

  IF :NEW.periode_paiement_id IS NOT NULL AND :NEW.date_mise_en_paiement IS NULL THEN
    raise_application_error(-20101, 'La mise en paiement numéro ' || :NEW.id || ' est bien effectuée mais la date de mise en paiement n''est pas précisée.');
  END IF;

END;