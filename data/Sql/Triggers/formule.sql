create or replace TRIGGER "OSE"."F_CONTRAT"
  AFTER DELETE OR UPDATE OF INTERVENANT_ID, HISTO_CREATION, HISTO_DESTRUCTION, STRUCTURE_ID, DATE_RETOUR_SIGNE, VALIDATION_ID ON "OSE"."CONTRAT"
  REFERENCING FOR EACH ROW
  BEGIN
  
  IF NOT OSE_EVENT.GET_ACTIF THEN RETURN; END IF;
  
  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      volume_horaire vh
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
    WHERE
      1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      AND (vh.contrat_id = :OLD.id OR vh.contrat_id = :NEW.id)

  ) LOOP

    OSE_EVENT.DEMANDE_CALCUL( OSE_FORMULE.package_sujet, p.intervenant_id );

  END LOOP;

END;

/

--------------------------------------------------------
--  DDL for Trigger F_CONTRAT_S
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_CONTRAT_S" 
AFTER UPDATE OR DELETE ON contrat
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_ELEMENT_MODULATEUR
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_ELEMENT_MODULATEUR" 
AFTER INSERT OR UPDATE OR DELETE ON element_modulateur
FOR EACH ROW
BEGIN

  IF NOT OSE_EVENT.GET_ACTIF THEN RETURN; END IF;

  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
    WHERE
      1 = OSE_DIVERS.COMPRISE_ENTRE( s.histo_creation, s.histo_destruction )
      AND (s.element_pedagogique_id = :OLD.element_id OR s.element_pedagogique_id = :NEW.element_id)
      
  ) LOOP
    
    OSE_EVENT.DEMANDE_CALCUL( OSE_FORMULE.package_sujet, p.intervenant_id );
    
  END LOOP;

END;
/

--------------------------------------------------------
--  DDL for Trigger F_ELEMENT_MODULATEUR_S
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_ELEMENT_MODULATEUR_S" 
AFTER INSERT OR UPDATE OR DELETE ON element_modulateur
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_ELEMENT_PEDAGOGIQUE
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_ELEMENT_PEDAGOGIQUE" 
  AFTER DELETE OR UPDATE OF ID, STRUCTURE_ID, PERIODE_ID, TAUX_FOAD, FI, FC, FA, HISTO_CREATION, HISTO_DESTRUCTION, TAUX_FA, TAUX_FC, TAUX_FI, ANNEE_ID ON "OSE"."ELEMENT_PEDAGOGIQUE"
  REFERENCING FOR EACH ROW
  BEGIN 
  
  IF NOT OSE_EVENT.GET_ACTIF THEN RETURN; END IF;
  
  FOR p IN
    ( SELECT DISTINCT s.intervenant_id
    FROM service s
    WHERE (s.element_pedagogique_id = :NEW.id
    OR s.element_pedagogique_id     = :OLD.id)
    AND 1                           = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
    ) LOOP OSE_EVENT.DEMANDE_CALCUL( OSE_FORMULE.package_sujet, p.intervenant_id );
END LOOP;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_ELEMENT_PEDAGOGIQUE_S
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_ELEMENT_PEDAGOGIQUE_S" AFTER
UPDATE OR DELETE ON element_pedagogique BEGIN 
  OSE_EVENT.CALCULER_DEMANDES;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_INTERVENANT
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_INTERVENANT" 
  AFTER UPDATE OF ID, DATE_NAISSANCE, STATUT_ID, STRUCTURE_ID, HISTO_CREATION, HISTO_DESTRUCTION, PREMIER_RECRUTEMENT, ANNEE_ID ON "OSE"."INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN

  IF NOT OSE_EVENT.GET_ACTIF THEN RETURN; END IF;

  FOR p IN (
      
    SELECT DISTINCT
      fr.intervenant_id
    FROM
      formule_resultat fr
    WHERE
      fr.intervenant_id = :NEW.id OR fr.intervenant_id = :OLD.id
  
  ) LOOP
  
    OSE_EVENT.DEMANDE_CALCUL( OSE_FORMULE.package_sujet, p.intervenant_id );

  END LOOP;
  
