/**
 * Création d'un nouvel indicateur.
 */

Insert into INDICATEUR (ID,CODE,TYPE,ORDRE,ENABLED) values (
    indicateur_id_seq.nextval,
    'AttenteValidationEnsPrevuPerm',  
    'Enseignements et référentiel',
    '325',
    '1'
);