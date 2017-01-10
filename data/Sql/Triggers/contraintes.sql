--------------------------------------------------------
--  DDL for Trigger AFFECTATION_RECHERCHE_CK
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."AFFECTATION_RECHERCHE_CK" 
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
    AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction )
    AND id <> :NEW.id;
  
  if rows_found > 0 THEN
    raise_application_error(-20101, 'Un enseignant (id=' || :NEW.intervenant_id || ') ne peut pas avoir plusieurs affectations de recherche pour une même structure');
  END IF;

END;
/



--------------------------------------------------------
--  DDL for Trigger AGREMENT_CK
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."AGREMENT_CK" 
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



--------------------------------------------------------
--  DDL for Trigger SERVICE_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."SERVICE_CK" 
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

  --IF :OLD.id IS NOT NULL AND ( :NEW.etablissement_id <> :OLD.etablissement_id OR :NEW.element_pedagogique_id <> :OLD.element_pedagogique_id ) THEN
    --UPDATE volume_horaire SET histo_destruction = SYSDATE, histo_destructeur_id = :NEW.histo_modificateur_id WHERE service_id = :NEW.id;
  --END IF;

END;
/



--------------------------------------------------------
--  DDL for Trigger SERVICE_HISTO_CK
--------------------------------------------------------

create or replace TRIGGER "OSE"."SERVICE_HISTO_CK"
  BEFORE UPDATE OF intervenant_id, element_pedagogique_id, etablissement_id, description ON "OSE"."SERVICE" 
  REFERENCING FOR EACH ROW
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
/



--------------------------------------------------------
--  DDL for Trigger SERVICE_HISTO_CK_S
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."SERVICE_HISTO_CK_S" 
AFTER UPDATE ON service
BEGIN
 -- En cas de restauration d'un service, on ne restaure pas les historiques de volumes horaires pour ne pas récussiter d'éventuels volume horaires indésirables car préalablement supprimés
 FOR s IN (

    SELECT *
    FROM
      service s
    WHERE
      s.histo_destruction IS NOT NULL AND s.histo_destruction > SYSDATE - 1

  ) LOOP

    UPDATE VOLUME_HORAIRE SET histo_destruction = s.histo_destruction, histo_destructeur_id = s.histo_destructeur_id WHERE service_id = s.id AND VOLUME_HORAIRE.histo_destruction IS NULL;

  END LOOP;
   
END;
/



--------------------------------------------------------
--  DDL for Trigger SERVICE_REFERENTIEL_HISTO_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."SERVICE_REFERENTIEL_HISTO_CK" 
BEFORE UPDATE OF 
  FONCTION_ID,
  INTERVENANT_ID,
  STRUCTURE_ID,
  HISTO_DESTRUCTION
ON service_referentiel FOR EACH ROW
DECLARE
  has_validation integer;
BEGIN
  SELECT COUNT(*) INTO has_validation
  FROM
    VALIDATION_VOL_HORAIRE_REF vvh
    JOIN validation v ON v.id = VVH.VALIDATION_ID
    JOIN volume_horaire_ref vh ON vh.id = vvh.volume_horaire_ref_id
  WHERE
    V.HISTO_DESTRUCTION IS NULL
    AND vh.service_referentiel_id = :OLD.ID;

  IF 0 <> has_validation THEN
    raise_application_error(-20101, 'Il est impossible de modifier ou supprimer du référentiel dont des heures ont déjà été validées.');
  END IF;

END;
/



--------------------------------------------------------
--  DDL for Trigger ELEMENT_PEDAGOGIQUE_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."ELEMENT_PEDAGOGIQUE_CK" 
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
/



--------------------------------------------------------
--  DDL for Trigger MISE_EN_PAIEMENT_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."MISE_EN_PAIEMENT_CK" 
  BEFORE INSERT OR UPDATE ON "OSE"."MISE_EN_PAIEMENT"
  REFERENCING FOR EACH ROW
  DECLARE
  has_validation NUMERIC;
  has_mise_en_paiement NUMERIC;
