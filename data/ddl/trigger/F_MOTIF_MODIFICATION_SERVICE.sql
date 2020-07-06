CREATE OR REPLACE TRIGGER "F_MOTIF_MODIFICATION_SERVICE"
AFTER UPDATE OR DELETE ON MOTIF_MODIFICATION_SERVICE
FOR EACH ROW
BEGIN

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      intervenant_id
    FROM
      modification_service_du msd
    WHERE
      msd.histo_destruction IS NULL
      AND (msd.motif_id = :NEW.id OR msd.motif_id = :OLD.id)

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL('formule', 'INTERVENANT_ID', p.intervenant_id );

  END LOOP;

END;