create or replace TRIGGER AGREMENT_CK
BEFORE UPDATE ON agrement FOR EACH ROW
DECLARE
  contrat_found INTEGER;
BEGIN

  SELECT
    COUNT(*) INTO contrat_found
  FROM
    contrat c
  WHERE
    c.INTERVENANT_ID = :NEW.intervenant_id
    AND c.structure_id = NVL(:NEW.structure_id,c.structure_id)
    AND 1 = OSE_DIVERS.COMPRISE_ENTRE( c.histo_creation, c.histo_destruction )
    AND ROWNUM = 1;

  IF 
    1 = contrat_found 
    AND :NEW.histo_destruction IS NOT NULL AND :OLD.histo_destruction IS NULL
  THEN 
  
    IF :NEW.structure_id IS NULL THEN
      raise_application_error(-20101, 'Cet agrément ne peut pas être supprimé car un contrat a été signé.');    
    ELSE
      raise_application_error(-20101, 'Cet agrément ne peut pas être supprimé car un contrat a été signé dans la même composante.');    
    END IF;
  END IF;
  
END;