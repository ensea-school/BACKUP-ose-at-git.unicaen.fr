CREATE OR REPLACE TRIGGER T_CRT_INTERVENANT
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
    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.id ) );
  END IF;

  IF :OLD.id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_CRT_STATUT_INTERVENANT
AFTER UPDATE OF
  peut_avoir_contrat
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

    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_CRT_SERVICE
AFTER INSERT
OR UPDATE OF
  intervenant_id,
	histo_creation,
	histo_destruction
OR DELETE ON SERVICE
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_CRT_SERVICE_REFERENTIEL
AFTER INSERT
OR UPDATE OF
	intervenant_id,
  structure_id,
	histo_creation,
	histo_destruction
OR DELETE ON SERVICE_REFERENTIEL
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_CRT_VOLUME_HORAIRE
AFTER INSERT
OR UPDATE OF
  service_id,
	contrat_id,
	heures,
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

    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_CRT_VOLUME_HORAIRE_REF
AFTER INSERT
OR UPDATE OF
  service_referentiel_id,
	heures,
	histo_creation,
	histo_destruction
OR DELETE ON VOLUME_HORAIRE_REF
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service_referentiel s
    WHERE
      s.id = :NEW.service_referentiel_id
      OR s.id = :OLD.service_referentiel_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_CRT_ELEMENT_PEDAGOGIQUE
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

    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_CRT_CONTRAT
AFTER INSERT
OR UPDATE OF
	histo_creation,
	histo_destruction,
	validation_id,
	date_retour_signe
OR DELETE ON CONTRAT
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_CRT_VALIDATION
AFTER INSERT
OR UPDATE OF
  histo_creation,
	histo_destruction
OR DELETE ON VALIDATION
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF :NEW.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', :NEW.intervenant_id ) );
  END IF;

  IF :OLD.intervenant_id IS NOT NULL THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', :OLD.intervenant_id ) );
  END IF;

END;

/

CREATE OR REPLACE TRIGGER T_CRT_VALIDATION_VOL_HORAIRE
AFTER INSERT
OR UPDATE OF
  validation_id,
	volume_horaire_id
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

    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;

/

CREATE OR REPLACE TRIGGER T_CRT_VAL_VOL_HORAIRE_REF
AFTER INSERT
OR UPDATE OF
  volume_horaire_ref_id,
	validation_id
OR DELETE ON VALIDATION_VOL_HORAIRE_REF
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
      JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id
    WHERE
      vh.id = :NEW.volume_horaire_ref_id
      OR vh.id = :OLD.volume_horaire_ref_id

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL( 'contrat', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id ) );

  END LOOP;

END;

/










CREATE OR REPLACE TRIGGER T_CRT_CONTRAT_S
AFTER INSERT OR UPDATE OR DELETE ON CONTRAT
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_CRT_ELEMENT_PEDAGOGIQUE_S
AFTER INSERT OR UPDATE OR DELETE ON ELEMENT_PEDAGOGIQUE
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_CRT_INTERVENANT_S
AFTER INSERT OR UPDATE OR DELETE ON INTERVENANT
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_CRT_SERVICE_S
AFTER INSERT OR UPDATE OR DELETE ON SERVICE
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_CRT_SERVICE_REFERENTIEL_S
AFTER INSERT OR UPDATE OR DELETE ON SERVICE_REFERENTIEL
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_CRT_STATUT_INTERVENANT_S
AFTER INSERT OR UPDATE OR DELETE ON STATUT_INTERVENANT
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_CRT_VALIDATION_S
AFTER INSERT OR UPDATE OR DELETE ON VALIDATION
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_CRT_VALIDATION_VOL_HORAIRE_S
AFTER INSERT OR UPDATE OR DELETE ON VALIDATION_VOL_HORAIRE
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_CRT_VAL_VOL_HORAIRE_REF_S
AFTER INSERT OR UPDATE OR DELETE ON VALIDATION_VOL_HORAIRE_REF
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_CRT_VOLUME_HORAIRE_S
AFTER INSERT OR UPDATE OR DELETE ON VOLUME_HORAIRE
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

CREATE OR REPLACE TRIGGER T_CRT_VOLUME_HORAIRE_REF_S
AFTER INSERT OR UPDATE OR DELETE ON VOLUME_HORAIRE_REF
BEGIN
  UNICAEN_TBL.CALCULER_DEMANDES;
END;

/

