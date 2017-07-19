-- ********************************************************************* --
-- *          à faire AVANT avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"', force => TRUE); END; 
/


DROP INDEX CCEP_ELEMENT_PEDAGOGIQUE_FK;
DROP INDEX FICHIER_HMFK;
DROP INDEX TYPE_PIECE_JOINTE_HDFK;
DROP INDEX TYPE_INTERVENTION_STRUCTU_HMFK;
DROP INDEX TYPE_PIECE_JOINTE_STATUT_HCFK;
DROP INDEX TYPE_INTERVENANT_HMFK;
DROP INDEX ETAPE_STRUCTURE_FK;
DROP INDEX FONC_REF_DOMAINE_FONCT_FK;
DROP INDEX HSM_INTERVENANT_FK;
DROP INDEX GRADE_HDFK;
DROP INDEX CC_ACTIVITE_HCFK;
DROP INDEX CCEP_CENTRE_COUT_FK;
DROP INDEX ETAPE_HMFK;
DROP INDEX PIECE_JOINTE_HMFK;
DROP INDEX TYPE_MODULATEUR_EP_HDFK;
DROP INDEX PERSONNEL_STRUCTURE_FK;
DROP INDEX AFFECTATION_R_STRUCTURE_FK;
DROP INDEX PERIODE_HDFK;
DROP INDEX PERSONNEL_HMFK;
DROP INDEX TYPE_INTERVENTION_STRUCTU_HDFK;
DROP INDEX VALIDATION_HCFK;
DROP INDEX SERVICE_INTERVENANT_FK;
DROP INDEX DEPARTEMENT_HCFK;
DROP INDEX TYPE_STRUCTURE_HMFK;
DROP INDEX CCEP_TYPE_HEURES_FK;
DROP INDEX AFFECTATION_STRUCTURE_FK;
DROP INDEX WF_ETAPE_AFK;
DROP INDEX TIS_ANNEE_DEBUT_FK;
DROP INDEX ROLE_HCFK;
DROP INDEX TYPE_RESSOURCE_HCFK;
DROP INDEX AFFECTATION_HMFK;
DROP INDEX TME_ELEMENT_PEDAGOGIQUE_FK;
DROP INDEX STRUCTURES_STRUCTURES_FK;
DROP INDEX PERSONNEL_SOURCE_FK;
DROP INDEX PJ_TYPE_PIECE_JOINTE_FK;
DROP INDEX ETR_SOURCE_FK;
DROP INDEX DEPARTEMENT_HMFK;
DROP INDEX VHENS_ELEMENT_PEDAGOGIQUE_FK;
DROP INDEX TYPE_DOTATION_SOURCE_FK;
DROP INDEX TYPE_MODULATEUR_HCFK;
DROP INDEX AFFECTATION_ROLE_FK;
DROP INDEX MEP_DOMAINE_FONCTIONNEL_FK;
DROP INDEX TAUX_HORAIRE_HETD_HCFK;
DROP INDEX TYPE_STRUCTURE_HDFK;
DROP INDEX CONTRAT_HMFK;
DROP INDEX AGREMENT_HDFK;
DROP INDEX STATUT_INTERVENANT_SOURCE_FK;
DROP INDEX DOSSIER_HMFK;
DROP INDEX TYPE_AGREMENT_HMFK;
DROP INDEX ELEMENT_TAUX_REGIMES_HCFK;
DROP INDEX SRFR_FK;
DROP INDEX AFFECTATION_HCFK;
DROP INDEX CENTRE_COUT_HMFK;
DROP INDEX VVHR_VALIDATION_FK;
DROP INDEX PRIVILEGE_CATEGORIE_FK;
DROP INDEX CONTRAT_VALIDATION_FK;
DROP INDEX MODULATEUR_TYPE_MODULATEUR_FK;
DROP INDEX VHENS_TYPE_INTERVENTION_FK;
DROP INDEX DOSSIER_INTERVENANT_FK;
DROP INDEX CORPS_HMFK;
DROP INDEX CPEP_FK;
DROP INDEX FRES_TYPE_VOLUME_HORAIRE_FK;
DROP INDEX PARAMETRE_HDFK;
DROP INDEX MEP_FR_SERVICE_FK;
DROP INDEX STATUT_INTERVENANT_TYPE_FK;
DROP INDEX GROUPE_TYPE_FORMATION_HMFK;
DROP INDEX MISE_EN_PAIEMENT_HCFK;
DROP INDEX GTYPE_FORMATION_SOURCE_FK;
DROP INDEX FRES_INTERVENANT_FK;
DROP INDEX TME_TYPE_MODULATEUR_FK;
DROP INDEX TYPE_HEURES_HDFK;
DROP INDEX PIECE_JOINTE_HDFK;
DROP INDEX MOTIF_MODIFICATION_SERVIC_HMFK;
DROP INDEX DISCIPLINE_HDFK;
DROP INDEX STRUCTURE_HDFK;
DROP INDEX PAYS_HDFK;
DROP INDEX ETAT_VOLUME_HORAIRE_HCFK;
DROP INDEX TYPE_HEURES_TYPE_HEURES_FK;
DROP INDEX DS_MDS_FK;
DROP INDEX VOLUME_HORAIRE_REF_HDFK;
DROP INDEX TAS_STATUT_INTERVENANT_FK;
DROP INDEX VHMNP_FK;
DROP INDEX ETAT_VOLUME_HORAIRE_HDFK;
DROP INDEX ADRESSE_INTERVENANT_SOURCE_FK;
DROP INDEX STATUT_INTERVENANT_HMFK;
DROP INDEX ETAPE_HDFK;
DROP INDEX PERIODE_HMFK;
DROP INDEX WF_INTERVENANT_ETAPE_SFK;
DROP INDEX CENTRE_COUT_EP_HCFK;
DROP INDEX SERVICE_ETABLISSEMENT_FK;
DROP INDEX TYPE_AGREMENT_STATUT_HDFK;
DROP INDEX EPS_FK;
DROP INDEX TYPE_PIECE_JOINTE_HMFK;
DROP INDEX ADRESSE_STRUCTURE_SOURCE_FK;
DROP INDEX VOLUME_HORAIRE_CONTRAT_FK;
DROP INDEX VOLUMES_HORAIRES_SERVICES_FK;
DROP INDEX CONTRAT_INTERVENANT_FK;
DROP INDEX INDIC_MODIF_DOSSIER_HDFK;
DROP INDEX ADRESSE_STRUCTURE_HCFK;
DROP INDEX TYPE_VOLUME_HORAIRE_HCFK;
DROP INDEX GROUPE_TYPE_FORMATION_HCFK;
DROP INDEX VALIDATION_HDFK;
DROP INDEX TYPE_DOTATION_HCFK;
DROP INDEX DOMAINE_FONCTIONNEL_HDFK;
DROP INDEX PERSONNEL_CIVILITE_FK;
DROP INDEX TYPE_INTERVENTION_EP_HCFK;
DROP INDEX ETAT_VOLUME_HORAIRE_HMFK;
DROP INDEX PAYS_SOURCE_FK;
DROP INDEX SERVICE_HDFK;
DROP INDEX TYPE_INTERVENTION_HDFK;
DROP INDEX TYPE_RESSOURCE_HMFK;
DROP INDEX TYPE_AGREMENT_HDFK;
DROP INDEX CENTRE_COUT_EP_HMFK;
DROP INDEX AFFECTATION_R_SOURCE_FK;
DROP INDEX DOTATION_ANNEE_FK;
DROP INDEX EM_ELEMENT_PEDAGOGIQUE_FK;
DROP INDEX TIEP_TYPE_INTERVENTION_FK;
DROP INDEX TYPE_DOTATION_HDFK;
DROP INDEX ROLE_PRIVILEGE_PRIVILEGE_FK;
DROP INDEX ELEMENT_PEDAGOGIQUE_SOURCE_FK;
DROP INDEX CENTRE_COUT_HCFK;
DROP INDEX ELEMENT_PEDAGOGIQUE_ANNEE_FK;
DROP INDEX CHEMIN_PEDAGOGIQUE_HCFK;
DROP INDEX TYPE_VALIDATION_HCFK;
DROP INDEX TIS_STRUCTURE_FK;
DROP INDEX MOTIF_NON_PAIEMENT_HCFK;
DROP INDEX AFFECTATION_HDFK;
DROP INDEX PIECE_JOINTE_HCFK;
DROP INDEX CONTRAT_FICHIER_FFK;
DROP INDEX ROLE_PRIVILEGE_ROLE_FK;
DROP INDEX TYPE_CONTRAT_HMFK;
DROP INDEX AGREMENT_HMFK;
DROP INDEX MODULATEUR_HDFK;
DROP INDEX PERSONNEL_HDFK;
DROP INDEX AGREMENT_HCFK;
DROP INDEX EFFECTIFS_ELEMENT_FK;
DROP INDEX DEPARTEMENT_HDFK;
DROP INDEX TYPE_FORMATION_HCFK;
DROP INDEX AFFECTATION_R_INTERVENANT_FK;
DROP INDEX SR_STRUCTURE_FK;
DROP INDEX TYPE_VOLUME_HORAIRE_HMFK;
DROP INDEX AFFECTATION_R_HCFK;
DROP INDEX CONTRAT_CONTRAT_FK;
DROP INDEX AFFECTATION_R_HDFK;
DROP INDEX INTERVENANT_STATUT_FK;
DROP INDEX TYPE_MODULATEUR_EP_HCFK;
DROP INDEX FONCTION_REFERENTIEL_HDFK;
DROP INDEX FICHIER_HDFK;
DROP INDEX VOLUME_HORAIRE_HCFK;
DROP INDEX GROUPE_ELEMENT_PEDAGOGIQUE_FK;
DROP INDEX CENTRE_COUT_HDFK;
DROP INDEX FRVH_VOLUME_HORAIRE_FK;
DROP INDEX GRADE_HMFK;
DROP INDEX VALIDATION_HMFK;
DROP INDEX DISCIPLINE_SOURCE_FK;
DROP INDEX DOTATION_TYPE_DOTATION_FK;
DROP INDEX TYPE_PIECE_JOINTE_HCFK;
DROP INDEX TYPE_CONTRAT_HDFK;
DROP INDEX SERVICE_HCFK;
DROP INDEX DOSSIER_HCFK;
DROP INDEX ADRESSE_INTERVENANT_HMFK;
DROP INDEX EFFECTIFS_HMFK;
DROP INDEX TYPE_CONTRAT_HCFK;
DROP INDEX MEP_FR_SERVICE_REF_FK;
DROP INDEX TYPE_FORMATION_SOURCE_FK;
DROP INDEX TYPE_MODULATEUR_STRUCTURE_HMFK;
DROP INDEX CENTRE_COUT_STRUCTURE_FK;
DROP INDEX ELEMENT_PEDAGOGIQUE_HMFK;
DROP INDEX INTERVENANT_STRUCTURE_FK;
DROP INDEX HSM_UTILISATEUR_FK;
DROP INDEX FRR_FORMULE_RESULTAT_FK;
DROP INDEX INTERVENANT_HCFK;
DROP INDEX FRS_FORMULE_RESULTAT_FK;
DROP INDEX ETABLISSEMENT_HCFK;
DROP INDEX AFFECTATION_PERSONNEL_FK;
DROP INDEX CHEMIN_PEDAGOGIQUE_HDFK;
DROP INDEX TYPE_INTERVENTION_EP_HMFK;
DROP INDEX TYPE_FORMATION_HMFK;
DROP INDEX MOTIF_NON_PAIEMENT_HMFK;
DROP INDEX TYPE_INTERVENANT_HCFK;
DROP INDEX PIECE_JOINTE_FICHIER_PJFK;
DROP INDEX PJ_DOSSIER_FK;
DROP INDEX VHIT_FK;
DROP INDEX CHEMIN_PEDAGOGIQUE_ETAPE_FK;
DROP INDEX ELEMENT_MODULATEUR_HCFK;
DROP INDEX VOLUME_HORAIRE_REF_HMFK;
DROP INDEX VVH_VOLUME_HORAIRE_FK;
DROP INDEX PAYS_HCFK;
DROP INDEX TYPE_STRUCTURE_HCFK;
DROP INDEX CENTRE_COUT_TYPE_RESSOURCE_FK;
DROP INDEX CHEMIN_PEDAGOGIQUE_HMFK;
DROP INDEX AFFECTATION_SOURCE_FK;
DROP INDEX TYPE_AGREMENT_STATUT_HCFK;
DROP INDEX INDIC_MODIF_DOSSIER_HCFK;
DROP INDEX GROUPE_HDFK;
DROP INDEX ELEMENT_PEDAGOGIQUE_ETAPE_FK;
DROP INDEX VOLUME_HORAIRE_ENS_HDFK;
DROP INDEX ADRESSE_INTERVENANT_HCFK;
DROP INDEX SERVICE_REFERENTIEL_HDFK;
DROP INDEX EFFECTIFS_HDFK;
DROP INDEX NOTIF_INDICATEUR_IFK;
DROP INDEX INDIC_MODIF_DOSSIER_HMFK;
DROP INDEX CC_ACTIVITE_HMFK;
DROP INDEX SERVICE_REFERENTIEL_HMFK;
DROP INDEX FICHIER_VALID_FK;
DROP INDEX STATUT_INTERVENANT_HDFK;
DROP INDEX NOTIF_INDICATEUR_UFK;
DROP INDEX TYPE_PIECE_JOINTE_STATUT_HMFK;
DROP INDEX MODIFICATION_SERVICE_DU_HCFK;
DROP INDEX INTERVENANT_HMFK;
DROP INDEX DOTATION_STRUCTURE_FK;
DROP INDEX MSD_INTERVENANT_FK;
DROP INDEX CC_ACTIVITE_HDFK;
DROP INDEX MISE_EN_PAIEMENT_HMFK;
DROP INDEX MODULATEUR_HMFK;
DROP INDEX INTERVENANT_GRADE_FK;
DROP INDEX ETABLISSEMENT_SOURCE_FK;
DROP INDEX TYPE_MODULATEUR_HDFK;
DROP INDEX GRADE_HCFK;
DROP INDEX TYPE_MODULATEUR_STRUCTURE_HDFK;
DROP INDEX ETAPE_HCFK;
DROP INDEX TYPE_MODULATEUR_HMFK;
DROP INDEX TYPE_MODULATEUR_EP_HMFK;
DROP INDEX DOMAINE_FONCTIONNEL_HCFK;
DROP INDEX TYPE_INTERVENTION_HMFK;
DROP INDEX TYPE_INTERVENTION_EP_HDFK;
DROP INDEX TYPE_AGREMENT_STATUT_HMFK;
DROP INDEX AGREMENT_STRUCTURE_FK;
DROP INDEX GRADE_CORPS_FK;
DROP INDEX VH_PERIODE_FK;
DROP INDEX STRUCTURE_ETABLISSEMENT_FK;
DROP INDEX DISCIPLINE_HMFK;
DROP INDEX TYPE_INTERVENTION_EP_SOURCE_FK;
DROP INDEX STATUT_INTERVENANT_HCFK;
DROP INDEX PARAMETRE_HCFK;
DROP INDEX FICHIER_HCFK;
DROP INDEX FRES_ETAT_VOLUME_HORAIRE_FK;
DROP INDEX WF_INTERVENANT_ETAPE_IFK;
DROP INDEX TYPE_INTERVENTION_STRUCTU_HCFK;
DROP INDEX DOTATION_HMFK;
DROP INDEX CENTRE_COUT_ACTIVITE_FK;
DROP INDEX STRUCTURE_SOURCE_FK;
DROP INDEX CENTRE_COUT_EP_HDFK;
DROP INDEX ELEMENT_PEDAGOGIQUE_HCFK;
DROP INDEX TAUX_HORAIRE_HETD_HDFK;
DROP INDEX PERSONNEL_HCFK;
DROP INDEX STAT_PRIV_STATUT_FK;
DROP INDEX ADRESSE_STRUCTURE_HMFK;
DROP INDEX GROUPE_HCFK;
DROP INDEX GROUPE_TYPE_FORMATION_HDFK;
DROP INDEX MISE_EN_PAIEMENT_VALIDATION_FK;
DROP INDEX CONTRAT_HDFK;
DROP INDEX ETAPE_DOMAINE_FONCTIONNEL_FK;
DROP INDEX DOSSIER_HDFK;
DROP INDEX ELEMENT_PEDAGOGIQUE_HDFK;
DROP INDEX NOTIF_INDICATEUR_SFK;
DROP INDEX AGREMENT_INTERVENANT_FK;
DROP INDEX CHEMIN_PEDAGOGIQUE_SOURCE_FK;
DROP INDEX VOLUME_HORAIRE_REF_HCFK;
DROP INDEX TD_TYPE_RESSOURCE_FK;
DROP INDEX ETAPE_TYPE_FORMATION_FK;
DROP INDEX ROLE_PERIMETRE_FK;
DROP INDEX TMS_TYPE_MODUL_FK;
DROP INDEX EFFECTIFS_HCFK;
DROP INDEX VOLUME_HORAIRE_HDFK;
DROP INDEX PIECE_JOINTE_FICHIER_FFK;
DROP INDEX CORPS_SOURCE_FK;
DROP INDEX PERIODE_HCFK;
DROP INDEX INDIC_DIFF_DOSSIER_INT_FK;
DROP INDEX TYPE_VOLUME_HORAIRE_HDFK;
DROP INDEX ADRESSE_STRUCTURE_HDFK;
DROP INDEX TYPE_HEURES_HMFK;
DROP INDEX MISE_EN_PAIEMENT_PERIODE_FK;
DROP INDEX DEPARTEMENT_SOURCE_FK;
DROP INDEX FRVHR_FORMULE_RESULTAT_FK;
DROP INDEX VALIDATION_STRUCTURE_FK;
DROP INDEX DOMAINE_FONCTIONNEL_HMFK;
DROP INDEX CENTRE_COUT_SOURCE_FK;
DROP INDEX FONCTION_REFERENTIEL_HMFK;
DROP INDEX TYPE_FORMATION_HDFK;
DROP INDEX TMS_ANNEE_DEBUT_FK;
DROP INDEX STRUCTURE_TYPE_STRUCTURE_FK;
DROP INDEX TIEP_ELEMENT_PEDAGOGIQUE_FK;
DROP INDEX ETAPE_SOURCE_FK;
DROP INDEX TIS_TYPE_INTERVENTION_FK;
DROP INDEX SERVICE_ELEMENT_PEDAGOGIQUE_FK;
DROP INDEX DISCIPLINE_HCFK;
DROP INDEX ADRESSE_STRUCTURE_STRUCTURE_FK;
DROP INDEX CORPS_HCFK;
DROP INDEX EP_DISCIPLINE_FK;
DROP INDEX ELEMENT_MODULATEUR_HMFK;
DROP INDEX TYPE_VALIDATION_HDFK;
DROP INDEX PAYS_HMFK;
DROP INDEX TMS_ANNEE_FIN_FK;
DROP INDEX ETR_ELEMENT_FK;
DROP INDEX ELEMENT_TAUX_REGIMES_HDFK;
DROP INDEX FRVH_FORMULE_RESULTAT_FK;
DROP INDEX STRUCTURE_HCFK;
DROP INDEX MODIFICATION_SERVICE_DU_HMFK;
DROP INDEX TYPE_RESSOURCE_HDFK;
DROP INDEX MEP_CENTRE_COUT_FK;
DROP INDEX VVH_VALIDATION_FK;
DROP INDEX SR_INTERVENANT_FK;
DROP INDEX SERVICE_REFERENTIEL_HCFK;
DROP INDEX FRS_SERVICE_FK;
DROP INDEX TYPE_FORMATION_GROUPE_FK;
DROP INDEX ADRESSE_INTERVENANT_HDFK;
DROP INDEX ROLE_HMFK;
DROP INDEX TYPE_INTERVENANT_HDFK;
DROP INDEX TYPE_VALIDATION_HMFK;
DROP INDEX TYPE_PIECE_JOINTE_STATUT_HDFK;
DROP INDEX DOTATION_HDFK;
DROP INDEX FONCTION_REFERENTIEL_HCFK;
DROP INDEX ELEMENT_PEDAGOGIQUE_PERIODE_FK;
DROP INDEX TYPE_AGREMENT_HCFK;
DROP INDEX FRSR_SERVICE_REFERENTIEL_FK;
DROP INDEX TYPE_INTERVENTION_HCFK;
DROP INDEX VALIDATION_INTERVENANT_FK;
DROP INDEX VOLUME_HORAIRE_ENS_HCFK;
DROP INDEX ELEMENT_MODULATEUR_HDFK;
DROP INDEX VHR_TYPE_VOLUME_HORAIRE_FK;
DROP INDEX VOLUME_HORAIRE_ENS_SOURCE_FK;
DROP INDEX HSM_TYPE_VOLUME_HORAIRE_FK;
DROP INDEX MOTIF_NON_PAIEMENT_HDFK;
DROP INDEX CONTRAT_TYPE_CONTRAT_FK;
DROP INDEX ETABLISSEMENT_HDFK;
DROP INDEX DOMAINE_FONCTIONNEL_SOURCE_FK;
DROP INDEX VHR_SERVICE_REFERENTIEL_FK;
DROP INDEX EFFECTIFS_SOURCE_FK;
DROP INDEX TPJS_STATUT_INTERVENANT_FK;
DROP INDEX CENTRE_COUT_CENTRE_COUT_FK;
DROP INDEX INTERVENANT_HDFK;
DROP INDEX PARAMETRE_HMFK;
DROP INDEX AGREMENT_TYPE_AGREMENT_FK;
DROP INDEX STRUCTURE_STRUCTURE_FK;
DROP INDEX AFFECTATION_R_HMFK;
DROP INDEX ETABLISSEMENT_HMFK;
DROP INDEX ROLE_HDFK;
DROP INDEX WF_INTERVENANT_ETAPE_EFK;
DROP INDEX TYPE_HEURES_HCFK;
DROP INDEX MISE_EN_PAIEMENT_HDFK;
DROP INDEX GROUPE_HMFK;
DROP INDEX MOTIF_MODIFICATION_SERVIC_HCFK;
DROP INDEX AII_FK;
DROP INDEX DOTATION_HCFK;
DROP INDEX INTERVENANTS_CIVILITES_FK;
DROP INDEX GROUPE_TYPE_INTERVENTION_FK;
DROP INDEX TAS_TYPE_AGREMENT_FK;
DROP INDEX FRVHR_VOLUME_HORAIRE_REF_FK;
DROP INDEX VH_TYPE_VOLUME_HORAIRE_FK;
DROP INDEX INTERVENANT_ANNEE_FK;
DROP INDEX MODULATEUR_HCFK;
DROP INDEX INTERVENANT_SOURCE_FK;
DROP INDEX INTERVENANT_DISCIPLINE_FK;
DROP INDEX TYPE_DOTATION_HMFK;
DROP INDEX ELEMENT_TAUX_REGIMES_HMFK;
DROP INDEX CONTRAT_STRUCTURE_FK;
DROP INDEX STRUCTURE_HMFK;
DROP INDEX CCEP_SOURCE_FK;
DROP INDEX VOLUME_HORAIRE_HMFK;
DROP INDEX MEP_TYPE_HEURES_FK;
DROP INDEX STAT_PRIV_PRIVILEGE_FK;
DROP INDEX PIECE_JOINTE_VFK;
DROP INDEX SERVICE_HMFK;
DROP INDEX TIS_ANNEE_FIN_FK;
DROP INDEX VOLUME_HORAIRE_ENS_HMFK;
DROP INDEX CONTRAT_HCFK;
DROP INDEX TAUX_HORAIRE_HETD_HMFK;
DROP INDEX TYPE_MODULATEUR_STRUCTURE_HCFK;
DROP INDEX VVHR_VOLUME_HORAIRE_REF_FK;
DROP INDEX MOTIF_MODIFICATION_SERVIC_HDFK;
DROP INDEX TMS_STRUCTURE_FK;
DROP INDEX MODIFICATION_SERVICE_DU_HDFK;
DROP INDEX CORPS_HDFK;
DROP INDEX TPJS_TYPE_PIECE_JOINTE_FK;
DROP INDEX EM_MODULATEUR_FK;
DROP INDEX TME_SOURCE_FK;
DROP INDEX FONCTION_REFERENTIEL_SFK;


---------------------------
--Modifié TABLE
--TYPE_RESSOURCE
---------------------------
ALTER TABLE "OSE"."TYPE_RESSOURCE" ADD ("ETABLISSEMENT" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE);

---------------------------
--Modifié TABLE
--TYPE_AGREMENT_STATUT
---------------------------
ALTER TABLE "OSE"."TYPE_AGREMENT_STATUT" DROP ("SEUIL_HETD");
ALTER TABLE "OSE"."TYPE_AGREMENT_STATUT" MODIFY ("PREMIER_RECRUTEMENT" NUMBER(1,0) DEFAULT NULL);
ALTER TABLE "OSE"."TYPE_AGREMENT_STATUT" MODIFY ("PREMIER_RECRUTEMENT" NULL);

---------------------------
--Modifié TABLE
--STRUCTURE
---------------------------
ALTER TABLE "OSE"."STRUCTURE" DROP ("UNITE_BUDGETAIRE");

---------------------------
--Modifié TABLE
--DOTATION
---------------------------
ALTER TABLE "OSE"."DOTATION" ADD ("ANNEE_CIVILE" NUMBER(*,0) NOT NULL ENABLE);
ALTER TABLE "OSE"."DOTATION" ADD ("LIBELLE" VARCHAR2(100 CHAR) NOT NULL ENABLE);
ALTER TABLE "OSE"."DOTATION" ADD ("TYPE_RESSOURCE_ID" NUMBER(*,0) CONSTRAINT "NNC_DOTATION_TYPE_ID" NOT NULL ENABLE);
ALTER TABLE "OSE"."DOTATION" DROP ("DATE_EFFET");
ALTER TABLE "OSE"."DOTATION" DROP ("TYPE_ID");
ALTER TABLE "OSE"."DOTATION" DROP CONSTRAINT "DOTATION_TYPE_DOTATION_FK";
ALTER TABLE "OSE"."DOTATION" ADD CONSTRAINT "DOTATION_TYPE_RESSOURCE_FK" FOREIGN KEY ("TYPE_RESSOURCE_ID") REFERENCES "OSE"."TYPE_RESSOURCE"("ID") ENABLE;
ALTER TABLE "OSE"."DOTATION" ADD CONSTRAINT "DOTATION__UN" UNIQUE ("TYPE_RESSOURCE_ID","ANNEE_ID","ANNEE_CIVILE","STRUCTURE_ID","LIBELLE","HISTO_DESTRUCTION") ENABLE;

---------------------------
--Nouveau TABLE
--CENTRE_COUT_STRUCTURE
---------------------------
  CREATE TABLE "OSE"."CENTRE_COUT_STRUCTURE" 
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"CENTRE_COUT_ID" NUMBER(*,0) NOT NULL ENABLE,
	"STRUCTURE_ID" NUMBER(*,0) NOT NULL ENABLE,
	"SOURCE_ID" NUMBER(*,0) NOT NULL ENABLE,
	"SOURCE_CODE" VARCHAR2(100 CHAR),
	"HISTO_CREATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
	"HISTO_CREATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
	"HISTO_DESTRUCTION" DATE,
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0),
	"UNITE_BUDGETAIRE" VARCHAR2(15 CHAR),
	CONSTRAINT "CENTRE_COUT_STRUCTURE_PK" PRIMARY KEY ("ID") ENABLE,
	CONSTRAINT "CCS_CC_S__UN" UNIQUE ("CENTRE_COUT_ID","STRUCTURE_ID","HISTO_DESTRUCTION") ENABLE,
	CONSTRAINT "CCS_SOURCE_CODE_UN" UNIQUE ("SOURCE_CODE","HISTO_DESTRUCTION") ENABLE,
	CONSTRAINT "CCS_CENTRE_COUT_FK" FOREIGN KEY ("CENTRE_COUT_ID")
	 REFERENCES "OSE"."CENTRE_COUT" ("ID") ON DELETE CASCADE ENABLE,
	CONSTRAINT "CCS_STRUCTURE_FK" FOREIGN KEY ("STRUCTURE_ID")
	 REFERENCES "OSE"."STRUCTURE" ("ID") ON DELETE CASCADE ENABLE,
	CONSTRAINT "CENTRE_COUT_STRUCTURE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE,
	CONSTRAINT "CENTRE_COUT_STRUCTURE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE,
	CONSTRAINT "CENTRE_COUT_STRUCTURE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE
   );
---------------------------
--Modifié TABLE
--CENTRE_COUT
---------------------------
ALTER TABLE "OSE"."CENTRE_COUT" ADD ("UNITE_BUDGETAIRE" VARCHAR2(15 CHAR));
ALTER TABLE "OSE"."CENTRE_COUT" DROP ("STRUCTURE_ID");
ALTER TABLE "OSE"."CENTRE_COUT" DROP CONSTRAINT "CENTRE_COUT_STRUCTURE_FK";

---------------------------
--Modifié TABLE
--AGREMENT
---------------------------
ALTER TABLE "OSE"."AGREMENT" DROP CONSTRAINT "AGREMENT__UN";
ALTER TABLE "OSE"."AGREMENT" ADD CONSTRAINT "AGREMENT__UN" UNIQUE ("TYPE_AGREMENT_ID","INTERVENANT_ID","STRUCTURE_ID","HISTO_DESTRUCTION") ENABLE;

---------------------------
--Modifié VIEW
--V_TBL_SERVICE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_SERVICE" 
 ( "ID", "SERVICE_ID", "INTERVENANT_ID", "TYPE_INTERVENANT_ID", "ANNEE_ID", "TYPE_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID", "ETABLISSEMENT_ID", "STRUCTURE_AFF_ID", "STRUCTURE_ENS_ID", "NIVEAU_FORMATION_ID", "ETAPE_ID", "ELEMENT_PEDAGOGIQUE_ID", "PERIODE_ID", "TYPE_INTERVENTION_ID", "FONCTION_REFERENTIEL_ID", "TYPE_ETAT", "INTERVENANT_CODE", "INTERVENANT_NOM", "INTERVENANT_DATE_NAISSANCE", "INTERVENANT_STATUT_LIBELLE", "INTERVENANT_TYPE_CODE", "INTERVENANT_TYPE_LIBELLE", "INTERVENANT_GRADE_CODE", "INTERVENANT_GRADE_LIBELLE", "INTERVENANT_DISCIPLINE_CODE", "INTERVENANT_DISCIPLINE_LIBELLE", "SERVICE_STRUCTURE_AFF_LIBELLE", "SERVICE_STRUCTURE_ENS_LIBELLE", "ETABLISSEMENT_LIBELLE", "GROUPE_TYPE_FORMATION_LIBELLE", "TYPE_FORMATION_LIBELLE", "ETAPE_NIVEAU", "ETAPE_CODE", "ETAPE_LIBELLE", "ELEMENT_CODE", "ELEMENT_LIBELLE", "ELEMENT_DISCIPLINE_CODE", "ELEMENT_DISCIPLINE_LIBELLE", "FONCTION_REFERENTIEL_LIBELLE", "ELEMENT_TAUX_FI", "ELEMENT_TAUX_FC", "ELEMENT_TAUX_FA", "COMMENTAIRES", "PERIODE_LIBELLE", "ELEMENT_PONDERATION_COMPL", "ELEMENT_SOURCE_LIBELLE", "HEURES", "HEURES_REF", "HEURES_NON_PAYEES", "SERVICE_STATUTAIRE", "SERVICE_DU_MODIFIE", "SERVICE_FI", "SERVICE_FA", "SERVICE_FC", "SERVICE_REFERENTIEL", "HEURES_COMPL_FI", "HEURES_COMPL_FA", "HEURES_COMPL_FC", "HEURES_COMPL_FC_MAJOREES", "HEURES_COMPL_REFERENTIEL", "TOTAL", "SOLDE", "DATE_CLOTURE_REALISE"
  )  AS 
  WITH t AS ( SELECT
  'vh_' || vh.id                    id,
  s.id                              service_id,
  s.intervenant_id                  intervenant_id,
  vh.type_volume_horaire_id         type_volume_horaire_id,
  fr.etat_volume_horaire_id         etat_volume_horaire_id,
  s.element_pedagogique_id          element_pedagogique_id,
  s.etablissement_id                etablissement_id,
  null                              structure_aff_id,
  null                              structure_ens_id,
  vh.periode_id                     periode_id,
  vh.type_intervention_id           type_intervention_id,
  null                              fonction_referentiel_id,
  
  s.description                     service_description,
  
  vh.heures                         heures,
  0                                 heures_ref,
  0                                 heures_non_payees,
  frvh.service_fi                   service_fi,
  frvh.service_fa                   service_fa,
  frvh.service_fc                   service_fc,
  0                                 service_referentiel,
  frvh.heures_compl_fi              heures_compl_fi,
  frvh.heures_compl_fa              heures_compl_fa,
  frvh.heures_compl_fc              heures_compl_fc,
  frvh.heures_compl_fc_majorees     heures_compl_fc_majorees,
  0                                 heures_compl_referentiel,
  frvh.total                        total,
  fr.solde                          solde,
  null                              commentaires
FROM
  formule_resultat_vh                frvh
  JOIN formule_resultat                fr ON fr.id = frvh.formule_resultat_id
  JOIN volume_horaire                  vh ON vh.id = frvh.volume_horaire_id AND vh.motif_non_paiement_id IS NULL AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
  JOIN service                          s ON s.id = vh.service_id AND s.intervenant_id = fr.intervenant_id AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )

UNION ALL

SELECT
  'vh_' || vh.id                    id,
  s.id                              service_id,
  s.intervenant_id                  intervenant_id,
  vh.type_volume_horaire_id         type_volume_horaire_id,
  vhe.etat_volume_horaire_id        etat_volume_horaire_id,
  s.element_pedagogique_id          element_pedagogique_id,
  s.etablissement_id                etablissement_id,
  null                              structure_aff_id,
  null                              structure_ens_id,
  vh.periode_id                     periode_id,
  vh.type_intervention_id           type_intervention_id,
  null                              fonction_referentiel_id,
  
  s.description                     service_description,
  
  vh.heures                         heures,
  0                                 heures_ref,
  1                                 heures_non_payees,
  0                                 service_fi,
  0                                 service_fa,
  0                                 service_fc,
  0                                 service_referentiel,
  0                                 heures_compl_fi,
  0                                 heures_compl_fa,
  0                                 heures_compl_fc,
  0                                 heures_compl_fc_majorees,
  0                                 heures_compl_referentiel,
  0                                 total,
  fr.solde                          solde,
  null                              commentaires 
