-- Script de migration de la version 7.x à la 8.0

INSERT INTO CATEGORIE_PRIVILEGE (ID, CODE, LIBELLE)
VALUES (CATEGORIE_PRIVILEGE_ID_SEQ.nextval, 'centres-couts', 'Paramétrage des centres de coûts');

INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE)
SELECT privilege_id_seq.nextval                               id,
       (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c) CATEGORIE_ID,
       t1.p                                                   CODE,
       t1.l                                                   LIBELLE,
       (SELECT count(*) FROM PRIVILEGE WHERE categorie_id = (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c)) +
       rownum                                                 ORDRE
FROM (SELECT 'centres-couts' c, 'administration-visualisation' p, 'Administration (visualisation)' l FROM dual
      UNION ALL SELECT 'centres-couts' c, 'administration-edition' p, 'Administration (édition)' l FROM dual) t1;


INSERT INTO CATEGORIE_PRIVILEGE (ID, CODE, LIBELLE)
VALUES (CATEGORIE_PRIVILEGE_ID_SEQ.nextval, 'etat-sortie', 'États de sortie');

INSERT INTO PRIVILEGE (ID, CATEGORIE_ID, CODE, LIBELLE, ORDRE)
SELECT privilege_id_seq.nextval                               id,
       (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c) CATEGORIE_ID,
       t1.p                                                   CODE,
       t1.l                                                   LIBELLE,
       (SELECT count(*) FROM PRIVILEGE WHERE categorie_id = (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c)) +
       rownum                                                 ORDRE
FROM (SELECT 'etat-sortie' c, 'administration-visualisation' p, 'Administration (visualisation)' l FROM dual
      UNION ALL SELECT 'etat-sortie' c, 'administration-edition' p, 'Administration (édition)' l FROM dual) t1;





-- =!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=
-- =  bien ajouter les requêtes de modif de DDL générées ! ! ! ! =
-- =!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=