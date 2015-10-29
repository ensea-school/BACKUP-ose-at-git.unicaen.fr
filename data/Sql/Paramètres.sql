--select * from parametre;


INSERT INTO PARAMETRE (
  NOM, 
  VALEUR, 
  DESCRIPTION,
  ID,
  HISTO_CREATEUR_ID,
  HISTO_MODIFICATEUR_ID
)VALUES(
  'discipline_codes_corresp_1_libelle',
  'Section(s) CNU Apogée',
  'Libellé de la liste 1 des correspondances de codes des disciplines',
  PARAMETRE_ID_SEQ.NEXTVAL,
  (select id from utilisateur where username = 'lecluse'),
  (select id from utilisateur where username = 'lecluse')
);

INSERT INTO PARAMETRE (
  NOM, 
  VALEUR, 
  DESCRIPTION,
  ID,
  HISTO_CREATEUR_ID,
  HISTO_MODIFICATEUR_ID
)VALUES(
  'discipline_codes_corresp_2_libelle',
  'Section(s) CNU Harpège',
  'Libellé de la liste 2 des correspondances de codes des disciplines',
  PARAMETRE_ID_SEQ.NEXTVAL,
  (select id from utilisateur where username = 'lecluse'),
  (select id from utilisateur where username = 'lecluse')
);

INSERT INTO PARAMETRE (
  NOM, 
  VALEUR, 
  DESCRIPTION,
  ID,
  HISTO_CREATEUR_ID,
  HISTO_MODIFICATEUR_ID
)VALUES(
  'discipline_codes_corresp_3_libelle',
  'Discipline Harpège',
  'Libellé de la liste 3 des correspondances de codes des disciplines',
  PARAMETRE_ID_SEQ.NEXTVAL,
  (select id from utilisateur where username = 'lecluse'),
  (select id from utilisateur where username = 'lecluse')
);

INSERT INTO PARAMETRE (
  NOM, 
  VALEUR, 
  DESCRIPTION,
  ID,
  HISTO_CREATEUR_ID,
  HISTO_MODIFICATEUR_ID
)VALUES(
  'discipline_codes_corresp_4_libelle',
  '',
  'Libellé de la liste 4 des correspondances de codes des disciplines',
  PARAMETRE_ID_SEQ.NEXTVAL,
  (select id from utilisateur where username = 'lecluse'),
  (select id from utilisateur where username = 'lecluse')
);