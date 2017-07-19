SELECT
  *
FROM
  element_pedagogique
WHERE
  source_code = 'LENOR1';
SELECT
  *
FROM
  chemin_pedagogique
WHERE
  element_pedagogique_id IN (32561,32777);



/
-- Duplication d'un EP à l'année suivante !!
DECLARE
  ep element_pedagogique%rowtype;
  cp chemin_pedagogique%rowtype;
  annee   NUMERIC DEFAULT 2014;
  ep_code VARCHAR(255) DEFAULT 'LENOR1';
  nep_id  NUMERIC;
BEGIN
  SELECT
    *
  INTO
    ep
  FROM
    element_pedagogique
  WHERE
    source_code = ep_code
  AND annee_id  = annee;
  SELECT
    *
  INTO
    cp
  FROM
    chemin_pedagogique
  WHERE
    element_pedagogique_id = ep.id;
  nep_id := ELEMENT_PEDAGOGIQUE_ID_SEQ.NEXTVAL;
  INSERT
  INTO
    ELEMENT_PEDAGOGIQUE
    (
      ID,
      LIBELLE,
      ETAPE_ID,
      STRUCTURE_ID,
      PERIODE_ID,
      TAUX_FOAD,
      FI,
      FC,
      FA,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATION,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATION,
      HISTO_MODIFICATEUR_ID,
      TAUX_FA,
      TAUX_FC,
      TAUX_FI,
      ANNEE_ID
    )
    VALUES
    (
      nep_id,
      ep.LIBELLE,
      ep.ETAPE_ID,
      ep.STRUCTURE_ID,
      ep.PERIODE_ID,
      ep.TAUX_FOAD,
      ep.FI,
      ep.FC,
      ep.FA,
      ep.SOURCE_ID,
      ep.SOURCE_CODE,
      sysdate,
      (
        SELECT
          id
        FROM
          utilisateur
        WHERE
          username='lecluse'
      )
      ,
      sysdate,
      (
        SELECT
          id
        FROM
          utilisateur
        WHERE
          username='lecluse'
      )
      ,
      ep.TAUX_FA,
      ep.TAUX_FC,
      ep.TAUX_FI,
      annee + 1
    );
  INSERT
  INTO
    CHEMIN_PEDAGOGIQUE
    (
      ID,
      ELEMENT_PEDAGOGIQUE_ID,
      ETAPE_ID,
      ORDRE,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATION,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATION,
      HISTO_MODIFICATEUR_ID
    )
    VALUES
    (
      CHEMIN_PEDAGOGIQUE_ID_SEQ.NEXTVAL,
      nep_id,
      cp.ETAPE_ID,
      cp.ORDRE,
      cp.SOURCE_ID,
      NULL,
      sysdate,
      (
        SELECT
          id
        FROM
          utilisateur
        WHERE
          username='lecluse'
      )
      ,
      sysdate,
      (
        SELECT
          id
        FROM
          utilisateur
        WHERE
          username='lecluse'
      )
    );
END;
