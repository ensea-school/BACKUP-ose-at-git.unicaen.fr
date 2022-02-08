CREATE OR REPLACE TRIGGER "SERVICE_CK"
BEFORE INSERT OR UPDATE ON service
FOR EACH ROW
DECLARE
  etablissement integer;
  res integer;
  se_actif numeric;
BEGIN

  etablissement := OSE_PARAMETRE.GET_ETABLISSEMENT();

  IF :NEW.etablissement_id = etablissement AND :NEW.element_pedagogique_id IS NULL THEN
    raise_application_error(-20101, 'Un enseignement doit obligatoirement être renseigné si le service est réalisé en interne.');
  END IF;

  SELECT si.service_exterieur INTO se_actif FROM intervenant i JOIN statut si ON si.id = i.statut_id WHERE i.id = :NEW.intervenant_id;

  IF NOT :NEW.etablissement_id <> etablissement AND se_actif = 1 THEN
    raise_application_error(-20101, 'L''intervenant n''a pas la possibilité de renseigner des enseignements pris à l''extérieur de par son statut.');
  END IF;

  IF :NEW.intervenant_id IS NOT NULL AND :NEW.element_pedagogique_id IS NOT NULL THEN
    SELECT
      count(*) INTO res
    FROM
      intervenant i,
      element_pedagogique ep
    WHERE
          i.id        = :NEW.intervenant_id
      AND ep.id       = :NEW.element_pedagogique_id
      AND ep.annee_id = i.annee_id
    ;

    IF 0 = res THEN -- années non concomitantes
      raise_application_error(-20101, 'L''année de l''intervenant ne correspond pas à l''année de l''élément pédagogique.');
    END IF;
  END IF;

END;