END;
/

--------------------------------------------------------
--  DDL for Trigger F_INTERVENANT_S
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_INTERVENANT_S" 
AFTER UPDATE ON "OSE"."INTERVENANT"
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_MODIF_SERVICE_DU
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_MODIF_SERVICE_DU" 
AFTER INSERT OR UPDATE OR DELETE ON modification_service_du
FOR EACH ROW
BEGIN

  IF NOT OSE_EVENT.GET_ACTIF THEN RETURN; END IF;

  IF DELETING OR UPDATING THEN
    OSE_EVENT.DEMANDE_CALCUL( OSE_FORMULE.package_sujet, :OLD.intervenant_id );
  END IF;
  IF INSERTING OR UPDATING THEN
    OSE_EVENT.DEMANDE_CALCUL( OSE_FORMULE.package_sujet, :NEW.intervenant_id );
  END IF;

END;
/

--------------------------------------------------------
--  DDL for Trigger F_MODIF_SERVICE_DU_S
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_MODIF_SERVICE_DU_S" 
AFTER INSERT OR UPDATE OR DELETE ON modification_service_du
BEGIN
    OSE_EVENT.CALCULER_DEMANDES;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_MODULATEUR
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_MODULATEUR" 
AFTER UPDATE OR DELETE ON modulateur
FOR EACH ROW
BEGIN
  
  IF NOT OSE_EVENT.GET_ACTIF THEN RETURN; END IF;
  
  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
      JOIN element_modulateur em ON 
        em.element_id   = s.element_pedagogique_id 
        AND 1 = ose_divers.comprise_entre( em.histo_creation, em.histo_destruction )
    WHERE
      1 = OSE_DIVERS.COMPRISE_ENTRE( s.histo_creation, s.histo_destruction )
      AND (em.modulateur_id = :OLD.id OR em.modulateur_id = :NEW.id)

  ) LOOP

    OSE_EVENT.DEMANDE_CALCUL( OSE_FORMULE.package_sujet, p.intervenant_id );

  END LOOP;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_MODULATEUR_S
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_MODULATEUR_S" 
AFTER UPDATE OR DELETE ON modulateur
BEGIN
    OSE_EVENT.CALCULER_DEMANDES;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_MOTIF_MODIFICATION_SERVICE
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_MOTIF_MODIFICATION_SERVICE" 
AFTER UPDATE OR DELETE ON MOTIF_MODIFICATION_SERVICE
FOR EACH ROW
BEGIN

  IF NOT OSE_EVENT.GET_ACTIF THEN RETURN; END IF;

  FOR p IN (
  
    SELECT DISTINCT
      intervenant_id
    FROM
      modification_service_du msd
    WHERE
      1 = OSE_DIVERS.COMPRISE_ENTRE( msd.histo_creation, msd.histo_destruction )
      AND (msd.motif_id = :NEW.id OR msd.motif_id = :OLD.id)
      
  ) LOOP
  
    OSE_EVENT.DEMANDE_CALCUL( OSE_FORMULE.package_sujet, p.intervenant_id );
  
  END LOOP;

END;
/

