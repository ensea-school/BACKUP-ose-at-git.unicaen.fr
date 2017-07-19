--select * from parametre;


INSERT INTO PARAMETRE (
  NOM, 
  VALEUR, 
  DESCRIPTION,
  ID,
  HISTO_CREATEUR_ID,
  HISTO_MODIFICATEUR_ID
)VALUES(
  'contrat_etablissement',
  'L''université de Caen',
  'Contrat : établissement',
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
  'contrat_etablissement_represente',
  'représentée par son Président, Pierre DENISE',
  'Contrat : représentant',
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
  'contrat_civilite_president',
  'le Président',
  'Contrat : civilité du président (avec article)',
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
  'contrat_lieu_signature',
  'Caen',
  'LContrat : lieu de signature',
  PARAMETRE_ID_SEQ.NEXTVAL,
  (select id from utilisateur where username = 'lecluse'),
  (select id from utilisateur where username = 'lecluse')
);