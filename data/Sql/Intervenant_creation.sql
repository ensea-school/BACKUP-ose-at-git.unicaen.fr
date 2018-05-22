select * from intervenant WHERE source_id =2;

INSERT INTO INTERVENANT (
  ID, civilite_id, code, nom_usuel, prenom, nom_patronymique, date_naissance, 
  statut_id, structure_id, annee_id, pays_naissance_id, source_id, source_code,
  histo_creation, histo_createur_id, histo_modification, histo_modificateur_id
) SELECT
  1,--INTERVENANT_ID_SEQ.NEXTVAL id,
  c.id, i.code, i.nom_usuel, i.prenom, i.nom_patronymique, TO_DATE(i.date_naissance, 'DD/MM/YYYY'),
  i.statut_id, i.structure_id, i.annee_id, pn.id, s.id, i.code, sysdate, u.id, sysdate, u.id
FROM
  (

SELECT
  '99999948'    code,
  'Madame'      civilite, -- Monsieur ou Madame
  'Boukcker'    nom_usuel,
  'Anne'        prenom,
  'Boukcker'    nom_patronymique,
  '22/11/1967'  date_naissance, -- varchar format 'DD/MM/YYYY'
  31            statut_id,
  102           structure_id,
  2017          annee_id,
  'FRANCE'      pays_naissance
FROM dual
UNION SELECT
  '99999948'    code,
  'Madame'      civilite, -- Monsieur ou Madame
  'Boukcker'    nom_usuel,
  'Anne'        prenom,
  'Boukcker'    nom_patronymique,
  '22/11/1967'  date_naissance, -- varchar format 'DD/MM/YYYY'
  31            statut_id,
  102           structure_id,
  2017          annee_id,
  'FRANCE'      pays_naissance
FROM dual


  ) i
  JOIN civilite    c ON c.libelle_long = i.civilite
  JOIN pays       pn ON pn.libelle_court = i.pays_naissance
  JOIN source      s ON s.code = 'OSE'
  JOIN utilisateur u ON u.username = 'lecluse'
;



select * from pays where libelle_long like 'FR%'
;
select * from utilisateur