CREATE OR REPLACE TRIGGER T_VAR_SERVICE_REFERENTIEL
AFTER INSERT 
OR UPDATE OF 
	intervenant_id,
  structure_id,
	histo_creation,
	histo_destruction
OR DELETE ON SERVICE_REFERENTIEL
FOR EACH ROW
BEGIN

  IF :NEW.id IS NOT NULL THEN
    OSE_EVENT.DEMANDE_CALCUL( OSE_VALIDATION_REFERENTIEL.package_sujet, :NEW.intervenant_id );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    OSE_EVENT.DEMANDE_CALCUL( OSE_VALIDATION_REFERENTIEL.package_sujet, :OLD.intervenant_id );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_VAR_VOLUME_HORAIRE_REF
AFTER INSERT 
OR UPDATE OF 
  type_volume_horaire_id,
	service_referentiel_id,
	histo_creation,
	histo_destruction
OR DELETE ON VOLUME_HORAIRE_REF
FOR EACH ROW
BEGIN

  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id
    FROM
      service_referentiel s
    WHERE
      s.id = :NEW.service_referentiel_id
      OR s.id = :OLD.service_referentiel_id    

  ) LOOP
  
    OSE_EVENT.DEMANDE_CALCUL( OSE_VALIDATION_REFERENTIEL.package_sujet, p.intervenant_id );
  
  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_VAR_INTERVENANT
AFTER INSERT 
OR UPDATE OF 
  annee_id,
	structure_id,
  statut_id,
	histo_creation,
	histo_destruction
OR DELETE ON INTERVENANT
FOR EACH ROW
BEGIN

  IF :NEW.id IS NOT NULL THEN
    OSE_EVENT.DEMANDE_CALCUL( OSE_VALIDATION_REFERENTIEL.package_sujet, :NEW.id );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    OSE_EVENT.DEMANDE_CALCUL( OSE_VALIDATION_REFERENTIEL.package_sujet, :OLD.id );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_VAR_STATUT_INTERVENANT
AFTER UPDATE OF 
  type_intervenant_id
ON STATUT_INTERVENANT
FOR EACH ROW
BEGIN

  FOR p IN (

    SELECT DISTINCT
      i.id intervenant_id
    FROM
      intervenant i
    WHERE
         i.statut_id = :NEW.id
      OR i.statut_id = :OLD.id
  
  ) LOOP
  
    OSE_EVENT.DEMANDE_CALCUL( OSE_VALIDATION_REFERENTIEL.package_sujet, p.intervenant_id );
  
  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_VAR_REGLE_STRUCTURE_VAL
AFTER UPDATE OF 
  priorite,
	type_intervenant_id,
	type_volume_horaire_id
ON REGLE_STRUCTURE_VALIDATION
FOR EACH ROW
BEGIN

  FOR p IN (

    SELECT DISTINCT
      i.id intervenant_id
    FROM
      intervenant i
      JOIN statut_intervenant si ON si.id = i.id
    WHERE
         si.type_intervenant_id = :NEW.type_intervenant_id
      OR si.type_intervenant_id = :OLD.type_intervenant_id
  
  ) LOOP
  
    OSE_EVENT.DEMANDE_CALCUL( OSE_VALIDATION_REFERENTIEL.package_sujet, p.intervenant_id );
  
  END LOOP;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
      JOIN volume_horaire vh ON vh.service_id = s.id
    WHERE
         vh.type_volume_horaire_id = :NEW.type_volume_horaire_id
      OR vh.type_volume_horaire_id = :OLD.type_volume_horaire_id
  
  ) LOOP
  
    OSE_EVENT.DEMANDE_CALCUL( OSE_VALIDATION_REFERENTIEL.package_sujet, p.intervenant_id );
  
  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_VAR_STRUCTURE
AFTER UPDATE OF 
	niveau,
	histo_creation,
	histo_destruction
ON STRUCTURE
FOR EACH ROW
BEGIN

  FOR p IN (

    SELECT DISTINCT
      tve.intervenant_id
    FROM
      tbl_validation_enseignement tve
    WHERE
         tve.structure_id = :NEW.id
      OR tve.structure_id = :OLD.id
  
  ) LOOP
  
    OSE_EVENT.DEMANDE_CALCUL( OSE_VALIDATION_REFERENTIEL.package_sujet, p.intervenant_id );
  
  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_VAR_VAL_VOL_HORAIRE_REF
AFTER INSERT 
OR UPDATE OF 
  volume_horaire_ref_id,
	validation_id
OR DELETE ON VALIDATION_VOL_HORAIRE_REF
FOR EACH ROW
BEGIN

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service_referentiel s
      JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id
    WHERE
      vh.id = :NEW.volume_horaire_ref_id
      OR vh.id = :OLD.volume_horaire_ref_id    

  ) LOOP

    OSE_EVENT.DEMANDE_CALCUL( OSE_VALIDATION_REFERENTIEL.package_sujet, p.intervenant_id );

  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_VAR_VALIDATION
AFTER INSERT 
OR UPDATE OF 
  histo_creation,
	histo_destruction
OR DELETE ON VALIDATION
FOR EACH ROW
BEGIN

  IF :NEW.intervenant_id IS NOT NULL THEN
    OSE_EVENT.DEMANDE_CALCUL( OSE_VALIDATION_REFERENTIEL.package_sujet, :NEW.intervenant_id );
  END IF;
  
  IF :OLD.intervenant_id IS NOT NULL THEN
    OSE_EVENT.DEMANDE_CALCUL( OSE_VALIDATION_REFERENTIEL.package_sujet, :OLD.intervenant_id );
  END IF;

END;






/


CREATE OR REPLACE TRIGGER T_VAR_INTERVENANT_S
AFTER INSERT OR UPDATE OR DELETE ON INTERVENANT
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_VAR_REGLE_STRUCTURE_VAL_S
AFTER INSERT OR UPDATE OR DELETE ON REGLE_STRUCTURE_VALIDATION
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_VAR_SERVICE_REFERENTIEL_S
AFTER INSERT OR UPDATE OR DELETE ON SERVICE_REFERENTIEL
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_VAR_STATUT_INTERVENANT_S
AFTER INSERT OR UPDATE OR DELETE ON STATUT_INTERVENANT
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_VAR_STRUCTURE_S
AFTER INSERT OR UPDATE OR DELETE ON STRUCTURE
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_VAR_VALIDATION_S
AFTER INSERT OR UPDATE OR DELETE ON VALIDATION
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_VAR_VAL_VOL_HORAIRE_REF_S
AFTER INSERT OR UPDATE OR DELETE ON VALIDATION_VOL_HORAIRE_REF
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_VAR_VOLUME_HORAIRE_REF_S
AFTER INSERT OR UPDATE OR DELETE ON VOLUME_HORAIRE_REF
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;

/








