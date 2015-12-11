  
  
INSERT INTO CATEGORIE_PRIVILEGE (
  ID,
  CODE,
  LIBELLE
) VALUES (
  CATEGORIE_PRIVILEGE_ID_SEQ.nextval,
  'contrat',
  'Contrats de travail/Avenants'
);

INSERT INTO PRIVILEGE (
  ID,
  CATEGORIE_ID,
  CODE,
  LIBELLE,
  ORDRE
)
SELECT 
  privilege_id_seq.nextval id,
  (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c ) CATEGORIE_ID,
  t1.p CODE,
  t1.l LIBELLE,
  (SELECT count(*) FROM PRIVILEGE WHERE categorie_id = (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c )) + rownum ORDRE
FROM (

      SELECT 'contrat' c, 'association' p, 'Association' l FROM dual
UNION SELECT 'contrat' c, 'visualisation' p, 'Visualisation' l FROM dual
UNION SELECT 'contrat' c, 'creation' p, 'Création d''un projet' l FROM dual
UNION SELECT 'contrat' c, 'suppression' p, 'Suppression d''un projet' l FROM dual
UNION SELECT 'contrat' c, 'validation' p, 'Validation' l FROM dual
UNION SELECT 'contrat' c, 'devalidation' p, 'Dévalidation' l FROM dual
UNION SELECT 'contrat' c, 'depot-retour-signe' p, 'Dépôt de contrat signé' l FROM dual
UNION SELECT 'contrat' c, 'saisie-date-retour-signe' p, 'Saisie de date retour' l FROM dual
UNION SELECT 'enseignement' c, 'cloture' p, 'Clôture' l FROM dual

) t1;

select
  
  p.id, cp.code categorie, p.code privilege, p.ordre ordre
from
  privilege p
  join categorie_privilege cp on cp.id = p.categorie_id
order by
  cp.code, p.ordre;
  
select * from categorie_privilege order by ordre;

update categorie_privilege set ordre = 10 WHERE code = 'odf';
update categorie_privilege set ordre = 20 WHERE code = 'discipline';

update categorie_privilege set ordre = 30 WHERE code = 'intervenant';
update categorie_privilege set ordre = 40 WHERE code = 'modif-service-du';
update categorie_privilege set ordre = 50 WHERE code = 'dossier';
update categorie_privilege set ordre = 60 WHERE code = 'piece-justificative';
update categorie_privilege set ordre = 70 WHERE code = 'enseignement';
update categorie_privilege set ordre = 80 WHERE code = 'motif-non-paiement';
update categorie_privilege set ordre = 90 WHERE code = 'referentiel';
update categorie_privilege set ordre = 100 WHERE code = 'agrement';
update categorie_privilege set ordre = 110 WHERE code = 'contrat';
update categorie_privilege set ordre = 120 WHERE code = 'mise-en-paiement';

update categorie_privilege set ordre = 130 WHERE code = 'indicateur';
update categorie_privilege set ordre = 140 WHERE code = 'droit';
update categorie_privilege set ordre = 150 WHERE code = 'import';




  





--UPDATE CATEGORIE_PRIVILEGE SET CODE = 'droit' WHERE CODE = 'privilege';
--update privilege set code = 'role-visualisation' where code = 'visualisation' AND categorie_id = (SELECT id FROM categorie_privilege WHERE code='droit');
--update privilege set code = 'role-edition' where code = 'edition' AND categorie_id = (SELECT id FROM categorie_privilege WHERE code='droit');
