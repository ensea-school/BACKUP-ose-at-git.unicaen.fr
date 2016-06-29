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

/

create or replace TRIGGER SERVICE_HISTO_CK
  BEFORE UPDATE OF intervenant_id, element_pedagogique_id, etablissement_id ON SERVICE
FOR EACH ROW
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