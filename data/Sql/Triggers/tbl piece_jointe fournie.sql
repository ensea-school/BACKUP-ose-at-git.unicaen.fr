CREATE OR REPLACE TRIGGER T_PJF_PIECE_JOINTE
AFTER INSERT
OR UPDATE OF
  type_piece_jointe_id,
	intervenant_id,
	validation_id,
	histo_creation,
	histo_destruction
OR DELETE ON PIECE_JOINTE
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.GET_ACTIF THEN RETURN; END IF;
  
  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_fournie', UNICAEN_TBL.make_params('intervenant_id', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_fournie', UNICAEN_TBL.make_params('intervenant_id', :OLD.intervenant_id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_PJF_DOSSIER
AFTER INSERT 
OR UPDATE OF 
  intervenant_id
OR DELETE ON DOSSIER
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.GET_ACTIF THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_fournie', UNICAEN_TBL.make_params('intervenant_id', :NEW.intervenant_id ) );
  END IF;
  
  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_fournie', UNICAEN_TBL.make_params('intervenant_id', :OLD.intervenant_id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_PJF_INTERVENANT
AFTER INSERT 
OR UPDATE OF 
  annee_id,
	histo_creation,
	histo_destruction
OR DELETE ON INTERVENANT
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.GET_ACTIF THEN RETURN; END IF;

  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_fournie', UNICAEN_TBL.make_params('intervenant_id', :NEW.id ) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_fournie', UNICAEN_TBL.make_params('intervenant_id', :OLD.id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_PJF_PIECE_JOINTE_FICHER
AFTER INSERT 
OR UPDATE OF 
  piece_jointe_id,
	fichier_id
OR DELETE ON PIECE_JOINTE_FICHIER
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.GET_ACTIF THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      pj.intervenant_id
    FROM
      piece_jointe pj
    WHERE
         pj.id = :NEW.piece_jointe_id
      OR pj.id = :OLD.piece_jointe_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_fournie', UNICAEN_TBL.make_params('intervenant_id', p.intervenant_id ) );

  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_PJF_FICHER
AFTER INSERT 
OR UPDATE OF 
  histo_creation,
	histo_destruction
OR DELETE ON FICHIER
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.GET_ACTIF THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      pj.intervenant_id
    FROM
      piece_jointe pj
      JOIN piece_jointe_fichier pjf ON pjf.piece_jointe_id = pj.id
    WHERE
         pjf.fichier_id = :NEW.id
      OR pjf.fichier_id = :OLD.id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_fournie', UNICAEN_TBL.make_params('intervenant_id', p.intervenant_id ) );

  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_PJF_VALIDATION
AFTER INSERT 
OR UPDATE OF 
  histo_creation,
	histo_destruction
OR DELETE ON VALIDATION
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.GET_ACTIF THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_fournie', UNICAEN_TBL.make_params('intervenant_id', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'piece_jointe_fournie', UNICAEN_TBL.make_params('intervenant_id', :OLD.intervenant_id ) );
  END IF;

END;

/









CREATE OR REPLACE TRIGGER T_PJF_DOSSIER_S
AFTER INSERT OR UPDATE OR DELETE ON DOSSIER
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_PJF_FICHER_S
AFTER INSERT OR UPDATE OR DELETE ON FICHIER
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_PJF_INTERVENANT_S
AFTER INSERT OR UPDATE OR DELETE ON INTERVENANT
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_PJF_PIECE_JOINTE_S
AFTER INSERT OR UPDATE OR DELETE ON PIECE_JOINTE
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_PJF_PIECE_JOINTE_FICHER_S
AFTER INSERT OR UPDATE OR DELETE ON PIECE_JOINTE_FICHIER
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_PJF_VALIDATION_S
AFTER INSERT OR UPDATE OR DELETE ON VALIDATION
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

