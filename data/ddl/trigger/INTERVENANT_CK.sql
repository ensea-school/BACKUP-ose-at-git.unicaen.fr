CREATE OR REPLACE TRIGGER "INTERVENANT_CK"
BEFORE INSERT OR
UPDATE of source_id, source_code, histo_destruction, code, statut_id, utilisateur_code
ON intervenant
    FOR EACH ROW
DECLARE
pragma autonomous_transaction;
  imp
NUMERIC;
  cs
NUMERIC;
BEGIN
  IF
:NEW.histo_destruction IS NOT NULL THEN
    RETURN;
END IF;

SELECT importable
INTO imp
FROM source
WHERE id = :new.source_id;
IF
imp = 1 THEN

    -- Contrôle du bon remplissage du source_code
    IF :NEW.source_code IS NULL THEN
      raise_application_error(-20101, 'Le code Source est NULL, or il est requis pour toute donnée importée.');
END IF;

END IF;

  -- Contrôle de l'unicité de STATUT_ID
SELECT COUNT(*)
INTO cs
FROM intervenant
WHERE id <> :new.id
  AND histo_destruction IS NULL
  AND code = :new.code
  AND annee_id = :new.annee_id
  AND statut_id = :new.statut_id
;
IF
cs > 0 THEN
    raise_application_error(-20101, 'Un intervenant ne peut pas avoir deux fois le même statut le même année');
END IF;

  -- On ne peut pas assicoer un même login à plusieurs intervenants
SELECT COUNT(*)
INTO cs
FROM intervenant
WHERE id <> :new.id
  AND histo_destruction IS NULL
  AND code <> :new.code
  AND annee_id = :new.annee_id
  AND utilisateur_code = :new.utilisateur_code
;
IF
cs > 0 THEN
    raise_application_error(-20101, 'L''utilisateur est déjà utilisé pour un autre intervenant. Merci d''en choisir un autre.' || ' Pour le nouveau dossier ID: '|| :NEW.id|| ', Code: '|| :NEW.code || ', utilisateur_code: '||:NEW.utilisateur_code );
END IF;

END;