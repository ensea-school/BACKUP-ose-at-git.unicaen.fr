CREATE OR REPLACE TRIGGER T_SRS_INTERVENANT
AFTER INSERT 
OR UPDATE OF 
  annee_id,
	statut_id,
	histo_creation,
	histo_destruction
OR DELETE ON INTERVENANT
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.GET_ACTIF THEN RETURN; END IF;

  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service_saisie', UNICAEN_TBL.make_params('intervenant_id', :NEW.id ) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service_saisie', UNICAEN_TBL.make_params('intervenant_id', :OLD.id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_SRS_STATUT_INTERVENANT
AFTER UPDATE OF 
    peut_saisir_service,
	peut_saisir_referentiel
ON STATUT_INTERVENANT
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.GET_ACTIF THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      i.id intervenant_id
    FROM
      intervenant i
    WHERE
         i.statut_id = :NEW.id
      OR i.statut_id = :OLD.id
  
  ) LOOP
  
    UNICAEN_TBL.DEMANDE_CALCUL( 'service_saisie', UNICAEN_TBL.make_params('intervenant_id', p.intervenant_id ) );
  
  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_SRS_SERVICE
AFTER INSERT 
OR UPDATE OF 
  intervenant_id,
	histo_creation,
	histo_destruction
OR DELETE ON SERVICE
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.GET_ACTIF THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service_saisie', UNICAEN_TBL.make_params('intervenant_id', :NEW.intervenant_id ) );
  END IF;
  
  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service_saisie', UNICAEN_TBL.make_params('intervenant_id', :OLD.intervenant_id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_SRS_VOLUME_HORAIRE
AFTER INSERT 
OR UPDATE OF 
  heures,
	service_id,
	type_volume_horaire_id,
	histo_creation,
	histo_destruction
OR DELETE ON VOLUME_HORAIRE
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.GET_ACTIF THEN RETURN; END IF;

  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
    WHERE
      s.id = :NEW.service_id
      OR s.id = :OLD.service_id    

  ) LOOP
  
    UNICAEN_TBL.DEMANDE_CALCUL( 'service_saisie', UNICAEN_TBL.make_params('intervenant_id', p.intervenant_id ) );
  
  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_SRS_SERVICE_REFERENTIEL
AFTER INSERT 
OR UPDATE OF 
	intervenant_id,
	histo_creation,
	histo_destruction
OR DELETE ON SERVICE_REFERENTIEL
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.GET_ACTIF THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service_saisie', UNICAEN_TBL.make_params('intervenant_id', :NEW.intervenant_id ) );
  END IF;
  
  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'service_saisie', UNICAEN_TBL.make_params('intervenant_id', :OLD.intervenant_id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_SRS_VOLUME_HORAIRE_REF
AFTER INSERT 
OR UPDATE OF 
  heures,
	service_referentiel_id,
	type_volume_horaire_id,
	histo_creation,
	histo_destruction
OR DELETE ON VOLUME_HORAIRE_REF
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.GET_ACTIF THEN RETURN; END IF;

  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id
    FROM
      service_referentiel s
    WHERE
      s.id = :NEW.service_referentiel_id
      OR s.id = :OLD.service_referentiel_id    

  ) LOOP
  
    UNICAEN_TBL.DEMANDE_CALCUL( 'service_saisie', UNICAEN_TBL.make_params('intervenant_id', p.intervenant_id ) );
  
  END LOOP;

END;

/









CREATE OR REPLACE TRIGGER T_SRS_INTERVENANT_S
AFTER INSERT OR UPDATE OR DELETE ON INTERVENANT
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_SRS_SERVICE_S
AFTER INSERT OR UPDATE OR DELETE ON SERVICE
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_SRS_SERVICE_REFERENTIEL_S
AFTER INSERT OR UPDATE OR DELETE ON SERVICE_REFERENTIEL
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_SRS_STATUT_INTERVENANT_S
AFTER INSERT OR UPDATE OR DELETE ON STATUT_INTERVENANT
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_SRS_VOLUME_HORAIRE_S
AFTER INSERT OR UPDATE OR DELETE ON VOLUME_HORAIRE
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_SRS_VOLUME_HORAIRE_REF_S
AFTER INSERT OR UPDATE OR DELETE ON VOLUME_HORAIRE_REF
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
