-- ********************************************************************* --
-- *          à faire AVANT avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"', force => TRUE); END; 

-- déannualisation des étapes du WF
SELECT
  'UPDATE wf_intervenant_etape SET etape_id = ' || we2.id || ' WHERE id = ' || wie.id || ';' req
FROM
  wf_intervenant_etape wie
  JOIN wf_etape we ON we.id = wie.etape_id AND we.annee_id <> 2015
  JOIN wf_etape we2 ON we2.code = we.code AND we2.annee_id = 2015;
  
DELETE FROM wf_etape where annee_id <> 2015;



-- suppr des étapes de début et de fin


INSERT INTO WF_TYPE_DEP (
  ID,
  CODE,
  LIBELLE
) VALUES (
  WF_TYPE_DEP_ID_SEQ.NEXTVAL,
  'locale-partielle',
  'Les étapes dépendantes à la structure près doivent être au moins partiellement franchies'
);
  
INSERT INTO WF_TYPE_DEP (
  ID,
  CODE,
  LIBELLE
) VALUES (
  WF_TYPE_DEP_ID_SEQ.NEXTVAL,
  'locale-complete',
  'Les étapes dépendantes à la structure près doivent être intégralement franchies'
);

INSERT INTO WF_TYPE_DEP (
  ID,
  CODE,
  LIBELLE
) VALUES (
  WF_TYPE_DEP_ID_SEQ.NEXTVAL,
  'globale-partielle',
  'Toutes les étapes dépendantes doivent être au moins partiellement franchies'
);

INSERT INTO WF_TYPE_DEP (
  ID,
  CODE,
  LIBELLE
) VALUES (
  WF_TYPE_DEP_ID_SEQ.NEXTVAL,
  'globale-complete',
  'Toutes les étapes dépendantes doivent être intégralement franchies'
);


-- insert des dépendances
INSERT INTO WF_ETAPE_DEP (ETAPE_SUIV_ID, ETAPE_PREC_ID) VALUES (
  (SELECT id FROM wf_etape WHERE code = 'PJ_SAISIE'),
  (SELECT id FROM wf_etape WHERE code = 'DONNEES_PERSO_SAISIE')
);

INSERT INTO WF_ETAPE_DEP (ETAPE_SUIV_ID, ETAPE_PREC_ID) VALUES (
  (SELECT id FROM wf_etape WHERE code = 'PJ_SAISIE'),
  (SELECT id FROM wf_etape WHERE code = 'SERVICE_SAISIE')
);

INSERT INTO WF_ETAPE_DEP (ETAPE_SUIV_ID, ETAPE_PREC_ID) VALUES (
  (SELECT id FROM wf_etape WHERE code = 'PJ_VALIDATION'),
  (SELECT id FROM wf_etape WHERE code = 'PJ_SAISIE')
);

INSERT INTO WF_ETAPE_DEP (ETAPE_SUIV_ID, ETAPE_PREC_ID) VALUES (
  (SELECT id FROM wf_etape WHERE code = 'DONNEES_PERSO_VALIDATION'),
  (SELECT id FROM wf_etape WHERE code = 'PJ_VALIDATION')
);

INSERT INTO WF_ETAPE_DEP (ETAPE_SUIV_ID, ETAPE_PREC_ID) VALUES (
  (SELECT id FROM wf_etape WHERE code = 'SERVICE_VALIDATION'),
  (SELECT id FROM wf_etape WHERE code = 'SERVICE_SAISIE')
);

INSERT INTO WF_ETAPE_DEP (ETAPE_SUIV_ID, ETAPE_PREC_ID) VALUES (
  (SELECT id FROM wf_etape WHERE code = 'REFERENTIEL_VALIDATION'),
  (SELECT id FROM wf_etape WHERE code = 'SERVICE_SAISIE')
);

INSERT INTO WF_ETAPE_DEP (ETAPE_SUIV_ID, ETAPE_PREC_ID) VALUES (
  (SELECT id FROM wf_etape WHERE code = 'CONSEIL_RESTREINT'),
  (SELECT id FROM wf_etape WHERE code = 'REFERENTIEL_VALIDATION')
);

INSERT INTO WF_ETAPE_DEP (ETAPE_SUIV_ID, ETAPE_PREC_ID) VALUES (
  (SELECT id FROM wf_etape WHERE code = 'CONSEIL_RESTREINT'),
  (SELECT id FROM wf_etape WHERE code = 'DONNEES_PERSO_VALIDATION')
);

INSERT INTO WF_ETAPE_DEP (ETAPE_SUIV_ID, ETAPE_PREC_ID) VALUES (
  (SELECT id FROM wf_etape WHERE code = 'CONSEIL_RESTREINT'),
  (SELECT id FROM wf_etape WHERE code = 'SERVICE_VALIDATION')
);