BEGIN

  /* Initialisation des conditions */
  SELECT COUNT(*) INTO has_validation FROM validation v WHERE 
    v.id = :NEW.validation_id
    AND 1 = OSE_DIVERS.COMPRISE_ENTRE( v.histo_creation, v.histo_destruction );
  
  IF :NEW.date_mise_en_paiement IS NULL THEN
    has_mise_en_paiement := 0;
  ELSE
    has_mise_en_paiement := 1;
  END IF;

  /* Mise en place des contraintes */
  IF :NEW.formule_res_service_id IS NULL AND :NEW.formule_res_service_ref_id IS NULL THEN
    raise_application_error(-20101, 'La mise en paiement ne correspond à aucun service ou service référentiel.');
  END IF;
  
  IF 1 = has_validation AND :NEW.date_validation IS NULL THEN
    raise_application_error(-20101, 'La validation de la mise en paiement numéro ' || :NEW.id || ' est bien renseignée mais la date de validation n''est pas précisée.');
  END IF;

  IF :NEW.periode_paiement_id IS NOT NULL AND :NEW.date_mise_en_paiement IS NULL THEN
    raise_application_error(-20101, 'La mise en paiement numéro ' || :NEW.id || ' est bien effectuée mais la date de mise en paiement n''est pas précisée.');
  END IF;

--  IF 0 = has_validation AND 1 = has_mise_en_paiement THEN
--    raise_application_error(-20101, 'La demande de mise en paiement numéro ' || :NEW.id || ' ne peut faire l''objet d''une mise en paiement tant qu''elle n''est pas validée.');
--  END IF;

  IF 
    :OLD.validation_id IS NOT NULL AND 1 = ose_divers.comprise_entre( :OLD.histo_creation, :OLD.histo_destruction )
    AND 1 = has_validation AND 0 = ose_divers.comprise_entre( :NEW.histo_creation, :NEW.histo_destruction )
  THEN
    raise_application_error(-20101, 'Il est impossible de supprimer une mise en paiement validée.');
  END IF;
END;
/



--------------------------------------------------------
--  DDL for Trigger MISE_EN_PAIEMENT_DEL_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."MISE_EN_PAIEMENT_DEL_CK" 
  BEFORE DELETE ON "OSE"."MISE_EN_PAIEMENT"
  REFERENCING FOR EACH ROW
  DECLARE
  has_validation NUMERIC;
BEGIN

  /* Initialisation des conditions */
  SELECT COUNT(*) INTO has_validation FROM validation v WHERE 
    v.id = :NEW.validation_id
    AND 1 = OSE_DIVERS.COMPRISE_ENTRE( v.histo_creation, v.histo_destruction );

  /* Mise en place des contraintes */
  IF 
    1 = has_validation AND 0 = ose_divers.comprise_entre( :OLD.histo_creation, :OLD.histo_destruction )
  THEN
    raise_application_error(-20101, 'Il est impossible de supprimer une mise en paiement validée.');
  END IF;
END;
/



--------------------------------------------------------
--  DDL for Trigger TYPE_INTERVENTION_STRUCTURE_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."TYPE_INTERVENTION_STRUCTURE_CK" 
  BEFORE INSERT OR UPDATE ON "OSE"."TYPE_INTERVENTION_STRUCTURE"
  REFERENCING FOR EACH ROW
  DECLARE 
  structure_niveau NUMERIC;
BEGIN
  
  SELECT structure.niveau INTO structure_niveau FROM structure WHERE structure.id = :NEW.structure_id;
  
  IF structure_niveau <> 2 THEN
    raise_application_error(-20101, 'Les types d''intervention ne peuvent être associés qu''à des structures de niveau 2.');
  END IF;

END;
/



--------------------------------------------------------
--  DDL for Trigger VALIDATION_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."VALIDATION_CK" 
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
/



--------------------------------------------------------
--  DDL for Trigger VALIDATION_VOL_HORAIRE_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."VALIDATION_VOL_HORAIRE_CK" 
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
    JOIN contrat c ON c.id = vh.contrat_id AND 1 = ose_divers.comprise_entre( c.histo_creation, c.histo_destruction )
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
/



