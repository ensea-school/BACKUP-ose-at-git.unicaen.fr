CREATE OR REPLACE TRIGGER T_DOS_INTERVENANT
AFTER INSERT
OR UPDATE OF
  annee_id,
  statut_id,
	histo_creation,
	histo_destruction
OR DELETE ON INTERVENANT
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'dossier', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.id ) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'dossier', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_DOS_STATUT_INTERVENANT
AFTER INSERT
OR UPDATE OF
    peut_saisir_dossier
OR DELETE ON STATUT_INTERVENANT
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      i.id intervenant_id
    FROM
      intervenant i
    WHERE
         i.statut_id = :NEW.id
      OR i.statut_id = :OLD.id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'dossier', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_DOS_DOSSIER
AFTER INSERT
OR UPDATE OF
  intervenant_id,
	histo_creation,
	histo_destruction
OR DELETE ON DOSSIER
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'dossier', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'dossier', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_DOS_VALIDATION
AFTER INSERT
OR UPDATE OF
    intervenant_id,
    type_validation_id,
    histo_creation,
    histo_destruction
OR DELETE ON VALIDATION
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'dossier', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'dossier', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;

/







CREATE OR REPLACE TRIGGER T_DOS_INTERVENANT_S
AFTER INSERT OR UPDATE OR DELETE ON INTERVENANT
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_DOS_STATUT_INTERVENANT_S
AFTER INSERT OR UPDATE OR DELETE ON STATUT_INTERVENANT
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_DOS_DOSSIER_S
AFTER INSERT OR UPDATE OR DELETE ON VALIDATION
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_DOS_VALIDATION_S
AFTER INSERT OR UPDATE OR DELETE ON VALIDATION
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;