--------------------------------------------------------
--  DDL for Trigger F_MOTIF_MODIFICATION_SERVICE_S
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_MOTIF_MODIFICATION_SERVICE_S" 
AFTER UPDATE OR DELETE ON MOTIF_MODIFICATION_SERVICE
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_SERVICE
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_SERVICE" 
AFTER INSERT OR UPDATE OR DELETE ON service
FOR EACH ROW
BEGIN

  IF NOT OSE_EVENT.GET_ACTIF THEN RETURN; END IF;

  IF DELETING OR UPDATING THEN
    OSE_EVENT.DEMANDE_CALCUL( OSE_FORMULE.package_sujet, :OLD.intervenant_id );
  END IF;
  IF INSERTING OR UPDATING THEN
    OSE_EVENT.DEMANDE_CALCUL( OSE_FORMULE.package_sujet, :NEW.intervenant_id );
  END IF;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_SERVICE_REFERENTIEL
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_SERVICE_REFERENTIEL" 
AFTER INSERT OR UPDATE OR DELETE ON service_referentiel
FOR EACH ROW
BEGIN

  IF NOT OSE_EVENT.GET_ACTIF THEN RETURN; END IF;

  IF DELETING OR UPDATING THEN
    OSE_EVENT.DEMANDE_CALCUL( OSE_FORMULE.package_sujet, :OLD.intervenant_id );
  END IF;
  IF INSERTING OR UPDATING THEN
    OSE_EVENT.DEMANDE_CALCUL( OSE_FORMULE.package_sujet, :NEW.intervenant_id );
  END IF;

END;
/

--------------------------------------------------------
--  DDL for Trigger F_SERVICE_REFERENTIEL_S
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_SERVICE_REFERENTIEL_S" 
AFTER INSERT OR UPDATE OR DELETE ON service_referentiel
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_SERVICE_S
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_SERVICE_S" 
AFTER INSERT OR UPDATE OR DELETE ON service
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_STATUT_INTERVENANT
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_STATUT_INTERVENANT" 
AFTER UPDATE ON statut_intervenant
FOR EACH ROW
BEGIN

  IF NOT OSE_EVENT.GET_ACTIF THEN RETURN; END IF;

  FOR p IN (
  
    SELECT DISTINCT
      fr.intervenant_id
    FROM
      intervenant i
      JOIN formule_resultat fr ON fr.intervenant_id = i.id
    WHERE
      (i.statut_id = :NEW.id OR i.statut_id = :OLD.id)
      AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
  
  ) LOOP
  
    OSE_EVENT.DEMANDE_CALCUL( OSE_FORMULE.package_sujet, p.intervenant_id );
  
  END LOOP;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_STATUT_INTERVENANT_S
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_STATUT_INTERVENANT_S" 
AFTER UPDATE ON statut_intervenant
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_TYPE_INTERVENTION
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_TYPE_INTERVENTION" 
AFTER UPDATE ON type_intervention
FOR EACH ROW
BEGIN

  IF NOT OSE_EVENT.GET_ACTIF THEN RETURN; END IF;

  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id
    FROM
      volume_horaire vh
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
    WHERE
      1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      AND (vh.type_intervention_id = :NEW.id OR vh.type_intervention_id = :OLD.id)
  
  ) LOOP
  
    OSE_EVENT.DEMANDE_CALCUL( OSE_FORMULE.package_sujet, p.intervenant_id );
  
  END LOOP;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_TYPE_INTERVENTION_S
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_TYPE_INTERVENTION_S" 
AFTER UPDATE ON type_intervention
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_VALIDATION
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION" 
AFTER UPDATE ON validation
FOR EACH ROW
BEGIN

  IF NOT OSE_EVENT.GET_ACTIF THEN RETURN; END IF;

  FOR p IN ( -- validations de volume horaire

    SELECT DISTINCT
      s.intervenant_id
    FROM
      validation_vol_horaire vvh
      JOIN volume_horaire vh ON vh.id = vvh.volume_horaire_id AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
    WHERE
      (vvh.validation_id = :OLD.ID OR vvh.validation_id = :NEW.id)

  ) LOOP

    OSE_EVENT.DEMANDE_CALCUL( OSE_FORMULE.package_sujet, p.intervenant_id );

  END LOOP;

  FOR p IN ( -- validations de contrat

    SELECT DISTINCT
      s.intervenant_id
    FROM
      contrat c
      JOIN volume_horaire vh ON vh.contrat_id = c.id AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
    WHERE
      (c.validation_id = :OLD.ID OR c.validation_id = :NEW.id)

  ) LOOP

    OSE_EVENT.DEMANDE_CALCUL( OSE_FORMULE.package_sujet, p.intervenant_id );

  END LOOP;

END;
/