--------------------------------------------------------
--  DDL for Trigger VOLUME_HORAIRE_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."VOLUME_HORAIRE_CK" 
BEFORE INSERT OR UPDATE ON volume_horaire 
FOR EACH ROW 
  DECLARE
    has_validation NUMERIC;
    modified       BOOLEAN;
    intervenant_id NUMERIC;
  BEGIN
    IF :OLD.motif_non_paiement_id IS NULL AND :NEW.motif_non_paiement_id IS NOT NULL THEN
      SELECT s.intervenant_id INTO intervenant_id FROM service s WHERE s.id = :NEW.service_id;
      IF 0 = ose_divers.intervenant_has_privilege( intervenant_id, 'saisie_motif_non_paiement') THEN
        raise_application_error(-20101, 'Il est impossible d''associer un motif de non paiement à cet intervenant.');
      END IF;
    END IF;
    
    IF :NEW.motif_non_paiement_id IS NOT NULL AND :NEW.contrat_id IS NOT NULL THEN
      raise_application_error(-20101, 'Les heures ayant un motif de non paiement ne peuvent faire l''objet d''une contractualisation');
    END IF;

    modified := 
      NVL(:NEW.id,0) <> NVL(:OLD.id,0)
      OR NVL(:NEW.type_volume_horaire_id,0) <> NVL(:OLD.type_volume_horaire_id,0)
      OR NVL(:NEW.service_id,0) <> NVL(:OLD.service_id,0)
      OR NVL(:NEW.periode_id,0) <> NVL(:OLD.periode_id,0)
      OR NVL(:NEW.type_intervention_id,0) <> NVL(:OLD.type_intervention_id,0)
      OR NVL(:NEW.heures,0) <> NVL(:OLD.heures,0)
      OR NVL(:NEW.motif_non_paiement_id,0) <> NVL(:OLD.motif_non_paiement_id,0)
      OR NVL(:NEW.histo_creation,SYSDATE) <> NVL(:OLD.histo_creation,SYSDATE)
      OR NVL(:NEW.histo_createur_id,0) <> NVL(:OLD.histo_createur_id,0)
      OR NVL(:NEW.histo_destruction,SYSDATE) <> NVL(:OLD.histo_destruction,SYSDATE)
      OR NVL(:NEW.histo_destructeur_id,0) <> NVL(:OLD.histo_destructeur_id,0);
    
    SELECT
      COUNT(*)
    INTO
      has_validation
    FROM
      VALIDATION_VOL_HORAIRE vvh
      JOIN validation v ON v.id = VVH.VALIDATION_ID
    WHERE
      V.HISTO_DESTRUCTION IS NULL
      AND vvh.VOLUME_HORAIRE_ID  = :NEW.ID;
      
    IF modified AND 0 <> has_validation THEN
      raise_application_error(-20101, 'Il est impossible de modifier des heures déjà validées.');
    END IF;
  END;
/



--------------------------------------------------------
--  DDL for Trigger VOLUME_HORAIRE_DEL_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."VOLUME_HORAIRE_DEL_CK" BEFORE
  DELETE ON volume_horaire FOR EACH ROW DECLARE has_validation INTEGER;
  pragma autonomous_transaction;
  BEGIN
    SELECT COUNT(*)
    INTO has_validation
    FROM VALIDATION_VOL_HORAIRE vvh
    JOIN validation v
    ON v.id                    = VVH.VALIDATION_ID
    WHERE V.HISTO_DESTRUCTION IS NULL
    AND vvh.VOLUME_HORAIRE_ID  = :OLD.ID;
    IF 0                      <> has_validation THEN
      raise_application_error(-20101, 'Il est impossible de supprimer des heures déjà validées.');
    END IF;
  END;
/



--------------------------------------------------------
--  DDL for Trigger VOLUME_HORAIRE_REF_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."VOLUME_HORAIRE_REF_CK" BEFORE UPDATE ON volume_horaire_REF FOR EACH ROW 
  DECLARE
    has_validation NUMERIC;
  BEGIN  
    
  SELECT 
    COUNT(*)
  INTO
    has_validation
  FROM
    VALIDATION_VOL_HORAIRE_REF vvh
    JOIN validation v ON v.id = VVH.VALIDATION_ID
  WHERE
    V.HISTO_DESTRUCTION IS NULL
    AND vvh.VOLUME_HORAIRE_REF_ID  = :NEW.ID;
    
  IF 0 <> has_validation THEN
    raise_application_error(-20101, 'Il est impossible de modifier des heures référentiel déjà validées.');
  END IF;
END;
/



--------------------------------------------------------
--  DDL for Trigger VOLUME_HORAIRE_REF_DEL_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."VOLUME_HORAIRE_REF_DEL_CK" BEFORE DELETE ON volume_horaire_REF FOR EACH ROW 
  DECLARE has_validation INTEGER;
  pragma autonomous_transaction;
  BEGIN
    SELECT COUNT(*)
    INTO has_validation
    FROM VALIDATION_VOL_HORAIRE_REF vvh
    JOIN validation v
    ON v.id                    = VVH.VALIDATION_ID
    WHERE V.HISTO_DESTRUCTION IS NULL
    AND vvh.VOLUME_HORAIRE_REF_ID  = :OLD.ID;
    IF 0                      <> has_validation THEN
      raise_application_error(-20101, 'Il est impossible de supprimer des heures référentiel déjà validées.');
    END IF;
  END;
/



--------------------------------------------------------
--  DDL for Trigger WF_ETAPE_DEP_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."WF_ETAPE_DEP_CK" 
BEFORE INSERT OR UPDATE ON wf_etape_dep
FOR EACH ROW 
BEGIN

  OSE_WORKFLOW.DEP_CHECK( :new.etape_suiv_id, :new.etape_prec_id );

END;
/