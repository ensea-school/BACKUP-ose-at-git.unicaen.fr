CREATE OR REPLACE TRIGGER "ELEMENT_PEDAGOGIQUE_CK"
BEFORE INSERT OR UPDATE ON element_pedagogique FOR EACH ROW
DECLARE
  enseignement INTEGER;
  source_id INTEGER;
BEGIN
  SELECT id INTO source_id FROM source WHERE code = 'OSE';

  IF :NEW.source_id <> source_id THEN RETURN; END IF; -- impossible de checker car l'UPD par import se fait champ par champ...

  IF :NEW.fi = 0 AND :NEW.fc = 0 AND :NEW.fa = 0 THEN
    raise_application_error(-20101, 'Un enseignement doit obligatoirement être au moins en FI, FC ou FA');
  END IF;

  IF 1 <> ROUND(:NEW.taux_fi + :NEW.taux_fc + :NEW.taux_fa, 2) THEN
    raise_application_error( -20101, 'Le total des taux FI, FC et FA n''est pas égal à 100%');
  END IF;

  IF :NEW.fi = 0 AND :NEW.taux_fi > 0 THEN
    raise_application_error( -20101, 'Le taux FI doit être à 0 puisque la formation n''est pas dispensée en FI');
  END IF;

  IF :NEW.fa = 0 AND :NEW.taux_fa > 0 THEN
    raise_application_error( -20101, 'Le taux FA doit être à 0 puisque la formation n''est pas dispensée en FA');
  END IF;

  IF :NEW.fc = 0 AND :NEW.taux_fc > 0 THEN
    raise_application_error( -20101, 'Le taux FC doit être à 0 puisque la formation n''est pas dispensée en FC');
  END IF;

  IF :NEW.periode_id IS NOT NULL THEN
    SELECT p.enseignement
    INTO enseignement
    FROM periode p
    WHERE p.id	     = :NEW.periode_id;
    IF enseignement <> 1 THEN
      raise_application_error(-20101, 'Cette période n''est pas appliquable à cet élément pédagogique.');
    END IF;
  END IF;

END;