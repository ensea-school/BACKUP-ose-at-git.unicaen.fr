CREATE OR REPLACE TRIGGER "F_ELEMENT_MODULATEUR"
AFTER INSERT OR UPDATE OR DELETE ON element_modulateur
FOR EACH ROW
BEGIN

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
    WHERE
      s.histo_destruction IS NULL
      AND (s.element_pedagogique_id = :OLD.element_id OR s.element_pedagogique_id = :NEW.element_id)

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL('formule', 'INTERVENANT_ID', p.intervenant_id );

  END LOOP;

END;