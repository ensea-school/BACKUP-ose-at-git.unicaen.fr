CREATE OR REPLACE TRIGGER "AFFECTATION_RECHERCHE_CK"
BEFORE INSERT OR UPDATE ON affectation_recherche
FOR EACH ROW
DECLARE
  pragma autonomous_transaction;
  rows_found integer;
BEGIN

  if :NEW.histo_destruction IS NOT NULL THEN RETURN; END IF; -- pas de check si c'est pour une historicisation

  select
    count(*) into rows_found
  from
    affectation_recherche
  where
    intervenant_id = :new.intervenant_id
    AND structure_id = :new.structure_id
    AND histo_destruction IS NULL
    AND id <> :NEW.id;

  if rows_found > 0 THEN
    raise_application_error(-20101, 'Un enseignant (id=' || :NEW.intervenant_id || ') ne peut pas avoir plusieurs affectations de recherche pour une même structure');
  END IF;

END;