FROM
  volume_horaire                  vh
  JOIN service                     s ON s.id = vh.service_id
  JOIN v_volume_horaire_etat     vhe ON vhe.volume_horaire_id = vh.id
  JOIN formule_resultat           fr ON fr.intervenant_id = s.intervenant_id AND fr.type_volume_horaire_id = vh.type_volume_horaire_id AND fr.etat_volume_horaire_id = vhe.etat_volume_horaire_id
WHERE
  vh.motif_non_paiement_id IS NOT NULL
  AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
  AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )

UNION ALL

SELECT
  'vh_ref_' || vhr.id               id,
  sr.id                             service_id,
  sr.intervenant_id                 intervenant_id,
  fr.type_volume_horaire_id         type_volume_horaire_id,
  fr.etat_volume_horaire_id         etat_volume_horaire_id,
  NULL                              element_pedagogique_id,
  OSE_PARAMETRE.GET_ETABLISSEMENT   etablissement_id,
  NULL                              structure_aff_id,
  sr.structure_id                   structure_ens_id,
  NULL                              periode_id,
  NULL                              type_intervention_id,
  sr.fonction_id                    fonction_referentiel_id,
  
  NULL                              service_description,
  
  0                                 heures,
  vhr.heures                        heures_ref,
  0                                 heures_non_payees,
  0                                 service_fi,
  0                                 service_fa,
  0                                 service_fc,
  frvr.service_referentiel          service_referentiel,
  0                                 heures_compl_fi,
  0                                 heures_compl_fa,
  0                                 heures_compl_fc,
  0                                 heures_compl_fc_majorees,
  frvr.heures_compl_referentiel     heures_compl_referentiel,
  frvr.total                        total,
  fr.solde                          solde,
  sr.commentaires                   commentaires
FROM
  formule_resultat_vh_ref       frvr
  JOIN formule_resultat           fr ON fr.id = frvr.formule_resultat_id
  JOIN volume_horaire_ref        vhr ON vhr.id =  frvr.volume_horaire_ref_id
  JOIN service_referentiel        sr ON sr.id = vhr.service_referentiel_id AND sr.intervenant_id = fr.intervenant_id AND 1 = ose_divers.comprise_entre( sr.histo_creation, sr.histo_destruction )
  
UNION ALL

SELECT
  'vh_0_' || i.id                   id,
  NULL                              service_id,
  i.id                              intervenant_id,
  tvh.id                            type_volume_horaire_id,
  evh.id                            etat_volume_horaire_id,
  NULL                              element_pedagogique_id,
  OSE_PARAMETRE.GET_ETABLISSEMENT   etablissement_id,
  NULL                              structure_aff_id,
  NULL                              structure_ens_id,
  NULL                              periode_id,
  NULL                              type_intervention_id,
  NULL                              fonction_referentiel_id,
  
  NULL                              service_description,
  
  0                                 heures,
  0                                 heures_ref,
  0                                 heures_non_payees,
  0                                 service_fi,
  0                                 service_fa,
  0                                 service_fc,
  0                                 service_referentiel,
  0                                 heures_compl_fi,
  0                                 heures_compl_fa,
  0                                 heures_compl_fc,
  0                                 heures_compl_fc_majorees,
  NULL                              heures_compl_referentiel,
  0                                 total,
  0                                 solde,
  NULL                              commentaires
FROM
  intervenant i
  JOIN statut_intervenant si ON si.id = i.statut_id
  JOIN etat_volume_horaire evh ON evh.code IN ('saisi','valide')
  JOIN type_volume_horaire tvh ON tvh.code IN ('PREVU','REALISE')
  LEFT JOIN modification_service_du msd ON msd.intervenant_id = i.id AND 1 = ose_divers.comprise_entre( msd.histo_creation, msd.histo_destruction )
  LEFT JOIN motif_modification_service mms ON mms.id = msd.motif_id
WHERE
  1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
  AND si.service_statutaire > 0
GROUP BY
  i.id, si.service_statutaire, evh.id, tvh.id
HAVING 
  si.service_statutaire + SUM(msd.heures * mms.multiplicateur) = 0


)
SELECT
  t.id                            id,
  t.service_id                    service_id,
  i.id                            intervenant_id,
  ti.id                           type_intervenant_id,  
  i.annee_id                      annee_id,
  t.type_volume_horaire_id        type_volume_horaire_id,
  t.etat_volume_horaire_id        etat_volume_horaire_id,
  etab.id                         etablissement_id,
  saff.id                         structure_aff_id,
  sens.id                         structure_ens_id,
  ose_divers.niveau_formation_id_calc( gtf.id, gtf.pertinence_niveau, etp.niveau ) niveau_formation_id,
  etp.id                          etape_id,
  ep.id                           element_pedagogique_id,
  t.periode_id                    periode_id,
  t.type_intervention_id          type_intervention_id,
  t.fonction_referentiel_id       fonction_referentiel_id,
  
  tvh.libelle || ' ' || evh.libelle type_etat,
  i.source_code                   intervenant_code,
  i.nom_usuel || ' ' || i.prenom  intervenant_nom,
  i.date_naissance                intervenant_date_naissance,
  si.libelle                      intervenant_statut_libelle,
  ti.code                         intervenant_type_code,
  ti.libelle                      intervenant_type_libelle,
  g.source_code                   intervenant_grade_code,
  g.libelle_court                 intervenant_grade_libelle,
  di.source_code                  intervenant_discipline_code,
  di.libelle_court                intervenant_discipline_libelle,
  saff.libelle_court              service_structure_aff_libelle,

  sens.libelle_court              service_structure_ens_libelle,
  etab.libelle                    etablissement_libelle,
  gtf.libelle_court               groupe_type_formation_libelle,
  tf.libelle_court                type_formation_libelle,
  etp.niveau                      etape_niveau,
  etp.source_code                 etape_code,
  etp.libelle                     etape_libelle,
  ep.source_code                  element_code,
  COALESCE(ep.libelle,to_char(t.service_description)) element_libelle,
  de.source_code                  element_discipline_code,
  de.libelle_court                element_discipline_libelle,
  fr.libelle_long                 fonction_referentiel_libelle,
  ep.taux_fi                      element_taux_fi,
  ep.taux_fc                      element_taux_fc,
  ep.taux_fa                      element_taux_fa,
  null                            commentaires,
  p.libelle_court                 periode_libelle,
  CASE WHEN fs.ponderation_service_compl = 1 THEN NULL ELSE fs.ponderation_service_compl END element_ponderation_compl,
  src.libelle                     element_source_libelle,
  
  t.heures                        heures,
  t.heures_ref                    heures_ref,
  t.heures_non_payees             heures_non_payees,
  si.service_statutaire           service_statutaire,
  fsm.heures                      service_du_modifie,
  t.service_fi                    service_fi,
  t.service_fa                    service_fa,
  t.service_fc                    service_fc,
  t.service_referentiel           service_referentiel,
  t.heures_compl_fi               heures_compl_fi,
  t.heures_compl_fa               heures_compl_fa,
  t.heures_compl_fc               heures_compl_fc,
  t.heures_compl_fc_majorees      heures_compl_fc_majorees,
  t.heures_compl_referentiel      heures_compl_referentiel,
  t.total                         total,
  t.solde                         solde,
  v.histo_modification            date_cloture_realise

FROM
  t
  JOIN intervenant                        i ON i.id     = t.intervenant_id AND ose_divers.comprise_entre(  i.histo_creation,  i.histo_destruction ) = 1
  JOIN statut_intervenant                si ON si.id    = i.statut_id            
  JOIN type_intervenant                  ti ON ti.id    = si.type_intervenant_id 
  JOIN etablissement                   etab ON etab.id  = t.etablissement_id
  JOIN type_volume_horaire              tvh ON tvh.id   = t.type_volume_horaire_id
  JOIN etat_volume_horaire              evh ON evh.id   = t.etat_volume_horaire_id
  LEFT JOIN grade                         g ON g.id     = i.grade_id
  LEFT JOIN discipline                   di ON di.id    = i.discipline_id
  LEFT JOIN structure                  saff ON saff.id  = i.structure_id AND ti.code = 'P'
  LEFT JOIN element_pedagogique          ep ON ep.id    = t.element_pedagogique_id
  LEFT JOIN discipline                   de ON de.id    = ep.discipline_id
  LEFT JOIN structure                  sens ON sens.id  = NVL(t.structure_ens_id, ep.structure_id)
  LEFT JOIN periode                       p ON p.id     = t.periode_id
  LEFT JOIN source                      src ON src.id   = ep.source_id OR (ep.source_id IS NULL AND src.code = 'OSE')
  LEFT JOIN etape                       etp ON etp.id   = ep.etape_id
  LEFT JOIN type_formation               tf ON tf.id    = etp.type_formation_id AND ose_divers.comprise_entre( tf.histo_creation, tf.histo_destruction ) = 1
  LEFT JOIN groupe_type_formation       gtf ON gtf.id   = tf.groupe_id AND ose_divers.comprise_entre( gtf.histo_creation, gtf.histo_destruction ) = 1
  LEFT JOIN v_formule_service_modifie   fsm ON fsm.intervenant_id = i.id
  LEFT JOIN v_formule_service            fs ON fs.id    = t.service_id
  LEFT JOIN fonction_referentiel         fr ON fr.id    = t.fonction_referentiel_id
  LEFT JOIN type_validation              tv ON tvh.code = 'REALISE' AND tv.code = 'CLOTURE_REALISE'
  LEFT JOIN validation                    v ON v.intervenant_id = i.id AND v.type_validation_id = tv.id AND 1 = ose_divers.comprise_entre( v.histo_creation, v.histo_destruction );
---------------------------
--Nouveau VIEW
--V_TBL_PILOTAGE_ECARTS_ETATS
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_PILOTAGE_ECARTS_ETATS" 
 ( "ANNEE_ID", "ANNEE", "ETAT", "TYPE_HEURES_ID", "TYPE_HEURES", "STRUCTURE_ID", "STRUCTURE", "INTERVENANT_ID", "INTERVENANT_TYPE", "INTERVENANT_CODE", "INTERVENANT", "HETD_PAYABLES"
  )  AS 
  SELECT 
  t3.annee_id annee_id,
  t3.annee_id || '-' || (t3.annee_id+1) annee,
  t3.etat,
  t3.type_heures_id,
  t3.type_heures,
  s.id structure_id,
  s.libelle_court structure,
  i.id intervenant_id,
  ti.libelle intervenant_type,
  i.source_code intervenant_code,
  i.prenom || ' ' || i.nom_usuel intervenant,
  t3.hetd_payables
FROM

(
SELECT
  annee_id,
  etat,
  type_heures_id,
  type_heures,
  structure_id,
  intervenant_id,
  sum(hetd) hetd_payables
FROM (
  SELECT
    annee_id,
    LOWER(tvh.code) || '-' || evh.code etat,
    10*tvh.ordre + evh.ordre ordre,
    type_heures_id,
    type_heures,
    structure_id,
    intervenant_id,
    SUM(hetd) hetd
  FROM (
    SELECT
      i.annee_id,
      fr.type_volume_horaire_id,
      fr.etat_volume_horaire_id,
      th.id type_heures_id,
      th.code type_heures,
      COALESCE(ep.structure_id,i.structure_id) structure_id,
      fr.intervenant_id,
      SUM(frs.heures_compl_fi) hetd
    FROM
           formule_resultat_service  frs
      JOIN formule_resultat           fr ON fr.id = frs.formule_resultat_id
      JOIN service                     s ON s.id = frs.service_id
      JOIN intervenant                 i ON i.id = fr.intervenant_id
      JOIN type_heures                th ON th.code = 'fi'
      LEFT JOIN element_pedagogique   ep ON ep.id = s.element_pedagogique_id
    GROUP BY
      i.annee_id,
      fr.type_volume_horaire_id,
      fr.etat_volume_horaire_id,
      th.id, th.code,
      fr.intervenant_id,
      ep.structure_id,
      i.structure_id
    
    UNION ALL
    
    SELECT
      i.annee_id,
      fr.type_volume_horaire_id,
      fr.etat_volume_horaire_id,
      th.id type_heures_id,
      th.code type_heures,
      COALESCE(ep.structure_id,i.structure_id) structure_id,
      fr.intervenant_id,
      SUM(frs.heures_compl_fa) hetd
    FROM
           formule_resultat_service  frs
      JOIN formule_resultat           fr ON fr.id = frs.formule_resultat_id
      JOIN service                     s ON s.id = frs.service_id
      JOIN intervenant                 i ON i.id = fr.intervenant_id
      JOIN type_heures                th ON th.code = 'fa'
      LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
    GROUP BY
      i.annee_id,
      fr.type_volume_horaire_id,
      fr.etat_volume_horaire_id,
      th.id, th.code,
      fr.intervenant_id,
      ep.structure_id,
      i.structure_id
      
    UNION ALL
      
    SELECT
      i.annee_id,
      fr.type_volume_horaire_id,
      fr.etat_volume_horaire_id,
      th.id type_heures_id,
      th.code type_heures,
      COALESCE(ep.structure_id,i.structure_id) structure_id,
      fr.intervenant_id,
      SUM(frs.heures_compl_fc) hetd
    FROM
           formule_resultat_service  frs
      JOIN formule_resultat           fr ON fr.id = frs.formule_resultat_id
      JOIN service                     s ON s.id = frs.service_id
      JOIN intervenant                 i ON i.id = fr.intervenant_id
      JOIN type_heures                th ON th.code = 'fc'
      LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
    GROUP BY
      i.annee_id,
      fr.type_volume_horaire_id,
      fr.etat_volume_horaire_id,
      th.id, th.code,
      fr.intervenant_id,
      ep.structure_id,
      i.structure_id
    
    UNION ALL
    
    SELECT
      i.annee_id,
      fr.type_volume_horaire_id,
      fr.etat_volume_horaire_id,
      th.id type_heures_id,
      th.code type_heures,
      sr.structure_id,
      fr.intervenant_id,
      sum( frsr.heures_compl_referentiel ) hetd
    FROM
           formule_resultat_service_ref  frsr
      JOIN formule_resultat                fr ON fr.id = frsr.formule_resultat_id
      JOIN service_referentiel             sr ON sr.id = frsr.service_referentiel_id
      JOIN intervenant                      i ON i.id = fr.intervenant_id
      JOIN type_heures                th ON th.code = 'referentiel'
    GROUP BY
      i.annee_id,
      fr.type_volume_horaire_id,
      fr.etat_volume_horaire_id,
      th.id, th.code,
      fr.intervenant_id,
      sr.structure_id
  ) t1
    JOIN type_volume_horaire            tvh ON tvh.id = t1.type_volume_horaire_id
    JOIN etat_volume_horaire            evh ON evh.id = t1.etat_volume_horaire_id
  GROUP BY
    annee_id, tvh.code, evh.code, tvh.ordre, evh.ordre, type_heures_id, type_heures, structure_id, intervenant_id
  
  UNION ALL
  
  SELECT
    annee_id,
    etat,
    ordre,
    type_heures_id,
    type_heures,
    structure_id,
    intervenant_id,
    SUM(hetd) hetd
  FROM (
    SELECT
      i.annee_id,
      'demande-mise-en-paiement' etat,
      90 ordre, 
      th.id   type_heures_id,
      th.code type_heures,
      COALESCE( sr.structure_id, ep.structure_id, i.structure_id ) structure_id,
      i.id intervenant_id,
      mep.heures hetd
    FROM
                mise_en_paiement              mep 
           JOIN type_heures                    th ON th.id = mep.type_heures_id
           JOIN centre_cout                    cc ON cc.id = mep.centre_cout_id
      LEFT JOIN formule_resultat_service      frs ON frs.id = mep.formule_res_service_id
      LEFT JOIN formule_resultat_service_ref frsr ON frsr.id = mep.formule_res_service_ref_id
      LEFT JOIN formule_resultat               fr ON fr.id = COALESCE(frs.formule_resultat_id, frsr.formule_resultat_id)
      LEFT JOIN service                         s ON s.id = frs.service_id
      LEFT JOIN element_pedagogique            ep ON ep.id = s.element_pedagogique_id
      LEFT JOIN service_referentiel            sr ON sr.id = frsr.service_referentiel_id
      LEFT JOIN intervenant                     i ON i.id = fr.intervenant_id
    WHERE
      1 = ose_divers.comprise_entre(mep.histo_creation,mep.histo_destruction) 
      AND th.eligible_extraction_paie = 1

    UNION ALL

    SELECT
      i.annee_id,
      'mise-en-paiement' etat,
      91 ordre,
      th.id type_heures_id,
      th.code type_heures,
      COALESCE( sr.structure_id, ep.structure_id, i.structure_id ) structure_id,
      i.id intervenant_id,
      mep.heures hetd     
    FROM
                mise_en_paiement              mep 
           JOIN type_heures                    th ON th.id = mep.type_heures_id
           JOIN centre_cout                    cc ON cc.id = mep.centre_cout_id
      LEFT JOIN formule_resultat_service      frs ON frs.id = mep.formule_res_service_id
      LEFT JOIN formule_resultat_service_ref frsr ON frsr.id = mep.formule_res_service_ref_id
      LEFT JOIN formule_resultat               fr ON fr.id = COALESCE(frs.formule_resultat_id, frsr.formule_resultat_id)
      LEFT JOIN service                         s ON s.id = frs.service_id
      LEFT JOIN element_pedagogique            ep ON ep.id = s.element_pedagogique_id
      LEFT JOIN service_referentiel            sr ON sr.id = frsr.service_referentiel_id
      LEFT JOIN intervenant                     i ON i.id = fr.intervenant_id
    WHERE
      1 = ose_divers.comprise_entre(mep.histo_creation,mep.histo_destruction) 
      AND th.eligible_extraction_paie = 1
      AND mep.PERIODE_PAIEMENT_ID IS NOT NULL
  ) t1
  GROUP BY
    annee_id, etat, ordre, type_heures_id, type_heures, structure_id, intervenant_id
) t2
GROUP BY
  annee_id, 
  etat, ordre
  ,type_heures_id, type_heures
  ,structure_id
  ,intervenant_id
ORDER BY
  annee_id, ordre
  
) t3
  JOIN intervenant i ON i.id = t3.intervenant_id
  JOIN statut_intervenant si ON si.id = i.statut_id
  JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
  JOIN structure s ON s.id = t3.structure_id;
---------------------------
--Modifié VIEW
--V_TBL_DMEP
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_DMEP" 
 ( "INTERVENANT_ID", "TYPE_INTERVENANT_ID", "ANNEE_ID", "STRUCTURE_AFF_ID", "STRUCTURE_ENS_ID", "STRUCTURE_ID", "CENTRE_COUT_ID", "ELEMENT_PEDAGOGIQUE_ID", "ETAPE_ID", "TYPE_FORMATION_ID", "GROUPE_TYPE_FORMATION_ID", "STATUT_INTERVENANT_ID", "PERIODE_ID", "INTERVENANT_CODE", "INTERVENANT_NOM", "INTERVENANT_DATE_NAISSANCE", "INTERVENANT_STATUT_LIBELLE", "INTERVENANT_TYPE_CODE", "INTERVENANT_TYPE_LIBELLE", "INTERVENANT_GRADE_CODE", "INTERVENANT_GRADE_LIBELLE", "INTERVENANT_DISCIPLINE_CODE", "INTERVENANT_DISCIPLINE_LIBELLE", "SERVICE_STRUCTURE_AFF_LIBELLE", "SERVICE_STRUCTURE_ENS_LIBELLE", "ETABLISSEMENT_LIBELLE", "GROUPE_TYPE_FORMATION_LIBELLE", "TYPE_FORMATION_LIBELLE", "ETAPE_NIVEAU", "ETAPE_CODE", "ETAPE_LIBELLE", "ELEMENT_CODE", "ELEMENT_LIBELLE", "ELEMENT_DISCIPLINE_CODE", "ELEMENT_DISCIPLINE_LIBELLE", "FONCTION_REFERENTIEL_LIBELLE", "ELEMENT_TAUX_FI", "ELEMENT_TAUX_FC", "ELEMENT_TAUX_FA", "ELEMENT_SOURCE_LIBELLE", "COMMENTAIRES", "ETAT", "TYPE_RESSOURCE_LIBELLE", "CENTRE_COUTS_CODE", "CENTRE_COUTS_LIBELLE", "DOMAINE_FONCTIONNEL_CODE", "DOMAINE_FONCTIONNEL_LIBELLE", "PERIODE_LIBELLE", "DATE_MISE_EN_PAIEMENT", "HEURES_FI", "HEURES_FA", "HEURES_FC", "HEURES_FC_MAJOREES", "HEURES_REFERENTIEL"
  )  AS 
  WITH mep AS (
  SELECT
    frs.service_id,
    frsr.service_referentiel_id,
    mep.date_mise_en_paiement,
    mep.periode_paiement_id,
    mep.centre_cout_id,
    mep.domaine_fonctionnel_id,
  
    sum(case when th.code = 'fi' then mep.heures else 0 end) heures_fi,
    sum(case when th.code = 'fa' then mep.heures else 0 end) heures_fa,
    sum(case when th.code = 'fc' then mep.heures else 0 end) heures_fc,
    sum(case when th.code = 'fc_majorees' then mep.heures else 0 end) heures_fc_majorees,
    sum(case when th.code = 'referentiel' then mep.heures else 0 end) heures_referentiel
  FROM
              mise_en_paiement              mep
         JOIN type_heures                    th ON th.id   = mep.type_heures_id
    LEFT JOIN formule_resultat_service      frs ON frs.id  = mep.formule_res_service_id
    LEFT JOIN formule_resultat_service_ref frsr ON frsr.id = mep.formule_res_service_ref_id
  WHERE
    1 = ose_divers.comprise_entre( mep.histo_creation, mep.histo_destruction )
  GROUP BY
    frs.service_id,
    frsr.service_referentiel_id,
    mep.date_mise_en_paiement,
    mep.periode_paiement_id,
    mep.centre_cout_id,
    mep.domaine_fonctionnel_id
)
SELECT 
  i.id                            intervenant_id,
  ti.id                           type_intervenant_id,
  i.annee_id                      annee_id,
  saff.id                         structure_aff_id,
  sens.id                         structure_ens_id,
  NVL(sens.id,saff.id)            structure_id,
  cc.id                           centre_cout_id,
  ep.id                           element_pedagogique_id,
  etp.id                          etape_id,
  tf.id                           type_formation_id,
  gtf.id                          groupe_type_formation_id,
  si.id                           statut_intervenant_id,
  p.id                            periode_id,
    
  i.source_code                   intervenant_code,
  i.nom_usuel || ' ' || i.prenom  intervenant_nom,
  i.date_naissance                intervenant_date_naissance,
  si.libelle                      intervenant_statut_libelle,
  ti.code                         intervenant_type_code,
  ti.libelle                      intervenant_type_libelle,
  g.source_code                   intervenant_grade_code,
  g.libelle_court                 intervenant_grade_libelle,
  di.source_code                  intervenant_discipline_code,
  di.libelle_court                intervenant_discipline_libelle,
  saff.libelle_court              service_structure_aff_libelle,
  
  sens.libelle_court              service_structure_ens_libelle,
  etab.libelle                    etablissement_libelle,
  gtf.libelle_court               groupe_type_formation_libelle,
  tf.libelle_court                type_formation_libelle,
  etp.niveau                      etape_niveau,
  etp.source_code                 etape_code,
  etp.libelle                     etape_libelle,
  ep.source_code                  element_code,
  ep.libelle                      element_libelle,
  de.source_code                  element_discipline_code,
  de.libelle_court                element_discipline_libelle,
  fr.libelle_long                 fonction_referentiel_libelle,
  ep.taux_fi                      element_taux_fi,
  ep.taux_fc                      element_taux_fc,
  ep.taux_fa                      element_taux_fa,
  src.libelle                     element_source_libelle,
  COALESCE(to_char(s.description),to_char(sr.commentaires)) commentaires,
  
  CASE
    WHEN mep.date_mise_en_paiement IS NULL THEN 'a-mettre-en-paiement'
    ELSE 'mis-en-paiement'
  END                             etat,
  tr.libelle                      type_ressource_libelle,
  cc.source_code                  centre_couts_code,
  cc.libelle                      centre_couts_libelle,
  df.source_code                  domaine_fonctionnel_code,
  df.libelle                      domaine_fonctionnel_libelle,
  p.libelle_long                  periode_libelle,
  mep.date_mise_en_paiement       date_mise_en_paiement,
  mep.heures_fi                   heures_fi,
  mep.heures_fa                   heures_fa,
  mep.heures_fc                   heures_fc,
  mep.heures_fc_majorees          heures_fc_majorees,
  mep.heures_referentiel          heures_referentiel
FROM
              mep
         JOIN centre_cout               cc ON cc.id   = mep.centre_cout_id
         JOIN type_ressource            tr ON tr.id   = cc.type_ressource_id
    LEFT JOIN service                    s ON s.id    = mep.service_id
    LEFT JOIN element_pedagogique       ep ON ep.id   = s.element_pedagogique_id
    LEFT JOIN source                   src ON src.id  = ep.source_id OR (ep.source_id IS NULL AND src.code = 'OSE')
    LEFT JOIN discipline                de ON de.id   = ep.discipline_id
    LEFT JOIN etape                    etp ON etp.id  = ep.etape_id
    LEFT JOIN type_formation            tf ON tf.id   = etp.type_formation_id
    LEFT JOIN groupe_type_formation    gtf ON gtf.id  = tf.groupe_id
    LEFT JOIN service_referentiel       sr ON sr.id   = mep.service_referentiel_id
    LEFT JOIN fonction_referentiel      fr ON fr.id   = sr.fonction_id
         JOIN intervenant                i ON i.id    = NVL( s.intervenant_id, sr.intervenant_id )
         JOIN statut_intervenant        si ON si.id   = i.statut_id
         JOIN type_intervenant          ti ON ti.id   = si.type_intervenant_id
    LEFT JOIN grade                      g ON g.id    = i.grade_id
    LEFT JOIN discipline                di ON di.id   = i.discipline_id
    LEFT JOIN structure               saff ON saff.id = i.structure_id AND ti.code = 'P'
    LEFT JOIN structure               sens ON sens.id = NVL( ep.structure_id, sr.structure_id )
         JOIN etablissement           etab ON etab.id = NVL( s.etablissement_id, sens.etablissement_id )
    LEFT JOIN periode                    p ON p.id    = mep.periode_paiement_id
    LEFT JOIN domaine_fonctionnel       df ON df.id   = mep.domaine_fonctionnel_id
ORDER BY
  intervenant_nom,
  service_structure_aff_libelle, 
  service_structure_ens_libelle, 
  etape_libelle, 
  element_libelle;
---------------------------
--Nouveau VIEW
--V_TBL_AGREMENT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_AGREMENT" 
 ( "ID", "ANNEE_ID", "TYPE_AGREMENT_ID", "INTERVENANT_ID", "STRUCTURE_ID", "OBLIGATOIRE", "AGREMENT_ID", "ATTEIGNABLE"
  )  AS 
  WITH i_s AS (
  SELECT DISTINCT
    fr.intervenant_id,
    ep.structure_id
  FROM
    formule_resultat fr
    JOIN type_volume_horaire  tvh ON tvh.code = 'PREVU' AND tvh.id = fr.type_volume_horaire_id
    JOIN etat_volume_horaire  evh ON evh.code = 'valide' AND evh.id = fr.etat_volume_horaire_id

    JOIN formule_resultat_service frs ON frs.formule_resultat_id = fr.id
    JOIN service s ON s.id = frs.service_id
    JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
  WHERE
    frs.total > 0
)
SELECT
  rownum                  id,
  i.annee_id              annee_id,
  tas.type_agrement_id    type_agrement_id,
  i.id                    intervenant_id,
  null                    structure_id,
  tas.obligatoire         obligatoire,
  a.id                    agrement_id,
  NVL(wie.atteignable,0)  atteignable
FROM
  type_agrement                  ta
  JOIN type_agrement_statut      tas ON tas.type_agrement_id = ta.id
                                    AND 1 = ose_divers.comprise_entre( tas.histo_creation, tas.histo_destruction )
                               
  JOIN intervenant                 i ON 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction )
                                    AND NVL(i.premier_recrutement,0) = tas.premier_recrutement
                                    AND i.statut_id = tas.statut_intervenant_id
  
  LEFT JOIN wf_etape              we ON we.annee_id = i.annee_id
                                    AND we.code = ta.code

  LEFT JOIN wf_intervenant_etape wie ON wie.intervenant_id = i.id
                                    AND wie.etape_id = we.id
                                    AND wie.structure_id IS NULL
                          
  LEFT JOIN agrement               a ON a.type_agrement_id = ta.id 
                                    AND a.intervenant_id = i.id
                                    AND 1 = ose_divers.comprise_entre( a.histo_creation, a.histo_destruction )
WHERE
  ta.code = 'CONSEIL_ACADEMIQUE'

UNION ALL

SELECT
  rownum + 1000000000     id,
  i.annee_id              annee_id,
  tas.type_agrement_id    type_agrement_id,
  i.id                    intervenant_id,
  i_s.structure_id        structure_id,
  tas.obligatoire         obligatoire,
  a.id                    agrement_id,
  NVL(wie.atteignable, 0) atteignable
FROM
  type_agrement                   ta
  JOIN type_agrement_statut      tas ON tas.type_agrement_id = ta.id
                                    AND 1 = ose_divers.comprise_entre( tas.histo_creation, tas.histo_destruction )

  JOIN intervenant                 i ON 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction )
                                    AND i.statut_id = tas.statut_intervenant_id

  JOIN                           i_s ON i_s.intervenant_id = i.id

  LEFT JOIN wf_etape              we ON we.annee_id = i.annee_id
                                    AND we.code = ta.code
  
  LEFT JOIN wf_intervenant_etape wie ON wie.intervenant_id = i.id
                                    AND wie.etape_id = we.id
                                    AND wie.structure_id = i_s.structure_id

  LEFT JOIN agrement               a ON a.type_agrement_id = ta.id 
                                    AND a.intervenant_id = i.id
                                    AND a.structure_id = i_s.structure_id
                                    AND 1 = ose_divers.comprise_entre( a.histo_creation, a.histo_destruction )
WHERE
  ta.code = 'CONSEIL_RESTREINT';
---------------------------
--Modifié VIEW
--V_MEP_INTERVENANT_STRUCTURE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_MEP_INTERVENANT_STRUCTURE" 
 ( "ID", "MISE_EN_PAIEMENT_ID", "INTERVENANT_ID", "STRUCTURE_ID", "PERIODE_PAIEMENT_ID", "DOMAINE_FONCTIONNEL_ID"
  )  AS 
  SELECT
  rownum id, 
  t1."MISE_EN_PAIEMENT_ID",
  t1."INTERVENANT_ID",
  t1."STRUCTURE_ID", 
  t1.periode_paiement_id, 
  t1.domaine_fonctionnel_id
FROM (

SELECT
  mep.id                   mise_en_paiement_id,
  fr.intervenant_id        intervenant_id,
  sr.structure_id          structure_id,
  mep.periode_paiement_id  periode_paiement_id,
  COALESCE(mep.domaine_fonctionnel_id, fr.domaine_fonctionnel_id) domaine_fonctionnel_id
FROM
  formule_resultat fr
  JOIN formule_resultat_service_ref frsr ON frsr.formule_resultat_id = fr.id
  JOIN mise_en_paiement              mep ON mep.formule_res_service_ref_id = frsr.id
  JOIN centre_cout                    cc ON cc.id = mep.centre_cout_id
  JOIN service_referentiel            sr ON sr.id = frsr.service_referentiel_id
  JOIN fonction_referentiel           fr ON fr.id = sr.fonction_id
UNION

SELECT
  mep.id                                      mise_en_paiement_id,
  fr.intervenant_id                           intervenant_id,
  COALESCE( ep.structure_id, i.structure_id ) structure_id,
  mep.periode_paiement_id                     periode_paiement_id,
  COALESCE(
    mep.domaine_fonctionnel_id, 
    e.domaine_fonctionnel_id, 
    to_number((SELECT valeur FROM parametre WHERE nom = 'domaine_fonctionnel_ens_ext'))
  ) domaine_fonctionnel_id
FROM
  formule_resultat fr
  JOIN intervenant                       i ON i.id = fr.intervenant_id
  JOIN formule_resultat_service        frs ON frs.formule_resultat_id = fr.id
  JOIN mise_en_paiement                mep ON mep.formule_res_service_id = frs.id
  JOIN centre_cout                      cc ON cc.id = mep.centre_cout_id
  JOIN service                           s ON s.id = frs.service_id
  LEFT JOIN element_pedagogique         ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN etape                        e ON e.id = ep.etape_id
) t1;
---------------------------
--Modifié VIEW
--V_INDICATEUR_210
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_INDICATEUR_210" 
 ( "INTERVENANT_ID", "STRUCTURE_ID", "TYPE_AGREMENT_ID"
  )  AS 
  SELECT
 -- rownum id,
  tbl.intervenant_id,
  tbl.structure_id,
  tbl.type_agrement_id
FROM 
  v_tbl_agrement tbl
  JOIN type_agrement ta ON ta.id = tbl.type_agrement_id
WHERE
  ta.code = 'CONSEIL_RESTREINT';
---------------------------
--Nouveau VIEW
--V_HETD_PREV_VAL_STRUCT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_HETD_PREV_VAL_STRUCT" 
 ( "ANNEE_ID", "STRUCTURE_ID", "HEURES"
  )  AS 
  SELECT
  annee_id,
  structure_id,
  sum(heures) heures

