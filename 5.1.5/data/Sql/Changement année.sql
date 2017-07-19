-- activer l'année :
UPDATE ANNEE SET ACTIVE = '1' WHERE ID = 2016;


-- mettre à jour les dates de campagne de saisie

-- maj des paramètres



SELECT
  '
  INSERT INTO element_pedagogique (
    ID,
    LIBELLE,
    ETAPE_ID,
    STRUCTURE_ID,
    PERIODE_ID,
    TAUX_FI, TAUX_FC, TAUX_FA,
    TAUX_FOAD,
    FI, FC, FA,
    SOURCE_ID, SOURCE_CODE,
    HISTO_CREATION, HISTO_CREATEUR_ID,
    HISTO_MODIFICATION, HISTO_MODIFICATEUR_ID,
    ANNEE_ID,
    DISCIPLINE_ID
  ) VALUES (
    element_pedagogique_id_seq.nextval,
    q''[' || ep.libelle || ']'',
    ' || ep.etape_id || ',
    ' || ep.structure_id || ',
    ' || NVL(to_char(ep.periode_id), 'NULL') || ',
    ' || TRIM(TO_CHAR(ep.taux_fi, '999.99')) || ', ' || TRIM(TO_CHAR(ep.taux_fc, '999.99')) || ', ' || TRIM(TO_CHAR(ep.taux_fa, '999.99')) || ',
    ' || TRIM(TO_CHAR(ep.taux_foad, '999.99')) || ',
    ' || TRIM(TO_CHAR(ep.fi, '999.99')) || ', ' || TRIM(TO_CHAR(ep.fc, '999.99')) || ', ' || TRIM(TO_CHAR(ep.fa, '999.99')) || ',
    ' || ep.source_id || ', q''[' || ep.source_code || ']'',
    SYSDATE, ' || u.id || ',
    SYSDATE, ' || u.id || ',
    ' || (ep.annee_id + 1) || ',
    ' || NVL(to_char(ep.discipline_id), 'NULL') || '
  );

  INSERT INTO chemin_pedagogique(
    ID,
    ELEMENT_PEDAGOGIQUE_ID,
    ETAPE_ID,
    ORDRE,
    SOURCE_ID, SOURCE_CODE,
    HISTO_CREATION, HISTO_CREATEUR_ID,
    HISTO_MODIFICATION, HISTO_MODIFICATEUR_ID
  ) VALUES (
    chemin_pedagogique_id_seq.nextval,
    element_pedagogique_id_seq.currval,
    ' || ep.etape_id || ',
    ' || rownum || ',
    ' || ep.source_id || ', q''[' || e.source_code || '_' || ep.source_code || '_' || (ep.annee_id + 1) || ']'',
    SYSDATE, ' || u.id || ',
    SYSDATE, ' || u.id || '
  );

  ' isql
FROM
  element_pedagogique ep
  JOIN etape e ON e.id = ep.etape_id
  JOIN source s ON ep.source_id = s.id AND s.code = 'OSE'
  JOIN utilisateur u ON u.username = 'lecluse'
WHERE
  ep.annee_id = 2015
  AND 1 = ose_divers.comprise_entre( ep.histo_creation, ep.histo_destruction )
;


-- réaliser l'import


-- modulateurs
SELECT
   'INSERT INTO ELEMENT_MODULATEUR(
    ID,
    ELEMENT_ID,
    MODULATEUR_ID,
    HISTO_CREATION, HISTO_CREATEUR_ID,
    HISTO_MODIFICATION, HISTO_MODIFICATEUR_ID
)VALUES(
    ELEMENT_MODULATEUR_ID_SEQ.NEXTVAL,
    ' || ep2.id || ',
    ' || m.id || ',
    SYSDATE, (SELECT id FROM utilisateur WHERE username=''lecluse''),
    SYSDATE, (SELECT id FROM utilisateur WHERE username=''lecluse'')
);' isql
FROM
  element_modulateur         em
  JOIN element_pedagogique   ep  ON ep.id = em.element_id
  JOIN element_pedagogique  ep2  ON ep2.source_code = ep.source_code
                                AND ep2.annee_id = ep.annee_id + 1
  JOIN modulateur             m  ON m.id = em.modulateur_id
                                AND 1 = ose_divers.comprise_entre( m.histo_creation, m.histo_destruction )
  JOIN type_modulateur       tm  ON tm.id = m.type_modulateur_id
                                AND 1 = ose_divers.comprise_entre( tm.histo_creation, tm.histo_destruction )
  JOIN type_modulateur_ep  tmep  ON tmep.type_modulateur_id = m.type_modulateur_id
                                AND tmep.element_pedagogique_id = ep2.id
                                AND 1 = ose_divers.comprise_entre( tmep.histo_creation, tmep.histo_destruction )
WHERE
  1 = ose_divers.comprise_entre( em.histo_creation, em.histo_destruction )
  AND ep.annee_id = 2015;
  
  
  
-- centres de coûts
SELECT
'INSERT INTO CENTRE_COUT_EP(
    ID,
    CENTRE_COUT_ID,
    ELEMENT_PEDAGOGIQUE_ID,
    TYPE_HEURES_ID,
    SOURCE_ID,
    SOURCE_CODE,
    HISTO_CREATION, HISTO_CREATEUR_ID,
    HISTO_MODIFICATION, HISTO_MODIFICATEUR_ID
)VALUES(
    CENTRE_COUT_EP_ID_SEQ.NEXTVAL,
    ' || CCE.CENTRE_COUT_ID || ',
    ' || ep2.id || ',
    ' || CCE.type_heures_id || ',
    ' || CCE.source_id || ',
    ''N-1toN_'' || CENTRE_COUT_EP_ID_SEQ.CURRVAL,
    SYSDATE, (SELECT id FROM utilisateur WHERE username=''lecluse''),
    SYSDATE, (SELECT id FROM utilisateur WHERE username=''lecluse'')
);' isql
FROM
  centre_cout_ep         cce
  JOIN element_pedagogique   ep  ON ep.id = CCE.ELEMENT_PEDAGOGIQUE_ID
  JOIN element_pedagogique  ep2  ON ep2.source_code = ep.source_code
                                AND ep2.annee_id = ep.annee_id + 1
WHERE
  1 = ose_divers.comprise_entre( cce.histo_creation, cce.histo_destruction )
  AND ep.annee_id = 2015;
  
