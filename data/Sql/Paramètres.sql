select * from parametre;


INSERT INTO PARAMETRE (
  NOM, 
  VALEUR, 
  DESCRIPTION,
  ID,
  HISTO_CREATEUR_ID,
  HISTO_MODIFICATEUR_ID
)VALUES(
  'annee_import',
  '2014',
  'Année courante pour l''import',
  PARAMETRE_ID_SEQ.NEXTVAL,
  (select id from utilisateur where username = 'lecluse'),
  (select id from utilisateur where username = 'lecluse')
);

UPDATE "OSE"."PARAMETRE" SET DESCRIPTION = 'Date de fin de saisie pour les intervenants permanents (format jj/mm/aaaa)' WHERE nom = 'date_fin_saisie_permanents';
UPDATE "OSE"."PARAMETRE" SET VALEUR = '20/10/2014' WHERE nom = 'date_fin_saisie_permanents';


select id from utilisateur where username = 'lecluse'