FROM 
(
SELECT
  i.annee_id,
  NVL( ep.structure_id, i.structure_id ) structure_id,
  frs.total heures
FROM
  formule_resultat_service frs
  JOIN formule_resultat fr ON fr.id = frs.formule_resultat_id
  JOIN type_volume_horaire tvh ON tvh.id = fr.type_volume_horaire_id
  JOIN etat_volume_horaire evh ON evh.id = fr.etat_volume_horaire_id
  JOIN intervenant i ON i.id = fr.intervenant_id
  JOIN service s ON s.id = frs.service_id
  LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id

WHERE
  tvh.code = 'PREVU'
  AND evh.code = 'valide'
) t1

GROUP BY
  annee_id, structure_id;
---------------------------
--Modifié VIEW
--V_FR_SERVICE_REF_CENTRE_COUT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_FR_SERVICE_REF_CENTRE_COUT" 
 ( "FORMULE_RESULTAT_SERV_REF_ID", "CENTRE_COUT_ID"
  )  AS 
  SELECT
  frsr.id formule_resultat_serv_ref_id, cc.id
FROM
  formule_resultat_service_ref   frsr
  JOIN service_referentiel    sr ON sr.id = frsr.service_referentiel_id
  JOIN centre_cout            cc ON 1 = ose_divers.comprise_entre( cc.histo_creation, cc.histo_destruction )
                                
  JOIN centre_cout_structure ccs ON ccs.centre_cout_id = cc.id 
                                AND ccs.structure_id = sr.structure_id 
                                AND 1 = ose_divers.comprise_entre( ccs.histo_creation, ccs.histo_destruction )
                                
  JOIN cc_activite             a ON a.id = cc.activite_id 
                                AND 1 = ose_divers.comprise_entre( a.histo_creation, a.histo_destruction )
                                
  JOIN type_ressource         tr ON tr.id = cc.type_ressource_id 
                                AND 1 = ose_divers.comprise_entre( tr.histo_creation, tr.histo_destruction )
WHERE
  frsr.heures_compl_referentiel > 0 AND tr.referentiel = 1;
---------------------------
--Modifié VIEW
--V_FR_SERVICE_CENTRE_COUT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_FR_SERVICE_CENTRE_COUT" 
 ( "FORMULE_RESULTAT_SERVICE_ID", "CENTRE_COUT_ID"
  )  AS 
  SELECT
  frs.id formule_resultat_service_id, cc.id centre_cout_id
FROM
  formule_resultat_service   frs
  JOIN service                 s ON s.id = frs.service_id
  JOIN element_pedagogique    ep ON ep.id = s.element_pedagogique_id
  JOIN centre_cout            cc ON 1 = ose_divers.comprise_entre( cc.histo_creation, cc.histo_destruction )
                                
  JOIN centre_cout_structure ccs ON ccs.centre_cout_id = cc.id 
                                AND ccs.structure_id = ep.structure_id 
                                AND 1 = ose_divers.comprise_entre( ccs.histo_creation, ccs.histo_destruction )
                                
  JOIN cc_activite             a ON a.id = cc.activite_id 
                                AND 1 = ose_divers.comprise_entre( a.histo_creation, a.histo_destruction )
                                
  JOIN type_ressource         tr ON tr.id = cc.type_ressource_id 
                                AND 1 = ose_divers.comprise_entre( tr.histo_creation, tr.histo_destruction )
WHERE
  (
    (frs.heures_compl_fi > 0 AND tr.fi = 1 AND a.fi = 1 )
    OR (frs.heures_compl_fa > 0 AND tr.fa = 1 AND a.fa = 1 )
    OR (frs.heures_compl_fc > 0 AND tr.fc = 1 AND a.fc = 1 )
    OR (frs.heures_compl_fc_majorees > 0 AND tr.fc_majorees = 1 AND a.fc_majorees = 1 )
  )

UNION

SELECT
  frs.id formule_resultat_service_id, cc.id
FROM
  formule_resultat_service   frs
  JOIN service                 s ON s.id = frs.service_id 
                                AND s.element_pedagogique_id IS NULL
                                
  JOIN intervenant             i ON i.id = s.intervenant_id
  JOIN centre_cout            cc ON 1 = ose_divers.comprise_entre( cc.histo_creation, cc.histo_destruction )
  
  JOIN centre_cout_structure ccs ON ccs.centre_cout_id = cc.id 
                                AND ccs.structure_id = i.structure_id 
                                AND 1 = ose_divers.comprise_entre( ccs.histo_creation, ccs.histo_destruction )
                                
  JOIN cc_activite             a ON a.id = cc.activite_id 
                                AND 1 = ose_divers.comprise_entre( a.histo_creation, a.histo_destruction )
                                
  JOIN type_ressource         tr ON tr.id = cc.type_ressource_id 
                                AND 1 = ose_divers.comprise_entre( tr.histo_creation, tr.histo_destruction )
WHERE
  (
    (frs.heures_compl_fi > 0 AND tr.fi = 1 AND a.fi = 1 )
    OR (frs.heures_compl_fa > 0 AND tr.fa = 1 AND a.fa = 1 )
    OR (frs.heures_compl_fc > 0 AND tr.fc = 1 AND a.fc = 1 )
    OR (frs.heures_compl_fc_majorees > 0 AND tr.fc_majorees = 1 AND a.fc_majorees = 1 )
  );
---------------------------
--Modifié VIEW
--V_EXPORT_PAIEMENT_WINPAIE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_EXPORT_PAIEMENT_WINPAIE" 
 ( "TYPE_INTERVENANT_ID", "ANNEE_ID", "STRUCTURE_ID", "PERIODE_PAIEMENT_ID", "INTERVENANT_ID", "INSEE", "NOM", "CARTE", "CODE_ORIGINE", "RETENUE", "SENS", "MC", "NBU", "MONTANT", "LIBELLE"
  )  AS 
  SELECT
  si.type_intervenant_id type_intervenant_id,
  i.annee_id,
  t2.structure_id,
  t2.periode_paiement_id,
  i.id intervenant_id,
  
  NVL(i.numero_insee,'') || TRIM(NVL(TO_CHAR(i.numero_insee_cle,'00'),'')) insee,
  i.nom_usuel || ',' || i.prenom nom,
  to_char((SELECT valeur FROM parametre WHERE nom = 'winpaie_carte' AND 1=ose_divers.comprise_entre(histo_creation,histo_destruction))) carte,
  t2.code_origine,
  to_char((SELECT valeur FROM parametre WHERE nom = 'winpaie_retenue' AND 1=ose_divers.comprise_entre(histo_creation,histo_destruction))) retenue,
  to_char((SELECT valeur FROM parametre WHERE nom = 'winpaie_sens' AND 1=ose_divers.comprise_entre(histo_creation,histo_destruction))) sens,
  to_char((SELECT valeur FROM parametre WHERE nom = 'winpaie_mc' AND 1=ose_divers.comprise_entre(histo_creation,histo_destruction))) mc,
  t2.nbu,
  OSE_FORMULE.GET_TAUX_HORAIRE_HETD( NVL(t2.date_mise_en_paiement,SYSDATE) ) montant,
  COALESCE(t2.unite_budgetaire,'') || ' ' || to_char(i.annee_id) || ' ' || to_char(i.annee_id+1) 
  /*  || ' ' || to_char(FLOOR(t2.nbu)) || ' H' || CASE
      WHEN to_char(ROUND( t2.nbu-FLOOR(t2.nbu), 2 )*100,'00') = ' 00' THEN '' 
      ELSE to_char(ROUND( t2.nbu-FLOOR(t2.nbu), 2 )*100,'00') END*/ libelle
FROM (
  SELECT
    structure_id,
    periode_paiement_id,
    intervenant_id,
    code_origine,
    ROUND( SUM(nbu), 2) nbu,
    unite_budgetaire,
    date_mise_en_paiement
  FROM (
    WITH mep AS (
    SELECT
      -- pour les filtres
      mep.id,
      mis.structure_id,
      mep.periode_paiement_id,
      mis.intervenant_id,
      mep.heures,
      cc.unite_budgetaire,
      mep.date_mise_en_paiement
    FROM
      v_mep_intervenant_structure  mis
      JOIN mise_en_paiement        mep ON mep.id = mis.mise_en_paiement_id AND 1 = ose_divers.comprise_entre( mep.histo_creation, mep.histo_destruction )
      JOIN centre_cout              cc ON cc.id = mep.centre_cout_id
      JOIN type_heures              th ON th.id = mep.type_heures_id
    WHERE
      mep.date_mise_en_paiement IS NOT NULL
      AND mep.periode_paiement_id IS NOT NULL
      AND th.eligible_extraction_paie = 1
    )
    SELECT
      mep.id,
      mep.structure_id,
      mep.periode_paiement_id,
      mep.intervenant_id,
      2 code_origine,
      mep.heures * 4 / 10 nbu,
      mep.unite_budgetaire,
      mep.date_mise_en_paiement
    FROM
      mep
    WHERE
      mep.heures * 4 / 10 > 0
      
    UNION
    
    SELECT 
      mep.id,
      mep.structure_id,
      mep.periode_paiement_id,
      mep.intervenant_id,
      1 code_origine,
      mep.heures * 6 / 10 nbu,
      mep.unite_budgetaire,
      mep.date_mise_en_paiement
    FROM
      mep
    WHERE
      mep.heures * 6 / 10 > 0
  ) t1
  GROUP BY
    structure_id,
    periode_paiement_id,
    intervenant_id,
    code_origine,
    unite_budgetaire,
    date_mise_en_paiement
) t2
JOIN intervenant i ON i.id = t2.intervenant_id
JOIN statut_intervenant si ON si.id = i.statut_id
JOIN structure s ON s.id = t2.structure_id;
---------------------------
--Modifié VIEW
--V_ETAT_PAIEMENT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_ETAT_PAIEMENT" 
 ( "PERIODE_PAIEMENT_ID", "STRUCTURE_ID", "INTERVENANT_TYPE_ID", "INTERVENANT_ID", "ANNEE_ID", "CENTRE_COUT_ID", "DOMAINE_FONCTIONNEL_ID", "ETAT", "STRUCTURE_LIBELLE", "DATE_MISE_EN_PAIEMENT", "PERIODE_PAIEMENT_LIBELLE", "INTERVENANT_TYPE", "INTERVENANT_CODE", "INTERVENANT_NOM", "INTERVENANT_NUMERO_INSEE", "CENTRE_COUT_CODE", "CENTRE_COUT_LIBELLE", "DOMAINE_FONCTIONNEL_CODE", "DOMAINE_FONCTIONNEL_LIBELLE", "HETD", "HETD_POURC", "HETD_MONTANT", "REM_FC_D714", "EXERCICE_AA", "EXERCICE_AA_MONTANT", "EXERCICE_AC", "EXERCICE_AC_MONTANT"
  )  AS 
  SELECT
  periode_paiement_id,
  structure_id,
  intervenant_type_id,
  intervenant_id, 
  annee_id, 
  centre_cout_id, 
  domaine_fonctionnel_id,
  etat,
  structure_libelle,
  date_mise_en_paiement,
  periode_paiement_libelle,
  intervenant_type,
  intervenant_code,
  intervenant_nom,
  intervenant_numero_insee,
  centre_cout_code,
  centre_cout_libelle,
  domaine_fonctionnel_code,
  domaine_fonctionnel_libelle,
  hetd,
  CASE WHEN pourc_ecart >= 0 THEN
    CASE WHEN RANK() OVER (PARTITION BY periode_paiement_id, intervenant_id, etat, structure_id ORDER BY CASE WHEN (pourc_ecart >= 0 AND pourc_diff >= 0) OR (pourc_ecart < 0 AND pourc_diff < 0) THEN pourc_diff ELSE -1 END DESC) <= (ABS(pourc_ecart) / 0.001) THEN hetd_pourc + (pourc_ecart / ABS(pourc_ecart) * 0.001) ELSE hetd_pourc END
  ELSE
    CASE WHEN RANK() OVER (PARTITION BY periode_paiement_id, intervenant_id, etat, structure_id ORDER BY CASE WHEN (pourc_ecart >= 0 AND pourc_diff >= 0) OR (pourc_ecart < 0 AND pourc_diff < 0) THEN pourc_diff ELSE -1 END) <= (ABS(pourc_ecart) / 0.001) THEN hetd_pourc + (pourc_ecart / ABS(pourc_ecart) * 0.001) ELSE hetd_pourc END
  END hetd_pourc,
  hetd_montant,
  rem_fc_d714,
  exercice_aa,
  exercice_aa_montant,
  exercice_ac,
  exercice_ac_montant 
FROM
(
SELECT
  dep3.*,
  
  1-CASE WHEN hetd > 0 THEN SUM( hetd_pourc ) OVER ( PARTITION BY periode_paiement_id, intervenant_id, etat, structure_id) ELSE 0 END pourc_ecart
  
  
FROM (

SELECT 
  periode_paiement_id,
  structure_id,
  intervenant_type_id,
  intervenant_id, 
  annee_id, 
  centre_cout_id, 
  domaine_fonctionnel_id,
  etat,
  structure_libelle,
  date_mise_en_paiement,
  periode_paiement_libelle,
  intervenant_type,
  intervenant_code,
  intervenant_nom,
  intervenant_numero_insee,
  centre_cout_code,
  centre_cout_libelle,
  domaine_fonctionnel_code,
  domaine_fonctionnel_libelle,
  hetd,
  ROUND( CASE WHEN hetd > 0 THEN hetd / SUM( hetd ) OVER( PARTITION BY periode_paiement_id, intervenant_id, etat, structure_id) ELSE 0 END, 3 ) hetd_pourc,
  ROUND( hetd * taux_horaire, 2 ) hetd_montant,
  ROUND( fc_majorees * taux_horaire, 2 ) rem_fc_d714,
  exercice_aa,
  ROUND( exercice_aa * taux_horaire, 2 ) exercice_aa_montant,
  exercice_ac,
  ROUND( exercice_ac * taux_horaire, 2 ) exercice_ac_montant,
  
  
  (CASE WHEN hetd > 0 THEN hetd / SUM( hetd ) OVER( PARTITION BY periode_paiement_id, intervenant_id, etat, structure_id) ELSE 0 END)
  -
  ROUND( CASE WHEN hetd > 0 THEN hetd / SUM( hetd ) OVER( PARTITION BY periode_paiement_id, intervenant_id, etat, structure_id) ELSE 0 END, 3 ) pourc_diff

FROM (
  WITH dep AS ( -- détails par état de paiement
  SELECT
    p.id                                                                periode_paiement_id,
    s.id                                                                structure_id,
    i.id                                                                intervenant_id,
    i.annee_id                                                          annee_id,
    cc.id                                                               centre_cout_id,
    df.id                                                               domaine_fonctionnel_id,
    ti.id                                                               intervenant_type_id,
    CASE
        WHEN mep.date_mise_en_paiement IS NULL THEN 'a-mettre-en-paiement'
        ELSE 'mis-en-paiement'
    END                                                                 etat,

    p.libelle_long || ' ' || to_char( add_months( a.date_debut, p.ecart_mois ), 'yyyy' ) periode_paiement_libelle,
    mep.date_mise_en_paiement                                           date_mise_en_paiement,
    s.libelle_court                                                     structure_libelle,
    ti.libelle                                                          intervenant_type,
    i.source_code                                                       intervenant_code,
    i.nom_usuel || ' ' || i.prenom                                      intervenant_nom,
    TRIM( NVL(i.numero_insee,'') || NVL(TO_CHAR(i.numero_insee_cle,'00'),'') ) intervenant_numero_insee,
    cc.source_code                                                      centre_cout_code,
    cc.libelle                                                          centre_cout_libelle,
    df.source_code                                                      domaine_fonctionnel_code,
    df.libelle                                                          domaine_fonctionnel_libelle,
    CASE WHEN th.code = 'fc_majorees' THEN 0 ELSE mep.heures END        hetd,
    CASE WHEN th.code = 'fc_majorees' THEN mep.heures ELSE 0 END        fc_majorees,
    mep.heures * 4 / 10                                                 exercice_aa,
    mep.heures * 6 / 10                                                 exercice_ac,
    OSE_FORMULE.GET_TAUX_HORAIRE_HETD( NVL(mep.date_mise_en_paiement,SYSDATE) )      taux_horaire
  FROM
    v_mep_intervenant_structure  mis
    JOIN mise_en_paiement        mep ON mep.id = mis.mise_en_paiement_id AND 1 = ose_divers.comprise_entre( mep.histo_creation, mep.histo_destruction )
    JOIN type_heures              th ON  th.id = mep.type_heures_id
    JOIN centre_cout              cc ON  cc.id = mep.centre_cout_id      -- pas d'historique pour les centres de coût, qui devront tout de même apparaitre mais en erreur
    JOIN intervenant               i ON   i.id = mis.intervenant_id      AND 1 = ose_divers.comprise_entre(   i.histo_creation,   i.histo_destruction )
    JOIN annee                     a ON   a.id = i.annee_id
    JOIN statut_intervenant       si ON  si.id = i.statut_id
    JOIN type_intervenant         ti ON  ti.id = si.type_intervenant_id
    JOIN structure                 s ON   s.id = mis.structure_id
    LEFT JOIN validation           v ON   v.id = mep.validation_id       AND 1 = ose_divers.comprise_entre(   v.histo_creation,   v.histo_destruction )
    LEFT JOIN domaine_fonctionnel df ON  df.id = mis.domaine_fonctionnel_id
    LEFT JOIN periode              p ON   p.id = mep.periode_paiement_id
  )
  SELECT
    periode_paiement_id,
    structure_id, 
    intervenant_type_id,
    intervenant_id, 
    annee_id, 
    centre_cout_id, 
    domaine_fonctionnel_id, 
    etat,
    periode_paiement_libelle,
    structure_libelle,
    date_mise_en_paiement,
    intervenant_type,
    intervenant_code,
    intervenant_nom,
    intervenant_numero_insee,
    centre_cout_code,
    centre_cout_libelle,
    domaine_fonctionnel_code,
    domaine_fonctionnel_libelle,
    SUM( hetd ) hetd,
    SUM( fc_majorees ) fc_majorees,
    SUM( exercice_aa ) exercice_aa,
    SUM( exercice_ac ) exercice_ac,
    taux_horaire
  FROM
    dep
  GROUP BY
    periode_paiement_id,
    structure_id, 
    intervenant_type_id,
    intervenant_id, 
    annee_id, 
    centre_cout_id, 
    domaine_fonctionnel_id, 
    etat,
    periode_paiement_libelle,
    structure_libelle,
    date_mise_en_paiement,
    intervenant_type,
    intervenant_code,
    intervenant_nom,
    intervenant_numero_insee,
    centre_cout_code,
    centre_cout_libelle,
    domaine_fonctionnel_code,
    domaine_fonctionnel_libelle,
    taux_horaire
) 
dep2
)
dep3
)
dep4;
---------------------------
--Modifié VIEW
--V_DIFF_STRUCTURE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_STRUCTURE" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ETABLISSEMENT_ID", "LIBELLE_COURT", "LIBELLE_LONG", "NIVEAU", "PARENTE_ID", "STRUCTURE_NIV2_ID", "TYPE_ID", "U_ETABLISSEMENT_ID", "U_LIBELLE_COURT", "U_LIBELLE_LONG", "U_NIVEAU", "U_PARENTE_ID", "U_STRUCTURE_NIV2_ID", "U_TYPE_ID"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ETABLISSEMENT_ID",diff."LIBELLE_COURT",diff."LIBELLE_LONG",diff."NIVEAU",diff."PARENTE_ID",diff."STRUCTURE_NIV2_ID",diff."TYPE_ID",diff."U_ETABLISSEMENT_ID",diff."U_LIBELLE_COURT",diff."U_LIBELLE_LONG",diff."U_NIVEAU",diff."U_PARENTE_ID",diff."U_STRUCTURE_NIV2_ID",diff."U_TYPE_ID" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ETABLISSEMENT_ID ELSE S.ETABLISSEMENT_ID END ETABLISSEMENT_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_COURT ELSE S.LIBELLE_COURT END LIBELLE_COURT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_LONG ELSE S.LIBELLE_LONG END LIBELLE_LONG,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NIVEAU ELSE S.NIVEAU END NIVEAU,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PARENTE_ID ELSE S.PARENTE_ID END PARENTE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_NIV2_ID ELSE S.STRUCTURE_NIV2_ID END STRUCTURE_NIV2_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TYPE_ID ELSE S.TYPE_ID END TYPE_ID,
    CASE WHEN D.ETABLISSEMENT_ID <> S.ETABLISSEMENT_ID OR (D.ETABLISSEMENT_ID IS NULL AND S.ETABLISSEMENT_ID IS NOT NULL) OR (D.ETABLISSEMENT_ID IS NOT NULL AND S.ETABLISSEMENT_ID IS NULL) THEN 1 ELSE 0 END U_ETABLISSEMENT_ID,
    CASE WHEN D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL) THEN 1 ELSE 0 END U_LIBELLE_COURT,
    CASE WHEN D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL) THEN 1 ELSE 0 END U_LIBELLE_LONG,
    CASE WHEN D.NIVEAU <> S.NIVEAU OR (D.NIVEAU IS NULL AND S.NIVEAU IS NOT NULL) OR (D.NIVEAU IS NOT NULL AND S.NIVEAU IS NULL) THEN 1 ELSE 0 END U_NIVEAU,
    CASE WHEN D.PARENTE_ID <> S.PARENTE_ID OR (D.PARENTE_ID IS NULL AND S.PARENTE_ID IS NOT NULL) OR (D.PARENTE_ID IS NOT NULL AND S.PARENTE_ID IS NULL) THEN 1 ELSE 0 END U_PARENTE_ID,
    CASE WHEN D.STRUCTURE_NIV2_ID <> S.STRUCTURE_NIV2_ID OR (D.STRUCTURE_NIV2_ID IS NULL AND S.STRUCTURE_NIV2_ID IS NOT NULL) OR (D.STRUCTURE_NIV2_ID IS NOT NULL AND S.STRUCTURE_NIV2_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_NIV2_ID,
    CASE WHEN D.TYPE_ID <> S.TYPE_ID OR (D.TYPE_ID IS NULL AND S.TYPE_ID IS NOT NULL) OR (D.TYPE_ID IS NOT NULL AND S.TYPE_ID IS NULL) THEN 1 ELSE 0 END U_TYPE_ID
FROM
  STRUCTURE D
  FULL JOIN SRC_STRUCTURE S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ETABLISSEMENT_ID <> S.ETABLISSEMENT_ID OR (D.ETABLISSEMENT_ID IS NULL AND S.ETABLISSEMENT_ID IS NOT NULL) OR (D.ETABLISSEMENT_ID IS NOT NULL AND S.ETABLISSEMENT_ID IS NULL)
  OR D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL)
  OR D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL)
  OR D.NIVEAU <> S.NIVEAU OR (D.NIVEAU IS NULL AND S.NIVEAU IS NOT NULL) OR (D.NIVEAU IS NOT NULL AND S.NIVEAU IS NULL)
  OR D.PARENTE_ID <> S.PARENTE_ID OR (D.PARENTE_ID IS NULL AND S.PARENTE_ID IS NOT NULL) OR (D.PARENTE_ID IS NOT NULL AND S.PARENTE_ID IS NULL)
  OR D.STRUCTURE_NIV2_ID <> S.STRUCTURE_NIV2_ID OR (D.STRUCTURE_NIV2_ID IS NULL AND S.STRUCTURE_NIV2_ID IS NOT NULL) OR (D.STRUCTURE_NIV2_ID IS NOT NULL AND S.STRUCTURE_NIV2_ID IS NULL)
  OR D.TYPE_ID <> S.TYPE_ID OR (D.TYPE_ID IS NULL AND S.TYPE_ID IS NOT NULL) OR (D.TYPE_ID IS NOT NULL AND S.TYPE_ID IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_INTERVENANT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_INTERVENANT" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ANNEE_ID", "BIC", "CIVILITE_ID", "CRITERE_RECHERCHE", "DATE_NAISSANCE", "DEP_NAISSANCE_CODE_INSEE", "DEP_NAISSANCE_LIBELLE", "DISCIPLINE_ID", "EMAIL", "GRADE_ID", "IBAN", "NOM_PATRONYMIQUE", "NOM_USUEL", "NUMERO_INSEE", "NUMERO_INSEE_CLE", "NUMERO_INSEE_PROVISOIRE", "PAYS_NAISSANCE_CODE_INSEE", "PAYS_NAISSANCE_LIBELLE", "PAYS_NATIONALITE_CODE_INSEE", "PAYS_NATIONALITE_LIBELLE", "PRENOM", "STATUT_ID", "STRUCTURE_ID", "TEL_MOBILE", "TEL_PRO", "VILLE_NAISSANCE_CODE_INSEE", "VILLE_NAISSANCE_LIBELLE", "U_ANNEE_ID", "U_BIC", "U_CIVILITE_ID", "U_CRITERE_RECHERCHE", "U_DATE_NAISSANCE", "U_DEP_NAISSANCE_CODE_INSEE", "U_DEP_NAISSANCE_LIBELLE", "U_DISCIPLINE_ID", "U_EMAIL", "U_GRADE_ID", "U_IBAN", "U_NOM_PATRONYMIQUE", "U_NOM_USUEL", "U_NUMERO_INSEE", "U_NUMERO_INSEE_CLE", "U_NUMERO_INSEE_PROVISOIRE", "U_PAYS_NAISSANCE_CODE_INSEE", "U_PAYS_NAISSANCE_LIBELLE", "U_PAYS_NATIONALITE_CODE_INSEE", "U_PAYS_NATIONALITE_LIBELLE", "U_PRENOM", "U_STATUT_ID", "U_STRUCTURE_ID", "U_TEL_MOBILE", "U_TEL_PRO", "U_VILLE_NAISSANCE_CODE_INSEE", "U_VILLE_NAISSANCE_LIBELLE"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ANNEE_ID",diff."BIC",diff."CIVILITE_ID",diff."CRITERE_RECHERCHE",diff."DATE_NAISSANCE",diff."DEP_NAISSANCE_CODE_INSEE",diff."DEP_NAISSANCE_LIBELLE",diff."DISCIPLINE_ID",diff."EMAIL",diff."GRADE_ID",diff."IBAN",diff."NOM_PATRONYMIQUE",diff."NOM_USUEL",diff."NUMERO_INSEE",diff."NUMERO_INSEE_CLE",diff."NUMERO_INSEE_PROVISOIRE",diff."PAYS_NAISSANCE_CODE_INSEE",diff."PAYS_NAISSANCE_LIBELLE",diff."PAYS_NATIONALITE_CODE_INSEE",diff."PAYS_NATIONALITE_LIBELLE",diff."PRENOM",diff."STATUT_ID",diff."STRUCTURE_ID",diff."TEL_MOBILE",diff."TEL_PRO",diff."VILLE_NAISSANCE_CODE_INSEE",diff."VILLE_NAISSANCE_LIBELLE",diff."U_ANNEE_ID",diff."U_BIC",diff."U_CIVILITE_ID",diff."U_CRITERE_RECHERCHE",diff."U_DATE_NAISSANCE",diff."U_DEP_NAISSANCE_CODE_INSEE",diff."U_DEP_NAISSANCE_LIBELLE",diff."U_DISCIPLINE_ID",diff."U_EMAIL",diff."U_GRADE_ID",diff."U_IBAN",diff."U_NOM_PATRONYMIQUE",diff."U_NOM_USUEL",diff."U_NUMERO_INSEE",diff."U_NUMERO_INSEE_CLE",diff."U_NUMERO_INSEE_PROVISOIRE",diff."U_PAYS_NAISSANCE_CODE_INSEE",diff."U_PAYS_NAISSANCE_LIBELLE",diff."U_PAYS_NATIONALITE_CODE_INSEE",diff."U_PAYS_NATIONALITE_LIBELLE",diff."U_PRENOM",diff."U_STATUT_ID",diff."U_STRUCTURE_ID",diff."U_TEL_MOBILE",diff."U_TEL_PRO",diff."U_VILLE_NAISSANCE_CODE_INSEE",diff."U_VILLE_NAISSANCE_LIBELLE" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND d.ANNEE_ID = ose_import.get_current_annee THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ANNEE_ID ELSE S.ANNEE_ID END ANNEE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.BIC ELSE S.BIC END BIC,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CIVILITE_ID ELSE S.CIVILITE_ID END CIVILITE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CRITERE_RECHERCHE ELSE S.CRITERE_RECHERCHE END CRITERE_RECHERCHE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DATE_NAISSANCE ELSE S.DATE_NAISSANCE END DATE_NAISSANCE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DEP_NAISSANCE_CODE_INSEE ELSE S.DEP_NAISSANCE_CODE_INSEE END DEP_NAISSANCE_CODE_INSEE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DEP_NAISSANCE_LIBELLE ELSE S.DEP_NAISSANCE_LIBELLE END DEP_NAISSANCE_LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DISCIPLINE_ID ELSE S.DISCIPLINE_ID END DISCIPLINE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.EMAIL ELSE S.EMAIL END EMAIL,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.GRADE_ID ELSE S.GRADE_ID END GRADE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.IBAN ELSE S.IBAN END IBAN,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NOM_PATRONYMIQUE ELSE S.NOM_PATRONYMIQUE END NOM_PATRONYMIQUE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NOM_USUEL ELSE S.NOM_USUEL END NOM_USUEL,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NUMERO_INSEE ELSE S.NUMERO_INSEE END NUMERO_INSEE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NUMERO_INSEE_CLE ELSE S.NUMERO_INSEE_CLE END NUMERO_INSEE_CLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NUMERO_INSEE_PROVISOIRE ELSE S.NUMERO_INSEE_PROVISOIRE END NUMERO_INSEE_PROVISOIRE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PAYS_NAISSANCE_CODE_INSEE ELSE S.PAYS_NAISSANCE_CODE_INSEE END PAYS_NAISSANCE_CODE_INSEE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PAYS_NAISSANCE_LIBELLE ELSE S.PAYS_NAISSANCE_LIBELLE END PAYS_NAISSANCE_LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PAYS_NATIONALITE_CODE_INSEE ELSE S.PAYS_NATIONALITE_CODE_INSEE END PAYS_NATIONALITE_CODE_INSEE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PAYS_NATIONALITE_LIBELLE ELSE S.PAYS_NATIONALITE_LIBELLE END PAYS_NATIONALITE_LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PRENOM ELSE S.PRENOM END PRENOM,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STATUT_ID ELSE S.STATUT_ID END STATUT_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_ID ELSE S.STRUCTURE_ID END STRUCTURE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TEL_MOBILE ELSE S.TEL_MOBILE END TEL_MOBILE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TEL_PRO ELSE S.TEL_PRO END TEL_PRO,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VILLE_NAISSANCE_CODE_INSEE ELSE S.VILLE_NAISSANCE_CODE_INSEE END VILLE_NAISSANCE_CODE_INSEE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VILLE_NAISSANCE_LIBELLE ELSE S.VILLE_NAISSANCE_LIBELLE END VILLE_NAISSANCE_LIBELLE,
    CASE WHEN D.ANNEE_ID <> S.ANNEE_ID OR (D.ANNEE_ID IS NULL AND S.ANNEE_ID IS NOT NULL) OR (D.ANNEE_ID IS NOT NULL AND S.ANNEE_ID IS NULL) THEN 1 ELSE 0 END U_ANNEE_ID,
    CASE WHEN D.BIC <> S.BIC OR (D.BIC IS NULL AND S.BIC IS NOT NULL) OR (D.BIC IS NOT NULL AND S.BIC IS NULL) THEN 1 ELSE 0 END U_BIC,
    CASE WHEN D.CIVILITE_ID <> S.CIVILITE_ID OR (D.CIVILITE_ID IS NULL AND S.CIVILITE_ID IS NOT NULL) OR (D.CIVILITE_ID IS NOT NULL AND S.CIVILITE_ID IS NULL) THEN 1 ELSE 0 END U_CIVILITE_ID,
    CASE WHEN D.CRITERE_RECHERCHE <> S.CRITERE_RECHERCHE OR (D.CRITERE_RECHERCHE IS NULL AND S.CRITERE_RECHERCHE IS NOT NULL) OR (D.CRITERE_RECHERCHE IS NOT NULL AND S.CRITERE_RECHERCHE IS NULL) THEN 1 ELSE 0 END U_CRITERE_RECHERCHE,
    CASE WHEN D.DATE_NAISSANCE <> S.DATE_NAISSANCE OR (D.DATE_NAISSANCE IS NULL AND S.DATE_NAISSANCE IS NOT NULL) OR (D.DATE_NAISSANCE IS NOT NULL AND S.DATE_NAISSANCE IS NULL) THEN 1 ELSE 0 END U_DATE_NAISSANCE,
    CASE WHEN D.DEP_NAISSANCE_CODE_INSEE <> S.DEP_NAISSANCE_CODE_INSEE OR (D.DEP_NAISSANCE_CODE_INSEE IS NULL AND S.DEP_NAISSANCE_CODE_INSEE IS NOT NULL) OR (D.DEP_NAISSANCE_CODE_INSEE IS NOT NULL AND S.DEP_NAISSANCE_CODE_INSEE IS NULL) THEN 1 ELSE 0 END U_DEP_NAISSANCE_CODE_INSEE,
    CASE WHEN D.DEP_NAISSANCE_LIBELLE <> S.DEP_NAISSANCE_LIBELLE OR (D.DEP_NAISSANCE_LIBELLE IS NULL AND S.DEP_NAISSANCE_LIBELLE IS NOT NULL) OR (D.DEP_NAISSANCE_LIBELLE IS NOT NULL AND S.DEP_NAISSANCE_LIBELLE IS NULL) THEN 1 ELSE 0 END U_DEP_NAISSANCE_LIBELLE,
    CASE WHEN D.DISCIPLINE_ID <> S.DISCIPLINE_ID OR (D.DISCIPLINE_ID IS NULL AND S.DISCIPLINE_ID IS NOT NULL) OR (D.DISCIPLINE_ID IS NOT NULL AND S.DISCIPLINE_ID IS NULL) THEN 1 ELSE 0 END U_DISCIPLINE_ID,
    CASE WHEN D.EMAIL <> S.EMAIL OR (D.EMAIL IS NULL AND S.EMAIL IS NOT NULL) OR (D.EMAIL IS NOT NULL AND S.EMAIL IS NULL) THEN 1 ELSE 0 END U_EMAIL,
    CASE WHEN D.GRADE_ID <> S.GRADE_ID OR (D.GRADE_ID IS NULL AND S.GRADE_ID IS NOT NULL) OR (D.GRADE_ID IS NOT NULL AND S.GRADE_ID IS NULL) THEN 1 ELSE 0 END U_GRADE_ID,
    CASE WHEN D.IBAN <> S.IBAN OR (D.IBAN IS NULL AND S.IBAN IS NOT NULL) OR (D.IBAN IS NOT NULL AND S.IBAN IS NULL) THEN 1 ELSE 0 END U_IBAN,
    CASE WHEN D.NOM_PATRONYMIQUE <> S.NOM_PATRONYMIQUE OR (D.NOM_PATRONYMIQUE IS NULL AND S.NOM_PATRONYMIQUE IS NOT NULL) OR (D.NOM_PATRONYMIQUE IS NOT NULL AND S.NOM_PATRONYMIQUE IS NULL) THEN 1 ELSE 0 END U_NOM_PATRONYMIQUE,
    CASE WHEN D.NOM_USUEL <> S.NOM_USUEL OR (D.NOM_USUEL IS NULL AND S.NOM_USUEL IS NOT NULL) OR (D.NOM_USUEL IS NOT NULL AND S.NOM_USUEL IS NULL) THEN 1 ELSE 0 END U_NOM_USUEL,
    CASE WHEN D.NUMERO_INSEE <> S.NUMERO_INSEE OR (D.NUMERO_INSEE IS NULL AND S.NUMERO_INSEE IS NOT NULL) OR (D.NUMERO_INSEE IS NOT NULL AND S.NUMERO_INSEE IS NULL) THEN 1 ELSE 0 END U_NUMERO_INSEE,
    CASE WHEN D.NUMERO_INSEE_CLE <> S.NUMERO_INSEE_CLE OR (D.NUMERO_INSEE_CLE IS NULL AND S.NUMERO_INSEE_CLE IS NOT NULL) OR (D.NUMERO_INSEE_CLE IS NOT NULL AND S.NUMERO_INSEE_CLE IS NULL) THEN 1 ELSE 0 END U_NUMERO_INSEE_CLE,
    CASE WHEN D.NUMERO_INSEE_PROVISOIRE <> S.NUMERO_INSEE_PROVISOIRE OR (D.NUMERO_INSEE_PROVISOIRE IS NULL AND S.NUMERO_INSEE_PROVISOIRE IS NOT NULL) OR (D.NUMERO_INSEE_PROVISOIRE IS NOT NULL AND S.NUMERO_INSEE_PROVISOIRE IS NULL) THEN 1 ELSE 0 END U_NUMERO_INSEE_PROVISOIRE,
    CASE WHEN D.PAYS_NAISSANCE_CODE_INSEE <> S.PAYS_NAISSANCE_CODE_INSEE OR (D.PAYS_NAISSANCE_CODE_INSEE IS NULL AND S.PAYS_NAISSANCE_CODE_INSEE IS NOT NULL) OR (D.PAYS_NAISSANCE_CODE_INSEE IS NOT NULL AND S.PAYS_NAISSANCE_CODE_INSEE IS NULL) THEN 1 ELSE 0 END U_PAYS_NAISSANCE_CODE_INSEE,
    CASE WHEN D.PAYS_NAISSANCE_LIBELLE <> S.PAYS_NAISSANCE_LIBELLE OR (D.PAYS_NAISSANCE_LIBELLE IS NULL AND S.PAYS_NAISSANCE_LIBELLE IS NOT NULL) OR (D.PAYS_NAISSANCE_LIBELLE IS NOT NULL AND S.PAYS_NAISSANCE_LIBELLE IS NULL) THEN 1 ELSE 0 END U_PAYS_NAISSANCE_LIBELLE,
    CASE WHEN D.PAYS_NATIONALITE_CODE_INSEE <> S.PAYS_NATIONALITE_CODE_INSEE OR (D.PAYS_NATIONALITE_CODE_INSEE IS NULL AND S.PAYS_NATIONALITE_CODE_INSEE IS NOT NULL) OR (D.PAYS_NATIONALITE_CODE_INSEE IS NOT NULL AND S.PAYS_NATIONALITE_CODE_INSEE IS NULL) THEN 1 ELSE 0 END U_PAYS_NATIONALITE_CODE_INSEE,
    CASE WHEN D.PAYS_NATIONALITE_LIBELLE <> S.PAYS_NATIONALITE_LIBELLE OR (D.PAYS_NATIONALITE_LIBELLE IS NULL AND S.PAYS_NATIONALITE_LIBELLE IS NOT NULL) OR (D.PAYS_NATIONALITE_LIBELLE IS NOT NULL AND S.PAYS_NATIONALITE_LIBELLE IS NULL) THEN 1 ELSE 0 END U_PAYS_NATIONALITE_LIBELLE,
    CASE WHEN D.PRENOM <> S.PRENOM OR (D.PRENOM IS NULL AND S.PRENOM IS NOT NULL) OR (D.PRENOM IS NOT NULL AND S.PRENOM IS NULL) THEN 1 ELSE 0 END U_PRENOM,
    CASE WHEN D.STATUT_ID <> S.STATUT_ID OR (D.STATUT_ID IS NULL AND S.STATUT_ID IS NOT NULL) OR (D.STATUT_ID IS NOT NULL AND S.STATUT_ID IS NULL) THEN 1 ELSE 0 END U_STATUT_ID,
    CASE WHEN D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_ID,
    CASE WHEN D.TEL_MOBILE <> S.TEL_MOBILE OR (D.TEL_MOBILE IS NULL AND S.TEL_MOBILE IS NOT NULL) OR (D.TEL_MOBILE IS NOT NULL AND S.TEL_MOBILE IS NULL) THEN 1 ELSE 0 END U_TEL_MOBILE,
    CASE WHEN D.TEL_PRO <> S.TEL_PRO OR (D.TEL_PRO IS NULL AND S.TEL_PRO IS NOT NULL) OR (D.TEL_PRO IS NOT NULL AND S.TEL_PRO IS NULL) THEN 1 ELSE 0 END U_TEL_PRO,
    CASE WHEN D.VILLE_NAISSANCE_CODE_INSEE <> S.VILLE_NAISSANCE_CODE_INSEE OR (D.VILLE_NAISSANCE_CODE_INSEE IS NULL AND S.VILLE_NAISSANCE_CODE_INSEE IS NOT NULL) OR (D.VILLE_NAISSANCE_CODE_INSEE IS NOT NULL AND S.VILLE_NAISSANCE_CODE_INSEE IS NULL) THEN 1 ELSE 0 END U_VILLE_NAISSANCE_CODE_INSEE,
    CASE WHEN D.VILLE_NAISSANCE_LIBELLE <> S.VILLE_NAISSANCE_LIBELLE OR (D.VILLE_NAISSANCE_LIBELLE IS NULL AND S.VILLE_NAISSANCE_LIBELLE IS NOT NULL) OR (D.VILLE_NAISSANCE_LIBELLE IS NOT NULL AND S.VILLE_NAISSANCE_LIBELLE IS NULL) THEN 1 ELSE 0 END U_VILLE_NAISSANCE_LIBELLE
FROM
  INTERVENANT D
  FULL JOIN SRC_INTERVENANT S ON S.source_id = D.source_id AND S.source_code = D.source_code AND S.ANNEE_ID = d.ANNEE_ID
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ANNEE_ID <> S.ANNEE_ID OR (D.ANNEE_ID IS NULL AND S.ANNEE_ID IS NOT NULL) OR (D.ANNEE_ID IS NOT NULL AND S.ANNEE_ID IS NULL)
  OR D.BIC <> S.BIC OR (D.BIC IS NULL AND S.BIC IS NOT NULL) OR (D.BIC IS NOT NULL AND S.BIC IS NULL)
  OR D.CIVILITE_ID <> S.CIVILITE_ID OR (D.CIVILITE_ID IS NULL AND S.CIVILITE_ID IS NOT NULL) OR (D.CIVILITE_ID IS NOT NULL AND S.CIVILITE_ID IS NULL)
  OR D.CRITERE_RECHERCHE <> S.CRITERE_RECHERCHE OR (D.CRITERE_RECHERCHE IS NULL AND S.CRITERE_RECHERCHE IS NOT NULL) OR (D.CRITERE_RECHERCHE IS NOT NULL AND S.CRITERE_RECHERCHE IS NULL)
  OR D.DATE_NAISSANCE <> S.DATE_NAISSANCE OR (D.DATE_NAISSANCE IS NULL AND S.DATE_NAISSANCE IS NOT NULL) OR (D.DATE_NAISSANCE IS NOT NULL AND S.DATE_NAISSANCE IS NULL)
  OR D.DEP_NAISSANCE_CODE_INSEE <> S.DEP_NAISSANCE_CODE_INSEE OR (D.DEP_NAISSANCE_CODE_INSEE IS NULL AND S.DEP_NAISSANCE_CODE_INSEE IS NOT NULL) OR (D.DEP_NAISSANCE_CODE_INSEE IS NOT NULL AND S.DEP_NAISSANCE_CODE_INSEE IS NULL)
  OR D.DEP_NAISSANCE_LIBELLE <> S.DEP_NAISSANCE_LIBELLE OR (D.DEP_NAISSANCE_LIBELLE IS NULL AND S.DEP_NAISSANCE_LIBELLE IS NOT NULL) OR (D.DEP_NAISSANCE_LIBELLE IS NOT NULL AND S.DEP_NAISSANCE_LIBELLE IS NULL)
  OR D.DISCIPLINE_ID <> S.DISCIPLINE_ID OR (D.DISCIPLINE_ID IS NULL AND S.DISCIPLINE_ID IS NOT NULL) OR (D.DISCIPLINE_ID IS NOT NULL AND S.DISCIPLINE_ID IS NULL)
  OR D.EMAIL <> S.EMAIL OR (D.EMAIL IS NULL AND S.EMAIL IS NOT NULL) OR (D.EMAIL IS NOT NULL AND S.EMAIL IS NULL)
  OR D.GRADE_ID <> S.GRADE_ID OR (D.GRADE_ID IS NULL AND S.GRADE_ID IS NOT NULL) OR (D.GRADE_ID IS NOT NULL AND S.GRADE_ID IS NULL)
  OR D.IBAN <> S.IBAN OR (D.IBAN IS NULL AND S.IBAN IS NOT NULL) OR (D.IBAN IS NOT NULL AND S.IBAN IS NULL)
  OR D.NOM_PATRONYMIQUE <> S.NOM_PATRONYMIQUE OR (D.NOM_PATRONYMIQUE IS NULL AND S.NOM_PATRONYMIQUE IS NOT NULL) OR (D.NOM_PATRONYMIQUE IS NOT NULL AND S.NOM_PATRONYMIQUE IS NULL)
  OR D.NOM_USUEL <> S.NOM_USUEL OR (D.NOM_USUEL IS NULL AND S.NOM_USUEL IS NOT NULL) OR (D.NOM_USUEL IS NOT NULL AND S.NOM_USUEL IS NULL)
  OR D.NUMERO_INSEE <> S.NUMERO_INSEE OR (D.NUMERO_INSEE IS NULL AND S.NUMERO_INSEE IS NOT NULL) OR (D.NUMERO_INSEE IS NOT NULL AND S.NUMERO_INSEE IS NULL)
  OR D.NUMERO_INSEE_CLE <> S.NUMERO_INSEE_CLE OR (D.NUMERO_INSEE_CLE IS NULL AND S.NUMERO_INSEE_CLE IS NOT NULL) OR (D.NUMERO_INSEE_CLE IS NOT NULL AND S.NUMERO_INSEE_CLE IS NULL)
  OR D.NUMERO_INSEE_PROVISOIRE <> S.NUMERO_INSEE_PROVISOIRE OR (D.NUMERO_INSEE_PROVISOIRE IS NULL AND S.NUMERO_INSEE_PROVISOIRE IS NOT NULL) OR (D.NUMERO_INSEE_PROVISOIRE IS NOT NULL AND S.NUMERO_INSEE_PROVISOIRE IS NULL)
  OR D.PAYS_NAISSANCE_CODE_INSEE <> S.PAYS_NAISSANCE_CODE_INSEE OR (D.PAYS_NAISSANCE_CODE_INSEE IS NULL AND S.PAYS_NAISSANCE_CODE_INSEE IS NOT NULL) OR (D.PAYS_NAISSANCE_CODE_INSEE IS NOT NULL AND S.PAYS_NAISSANCE_CODE_INSEE IS NULL)
  OR D.PAYS_NAISSANCE_LIBELLE <> S.PAYS_NAISSANCE_LIBELLE OR (D.PAYS_NAISSANCE_LIBELLE IS NULL AND S.PAYS_NAISSANCE_LIBELLE IS NOT NULL) OR (D.PAYS_NAISSANCE_LIBELLE IS NOT NULL AND S.PAYS_NAISSANCE_LIBELLE IS NULL)
  OR D.PAYS_NATIONALITE_CODE_INSEE <> S.PAYS_NATIONALITE_CODE_INSEE OR (D.PAYS_NATIONALITE_CODE_INSEE IS NULL AND S.PAYS_NATIONALITE_CODE_INSEE IS NOT NULL) OR (D.PAYS_NATIONALITE_CODE_INSEE IS NOT NULL AND S.PAYS_NATIONALITE_CODE_INSEE IS NULL)
  OR D.PAYS_NATIONALITE_LIBELLE <> S.PAYS_NATIONALITE_LIBELLE OR (D.PAYS_NATIONALITE_LIBELLE IS NULL AND S.PAYS_NATIONALITE_LIBELLE IS NOT NULL) OR (D.PAYS_NATIONALITE_LIBELLE IS NOT NULL AND S.PAYS_NATIONALITE_LIBELLE IS NULL)
  OR D.PRENOM <> S.PRENOM OR (D.PRENOM IS NULL AND S.PRENOM IS NOT NULL) OR (D.PRENOM IS NOT NULL AND S.PRENOM IS NULL)
  OR D.STATUT_ID <> S.STATUT_ID OR (D.STATUT_ID IS NULL AND S.STATUT_ID IS NOT NULL) OR (D.STATUT_ID IS NOT NULL AND S.STATUT_ID IS NULL)
  OR D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL)
  OR D.TEL_MOBILE <> S.TEL_MOBILE OR (D.TEL_MOBILE IS NULL AND S.TEL_MOBILE IS NOT NULL) OR (D.TEL_MOBILE IS NOT NULL AND S.TEL_MOBILE IS NULL)
  OR D.TEL_PRO <> S.TEL_PRO OR (D.TEL_PRO IS NULL AND S.TEL_PRO IS NOT NULL) OR (D.TEL_PRO IS NOT NULL AND S.TEL_PRO IS NULL)
  OR D.VILLE_NAISSANCE_CODE_INSEE <> S.VILLE_NAISSANCE_CODE_INSEE OR (D.VILLE_NAISSANCE_CODE_INSEE IS NULL AND S.VILLE_NAISSANCE_CODE_INSEE IS NOT NULL) OR (D.VILLE_NAISSANCE_CODE_INSEE IS NOT NULL AND S.VILLE_NAISSANCE_CODE_INSEE IS NULL)
  OR D.VILLE_NAISSANCE_LIBELLE <> S.VILLE_NAISSANCE_LIBELLE OR (D.VILLE_NAISSANCE_LIBELLE IS NULL AND S.VILLE_NAISSANCE_LIBELLE IS NOT NULL) OR (D.VILLE_NAISSANCE_LIBELLE IS NOT NULL AND S.VILLE_NAISSANCE_LIBELLE IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Nouveau VIEW
--V_DIFF_CENTRE_COUT_STRUCTURE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_CENTRE_COUT_STRUCTURE" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "CENTRE_COUT_ID", "STRUCTURE_ID", "U_CENTRE_COUT_ID", "U_STRUCTURE_ID"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."CENTRE_COUT_ID",diff."STRUCTURE_ID",diff."U_CENTRE_COUT_ID",diff."U_STRUCTURE_ID" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CENTRE_COUT_ID ELSE S.CENTRE_COUT_ID END CENTRE_COUT_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_ID ELSE S.STRUCTURE_ID END STRUCTURE_ID,
    CASE WHEN D.CENTRE_COUT_ID <> S.CENTRE_COUT_ID OR (D.CENTRE_COUT_ID IS NULL AND S.CENTRE_COUT_ID IS NOT NULL) OR (D.CENTRE_COUT_ID IS NOT NULL AND S.CENTRE_COUT_ID IS NULL) THEN 1 ELSE 0 END U_CENTRE_COUT_ID,
    CASE WHEN D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_ID
FROM
  CENTRE_COUT_STRUCTURE D
  FULL JOIN SRC_CENTRE_COUT_STRUCTURE S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.CENTRE_COUT_ID <> S.CENTRE_COUT_ID OR (D.CENTRE_COUT_ID IS NULL AND S.CENTRE_COUT_ID IS NOT NULL) OR (D.CENTRE_COUT_ID IS NOT NULL AND S.CENTRE_COUT_ID IS NULL)
  OR D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_CENTRE_COUT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_CENTRE_COUT" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ACTIVITE_ID", "LIBELLE", "PARENT_ID", "TYPE_RESSOURCE_ID", "UNITE_BUDGETAIRE", "U_ACTIVITE_ID", "U_LIBELLE", "U_PARENT_ID", "U_TYPE_RESSOURCE_ID", "U_UNITE_BUDGETAIRE"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ACTIVITE_ID",diff."LIBELLE",diff."PARENT_ID",diff."TYPE_RESSOURCE_ID",diff."UNITE_BUDGETAIRE",diff."U_ACTIVITE_ID",diff."U_LIBELLE",diff."U_PARENT_ID",diff."U_TYPE_RESSOURCE_ID",diff."U_UNITE_BUDGETAIRE" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ACTIVITE_ID ELSE S.ACTIVITE_ID END ACTIVITE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE ELSE S.LIBELLE END LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PARENT_ID ELSE S.PARENT_ID END PARENT_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TYPE_RESSOURCE_ID ELSE S.TYPE_RESSOURCE_ID END TYPE_RESSOURCE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.UNITE_BUDGETAIRE ELSE S.UNITE_BUDGETAIRE END UNITE_BUDGETAIRE,
    CASE WHEN D.ACTIVITE_ID <> S.ACTIVITE_ID OR (D.ACTIVITE_ID IS NULL AND S.ACTIVITE_ID IS NOT NULL) OR (D.ACTIVITE_ID IS NOT NULL AND S.ACTIVITE_ID IS NULL) THEN 1 ELSE 0 END U_ACTIVITE_ID,
    CASE WHEN D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL) THEN 1 ELSE 0 END U_LIBELLE,
    CASE WHEN D.PARENT_ID <> S.PARENT_ID OR (D.PARENT_ID IS NULL AND S.PARENT_ID IS NOT NULL) OR (D.PARENT_ID IS NOT NULL AND S.PARENT_ID IS NULL) THEN 1 ELSE 0 END U_PARENT_ID,
    CASE WHEN D.TYPE_RESSOURCE_ID <> S.TYPE_RESSOURCE_ID OR (D.TYPE_RESSOURCE_ID IS NULL AND S.TYPE_RESSOURCE_ID IS NOT NULL) OR (D.TYPE_RESSOURCE_ID IS NOT NULL AND S.TYPE_RESSOURCE_ID IS NULL) THEN 1 ELSE 0 END U_TYPE_RESSOURCE_ID,
    CASE WHEN D.UNITE_BUDGETAIRE <> S.UNITE_BUDGETAIRE OR (D.UNITE_BUDGETAIRE IS NULL AND S.UNITE_BUDGETAIRE IS NOT NULL) OR (D.UNITE_BUDGETAIRE IS NOT NULL AND S.UNITE_BUDGETAIRE IS NULL) THEN 1 ELSE 0 END U_UNITE_BUDGETAIRE
