select * from parametre;


INSERT INTO PARAMETRE (
  NOM, 
  VALEUR, 
  DESCRIPTION,
  ID, HISTO_CREATEUR_ID, HISTO_MODIFICATEUR_ID
)VALUES(
  'date_fin_saisie_permanents',
  '20/10',
  'Date de fin de saisie pour les intervenants permanents (format jj/mm)',
  PARAMETRE_ID_SEQ.NEXTVAL, 1,1
);

UPDATE "OSE"."PARAMETRE" SET DESCRIPTION = 'Date de fin de saisie pour les intervenants permanents (format jj/mm/aaaa)' WHERE nom = 'date_fin_saisie_permanents';
UPDATE "OSE"."PARAMETRE" SET VALEUR = '20/10/2014' WHERE nom = 'date_fin_saisie_permanents';