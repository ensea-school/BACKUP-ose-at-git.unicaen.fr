CREATE OR REPLACE TRIGGER "SERVICE_CK"
BEFORE INSERT OR UPDATE ON service
FOR EACH ROW
DECLARE
  etablissement integer;
  res integer;
BEGIN

  etablissement := OSE_PARAMETRE.GET_ETABLISSEMENT();

  IF :NEW.etablissement_id = etablissement AND :NEW.element_pedagogique_id IS NULL THEN
    raise_application_error(-20101, 'Un enseignement doit obligatoirement être renseigné si le service est réalisé en interne.');
  END IF;


  IF :NEW.etablissement_id <> etablissement AND OSE_DIVERS.INTERVENANT_HAS_PRIVILEGE(:NEW.intervenant_id, 'saisie_service_exterieur') = 0 THEN
    raise_application_error(-20101, 'Les intervenants vacataires n''ont pas la possibilité de renseigner des enseignements pris à l''extérieur.');
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