FROM
  CENTRE_COUT D
  FULL JOIN SRC_CENTRE_COUT S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ACTIVITE_ID <> S.ACTIVITE_ID OR (D.ACTIVITE_ID IS NULL AND S.ACTIVITE_ID IS NOT NULL) OR (D.ACTIVITE_ID IS NOT NULL AND S.ACTIVITE_ID IS NULL)
  OR D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL)
  OR D.PARENT_ID <> S.PARENT_ID OR (D.PARENT_ID IS NULL AND S.PARENT_ID IS NOT NULL) OR (D.PARENT_ID IS NOT NULL AND S.PARENT_ID IS NULL)
  OR D.TYPE_RESSOURCE_ID <> S.TYPE_RESSOURCE_ID OR (D.TYPE_RESSOURCE_ID IS NULL AND S.TYPE_RESSOURCE_ID IS NOT NULL) OR (D.TYPE_RESSOURCE_ID IS NOT NULL AND S.TYPE_RESSOURCE_ID IS NULL)
  OR D.UNITE_BUDGETAIRE <> S.UNITE_BUDGETAIRE OR (D.UNITE_BUDGETAIRE IS NULL AND S.UNITE_BUDGETAIRE IS NOT NULL) OR (D.UNITE_BUDGETAIRE IS NOT NULL AND S.UNITE_BUDGETAIRE IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Nouveau VIEW
--V_CENTRE_COUT_STRUCTURE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_CENTRE_COUT_STRUCTURE" 
 ( "CENTRE_COUT_ID", "STRUCTURE_ID"
  )  AS 
  SELECT
  ccs.centre_cout_id,
  ccs.structure_id
FROM
  centre_cout_structure ccs
WHERE
  1 = ose_divers.comprise_entre( ccs.histo_creation, ccs.histo_destruction );
---------------------------
--Modifié VIEW
--SRC_STRUCTURE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_STRUCTURE" 
 ( "ID", "LIBELLE_LONG", "LIBELLE_COURT", "PARENTE_ID", "STRUCTURE_NIV2_ID", "TYPE_ID", "ETABLISSEMENT_ID", "NIVEAU", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  null id,
  S.LIBELLE_LONG,
  S.LIBELLE_COURT,
  sp.id parente_id,
  S2.id structure_niv2_id,
  ts.id type_id,
  OSE_PARAMETRE.GET_ETABLISSEMENT etablissement_id,
  S.niveau,
  S.SOURCE_ID,
  S.SOURCE_CODE
FROM
  mv_structure s
  JOIN type_structure ts on ts.code = S.Z_TYPE_ID
  LEFT JOIN structure sp on (sp.source_code = s.z_parente_id)
  LEFT JOIN structure s2 on (s2.source_code = s.z_structure_niv2_id);
---------------------------
--Nouveau VIEW
--SRC_CENTRE_COUT_STRUCTURE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_CENTRE_COUT_STRUCTURE" 
 ( "ID", "CENTRE_COUT_ID", "STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  WITH cc AS (

SELECT
  cc.id id,
  cc.source_code source_code,
  cc.source_code ori_source_code
FROM
  centre_cout cc
  LEFT JOIN centre_cout pcc ON pcc.id = cc.parent_id
WHERE
  pcc.id IS NULL
  
UNION ALL

SELECT
  cc.id id,
  pcc.source_code source_code,
  cc.source_code ori_source_code
FROM
  centre_cout cc
  JOIN centre_cout pcc ON pcc.id = cc.parent_id

)
SELECT
  NULL id,
  cc.id centre_cout_id,
  s.id structure_id,
  (SELECT id FROM source WHERE code='Calcul') source_id,
  cc.ori_source_code || '_' || s.source_code source_code
FROM
  unicaen_corresp_structure_cc ucs
  JOIN cc ON substr( cc.source_code, 2, 3 ) = ucs.code_sifac
  JOIN structure s ON s.source_code = CASE WHEN cc.source_code = 'P950DRRA' THEN 'ECODOCT' ELSE ucs.code_harpege END;
---------------------------
--Modifié VIEW
--SRC_CENTRE_COUT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_CENTRE_COUT" 
 ( "ID", "LIBELLE", "ACTIVITE_ID", "TYPE_RESSOURCE_ID", "UNITE_BUDGETAIRE", "PARENT_ID", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  null              id,
  mvcc.libelle      libelle,
  a.id              activite_id,
  tr.id             type_ressource_id,
  mvcc.unite_budgetaire unite_budgetaire,
  cc.id             parent_id,
  mvcc.source_id    source_id,
  mvcc.source_code  source_code
FROM
  MV_centre_cout mvcc
  LEFT JOIN cc_activite        a ON a.code         = mvcc.z_activite_id
  LEFT JOIN type_ressource    tr ON tr.code        = mvcc.z_type_ressource_id
  LEFT JOIN centre_cout       cc ON cc.source_code = mvcc.z_parent_id
WHERE
  mvcc.z_activite_id IS NOT NULL;
---------------------------
--Nouveau INDEX
--NOTIF_INDICATEUR_UFK_IDX
---------------------------
  CREATE INDEX "OSE"."NOTIF_INDICATEUR_UFK_IDX" ON "OSE"."NOTIFICATION_INDICATEUR" ("PERSONNEL_ID");
---------------------------
--Nouveau INDEX
--DEPARTEMENT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."DEPARTEMENT_HCFK_IDX" ON "OSE"."DEPARTEMENT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TIS_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TIS_HMFK_IDX" ON "OSE"."TYPE_INTERVENTION_STRUCTURE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--CC_TYPE_RESSOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CC_TYPE_RESSOURCE_FK_IDX" ON "OSE"."CENTRE_COUT" ("TYPE_RESSOURCE_ID");
---------------------------
--Nouveau INDEX
--VVH_VALIDATION_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VVH_VALIDATION_FK_IDX" ON "OSE"."VALIDATION_VOL_HORAIRE" ("VALIDATION_ID");
---------------------------
--Nouveau INDEX
--INDIC_DIFF_DOSSIER_INT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."INDIC_DIFF_DOSSIER_INT_FK_IDX" ON "OSE"."INDIC_MODIF_DOSSIER" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--PAYS_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."PAYS_HDFK_IDX" ON "OSE"."PAYS" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_ENS_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_ENS_HCFK_IDX" ON "OSE"."VOLUME_HORAIRE_ENS" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--DOMAINE_FONCTIONNEL_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."DOMAINE_FONCTIONNEL_HDFK_IDX" ON "OSE"."DOMAINE_FONCTIONNEL" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--AS_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AS_SOURCE_FK_IDX" ON "OSE"."ADRESSE_STRUCTURE" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--ETAPE_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."ETAPE_STRUCTURE_FK_IDX" ON "OSE"."ETAPE" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_STRUCTURE_FK_IDX" ON "OSE"."INTERVENANT" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--VH_PERIODE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VH_PERIODE_FK_IDX" ON "OSE"."VOLUME_HORAIRE" ("PERIODE_ID");
---------------------------
--Nouveau INDEX
--WF_INTERVENANT_ETAPE_EFK_IDX
---------------------------
  CREATE INDEX "OSE"."WF_INTERVENANT_ETAPE_EFK_IDX" ON "OSE"."WF_INTERVENANT_ETAPE" ("ETAPE_ID");
---------------------------
--Nouveau INDEX
--TPJS_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TPJS_HMFK_IDX" ON "OSE"."TYPE_PIECE_JOINTE_STATUT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."CONTRAT_HCFK_IDX" ON "OSE"."CONTRAT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--FICHIER_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."FICHIER_HMFK_IDX" ON "OSE"."FICHIER" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--STAT_PRIV_STATUT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."STAT_PRIV_STATUT_FK_IDX" ON "OSE"."STATUT_PRIVILEGE" ("STATUT_ID");
---------------------------
--Nouveau INDEX
--VALIDATION_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."VALIDATION_HDFK_IDX" ON "OSE"."VALIDATION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TIEP_TYPE_INTERVENTION_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TIEP_TYPE_INTERVENTION_FK_IDX" ON "OSE"."TYPE_INTERVENTION_EP" ("TYPE_INTERVENTION_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_MODULATEUR_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_MODULATEUR_HMFK_IDX" ON "OSE"."ELEMENT_MODULATEUR" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_VALIDATION_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_VALIDATION_HDFK_IDX" ON "OSE"."TYPE_VALIDATION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_INTERVENANT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CONTRAT_INTERVENANT_FK_IDX" ON "OSE"."CONTRAT" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--TIS_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TIS_STRUCTURE_FK_IDX" ON "OSE"."TYPE_INTERVENTION_STRUCTURE" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_DISCIPLINE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_DISCIPLINE_FK_IDX" ON "OSE"."INTERVENANT" ("DISCIPLINE_ID");
---------------------------
--Nouveau INDEX
--DEPARTEMENT_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."DEPARTEMENT_SOURCE_FK_IDX" ON "OSE"."DEPARTEMENT" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--DOTATION_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."DOTATION_HCFK_IDX" ON "OSE"."DOTATION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--DEPARTEMENT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."DEPARTEMENT_HMFK_IDX" ON "OSE"."DEPARTEMENT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--SERVICE_ETABLISSEMENT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."SERVICE_ETABLISSEMENT_FK_IDX" ON "OSE"."SERVICE" ("ETABLISSEMENT_ID");
---------------------------
--Nouveau INDEX
--MODULATEUR_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."MODULATEUR_HCFK_IDX" ON "OSE"."MODULATEUR" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--EFFECTIFS_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."EFFECTIFS_HMFK_IDX" ON "OSE"."EFFECTIFS" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--GROUPE_EP_FK_IDX
---------------------------
  CREATE INDEX "OSE"."GROUPE_EP_FK_IDX" ON "OSE"."GROUPE" ("ELEMENT_PEDAGOGIQUE_ID");
---------------------------
--Nouveau INDEX
--PIECE_JOINTE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."PIECE_JOINTE_HMFK_IDX" ON "OSE"."PIECE_JOINTE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ADRESSE_STRUCTURE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."ADRESSE_STRUCTURE_HCFK_IDX" ON "OSE"."ADRESSE_STRUCTURE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TMS_ANNEE_DEBUT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TMS_ANNEE_DEBUT_FK_IDX" ON "OSE"."TYPE_MODULATEUR_STRUCTURE" ("ANNEE_DEBUT_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."CONTRAT_HDFK_IDX" ON "OSE"."CONTRAT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_RESSOURCE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_RESSOURCE_HCFK_IDX" ON "OSE"."TYPE_RESSOURCE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--MEP_DOMAINE_FONCTIONNEL_FK_IDX
---------------------------
  CREATE INDEX "OSE"."MEP_DOMAINE_FONCTIONNEL_FK_IDX" ON "OSE"."MISE_EN_PAIEMENT" ("DOMAINE_FONCTIONNEL_ID");
---------------------------
--Nouveau INDEX
--AGREMENT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."AGREMENT_HCFK_IDX" ON "OSE"."AGREMENT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--GRADE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."GRADE_HCFK_IDX" ON "OSE"."GRADE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_CONTRAT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_CONTRAT_HCFK_IDX" ON "OSE"."TYPE_CONTRAT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--MEP_VALIDATION_FK_IDX
---------------------------
  CREATE INDEX "OSE"."MEP_VALIDATION_FK_IDX" ON "OSE"."MISE_EN_PAIEMENT" ("VALIDATION_ID");
---------------------------
--Nouveau INDEX
--TPJS_STATUT_INTERVENANT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TPJS_STATUT_INTERVENANT_FK_IDX" ON "OSE"."TYPE_PIECE_JOINTE_STATUT" ("STATUT_INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--ETAPE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."ETAPE_HDFK_IDX" ON "OSE"."ETAPE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--WF_INTERVENANT_ETAPE_IFK_IDX
---------------------------
  CREATE INDEX "OSE"."WF_INTERVENANT_ETAPE_IFK_IDX" ON "OSE"."WF_INTERVENANT_ETAPE" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--MISE_EN_PAIEMENT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."MISE_EN_PAIEMENT_HCFK_IDX" ON "OSE"."MISE_EN_PAIEMENT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_FORMATION_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_FORMATION_HCFK_IDX" ON "OSE"."TYPE_FORMATION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--VHENS_EP_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VHENS_EP_FK_IDX" ON "OSE"."VOLUME_HORAIRE_ENS" ("ELEMENT_PEDAGOGIQUE_ID");
---------------------------
--Nouveau INDEX
--S_ELEMENT_PEDAGOGIQUE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."S_ELEMENT_PEDAGOGIQUE_FK_IDX" ON "OSE"."SERVICE" ("ELEMENT_PEDAGOGIQUE_ID");
---------------------------
--Nouveau INDEX
--GRADE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."GRADE_HMFK_IDX" ON "OSE"."GRADE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--FRES_EVH_FK_IDX
---------------------------
  CREATE INDEX "OSE"."FRES_EVH_FK_IDX" ON "OSE"."FORMULE_RESULTAT" ("ETAT_VOLUME_HORAIRE_ID");
---------------------------
--Nouveau INDEX
--PAYS_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."PAYS_SOURCE_FK_IDX" ON "OSE"."PAYS" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--TIS_TYPE_INTERVENTION_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TIS_TYPE_INTERVENTION_FK_IDX" ON "OSE"."TYPE_INTERVENTION_STRUCTURE" ("TYPE_INTERVENTION_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_VALIDATION_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CONTRAT_VALIDATION_FK_IDX" ON "OSE"."CONTRAT" ("VALIDATION_ID");
---------------------------
--Nouveau INDEX
--SR_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."SR_STRUCTURE_FK_IDX" ON "OSE"."SERVICE_REFERENTIEL" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--CP_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CP_SOURCE_FK_IDX" ON "OSE"."CHEMIN_PEDAGOGIQUE" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--ETAPE_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."ETAPE_SOURCE_FK_IDX" ON "OSE"."ETAPE" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_HCFK_IDX" ON "OSE"."TYPE_MODULATEUR" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_HMFK_IDX" ON "OSE"."TYPE_INTERVENTION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--EP_PERIODE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."EP_PERIODE_FK_IDX" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("PERIODE_ID");
---------------------------
--Nouveau INDEX
--DOTATION_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."DOTATION_STRUCTURE_FK_IDX" ON "OSE"."DOTATION" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--WF_ETAPE_AFK_IDX
---------------------------
  CREATE INDEX "OSE"."WF_ETAPE_AFK_IDX" ON "OSE"."WF_ETAPE" ("ANNEE_ID");
---------------------------
--Nouveau INDEX
--STATUT_INTERVENANT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."STATUT_INTERVENANT_HDFK_IDX" ON "OSE"."STATUT_INTERVENANT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CCEP_TYPE_HEURES_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CCEP_TYPE_HEURES_FK_IDX" ON "OSE"."CENTRE_COUT_EP" ("TYPE_HEURES_ID");
---------------------------
--Nouveau INDEX
--DS_MDS_FK_IDX
---------------------------
  CREATE INDEX "OSE"."DS_MDS_FK_IDX" ON "OSE"."MODIFICATION_SERVICE_DU" ("MOTIF_ID");
---------------------------
--Nouveau INDEX
--DISCIPLINE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."DISCIPLINE_HDFK_IDX" ON "OSE"."DISCIPLINE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--FRES_INTERVENANT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."FRES_INTERVENANT_FK_IDX" ON "OSE"."FORMULE_RESULTAT" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--FRVH_FORMULE_RESULTAT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."FRVH_FORMULE_RESULTAT_FK_IDX" ON "OSE"."FORMULE_RESULTAT_VH" ("FORMULE_RESULTAT_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_ENS_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_ENS_HMFK_IDX" ON "OSE"."VOLUME_HORAIRE_ENS" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_HDFK_IDX" ON "OSE"."AFFECTATION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_HDFK_IDX" ON "OSE"."VOLUME_HORAIRE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--MEP_CENTRE_COUT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."MEP_CENTRE_COUT_FK_IDX" ON "OSE"."MISE_EN_PAIEMENT" ("CENTRE_COUT_ID");
---------------------------
--Nouveau INDEX
--TYPE_DOTATION_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_DOTATION_HCFK_IDX" ON "OSE"."TYPE_DOTATION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--ETABLISSEMENT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."ETABLISSEMENT_HMFK_IDX" ON "OSE"."ETABLISSEMENT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_PEDAGOGIQUE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_PEDAGOGIQUE_HMFK_IDX" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_HCFK_IDX" ON "OSE"."TYPE_INTERVENTION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_R_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_R_HMFK_IDX" ON "OSE"."AFFECTATION_RECHERCHE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."CONTRAT_HMFK_IDX" ON "OSE"."CONTRAT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TME_TYPE_MODULATEUR_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TME_TYPE_MODULATEUR_FK_IDX" ON "OSE"."TYPE_MODULATEUR_EP" ("TYPE_MODULATEUR_ID");
---------------------------
--Nouveau INDEX
--EP_DISCIPLINE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."EP_DISCIPLINE_FK_IDX" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("DISCIPLINE_ID");
---------------------------
--Nouveau INDEX
--PERSONNEL_CIVILITE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."PERSONNEL_CIVILITE_FK_IDX" ON "OSE"."PERSONNEL" ("CIVILITE_ID");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_HMFK_IDX" ON "OSE"."TYPE_MODULATEUR" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_MODULATEUR_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_MODULATEUR_HDFK_IDX" ON "OSE"."ELEMENT_MODULATEUR" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--PARAMETRE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."PARAMETRE_HCFK_IDX" ON "OSE"."PARAMETRE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--SERVICE_REFERENTIEL_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."SERVICE_REFERENTIEL_HCFK_IDX" ON "OSE"."SERVICE_REFERENTIEL" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--ETABLISSEMENT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."ETABLISSEMENT_HDFK_IDX" ON "OSE"."ETABLISSEMENT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_PERSONNEL_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_PERSONNEL_FK_IDX" ON "OSE"."AFFECTATION" ("PERSONNEL_ID");
---------------------------
--Nouveau INDEX
--TMS_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TMS_HDFK_IDX" ON "OSE"."TYPE_MODULATEUR_STRUCTURE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--MISE_EN_PAIEMENT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."MISE_EN_PAIEMENT_HDFK_IDX" ON "OSE"."MISE_EN_PAIEMENT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_FORMATION_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_FORMATION_HDFK_IDX" ON "OSE"."TYPE_FORMATION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CC_ACTIVITE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."CC_ACTIVITE_HMFK_IDX" ON "OSE"."CC_ACTIVITE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--WF_INTERVENANT_ETAPE_SFK_IDX
---------------------------
  CREATE INDEX "OSE"."WF_INTERVENANT_ETAPE_SFK_IDX" ON "OSE"."WF_INTERVENANT_ETAPE" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--STATUT_INTERVENANT_TYPE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."STATUT_INTERVENANT_TYPE_FK_IDX" ON "OSE"."STATUT_INTERVENANT" ("TYPE_INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--TYPE_AGREMENT_STATUT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_AGREMENT_STATUT_HCFK_IDX" ON "OSE"."TYPE_AGREMENT_STATUT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TIS_ANNEE_DEBUT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TIS_ANNEE_DEBUT_FK_IDX" ON "OSE"."TYPE_INTERVENTION_STRUCTURE" ("ANNEE_DEBUT_ID");
---------------------------
--Nouveau INDEX
--DOSSIER_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."DOSSIER_HCFK_IDX" ON "OSE"."DOSSIER" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--EM_ELEMENT_PEDAGOGIQUE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."EM_ELEMENT_PEDAGOGIQUE_FK_IDX" ON "OSE"."ELEMENT_MODULATEUR" ("ELEMENT_ID");
---------------------------
--Nouveau INDEX
--NOTIF_INDICATEUR_SFK_IDX
---------------------------
  CREATE INDEX "OSE"."NOTIF_INDICATEUR_SFK_IDX" ON "OSE"."NOTIFICATION_INDICATEUR" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--SERVICE_REFERENTIEL_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."SERVICE_REFERENTIEL_HMFK_IDX" ON "OSE"."SERVICE_REFERENTIEL" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_EP_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_EP_HMFK_IDX" ON "OSE"."CENTRE_COUT_EP" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--PIECE_JOINTE_FICHIER_PJFK_IDX
---------------------------
  CREATE INDEX "OSE"."PIECE_JOINTE_FICHIER_PJFK_IDX" ON "OSE"."PIECE_JOINTE_FICHIER" ("PIECE_JOINTE_ID");
---------------------------
--Nouveau INDEX
--VALIDATION_INTERVENANT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VALIDATION_INTERVENANT_FK_IDX" ON "OSE"."VALIDATION" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--FRVHR_FORMULE_RESULTAT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."FRVHR_FORMULE_RESULTAT_FK_IDX" ON "OSE"."FORMULE_RESULTAT_VH_REF" ("FORMULE_RESULTAT_ID");
---------------------------
--Nouveau INDEX
--MISE_EN_PAIEMENT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."MISE_EN_PAIEMENT_HMFK_IDX" ON "OSE"."MISE_EN_PAIEMENT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_VOLUME_HORAIRE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_VOLUME_HORAIRE_HDFK_IDX" ON "OSE"."TYPE_VOLUME_HORAIRE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--INDIC_MODIF_DOSSIER_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."INDIC_MODIF_DOSSIER_HDFK_IDX" ON "OSE"."INDIC_MODIF_DOSSIER" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--DOSSIER_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."DOSSIER_HDFK_IDX" ON "OSE"."DOSSIER" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--VHIT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VHIT_FK_IDX" ON "OSE"."VOLUME_HORAIRE" ("TYPE_INTERVENTION_ID");
---------------------------
--Nouveau INDEX
--TYPE_VOLUME_HORAIRE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_VOLUME_HORAIRE_HCFK_IDX" ON "OSE"."TYPE_VOLUME_HORAIRE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--MEP_FR_SERVICE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."MEP_FR_SERVICE_FK_IDX" ON "OSE"."MISE_EN_PAIEMENT" ("FORMULE_RES_SERVICE_ID");
---------------------------
--Nouveau INDEX
--TYPE_RESSOURCE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_RESSOURCE_HDFK_IDX" ON "OSE"."TYPE_RESSOURCE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--GROUPE_TYPE_FORMATION_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."GROUPE_TYPE_FORMATION_HDFK_IDX" ON "OSE"."GROUPE_TYPE_FORMATION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ETAPE_TYPE_FORMATION_FK_IDX
---------------------------
  CREATE INDEX "OSE"."ETAPE_TYPE_FORMATION_FK_IDX" ON "OSE"."ETAPE" ("TYPE_FORMATION_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENANT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENANT_HMFK_IDX" ON "OSE"."TYPE_INTERVENANT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ADRESSE_INTERVENANT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."ADRESSE_INTERVENANT_HDFK_IDX" ON "OSE"."ADRESSE_INTERVENANT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_HDFK_IDX" ON "OSE"."CENTRE_COUT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CCEP_CENTRE_COUT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CCEP_CENTRE_COUT_FK_IDX" ON "OSE"."CENTRE_COUT_EP" ("CENTRE_COUT_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_TAUX_REGIMES_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_TAUX_REGIMES_HMFK_IDX" ON "OSE"."ELEMENT_TAUX_REGIMES" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANTS_CIVILITES_FK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANTS_CIVILITES_FK_IDX" ON "OSE"."INTERVENANT" ("CIVILITE_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_HDFK_IDX" ON "OSE"."TYPE_INTERVENTION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--VVHR_VALIDATION_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VVHR_VALIDATION_FK_IDX" ON "OSE"."VALIDATION_VOL_HORAIRE_REF" ("VALIDATION_ID");
---------------------------
--Nouveau INDEX
--MODULATEUR_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."MODULATEUR_HMFK_IDX" ON "OSE"."MODULATEUR" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_AGREMENT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_AGREMENT_HMFK_IDX" ON "OSE"."TYPE_AGREMENT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_DOTATION_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_DOTATION_SOURCE_FK_IDX" ON "OSE"."TYPE_DOTATION" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_CENTRE_COUT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_CENTRE_COUT_FK_IDX" ON "OSE"."CENTRE_COUT" ("PARENT_ID");
---------------------------
--Nouveau INDEX
--MODULATEUR_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."MODULATEUR_HDFK_IDX" ON "OSE"."MODULATEUR" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--PAYS_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."PAYS_HMFK_IDX" ON "OSE"."PAYS" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--CORPS_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."CORPS_HCFK_IDX" ON "OSE"."CORPS" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_GRADE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_GRADE_FK_IDX" ON "OSE"."INTERVENANT" ("GRADE_ID");
---------------------------
--Nouveau INDEX
--FRS_FORMULE_RESULTAT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."FRS_FORMULE_RESULTAT_FK_IDX" ON "OSE"."FORMULE_RESULTAT_SERVICE" ("FORMULE_RESULTAT_ID");
---------------------------
--Nouveau INDEX
--VHR_TYPE_VOLUME_HORAIRE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VHR_TYPE_VOLUME_HORAIRE_FK_IDX" ON "OSE"."VOLUME_HORAIRE_REF" ("TYPE_VOLUME_HORAIRE_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_HCFK_IDX" ON "OSE"."INTERVENANT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_HDFK_IDX" ON "OSE"."INTERVENANT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--DOSSIER_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."DOSSIER_HMFK_IDX" ON "OSE"."DOSSIER" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_VOLUME_HORAIRE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_VOLUME_HORAIRE_HMFK_IDX" ON "OSE"."TYPE_VOLUME_HORAIRE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_VALIDATION_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_VALIDATION_HMFK_IDX" ON "OSE"."TYPE_VALIDATION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_HMFK_IDX" ON "OSE"."AFFECTATION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_PIECE_JOINTE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_PIECE_JOINTE_HCFK_IDX" ON "OSE"."TYPE_PIECE_JOINTE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_AGREMENT_STATUT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_AGREMENT_STATUT_HDFK_IDX" ON "OSE"."TYPE_AGREMENT_STATUT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ADRESSE_INTERVENANT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."ADRESSE_INTERVENANT_HCFK_IDX" ON "OSE"."ADRESSE_INTERVENANT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_EP_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_EP_HMFK_IDX" ON "OSE"."TYPE_MODULATEUR_EP" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_VALIDATION_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_VALIDATION_HCFK_IDX" ON "OSE"."TYPE_VALIDATION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_FORMATION_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_FORMATION_SOURCE_FK_IDX" ON "OSE"."TYPE_FORMATION" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_HMFK_IDX" ON "OSE"."INTERVENANT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_CONTRAT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_CONTRAT_FK_IDX" ON "OSE"."VOLUME_HORAIRE" ("CONTRAT_ID");
---------------------------
--Nouveau INDEX
--TAUX_HORAIRE_HETD_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TAUX_HORAIRE_HETD_HDFK_IDX" ON "OSE"."TAUX_HORAIRE_HETD" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_EP_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_EP_HMFK_IDX" ON "OSE"."TYPE_INTERVENTION_EP" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--PRIVILEGE_CATEGORIE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."PRIVILEGE_CATEGORIE_FK_IDX" ON "OSE"."PRIVILEGE" ("CATEGORIE_ID");
---------------------------
--Nouveau INDEX
--SERVICE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."SERVICE_HCFK_IDX" ON "OSE"."SERVICE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--CCEP_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CCEP_SOURCE_FK_IDX" ON "OSE"."CENTRE_COUT_EP" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--PJ_TYPE_PIECE_JOINTE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."PJ_TYPE_PIECE_JOINTE_FK_IDX" ON "OSE"."PIECE_JOINTE" ("TYPE_PIECE_JOINTE_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_PEDAGOGIQUE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_PEDAGOGIQUE_HCFK_IDX" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--GROUPE_TYPE_FORMATION_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."GROUPE_TYPE_FORMATION_HMFK_IDX" ON "OSE"."GROUPE_TYPE_FORMATION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--VVH_VOLUME_HORAIRE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VVH_VOLUME_HORAIRE_FK_IDX" ON "OSE"."VALIDATION_VOL_HORAIRE" ("VOLUME_HORAIRE_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_FICHIER_FFK_IDX
---------------------------
  CREATE INDEX "OSE"."CONTRAT_FICHIER_FFK_IDX" ON "OSE"."CONTRAT_FICHIER" ("FICHIER_ID");
---------------------------
--Nouveau INDEX
--EFFECTIFS_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."EFFECTIFS_HCFK_IDX" ON "OSE"."EFFECTIFS" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--SERVICE_INTERVENANT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."SERVICE_INTERVENANT_FK_IDX" ON "OSE"."SERVICE" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--TYPE_HEURES_TYPE_HEURES_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_HEURES_TYPE_HEURES_FK_IDX" ON "OSE"."TYPE_HEURES" ("TYPE_HEURES_ELEMENT_ID");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_EP_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_EP_HDFK_IDX" ON "OSE"."TYPE_MODULATEUR_EP" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TPJS_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TPJS_HCFK_IDX" ON "OSE"."TYPE_PIECE_JOINTE_STATUT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--ETR_ELEMENT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."ETR_ELEMENT_FK_IDX" ON "OSE"."ELEMENT_TAUX_REGIMES" ("ELEMENT_PEDAGOGIQUE_ID");
---------------------------
--Nouveau INDEX
--CC_ACTIVITE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."CC_ACTIVITE_HDFK_IDX" ON "OSE"."CC_ACTIVITE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--NOTIF_INDICATEUR_IFK_IDX
---------------------------
  CREATE INDEX "OSE"."NOTIF_INDICATEUR_IFK_IDX" ON "OSE"."NOTIFICATION_INDICATEUR" ("INDICATEUR_ID");
---------------------------
--Nouveau INDEX
--DF_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."DF_SOURCE_FK_IDX" ON "OSE"."DOMAINE_FONCTIONNEL" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--TPJS_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TPJS_HDFK_IDX" ON "OSE"."TYPE_PIECE_JOINTE_STATUT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_ACTIVITE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_ACTIVITE_FK_IDX" ON "OSE"."CENTRE_COUT" ("ACTIVITE_ID");
---------------------------
--Nouveau INDEX
--CPEP_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CPEP_FK_IDX" ON "OSE"."CHEMIN_PEDAGOGIQUE" ("ELEMENT_PEDAGOGIQUE_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_REF_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_REF_HDFK_IDX" ON "OSE"."VOLUME_HORAIRE_REF" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TIEP_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TIEP_SOURCE_FK_IDX" ON "OSE"."TYPE_INTERVENTION_EP" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--TYPE_HEURES_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_HEURES_HMFK_IDX" ON "OSE"."TYPE_HEURES" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--PARAMETRE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."PARAMETRE_HMFK_IDX" ON "OSE"."PARAMETRE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--STRUCTURE_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."STRUCTURE_SOURCE_FK_IDX" ON "OSE"."STRUCTURE" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_EP_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_EP_HDFK_IDX" ON "OSE"."TYPE_INTERVENTION_EP" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--VH_SERVICES_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VH_SERVICES_FK_IDX" ON "OSE"."VOLUME_HORAIRE" ("SERVICE_ID");
---------------------------
--Nouveau INDEX
--GRADE_CORPS_FK_IDX
---------------------------
  CREATE INDEX "OSE"."GRADE_CORPS_FK_IDX" ON "OSE"."GRADE" ("CORPS_ID");
---------------------------
--Nouveau INDEX
--VHMNP_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VHMNP_FK_IDX" ON "OSE"."VOLUME_HORAIRE" ("MOTIF_NON_PAIEMENT_ID");
---------------------------
--Nouveau INDEX
--GROUPE_TI_FK_IDX
---------------------------
  CREATE INDEX "OSE"."GROUPE_TI_FK_IDX" ON "OSE"."GROUPE" ("TYPE_INTERVENTION_ID");
---------------------------
--Nouveau INDEX
--STAT_PRIV_PRIVILEGE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."STAT_PRIV_PRIVILEGE_FK_IDX" ON "OSE"."STATUT_PRIVILEGE" ("PRIVILEGE_ID");
---------------------------
--Nouveau INDEX
--MOTIF_NON_PAIEMENT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."MOTIF_NON_PAIEMENT_HCFK_IDX" ON "OSE"."MOTIF_NON_PAIEMENT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--PERIODE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."PERIODE_HCFK_IDX" ON "OSE"."PERIODE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CONTRAT_STRUCTURE_FK_IDX" ON "OSE"."CONTRAT" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--TMS_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TMS_STRUCTURE_FK_IDX" ON "OSE"."TYPE_MODULATEUR_STRUCTURE" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--EFFECTIFS_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."EFFECTIFS_HDFK_IDX" ON "OSE"."EFFECTIFS" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CHEMIN_PEDAGOGIQUE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."CHEMIN_PEDAGOGIQUE_HCFK_IDX" ON "OSE"."CHEMIN_PEDAGOGIQUE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--FONCTION_REFERENTIEL_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."FONCTION_REFERENTIEL_HMFK_IDX" ON "OSE"."FONCTION_REFERENTIEL" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--FRSR_SR_FK_IDX
---------------------------
  CREATE INDEX "OSE"."FRSR_SR_FK_IDX" ON "OSE"."FORMULE_RESULTAT_SERVICE_REF" ("SERVICE_REFERENTIEL_ID");
---------------------------
--Nouveau INDEX
--PARAMETRE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."PARAMETRE_HDFK_IDX" ON "OSE"."PARAMETRE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENANT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENANT_HDFK_IDX" ON "OSE"."TYPE_INTERVENANT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_ENS_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_ENS_HDFK_IDX" ON "OSE"."VOLUME_HORAIRE_ENS" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--FONCTION_REFERENTIEL_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."FONCTION_REFERENTIEL_HCFK_IDX" ON "OSE"."FONCTION_REFERENTIEL" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--CCS_SOURCE_CODE_UN
---------------------------
  CREATE UNIQUE INDEX "OSE"."CCS_SOURCE_CODE_UN" ON "OSE"."CENTRE_COUT_STRUCTURE" ("SOURCE_CODE","HISTO_DESTRUCTION");
---------------------------
--Nouveau INDEX
--FRVHR_VHR_FK_IDX
---------------------------
  CREATE INDEX "OSE"."FRVHR_VHR_FK_IDX" ON "OSE"."FORMULE_RESULTAT_VH_REF" ("VOLUME_HORAIRE_REF_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_EP_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_EP_HDFK_IDX" ON "OSE"."CENTRE_COUT_EP" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--HSM_UTILISATEUR_FK_IDX
---------------------------
  CREATE INDEX "OSE"."HSM_UTILISATEUR_FK_IDX" ON "OSE"."HISTO_INTERVENANT_SERVICE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--EP_ETAPE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."EP_ETAPE_FK_IDX" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("ETAPE_ID");
---------------------------
--Nouveau INDEX
--PERSONNEL_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."PERSONNEL_HMFK_IDX" ON "OSE"."PERSONNEL" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_AGREMENT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_AGREMENT_HCFK_IDX" ON "OSE"."TYPE_AGREMENT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_R_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_R_STRUCTURE_FK_IDX" ON "OSE"."AFFECTATION_RECHERCHE" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--ETAPE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."ETAPE_HCFK_IDX" ON "OSE"."ETAPE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--SRFR_FK_IDX
---------------------------
  CREATE INDEX "OSE"."SRFR_FK_IDX" ON "OSE"."SERVICE_REFERENTIEL" ("FONCTION_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_STRUCTURE_FK_IDX" ON "OSE"."AFFECTATION" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_MODULATEUR_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_MODULATEUR_HCFK_IDX" ON "OSE"."ELEMENT_MODULATEUR" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--PAYS_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."PAYS_HCFK_IDX" ON "OSE"."PAYS" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--VALIDATION_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."VALIDATION_HMFK_IDX" ON "OSE"."VALIDATION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ROLE_PERIMETRE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."ROLE_PERIMETRE_FK_IDX" ON "OSE"."ROLE" ("PERIMETRE_ID");
---------------------------
--Nouveau INDEX
--AII_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AII_FK_IDX" ON "OSE"."ADRESSE_INTERVENANT" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--GRADE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."GRADE_HDFK_IDX" ON "OSE"."GRADE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--STRUCTURE_ETABLISSEMENT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."STRUCTURE_ETABLISSEMENT_FK_IDX" ON "OSE"."STRUCTURE" ("ETABLISSEMENT_ID");
---------------------------
--Nouveau INDEX
--TYPE_STRUCTURE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_STRUCTURE_HCFK_IDX" ON "OSE"."TYPE_STRUCTURE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--PIECE_JOINTE_FICHIER_FFK_IDX
---------------------------
  CREATE INDEX "OSE"."PIECE_JOINTE_FICHIER_FFK_IDX" ON "OSE"."PIECE_JOINTE_FICHIER" ("FICHIER_ID");
---------------------------
--Nouveau INDEX
--TYPE_AGREMENT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_AGREMENT_HDFK_IDX" ON "OSE"."TYPE_AGREMENT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ETAT_VOLUME_HORAIRE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."ETAT_VOLUME_HORAIRE_HCFK_IDX" ON "OSE"."ETAT_VOLUME_HORAIRE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--FRS_SERVICE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."FRS_SERVICE_FK_IDX" ON "OSE"."FORMULE_RESULTAT_SERVICE" ("SERVICE_ID");
---------------------------
--Modifié INDEX
--AGREMENT__UN
---------------------------
DROP INDEX "OSE"."AGREMENT__UN";
  CREATE UNIQUE INDEX "OSE"."AGREMENT__UN" ON "OSE"."AGREMENT" ("TYPE_AGREMENT_ID","INTERVENANT_ID","STRUCTURE_ID","HISTO_DESTRUCTION");
---------------------------
--Nouveau INDEX
--ST_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."ST_STRUCTURE_FK_IDX" ON "OSE"."STRUCTURE" ("TYPE_ID");
---------------------------
--Nouveau INDEX
--VH_TYPE_VOLUME_HORAIRE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VH_TYPE_VOLUME_HORAIRE_FK_IDX" ON "OSE"."VOLUME_HORAIRE" ("TYPE_VOLUME_HORAIRE_ID");
---------------------------
--Nouveau INDEX
--DOTATION_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."DOTATION_HMFK_IDX" ON "OSE"."DOTATION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--MODULATEUR_TM_FK_IDX
---------------------------
  CREATE INDEX "OSE"."MODULATEUR_TM_FK_IDX" ON "OSE"."MODULATEUR" ("TYPE_MODULATEUR_ID");
---------------------------
--Nouveau INDEX
--VHENS_TYPE_INTERVENTION_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VHENS_TYPE_INTERVENTION_FK_IDX" ON "OSE"."VOLUME_HORAIRE_ENS" ("TYPE_INTERVENTION_ID");
---------------------------
--Nouveau INDEX
--ETAT_VOLUME_HORAIRE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."ETAT_VOLUME_HORAIRE_HDFK_IDX" ON "OSE"."ETAT_VOLUME_HORAIRE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_R_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_R_SOURCE_FK_IDX" ON "OSE"."AFFECTATION_RECHERCHE" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--ROLE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."ROLE_HCFK_IDX" ON "OSE"."ROLE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--INDIC_MODIF_DOSSIER_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."INDIC_MODIF_DOSSIER_HMFK_IDX" ON "OSE"."INDIC_MODIF_DOSSIER" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--CORPS_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CORPS_SOURCE_FK_IDX" ON "OSE"."CORPS" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--RP_PRIVILEGE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."RP_PRIVILEGE_FK_IDX" ON "OSE"."ROLE_PRIVILEGE" ("PRIVILEGE_ID");
---------------------------
--Nouveau INDEX
--MSD_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."MSD_HMFK_IDX" ON "OSE"."MODIFICATION_SERVICE_DU" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--MEP_PERIODE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."MEP_PERIODE_FK_IDX" ON "OSE"."MISE_EN_PAIEMENT" ("PERIODE_PAIEMENT_ID");
---------------------------
--Nouveau INDEX
--TME_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TME_SOURCE_FK_IDX" ON "OSE"."TYPE_MODULATEUR_EP" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--VALIDATION_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VALIDATION_STRUCTURE_FK_IDX" ON "OSE"."VALIDATION" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--AI_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AI_SOURCE_FK_IDX" ON "OSE"."ADRESSE_INTERVENANT" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--TMS_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TMS_HMFK_IDX" ON "OSE"."TYPE_MODULATEUR_STRUCTURE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_STATUT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_STATUT_FK_IDX" ON "OSE"."INTERVENANT" ("STATUT_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_TAUX_REGIMES_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_TAUX_REGIMES_HDFK_IDX" ON "OSE"."ELEMENT_TAUX_REGIMES" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--VVHR_VOLUME_HORAIRE_REF_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VVHR_VOLUME_HORAIRE_REF_FK_IDX" ON "OSE"."VALIDATION_VOL_HORAIRE_REF" ("VOLUME_HORAIRE_REF_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_EP_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_EP_HCFK_IDX" ON "OSE"."TYPE_INTERVENTION_EP" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_SOURCE_FK_IDX" ON "OSE"."INTERVENANT" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--DOSSIER_INTERVENANT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."DOSSIER_INTERVENANT_FK_IDX" ON "OSE"."DOSSIER" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--TYPE_DOTATION_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_DOTATION_HDFK_IDX" ON "OSE"."TYPE_DOTATION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ROLE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."ROLE_HDFK_IDX" ON "OSE"."ROLE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CCEP_EP_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CCEP_EP_FK_IDX" ON "OSE"."CENTRE_COUT_EP" ("ELEMENT_PEDAGOGIQUE_ID");
---------------------------
--Nouveau INDEX
--CORPS_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."CORPS_HMFK_IDX" ON "OSE"."CORPS" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ROLE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."ROLE_HMFK_IDX" ON "OSE"."ROLE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--DOTATION__UN
---------------------------
  CREATE UNIQUE INDEX "OSE"."DOTATION__UN" ON "OSE"."DOTATION" ("TYPE_RESSOURCE_ID","ANNEE_ID","ANNEE_CIVILE","STRUCTURE_ID","LIBELLE","HISTO_DESTRUCTION");
---------------------------
--Nouveau INDEX
--AGREMENT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."AGREMENT_HMFK_IDX" ON "OSE"."AGREMENT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--FONCTION_REFERENTIEL_SFK_IDX
---------------------------
  CREATE INDEX "OSE"."FONCTION_REFERENTIEL_SFK_IDX" ON "OSE"."FONCTION_REFERENTIEL" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--EPS_FK_IDX
---------------------------
  CREATE INDEX "OSE"."EPS_FK_IDX" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--PERIODE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."PERIODE_HDFK_IDX" ON "OSE"."PERIODE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--GTYPE_FORMATION_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."GTYPE_FORMATION_SOURCE_FK_IDX" ON "OSE"."GROUPE_TYPE_FORMATION" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--SI_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."SI_SOURCE_FK_IDX" ON "OSE"."STATUT_INTERVENANT" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--MSD_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."MSD_HDFK_IDX" ON "OSE"."MODIFICATION_SERVICE_DU" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CP_ETAPE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CP_ETAPE_FK_IDX" ON "OSE"."CHEMIN_PEDAGOGIQUE" ("ETAPE_ID");
---------------------------
--Nouveau INDEX
--STRUCTURES_STRUCTURES_FK_IDX
---------------------------
  CREATE INDEX "OSE"."STRUCTURES_STRUCTURES_FK_IDX" ON "OSE"."STRUCTURE" ("PARENTE_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_HCFK_IDX" ON "OSE"."AFFECTATION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--FICHIER_VALID_FK_IDX
---------------------------
  CREATE INDEX "OSE"."FICHIER_VALID_FK_IDX" ON "OSE"."FICHIER" ("VALIDATION_ID");
---------------------------
--Nouveau INDEX
--ADRESSE_INTERVENANT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."ADRESSE_INTERVENANT_HMFK_IDX" ON "OSE"."ADRESSE_INTERVENANT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ETR_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."ETR_SOURCE_FK_IDX" ON "OSE"."ELEMENT_TAUX_REGIMES" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--PERSONNEL_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."PERSONNEL_HCFK_IDX" ON "OSE"."PERSONNEL" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--PERIODE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."PERIODE_HMFK_IDX" ON "OSE"."PERIODE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ETAPE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."ETAPE_HMFK_IDX" ON "OSE"."ETAPE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TIS_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TIS_HCFK_IDX" ON "OSE"."TYPE_INTERVENTION_STRUCTURE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_PEDAGOGIQUE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_PEDAGOGIQUE_HDFK_IDX" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--STATUT_INTERVENANT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."STATUT_INTERVENANT_HMFK_IDX" ON "OSE"."STATUT_INTERVENANT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TAUX_HORAIRE_HETD_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TAUX_HORAIRE_HETD_HMFK_IDX" ON "OSE"."TAUX_HORAIRE_HETD" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_ROLE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_ROLE_FK_IDX" ON "OSE"."AFFECTATION" ("ROLE_ID");
---------------------------
--Nouveau INDEX
--FICHIER_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."FICHIER_HCFK_IDX" ON "OSE"."FICHIER" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--EP_ANNEE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."EP_ANNEE_FK_IDX" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("ANNEE_ID");
---------------------------
--Nouveau INDEX
--TYPE_STRUCTURE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_STRUCTURE_HMFK_IDX" ON "OSE"."TYPE_STRUCTURE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--FONCTION_REFERENTIEL_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."FONCTION_REFERENTIEL_HDFK_IDX" ON "OSE"."FONCTION_REFERENTIEL" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--PERSONNEL_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."PERSONNEL_STRUCTURE_FK_IDX" ON "OSE"."PERSONNEL" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--GROUPE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."GROUPE_HMFK_IDX" ON "OSE"."GROUPE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_HMFK_IDX" ON "OSE"."CENTRE_COUT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--MMSD_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."MMSD_HCFK_IDX" ON "OSE"."MOTIF_MODIFICATION_SERVICE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--GROUPE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."GROUPE_HCFK_IDX" ON "OSE"."GROUPE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--EFFECTIFS_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."EFFECTIFS_SOURCE_FK_IDX" ON "OSE"."EFFECTIFS" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--STRUCTURE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."STRUCTURE_HMFK_IDX" ON "OSE"."STRUCTURE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--AGREMENT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."AGREMENT_HDFK_IDX" ON "OSE"."AGREMENT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_ANNEE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_ANNEE_FK_IDX" ON "OSE"."INTERVENANT" ("ANNEE_ID");
---------------------------
--Nouveau INDEX
--FRVH_VOLUME_HORAIRE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."FRVH_VOLUME_HORAIRE_FK_IDX" ON "OSE"."FORMULE_RESULTAT_VH" ("VOLUME_HORAIRE_ID");
---------------------------
--Nouveau INDEX
--TYPE_HEURES_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_HEURES_HDFK_IDX" ON "OSE"."TYPE_HEURES" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--MSD_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."MSD_HCFK_IDX" ON "OSE"."MODIFICATION_SERVICE_DU" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--MEP_TYPE_HEURES_FK_IDX
---------------------------
  CREATE INDEX "OSE"."MEP_TYPE_HEURES_FK_IDX" ON "OSE"."MISE_EN_PAIEMENT" ("TYPE_HEURES_ID");
---------------------------
--Nouveau INDEX
--TPJS_TYPE_PIECE_JOINTE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TPJS_TYPE_PIECE_JOINTE_FK_IDX" ON "OSE"."TYPE_PIECE_JOINTE_STATUT" ("TYPE_PIECE_JOINTE_ID");
---------------------------
--Nouveau INDEX
--EP_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."EP_SOURCE_FK_IDX" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_STRUCTURE_PK
---------------------------
  CREATE UNIQUE INDEX "OSE"."CENTRE_COUT_STRUCTURE_PK" ON "OSE"."CENTRE_COUT_STRUCTURE" ("ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_R_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_R_HDFK_IDX" ON "OSE"."AFFECTATION_RECHERCHE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_TYPE_CONTRAT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CONTRAT_TYPE_CONTRAT_FK_IDX" ON "OSE"."CONTRAT" ("TYPE_CONTRAT_ID");
---------------------------
--Nouveau INDEX
--MMSD_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."MMSD_HDFK_IDX" ON "OSE"."MOTIF_MODIFICATION_SERVICE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_CONTRAT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_CONTRAT_HDFK_IDX" ON "OSE"."TYPE_CONTRAT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--PJ_DOSSIER_FK_IDX
---------------------------
  CREATE INDEX "OSE"."PJ_DOSSIER_FK_IDX" ON "OSE"."PIECE_JOINTE" ("DOSSIER_ID");
---------------------------
--Nouveau INDEX
--DOTATION_ANNEE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."DOTATION_ANNEE_FK_IDX" ON "OSE"."DOTATION" ("ANNEE_ID");
---------------------------
--Nouveau INDEX
--DISCIPLINE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."DISCIPLINE_HCFK_IDX" ON "OSE"."DISCIPLINE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_REF_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_REF_HCFK_IDX" ON "OSE"."VOLUME_HORAIRE_REF" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--VHE_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VHE_SOURCE_FK_IDX" ON "OSE"."VOLUME_HORAIRE_ENS" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_R_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_R_HCFK_IDX" ON "OSE"."AFFECTATION_RECHERCHE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--PIECE_JOINTE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."PIECE_JOINTE_HCFK_IDX" ON "OSE"."PIECE_JOINTE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--PIECE_JOINTE_VFK_IDX
---------------------------
  CREATE INDEX "OSE"."PIECE_JOINTE_VFK_IDX" ON "OSE"."PIECE_JOINTE" ("VALIDATION_ID");
---------------------------
--Nouveau INDEX
--INDIC_MODIF_DOSSIER_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."INDIC_MODIF_DOSSIER_HCFK_IDX" ON "OSE"."INDIC_MODIF_DOSSIER" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_EP_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_EP_HCFK_IDX" ON "OSE"."CENTRE_COUT_EP" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TMS_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TMS_HCFK_IDX" ON "OSE"."TYPE_MODULATEUR_STRUCTURE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--CHEMIN_PEDAGOGIQUE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."CHEMIN_PEDAGOGIQUE_HMFK_IDX" ON "OSE"."CHEMIN_PEDAGOGIQUE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--FRR_FORMULE_RESULTAT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."FRR_FORMULE_RESULTAT_FK_IDX" ON "OSE"."FORMULE_RESULTAT_SERVICE_REF" ("FORMULE_RESULTAT_ID");
---------------------------
--Nouveau INDEX
--AR_INTERVENANT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AR_INTERVENANT_FK_IDX" ON "OSE"."AFFECTATION_RECHERCHE" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--DOTATION_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."DOTATION_HDFK_IDX" ON "OSE"."DOTATION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--PIECE_JOINTE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."PIECE_JOINTE_HDFK_IDX" ON "OSE"."PIECE_JOINTE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--SR_INTERVENANT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."SR_INTERVENANT_FK_IDX" ON "OSE"."SERVICE_REFERENTIEL" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENANT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENANT_HCFK_IDX" ON "OSE"."TYPE_INTERVENANT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_FORMATION_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_FORMATION_HMFK_IDX" ON "OSE"."TYPE_FORMATION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--MMSD_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."MMSD_HMFK_IDX" ON "OSE"."MOTIF_MODIFICATION_SERVICE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--STRUCTURE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."STRUCTURE_HDFK_IDX" ON "OSE"."STRUCTURE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--FONC_REF_DOMAINE_FONCT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."FONC_REF_DOMAINE_FONCT_FK_IDX" ON "OSE"."FONCTION_REFERENTIEL" ("DOMAINE_FONCTIONNEL_ID");
---------------------------
--Nouveau INDEX
--TYPE_DOTATION_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_DOTATION_HMFK_IDX" ON "OSE"."TYPE_DOTATION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--DOMAINE_FONCTIONNEL_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."DOMAINE_FONCTIONNEL_HMFK_IDX" ON "OSE"."DOMAINE_FONCTIONNEL" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TMS_TYPE_MODUL_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TMS_TYPE_MODUL_FK_IDX" ON "OSE"."TYPE_MODULATEUR_STRUCTURE" ("TYPE_MODULATEUR_ID");
---------------------------
--Nouveau INDEX
--EFFECTIFS_ELEMENT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."EFFECTIFS_ELEMENT_FK_IDX" ON "OSE"."EFFECTIFS" ("ELEMENT_PEDAGOGIQUE_ID");
---------------------------
--Nouveau INDEX
--DOTATION_TYPE_DOTATION_FK_IDX
---------------------------
  CREATE INDEX "OSE"."DOTATION_TYPE_DOTATION_FK_IDX" ON "OSE"."DOTATION" ("TYPE_RESSOURCE_ID");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_HDFK_IDX" ON "OSE"."TYPE_MODULATEUR" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ETAT_VOLUME_HORAIRE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."ETAT_VOLUME_HORAIRE_HMFK_IDX" ON "OSE"."ETAT_VOLUME_HORAIRE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--AS_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AS_STRUCTURE_FK_IDX" ON "OSE"."ADRESSE_STRUCTURE" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--CC_ACTIVITE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."CC_ACTIVITE_HCFK_IDX" ON "OSE"."CC_ACTIVITE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_PIECE_JOINTE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_PIECE_JOINTE_HDFK_IDX" ON "OSE"."TYPE_PIECE_JOINTE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TAUX_HORAIRE_HETD_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TAUX_HORAIRE_HETD_HCFK_IDX" ON "OSE"."TAUX_HORAIRE_HETD" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--MEP_FR_SERVICE_REF_FK_IDX
---------------------------
  CREATE INDEX "OSE"."MEP_FR_SERVICE_REF_FK_IDX" ON "OSE"."MISE_EN_PAIEMENT" ("FORMULE_RES_SERVICE_REF_ID");
---------------------------
--Nouveau INDEX
--DISCIPLINE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."DISCIPLINE_HMFK_IDX" ON "OSE"."DISCIPLINE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--MOTIF_NON_PAIEMENT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."MOTIF_NON_PAIEMENT_HMFK_IDX" ON "OSE"."MOTIF_NON_PAIEMENT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--FICHIER_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."FICHIER_HDFK_IDX" ON "OSE"."FICHIER" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TAS_TYPE_AGREMENT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TAS_TYPE_AGREMENT_FK_IDX" ON "OSE"."TYPE_AGREMENT_STATUT" ("TYPE_AGREMENT_ID");
---------------------------
--Nouveau INDEX
--SERVICE_REFERENTIEL_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."SERVICE_REFERENTIEL_HDFK_IDX" ON "OSE"."SERVICE_REFERENTIEL" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--VALIDATION_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."VALIDATION_HCFK_IDX" ON "OSE"."VALIDATION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TMS_ANNEE_FIN_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TMS_ANNEE_FIN_FK_IDX" ON "OSE"."TYPE_MODULATEUR_STRUCTURE" ("ANNEE_FIN_ID");
---------------------------
--Nouveau INDEX
--ETABLISSEMENT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."ETABLISSEMENT_HCFK_IDX" ON "OSE"."ETABLISSEMENT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--VHR_SERVICE_REFERENTIEL_FK_IDX
---------------------------
  CREATE INDEX "OSE"."VHR_SERVICE_REFERENTIEL_FK_IDX" ON "OSE"."VOLUME_HORAIRE_REF" ("SERVICE_REFERENTIEL_ID");
---------------------------
--Nouveau INDEX
--MOTIF_NON_PAIEMENT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."MOTIF_NON_PAIEMENT_HDFK_IDX" ON "OSE"."MOTIF_NON_PAIEMENT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--STRUCTURE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."STRUCTURE_HCFK_IDX" ON "OSE"."STRUCTURE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_SOURCE_FK_IDX" ON "OSE"."AFFECTATION" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_EP_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_EP_HCFK_IDX" ON "OSE"."TYPE_MODULATEUR_EP" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--CHEMIN_PEDAGOGIQUE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."CHEMIN_PEDAGOGIQUE_HDFK_IDX" ON "OSE"."CHEMIN_PEDAGOGIQUE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_STRUCTURE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_STRUCTURE_HDFK_IDX" ON "OSE"."TYPE_STRUCTURE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--STRUCTURE_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."STRUCTURE_STRUCTURE_FK_IDX" ON "OSE"."STRUCTURE" ("STRUCTURE_NIV2_ID");
---------------------------
--Nouveau INDEX
--TD_TYPE_RESSOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TD_TYPE_RESSOURCE_FK_IDX" ON "OSE"."TYPE_DOTATION" ("TYPE_RESSOURCE_ID");
---------------------------
--Nouveau INDEX
--MSD_INTERVENANT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."MSD_INTERVENANT_FK_IDX" ON "OSE"."MODIFICATION_SERVICE_DU" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--AGREMENT_STRUCTURE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AGREMENT_STRUCTURE_FK_IDX" ON "OSE"."AGREMENT" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--TIS_ANNEE_FIN_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TIS_ANNEE_FIN_FK_IDX" ON "OSE"."TYPE_INTERVENTION_STRUCTURE" ("ANNEE_FIN_ID");
---------------------------
--Nouveau INDEX
--TYPE_PIECE_JOINTE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_PIECE_JOINTE_HMFK_IDX" ON "OSE"."TYPE_PIECE_JOINTE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ADRESSE_STRUCTURE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."ADRESSE_STRUCTURE_HDFK_IDX" ON "OSE"."ADRESSE_STRUCTURE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--PERSONNEL_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."PERSONNEL_HDFK_IDX" ON "OSE"."PERSONNEL" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_REF_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_REF_HMFK_IDX" ON "OSE"."VOLUME_HORAIRE_REF" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ADRESSE_STRUCTURE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."ADRESSE_STRUCTURE_HMFK_IDX" ON "OSE"."ADRESSE_STRUCTURE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--HSM_INTERVENANT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."HSM_INTERVENANT_FK_IDX" ON "OSE"."HISTO_INTERVENANT_SERVICE" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--CORPS_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."CORPS_HDFK_IDX" ON "OSE"."CORPS" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--GROUPE_TYPE_FORMATION_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."GROUPE_TYPE_FORMATION_HCFK_IDX" ON "OSE"."GROUPE_TYPE_FORMATION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_SOURCE_FK_IDX" ON "OSE"."CENTRE_COUT" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--TYPE_RESSOURCE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_RESSOURCE_HMFK_IDX" ON "OSE"."TYPE_RESSOURCE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_CONTRAT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_CONTRAT_HMFK_IDX" ON "OSE"."TYPE_CONTRAT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--GROUPE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."GROUPE_HDFK_IDX" ON "OSE"."GROUPE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--STATUT_INTERVENANT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."STATUT_INTERVENANT_HCFK_IDX" ON "OSE"."STATUT_INTERVENANT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--DEPARTEMENT_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."DEPARTEMENT_HDFK_IDX" ON "OSE"."DEPARTEMENT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TAS_STATUT_INTERVENANT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TAS_STATUT_INTERVENANT_FK_IDX" ON "OSE"."TYPE_AGREMENT_STATUT" ("STATUT_INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_HCFK_IDX" ON "OSE"."VOLUME_HORAIRE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--FRES_TVH_FK_IDX
---------------------------
  CREATE INDEX "OSE"."FRES_TVH_FK_IDX" ON "OSE"."FORMULE_RESULTAT" ("TYPE_VOLUME_HORAIRE_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_TAUX_REGIMES_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."ELEMENT_TAUX_REGIMES_HCFK_IDX" ON "OSE"."ELEMENT_TAUX_REGIMES" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TIS_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."TIS_HDFK_IDX" ON "OSE"."TYPE_INTERVENTION_STRUCTURE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ROLE_PRIVILEGE_ROLE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."ROLE_PRIVILEGE_ROLE_FK_IDX" ON "OSE"."ROLE_PRIVILEGE" ("ROLE_ID");
---------------------------
--Nouveau INDEX
--AGREMENT_TYPE_AGREMENT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AGREMENT_TYPE_AGREMENT_FK_IDX" ON "OSE"."AGREMENT" ("TYPE_AGREMENT_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_HMFK_IDX" ON "OSE"."VOLUME_HORAIRE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--PERSONNEL_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."PERSONNEL_SOURCE_FK_IDX" ON "OSE"."PERSONNEL" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--DOMAINE_FONCTIONNEL_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."DOMAINE_FONCTIONNEL_HCFK_IDX" ON "OSE"."DOMAINE_FONCTIONNEL" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--HSM_TYPE_VOLUME_HORAIRE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."HSM_TYPE_VOLUME_HORAIRE_FK_IDX" ON "OSE"."HISTO_INTERVENANT_SERVICE" ("TYPE_VOLUME_HORAIRE_ID");
---------------------------
--Nouveau INDEX
--TYPE_AGREMENT_STATUT_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_AGREMENT_STATUT_HMFK_IDX" ON "OSE"."TYPE_AGREMENT_STATUT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TME_ELEMENT_PEDAGOGIQUE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TME_ELEMENT_PEDAGOGIQUE_FK_IDX" ON "OSE"."TYPE_MODULATEUR_EP" ("ELEMENT_PEDAGOGIQUE_ID");
---------------------------
--Nouveau INDEX
--ETABLISSEMENT_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."ETABLISSEMENT_SOURCE_FK_IDX" ON "OSE"."ETABLISSEMENT" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_CONTRAT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."CONTRAT_CONTRAT_FK_IDX" ON "OSE"."CONTRAT" ("CONTRAT_ID");
---------------------------
--Nouveau INDEX
--TYPE_FORMATION_GROUPE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_FORMATION_GROUPE_FK_IDX" ON "OSE"."TYPE_FORMATION" ("GROUPE_ID");
---------------------------
--Nouveau INDEX
--SERVICE_HMFK_IDX
---------------------------
  CREATE INDEX "OSE"."SERVICE_HMFK_IDX" ON "OSE"."SERVICE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--EM_MODULATEUR_FK_IDX
---------------------------
  CREATE INDEX "OSE"."EM_MODULATEUR_FK_IDX" ON "OSE"."ELEMENT_MODULATEUR" ("MODULATEUR_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_HCFK_IDX" ON "OSE"."CENTRE_COUT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TIEP_EP_FK_IDX
---------------------------
  CREATE INDEX "OSE"."TIEP_EP_FK_IDX" ON "OSE"."TYPE_INTERVENTION_EP" ("ELEMENT_PEDAGOGIQUE_ID");
---------------------------
--Nouveau INDEX
--CCS_CC_S__UN
---------------------------
  CREATE UNIQUE INDEX "OSE"."CCS_CC_S__UN" ON "OSE"."CENTRE_COUT_STRUCTURE" ("CENTRE_COUT_ID","STRUCTURE_ID","HISTO_DESTRUCTION");
---------------------------
--Nouveau INDEX
--AGREMENT_INTERVENANT_FK_IDX
---------------------------
  CREATE INDEX "OSE"."AGREMENT_INTERVENANT_FK_IDX" ON "OSE"."AGREMENT" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--TYPE_HEURES_HCFK_IDX
---------------------------
  CREATE INDEX "OSE"."TYPE_HEURES_HCFK_IDX" ON "OSE"."TYPE_HEURES" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--SERVICE_HDFK_IDX
---------------------------
  CREATE INDEX "OSE"."SERVICE_HDFK_IDX" ON "OSE"."SERVICE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ETAPE_DF_FK_IDX
---------------------------
  CREATE INDEX "OSE"."ETAPE_DF_FK_IDX" ON "OSE"."ETAPE" ("DOMAINE_FONCTIONNEL_ID");
---------------------------
--Nouveau INDEX
--DISCIPLINE_SOURCE_FK_IDX
---------------------------
  CREATE INDEX "OSE"."DISCIPLINE_SOURCE_FK_IDX" ON "OSE"."DISCIPLINE" ("SOURCE_ID");
---------------------------
--Modifié TRIGGER
--WF_TRG_AGREMENT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_AGREMENT"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."AGREMENT"
  REFERENCING FOR EACH ROW
  BEGIN
  ose_workflow.add_intervenant_to_update (CASE WHEN deleting THEN :OLD.intervenant_id ELSE :NEW.intervenant_id END); 
END;
/
---------------------------
--Modifié TRIGGER
--F_ELEMENT_PEDAGOGIQUE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_ELEMENT_PEDAGOGIQUE"
  AFTER DELETE OR UPDATE OF ID, STRUCTURE_ID, PERIODE_ID, TAUX_FI, TAUX_FC, TAUX_FA, TAUX_FOAD, FI, FC, FA, HISTO_CREATION, HISTO_DESTRUCTION, ANNEE_ID ON "OSE"."ELEMENT_PEDAGOGIQUE"
  REFERENCING FOR EACH ROW
  BEGIN FOR p IN
    ( SELECT DISTINCT s.intervenant_id
    FROM service s
    WHERE (s.element_pedagogique_id = :NEW.id
    OR s.element_pedagogique_id     = :OLD.id)
    AND 1                           = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
    ) LOOP OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id );
END LOOP;
END;
/
---------------------------
--Modifié TRIGGER
--F_CONTRAT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_CONTRAT"
  AFTER DELETE OR UPDATE OF INTERVENANT_ID, STRUCTURE_ID, VALIDATION_ID, DATE_RETOUR_SIGNE, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."CONTRAT"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      volume_horaire vh
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
    WHERE
      1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      AND (vh.contrat_id = :OLD.id OR vh.contrat_id = :NEW.id)

  ) LOOP

    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id );

  END LOOP;

END;
/
---------------------------
--Modifié PACKAGE
--OSE_WORKFLOW
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_WORKFLOW" AS 

  PROCEDURE add_intervenant_to_update         (p_intervenant_id NUMERIC);
  PROCEDURE update_intervenant_etapes         (p_intervenant_id NUMERIC);
  PROCEDURE update_intervenants_etapes;
  PROCEDURE update_all_intervenants_etapes    (p_annee_id NUMERIC DEFAULT 2015);
--  PROCEDURE Process_Intervenant_Etape (p_intervenant_id NUMERIC) ;
  
  TYPE T_LIST_STRUCTURE_ID IS TABLE OF NUMBER INDEX BY PLS_INTEGER;

  -- liste d'ids de structures
  l_structures_ids T_LIST_STRUCTURE_ID;
  
  --
  -- Fetch des ids des structures d'intervention (enseignement)
  --
  PROCEDURE fetch_struct_ens_ids_tvh          (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC);
  PROCEDURE fetch_struct_ens_ids              (p_intervenant_id NUMERIC);
  PROCEDURE fetch_struct_ens_realise_ids      (p_intervenant_id NUMERIC);
  
  --
  -- Fetch des ids des structures d'intervention (référentiel)
  --
  PROCEDURE fetch_struct_ref_ids_tvh          (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC);
  PROCEDURE fetch_struct_ref_ids              (p_intervenant_id NUMERIC);
  PROCEDURE fetch_struct_ref_realise_ids      (p_intervenant_id NUMERIC);
  
  --
  -- Fetch des ids des structures d'intervention (enseignement + référentiel)
  --
  PROCEDURE fetch_struct_ensref_ids          (p_intervenant_id NUMERIC);
  PROCEDURE fetch_struct_ensref_realis_ids   (p_intervenant_id NUMERIC);
  
    
  
  
  --------------------------------------------------------------------------------------------------------------------------
  -- Règles de pertinence et de franchissement des étapes
  --------------------------------------------------------------------------------------------------------------------------
  --
  -- Données personnelles
  --
  FUNCTION peut_saisir_dossier                (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_dossier                    (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION dossier_valide                     (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  --
  -- Enseignements
  --  
  FUNCTION peut_saisir_service                (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  FUNCTION possede_services_tvh               (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_services                   (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_services_realises          (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  FUNCTION service_valide_tvh                 (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION service_valide                     (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION service_realise_valide             (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  FUNCTION peut_cloturer_realise              (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION realise_cloture                    (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  --
  -- Référentiel
  --
  FUNCTION peut_saisir_referentiel            (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  FUNCTION possede_referentiel_tvh            (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_referentiel                (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_referentiel_realise        (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  FUNCTION referentiel_valide_tvh             (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION referentiel_valide                 (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION referentiel_realise_valide         (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  --
  -- Pièces justificatives
  --
  FUNCTION peut_saisir_pj                     (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION peut_valider_pj                    (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  FUNCTION pj_oblig_fournies                  (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION pj_oblig_validees                  (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  --
  -- Agréments
  --
  FUNCTION necessite_agrement_cr              (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION necessite_agrement_ca              (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  FUNCTION agrement_cr_fourni                 (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION agrement_ca_fourni                 (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  --
  -- Contrat / avenant
  --
  FUNCTION necessite_contrat                  (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_contrat                    (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  --
  -- Paiement
  --
  FUNCTION peut_demander_mep                  (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_demande_mep                (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION peut_saisir_mep                    (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_mep                        (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;

END OSE_WORKFLOW;
/
---------------------------
--Modifié PACKAGE
--OSE_IMPORT
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_IMPORT" IS
 
  PROCEDURE set_current_user(p_current_user IN INTEGER);
  FUNCTION get_current_user return INTEGER;

  FUNCTION get_current_annee RETURN INTEGER;
  PROCEDURE set_current_annee (p_current_annee INTEGER);

  FUNCTION get_sql_criterion( table_name varchar2, sql_criterion VARCHAR2 ) RETURN CLOB;

  PROCEDURE SYNC_LOG( message CLOB, table_name VARCHAR2 DEFAULT NULL, source_code VARCHAR2 DEFAULT NULL );
  PROCEDURE REFRESH_MVS;
  PROCEDURE SYNC_TABLES;
  PROCEDURE SYNCHRONISATION;

  -- AUTOMATIC GENERATION --

  PROCEDURE MAJ_GROUPE_TYPE_FORMATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_TYPE_FORMATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_PERSONNEL(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ADRESSE_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_AFFECTATION_RECHERCHE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_CORPS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ADRESSE_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_CHEMIN_PEDAGOGIQUE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ETABLISSEMENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ELEMENT_PEDAGOGIQUE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_CENTRE_COUT_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_TYPE_INTERVENTION_EP(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_DEPARTEMENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ETAPE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_PAYS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_GRADE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_AFFECTATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_TYPE_MODULATEUR_EP(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ELEMENT_TAUX_REGIMES(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_EFFECTIFS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_DOMAINE_FONCTIONNEL(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_CENTRE_COUT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');

  -- END OF AUTOMATIC GENERATION --
END ose_import;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_WORKFLOW
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_WORKFLOW" AS

  --------------------------------------------------------------------------------------------------------------------------
  -- Moteur du workflow.
  --------------------------------------------------------------------------------------------------------------------------
  
  /**
   * Inscription de l'intervenant dont il faudra regénérer la progression dans le workflow.
   */
  PROCEDURE Add_Intervenant_To_Update (p_intervenant_id NUMERIC)
  IS
  BEGIN 
    MERGE INTO wf_tmp_intervenant t USING dual ON (t.intervenant_id = p_intervenant_id) WHEN NOT MATCHED THEN INSERT (INTERVENANT_ID) VALUES (p_intervenant_id);
  END;
  
  /**
   * Parcours des intervenants dont il faut regénérer la progression dans le workflow.
   */
  PROCEDURE Update_Intervenants_Etapes 
  IS
  BEGIN
    FOR ti IN (SELECT distinct * FROM wf_tmp_intervenant) LOOP
      --DBMS_OUTPUT.put_line ('wf_tmp_intervenant.intervenant_id = '||ti.intervenant_id);
      ose_workflow.Update_Intervenant_Etapes(ti.intervenant_id);
    END LOOP;
    DELETE FROM wf_tmp_intervenant;
  END;
  
  /**
   * Regénère la progression dans le workflow de tous les intervenants dont le statut autorise la saisie de service.
   */
  PROCEDURE Update_All_Intervenants_Etapes (p_annee_id NUMERIC DEFAULT 2015)
  IS
    CURSOR intervenant_cur IS 
      SELECT i.* FROM intervenant i 
      JOIN statut_intervenant si ON si.id = i.statut_id AND 1 = ose_divers.comprise_entre(si.histo_creation, si.histo_destruction) AND si.peut_saisir_service = 1
      WHERE i.annee_id = p_annee_id AND 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction);
  BEGIN
    FOR intervenant_rec IN intervenant_cur
    LOOP
      --DBMS_OUTPUT.put_line (intervenant_rec.nom_usuel || '(' || intervenant_rec.source_code || ')');
      ose_workflow.Update_Intervenant_Etapes(intervenant_rec.id);
    END LOOP;
  END;
  
  
  /**
   * Regénère la progression complète dans le workflow d'un intervenant.
   */
  PROCEDURE Update_Intervenant_Etapes (p_intervenant_id NUMERIC) 
  IS
    v_annee_id NUMERIC;
    structures_ids T_LIST_STRUCTURE_ID;
    structure_id NUMERIC;
    pertinente NUMERIC;
    franchie NUMERIC;
    atteignable NUMERIC;
    non_franchies NUMERIC;
    courante NUMERIC;
    courante_trouvee NUMERIC := 0;
    ordre NUMERIC := 1;
    exist_etapes NUMERIC;
    teste NUMERIC;
  BEGIN
    --
    -- Année concernée.
    --
    select i.annee_id into v_annee_id from intervenant i where i.id = p_intervenant_id;
    
    --
    -- Création si besoin des étapes pour l'année concernée.
    --
    select count(*) into exist_etapes from wf_etape where annee_id = v_annee_id;
    if exist_etapes = 0 then
      insert into WF_ETAPE (ID,CODE,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURE_DEPENDANT,ORDRE,STRUCTURES_IDS_FUNC,ANNEE_ID)
        select wf_etape_id_seq.nextval, CODE,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURE_DEPENDANT,ORDRE,STRUCTURES_IDS_FUNC, v_annee_id from WF_ETAPE;
    end if;
    
    --
    -- RAZ progression.
    --
    DELETE FROM wf_intervenant_etape ie WHERE ie.intervenant_id = p_intervenant_id;
    
    --
    -- Parcours des étapes de l'année concernée.
    --
    FOR etape_rec IN ( select * from wf_etape where annee_id = v_annee_id and code <> 'DEBUT' and code <> 'FIN' order by ordre )
    LOOP
      --
      -- Si l'étape n'est pas pertinente, on passe à la suivante.
      --
      pertinente := 0;
      IF etape_rec.PERTIN_FUNC IS NULL THEN
        pertinente := 1;
      ELSE
        EXECUTE IMMEDIATE 'BEGIN :res := ' || etape_rec.PERTIN_FUNC || '(:1); END;' USING OUT pertinente, p_intervenant_id;
        --DBMS_OUTPUT.put_line (etape_rec.libelle || ' --> ' || etape_rec.PERTIN_FUNC || ' returned ' || pertinente);
      END IF;
      IF pertinente = 0 THEN 
        CONTINUE;
      END IF;
      
      --
      -- La règle (fonction) de franchissement prend 2 arguments : l'id de l'intervenant (null interdit) et l'id de la structure (null accepté).
      -- Cette règle sera exécutée une fois avec un id de structure null (ce qui se traduit par "peu importe la structure"), puis
      -- autant de fois qu'il existe de structures d'enseignement dans le cas où l'étape est déclinable par structure.
      -- L'id null et les ids des structures sont stockés dans une liste qui sera parcourue plus loin.
      --
      l_structures_ids.DELETE;
      -- id structure null
      l_structures_ids(l_structures_ids.COUNT) := NULL;
      -- pour les étapes pouvant être déclinées par structure, collecte des structures d'enseignement
      IF etape_rec.STRUCTURE_DEPENDANT = 1 AND etape_rec.STRUCTURES_IDS_FUNC IS NOT NULL THEN
        --ose_workflow.fetch_struct_ens_ids(p_intervenant_id, structures_ids);
        EXECUTE IMMEDIATE 'BEGIN ' || etape_rec.STRUCTURES_IDS_FUNC || '(:1); END;' USING p_intervenant_id;
      END IF;
      
      --
      -- Dans la progression de l'intervenant, une même étape peut figurer plusieurs fois : une fois avec un id de structure null 
      -- (ce qui se traduit par "peu importe la structure") + autant de fois qu'il existe de structures d'enseignement dans le cas où 
      -- l'étape est déclinable par structure.
      --
      FOR i IN 0 .. l_structures_ids.COUNT - 1
      LOOP
        structure_id := l_structures_ids(i);
        --DBMS_OUTPUT.put_line (etape_rec.libelle || ' : structures_ids('||i||') := ' || structure_id);
        
        --
        -- Interrogation de la règle de franchissement de l'étape.
        --
        IF etape_rec.FRANCH_FUNC IS NULL THEN
          franchie := 1;
        ELSE
          EXECUTE IMMEDIATE 'BEGIN :res := ' || etape_rec.FRANCH_FUNC || '(:1, :2); END;' USING OUT franchie, p_intervenant_id, structure_id;
          --DBMS_OUTPUT.put_line (etape_rec.FRANCH_FUNC || ' returned ' || franchie);
        END IF;
                        
        atteignable := 1;
        
        --
        -- Si l'étape courante n'a pas encore été trouvée.
        --
        IF courante_trouvee = 0 THEN 
          IF franchie = 1 THEN 
            courante := 0;
          ELSE
            -- l'étape marquée "courante" est la 1ère étape non franchie
            courante := 1;
            courante_trouvee := etape_rec.id;
          END IF;
        --
        -- Si l'étape courante a été trouvée et que l'on se situe dessus.
        --
        ELSIF courante_trouvee = etape_rec.id THEN
          IF franchie = 1 THEN 
            courante := 0;
          ELSE
            courante := 1;
          END IF;
        --
        -- Une étape située après l'étape courante est forcément "non courante".
        --
        ELSE
          courante := 0;
          atteignable := 0;
        END IF;
                 
                 
        SELECT
          count(*) INTO non_franchies
        FROM
          wf_etape we
          JOIN wf_intervenant_etape wie ON wie.etape_id = we.id 
        WHERE
          intervenant_id = p_intervenant_id
          AND we.ordre < etape_rec.ordre
          AND (wie.structure_id IS NULL OR wie.structure_id = Update_Intervenant_Etapes.structure_id)
          AND franchie = 0
          AND rownum = 1;
            
        atteignable := CASE WHEN non_franchies = 1 THEN 0 ELSE 1 END;

        --
        -- Ecriture dans la table.
        --
        INSERT INTO wf_intervenant_etape (id, intervenant_id, etape_id, structure_id, courante, franchie, atteignable, ordre) 
          SELECT wf_intervenant_etape_id_seq.nextval, p_intervenant_id, etape_rec.id, structure_id, courante, franchie, atteignable, ordre FROM DUAL;
        ordre := ordre + 1;
      END LOOP;
      
      select count(*) into teste FROM wf_intervenant_etape WHERE intervenant_id = p_intervenant_id;
      ose_test.echo('a=' || teste);
      
    END LOOP;
  END;
  
  /**
   * Fetch les ids des structures d'enseignement PREVU de l'intervenant spécifié.
   */
  PROCEDURE fetch_struct_ens_ids (p_intervenant_id NUMERIC)
  IS
  BEGIN
    fetch_struct_ens_ids_tvh('PREVU', p_intervenant_id);
  END;
  
  /**
   * Fetch les ids des structures d'enseignement REALISE de l'intervenant spécifié.
   */
  PROCEDURE fetch_struct_ens_realise_ids (p_intervenant_id NUMERIC)
  IS
  BEGIN
    fetch_struct_ens_ids_tvh('REALISE', p_intervenant_id);
  END;
  
  /**
   * Fetch les ids des structures d'enseignement de l'intervenant spécifié, 
   * pour le type de volume horaire spécifié.
   */
  PROCEDURE fetch_struct_ens_ids_tvh (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC)
  IS
    i PLS_INTEGER;
  BEGIN
    i := l_structures_ids.COUNT;
    FOR d IN (
      SELECT distinct ep.structure_id 
      FROM element_pedagogique ep
      JOIN service s on s.element_pedagogique_id = ep.id /*AND S.ANNEE_ID = OSE_PARAMETRE.GET_ANNEE()*/ AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
      JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = p_type_volume_horaire_code
      WHERE s.intervenant_id = p_intervenant_id
    ) LOOP
      l_structures_ids(i) := d.structure_id;
      i := i + 1;
    END LOOP;
  END;
  
  /**
   * Fetch les ids des structures du référentiel PREVU de l'intervenant spécifié.
   */
  PROCEDURE fetch_struct_ref_ids (p_intervenant_id NUMERIC)
  IS
  BEGIN
    fetch_struct_ref_ids_tvh('PREVU', p_intervenant_id);
  END;
  
  /**
   * Fetch les ids des structures du référentiel REALISE de l'intervenant spécifié.
   */
  PROCEDURE fetch_struct_ref_realise_ids (p_intervenant_id NUMERIC)
  IS
  BEGIN
    fetch_struct_ref_ids_tvh('REALISE', p_intervenant_id);
  END;
  
  /**
   * Fetch les ids des structures du référentiel de l'intervenant spécifié, 
   * pour le seul type de volume horaire spécifié.
   */
  PROCEDURE fetch_struct_ref_ids_tvh (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC)
  IS
    i PLS_INTEGER;
  BEGIN
    i := l_structures_ids.COUNT;
    FOR d IN (
      SELECT distinct structure_id FROM service_referentiel s 
      JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = p_type_volume_horaire_code
      WHERE s.intervenant_id = p_intervenant_id /*AND S.ANNEE_ID = OSE_PARAMETRE.GET_ANNEE()*/ AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
    ) LOOP
      l_structures_ids(i) := d.structure_id;
      i := i + 1;
    END LOOP;
  END;
  
  
  /**
   * Fetch les ids des structures d'enseignement + les ids des structures du référentiel PREVU de l'intervenant spécifié.
   */
  PROCEDURE fetch_struct_ensref_ids          (p_intervenant_id NUMERIC)
  IS
    i PLS_INTEGER;
  BEGIN
    fetch_struct_ens_ids (p_intervenant_id);
    fetch_struct_ref_ids (p_intervenant_id);
  END;
  
  /**
   * Fetch les ids des structures d'enseignement + les ids des structures du référentiel REALISE de l'intervenant spécifié.
   */
  PROCEDURE fetch_struct_ensref_realis_ids  (p_intervenant_id NUMERIC)
  IS
  BEGIN
    fetch_struct_ens_realise_ids (p_intervenant_id);
    fetch_struct_ref_realise_ids (p_intervenant_id);
  END;
  
  
  
  
  
  
  
  --------------------------------------------------------------------------------------------------------------------------
  -- Règles de pertinence et de franchissement des étapes.
  --------------------------------------------------------------------------------------------------------------------------
  
  /**
   *
   */
  FUNCTION peut_saisir_dossier (p_intervenant_id NUMERIC, p_structure_id NUMERIC) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT si.peut_saisir_dossier INTO res FROM statut_intervenant si 
    JOIN intervenant i ON i.statut_id = si.id 
    WHERE i.id = p_intervenant_id;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION possede_dossier (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT CASE WHEN i.dossier_id IS NULL THEN 0 ELSE 1 END INTO res FROM intervenant i where i.id = p_intervenant_id;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION dossier_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM validation v 
    JOIN type_validation tv ON tv.id = v.type_validation_id AND tv.code = 'DONNEES_PERSO_PAR_COMP' 
    WHERE 1 = ose_divers.comprise_entre(v.histo_creation, v.histo_destruction)
    AND v.intervenant_id = p_intervenant_id;
    RETURN res;
  END;
  
  
  
  
  
  
  /**
   *
   */
  FUNCTION peut_saisir_service (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    -- teste le statut de l'intervenant issu de la table INTERVENANT
    SELECT si.peut_saisir_service INTO res 
    FROM statut_intervenant si 
    JOIN intervenant i ON i.statut_id = si.id 
    WHERE i.id = p_intervenant_id;
    
    if res > 0 then
      RETURN res;
    end if;
    
    -- teste sinon le statut saisi dans l'éventuel dossier
    SELECT
      count(*) INTO res 
    FROM
      intervenant i
      JOIN dossier d ON d.id = i.dossier_id AND 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction)
      JOIN statut_intervenant si on si.id = d.statut_id and si.peut_saisir_service = 1
    WHERE
      i.id = p_intervenant_id
    ;
    
    RETURN res;
  END;
  
  /**
   *
   */
  FUNCTION possede_services (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    RETURN possede_services_tvh('PREVU', p_intervenant_id, p_structure_id);
  END;
  
  /**
   *
   */
  FUNCTION possede_services_realises (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    RETURN possede_services_tvh('REALISE', p_intervenant_id, p_structure_id);
  END;
  
  /**
   *
   */
  FUNCTION possede_services_tvh (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    IF p_structure_id IS NULL THEN
      SELECT count(*) INTO res FROM service s 
      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id AND 1 = ose_divers.comprise_entre(ep.histo_creation, ep.histo_destruction)
      --JOIN etape e ON e.id = ep.etape_id AND 1 = ose_divers.comprise_entre(e.histo_creation, e.histo_destruction)
      JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = p_type_volume_horaire_code
      WHERE s.intervenant_id = p_intervenant_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction) /*AND s.annee_id = ose_parametre.get_annee()*/;
    ELSE
      SELECT count(*) INTO res FROM service s 
      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id 
      --JOIN etape e ON e.id = ep.etape_id AND 1 = ose_divers.comprise_entre(e.histo_creation, e.histo_destruction)
      JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = p_type_volume_horaire_code
      WHERE s.intervenant_id = p_intervenant_id /*AND s.annee_id = ose_parametre.get_annee()*/
      AND ep.structure_id = p_structure_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction);
    END IF;
    RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
  END;
  
  /**
   *
   */
  FUNCTION service_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
  BEGIN
    RETURN service_valide_tvh('PREVU', p_intervenant_id, p_structure_id);
  END;
  
  /**
   *
   */
  FUNCTION service_realise_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
  BEGIN
    RETURN service_valide_tvh('REALISE', p_intervenant_id, p_structure_id);
  END;
  
  /**
   *
   */
  FUNCTION service_valide_tvh (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    CURSOR service_cur IS 
      SELECT s.*, ep.structure_id
      FROM service s 
      JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON vh.type_volume_horaire_id = tvh.id AND tvh.code = p_type_volume_horaire_code
      JOIN v_volume_horaire_etat vhe ON vhe.volume_horaire_id = vh.id
      JOIN etat_volume_horaire evh ON evh.id = vhe.etat_volume_horaire_id AND evh.ordre >= ( SELECT min(ordre) FROM etat_volume_horaire WHERE code = 'valide' )
      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id AND 1 = ose_divers.comprise_entre(ep.histo_creation, ep.histo_destruction)
      --JOIN etape e ON e.id = ep.etape_id AND 1 = ose_divers.comprise_entre(e.histo_creation, e.histo_destruction)
      WHERE s.intervenant_id = p_intervenant_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction) /*AND s.annee_id = ose_parametre.get_annee()*/;
    service_rec service_cur%rowtype;
    res NUMERIC := 0;
    nb numeric;
  BEGIN
    FOR service_rec IN service_cur
    LOOP
      IF p_structure_id IS NULL THEN
        -- si aucune structure n'est spécifiée, on se contente du moindre service trouvé
        return 1;
      END IF;
      -- si une structure précise est spécifiée, on se contente du moindre service trouvé concernant cette structure d'enseignement
      IF service_rec.structure_id = p_structure_id THEN
        return 1;
      END IF;
    END LOOP;
    
    RETURN 0;
  END;
  
  
  
  
  
  
  
  
  /**
   *
   */
  FUNCTION peut_cloturer_realise              (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    estPerm numeric;
  BEGIN
    select count(*) into estPerm
    from type_intervenant ti 
    join statut_intervenant si on si.TYPE_INTERVENANT_ID = ti.id 
    join intervenant i on i.STATUT_ID = si.id and i.id = p_intervenant_id
    where ti.code = 'P';
    
    return estPerm;
  END;
  
  /**
   *
   */
  FUNCTION realise_cloture                    (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    found numeric;
  BEGIN
    select count(*) into found 
    from validation v 
    join type_validation tv on tv.id = v.type_validation_id and tv.code = 'CLOTURE_REALISE'
    where 1 = ose_divers.comprise_entre(v.histo_creation, v.histo_destruction)
    and v.intervenant_id = p_intervenant_id;
    
    return case when found > 0 then 1 else 0 end;
  END;
  
  
  
  
  
  
  
  
  
  
  
  
  /**
   *
   */
  FUNCTION peut_saisir_referentiel (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT si.peut_saisir_referentiel INTO res FROM statut_intervenant si JOIN intervenant i ON i.statut_id = si.id 
    WHERE i.id = p_intervenant_id;
    RETURN res;
  END;
  
  /**
   *
   */
  FUNCTION possede_referentiel (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    RETURN possede_referentiel_tvh('PREVU', p_intervenant_id, p_structure_id);
  END;
  
  /**
   *
   */
  FUNCTION possede_referentiel_realise (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    RETURN possede_referentiel_tvh('REALISE', p_intervenant_id, p_structure_id);
  END;
  
  /**
   *
   */
  FUNCTION possede_referentiel_tvh (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    IF p_structure_id IS NULL THEN
      SELECT count(*) INTO res FROM service_referentiel s 
      JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = p_type_volume_horaire_code
      WHERE s.intervenant_id = p_intervenant_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction);
    ELSE
      SELECT count(*) INTO res FROM service_referentiel s 
      JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = p_type_volume_horaire_code
      WHERE s.intervenant_id = p_intervenant_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
      AND s.structure_id = p_structure_id;
    END IF;
    RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
  END;

  /**
   *
   */
  FUNCTION referentiel_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    RETURN referentiel_valide_tvh('PREVU', p_intervenant_id, p_structure_id);
  END;

  /**
   *
   */
  FUNCTION referentiel_realise_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    RETURN referentiel_valide_tvh('REALISE', p_intervenant_id, p_structure_id);
  END;

  /**
   *
   */
  FUNCTION referentiel_valide_tvh (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    CURSOR ref_cur IS 
      SELECT s.* FROM service_referentiel s 
      JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON vh.type_volume_horaire_id = tvh.id AND tvh.code = p_type_volume_horaire_code
      JOIN v_volume_horaire_ref_etat vhe ON vhe.volume_horaire_ref_id = vh.id
      JOIN etat_volume_horaire evh ON evh.id = vhe.etat_volume_horaire_id AND evh.ordre >= ( SELECT min(ordre) FROM etat_volume_horaire WHERE code = 'valide' )
      WHERE s.intervenant_id = p_intervenant_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction);
    ref_rec ref_cur%rowtype;
    res NUMERIC := 0;
    nb numeric;
  BEGIN
    -- si aucun référentiel, la validation doit être considérée comme faite
    if ose_workflow.possede_referentiel_tvh(p_type_volume_horaire_code, p_intervenant_id, p_structure_id) < 1 then
      return 1;
    end if;
  
    IF p_structure_id IS NULL THEN
      -- si aucune structure n'est spécifiée, on se contente du moindre référentiel trouvé
      OPEN ref_cur;
      FETCH ref_cur INTO ref_rec;
      IF ref_cur%FOUND = TRUE THEN
        res := 1;
      END IF;
      CLOSE ref_cur;
    ELSE
      -- si une structure précise est spécifiée, on se contente du moindre référentiel trouvé concernant cette structure d'enseignement
      FOR ref_rec IN ref_cur
      LOOP
        IF ref_rec.structure_id = p_structure_id THEN
          res := 1;
          EXIT;
        END IF;
      END LOOP;
    END IF;
    RETURN res;
  END;
  
  
  
  
  
  
  
  
  
  
  /**
   *
   */
  FUNCTION peut_saisir_pj (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM type_piece_jointe_statut tpjs 
    JOIN statut_intervenant si on tpjs.statut_intervenant_id = si.id 
    JOIN intervenant i ON i.statut_id = si.id
    WHERE i.id = p_intervenant_id;
    RETURN res;
  END;
  
  /**
   *
   */
  FUNCTION peut_valider_pj (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    res := peut_saisir_pj(p_intervenant_id, p_structure_id);
    if (res = 0) then
      return 0;
    end if;
  
    -- nombre de pj fournies (avec fichier)
    SELECT
      count(*) into res
    FROM
      intervenant i
      JOIN dossier d on d.id = i.dossier_id  and 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction)
      JOIN PIECE_JOINTE pj ON pj.dossier_id = d.id AND 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
      JOIN PIECE_JOINTE_FICHIER pjf ON pjf.piece_jointe_id = pj.id     
    WHERE
      i.id = p_intervenant_id
    ;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION pj_oblig_fournies (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    -- verif existence de données perso
    res := possede_dossier(p_intervenant_id, p_structure_id);
    if (res = 0) then
      return 0;
    end if;
  
    SELECT count(*) INTO res FROM (WITH 
      ATTENDU_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES pour chaque intervenant
          SELECT
            I.ID INTERVENANT_ID, 
            I.SOURCE_CODE, 
            count( distinct pj.id) NB
          FROM 
            INTERVENANT i
            JOIN DOSSIER d ON d.id = i.dossier_id AND 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction)
            JOIN piece_jointe pj on pj.dossier_id = d.id AND 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
          WHERE
            1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
            AND pj.OBLIGATOIRE = 1
          GROUP BY I.ID, I.SOURCE_CODE
      ), 
      FOURNI_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES FOURNIES AVEC FICHIER par chaque intervenant, VALIDEES OU NON
          SELECT
            I.ID INTERVENANT_ID,
            I.SOURCE_CODE,
            count( distinct pj.ID) NB
          FROM 
            INTERVENANT i
            JOIN DOSSIER d ON d.id = i.dossier_id AND 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction)
            JOIN PIECE_JOINTE pj ON pj.DOSSIER_ID = d.ID AND 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
            JOIN PIECE_JOINTE_FICHIER pjf ON pjf.piece_jointe_id = pj.id -- AVEC FICHIER
          WHERE
            1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
            AND pj.OBLIGATOIRE = 1
          GROUP BY I.ID, I.SOURCE_CODE
      )
      SELECT 
          I.ID, 
          I.source_code,
          I.nom_usuel,
          COALESCE(A.NB, 0) NB_PJ_OBLIG_ATTENDU, 
          COALESCE(F.NB, 0) NB_PJ_OBLIG_FOURNI
      FROM intervenant i
      left join ATTENDU_OBLIGATOIRE A on a.intervenant_id = i.id
      LEFT JOIN FOURNI_OBLIGATOIRE  F ON F.INTERVENANT_ID = i.id
      WHERE i.ID = p_intervenant_id
    )
    WHERE NB_PJ_OBLIG_ATTENDU = 0 OR NB_PJ_OBLIG_ATTENDU <= NB_PJ_OBLIG_FOURNI;
    
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION pj_oblig_validees (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    -- verif existence de données perso
    res := possede_dossier(p_intervenant_id, p_structure_id);
    if (res = 0) then
      return 0;
    end if;
    
    SELECT count(*) INTO res FROM (
      WITH 
      ATTENDU_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES pour chaque intervenant
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(distinct pj.id) NB
          FROM
            INTERVENANT I
            JOIN DOSSIER d ON d.id = i.dossier_id AND 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction)
            JOIN piece_jointe pj on pj.dossier_id = d.id AND 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
          WHERE 
            1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
            AND pj.OBLIGATOIRE = 1
          GROUP BY I.ID, I.SOURCE_CODE
      ), 
      FOURNI_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES FOURNIES AVEC FICHIER par chaque intervenant et VALIDEES 
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(distinct pj.ID) NB
          FROM INTERVENANT I
          INNER JOIN DOSSIER d ON d.id = i.dossier_id AND 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction)
          INNER JOIN PIECE_JOINTE pj ON d.ID = pj.DOSSIER_ID AND 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
          INNER JOIN PIECE_JOINTE_FICHIER pjf ON pjf.piece_jointe_id = pj.id -- AVEC FICHIER
          WHERE
            1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
            AND pj.OBLIGATOIRE = 1
            and pj.validation_id is not null
          GROUP BY I.ID, I.SOURCE_CODE
      )
      SELECT 
          I.ID, 
          I.source_code,
          I.nom_usuel,
          COALESCE(A.NB, 0) NB_PJ_OBLIG_ATTENDU, 
          COALESCE(F.NB, 0) NB_PJ_OBLIG_FOURNI
      FROM intervenant i
      left join ATTENDU_OBLIGATOIRE A on a.intervenant_id = i.id
      LEFT JOIN FOURNI_OBLIGATOIRE  F ON F.INTERVENANT_ID = i.id
      WHERE i.ID = p_intervenant_id
    )
    WHERE NB_PJ_OBLIG_ATTENDU = 0 OR NB_PJ_OBLIG_ATTENDU <= NB_PJ_OBLIG_FOURNI;
    
    RETURN res;
  END;
  
  
  
  
  
  
  
  /**
   *
   */
  FUNCTION necessite_agrement_cr (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res 
    FROM
      v_tbl_agrement tbl
      JOIN type_agrement ta ON ta.id = tbl.type_agrement_id
    WHERE 
      ta.code = 'CONSEIL_RESTREINT'
      AND tbl.intervenant_id = p_intervenant_id
      AND (tbl.structure_id = p_structure_id OR p_structure_id IS NULL)
      AND ROWNUM = 1;
    RETURN res;
  END;
  
  /**
   *
   */
  FUNCTION necessite_agrement_ca (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT COUNT(*) INTO res 
    FROM
      v_tbl_agrement tbl
      JOIN type_agrement ta ON ta.id = tbl.type_agrement_id
    WHERE 
      ta.code = 'CONSEIL_ACADEMIQUE'
      AND tbl.intervenant_id = p_intervenant_id
      AND (tbl.structure_id = p_structure_id OR p_structure_id IS NULL)
      AND ROWNUM = 1;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION agrement_cr_fourni (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res 
    FROM
      v_tbl_agrement tbl
      JOIN type_agrement ta ON ta.id = tbl.type_agrement_id
    WHERE 
      ta.code = 'CONSEIL_RESTREINT'
      AND tbl.agrement_id IS NULL
      AND tbl.intervenant_id = p_intervenant_id
      AND (tbl.structure_id = p_structure_id OR p_structure_id IS NULL)
      AND ROWNUM = 1;
    IF res > 0 THEN RETURN 0; ELSE RETURN 1; END IF;
  END;

  /**
   *
   */
  FUNCTION agrement_ca_fourni (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res 
    FROM
      v_tbl_agrement tbl
      JOIN type_agrement ta ON ta.id = tbl.type_agrement_id
    WHERE 
      ta.code = 'CONSEIL_ACADEMIQUE'
      AND tbl.agrement_id IS NULL
      AND tbl.intervenant_id = p_intervenant_id
      AND (tbl.structure_id = p_structure_id OR p_structure_id IS NULL)
      AND ROWNUM = 1;
    IF res > 0 THEN RETURN 0; ELSE RETURN 1; END IF;
  END;
  
  
   
  
  
  
  
  
  
  
  /**
   *
   */
  FUNCTION necessite_contrat (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT si.peut_avoir_contrat INTO res FROM statut_intervenant si JOIN intervenant i ON i.statut_id = si.id 
    WHERE i.id = p_intervenant_id;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION possede_contrat (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res 
    FROM contrat c
    JOIN validation v ON c.validation_id = v.id AND 1 = ose_divers.comprise_entre(v.histo_creation, v.histo_destruction)
    WHERE 1 = ose_divers.comprise_entre(c.histo_creation, c.histo_destruction)
    AND c.intervenant_id = p_intervenant_id
    AND (p_structure_id IS NULL OR p_structure_id IS NOT NULL AND c.STRUCTURE_ID = p_structure_id) 
    AND ROWNUM = 1;
    
    RETURN res;
  END;






  /**
   *
   */
  FUNCTION peut_demander_mep (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    -- si l'intervenant possède déjà des demande de MEP, il peut demander des MEP
    if possede_demande_mep(p_intervenant_id, p_structure_id) = 1 then
      return 1;
    end if;
  
    IF p_structure_id IS NULL THEN
      select count(*) into res from v_indic_attente_demande_mep where intervenant_id = p_intervenant_id;
    ELSE
      select count(*) into res from v_indic_attente_demande_mep where intervenant_id = p_intervenant_id and structure_id = p_structure_id;
    END IF;
    RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
  END;

  /**
   *
   */
  FUNCTION possede_demande_mep (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    IF p_structure_id IS NULL THEN
      select count(*) into res from v_total_demande_mep_structure where intervenant_id = p_intervenant_id;
    ELSE
      select count(*) into res from v_total_demande_mep_structure where intervenant_id = p_intervenant_id and structure_id = p_structure_id;
    END IF;
    RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
  END;

  /**
   *
   */
  FUNCTION peut_saisir_mep (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    RETURN possede_demande_mep(p_intervenant_id, p_structure_id);
  END;

  /**
   *
   */
  FUNCTION possede_mep (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    IF p_structure_id IS NULL THEN
      select count(*) into res from V_MEP_INTERVENANT_STRUCTURE where periode_paiement_id is not null and intervenant_id = p_intervenant_id;
    ELSE
      select count(*) into res from V_MEP_INTERVENANT_STRUCTURE where periode_paiement_id is not null and intervenant_id = p_intervenant_id and structure_id = p_structure_id;
    END IF;
    RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
  END;


END OSE_WORKFLOW;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_IMPORT
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_IMPORT" IS

  v_current_user INTEGER;
  v_current_annee INTEGER;



  FUNCTION get_current_user RETURN INTEGER IS
  BEGIN
    IF v_current_user IS NULL THEN
      v_current_user := OSE_PARAMETRE.GET_OSE_USER();
    END IF;
    RETURN v_current_user;
  END get_current_user;
 
  PROCEDURE set_current_user (p_current_user INTEGER) is
  BEGIN
    v_current_user := p_current_user;
  END set_current_user;



  FUNCTION get_current_annee RETURN INTEGER IS
  BEGIN
    IF v_current_annee IS NULL THEN
      v_current_annee := OSE_PARAMETRE.GET_ANNEE_IMPORT();
    END IF;
    RETURN v_current_annee;
  END get_current_annee;
 
  PROCEDURE set_current_annee (p_current_annee INTEGER) IS
  BEGIN
    v_current_annee := p_current_annee;
  END set_current_annee;



  FUNCTION get_sql_criterion( table_name varchar2, sql_criterion VARCHAR2 ) RETURN CLOB IS
  BEGIN
    IF sql_criterion <> '' OR sql_criterion IS NOT NULL THEN
      RETURN sql_criterion;
    END IF;
    RETURN CASE table_name
      WHEN 'INTERVENANT' THEN -- Met à jour toutes les données sauf le statut, qui sera traité à part
        'WHERE IMPORT_ACTION IN (''delete'',''update'',''undelete'')'
        
      WHEN 'AFFECTATION_RECHERCHE' THEN
        'WHERE INTERVENANT_ID IS NOT NULL'
        
      WHEN 'ADRESSE_INTERVENANT' THEN
        'WHERE INTERVENANT_ID IS NOT NULL'
        
      WHEN 'ELEMENT_TAUX_REGIMES' THEN
        'WHERE IMPORT_ACTION IN (''delete'',''insert'',''undelete'')'

      ELSE
        ''
    END;
  END;



  PROCEDURE SYNC_LOG( message CLOB, table_name VARCHAR2 DEFAULT NULL, source_code VARCHAR2 DEFAULT NULL ) IS
  BEGIN
    INSERT INTO OSE.SYNC_LOG("ID","DATE_SYNC","MESSAGE","TABLE_NAME","SOURCE_CODE") VALUES (SYNC_LOG_ID_SEQ.NEXTVAL, SYSDATE, message,table_name,source_code);
  END SYNC_LOG;



  PROCEDURE REFRESH_MV( mview_name varchar2 ) IS
  BEGIN
    DBMS_MVIEW.REFRESH(mview_name, 'C');
  EXCEPTION WHEN OTHERS THEN
    OSE_IMPORT.SYNC_LOG( SQLERRM, mview_name );
  END;

  PROCEDURE REFRESH_MVS IS
  BEGIN
    -- Mise à jour des vues matérialisées
    REFRESH_MV('MV_PAYS');
    REFRESH_MV('MV_DEPARTEMENT');
    REFRESH_MV('MV_ETABLISSEMENT');
    REFRESH_MV('MV_STRUCTURE');
    REFRESH_MV('MV_ADRESSE_STRUCTURE');
    
    REFRESH_MV('MV_PERSONNEL');
    REFRESH_MV('MV_AFFECTATION');
    
    REFRESH_MV('MV_CORPS');
    REFRESH_MV('MV_GRADE');
    
    REFRESH_MV('MV_INTERVENANT');
    REFRESH_MV('MV_AFFECTATION_RECHERCHE');
    REFRESH_MV('MV_ADRESSE_INTERVENANT');
    REFRESH_MV('MV_INTERVENANT_RECHERCHE'); -- pour la recherche d'intervenants
    
    REFRESH_MV('MV_GROUPE_TYPE_FORMATION');
    REFRESH_MV('MV_TYPE_FORMATION');
    REFRESH_MV('MV_ETAPE');
    REFRESH_MV('MV_ELEMENT_PEDAGOGIQUE');
    REFRESH_MV('MV_EFFECTIFS');
    REFRESH_MV('MV_ELEMENT_TAUX_REGIMES');
    REFRESH_MV('MV_CHEMIN_PEDAGOGIQUE');
    REFRESH_MV('MV_ELEMENT_PORTEUR_PORTE');
    
    REFRESH_MV('MV_CENTRE_COUT');
    REFRESH_MV('MV_DOMAINE_FONCTIONNEL');
  END;

  PROCEDURE SYNC_TABLES IS
  BEGIN
    MAJ_PAYS();
    MAJ_DEPARTEMENT();
  
    MAJ_ETABLISSEMENT();
    MAJ_STRUCTURE();
    MAJ_ADRESSE_STRUCTURE();
    
    MAJ_DOMAINE_FONCTIONNEL();
    MAJ_CENTRE_COUT();
    MAJ_CENTRE_COUT_STRUCTURE();

    MAJ_PERSONNEL();
    MAJ_AFFECTATION();

    MAJ_CORPS();
    MAJ_GRADE();

    MAJ_INTERVENANT();
    MAJ_AFFECTATION_RECHERCHE();
    MAJ_ADRESSE_INTERVENANT();

    MAJ_GROUPE_TYPE_FORMATION();
    MAJ_TYPE_FORMATION();
    MAJ_ETAPE();
    MAJ_ELEMENT_PEDAGOGIQUE();
    MAJ_EFFECTIFS();
    MAJ_ELEMENT_TAUX_REGIMES();
    MAJ_CHEMIN_PEDAGOGIQUE();
    
    -- Mise à jour des sources calculées en dernier
    MAJ_TYPE_INTERVENTION_EP();
    MAJ_TYPE_MODULATEUR_EP();
  END;

  PROCEDURE SYNCHRONISATION IS
  BEGIN
    REFRESH_MVS;
    SYNC_TABLES;
  END SYNCHRONISATION;



  FUNCTION IN_COLUMN_LIST( VALEUR VARCHAR2, CHAMPS CLOB ) RETURN NUMERIC IS
  BEGIN
    IF REGEXP_LIKE(CHAMPS, '(^|,)[ \t\r\n\v\f]*' || VALEUR || '[ \t\r\n\v\f]*(,|$)') THEN RETURN 1; END IF;
    RETURN 0;
  END;





  -- AUTOMATIC GENERATION --

  PROCEDURE MAJ_GROUPE_TYPE_FORMATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_GROUPE_TYPE_FORMATION%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_GROUPE_TYPE_FORMATION.* FROM V_DIFF_GROUPE_TYPE_FORMATION ' || get_sql_criterion('GROUPE_TYPE_FORMATION',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.GROUPE_TYPE_FORMATION
              ( id, LIBELLE_COURT,LIBELLE_LONG,ORDRE,PERTINENCE_NIVEAU, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,GROUPE_TYPE_FORMATION_ID_SEQ.NEXTVAL), diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG,diff_row.ORDRE,diff_row.PERTINENCE_NIVEAU, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PERTINENCE_NIVEAU = 1 AND IN_COLUMN_LIST('PERTINENCE_NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET PERTINENCE_NIVEAU = diff_row.PERTINENCE_NIVEAU WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.GROUPE_TYPE_FORMATION SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PERTINENCE_NIVEAU = 1 AND IN_COLUMN_LIST('PERTINENCE_NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET PERTINENCE_NIVEAU = diff_row.PERTINENCE_NIVEAU WHERE ID = diff_row.id; END IF;
            UPDATE OSE.GROUPE_TYPE_FORMATION SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'GROUPE_TYPE_FORMATION', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_GROUPE_TYPE_FORMATION;



  PROCEDURE MAJ_TYPE_FORMATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_TYPE_FORMATION%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_TYPE_FORMATION.* FROM V_DIFF_TYPE_FORMATION ' || get_sql_criterion('TYPE_FORMATION',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.TYPE_FORMATION
              ( id, GROUPE_ID,LIBELLE_COURT,LIBELLE_LONG, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,TYPE_FORMATION_ID_SEQ.NEXTVAL), diff_row.GROUPE_ID,diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_GROUPE_ID = 1 AND IN_COLUMN_LIST('GROUPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET GROUPE_ID = diff_row.GROUPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.TYPE_FORMATION SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_GROUPE_ID = 1 AND IN_COLUMN_LIST('GROUPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET GROUPE_ID = diff_row.GROUPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
            UPDATE OSE.TYPE_FORMATION SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'TYPE_FORMATION', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_TYPE_FORMATION;



  PROCEDURE MAJ_PERSONNEL(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_PERSONNEL%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_PERSONNEL.* FROM V_DIFF_PERSONNEL ' || get_sql_criterion('PERSONNEL',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.PERSONNEL
              ( id, CIVILITE_ID,EMAIL,NOM_PATRONYMIQUE,NOM_USUEL,PRENOM,STRUCTURE_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,PERSONNEL_ID_SEQ.NEXTVAL), diff_row.CIVILITE_ID,diff_row.EMAIL,diff_row.NOM_PATRONYMIQUE,diff_row.NOM_USUEL,diff_row.PRENOM,diff_row.STRUCTURE_ID, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_PATRONYMIQUE = 1 AND IN_COLUMN_LIST('NOM_PATRONYMIQUE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET NOM_PATRONYMIQUE = diff_row.NOM_PATRONYMIQUE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_USUEL = 1 AND IN_COLUMN_LIST('NOM_USUEL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET NOM_USUEL = diff_row.NOM_USUEL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRENOM = 1 AND IN_COLUMN_LIST('PRENOM',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET PRENOM = diff_row.PRENOM WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.PERSONNEL SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_PATRONYMIQUE = 1 AND IN_COLUMN_LIST('NOM_PATRONYMIQUE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET NOM_PATRONYMIQUE = diff_row.NOM_PATRONYMIQUE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_USUEL = 1 AND IN_COLUMN_LIST('NOM_USUEL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET NOM_USUEL = diff_row.NOM_USUEL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRENOM = 1 AND IN_COLUMN_LIST('PRENOM',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET PRENOM = diff_row.PRENOM WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
            UPDATE OSE.PERSONNEL SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'PERSONNEL', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_PERSONNEL;



  PROCEDURE MAJ_ADRESSE_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ADRESSE_STRUCTURE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ADRESSE_STRUCTURE.* FROM V_DIFF_ADRESSE_STRUCTURE ' || get_sql_criterion('ADRESSE_STRUCTURE',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.ADRESSE_STRUCTURE
              ( id, CODE_POSTAL,LOCALITE,NOM_VOIE,NO_VOIE,PAYS_CODE_INSEE,PAYS_LIBELLE,PRINCIPALE,STRUCTURE_ID,TELEPHONE,VILLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,ADRESSE_STRUCTURE_ID_SEQ.NEXTVAL), diff_row.CODE_POSTAL,diff_row.LOCALITE,diff_row.NOM_VOIE,diff_row.NO_VOIE,diff_row.PAYS_CODE_INSEE,diff_row.PAYS_LIBELLE,diff_row.PRINCIPALE,diff_row.STRUCTURE_ID,diff_row.TELEPHONE,diff_row.VILLE, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_CODE_POSTAL = 1 AND IN_COLUMN_LIST('CODE_POSTAL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET CODE_POSTAL = diff_row.CODE_POSTAL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALITE = 1 AND IN_COLUMN_LIST('LOCALITE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET LOCALITE = diff_row.LOCALITE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_VOIE = 1 AND IN_COLUMN_LIST('NOM_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET NOM_VOIE = diff_row.NOM_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NO_VOIE = 1 AND IN_COLUMN_LIST('NO_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET NO_VOIE = diff_row.NO_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET PAYS_CODE_INSEE = diff_row.PAYS_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET PAYS_LIBELLE = diff_row.PAYS_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRINCIPALE = 1 AND IN_COLUMN_LIST('PRINCIPALE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET PRINCIPALE = diff_row.PRINCIPALE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TELEPHONE = 1 AND IN_COLUMN_LIST('TELEPHONE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET TELEPHONE = diff_row.TELEPHONE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.ADRESSE_STRUCTURE SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_CODE_POSTAL = 1 AND IN_COLUMN_LIST('CODE_POSTAL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET CODE_POSTAL = diff_row.CODE_POSTAL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALITE = 1 AND IN_COLUMN_LIST('LOCALITE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET LOCALITE = diff_row.LOCALITE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_VOIE = 1 AND IN_COLUMN_LIST('NOM_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET NOM_VOIE = diff_row.NOM_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NO_VOIE = 1 AND IN_COLUMN_LIST('NO_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET NO_VOIE = diff_row.NO_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET PAYS_CODE_INSEE = diff_row.PAYS_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET PAYS_LIBELLE = diff_row.PAYS_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRINCIPALE = 1 AND IN_COLUMN_LIST('PRINCIPALE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET PRINCIPALE = diff_row.PRINCIPALE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TELEPHONE = 1 AND IN_COLUMN_LIST('TELEPHONE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET TELEPHONE = diff_row.TELEPHONE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;
            UPDATE OSE.ADRESSE_STRUCTURE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'ADRESSE_STRUCTURE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_ADRESSE_STRUCTURE;



  PROCEDURE MAJ_AFFECTATION_RECHERCHE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_AFFECTATION_RECHERCHE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_AFFECTATION_RECHERCHE.* FROM V_DIFF_AFFECTATION_RECHERCHE ' || get_sql_criterion('AFFECTATION_RECHERCHE',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.AFFECTATION_RECHERCHE
              ( id, INTERVENANT_ID,STRUCTURE_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,AFFECTATION_RECHERCHE_ID_SEQ.NEXTVAL), diff_row.INTERVENANT_ID,diff_row.STRUCTURE_ID, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.AFFECTATION_RECHERCHE SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
            UPDATE OSE.AFFECTATION_RECHERCHE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'AFFECTATION_RECHERCHE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_AFFECTATION_RECHERCHE;



  PROCEDURE MAJ_CORPS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_CORPS%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_CORPS.* FROM V_DIFF_CORPS ' || get_sql_criterion('CORPS',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.CORPS
              ( id, LIBELLE_COURT,LIBELLE_LONG, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,CORPS_ID_SEQ.NEXTVAL), diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.CORPS SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
            UPDATE OSE.CORPS SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'CORPS', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_CORPS;



  PROCEDURE MAJ_ADRESSE_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ADRESSE_INTERVENANT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ADRESSE_INTERVENANT.* FROM V_DIFF_ADRESSE_INTERVENANT ' || get_sql_criterion('ADRESSE_INTERVENANT',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.ADRESSE_INTERVENANT
              ( id, CODE_POSTAL,INTERVENANT_ID,LOCALITE,MENTION_COMPLEMENTAIRE,NOM_VOIE,NO_VOIE,PAYS_CODE_INSEE,PAYS_LIBELLE,TEL_DOMICILE,VILLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,ADRESSE_INTERVENANT_ID_SEQ.NEXTVAL), diff_row.CODE_POSTAL,diff_row.INTERVENANT_ID,diff_row.LOCALITE,diff_row.MENTION_COMPLEMENTAIRE,diff_row.NOM_VOIE,diff_row.NO_VOIE,diff_row.PAYS_CODE_INSEE,diff_row.PAYS_LIBELLE,diff_row.TEL_DOMICILE,diff_row.VILLE, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_CODE_POSTAL = 1 AND IN_COLUMN_LIST('CODE_POSTAL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET CODE_POSTAL = diff_row.CODE_POSTAL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALITE = 1 AND IN_COLUMN_LIST('LOCALITE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET LOCALITE = diff_row.LOCALITE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_MENTION_COMPLEMENTAIRE = 1 AND IN_COLUMN_LIST('MENTION_COMPLEMENTAIRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET MENTION_COMPLEMENTAIRE = diff_row.MENTION_COMPLEMENTAIRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_VOIE = 1 AND IN_COLUMN_LIST('NOM_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET NOM_VOIE = diff_row.NOM_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NO_VOIE = 1 AND IN_COLUMN_LIST('NO_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET NO_VOIE = diff_row.NO_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PAYS_CODE_INSEE = diff_row.PAYS_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PAYS_LIBELLE = diff_row.PAYS_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_DOMICILE = 1 AND IN_COLUMN_LIST('TEL_DOMICILE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET TEL_DOMICILE = diff_row.TEL_DOMICILE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.ADRESSE_INTERVENANT SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_CODE_POSTAL = 1 AND IN_COLUMN_LIST('CODE_POSTAL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET CODE_POSTAL = diff_row.CODE_POSTAL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALITE = 1 AND IN_COLUMN_LIST('LOCALITE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET LOCALITE = diff_row.LOCALITE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_MENTION_COMPLEMENTAIRE = 1 AND IN_COLUMN_LIST('MENTION_COMPLEMENTAIRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET MENTION_COMPLEMENTAIRE = diff_row.MENTION_COMPLEMENTAIRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_VOIE = 1 AND IN_COLUMN_LIST('NOM_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET NOM_VOIE = diff_row.NOM_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NO_VOIE = 1 AND IN_COLUMN_LIST('NO_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET NO_VOIE = diff_row.NO_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PAYS_CODE_INSEE = diff_row.PAYS_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PAYS_LIBELLE = diff_row.PAYS_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_DOMICILE = 1 AND IN_COLUMN_LIST('TEL_DOMICILE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET TEL_DOMICILE = diff_row.TEL_DOMICILE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;
            UPDATE OSE.ADRESSE_INTERVENANT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'ADRESSE_INTERVENANT', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_ADRESSE_INTERVENANT;



  PROCEDURE MAJ_CHEMIN_PEDAGOGIQUE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_CHEMIN_PEDAGOGIQUE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_CHEMIN_PEDAGOGIQUE.* FROM V_DIFF_CHEMIN_PEDAGOGIQUE ' || get_sql_criterion('CHEMIN_PEDAGOGIQUE',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.CHEMIN_PEDAGOGIQUE
              ( id, ELEMENT_PEDAGOGIQUE_ID,ETAPE_ID,ORDRE, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,CHEMIN_PEDAGOGIQUE_ID_SEQ.NEXTVAL), diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.ETAPE_ID,diff_row.ORDRE, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.CHEMIN_PEDAGOGIQUE SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
            UPDATE OSE.CHEMIN_PEDAGOGIQUE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'CHEMIN_PEDAGOGIQUE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_CHEMIN_PEDAGOGIQUE;



  PROCEDURE MAJ_ETABLISSEMENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ETABLISSEMENT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ETABLISSEMENT.* FROM V_DIFF_ETABLISSEMENT ' || get_sql_criterion('ETABLISSEMENT',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.ETABLISSEMENT
              ( id, DEPARTEMENT,LIBELLE,LOCALISATION, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,ETABLISSEMENT_ID_SEQ.NEXTVAL), diff_row.DEPARTEMENT,diff_row.LIBELLE,diff_row.LOCALISATION, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_DEPARTEMENT = 1 AND IN_COLUMN_LIST('DEPARTEMENT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET DEPARTEMENT = diff_row.DEPARTEMENT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALISATION = 1 AND IN_COLUMN_LIST('LOCALISATION',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET LOCALISATION = diff_row.LOCALISATION WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.ETABLISSEMENT SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_DEPARTEMENT = 1 AND IN_COLUMN_LIST('DEPARTEMENT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET DEPARTEMENT = diff_row.DEPARTEMENT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALISATION = 1 AND IN_COLUMN_LIST('LOCALISATION',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET LOCALISATION = diff_row.LOCALISATION WHERE ID = diff_row.id; END IF;
            UPDATE OSE.ETABLISSEMENT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'ETABLISSEMENT', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_ETABLISSEMENT;



  PROCEDURE MAJ_ELEMENT_PEDAGOGIQUE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ELEMENT_PEDAGOGIQUE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ELEMENT_PEDAGOGIQUE.* FROM V_DIFF_ELEMENT_PEDAGOGIQUE ' || get_sql_criterion('ELEMENT_PEDAGOGIQUE',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.ELEMENT_PEDAGOGIQUE
              ( id, ANNEE_ID,DISCIPLINE_ID,ETAPE_ID,FA,FC,FI,LIBELLE,PERIODE_ID,STRUCTURE_ID,TAUX_FA,TAUX_FC,TAUX_FI,TAUX_FOAD, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,ELEMENT_PEDAGOGIQUE_ID_SEQ.NEXTVAL), diff_row.ANNEE_ID,diff_row.DISCIPLINE_ID,diff_row.ETAPE_ID,diff_row.FA,diff_row.FC,diff_row.FI,diff_row.LIBELLE,diff_row.PERIODE_ID,diff_row.STRUCTURE_ID,diff_row.TAUX_FA,diff_row.TAUX_FC,diff_row.TAUX_FI,diff_row.TAUX_FOAD, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('DISCIPLINE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET DISCIPLINE_ID = diff_row.DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FA = 1 AND IN_COLUMN_LIST('FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FA = diff_row.FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FC = 1 AND IN_COLUMN_LIST('FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FC = diff_row.FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FI = 1 AND IN_COLUMN_LIST('FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FI = diff_row.FI WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PERIODE_ID = 1 AND IN_COLUMN_LIST('PERIODE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET PERIODE_ID = diff_row.PERIODE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FA = 1 AND IN_COLUMN_LIST('TAUX_FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FA = diff_row.TAUX_FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FC = 1 AND IN_COLUMN_LIST('TAUX_FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FC = diff_row.TAUX_FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FI = 1 AND IN_COLUMN_LIST('TAUX_FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FI = diff_row.TAUX_FI WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FOAD = 1 AND IN_COLUMN_LIST('TAUX_FOAD',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FOAD = diff_row.TAUX_FOAD WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.ELEMENT_PEDAGOGIQUE SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('DISCIPLINE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET DISCIPLINE_ID = diff_row.DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FA = 1 AND IN_COLUMN_LIST('FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FA = diff_row.FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FC = 1 AND IN_COLUMN_LIST('FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FC = diff_row.FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FI = 1 AND IN_COLUMN_LIST('FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FI = diff_row.FI WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PERIODE_ID = 1 AND IN_COLUMN_LIST('PERIODE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET PERIODE_ID = diff_row.PERIODE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FA = 1 AND IN_COLUMN_LIST('TAUX_FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FA = diff_row.TAUX_FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FC = 1 AND IN_COLUMN_LIST('TAUX_FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FC = diff_row.TAUX_FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FI = 1 AND IN_COLUMN_LIST('TAUX_FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FI = diff_row.TAUX_FI WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FOAD = 1 AND IN_COLUMN_LIST('TAUX_FOAD',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FOAD = diff_row.TAUX_FOAD WHERE ID = diff_row.id; END IF;
            UPDATE OSE.ELEMENT_PEDAGOGIQUE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'ELEMENT_PEDAGOGIQUE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_ELEMENT_PEDAGOGIQUE;



  PROCEDURE MAJ_CENTRE_COUT_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_CENTRE_COUT_STRUCTURE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_CENTRE_COUT_STRUCTURE.* FROM V_DIFF_CENTRE_COUT_STRUCTURE ' || get_sql_criterion('CENTRE_COUT_STRUCTURE',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.CENTRE_COUT_STRUCTURE
              ( id, CENTRE_COUT_ID,STRUCTURE_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,CENTRE_COUT_STRUCTURE_ID_SEQ.NEXTVAL), diff_row.CENTRE_COUT_ID,diff_row.STRUCTURE_ID, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_CENTRE_COUT_ID = 1 AND IN_COLUMN_LIST('CENTRE_COUT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT_STRUCTURE SET CENTRE_COUT_ID = diff_row.CENTRE_COUT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT_STRUCTURE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.CENTRE_COUT_STRUCTURE SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_CENTRE_COUT_ID = 1 AND IN_COLUMN_LIST('CENTRE_COUT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT_STRUCTURE SET CENTRE_COUT_ID = diff_row.CENTRE_COUT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT_STRUCTURE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
            UPDATE OSE.CENTRE_COUT_STRUCTURE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'CENTRE_COUT_STRUCTURE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_CENTRE_COUT_STRUCTURE;



  PROCEDURE MAJ_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_STRUCTURE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_STRUCTURE.* FROM V_DIFF_STRUCTURE ' || get_sql_criterion('STRUCTURE',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.STRUCTURE
              ( id, ETABLISSEMENT_ID,LIBELLE_COURT,LIBELLE_LONG,NIVEAU,PARENTE_ID,STRUCTURE_NIV2_ID,TYPE_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,STRUCTURE_ID_SEQ.NEXTVAL), diff_row.ETABLISSEMENT_ID,diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG,diff_row.NIVEAU,diff_row.PARENTE_ID,diff_row.STRUCTURE_NIV2_ID,diff_row.TYPE_ID, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ETABLISSEMENT_ID = 1 AND IN_COLUMN_LIST('ETABLISSEMENT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET ETABLISSEMENT_ID = diff_row.ETABLISSEMENT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NIVEAU = 1 AND IN_COLUMN_LIST('NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET NIVEAU = diff_row.NIVEAU WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PARENTE_ID = 1 AND IN_COLUMN_LIST('PARENTE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET PARENTE_ID = diff_row.PARENTE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_NIV2_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_NIV2_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET STRUCTURE_NIV2_ID = diff_row.STRUCTURE_NIV2_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_ID = 1 AND IN_COLUMN_LIST('TYPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET TYPE_ID = diff_row.TYPE_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.STRUCTURE SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ETABLISSEMENT_ID = 1 AND IN_COLUMN_LIST('ETABLISSEMENT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET ETABLISSEMENT_ID = diff_row.ETABLISSEMENT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NIVEAU = 1 AND IN_COLUMN_LIST('NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET NIVEAU = diff_row.NIVEAU WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PARENTE_ID = 1 AND IN_COLUMN_LIST('PARENTE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET PARENTE_ID = diff_row.PARENTE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_NIV2_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_NIV2_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET STRUCTURE_NIV2_ID = diff_row.STRUCTURE_NIV2_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_ID = 1 AND IN_COLUMN_LIST('TYPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET TYPE_ID = diff_row.TYPE_ID WHERE ID = diff_row.id; END IF;
            UPDATE OSE.STRUCTURE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'STRUCTURE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_STRUCTURE;



  PROCEDURE MAJ_TYPE_INTERVENTION_EP(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_TYPE_INTERVENTION_EP%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_TYPE_INTERVENTION_EP.* FROM V_DIFF_TYPE_INTERVENTION_EP ' || get_sql_criterion('TYPE_INTERVENTION_EP',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.TYPE_INTERVENTION_EP
              ( id, ELEMENT_PEDAGOGIQUE_ID,TYPE_INTERVENTION_ID,VISIBLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,TYPE_INTERVENTION_EP_ID_SEQ.NEXTVAL), diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.TYPE_INTERVENTION_ID,diff_row.VISIBLE, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_INTERVENTION_ID = 1 AND IN_COLUMN_LIST('TYPE_INTERVENTION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET TYPE_INTERVENTION_ID = diff_row.TYPE_INTERVENTION_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VISIBLE = 1 AND IN_COLUMN_LIST('VISIBLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET VISIBLE = diff_row.VISIBLE WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.TYPE_INTERVENTION_EP SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_INTERVENTION_ID = 1 AND IN_COLUMN_LIST('TYPE_INTERVENTION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET TYPE_INTERVENTION_ID = diff_row.TYPE_INTERVENTION_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VISIBLE = 1 AND IN_COLUMN_LIST('VISIBLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET VISIBLE = diff_row.VISIBLE WHERE ID = diff_row.id; END IF;
            UPDATE OSE.TYPE_INTERVENTION_EP SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'TYPE_INTERVENTION_EP', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_TYPE_INTERVENTION_EP;



  PROCEDURE MAJ_DEPARTEMENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_DEPARTEMENT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_DEPARTEMENT.* FROM V_DIFF_DEPARTEMENT ' || get_sql_criterion('DEPARTEMENT',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.DEPARTEMENT
              ( id, LIBELLE_COURT,LIBELLE_LONG, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,DEPARTEMENT_ID_SEQ.NEXTVAL), diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DEPARTEMENT SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DEPARTEMENT SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.DEPARTEMENT SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DEPARTEMENT SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DEPARTEMENT SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
            UPDATE OSE.DEPARTEMENT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'DEPARTEMENT', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_DEPARTEMENT;



  PROCEDURE MAJ_ETAPE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ETAPE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ETAPE.* FROM V_DIFF_ETAPE ' || get_sql_criterion('ETAPE',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.ETAPE
              ( id, DOMAINE_FONCTIONNEL_ID,LIBELLE,NIVEAU,SPECIFIQUE_ECHANGES,STRUCTURE_ID,TYPE_FORMATION_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,ETAPE_ID_SEQ.NEXTVAL), diff_row.DOMAINE_FONCTIONNEL_ID,diff_row.LIBELLE,diff_row.NIVEAU,diff_row.SPECIFIQUE_ECHANGES,diff_row.STRUCTURE_ID,diff_row.TYPE_FORMATION_ID, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_DOMAINE_FONCTIONNEL_ID = 1 AND IN_COLUMN_LIST('DOMAINE_FONCTIONNEL_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET DOMAINE_FONCTIONNEL_ID = diff_row.DOMAINE_FONCTIONNEL_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NIVEAU = 1 AND IN_COLUMN_LIST('NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET NIVEAU = diff_row.NIVEAU WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_SPECIFIQUE_ECHANGES = 1 AND IN_COLUMN_LIST('SPECIFIQUE_ECHANGES',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET SPECIFIQUE_ECHANGES = diff_row.SPECIFIQUE_ECHANGES WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_FORMATION_ID = 1 AND IN_COLUMN_LIST('TYPE_FORMATION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET TYPE_FORMATION_ID = diff_row.TYPE_FORMATION_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.ETAPE SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_DOMAINE_FONCTIONNEL_ID = 1 AND IN_COLUMN_LIST('DOMAINE_FONCTIONNEL_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET DOMAINE_FONCTIONNEL_ID = diff_row.DOMAINE_FONCTIONNEL_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NIVEAU = 1 AND IN_COLUMN_LIST('NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET NIVEAU = diff_row.NIVEAU WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_SPECIFIQUE_ECHANGES = 1 AND IN_COLUMN_LIST('SPECIFIQUE_ECHANGES',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET SPECIFIQUE_ECHANGES = diff_row.SPECIFIQUE_ECHANGES WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_FORMATION_ID = 1 AND IN_COLUMN_LIST('TYPE_FORMATION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET TYPE_FORMATION_ID = diff_row.TYPE_FORMATION_ID WHERE ID = diff_row.id; END IF;
            UPDATE OSE.ETAPE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'ETAPE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_ETAPE;



  PROCEDURE MAJ_PAYS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_PAYS%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_PAYS.* FROM V_DIFF_PAYS ' || get_sql_criterion('PAYS',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.PAYS
              ( id, LIBELLE_COURT,LIBELLE_LONG,TEMOIN_UE,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,PAYS_ID_SEQ.NEXTVAL), diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG,diff_row.TEMOIN_UE,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PAYS SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PAYS SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEMOIN_UE = 1 AND IN_COLUMN_LIST('TEMOIN_UE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PAYS SET TEMOIN_UE = diff_row.TEMOIN_UE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PAYS SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PAYS SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.PAYS SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PAYS SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PAYS SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEMOIN_UE = 1 AND IN_COLUMN_LIST('TEMOIN_UE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PAYS SET TEMOIN_UE = diff_row.TEMOIN_UE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PAYS SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PAYS SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
            UPDATE OSE.PAYS SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'PAYS', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_PAYS;



  PROCEDURE MAJ_GRADE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_GRADE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_GRADE.* FROM V_DIFF_GRADE ' || get_sql_criterion('GRADE',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.GRADE
              ( id, CORPS_ID,ECHELLE,LIBELLE_COURT,LIBELLE_LONG, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,GRADE_ID_SEQ.NEXTVAL), diff_row.CORPS_ID,diff_row.ECHELLE,diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_CORPS_ID = 1 AND IN_COLUMN_LIST('CORPS_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GRADE SET CORPS_ID = diff_row.CORPS_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ECHELLE = 1 AND IN_COLUMN_LIST('ECHELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GRADE SET ECHELLE = diff_row.ECHELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GRADE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GRADE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.GRADE SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_CORPS_ID = 1 AND IN_COLUMN_LIST('CORPS_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GRADE SET CORPS_ID = diff_row.CORPS_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ECHELLE = 1 AND IN_COLUMN_LIST('ECHELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GRADE SET ECHELLE = diff_row.ECHELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GRADE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GRADE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
            UPDATE OSE.GRADE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'GRADE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_GRADE;



  PROCEDURE MAJ_AFFECTATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_AFFECTATION%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_AFFECTATION.* FROM V_DIFF_AFFECTATION ' || get_sql_criterion('AFFECTATION',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.AFFECTATION
              ( id, PERSONNEL_ID,ROLE_ID,STRUCTURE_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,AFFECTATION_ID_SEQ.NEXTVAL), diff_row.PERSONNEL_ID,diff_row.ROLE_ID,diff_row.STRUCTURE_ID, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_PERSONNEL_ID = 1 AND IN_COLUMN_LIST('PERSONNEL_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION SET PERSONNEL_ID = diff_row.PERSONNEL_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ROLE_ID = 1 AND IN_COLUMN_LIST('ROLE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION SET ROLE_ID = diff_row.ROLE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.AFFECTATION SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_PERSONNEL_ID = 1 AND IN_COLUMN_LIST('PERSONNEL_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION SET PERSONNEL_ID = diff_row.PERSONNEL_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ROLE_ID = 1 AND IN_COLUMN_LIST('ROLE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION SET ROLE_ID = diff_row.ROLE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
            UPDATE OSE.AFFECTATION SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'AFFECTATION', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_AFFECTATION;



  PROCEDURE MAJ_TYPE_MODULATEUR_EP(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_TYPE_MODULATEUR_EP%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_TYPE_MODULATEUR_EP.* FROM V_DIFF_TYPE_MODULATEUR_EP ' || get_sql_criterion('TYPE_MODULATEUR_EP',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.TYPE_MODULATEUR_EP
              ( id, ELEMENT_PEDAGOGIQUE_ID,TYPE_MODULATEUR_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,TYPE_MODULATEUR_EP_ID_SEQ.NEXTVAL), diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.TYPE_MODULATEUR_ID, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_MODULATEUR_EP SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_MODULATEUR_ID = 1 AND IN_COLUMN_LIST('TYPE_MODULATEUR_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_MODULATEUR_EP SET TYPE_MODULATEUR_ID = diff_row.TYPE_MODULATEUR_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.TYPE_MODULATEUR_EP SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_MODULATEUR_EP SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_MODULATEUR_ID = 1 AND IN_COLUMN_LIST('TYPE_MODULATEUR_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_MODULATEUR_EP SET TYPE_MODULATEUR_ID = diff_row.TYPE_MODULATEUR_ID WHERE ID = diff_row.id; END IF;
            UPDATE OSE.TYPE_MODULATEUR_EP SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'TYPE_MODULATEUR_EP', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_TYPE_MODULATEUR_EP;



  PROCEDURE MAJ_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_INTERVENANT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_INTERVENANT.* FROM V_DIFF_INTERVENANT ' || get_sql_criterion('INTERVENANT',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.INTERVENANT
              ( id, ANNEE_ID,BIC,CIVILITE_ID,CRITERE_RECHERCHE,DATE_NAISSANCE,DEP_NAISSANCE_CODE_INSEE,DEP_NAISSANCE_LIBELLE,DISCIPLINE_ID,EMAIL,GRADE_ID,IBAN,NOM_PATRONYMIQUE,NOM_USUEL,NUMERO_INSEE,NUMERO_INSEE_CLE,NUMERO_INSEE_PROVISOIRE,PAYS_NAISSANCE_CODE_INSEE,PAYS_NAISSANCE_LIBELLE,PAYS_NATIONALITE_CODE_INSEE,PAYS_NATIONALITE_LIBELLE,PRENOM,STATUT_ID,STRUCTURE_ID,TEL_MOBILE,TEL_PRO,VILLE_NAISSANCE_CODE_INSEE,VILLE_NAISSANCE_LIBELLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,INTERVENANT_ID_SEQ.NEXTVAL), diff_row.ANNEE_ID,diff_row.BIC,diff_row.CIVILITE_ID,diff_row.CRITERE_RECHERCHE,diff_row.DATE_NAISSANCE,diff_row.DEP_NAISSANCE_CODE_INSEE,diff_row.DEP_NAISSANCE_LIBELLE,diff_row.DISCIPLINE_ID,diff_row.EMAIL,diff_row.GRADE_ID,diff_row.IBAN,diff_row.NOM_PATRONYMIQUE,diff_row.NOM_USUEL,diff_row.NUMERO_INSEE,diff_row.NUMERO_INSEE_CLE,diff_row.NUMERO_INSEE_PROVISOIRE,diff_row.PAYS_NAISSANCE_CODE_INSEE,diff_row.PAYS_NAISSANCE_LIBELLE,diff_row.PAYS_NATIONALITE_CODE_INSEE,diff_row.PAYS_NATIONALITE_LIBELLE,diff_row.PRENOM,diff_row.STATUT_ID,diff_row.STRUCTURE_ID,diff_row.TEL_MOBILE,diff_row.TEL_PRO,diff_row.VILLE_NAISSANCE_CODE_INSEE,diff_row.VILLE_NAISSANCE_LIBELLE, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_BIC = 1 AND IN_COLUMN_LIST('BIC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET BIC = diff_row.BIC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CRITERE_RECHERCHE = 1 AND IN_COLUMN_LIST('CRITERE_RECHERCHE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET CRITERE_RECHERCHE = diff_row.CRITERE_RECHERCHE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DATE_NAISSANCE = 1 AND IN_COLUMN_LIST('DATE_NAISSANCE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DATE_NAISSANCE = diff_row.DATE_NAISSANCE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DEP_NAISSANCE_CODE_INSEE = diff_row.DEP_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DEP_NAISSANCE_LIBELLE = diff_row.DEP_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('DISCIPLINE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DISCIPLINE_ID = diff_row.DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_GRADE_ID = 1 AND IN_COLUMN_LIST('GRADE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET GRADE_ID = diff_row.GRADE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_IBAN = 1 AND IN_COLUMN_LIST('IBAN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET IBAN = diff_row.IBAN WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_PATRONYMIQUE = 1 AND IN_COLUMN_LIST('NOM_PATRONYMIQUE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NOM_PATRONYMIQUE = diff_row.NOM_PATRONYMIQUE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_USUEL = 1 AND IN_COLUMN_LIST('NOM_USUEL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NOM_USUEL = diff_row.NOM_USUEL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NUMERO_INSEE = diff_row.NUMERO_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE_CLE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE_CLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NUMERO_INSEE_CLE = diff_row.NUMERO_INSEE_CLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE_PROVISOIRE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE_PROVISOIRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NUMERO_INSEE_PROVISOIRE = diff_row.NUMERO_INSEE_PROVISOIRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NAISSANCE_CODE_INSEE = diff_row.PAYS_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NAISSANCE_LIBELLE = diff_row.PAYS_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NATIONALITE_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_NATIONALITE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NATIONALITE_CODE_INSEE = diff_row.PAYS_NATIONALITE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NATIONALITE_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_NATIONALITE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NATIONALITE_LIBELLE = diff_row.PAYS_NATIONALITE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRENOM = 1 AND IN_COLUMN_LIST('PRENOM',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PRENOM = diff_row.PRENOM WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STATUT_ID = 1 AND IN_COLUMN_LIST('STATUT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET STATUT_ID = diff_row.STATUT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_MOBILE = 1 AND IN_COLUMN_LIST('TEL_MOBILE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET TEL_MOBILE = diff_row.TEL_MOBILE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_PRO = 1 AND IN_COLUMN_LIST('TEL_PRO',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET TEL_PRO = diff_row.TEL_PRO WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('VILLE_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET VILLE_NAISSANCE_CODE_INSEE = diff_row.VILLE_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('VILLE_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET VILLE_NAISSANCE_LIBELLE = diff_row.VILLE_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.INTERVENANT SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_BIC = 1 AND IN_COLUMN_LIST('BIC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET BIC = diff_row.BIC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CRITERE_RECHERCHE = 1 AND IN_COLUMN_LIST('CRITERE_RECHERCHE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET CRITERE_RECHERCHE = diff_row.CRITERE_RECHERCHE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DATE_NAISSANCE = 1 AND IN_COLUMN_LIST('DATE_NAISSANCE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DATE_NAISSANCE = diff_row.DATE_NAISSANCE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DEP_NAISSANCE_CODE_INSEE = diff_row.DEP_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DEP_NAISSANCE_LIBELLE = diff_row.DEP_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('DISCIPLINE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DISCIPLINE_ID = diff_row.DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_GRADE_ID = 1 AND IN_COLUMN_LIST('GRADE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET GRADE_ID = diff_row.GRADE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_IBAN = 1 AND IN_COLUMN_LIST('IBAN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET IBAN = diff_row.IBAN WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_PATRONYMIQUE = 1 AND IN_COLUMN_LIST('NOM_PATRONYMIQUE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NOM_PATRONYMIQUE = diff_row.NOM_PATRONYMIQUE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_USUEL = 1 AND IN_COLUMN_LIST('NOM_USUEL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NOM_USUEL = diff_row.NOM_USUEL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NUMERO_INSEE = diff_row.NUMERO_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE_CLE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE_CLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NUMERO_INSEE_CLE = diff_row.NUMERO_INSEE_CLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE_PROVISOIRE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE_PROVISOIRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NUMERO_INSEE_PROVISOIRE = diff_row.NUMERO_INSEE_PROVISOIRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NAISSANCE_CODE_INSEE = diff_row.PAYS_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NAISSANCE_LIBELLE = diff_row.PAYS_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NATIONALITE_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_NATIONALITE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NATIONALITE_CODE_INSEE = diff_row.PAYS_NATIONALITE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NATIONALITE_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_NATIONALITE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NATIONALITE_LIBELLE = diff_row.PAYS_NATIONALITE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRENOM = 1 AND IN_COLUMN_LIST('PRENOM',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PRENOM = diff_row.PRENOM WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STATUT_ID = 1 AND IN_COLUMN_LIST('STATUT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET STATUT_ID = diff_row.STATUT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_MOBILE = 1 AND IN_COLUMN_LIST('TEL_MOBILE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET TEL_MOBILE = diff_row.TEL_MOBILE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_PRO = 1 AND IN_COLUMN_LIST('TEL_PRO',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET TEL_PRO = diff_row.TEL_PRO WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('VILLE_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET VILLE_NAISSANCE_CODE_INSEE = diff_row.VILLE_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('VILLE_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET VILLE_NAISSANCE_LIBELLE = diff_row.VILLE_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;
            UPDATE OSE.INTERVENANT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'INTERVENANT', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_INTERVENANT;



  PROCEDURE MAJ_ELEMENT_TAUX_REGIMES(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ELEMENT_TAUX_REGIMES%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ELEMENT_TAUX_REGIMES.* FROM V_DIFF_ELEMENT_TAUX_REGIMES ' || get_sql_criterion('ELEMENT_TAUX_REGIMES',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.ELEMENT_TAUX_REGIMES
              ( id, ELEMENT_PEDAGOGIQUE_ID,TAUX_FA,TAUX_FC,TAUX_FI, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,ELEMENT_TAUX_REGIMES_ID_SEQ.NEXTVAL), diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.TAUX_FA,diff_row.TAUX_FC,diff_row.TAUX_FI, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FA = 1 AND IN_COLUMN_LIST('TAUX_FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FA = diff_row.TAUX_FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FC = 1 AND IN_COLUMN_LIST('TAUX_FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FC = diff_row.TAUX_FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FI = 1 AND IN_COLUMN_LIST('TAUX_FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FI = diff_row.TAUX_FI WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.ELEMENT_TAUX_REGIMES SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FA = 1 AND IN_COLUMN_LIST('TAUX_FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FA = diff_row.TAUX_FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FC = 1 AND IN_COLUMN_LIST('TAUX_FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FC = diff_row.TAUX_FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FI = 1 AND IN_COLUMN_LIST('TAUX_FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FI = diff_row.TAUX_FI WHERE ID = diff_row.id; END IF;
            UPDATE OSE.ELEMENT_TAUX_REGIMES SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'ELEMENT_TAUX_REGIMES', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_ELEMENT_TAUX_REGIMES;



  PROCEDURE MAJ_EFFECTIFS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_EFFECTIFS%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_EFFECTIFS.* FROM V_DIFF_EFFECTIFS ' || get_sql_criterion('EFFECTIFS',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.EFFECTIFS
              ( id, ANNEE_ID,ELEMENT_PEDAGOGIQUE_ID,FA,FC,FI, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,EFFECTIFS_ID_SEQ.NEXTVAL), diff_row.ANNEE_ID,diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.FA,diff_row.FC,diff_row.FI, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FA = 1 AND IN_COLUMN_LIST('FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FA = diff_row.FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FC = 1 AND IN_COLUMN_LIST('FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FC = diff_row.FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FI = 1 AND IN_COLUMN_LIST('FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FI = diff_row.FI WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.EFFECTIFS SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FA = 1 AND IN_COLUMN_LIST('FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FA = diff_row.FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FC = 1 AND IN_COLUMN_LIST('FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FC = diff_row.FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FI = 1 AND IN_COLUMN_LIST('FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FI = diff_row.FI WHERE ID = diff_row.id; END IF;
            UPDATE OSE.EFFECTIFS SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'EFFECTIFS', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_EFFECTIFS;



  PROCEDURE MAJ_DOMAINE_FONCTIONNEL(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_DOMAINE_FONCTIONNEL%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_DOMAINE_FONCTIONNEL.* FROM V_DIFF_DOMAINE_FONCTIONNEL ' || get_sql_criterion('DOMAINE_FONCTIONNEL',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.DOMAINE_FONCTIONNEL
              ( id, LIBELLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,DOMAINE_FONCTIONNEL_ID_SEQ.NEXTVAL), diff_row.LIBELLE, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DOMAINE_FONCTIONNEL SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.DOMAINE_FONCTIONNEL SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DOMAINE_FONCTIONNEL SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
            UPDATE OSE.DOMAINE_FONCTIONNEL SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'DOMAINE_FONCTIONNEL', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_DOMAINE_FONCTIONNEL;



  PROCEDURE MAJ_CENTRE_COUT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_CENTRE_COUT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_CENTRE_COUT.* FROM V_DIFF_CENTRE_COUT ' || get_sql_criterion('CENTRE_COUT',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.CENTRE_COUT
              ( id, ACTIVITE_ID,LIBELLE,PARENT_ID,TYPE_RESSOURCE_ID,UNITE_BUDGETAIRE, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,CENTRE_COUT_ID_SEQ.NEXTVAL), diff_row.ACTIVITE_ID,diff_row.LIBELLE,diff_row.PARENT_ID,diff_row.TYPE_RESSOURCE_ID,diff_row.UNITE_BUDGETAIRE, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ACTIVITE_ID = 1 AND IN_COLUMN_LIST('ACTIVITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT SET ACTIVITE_ID = diff_row.ACTIVITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PARENT_ID = 1 AND IN_COLUMN_LIST('PARENT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT SET PARENT_ID = diff_row.PARENT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_RESSOURCE_ID = 1 AND IN_COLUMN_LIST('TYPE_RESSOURCE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT SET TYPE_RESSOURCE_ID = diff_row.TYPE_RESSOURCE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_UNITE_BUDGETAIRE = 1 AND IN_COLUMN_LIST('UNITE_BUDGETAIRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT SET UNITE_BUDGETAIRE = diff_row.UNITE_BUDGETAIRE WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.CENTRE_COUT SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ACTIVITE_ID = 1 AND IN_COLUMN_LIST('ACTIVITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT SET ACTIVITE_ID = diff_row.ACTIVITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PARENT_ID = 1 AND IN_COLUMN_LIST('PARENT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT SET PARENT_ID = diff_row.PARENT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_RESSOURCE_ID = 1 AND IN_COLUMN_LIST('TYPE_RESSOURCE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT SET TYPE_RESSOURCE_ID = diff_row.TYPE_RESSOURCE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_UNITE_BUDGETAIRE = 1 AND IN_COLUMN_LIST('UNITE_BUDGETAIRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT SET UNITE_BUDGETAIRE = diff_row.UNITE_BUDGETAIRE WHERE ID = diff_row.id; END IF;
            UPDATE OSE.CENTRE_COUT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'CENTRE_COUT', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_CENTRE_COUT;

  -- END OF AUTOMATIC GENERATION --
END ose_import;
/



-- ********************************************************************* --
-- *          à faire APRÈS avoir mis à jour le code source            * --
-- ********************************************************************* --

-- mettre paye-etat à paie-etat dans type_ressource

-- attention aux centres de couts sans unité budgétaire ! ! : à compléter manuellement puis à rendre NOT NULL
-- privilèges à mettre à jour
-- messages d'erreur aussi ? ?

BEGIN DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"'); END;
/
BEGIN OSE_FORMULE.CALCULER_TOUT; END;
/