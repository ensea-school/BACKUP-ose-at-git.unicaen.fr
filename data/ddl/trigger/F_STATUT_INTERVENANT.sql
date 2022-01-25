CREATE OR REPLACE TRIGGER "F_STATUT_INTERVENANT"
AFTER UPDATE OF
  service_statutaire,
  depassement,
  type_intervenant_id,
  non_autorise
ON "STATUT"
FOR EACH ROW
BEGIN return; /* Désactivation du trigger... */

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      fr.intervenant_id
    FROM
      intervenant i
      JOIN formule_resultat fr ON fr.intervenant_id = i.id
    WHERE
      (i.statut_id = :NEW.id OR i.statut_id = :OLD.id)
      AND i.histo_destruction IS NULL

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL('formule', 'INTERVENANT_ID', p.intervenant_id);

  END LOOP;
END;