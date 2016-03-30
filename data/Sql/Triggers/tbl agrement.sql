CREATE OR REPLACE TRIGGER T_AGR_SERVICE
AFTER INSERT 
OR UPDATE OF 
    element_pedagogique_id
OR DELETE ON SERVICE
FOR EACH ROW
BEGIN

  IF :NEW.intervenant_id IS NOT NULL THEN
    OSE_EVENT.DEMANDE_CALCUL( ose_agrement.package_sujet, :NEW.intervenant_id );
  END IF;
  
  IF :OLD.intervenant_id IS NOT NULL THEN
    OSE_EVENT.DEMANDE_CALCUL( ose_agrement.package_sujet, :OLD.intervenant_id );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_AGR_ELEMENT_PEDAGOGIQUE
AFTER INSERT 
OR UPDATE OF 
    structure_id
OR DELETE ON ELEMENT_PEDAGOGIQUE
FOR EACH ROW
BEGIN

  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
    WHERE
         s.element_pedagogique_id = :NEW.id
      OR s.element_pedagogique_id = :OLD.id
  
  ) LOOP
  
    OSE_EVENT.DEMANDE_CALCUL( ose_agrement.package_sujet, p.intervenant_id );
  
  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_AGR_TA_STATUT
AFTER INSERT 
OR UPDATE OF 
    type_agrement_id,
		obligatoire,
		histo_creation,
		histo_destruction,
		premier_recrutement,
		statut_intervenant_id
OR DELETE ON TYPE_AGREMENT_STATUT
FOR EACH ROW
BEGIN

  FOR p IN (
  
    SELECT DISTINCT
      i.id intervenant_id
    FROM
      statut_intervenant si
      JOIN intervenant i ON i.statut_id = si.id
    WHERE
         si.id = :NEW.statut_intervenant_id
      OR si.id = :OLD.statut_intervenant_id
  
  ) LOOP
  
    OSE_EVENT.DEMANDE_CALCUL( ose_agrement.package_sujet, p.intervenant_id );
  
  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_AGR_INTERVENANT
AFTER INSERT 
OR UPDATE OF 
    histo_creation,
		histo_destruction,
		premier_recrutement,
		statut_id,
		annee_id
OR DELETE ON INTERVENANT
FOR EACH ROW
BEGIN

  IF :NEW.id IS NOT NULL THEN
    OSE_EVENT.DEMANDE_CALCUL( ose_agrement.package_sujet, :NEW.id );
  END IF;
  
  IF :OLD.id IS NOT NULL THEN
    OSE_EVENT.DEMANDE_CALCUL( ose_agrement.package_sujet, :OLD.id );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_AGR_AGREMENT
AFTER INSERT 
OR UPDATE OF 
    type_agrement_id,
		intervenant_id,
		histo_creation,
		histo_destruction,
		structure_id
OR DELETE ON AGREMENT
FOR EACH ROW
BEGIN

  IF :NEW.intervenant_id IS NOT NULL THEN
    OSE_EVENT.DEMANDE_CALCUL( ose_agrement.package_sujet, :NEW.intervenant_id );
  END IF;
  
  IF :OLD.intervenant_id IS NOT NULL THEN
    OSE_EVENT.DEMANDE_CALCUL( ose_agrement.package_sujet, :OLD.intervenant_id );
  END IF;

END;

/







CREATE OR REPLACE TRIGGER T_AGR_SERVICE_S
AFTER INSERT OR UPDATE OR DELETE ON SERVICE
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_AGR_ELEMENT_PEDAGOGIQUE_S
AFTER INSERT OR UPDATE OR DELETE ON ELEMENT_PEDAGOGIQUE
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_AGR_TA_STATUT_S
AFTER INSERT OR UPDATE OR DELETE ON TYPE_AGREMENT_STATUT
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_AGR_INTERVENANT_S
AFTER INSERT OR UPDATE OR DELETE ON INTERVENANT
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_AGR_AGREMENT_S
AFTER INSERT OR UPDATE OR DELETE ON AGREMENT
BEGIN
  OSE_EVENT.CALCULER_DEMANDES;
END;
