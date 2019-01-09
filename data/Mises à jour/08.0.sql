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


INSERT INTO parametre (
    id,
    nom,
    valeur,
    description,
    histo_creation,
    histo_createur_id,
    histo_modification,
    histo_modificateur_id
    ) VALUES (
                 parametre_id_seq.nextval,
                 'structure_univ',
                 null,
                 'Composante représentant l''université (utile éventuellement pour la forpule de calcul)',
                 sysdate,
                 (select id from utilisateur where username='oseappli'),
                 sysdate,
                 (select id from utilisateur where username='oseappli')
                 );


UPDATE tbl SET feuille_de_route = 1 WHERE tbl_name IN (
    'agrement',
    'cloture_realise',
    'contrat',
    'dossier',
    'paiement',
    'piece_jointe',
    'piece_jointe_demande',
    'piece_jointe_fournie',
    'service',
    'service_referentiel',
    'service_saisie',
    'validation_referentiel',
    'workflow',
    'validation_enseignement',
    'formule'
    );


-- =!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=
-- =  bien ajouter les requêtes de modif de DDL générées ! ! ! ! =
-- =!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=!=