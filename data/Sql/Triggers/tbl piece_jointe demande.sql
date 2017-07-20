CREATE OR REPLACE TRIGGER T_PJD_SERVICE
AFTER INSERT 
OR UPDATE OF 
  intervenant_id,
	histo_creation,
	histo_destruction,
	element_pedagogique_id
OR DELETE ON SERVICE
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.GET_ACTIF THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_demande', UNICAEN_TBL.make_params('intervenant_id', :NEW.intervenant_id ) );
  END IF;
  
  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_demande', UNICAEN_TBL.make_params('intervenant_id', :OLD.intervenant_id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_PJD_VOLUME_HORAIRE
AFTER INSERT 
OR UPDATE OF 
  heures,
	service_id,
	type_volume_horaire_id,
	histo_creation,
	histo_destruction,
	motif_non_paiement_id
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
  
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_demande', UNICAEN_TBL.make_params('intervenant_id', p.intervenant_id ) );
  
  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_PJD_INTERVENANT
AFTER INSERT 
OR UPDATE OF 
  annee_id,
	statut_id,
	bic,
	iban,
	premier_recrutement,
	histo_creation,
	histo_destruction
OR DELETE ON INTERVENANT
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.GET_ACTIF THEN RETURN; END IF;

  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_demande', UNICAEN_TBL.make_params('intervenant_id', :NEW.id ) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_demande', UNICAEN_TBL.make_params('intervenant_id', :OLD.id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_PJD_DOSSIER
AFTER INSERT 
OR UPDATE OF 
  intervenant_id,
	histo_creation,
	histo_destruction,
	rib
OR DELETE ON DOSSIER
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.GET_ACTIF THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_demande', UNICAEN_TBL.make_params('intervenant_id', :NEW.intervenant_id ) );
  END IF;
  
  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_demande', UNICAEN_TBL.make_params('intervenant_id', :OLD.intervenant_id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_PJD_T_PIECE_JOINTE_STATUT
AFTER INSERT 
OR UPDATE OF 
  statut_intervenant_id,
	histo_creation,
	histo_destruction,
	type_piece_jointe_id,
	seuil_hetd,
	premier_recrutement
OR DELETE ON TYPE_PIECE_JOINTE_STATUT
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.GET_ACTIF THEN RETURN; END IF;

  FOR p IN (
  
    SELECT DISTINCT
      i.id intervenant_id
    FROM
      intervenant i
    WHERE
         i.statut_id = :NEW.statut_intervenant_id
      OR i.statut_id = :OLD.statut_intervenant_id
  
  ) LOOP
  
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_demande', UNICAEN_TBL.make_params('intervenant_id', p.intervenant_id ) );
  
  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_PJD_TYPE_PIECE_JOINTE
AFTER INSERT 
OR UPDATE OF 
  histo_creation,
	histo_destruction,
	code
OR DELETE ON TYPE_PIECE_JOINTE
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.GET_ACTIF THEN RETURN; END IF;

  FOR p IN (
  
    SELECT DISTINCT
      i.id intervenant_id
    FROM
      intervenant i
      JOIN statut_intervenant si ON si.id = i.statut_id
      JOIN type_piece_jointe_statut tpjs ON tpjs.statut_intervenant_id = si.id
    WHERE
         TPJS.TYPE_PIECE_JOINTE_ID = :NEW.id
      OR TPJS.TYPE_PIECE_JOINTE_ID = :OLD.id
  
  ) LOOP
  
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_demande', UNICAEN_TBL.make_params('intervenant_id', p.intervenant_id ) );
  
  END LOOP;

END;

/








CREATE OR REPLACE TRIGGER T_PJD_DOSSIER_S
AFTER INSERT OR UPDATE OR DELETE ON DOSSIER
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_PJD_INTERVENANT_S
AFTER INSERT OR UPDATE OR DELETE ON INTERVENANT
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_PJD_SERVICE_S
AFTER INSERT OR UPDATE OR DELETE ON SERVICE
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_PJD_T_PIECE_JOINTE_STATUT_S
AFTER INSERT OR UPDATE OR DELETE ON TYPE_PIECE_JOINTE_STATUT
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_PJD_TYPE_PIECE_JOINTE_S
AFTER INSERT OR UPDATE OR DELETE ON TYPE_PIECE_JOINTE
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_PJD_VOLUME_HORAIRE_S
AFTER INSERT OR UPDATE OR DELETE ON VOLUME_HORAIRE
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/