--------------------------------------------------------
--  DDL for Trigger F_VALIDATION_S
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION_S" 
AFTER UPDATE ON validation
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_VALIDATION_VOL_HORAIRE
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION_VOL_HORAIRE" 
AFTER INSERT OR UPDATE OR DELETE ON validation_vol_horaire
FOR EACH ROW
BEGIN

  IF NOT OSE_EVENT.GET_ACTIF THEN RETURN; END IF;

  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id
    FROM
      volume_horaire vh
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
    WHERE
      1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      AND (vh.id = :NEW.volume_horaire_id OR vh.id = :OLD.volume_horaire_id)
  
  ) LOOP
  
    OSE_EVENT.DEMANDE_CALCUL( OSE_FORMULE.package_sujet, p.intervenant_id );
  
  END LOOP;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_VALIDATION_VOL_HORAIRE_REF
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION_VOL_HORAIRE_REF" 
AFTER INSERT OR UPDATE OR DELETE ON validation_vol_horaire_ref
FOR EACH ROW
BEGIN

  IF NOT OSE_EVENT.GET_ACTIF THEN RETURN; END IF;

  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id
    FROM
      volume_horaire_ref vh
      JOIN service_referentiel s ON s.id = vh.service_referentiel_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
    WHERE
      1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      AND (vh.id = :NEW.volume_horaire_ref_id OR vh.id = :OLD.volume_horaire_ref_id)
  
  ) LOOP
  
    OSE_EVENT.DEMANDE_CALCUL( OSE_FORMULE.package_sujet, p.intervenant_id );
  
  END LOOP;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_VALIDATION_VOL_HORAIRE_REF_S
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION_VOL_HORAIRE_REF_S" 
AFTER INSERT OR UPDATE OR DELETE ON validation_vol_horaire_ref
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_VALIDATION_VOL_HORAIRE_S
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION_VOL_HORAIRE_S" 
AFTER INSERT OR UPDATE OR DELETE ON validation_vol_horaire
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_VOLUME_HORAIRE
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_VOLUME_HORAIRE" 
AFTER INSERT 
OR UPDATE OF TYPE_VOLUME_HORAIRE_ID, SERVICE_ID, PERIODE_ID, TYPE_INTERVENTION_ID, 
             HEURES, MOTIF_NON_PAIEMENT_ID, CONTRAT_ID,
             HISTO_CREATION, HISTO_MODIFICATION, HISTO_DESTRUCTION
OR DELETE ON volume_horaire
FOR EACH ROW
BEGIN

  IF NOT OSE_EVENT.GET_ACTIF THEN RETURN; END IF;

  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
    WHERE
      1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
      AND (s.id = :NEW.service_id OR s.id = :OLD.service_id)
  
  ) LOOP
  
    OSE_EVENT.DEMANDE_CALCUL( OSE_FORMULE.package_sujet, p.intervenant_id );
  
  END LOOP;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_VOLUME_HORAIRE_REF
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_VOLUME_HORAIRE_REF" 
AFTER INSERT OR UPDATE OR DELETE ON volume_horaire_ref
FOR EACH ROW
BEGIN

  IF NOT OSE_EVENT.GET_ACTIF THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service_referentiel s
    WHERE
      1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
      AND (s.id = :NEW.service_referentiel_id OR s.id = :OLD.service_referentiel_id)

  ) LOOP

    OSE_EVENT.DEMANDE_CALCUL( OSE_FORMULE.package_sujet, p.intervenant_id );
  END LOOP;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_VOLUME_HORAIRE_REF_S
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_VOLUME_HORAIRE_REF_S" 
AFTER INSERT OR UPDATE OR DELETE ON volume_horaire_ref
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;
/

--------------------------------------------------------
--  DDL for Trigger F_VOLUME_HORAIRE_S
--------------------------------------------------------

CREATE OR REPLACE TRIGGER "OSE"."F_VOLUME_HORAIRE_S" 
AFTER INSERT OR UPDATE OR DELETE ON volume_horaire
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;
/
