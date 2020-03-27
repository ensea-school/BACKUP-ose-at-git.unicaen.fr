CREATE OR REPLACE TRIGGER "VALIDATION_VOL_HORAIRE_CK"
BEFORE INSERT OR UPDATE OR DELETE ON validation_vol_horaire
FOR EACH ROW
DECLARE
  contrat_blinde NUMERIC;
  donnee_historisee NUMERIC;
  pragma autonomous_transaction;
BEGIN

  if updating or deleting then

    SELECT count(*) INTO contrat_blinde
    FROM volume_horaire vh
    JOIN contrat c ON c.id = vh.contrat_id AND c.histo_destruction IS NULL
    WHERE vh.id = :OLD.volume_horaire_id;

    -- Si des volumes horaires ont déjà fait l'objet de contrats alors pas de dévalidation possible des heures
    IF contrat_blinde = 1 THEN
      raise_application_error(-20101, 'La dévalidation est impossible car un contrat a déjà été édité sur la base de ces heures.');
    END IF;

  else

    -- si on en trouve un service, EP, étape ou VH historisé, problème
    select count(*) into donnee_historisee
    from service s
    join element_pedagogique ep on s.element_pedagogique_id = ep.id
    --join etape e on ep.etape_id = e.id
    join volume_horaire vh on vh.service_id = s.id
    where
      vh.id = :NEW.volume_horaire_id
      AND (
        s.histo_destructeur_id is not null
        or ep.histo_destructeur_id is not null
        --or e.histo_destructeur_id is not null
        or (vh.histo_destructeur_id is not null)
      )
      AND vh.heures > 0;

    IF donnee_historisee > 0 THEN
      raise_application_error(-20101, :NEW.volume_horaire_id || ' La validation est impossible car elle porte sur des données historisées (supprimées).');
    END IF;

  end if;

END;