INSERT INTO WF_ETAPE_DEP (ETAPE_SUIV_ID, ETAPE_PREC_ID) VALUES (
  (SELECT id FROM wf_etape WHERE code = 'CONSEIL_RESTREINT'),
  (SELECT id FROM wf_etape WHERE code = 'PJ_VALIDATION')
);

INSERT INTO WF_ETAPE_DEP (ETAPE_SUIV_ID, ETAPE_PREC_ID) VALUES (
  (SELECT id FROM wf_etape WHERE code = 'CONSEIL_ACADEMIQUE'),
  (SELECT id FROM wf_etape WHERE code = 'CONSEIL_RESTREINT')
);

INSERT INTO WF_ETAPE_DEP (ETAPE_SUIV_ID, ETAPE_PREC_ID) VALUES (
  (SELECT id FROM wf_etape WHERE code = 'CONTRAT'),
  (SELECT id FROM wf_etape WHERE code = 'CONSEIL_RESTREINT')
);

INSERT INTO WF_ETAPE_DEP (ETAPE_SUIV_ID, ETAPE_PREC_ID) VALUES (
  (SELECT id FROM wf_etape WHERE code = 'CONTRAT'),
  (SELECT id FROM wf_etape WHERE code = 'CONSEIL_ACADEMIQUE')
);

INSERT INTO WF_ETAPE_DEP (ETAPE_SUIV_ID, ETAPE_PREC_ID) VALUES (
  (SELECT id FROM wf_etape WHERE code = 'SERVICE_SAISIE_REALISE'),
  (SELECT id FROM wf_etape WHERE code = 'CONTRAT')
);

INSERT INTO WF_ETAPE_DEP (ETAPE_SUIV_ID, ETAPE_PREC_ID) VALUES (
  (SELECT id FROM wf_etape WHERE code = 'CLOTURE_REALISE'),
  (SELECT id FROM wf_etape WHERE code = 'SERVICE_SAISIE_REALISE')
);

INSERT INTO WF_ETAPE_DEP (ETAPE_SUIV_ID, ETAPE_PREC_ID) VALUES (
  (SELECT id FROM wf_etape WHERE code = 'SERVICE_VALIDATION_REALISE'),
  (SELECT id FROM wf_etape WHERE code = 'CLOTURE_REALISE')
);

INSERT INTO WF_ETAPE_DEP (ETAPE_SUIV_ID, ETAPE_PREC_ID) VALUES (
  (SELECT id FROM wf_etape WHERE code = 'REFERENTIEL_VALIDATION_REALISE'),
  (SELECT id FROM wf_etape WHERE code = 'CLOTURE_REALISE')
);

INSERT INTO WF_ETAPE_DEP (ETAPE_SUIV_ID, ETAPE_PREC_ID) VALUES (
  (SELECT id FROM wf_etape WHERE code = 'SERVICE_VALIDATION_REALISE'),
  (SELECT id FROM wf_etape WHERE code = 'SERVICE_SAISIE_REALISE')
);

INSERT INTO WF_ETAPE_DEP (ETAPE_SUIV_ID, ETAPE_PREC_ID) VALUES (
  (SELECT id FROM wf_etape WHERE code = 'REFERENTIEL_VALIDATION_REALISE'),
  (SELECT id FROM wf_etape WHERE code = 'SERVICE_SAISIE_REALISE')
);

INSERT INTO WF_ETAPE_DEP (ETAPE_SUIV_ID, ETAPE_PREC_ID) VALUES (
  (SELECT id FROM wf_etape WHERE code = 'DEMANDE_MEP'),
  (SELECT id FROM wf_etape WHERE code = 'CLOTURE_REALISE')
);

INSERT INTO WF_ETAPE_DEP (ETAPE_SUIV_ID, ETAPE_PREC_ID) VALUES (
  (SELECT id FROM wf_etape WHERE code = 'SAISIE_MEP'),
  (SELECT id FROM wf_etape WHERE code = 'DEMANDE_MEP')
);

/


update statut_intervenant set tem_atv = 1 WHERE source_code IN (

'ETUD_HORS_UCBN',
'ETUD_UCBN',
'RETR_HORS_UCBN'

);


--select * from parametre;


INSERT INTO PARAMETRE (
  NOM, 
  VALEUR, 
  DESCRIPTION,
  ID,
  HISTO_CREATEUR_ID,
  HISTO_MODIFICATEUR_ID
)VALUES(
  'contrat_etablissement',
  'L''université de Caen',
  'Contrat : établissement',
  PARAMETRE_ID_SEQ.NEXTVAL,
  (select id from utilisateur where username = 'lecluse'),
  (select id from utilisateur where username = 'lecluse')
);

