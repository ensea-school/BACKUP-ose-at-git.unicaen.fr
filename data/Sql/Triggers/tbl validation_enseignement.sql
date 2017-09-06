CREATE OR REPLACE TRIGGER T_VAE_SERVICE
AFTER INSERT
OR UPDATE OF
	intervenant_id,
	element_pedagogique_id,
	histo_creation,
	histo_destruction
OR DELETE ON SERVICE
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_VAE_VOLUME_HORAIRE
AFTER INSERT
OR UPDATE OF
  type_volume_horaire_id,
	service_id,
	histo_creation,
	histo_destruction
OR DELETE ON VOLUME_HORAIRE
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
    WHERE
      s.id = :NEW.service_id
      OR s.id = :OLD.service_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_VAE_INTERVENANT
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
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.id ) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_VAE_STATUT_INTERVENANT
AFTER UPDATE OF
  type_intervenant_id
ON STATUT_INTERVENANT
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

    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_VAE_REGLE_STRUCTURE_VAL
AFTER UPDATE OF
  priorite,
	type_intervenant_id,
	type_volume_horaire_id
ON REGLE_STRUCTURE_VALIDATION
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

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

    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

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

    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_VAE_STRUCTURE
AFTER UPDATE OF
	niveau,
	histo_creation,
	histo_destruction
ON STRUCTURE
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      tve.intervenant_id
    FROM
      tbl_validation_enseignement tve
    WHERE
         tve.structure_id = :NEW.id
      OR tve.structure_id = :OLD.id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_VAE_ELEMENT_PEDAGOGIQUE
AFTER INSERT
OR UPDATE OF
  structure_id
OR DELETE ON ELEMENT_PEDAGOGIQUE
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
    WHERE
         s.element_pedagogique_id = :NEW.id
      OR s.element_pedagogique_id = :OLD.id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_VAE_VALIDATION_VOL_HORAIRE
AFTER INSERT
OR UPDATE OF
  volume_horaire_id,
	validation_id
OR DELETE ON VALIDATION_VOL_HORAIRE
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
      JOIN volume_horaire vh ON vh.service_id = s.id
    WHERE
      vh.id = :NEW.volume_horaire_id
      OR vh.id = :OLD.volume_horaire_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_VAE_VALIDATION
AFTER INSERT
OR UPDATE OF
  histo_creation,
	histo_destruction
OR DELETE ON VALIDATION
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'validation_enseignement', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;






/

CREATE OR REPLACE TRIGGER T_VAE_ELEMENT_PEDAGOGIQUE_S
AFTER INSERT OR UPDATE OR DELETE ON ELEMENT_PEDAGOGIQUE
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_VAE_INTERVENANT_S
AFTER INSERT OR UPDATE OR DELETE ON INTERVENANT
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_VAE_REGLE_STRUCTURE_VAL_S
AFTER INSERT OR UPDATE OR DELETE ON REGLE_STRUCTURE_VALIDATION
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_VAE_SERVICE_S
AFTER INSERT OR UPDATE OR DELETE ON SERVICE
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_VAE_STATUT_INTERVENANT_S
AFTER INSERT OR UPDATE OR DELETE ON STATUT_INTERVENANT
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_VAE_STRUCTURE_S
AFTER INSERT OR UPDATE OR DELETE ON STRUCTURE
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_VAE_VALIDATION_S
AFTER INSERT OR UPDATE OR DELETE ON VALIDATION
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_VAE_VALIDATION_VOL_HORAIRE_S
AFTER INSERT OR UPDATE OR DELETE ON VALIDATION_VOL_HORAIRE
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_VAE_VOLUME_HORAIRE_S
AFTER INSERT OR UPDATE OR DELETE ON VOLUME_HORAIRE
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/










