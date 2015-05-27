--
-- Version 1.5
--


---------------------------------------------------------------------------------
-- Modif Statuts (Tâche #1728 : Refonte de la page Données personnelles)
---------------------------------------------------------------------------------

update statut_intervenant set 
    LIBELLE = 'Sans emploi, non étudiant', 
    SOURCE_CODE = 'SS_EMPLOI_NON_ETUD', 
    PEUT_CHOISIR_DANS_DOSSIER = 1,
    histo_modification = sysdate
where SOURCE_CODE = 'CHARG_ENS_1AN';

update statut_intervenant set 
    LIBELLE = 'Auto-entrepreneur, profession libérale ou indépendante', 
    SOURCE_CODE = 'AUTO_LIBER_INDEP',
    histo_modification = sysdate
where SOURCE_CODE = 'NON_SALAR';







---------------------------------------------------------------------------------
-- Refonte gestion des PJ.
-- Nécessaire pour permettre la demande du RIB uniquement s'il a changé dans le dossier.
---------------------------------------------------------------------------------

-- par défaut, le RIB devient une PJ obligatoire (premier recrutement ou non)
delete from type_piece_jointe_statut where type_piece_jointe_id = ( select id from type_piece_jointe where code = 'RIB');

-- mise à niveau du contenu de la table PIECE_JOINTE.
-- NB: peut être la,cée plusieurs fois.
alter trigger WF_TRG_PJ disable;
alter trigger WF_TRG_PJ_VALIDATION disable;
begin upgrade_piece_jointe_v15(); end; 
/
alter trigger WF_TRG_PJ enable;
alter trigger WF_TRG_PJ_VALIDATION enable;


-- drop table PJ_TMP_INTERVENANT;
create global temporary table pj_tmp_intervenant (
  intervenant_id number(*,0) not null , 
  type_piece_jointe_id number(*,0) not null , 
  CONSTRAINT "PJ_TMP_INTERVENANT_PK" PRIMARY KEY (INTERVENANT_ID, type_piece_jointe_id) 
) on commit delete rows ;






---------------------------------------------------------------------------------
-- WF
---------------------------------------------------------------------------------

Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) 
values (WF_ETAPE_id_seq.nextval, 'CLOTURE_REALISE', '0', 'Clôture de la saisie du service réalisé', 'ose_workflow.peut_cloturer_realise', 'ose_workflow.realise_cloture', 'Application\Service\Workflow\Step\ClotureRealiseStep', '1', null, 115);

Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) 
values (WF_ETAPE_id_seq.nextval, 'DEMANDE_MEP', '1', 'Demande mise en paiemant', 'ose_workflow.peut_demander_mep', 'ose_workflow.possede_demande_mep', 'Application\Service\Workflow\Step\DemandeMepStep', '1', 'ose_workflow.fetch_struct_ensref_realis_ids', 140);
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) 
values (WF_ETAPE_id_seq.nextval, 'SAISIE_MEP',  '1', 'Mise en paiement',         'ose_workflow.peut_saisir_mep',   'ose_workflow.possede_mep',         'Application\Service\Workflow\Step\MepStep',        '1', 'ose_workflow.fetch_struct_ensref_realis_ids', 150);

--
-- Génération des progressions de tous les intervenants (~ 2 min)
--
begin OSE_WORKFLOW.UPDATE_ALL_INTERVENANTS_ETAPES() ; end;
/





---------------------------------------------------------------------------------
-- Indicateurs
---------------------------------------------------------------------------------

update indicateur set code = 'AttenteValidationEnsPrevuVac'   where code = 'AttenteValidationEnsPrevu';
update indicateur set code = 'AttenteValidationEnsRealiseVac' where code = 'AttenteValidationEnsRealise';

Insert into INDICATEUR (ID,CODE,TYPE,ORDRE,ENABLED) values (
    indicateur_id_seq.nextval,
    'AttenteValidationEnsPrevuPerm',  
    'Enseignements et référentiel',
    '325',
    '1'
);
Insert into INDICATEUR (ID,CODE,TYPE,ORDRE,ENABLED) values (
    indicateur_id_seq.nextval,
    'AttenteValidationEnsRealisePerm',
    'Enseignements et référentiel',
    '375',
    '1'
);


update indicateur set code = 'AttenteDemandeMepVac' where code = 'AttenteDemandeMep';
update indicateur set code = 'AttenteMepVac'        where code = 'AttenteMep';

Insert into INDICATEUR (ID,CODE,TYPE,ORDRE,ENABLED) values (
    indicateur_id_seq.nextval,
    'AttenteDemandeMepPerm',  
    'Mise en paiement',
    '1150',
    '1'
);
Insert into INDICATEUR (ID,CODE,TYPE,ORDRE,ENABLED) values (
    indicateur_id_seq.nextval,
    'AttenteMepPerm',
    'Mise en paiement',
    '1250',
    '1'
);


Insert into INDICATEUR (ID,CODE,TYPE,ORDRE,ENABLED) values (
    indicateur_id_seq.nextval,
    'EnsRealisePermSaisieCloturee',
    'Enseignements et référentiel',
    '335',
    '1'
);
