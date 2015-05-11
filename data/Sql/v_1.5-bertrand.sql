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
-- WF
---------------------------------------------------------------------------------

Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) 
values (WF_ETAPE_id_seq.nextval, 'DEMANDE_MEP', '1', 'Demande mise en paiemant', 'ose_workflow.peut_demander_mep', 'ose_workflow.possede_demande_mep', 'Application\Service\Workflow\Step\DemandeMepStep', '1', 'ose_workflow.fetch_struct_ensref_realis_ids', 140);
Insert into WF_ETAPE (ID,CODE,STRUCTURE_DEPENDANT,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURES_IDS_FUNC,ORDRE) 
values (WF_ETAPE_id_seq.nextval, 'SAISIE_MEP',  '1', 'Mise en paiement',         'ose_workflow.peut_saisir_mep',   'ose_workflow.possede_mep',         'Application\Service\Workflow\Step\MepStep',        '1', 'ose_workflow.fetch_struct_ensref_realis_ids', 150);

--
-- Génération des progressions de tous les intervenants (~ 2 min)
--
begin OSE_WORKFLOW.UPDATE_ALL_INTERVENANTS_ETAPES() ; end;
/
