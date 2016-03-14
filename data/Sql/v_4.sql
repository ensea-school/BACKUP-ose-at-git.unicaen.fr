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





-- ********************************************************************* --
-- *          à faire APRÈS avoir mis à jour le code source            * --
-- ********************************************************************* --


BEGIN DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"'); END;
/
BEGIN OSE_FORMULE.CALCULER_TOUT; END;
/