-- ********************************************************************* --
-- *          à faire AVANT avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"', force => TRUE); END; 
/





-- ********************************************************************* --
-- *          à faire APRÈS avoir mis à jour le code source            * --
-- ********************************************************************* --

drop view "OSE"."V_FORMULE_REFERENTIEL";

INSERT INTO VOLUME_HORAIRE_REF (
    ID,
    TYPE_VOLUME_HORAIRE_ID,
    SERVICE_REFERENTIEL_ID,
    HEURES,
    HISTO_CREATION, HISTO_CREATEUR_ID,
    HISTO_MODIFICATION, HISTO_MODIFICATEUR_ID,
    HISTO_DESTRUCTION, HISTO_DESTRUCTEUR_ID
)
SELECT volume_horaire_ref_id_seq.nextval, 1, id, heures, sysdate, 1, sysdate, 1, histo_destruction, histo_destructeur_id FROM service_referentiel;



INSERT INTO CC_ACTIVITE (
  ID,
  CODE,
  LIBELLE,
  HISTO_CREATION, HISTO_CREATEUR_ID,
  HISTO_MODIFICATION, HISTO_MODIFICATEUR_ID
)VALUES(
  CC_ACTIVITE_id_seq.nextval,
  'pilotage',
  'Pilotage',
  sysdate, ose_parametre.get_ose_user,
  sysdate, ose_parametre.get_ose_user
);

INSERT INTO CC_ACTIVITE (
  ID,
  CODE,
  LIBELLE,
  HISTO_CREATION, HISTO_CREATEUR_ID,
  HISTO_MODIFICATION, HISTO_MODIFICATEUR_ID
)VALUES(
  CC_ACTIVITE_id_seq.nextval,
  'enseignement',
  'Enseignement',
  sysdate, ose_parametre.get_ose_user,
  sysdate, ose_parametre.get_ose_user
);

INSERT INTO TYPE_RESSOURCE (
  ID,
  CODE,
  LIBELLE,
  HISTO_CREATION, HISTO_CREATEUR_ID,
  HISTO_MODIFICATION, HISTO_MODIFICATEUR_ID
)VALUES(
  TYPE_RESSOURCE_id_seq.nextval,
  'paye-etat',
  'Paye état',
  sysdate, ose_parametre.get_ose_user,
  sysdate, ose_parametre.get_ose_user
);

INSERT INTO TYPE_RESSOURCE (
  ID,
  CODE,
  LIBELLE,
  HISTO_CREATION, HISTO_CREATEUR_ID,
  HISTO_MODIFICATION, HISTO_MODIFICATEUR_ID
)VALUES(
  TYPE_RESSOURCE_id_seq.nextval,
  'ressources-propres',
  'Ressources propres',
  sysdate, ose_parametre.get_ose_user,
  sysdate, ose_parametre.get_ose_user
);

INSERT
INTO TYPE_DOTATION
  (
    ID,
    LIBELLE,
    SOURCE_CODE,
    SOURCE_ID,
    TYPE_RESSOURCE_ID,
    HISTO_CREATION,
    HISTO_CREATEUR_ID,
    HISTO_MODIFICATION,
    HISTO_MODIFICATEUR_ID
  )
  VALUES
  (
    type_dotation_id_seq.nextval,
    'Dotation initiale',
    'dotation-initiale',
    ose_import.get_source_id('OSE'),
    (select id from type_ressource where code = 'paye-etat'),
    sysdate,ose_parametre.get_ose_user,
    sysdate,ose_parametre.get_ose_user
  );

INSERT
INTO TYPE_DOTATION
  (
    ID,
    LIBELLE,
    SOURCE_CODE,
    SOURCE_ID,
    TYPE_RESSOURCE_ID,
    HISTO_CREATION,
    HISTO_CREATEUR_ID,
    HISTO_MODIFICATION,
    HISTO_MODIFICATEUR_ID
  )
  VALUES
  (
    type_dotation_id_seq.nextval,
    'Dotation complémentaire',
    'dotation-complementaire',
    ose_import.get_source_id('OSE'),
    (select id from type_ressource where code = 'paye-etat'),
    sysdate,ose_parametre.get_ose_user,
    sysdate,ose_parametre.get_ose_user
  );
  
INSERT
INTO TYPE_DOTATION
  (
    ID,
    LIBELLE,
    SOURCE_CODE,
    SOURCE_ID,
    TYPE_RESSOURCE_ID,
    HISTO_CREATION,
    HISTO_CREATEUR_ID,
    HISTO_MODIFICATION,
    HISTO_MODIFICATEUR_ID
  )
  VALUES
  (
    type_dotation_id_seq.nextval,
    'Abondement',
    'abondement',
    ose_import.get_source_id('OSE'),
    (select id from type_ressource where code = 'ressources-propres'),
    sysdate,ose_parametre.get_ose_user,
    sysdate,ose_parametre.get_ose_user
  );

/
BEGIN DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"'); END;
/
BEGIN OSE_FORMULE.CALCULER_TOUT; END;
/