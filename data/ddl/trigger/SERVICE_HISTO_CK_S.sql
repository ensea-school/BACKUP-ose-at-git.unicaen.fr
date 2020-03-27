CREATE OR REPLACE TRIGGER "SERVICE_HISTO_CK_S"
AFTER UPDATE ON service
BEGIN
 -- En cas de restauration d'un service, on ne restaure pas les historiques de volumes horaires pour ne pas récussiter d'éventuels volume horaires indésirables car préalablement supprimés
 FOR s IN (

    SELECT *
    FROM
      service s
    WHERE
      s.histo_destruction IS NOT NULL AND s.histo_destruction > SYSDATE - 1

  ) LOOP

    UPDATE VOLUME_HORAIRE SET histo_destruction = s.histo_destruction, histo_destructeur_id = s.histo_destructeur_id WHERE service_id = s.id AND VOLUME_HORAIRE.histo_destruction IS NULL;

  END LOOP;

END;