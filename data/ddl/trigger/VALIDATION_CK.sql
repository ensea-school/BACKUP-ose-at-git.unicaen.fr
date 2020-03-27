CREATE OR REPLACE TRIGGER "VALIDATION_CK"
BEFORE UPDATE OF histo_destruction, histo_destructeur_id OR DELETE ON validation
FOR EACH ROW
DECLARE
  v validation%rowtype;
  err varchar2(500) default null;
  pragma autonomous_transaction;
BEGIN

  IF deleting THEN
    v.id                  := :OLD.id;
    v.type_validation_id  := :OLD.type_validation_id;
    v.intervenant_id      := :OLD.intervenant_id;
    v.structure_id        := :OLD.structure_id;


  ELSIF :OLD.histo_destruction IS NULL AND :NEW.histo_destruction IS NOT NULL THEN

    v.id                  := :NEW.id;
    v.type_validation_id  := :NEW.type_validation_id;
    v.intervenant_id      := :NEW.intervenant_id;
    v.structure_id        := :NEW.structure_id;

  END IF;

  err := ose_validation.can_devalider( v );

  IF err is not null THEN
    raise_application_error(-20101, err);
  END IF;

END;