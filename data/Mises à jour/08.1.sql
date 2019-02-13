-- OSE 8.1
-- Mise à jour depuis les versions 8.0 à 8.0.x vers la version 8.1


-- ATTENTION :
/*
Ne pas oublier de générer le DDL différentiel

Ne pas oublier non plus d'insérer les requêtes des versions 8.0.1 et 8.0.3!!!!!!!
 */

insert into formule(id, libelle, package_name, procedure_name)
values (formule_id_seq.nextval, 'Université de Caen', 'FORMULE_UNICAEN', 'CALCUL_RESULTAT_V3');

insert into formule(id, libelle, package_name, procedure_name)
values (formule_id_seq.nextval, 'Université de Montpellier', 'FORMULE_MONTPELLIER', 'CALCUL_RESULTAT');


insert into formule_test_structure (id, libelle, universite) values (ftest_structure_id_seq.nextval, 'Droit', 0);
insert into formule_test_structure (id, libelle, universite) values (ftest_structure_id_seq.nextval, 'Histoire', 0);
insert into formule_test_structure (id, libelle, universite) values (ftest_structure_id_seq.nextval, 'IAE', 0);
insert into formule_test_structure (id, libelle, universite) values (ftest_structure_id_seq.nextval, 'IUT', 0);
insert into formule_test_structure (id, libelle, universite) values (ftest_structure_id_seq.nextval, 'Lettres', 0);
insert into formule_test_structure (id, libelle, universite) values (ftest_structure_id_seq.nextval, 'Santé', 0);
insert into formule_test_structure (id, libelle, universite) values (ftest_structure_id_seq.nextval, 'Sciences', 0);
insert into formule_test_structure (id, libelle, universite) values (ftest_structure_id_seq.nextval, 'SUAPS', 0);
insert into formule_test_structure (id, libelle, universite) values (ftest_structure_id_seq.nextval, 'Université', 1);

INSERT INTO parametre (
  id, nom,
  valeur, description,
  histo_creation, histo_createur_id,
  histo_modification, histo_modificateur_id
) VALUES (
  parametre_id_seq.nextval, 'formule',
  '1', 'Formule de calcul',
  sysdate, (select id from utilisateur where username='oseappli'),
  sysdate, (select id from utilisateur where username='oseappli')
);
DELETE FROM parametre WHERE nom IN ('formule_package_name', 'formule_function_name');




INSERT INTO CATEGORIE_PRIVILEGE (ID,CODE,LIBELLE) VALUES (
  CATEGORIE_PRIVILEGE_ID_SEQ.nextval,
  'domaines-fonctionnels',
  'Domaines fonctionnels'
);
INSERT INTO CATEGORIE_PRIVILEGE (ID,CODE,LIBELLE) VALUES (
  CATEGORIE_PRIVILEGE_ID_SEQ.nextval,
  'motifs-modification-service-du',
  'Motifs de modification de service dû'
);
INSERT INTO CATEGORIE_PRIVILEGE (ID,CODE,LIBELLE) VALUES (
  CATEGORIE_PRIVILEGE_ID_SEQ.nextval,
  'structures',
  'Structures'
);

INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE)
SELECT
       privilege_id_seq.nextval id,
       (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c ) CATEGORIE_ID,
       t1.p CODE,
       t1.l LIBELLE,
       (SELECT count(*) FROM PRIVILEGE WHERE categorie_id = (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c )) + rownum ORDRE
FROM (

     SELECT 'odf' c, 'grands-types-diplome-visualisation' p, 'Grands types de diplômes (visualisation)' l FROM dual
     UNION ALL SELECT 'odf' c, 'grands-types-diplome-edition' p, 'Grands types de diplômes (édition)' l FROM dual

     UNION ALL SELECT 'odf' c, 'types-diplome-visualisation' p, 'Types de diplômes (visualisation)' l FROM dual
     UNION ALL SELECT 'odf' c, 'types-diplome-edition' p, 'Types de diplômes (édition)' l FROM dual

     UNION ALL SELECT 'motifs-modification-service-du' c, 'visualisation' p, 'Administration (visualisation)' l FROM dual
     UNION ALL SELECT 'motifs-modification-service-du' c, 'edition' p, 'Administration (édition)' l FROM dual

     UNION ALL SELECT 'structures' c, 'administration-visualisation' p, 'Administration (visualisation)' l FROM dual
     UNION ALL SELECT 'structures' c, 'administration-edition' p, 'Administration (édition)' l FROM dual

     UNION ALL SELECT 'budget' c, 'types-ressources-visualisation' p, 'Types de ressources - Visualisation' l FROM dual
     UNION ALL SELECT 'budget' c, 'types-ressources-edition' p, 'Types de ressources - Édition' l FROM dual

     ) t1;