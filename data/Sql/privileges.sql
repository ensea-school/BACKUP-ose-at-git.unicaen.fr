  
  
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

      SELECT 'budget' c, 'edition-engagement-etablissement' p, 'Dotation paye état' l FROM dual
UNION SELECT 'budget' c, 'edition-engagement-composante' p, 'Dotation ressources propres' l FROM dual

) t1;


/* Liste... */
select
  rpad( '/* ' || cp.code || ' */ ', 30 ) || 'update privilege set ordre =  WHERE code = ''' || p.code || ''' AND categorie_id = (SELECT cp.id FROM categorie_privilege cp WHERE cp.code= ''' || cp.code || ''');'
  
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


--delete from privilege where code='edition' AND categorie_id = (select cp.id from categorie_privilege cp where cp.code='budget');


--UPDATE CATEGORIE_PRIVILEGE SET CODE = 'droit' WHERE CODE = 'privilege';
--update privilege set code = 'role-visualisation' where code = 'visualisation' AND categorie_id = (SELECT id FROM categorie_privilege WHERE code='droit');
--update privilege set code = 'role-edition' where code = 'edition' AND categorie_id = (SELECT id FROM categorie_privilege WHERE code='droit');