INSERT INTO PARAMETRE (
  NOM, 
  VALEUR, 
  DESCRIPTION,
  ID,
  HISTO_CREATEUR_ID,
  HISTO_MODIFICATEUR_ID
)VALUES(
  'contrat_etablissement_represente',
  'représentée par son Président, Pierre DENISE',
  'Contrat : représentant',
  PARAMETRE_ID_SEQ.NEXTVAL,
  (select id from utilisateur where username = 'lecluse'),
  (select id from utilisateur where username = 'lecluse')
);

INSERT INTO PARAMETRE (
  NOM, 
  VALEUR, 
  DESCRIPTION,
  ID,
  HISTO_CREATEUR_ID,
  HISTO_MODIFICATEUR_ID
)VALUES(
  'contrat_civilite_president',
  'le Président',
  'Contrat : civilité du président (avec article)',
  PARAMETRE_ID_SEQ.NEXTVAL,
  (select id from utilisateur where username = 'lecluse'),
  (select id from utilisateur where username = 'lecluse')
);

INSERT INTO PARAMETRE (
  NOM, 
  VALEUR, 
  DESCRIPTION,
  ID,
  HISTO_CREATEUR_ID,
  HISTO_MODIFICATEUR_ID
)VALUES(
  'contrat_lieu_signature',
  'Caen',
  'LContrat : lieu de signature',
  PARAMETRE_ID_SEQ.NEXTVAL,
  (select id from utilisateur where username = 'lecluse'),
  (select id from utilisateur where username = 'lecluse')
);



INSERT INTO REGLE_STRUCTURE_VALIDATION (
    ID,
    TYPE_VOLUME_HORAIRE_ID,
    TYPE_INTERVENANT_ID,
    PRIORITE
  ) VALUES (
    REGLE_STRUCTURE_VAL_ID_SEQ.NEXTVAL,
    (select id from type_volume_horaire WHERE code = 'PREVU'), -- PREVU ou REALISE
    (select id from TYPE_INTERVENANT WHERE code = 'P'), -- P ou E
    'affectation'
  );
  
  INSERT INTO REGLE_STRUCTURE_VALIDATION (
    ID,
    TYPE_VOLUME_HORAIRE_ID,
    TYPE_INTERVENANT_ID,
    PRIORITE
  ) VALUES (
    REGLE_STRUCTURE_VAL_ID_SEQ.NEXTVAL,
    (select id from type_volume_horaire WHERE code = 'PREVU'), -- PREVU ou REALISE
    (select id from TYPE_INTERVENANT WHERE code = 'E'), -- P ou E
    'enseignement'
  );
  
  INSERT INTO REGLE_STRUCTURE_VALIDATION (
    ID,
    TYPE_VOLUME_HORAIRE_ID,
    TYPE_INTERVENANT_ID,
    PRIORITE
  ) VALUES (
    REGLE_STRUCTURE_VAL_ID_SEQ.NEXTVAL,
    (select id from type_volume_horaire WHERE code = 'REALISE'), -- PREVU ou REALISE
    (select id from TYPE_INTERVENANT WHERE code = 'P'), -- P ou E
    'enseignement'
  );
  
  INSERT INTO REGLE_STRUCTURE_VALIDATION (
    ID,
    TYPE_VOLUME_HORAIRE_ID,
    TYPE_INTERVENANT_ID,
    PRIORITE
  ) VALUES (
    REGLE_STRUCTURE_VAL_ID_SEQ.NEXTVAL,
    (select id from type_volume_horaire WHERE code = 'REALISE'), -- PREVU ou REALISE
    (select id from TYPE_INTERVENANT WHERE code = 'E'), -- P ou E
    'enseignement'
  );
  


-- PENSER A TOUTES LES AUTRES DEMANDES DE PACKAGES ! ! !  
INSERT INTO "OSE"."PACKAGE_DEPS" (ID, P1, P2) VALUES ('15', 'OSE_WORKFLOW', 'OSE_VALIDATION_ENSEIGNEMENT')
INSERT INTO "OSE"."PACKAGE_DEPS" (ID, P1, P2) VALUES ('16', 'OSE_WORKFLOW', 'OSE_VALIDATION_REFERENTIEL')

  


-- ********************************************************************* --
-- *          à faire APRÈS avoir mis à jour le code source            * --
-- ********************************************************************* --


BEGIN DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"'); END;
/
BEGIN OSE_FORMULE.CALCULER_TOUT; END;
/