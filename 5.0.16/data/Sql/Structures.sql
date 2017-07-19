SELECT * FROM

(select id, source_code, rpad(' ', (level-1)*4) || libelle_court libelle, niveau
from structure
start with parente_id is null
connect by parente_id = prior id) tmp
WHERE
  niveau < 3
  
ORDER BY
  libelle

;



INSERT
INTO STRUCTURE
  (
    ID,
    LIBELLE_LONG,
    LIBELLE_COURT,
    PARENTE_ID,
    STRUCTURE_NIV2_ID,
    TYPE_ID,
    ETABLISSEMENT_ID,
    NIVEAU,
    SOURCE_ID,
    SOURCE_CODE,
    VALIDITE_DEBUT,
    HISTO_CREATION,
    HISTO_CREATEUR_ID,
    HISTO_MODIFICATION,
    HISTO_MODIFICATEUR_ID
  )
  VALUES
  (
    structure_id_seq.nextval,
    'Université de Caen',
    'Univ-Caen',
    (SELECT id FROM structure WHERE niveau = 1),
    structure_id_seq.currval,
    2,
    (SELECT etablissement_id FROM structure WHERE niveau = 1),
    2,
    (select id from source where code = 'OSE'),
    'UNICAEN',
    sysdate,
    sysdate, 4,
    sysdate, 4
  );