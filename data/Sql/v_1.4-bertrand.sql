--
-- Version 1.4
--


---------------------------------------------------------------------------------
-- WF
---------------------------------------------------------------------------------

Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) values (WF_ETAPE_id_seq.nextval, 'DEBUT',                              '0',    'Début du workflow',                           null,                                          null,                                       null,                                                                   '0',     null,                                         0);
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) values (WF_ETAPE_id_seq.nextval, 'DONNEES_PERSO_SAISIE',               '0',    'Saisie des données personnelles',             'ose_workflow.peut_saisir_dossier',            'ose_workflow.possede_dossier',             'Application\Service\Workflow\Step\SaisieDossierStep',                  '1',     null,                                         10);
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) values (WF_ETAPE_id_seq.nextval, 'SERVICE_SAISIE',                     '0',    'Saisie des enseignements prévisionnels',      'ose_workflow.peut_saisir_service',            'ose_workflow.possede_services',            'Application\Service\Workflow\Step\SaisieServiceStep',                  '1',     null,                                         20);
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) values (WF_ETAPE_id_seq.nextval, 'PJ_SAISIE',                          '0',    'Pièces justificatives',                       'ose_workflow.peut_saisir_piece_jointe',       'ose_workflow.pieces_jointes_fournies',     'Application\Service\Workflow\Step\SaisiePiecesJointesStep',            '1',     null,                                         30);
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) values (WF_ETAPE_id_seq.nextval, 'PJ_VALIDATION',                      '0',    'Validation des pièces justificatives',        'ose_workflow.pieces_jointes_fournies',        'ose_workflow.pieces_jointes_validees',     'Application\Service\Workflow\Step\ValidationPiecesJointesStep',        '1',     null,                                         40);
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) values (WF_ETAPE_id_seq.nextval, 'DONNEES_PERSO_VALIDATION',           '0',    'Validation des données personnelles',         'ose_workflow.peut_saisir_dossier',            'ose_workflow.dossier_valide',              'Application\Service\Workflow\Step\ValidationDossierStep',              '1',     null,                                         50);
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) values (WF_ETAPE_id_seq.nextval, 'SERVICE_VALIDATION',                 '1',    'Validation des enseignements prévisionnels',  'ose_workflow.possede_services',               'ose_workflow.service_valide',              'Application\Service\Workflow\Step\ValidationServiceStep',              '1',     'ose_workflow.fetch_struct_ens_ids',          60);
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) values (WF_ETAPE_id_seq.nextval, 'REFERENTIEL_VALIDATION',             '1',    'Validation du référentiel prévisionnel',      'ose_workflow.possede_referentiel',            'ose_workflow.referentiel_valide',          'Application\Service\Workflow\Step\ValidationReferentielStep',          '1',     'ose_workflow.fetch_struct_ref_ids',          70);
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) values (WF_ETAPE_id_seq.nextval, 'CONSEIL_RESTREINT',                  '1',    'Agrément du Conseil Restreint',               'ose_workflow.necessite_agrement_cr',          'ose_workflow.agrement_cr_fourni',          'Application\Service\Workflow\Step\AgrementStep',                       '1',     'ose_workflow.fetch_struct_ens_ids',          80);
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) values (WF_ETAPE_id_seq.nextval, 'CONSEIL_ACADEMIQUE',                 '0',    'Agrément du Conseil Académique',              'ose_workflow.necessite_agrement_ca',          'ose_workflow.agrement_ca_fourni',          'Application\Service\Workflow\Step\AgrementStep',                       '1',     null,                                         90);
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) values (WF_ETAPE_id_seq.nextval, 'CONTRAT',                            '1',    'Contrats et avenants',                        'ose_workflow.necessite_contrat',              'ose_workflow.possede_contrat',             'Application\Service\Workflow\Step\EditionContratStep',                 '1',     'ose_workflow.fetch_struct_ens_ids',          100);
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) values (WF_ETAPE_id_seq.nextval, 'SERVICE_SAISIE_REALISE',             '0',    'Saisie des enseignements réalisés',           'ose_workflow.peut_saisir_service',            'ose_workflow.possede_services_realises',   'Application\Service\Workflow\Step\SaisieServiceRealiseStep',           '1',     null,                                         110);
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) values (WF_ETAPE_id_seq.nextval, 'SERVICE_VALIDATION_REALISE',         '1',    'Validation des enseignements réalisés',       'ose_workflow.possede_services_realises',      'ose_workflow.service_realise_valide',      'Application\Service\Workflow\Step\ValidationServiceRealiseStep',       '1',     'ose_workflow.fetch_struct_ens_ids',          120);
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) values (WF_ETAPE_id_seq.nextval, 'REFERENTIEL_VALIDATION_REALISE',     '1',    'Validation du référentiel réalisé',           'ose_workflow.possede_referentiel_realise',    'ose_workflow.referentiel_realise_valide',  'Application\Service\Workflow\Step\ValidationReferentielRealiseStep',   '1',     'ose_workflow.fetch_struct_ref_ids',          130);
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) values (WF_ETAPE_id_seq.nextval, 'FIN',                                '0',    'Fin du workflow',                             null,                                          null,                                       null,                                                                   '0',     null,                                         1000000);

--Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'DEBUT' and e2.code = 'DONNEES_PERSO_SAISIE';
--Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'DONNEES_PERSO_SAISIE' and e2.code = 'SERVICE_SAISIE';
--Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'SERVICE_SAISIE' and e2.code = 'PJ_SAISIE';
--Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'PJ_SAISIE' and e2.code = 'PJ_VALIDATION';
--Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'PJ_VALIDATION' and e2.code = 'DONNEES_PERSO_VALIDATION';
--Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'DONNEES_PERSO_VALIDATION' and e2.code = 'SERVICE_VALIDATION';
--Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'SERVICE_VALIDATION' and e2.code = 'REFERENTIEL_VALIDATION';
--Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'REFERENTIEL_VALIDATION' and e2.code = 'CONSEIL_RESTREINT';
--Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'CONSEIL_RESTREINT' and e2.code = 'CONSEIL_ACADEMIQUE';
--Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'CONSEIL_ACADEMIQUE' and e2.code = 'CONTRAT';
--Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'CONTRAT' and e2.code = 'SERVICE_SAISIE_REALISE';
--Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'SERVICE_SAISIE_REALISE' and e2.code = 'SERVICE_VALIDATION_REALISE';
--Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'SERVICE_VALIDATION_REALISE' and e2.code = 'REFERENTIEL_VALIDATION_REALISE';
--Insert into WF_ETAPE_TO_ETAPE (DEPART_ETAPE_ID,ARRIVEE_ETAPE_ID) select e1.id, e2.id from WF_ETAPE e1, WF_ETAPE e2 where e1.code = 'REFERENTIEL_VALIDATION_REALISE' and e2.code = 'FIN';

--
-- Génération des progressions de tous les intervenants (~ 40 sec)
--
begin OSE_WORKFLOW.UPDATE_ALL_INTERVENANTS_ETAPES() ; end;
/




---------------------------------------------------------------------------------
-- Validation du référentiel
---------------------------------------------------------------------------------
INSERT INTO TYPE_VALIDATION (
    ID,
    CODE,
    LIBELLE,
    HISTO_CREATEUR_ID,
    HISTO_MODIFICATEUR_ID)
  VALUES (
    TYPE_VALIDATION_id_seq.nextval,
    'REFERENTIEL',
    'Validation du référentiel',
    1,
    1);




---------------------------------------------------------------------------------
-- Indicateurs
---------------------------------------------------------------------------------

Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'AttenteValidationDonneesPerso',      'Données personnelles','AttenteValidationDonneesPerso','100','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'DonneesPersoDiffImport',             'Données personnelles','DonneesPersoDiffImport','1000','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'AttentePieceJustif',                 'Pièces justificatives','AttentePieceJustif','200','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'AttenteValidationPieceJustif',       'Pièces justificatives','AttenteValidationPieceJustif','210','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'AttenteValidationEns',               'Enseignements','AttenteValidationEnsIndicateurImpl','300','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'AttenteAgrementCR',                  'Agrément','AttenteAgrementCR','400','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'AttenteAgrementCA',                  'Agrément','AttenteAgrementCA','500','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'AttenteContrat',                     'Contrat / avenant','Attente Contrat','600','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'AttenteAvenant',                     'Contrat / avenant','Attente Avenant','700','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'SaisieServiceApresContratAvenant',   'Contrat / avenant','Saisie Service Apres Contrat Avenant','800','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'AgrementCAMaisPasContrat',           'Contrat / avenant','AgrementCAMaisPasContrat','50','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'ContratAvenantDeposes',              'Contrat / avenant','Contrat Avenant Déposés','900','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'AttenteRetourContrat',               'Contrat / avenant','AttenteRetourContrat','950','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'PermAffectAutreIntervMeme',          'Affectation','PermAffectAutreIntervMeme','975','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'PermAffectMemeIntervAutre',          'Affectation','PermAffectMemeIntervAutre','976','1');
Insert into INDICATEUR (ID,CODE,TYPE,LIBELLE,ORDRE,ENABLED) values (indicateur_id_seq.nextval,'BiatssAffectMemeIntervAutre',        'Affectation','BiatssAffectMemeIntervAutre','977','1');
