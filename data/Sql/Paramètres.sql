select * from parametre;


INSERT INTO PARAMETRE (
  NOM, 
  VALEUR, 
  DESCRIPTION,
  ID,
  HISTO_CREATEUR_ID,
  HISTO_MODIFICATEUR_ID
)VALUES(
  'domaine_fonctionnel_ens_ext',
  (select id from domaine_fonctionnel where source_code = '102'),
  'ID du domaine fonctionnel à privilégier pour les enseignements pris à l''extérieur',
  PARAMETRE_ID_SEQ.NEXTVAL,
  (select id from utilisateur where username = 'lecluse'),
  (select id from utilisateur where username = 'lecluse')
);
