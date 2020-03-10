CREATE OR REPLACE TRIGGER "F_MODULATEUR"
AFTER UPDATE OR DELETE ON modulateur
FOR EACH ROW
BEGIN

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
      JOIN element_modulateur em ON
        em.element_id   = s.element_pedagogique_id
        AND em.histo_destruction IS NULL
    WHERE
      s.histo_destruction IS NULL
      AND (em.modulateur_id = :OLD.id OR em.modulateur_id = :NEW.id)

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );

  END LOOP;
END;