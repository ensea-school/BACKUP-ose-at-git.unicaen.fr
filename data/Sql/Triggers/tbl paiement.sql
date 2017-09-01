CREATE OR REPLACE TRIGGER T_PAI_INTERVENANT
AFTER INSERT 
OR UPDATE OF 
  annee_id,
	structure_id
OR DELETE ON INTERVENANT
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.GET_ACTIF THEN RETURN; END IF;

  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'paiement', UNICAEN_TBL.make_params('intervenant_id', :NEW.id ) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'paiement', UNICAEN_TBL.make_params('intervenant_id', :OLD.id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_PAI_SERVICE
AFTER INSERT 
OR UPDATE OF 
    element_pedagogique_id
OR DELETE ON SERVICE
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.GET_ACTIF THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'paiement', UNICAEN_TBL.make_params('intervenant_id', :NEW.intervenant_id ) );
  END IF;
  
  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'paiement', UNICAEN_TBL.make_params('intervenant_id', :OLD.intervenant_id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_PAI_SERVICE_REFERENTIEL
AFTER INSERT 
OR UPDATE OF 
    structure_id
OR DELETE ON SERVICE_REFERENTIEL
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.GET_ACTIF THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'paiement', UNICAEN_TBL.make_params('intervenant_id', :NEW.intervenant_id ) );
  END IF;
  
  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'paiement', UNICAEN_TBL.make_params('intervenant_id', :OLD.intervenant_id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_PAI_ELEMENT_PEDAGOGIQUE
AFTER INSERT 
OR UPDATE OF 
  structure_id
OR DELETE ON ELEMENT_PEDAGOGIQUE
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.GET_ACTIF THEN RETURN; END IF;

  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
    WHERE
         s.element_pedagogique_id = :NEW.id
      OR s.element_pedagogique_id = :OLD.id
  
  ) LOOP
  
    UNICAEN_TBL.DEMANDE_CALCUL( 'paiement', UNICAEN_TBL.make_params('intervenant_id', p.intervenant_id ) );
  
  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_PAI_MISE_EN_PAIEMENT
AFTER INSERT 
OR UPDATE OF 
  periode_paiement_id,
	formule_res_service_id,
	formule_res_service_ref_id,
  heures,
	histo_creation,
	histo_destruction
OR DELETE ON MISE_EN_PAIEMENT
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.GET_ACTIF THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      fr.intervenant_id
    FROM
      formule_resultat fr
      LEFT JOIN formule_resultat_service frs ON frs.formule_resultat_id = fr.id
      LEFT JOIN formule_resultat_service_ref frsr ON frsr.formule_resultat_id = fr.id
    WHERE
         (frs.id  IS NOT NULL AND (frs.id  = :OLD.formule_res_service_id     OR frs.id  = :NEW.formule_res_service_id    ))
      OR (frsr.id IS NOT NULL AND (frsr.id = :OLD.formule_res_service_ref_id OR frsr.id = :NEW.formule_res_service_ref_id))

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'paiement', UNICAEN_TBL.make_params('intervenant_id', p.intervenant_id ) );

  END LOOP;

END;

/








CREATE OR REPLACE TRIGGER T_PAI_ELEMENT_PEDAGOGIQUE_S
AFTER INSERT OR UPDATE OR DELETE ON ELEMENT_PEDAGOGIQUE
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/


CREATE OR REPLACE TRIGGER T_PAI_INTERVENANT_S
AFTER INSERT OR UPDATE OR DELETE ON INTERVENANT
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/


CREATE OR REPLACE TRIGGER T_PAI_MISE_EN_PAIEMENT_S
AFTER INSERT OR UPDATE OR DELETE ON MISE_EN_PAIEMENT
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/


CREATE OR REPLACE TRIGGER T_PAI_SERVICE_S
AFTER INSERT OR UPDATE OR DELETE ON SERVICE
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/


CREATE OR REPLACE TRIGGER T_PAI_SERVICE_REFERENTIEL_S
AFTER INSERT OR UPDATE OR DELETE ON SERVICE_REFERENTIEL
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;
