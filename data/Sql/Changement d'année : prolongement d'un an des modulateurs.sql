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