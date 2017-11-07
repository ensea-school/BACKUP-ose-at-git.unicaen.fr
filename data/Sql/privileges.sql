INSERT INTO CATEGORIE_PRIVILEGE (
  ID,
  CODE,
  LIBELLE
) VALUES (
  CATEGORIE_PRIVILEGE_ID_SEQ.nextval,
  'plafonds',
  'Plafonds'
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

            
            SELECT 'plafonds' c, 'gestion-visualisation' p, 'Gestion (visualisation)' l FROM dual
            union SELECT 'plafonds' c, 'gestion-edition' p, 'Gestion (édition)' l FROM dual
      --UNION SELECT 'modulateur' c, 'edition' p, 'Édition' l FROM dual
      --UNION SELECT 'chargens' c, 'formation-choix-edition' p, 'Édition des formations (choix liens)' l FROM dual
/*      UNION SELECT 'chargens' c, 'seuil-etablissement-edition' p, 'Édition des seuil (établissement)' l FROM dual

      UNION SELECT 'chargens' c, 'seuil-composante-visualisation' p, 'Visualisation des seuils (composantes)' l FROM dual
      UNION SELECT 'chargens' c, 'seuil-composante-edition' p, 'Édition des seuil (composantes)' l FROM dual

      UNION SELECT 'chargens' c, 'scenario-visualisation' p, 'Visualisation des scénarios' l FROM dual
      UNION SELECT 'chargens' c, 'scenario-duplication' p, 'Duplication de scénario' l FROM dual
      UNION SELECT 'chargens' c, 'scenario-composante-edition' p, 'Édition des scénarios (composantes)' l FROM dual

      UNION SELECT 'chargens' c, 'formation-visualisation' p, 'Visualisation des formations' l FROM dual
      UNION SELECT 'chargens' c, 'formation-effectifs-edition' p, 'Édition des formations (effectifs)' l FROM dual
      UNION SELECT 'chargens' c, 'formation-assiduite-edition' p, 'Édition des formations (assiduité)' l FROM dual
      UNION SELECT 'chargens' c, 'formation-seuils-edition' p, 'Édition des formations (seuils)' l FROM dual*/
) t1;

delete from privilege where id = 123;

/* Liste... */
select
  cp.code, p.code, p.libelle, cp.libelle
from
  privilege p
  join categorie_privilege cp on cp.id = p.categorie_id
order by
  cp.code, p.ordre;

select * from categorie_privilege order by ordre;


delete from privilege where code = 'visualisation' AND CATEGORIE_ID = (select id from CATEGORIE_PRIVILEGE where code ='chargens');


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


delete from privilege where code='association' 
AND categorie_id = (select cp.id from categorie_privilege cp where cp.code='referentiel');
delete from privilege where code='association' 
AND categorie_id = (select cp.id from categorie_privilege cp where cp.code='dossier');


--UPDATE CATEGORIE_PRIVILEGE SET CODE = 'droit' WHERE CODE = 'privilege';
--update privilege set code = 'role-visualisation' where code = 'visualisation' AND categorie_id = (SELECT id FROM categorie_privilege WHERE code='droit');
--update privilege set code = 'role-edition' where code = 'edition' AND categorie_id = (SELECT id FROM categorie_privilege WHERE code='droit');


select * from categorie_privilege where code = 'privilege';

delete from categorie_privilege where id = 5;