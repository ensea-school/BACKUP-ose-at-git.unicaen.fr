-- ********************************************************************* --
-- *          à faire AVANT avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"', force => TRUE); END; 
/




--------------------------------------------------------
--  Fichier créé - vendredi-décembre-19-2014   
--------------------------------------------------------
---------------------------
--Nouveau SEQUENCE
--WF_TMP_INTERVENANT_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."WF_TMP_INTERVENANT_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE NOORDER NOCYCLE;
---------------------------
--Nouveau SEQUENCE
--PIECE_JOINTE_FICHIER_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."PIECE_JOINTE_FICHIER_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE NOORDER NOCYCLE;
---------------------------
--Nouveau SEQUENCE
--NOTIFICATION_INDICATEUR_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."NOTIFICATION_INDICATEUR_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 21 CACHE 20 NOORDER NOCYCLE;
---------------------------
--Nouveau SEQUENCE
--INDICATEUR_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."INDICATEUR_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 61 CACHE 20 NOORDER NOCYCLE;
---------------------------
--Nouveau SEQUENCE
--FORMULE_RESULTAT_SERVIC_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."FORMULE_RESULTAT_SERVIC_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 41207 NOCACHE NOORDER NOCYCLE;
---------------------------
--Nouveau SEQUENCE
--FORMULE_RESULTAT_REFERE_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."FORMULE_RESULTAT_REFERE_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 164 NOCACHE NOORDER NOCYCLE;
---------------------------
--Nouveau SEQUENCE
--CONTRAT_FICHIER_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."CONTRAT_FICHIER_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE NOORDER NOCYCLE;

CREATE SEQUENCE CENTRE_COUT_ID_SEQ INCREMENT BY 1 MAXVALUE 9999999999999999999999999999 MINVALUE 1 NOCACHE;             
CREATE SEQUENCE DOTATION_ID_SEQ INCREMENT BY 1 MAXVALUE 9999999999999999999999999999 MINVALUE 1 NOCACHE;                
CREATE SEQUENCE HEURES_MISES_EN_PAIEMEN_ID_SEQ INCREMENT BY 1 MAXVALUE 9999999999999999999999999999 MINVALUE 1 NOCACHE; 
CREATE SEQUENCE MISE_EN_PAIEMENT_ID_SEQ INCREMENT BY 1 MAXVALUE 9999999999999999999999999999 MINVALUE 1 NOCACHE;        
CREATE SEQUENCE TYPE_CENTRE_COUT_ID_SEQ INCREMENT BY 1 MAXVALUE 9999999999999999999999999999 MINVALUE 1 NOCACHE;        
CREATE SEQUENCE TYPE_DOTATION_ID_SEQ INCREMENT BY 1 MAXVALUE 9999999999999999999999999999 MINVALUE 1 NOCACHE;           
CREATE SEQUENCE VALIDATION_SERVICE_REFE_ID_SEQ INCREMENT BY 1 MAXVALUE 9999999999999999999999999999 MINVALUE 1 NOCACHE; 

---------------------------
--Nouveau TABLE
--TYPE_CENTRE_COUT
---------------------------
  CREATE TABLE "OSE"."TYPE_CENTRE_COUT" 
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"CODE" VARCHAR2(50 CHAR) NOT NULL ENABLE,
	"LIBELLE" VARCHAR2(200 CHAR) NOT NULL ENABLE,
	"HISTO_CREATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
	"HISTO_CREATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
	"HISTO_DESTRUCTION" DATE,
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0),
	CONSTRAINT "TYPE_CENTRE_COUT_PK" PRIMARY KEY ("ID") ENABLE,
	CONSTRAINT "TYPE_CENTRE_COUT_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE,
	CONSTRAINT "TYPE_CENTRE_COUT_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE,
	CONSTRAINT "TYPE_CENTRE_COUT_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE
   );
---------------------------
--Modifié TABLE
--SERVICE
---------------------------
ALTER TABLE "OSE"."SERVICE" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."SERVICE" DROP ("VALIDITE_FIN");


---------------------------
--Modifié TABLE
--FORMULE_RESULTAT
---------------------------
ALTER TABLE "OSE"."FORMULE_RESULTAT" ADD ("TO_DELETE" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE);

---------------------------
--Nouveau TABLE
--VALIDATION_SERVICE_REFERENTIEL
---------------------------
  CREATE TABLE "OSE"."VALIDATION_SERVICE_REFERENTIEL" 
   (	"VALIDATION_ID" NUMBER(*,0) NOT NULL ENABLE,
	"SERVICE_REFERENTIEL_ID" NUMBER(*,0) NOT NULL ENABLE,
	CONSTRAINT "VALIDATION_SR_PK" PRIMARY KEY ("VALIDATION_ID","SERVICE_REFERENTIEL_ID") ENABLE,
	CONSTRAINT "VSR_SERVICE_REFERENTIEL_FK" FOREIGN KEY ("SERVICE_REFERENTIEL_ID")
	 REFERENCES "OSE"."SERVICE_REFERENTIEL" ("ID") ON DELETE CASCADE ENABLE,
	CONSTRAINT "VSR_VALIDATION_FK" FOREIGN KEY ("VALIDATION_ID")
	 REFERENCES "OSE"."VALIDATION" ("ID") ON DELETE CASCADE ENABLE
   );

---------------------------
--Modifié TABLE
--VOLUME_HORAIRE
---------------------------
ALTER TABLE "OSE"."VOLUME_HORAIRE" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."VOLUME_HORAIRE" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--PERIODE
---------------------------
ALTER TABLE "OSE"."PERIODE" DROP ("TYPE_INTERVENANT_ID");

---------------------------
--Modifié TABLE
--MODIFICATION_SERVICE_DU
---------------------------
ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" DROP ("VALIDITE_FIN");

---------------------------
--Nouveau TABLE
--INDICATEUR
---------------------------
  CREATE TABLE "OSE"."INDICATEUR" 
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"CODE" VARCHAR2(128 CHAR) NOT NULL ENABLE,
	"TYPE" VARCHAR2(64 CHAR) NOT NULL ENABLE,
	"LIBELLE" VARCHAR2(512 CHAR) NOT NULL ENABLE,
	"ORDRE" NUMBER(*,0) DEFAULT 100 NOT NULL ENABLE,
	"ENABLED" NUMBER(1,0) DEFAULT 1 NOT NULL ENABLE,
	CONSTRAINT "INDICATEUR_PK" PRIMARY KEY ("ID") ENABLE,
	CONSTRAINT "INDICATEUR_CODE_UN" UNIQUE ("CODE") ENABLE
   );
   
---------------------------
--Nouveau TABLE
--NOTIFICATION_INDICATEUR
---------------------------
  CREATE TABLE "OSE"."NOTIFICATION_INDICATEUR" 
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"INDICATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
	"PERSONNEL_ID" NUMBER(*,0) NOT NULL ENABLE,
	"STRUCTURE_ID" NUMBER(*,0),
	"FREQUENCE" VARCHAR2(128 CHAR) NOT NULL ENABLE,
	"DATE_ABONNEMENT" DATE NOT NULL ENABLE,
	"DATE_DERN_NOTIF" DATE,
	CONSTRAINT "NOTIF_INDICATEUR_PK" PRIMARY KEY ("ID") ENABLE,
	CONSTRAINT "NOTIF_INDICATEUR_IFK" FOREIGN KEY ("INDICATEUR_ID")
	 REFERENCES "OSE"."INDICATEUR" ("ID") ENABLE,
	CONSTRAINT "NOTIF_INDICATEUR_SFK" FOREIGN KEY ("STRUCTURE_ID")
	 REFERENCES "OSE"."STRUCTURE" ("ID") ENABLE,
	CONSTRAINT "NOTIF_INDICATEUR_UFK" FOREIGN KEY ("PERSONNEL_ID")
	 REFERENCES "OSE"."PERSONNEL" ("ID") ENABLE
   );


---------------------------
--Nouveau TABLE
--FORMULE_RESULTAT_REFERENTIEL
---------------------------
  CREATE TABLE "OSE"."FORMULE_RESULTAT_REFERENTIEL" 
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"FORMULE_RESULTAT_ID" NUMBER(*,0) NOT NULL ENABLE,
	"SERVICE_REFERENTIEL_ID" NUMBER(*,0) NOT NULL ENABLE,
	"SERVICE_ASSURE" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"HEURES_SERVICE" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"HEURES_COMPL_REFERENTIEL" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"TO_DELETE" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE,
	CONSTRAINT "FRR_PK" PRIMARY KEY ("ID") ENABLE,
	CONSTRAINT "FRR_FORMULE_RESULTAT_FK" FOREIGN KEY ("FORMULE_RESULTAT_ID")
	 REFERENCES "OSE"."FORMULE_RESULTAT" ("ID") ON DELETE CASCADE ENABLE,
	CONSTRAINT "FRR_SERVICE_REFERENTIEL_FK" FOREIGN KEY ("SERVICE_REFERENTIEL_ID")
	 REFERENCES "OSE"."SERVICE_REFERENTIEL" ("ID") ON DELETE CASCADE ENABLE
   );
---------------------------
--Modifié TABLE
--ELEMENT_PEDAGOGIQUE
---------------------------
ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" ADD ("CENTRE_COUT_ID" NUMBER(*,0));

---------------------------
--Nouveau TABLE
--CENTRE_COUT
---------------------------
  CREATE TABLE "OSE"."CENTRE_COUT" 
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"LIBELLE" VARCHAR2(200 CHAR) NOT NULL ENABLE,
	"TYPE_ID" NUMBER(*,0) NOT NULL ENABLE,
	"PARENT_ID" NUMBER(*,0),
	"STRUCTURE_ID" NUMBER(*,0) NOT NULL ENABLE,
	"PAIE_ETAT" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE,
	"FI" NUMBER(1,0) DEFAULT 1 NOT NULL ENABLE,
	"FA" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE,
	"FC" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE,
	"REFERENTIEL" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE,
	"SOURCE_ID" NUMBER(*,0) NOT NULL ENABLE,
	"SOURCE_CODE" VARCHAR2(100 CHAR) NOT NULL ENABLE,
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE NOT NULL ENABLE,
	"VALIDITE_FIN" DATE,
	"HISTO_CREATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
	"HISTO_CREATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
	"HISTO_DESTRUCTION" DATE,
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0),
	CONSTRAINT "CENTRE_COUT_PK" PRIMARY KEY ("ID") ENABLE,
	CONSTRAINT "CENTRE_COUT_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE,
	CONSTRAINT "CENTRE_COUT_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE,
	CONSTRAINT "CENTRE_COUT_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE,
	CONSTRAINT "CENTRE_COUT_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	 REFERENCES "OSE"."SOURCE" ("ID") ON DELETE CASCADE ENABLE,
	CONSTRAINT "CENTRE_COUT_STRUCTURE_FK" FOREIGN KEY ("STRUCTURE_ID")
	 REFERENCES "OSE"."STRUCTURE" ("ID") ENABLE,
	CONSTRAINT "CENTRE_COUT_TYPE_CC_FK" FOREIGN KEY ("TYPE_ID")
	 REFERENCES "OSE"."TYPE_CENTRE_COUT" ("ID") ON DELETE CASCADE ENABLE
   );
   
   ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" ADD CONSTRAINT "EP_CENTRE_COUT_FK" FOREIGN KEY ("CENTRE_COUT_ID") REFERENCES "OSE"."CENTRE_COUT"("ID") ENABLE;
---------------------------
--Modifié TABLE
--SERVICE_REFERENTIEL
---------------------------
ALTER TABLE "OSE"."SERVICE_REFERENTIEL" ADD ("TYPE_VOLUME_HORAIRE_ID" NUMBER(*,0));
drop trigger "OSE"."F_SERVICE_REFERENTIEL";
update service_referentiel set type_volume_horaire_id = 1;
ALTER TABLE SERVICE_REFERENTIEL MODIFY (TYPE_VOLUME_HORAIRE_ID NOT NULL);
ALTER TABLE "OSE"."SERVICE_REFERENTIEL" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."SERVICE_REFERENTIEL" DROP ("VALIDITE_FIN");
ALTER TABLE "OSE"."SERVICE_REFERENTIEL" ADD CONSTRAINT "SR_TYPE_VOLUME_HORAIRE_FK" FOREIGN KEY ("TYPE_VOLUME_HORAIRE_ID") REFERENCES "OSE"."TYPE_VOLUME_HORAIRE"("ID") ENABLE;

---------------------------
--Modifié TABLE
--WF_TMP_INTERVENANT
---------------------------
ALTER TABLE "OSE"."WF_TMP_INTERVENANT" ADD CONSTRAINT "WF_TMP_INTERVENANT_PK" PRIMARY KEY ("INTERVENANT_ID") ENABLE;

---------------------------
--Modifié TABLE
--SYNC_LOG
---------------------------
ALTER TABLE "OSE"."SYNC_LOG" MODIFY ("DATE_SYNC" TIMESTAMP(6));

---------------------------
--Nouveau TABLE
--TYPE_DOTATION
---------------------------
  CREATE TABLE "OSE"."TYPE_DOTATION" 
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"LIBELLE" VARCHAR2(200 CHAR) NOT NULL ENABLE,
	"PAIE_ETAT" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE,
	"SOURCE_CODE" VARCHAR2(100 CHAR),
	"SOURCE_ID" NUMBER(*,0) NOT NULL ENABLE,
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE NOT NULL ENABLE,
	"VALIDITE_FIN" DATE,
	"HISTO_CREATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
	"HISTO_CREATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
	"HISTO_DESTRUCTION" DATE,
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0),
	CONSTRAINT "TYPE_DOTATION_PK" PRIMARY KEY ("ID") ENABLE,
	CONSTRAINT "TYPE_DOTATION_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE,
	CONSTRAINT "TYPE_DOTATION_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE,
	CONSTRAINT "TYPE_DOTATION_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE,
	CONSTRAINT "TYPE_DOTATION_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	 REFERENCES "OSE"."SOURCE" ("ID") ON DELETE CASCADE ENABLE
   );
---------------------------
--Nouveau TABLE
--FORMULE_RESULTAT_SERVICE
---------------------------
  CREATE TABLE "OSE"."FORMULE_RESULTAT_SERVICE" 
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"FORMULE_RESULTAT_ID" NUMBER(*,0) NOT NULL ENABLE,
	"SERVICE_ID" NUMBER(*,0) NOT NULL ENABLE,
	"SERVICE_ASSURE" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"HEURES_SERVICE" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"HEURES_COMPL_FI" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"HEURES_COMPL_FA" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"HEURES_COMPL_FC" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"TO_DELETE" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE,
	CONSTRAINT "FORMULE_RESULTAT_SERVICE_PK" PRIMARY KEY ("ID") ENABLE,
	CONSTRAINT "FRS_FORMULE_RESULTAT_FK" FOREIGN KEY ("FORMULE_RESULTAT_ID")
	 REFERENCES "OSE"."FORMULE_RESULTAT" ("ID") ON DELETE CASCADE ENABLE,
	CONSTRAINT "FRS_SERVICE_FK" FOREIGN KEY ("SERVICE_ID")
	 REFERENCES "OSE"."SERVICE" ("ID") ON DELETE CASCADE ENABLE
   );
---------------------------
--Nouveau TABLE
--DOTATION
---------------------------
  CREATE TABLE "OSE"."DOTATION" 
   (	"ID" NUMBER(*,0) CONSTRAINT "NNC_DOTATION_ID" NOT NULL ENABLE,
	"TYPE_ID" NUMBER(*,0) CONSTRAINT "NNC_DOTATION_TYPE_ID" NOT NULL ENABLE,
	"ANNEE_ID" NUMBER(*,0) CONSTRAINT "NNC_DOTATION_ANNEE_ID" NOT NULL ENABLE,
	"STRUCTURE_ID" NUMBER(*,0) CONSTRAINT "NNC_DOTATION_STRUCTURE_ID" NOT NULL ENABLE,
	"HEURES" FLOAT(126) DEFAULT 0 CONSTRAINT "NNC_DOTATION_HEURES" NOT NULL ENABLE,
	"HISTO_CREATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
	"HISTO_CREATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
	"HISTO_DESTRUCTION" DATE,
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0),
	CONSTRAINT "DOTATION_PK" PRIMARY KEY ("ID") ENABLE,
	CONSTRAINT "DOTATION_ANNEE_FK" FOREIGN KEY ("ANNEE_ID")
	 REFERENCES "OSE"."ANNEE" ("ID") ON DELETE CASCADE ENABLE,
	CONSTRAINT "DOTATION_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE,
	CONSTRAINT "DOTATION_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE,
	CONSTRAINT "DOTATION_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE,
	CONSTRAINT "DOTATION_STRUCTURE_FK" FOREIGN KEY ("STRUCTURE_ID")
	 REFERENCES "OSE"."STRUCTURE" ("ID") ON DELETE CASCADE ENABLE,
	CONSTRAINT "DOTATION_TYPE_DOTATION_FK" FOREIGN KEY ("TYPE_ID")
	 REFERENCES "OSE"."TYPE_DOTATION" ("ID") ON DELETE CASCADE ENABLE
   );
---------------------------
--Modifié TABLE
--WF_INTERVENANT_ETAPE
---------------------------
ALTER TABLE "OSE"."WF_INTERVENANT_ETAPE" ADD ("ATTEIGNABLE" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE);
ALTER TABLE "OSE"."WF_INTERVENANT_ETAPE" ADD ("DATE_CREATION" DATE DEFAULT SYSDATE NOT NULL ENABLE);
ALTER TABLE "OSE"."WF_INTERVENANT_ETAPE" ADD ("STRUCTURE_ID" NUMBER(38,0) DEFAULT null);
ALTER TABLE "OSE"."WF_INTERVENANT_ETAPE" ADD CONSTRAINT "WF_INTERVENANT_ETAPE_SFK" FOREIGN KEY ("STRUCTURE_ID") REFERENCES "OSE"."STRUCTURE"("ID") ON DELETE CASCADE ENABLE;

---------------------------
--Nouveau TABLE
--MISE_EN_PAIEMENT
---------------------------
  CREATE TABLE "OSE"."MISE_EN_PAIEMENT" 
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"FORMULE_SERVICE_ID" NUMBER(*,0) NOT NULL ENABLE,
	"FORMULE_REFERENTIEL_ID" NUMBER(*,0) NOT NULL ENABLE,
	"DATE_MISE_EN_PAIEMENT" DATE,
	"PERIODE_PAIEMENT_ID" NUMBER(*,0),
	"VALIDATION_ID" NUMBER(*,0),
	"DATE_VALIDATION" DATE,
	"HISTO_CREATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
	"HISTO_CREATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
	"HISTO_DESTRUCTION" DATE,
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0),
	CONSTRAINT "MISE_EN_PAIEMENT_PK" PRIMARY KEY ("ID") ENABLE,
	CONSTRAINT "MEP_FORMULE_REFERENTIEL_FK" FOREIGN KEY ("FORMULE_REFERENTIEL_ID")
	 REFERENCES "OSE"."FORMULE_RESULTAT_REFERENTIEL" ("ID") ENABLE,
	CONSTRAINT "MEP_FORMULE_SERVICE_FK" FOREIGN KEY ("FORMULE_SERVICE_ID")
	 REFERENCES "OSE"."FORMULE_RESULTAT_SERVICE" ("ID") ENABLE,
	CONSTRAINT "MISE_EN_PAIEMENT_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE,
	CONSTRAINT "MISE_EN_PAIEMENT_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE,
	CONSTRAINT "MISE_EN_PAIEMENT_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE,
	CONSTRAINT "MISE_EN_PAIEMENT_PERIODE_FK" FOREIGN KEY ("PERIODE_PAIEMENT_ID")
	 REFERENCES "OSE"."PERIODE" ("ID") ENABLE,
	CONSTRAINT "MISE_EN_PAIEMENT_VALIDATION_FK" FOREIGN KEY ("VALIDATION_ID")
	 REFERENCES "OSE"."VALIDATION" ("ID") ENABLE
   );
   
   
---------------------------
--Nouveau TABLE
--HEURES_MISES_EN_PAIEMENT
---------------------------
  CREATE TABLE "OSE"."HEURES_MISES_EN_PAIEMENT" 
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"MISE_EN_PAIEMENT_ID" NUMBER(*,0) NOT NULL ENABLE,
	"CENTRE_COUT_ID" NUMBER(*,0) NOT NULL ENABLE,
	"HEURES_FI" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"HEURES_FA" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"HEURES_FC" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"HEURES_REFERENTIEL" FLOAT(126) DEFAULT 0 NOT NULL ENABLE,
	"HISTO_CREATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
	"HISTO_CREATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
	"HISTO_DESTRUCTION" DATE,
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0),
	CONSTRAINT "HEURES_MISES_EN_PAIEMENT_PK" PRIMARY KEY ("ID") ENABLE,
	CONSTRAINT "HEURES_MISES_EN_PAIEMENT_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE,
	CONSTRAINT "HEURES_MISES_EN_PAIEMENT_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE,
	CONSTRAINT "HEURES_MISES_EN_PAIEMENT_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE,
	CONSTRAINT "HMEP_CENTRE_COUT_FK" FOREIGN KEY ("CENTRE_COUT_ID")
	 REFERENCES "OSE"."CENTRE_COUT" ("ID") ON DELETE CASCADE ENABLE,
	CONSTRAINT "HMEP_MISE_EN_PAIEMENT_FK" FOREIGN KEY ("MISE_EN_PAIEMENT_ID")
	 REFERENCES "OSE"."MISE_EN_PAIEMENT" ("ID") ON DELETE CASCADE ENABLE
   );


---------------------------
--Nouveau VIEW
--V_FORMULE_SERVICE_MODIFIE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_SERVICE_MODIFIE" 
 ( "ID", "INTERVENANT_ID", "ANNEE_ID", "HEURES"
  )  AS 
  SELECT
  msd.intervenant_id id,
  msd.intervenant_id,
  msd.annee_id,
  NVL( SUM( msd.heures * mms.multiplicateur ), 0 ) heures
FROM
  modification_service_du msd
  JOIN MOTIF_MODIFICATION_SERVICE mms ON mms.id = msd.motif_id
WHERE
  1 = ose_divers.comprise_entre( mms.validite_debut, mms.validite_fin,      ose_formule.get_date_obs )
GROUP BY
  msd.intervenant_id,
  msd.annee_id;
---------------------------
--Modifié VIEW
--V_FORMULE_VOLUME_HORAIRE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_VOLUME_HORAIRE" 
 ( "ID", "SERVICE_ID", "INTERVENANT_ID", "ANNEE_ID", "TYPE_INTERVENTION_ID", "TYPE_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ORDRE", "HEURES", "TAUX_SERVICE_DU", "TAUX_SERVICE_COMPL"
  )  AS 
  SELECT
  vh.id                       id,
  s.id                        service_id,
  s.intervenant_id            intervenant_id,
  s.annee_id                  annee_id,
  ti.id                       type_intervention_id,
  vh.type_volume_horaire_id   type_volume_horaire_id,
  evh.id                      etat_volume_horaire_id,
  evh.ordre                   etat_volume_horaire_ordre,
  vh.heures                   heures,
  ti.taux_hetd_service        taux_service_du,
  ti.taux_hetd_complementaire taux_service_compl
FROM
  volume_horaire               vh
  JOIN service                  s ON s.id     = vh.service_id
  JOIN type_intervention       ti ON ti.id    = vh.type_intervention_id
  JOIN v_volume_horaire_etat  vhe ON vhe.volume_horaire_id = vh.id
  JOIN etat_volume_horaire    evh ON evh.id = vhe.etat_volume_horaire_id
WHERE
  1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction, ose_formule.get_date_obs )
  AND 1 = ose_divers.comprise_entre( s.histo_creation,   s.histo_destruction,   ose_formule.get_date_obs )
  AND vh.heures <> 0
  AND vh.motif_non_paiement_id IS NULL;
---------------------------
--Modifié VIEW
--V_FORMULE_SERVICE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_SERVICE" 
 ( "ID", "INTERVENANT_ID", "ANNEE_ID", "TAUX_FI", "TAUX_FA", "TAUX_FC", "STRUCTURE_AFF_ID", "STRUCTURE_ENS_ID", "PONDERATION_SERVICE_DU", "PONDERATION_SERVICE_COMPL"
  )  AS 
  SELECT
  s.id              id,
  s.intervenant_id  intervenant_id,
  s.annee_id        annee_id,
  CASE WHEN ep.id IS NOT NULL THEN ep.taux_fi ELSE 1 END taux_fi,
  CASE WHEN ep.id IS NOT NULL THEN ep.taux_fa ELSE 0 END taux_fa,
  CASE WHEN ep.id IS NOT NULL THEN ep.taux_fc ELSE 0 END taux_fc,
  s.structure_aff_id,
  s.structure_ens_id,
  NVL( EXP (SUM (LN (m.ponderation_service_du))), 1) ponderation_service_du,
  NVL( EXP (SUM (LN (m.ponderation_service_compl))), 1) ponderation_service_compl
FROM
  service s
  LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN element_modulateur em ON em.element_id = s.element_pedagogique_id
        AND em.annee_id = s.annee_id
        AND 1 = ose_divers.comprise_entre( em.histo_creation, em.histo_destruction, ose_formule.get_date_obs )
  LEFT JOIN modulateur         m ON m.id = em.modulateur_id
WHERE
  1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction, ose_formule.get_date_obs )
GROUP BY
  s.id,
  s.intervenant_id,
  s.annee_id,
  ep.id,
  ep.taux_fi, ep.taux_fa, ep.taux_fc,
  s.structure_aff_id, s.structure_ens_id;
---------------------------
--Modifié VIEW
--V_TBL_SERVICE_RESUME
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_SERVICE_RESUME" 
 ( "ANNEE_ID", "TYPE_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID", "SERVICE_ID", "INTERVENANT_ID", "TYPE_INTERVENANT_ID", "STRUCTURE_AFF_ID", "STRUCTURE_ENS_ID", "NIVEAU_FORMATION_ID", "ETAPE_ID", "ELEMENT_PEDAGOGIQUE_ID", "INTERVENANT_CODE", "INTERVENANT_NOM", "INTERVENANT_TYPE_CODE", "SERVICE_DU", "HEURES_SOLDE", "HEURES_COMPL"
  )  AS 
  SELECT
  fr.annee_id                     annee_id,
  fr.type_volume_horaire_id       type_volume_horaire_id,
  fr.etat_volume_horaire_id       etat_volume_horaire_id,
  s.id                            service_id,
  i.id                            intervenant_id,
  ti.id                           type_intervenant_id,
  CASE WHEN ti.code = 'E' THEN NULL ELSE s.structure_aff_id END structure_aff_id,
  
  s.structure_ens_id              structure_ens_id,
  enf.niveau_formation_id         niveau_formation_id,
  etp.id                          etape_id,
  ep.id                           element_pedagogique_id,

  i.source_code                   intervenant_code,
  i.nom_usuel || ' ' || i.prenom  intervenant_nom,
  ti.code                         intervenant_type_code,
  fr.service_du                   service_du,
  fr.heures_solde                 heures_solde,
  fr.heures_compl_total           heures_compl
FROM
  formule_resultat                    fr
  JOIN intervenant                     i ON i.id    = fr.intervenant_id
  JOIN statut_intervenant             si ON si.id   = i.statut_id            
  JOIN type_intervenant               ti ON ti.id   = si.type_intervenant_id 
  LEFT JOIN service                    s ON s.intervenant_id = fr.intervenant_id AND s.annee_id = fr.annee_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
  LEFT JOIN element_pedagogique       ep ON ep.id   = s.element_pedagogique_id
  LEFT JOIN etape                    etp ON etp.id  = ep.etape_id
  LEFT JOIN v_etape_niveau_formation enf ON enf.etape_id = etp.id;
---------------------------
--Modifié VIEW
--V_PJ_HEURES
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_PJ_HEURES" 
 ( "NOM_USUEL", "PRENOM", "INTERVENANT_ID", "SOURCE_CODE", "ANNEE_ID", "CATEG", "TOTAL_HEURES"
  )  AS 
  SELECT i.NOM_USUEL, i.PRENOM, i.id intervenant_id, i.SOURCE_CODE, s.annee_id, 'service' categ, sum(vh.HEURES) as total_heures
  from INTERVENANT i 
  join SERVICE s on s.INTERVENANT_ID = i.id                                   and s.HISTO_DESTRUCTEUR_ID is null
  join VOLUME_HORAIRE vh on vh.SERVICE_ID = s.id                         and vh.HISTO_DESTRUCTEUR_ID is null
  join type_volume_horaire tvh ON TVH.ID = VH.TYPE_VOLUME_HORAIRE_ID AND (tvh.code = 'PREVU')
  join ELEMENT_PEDAGOGIQUE ep on s.ELEMENT_PEDAGOGIQUE_ID = ep.id        and ep.HISTO_DESTRUCTEUR_ID is null
  join ETAPE e on ep.ETAPE_ID = e.id and e.HISTO_DESTRUCTEUR_ID is null
  where i.HISTO_DESTRUCTEUR_ID is null
  group by i.NOM_USUEL, i.PRENOM, i.id, i.SOURCE_CODE, s.annee_id, 'service'
UNION
  select i.NOM_USUEL, i.PRENOM, i.id intervenant_id, i.SOURCE_CODE, s.annee_id, 'referentiel' categ, sum(s.HEURES) as total_heures
  from INTERVENANT i 
  join SERVICE_REFERENTIEL s on s.INTERVENANT_ID = i.id and s.HISTO_DESTRUCTEUR_ID is null
  where i.HISTO_DESTRUCTEUR_ID is null
  group by i.NOM_USUEL, i.PRENOM, i.id, i.SOURCE_CODE, s.annee_id, 'referentiel';
---------------------------
--Modifié VIEW
--V_SYMPA_LISTE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_SYMPA_LISTE" 
 ( "EMAIL"
  )  AS 
  select distinct p.email
  from role r
  inner join type_role tr on r.type_id = tr.id and 1 = ose_divers.comprise_entre( tr.histo_creation, tr.histo_destruction)
  inner join personnel p on r.personnel_id = p.id and 1 = ose_divers.comprise_entre( p.histo_creation, p.histo_destruction)
  where tr.code in (
     'gestionnaire-composante'
    ,'responsable-composante'
    ,'responsable-drh'
    ,'gestionnaire-drh'
    ,'administrateur'
  )
  and 1 = ose_divers.comprise_entre( r.histo_creation, r.histo_destruction)
  order by p.email;
---------------------------
--Nouveau VIEW
--V_TMP_WF
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_TMP_WF" 
 ( "ID", "SOURCE_CODE", "NB_COMP", "NB_AGREM"
  )  AS 
  WITH 
  composantes_enseign AS (
      -- composantes d'enseignement par intervenant
      SELECT DISTINCT i.ID, i.source_code, s.structure_ens_id
      FROM service s
      INNER JOIN intervenant i ON i.ID = s.intervenant_id AND (i.histo_destructeur_id IS NULL)
      INNER JOIN STRUCTURE comp ON comp.ID = s.structure_ens_id AND (comp.histo_destructeur_id IS NULL)
      WHERE s.histo_destructeur_id IS NULL
  ),
  agrements_oblig_exist AS (
      -- agréments obligatoires obtenus par intervenant et structure
      SELECT i.ID, i.source_code, A.type_agrement_id, A.ID agrement_id, A.structure_id
      FROM agrement A
      INNER JOIN type_agrement ta ON A.type_agrement_id = ta.ID AND (ta.histo_destructeur_id IS NULL)
      INNER JOIN intervenant i ON A.intervenant_id = i.ID AND (i.histo_destructeur_id IS NULL)
      INNER JOIN type_agrement_statut tas ON i.statut_id = tas.statut_intervenant_id AND ta.ID = tas.type_agrement_id 
          AND i.premier_recrutement = tas.premier_recrutement AND tas.obligatoire = 1 AND (tas.histo_destructeur_id IS NULL) 
      WHERE A.histo_destructeur_id IS NULL
      AND ta.code = 'CONSEIL_RESTREINT'
  ), 
  v_agrement AS (
    -- nombres de composantes d'enseignement et d'agrément obligatoires fournis par intervenant
    SELECT DISTINCT i.ID, i.source_code, 
      ( select count(*) from COMPOSANTES_ENSEIGN ce where ce.id = i.id ) nb_comp, 
      ( select count(*) from AGREMENTS_OBLIG_EXIST ao where ao.id = i.id ) nb_agrem
    FROM intervenant i 
    WHERE i.histo_destructeur_id IS NULL
  )
  SELECT "ID","SOURCE_CODE","NB_COMP","NB_AGREM"
  FROM v_agrement v
  WHERE v.nb_comp <= nb_agrem;
  --AND v.id = p_intervenant_id;
---------------------------
--Modifié VIEW
--V_TBL_SERVICE_EXPORT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_SERVICE_EXPORT" 
 ( "SERVICE_ID", "ANNEE_ID", "TYPE_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID", "TYPE_INTERVENANT_ID", "STRUCTURE_AFF_ID", "INTERVENANT_ID", "STRUCTURE_ENS_ID", "NIVEAU_FORMATION_ID", "ETAPE_ID", "ELEMENT_PEDAGOGIQUE_ID", "INTERVENANT_CODE", "INTERVENANT_NOM", "INTERVENANT_STATUT_LIBELLE", "INTERVENANT_TYPE_LIBELLE", "SERVICE_STRUCTURE_AFF_LIBELLE", "SERVICE_STRUCTURE_ENS_LIBELLE", "ETABLISSEMENT_LIBELLE", "ETAPE_CODE", "ETAPE_LIBELLE", "ELEMENT_CODE", "ELEMENT_LIBELLE", "COMMENTAIRES", "ELEMENT_PERIODE_LIBELLE", "ELEMENT_PONDERATION_COMPL", "ELEMENT_SOURCE_LIBELLE", "HEURES_SERVICE", "HEURES_REELLES", "HEURES_SERVICE_STATUTAIRE", "HEURES_SERVICE_DU_MODIFIE", "HEURES_ASSUREES", "HEURES_SOLDE", "HEURES_NON_PAYEES", "HEURES_REFERENTIEL"
  )  AS 
  SELECT
  s.id                            service_id,
  fr.annee_id                     annee_id,
  fr.type_volume_horaire_id       type_volume_horaire_id,
  fr.etat_volume_horaire_id       etat_volume_horaire_id,
  ti.id                           type_intervenant_id,
  CASE WHEN ti.code = 'E' THEN NULL ELSE saff.id END                         structure_aff_id,
  i.id                            intervenant_id,
  sens.id                         structure_ens_id,
  enf.niveau_formation_id         niveau_formation_id,
  etp.id                          etape_id,
  ep.id                           element_pedagogique_id,

  i.source_code                   intervenant_code,
  i.nom_usuel || ' ' || i.prenom  intervenant_nom,
  si.libelle                      intervenant_statut_libelle,
  ti.libelle                      intervenant_type_libelle,
  saff.libelle_court              service_structure_aff_libelle,

  sens.libelle_court              service_structure_ens_libelle,
  etab.libelle                    etablissement_libelle,
  etp.source_code                 etape_code,
  etp.libelle                     etape_libelle,
  ep.source_code                  element_code,
  ep.libelle                      element_libelle,
  null                            commentaires,
  p.libelle_court                 element_periode_libelle,
  CASE WHEN fs.ponderation_service_compl = 1 THEN NULL ELSE fs.ponderation_service_compl END element_ponderation_compl,
  src.libelle                     element_source_libelle,

  fr.service                      heures_service,
  fr.service + fr.referentiel     heures_reelles,
  si.service_statutaire           heures_service_statutaire,
  fsm.heures                      heures_service_du_modifie,
  fr.service_assure               heures_assurees,
  fr.heures_solde                 heures_solde,
  
  (SELECT
      COALESCE( SUM( vh.heures * CASE WHEN vh.motif_non_paiement_id IS NULL THEN 0 ELSE 1 END ), 0)
    FROM
      volume_horaire vh
      JOIN v_volume_horaire_etat vhe ON vhe.volume_horaire_id = vh.id
    WHERE
      ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction ) = 1
      AND vh.service_id = s.id
      AND NVL(vh.type_volume_horaire_id,1) = fr.type_volume_horaire_id 
      AND NVL(vhe.etat_volume_horaire_id,1) >= fr.etat_volume_horaire_id) heures_non_payees,
  0                               heures_referentiel  

FROM
  service s
  JOIN intervenant                     i ON i.id    = s.intervenant_id AND ose_divers.comprise_entre(  i.histo_creation,  i.histo_destruction ) = 1
  JOIN statut_intervenant             si ON si.id   = i.statut_id            
  JOIN type_intervenant               ti ON ti.id   = si.type_intervenant_id 
  JOIN structure                    sens ON sens.id = s.structure_ens_id
  JOIN etablissement                etab ON etab.id = s.etablissement_id
  JOIN formule_resultat               fr ON fr.intervenant_id = i.id
  LEFT JOIN structure               saff ON saff.id = s.structure_aff_id AND ti.code = 'P'
  LEFT JOIN element_pedagogique       ep ON ep.id   = s.element_pedagogique_id
  LEFT JOIN periode                    p ON p.id    = ep.periode_id
  LEFT JOIN source                   src ON src.id  = ep.source_id
  LEFT JOIN etape                    etp ON etp.id  = ep.etape_id
  LEFT JOIN v_etape_niveau_formation enf ON enf.etape_id = etp.id
  LEFT JOIN v_formule_service_modifie fsm ON fsm.intervenant_id = i.id AND fsm.annee_id = fr.annee_id
  LEFT JOIN v_formule_service         fs ON fs.id = s.id
WHERE
  ose_divers.comprise_entre( s.histo_creation, s.histo_destruction ) = 1

UNION

SELECT
  -1                              service_id,
  sr.annee_id                     annee_id,
  fr.type_volume_horaire_id       type_volume_horaire_id,
  fr.etat_volume_horaire_id       etat_volume_horaire_id,
  ti.id                           type_intervenant_id,
  CASE WHEN ti.code = 'E' THEN NULL ELSE saff.id END structure_aff_id,
  i.id                            intervenant_id,
  sens.id                         structure_ens_id,
  -1                              niveau_formation_id,
  -1                              etape_id,
  -1                              element_pedagogique_id,


  i.source_code                   intervenant_code,
  i.nom_usuel || ' ' || i.prenom  intervenant_nom,
  si.libelle                      intervenant_statut_libelle,
  ti.libelle                      intervenant_type_libelle,
  saff.libelle_court              service_structure_aff_libelle,

  sens.libelle_court              service_structure_ens_libelle,
  null                            etablissement_libelle,
  null                            etape_code,
  null                            etape_libelle,
  fonc.code                       element_code,
  fonc.libelle_court              element_libelle,
  sr.commentaires                 commentaires,
  null                            element_periode_libelle,
  null                            element_ponderation_compl,
  src.libelle                     element_source_libelle,

  fr.service                      heures_service,
  fr.service + fr.referentiel     heures_reelles,
  si.service_statutaire           heures_service_statutaire,
  fsm.heures                      heures_service_du_modifie,
  fr.service_assure               heures_assurees,
  fr.heures_solde                 heures_solde,
  0                               heures_non_payees,
  sr.heures                       heures_referentiel  

FROM
  service_referentiel                 sr
  JOIN fonction_referentiel         fonc ON fonc.id = sr.fonction_id
  JOIN intervenant                     i ON i.id    = sr.intervenant_id AND ose_divers.comprise_entre(  i.histo_creation,  i.histo_destruction ) = 1
  JOIN statut_intervenant             si ON si.id   = i.statut_id            
  JOIN type_intervenant               ti ON ti.id   = si.type_intervenant_id 
  JOIN structure                    sens ON sens.id = sr.structure_id
  JOIN formule_resultat               fr ON fr.intervenant_id = i.id
  JOIN source                        src ON src.code = 'OSE'
  LEFT JOIN structure               saff ON saff.id = i.structure_id AND ti.code = 'P'
  LEFT JOIN v_formule_service_modifie fsm ON fsm.intervenant_id = i.id AND fsm.annee_id = fr.annee_id
WHERE
  ose_divers.comprise_entre( sr.histo_creation, sr.histo_destruction ) = 1;
---------------------------
--Modifié VIEW
--V_ELEMENT_TYPE_INTERVENTION
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_ELEMENT_TYPE_INTERVENTION" 
 ( "ELEMENT_PEDAGOGIQUE_ID", "TYPE_INTERVENTION_ID"
  )  AS 
  SELECT
  ep.id element_pedagogique_id,
  ti.id type_intervention_id
FROM
  element_pedagogique ep
  JOIN structure s_ep ON s_ep.id = ep.structure_id
  JOIN type_intervention ti ON 1=1
  LEFT JOIN TYPE_INTERVENTION_EP ti_ep ON TI_EP.ELEMENT_PEDAGOGIQUE_ID = EP.ID AND TI_EP.TYPE_INTERVENTION_ID = TI.ID AND 1 = ose_divers.comprise_entre( TI_EP.HISTO_CREATION, TI_EP.HISTO_DESTRUCTION )
  LEFT JOIN TYPE_INTERVENTION_STRUCTURE ti_s ON S_EP.STRUCTURE_NIV2_ID = TI_S.STRUCTURE_ID AND TI_S.TYPE_INTERVENTION_ID = TI.ID AND 1 = ose_divers.comprise_entre( TI_S.HISTO_CREATION, TI_S.HISTO_DESTRUCTION )
WHERE
  CASE
    WHEN TI_EP.VISIBLE IS NULL THEN
      CASE
        WHEN TI_S.VISIBLE IS NULL THEN TI.VISIBLE
        ELSE TI_S.VISIBLE
      END
    ELSE TI_EP.VISIBLE
  END = 1
ORDER BY
  TI.ORDRE;
---------------------------
--Nouveau VIEW
--V_FORMULE_REFERENTIEL
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_REFERENTIEL" 
 ( "ID", "INTERVENANT_ID", "ANNEE_ID", "FONCTION_ID", "STRUCTURE_ID", "HEURES"
  )  AS 
  SELECT
  sr.id             id,
  sr.intervenant_id intervenant_id,
  sr.annee_id       annee_id,
  sr.fonction_id    fonction_id,
  sr.structure_id   structure_id,
  sr.heures         heures
FROM
  service_referentiel sr
  JOIN intervenant i ON i.id = sr.intervenant_id
WHERE
  1 = ose_divers.comprise_entre( sr.histo_creation, sr.histo_destruction, ose_formule.get_date_obs )
  AND heures > 0;
---------------------------
--Nouveau VIEW
--V_FORMULE_INTERVENANT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_INTERVENANT" 
 ( "ID", "STRUCTURE_ID", "HEURES_SERVICE_STATUTAIRE"
  )  AS 
  SELECT
  i.id,
  i.structure_id,
  si.service_statutaire heures_service_statutaire
FROM
  intervenant i
  JOIN statut_intervenant si ON si.id = i.statut_id
WHERE
  1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction, ose_formule.get_date_obs );
---------------------------
--Modifié VIEW
--V_WF_INTERVENANT_ETAPE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_WF_INTERVENANT_ETAPE" 
 ( "ID", "NOM_USUEL", "PRENOM", "SOURCE_CODE", "TYPE", "ORDRE", "LIBELLE", "STRUCTURE_ID", "LIBELLE_COURT", "ATTEIGNABLE", "FRANCHIE", "COURANTE", "DATE_CREATION"
  )  AS 
  select i.id, i.nom_usuel, i.prenom, i.source_code, TI.code type, ie.ordre, e.libelle, s.id structure_id, s.libelle_court, ie.atteignable, ie.franchie, ie.courante, IE.DATE_CREATION
  from wf_intervenant_etape ie 
  inner join intervenant i on i.id = ie.intervenant_id
  inner join wf_etape e on e.id = ie.etape_id
  inner join STATUT_INTERVENANT si on si.id = I.STATUT_ID
  inner join TYPE_INTERVENANT ti on ti.id = SI.TYPE_INTERVENANT_ID
  left join structure s on s.id = IE.STRUCTURE_ID
  order by i.nom_usuel, i.id, ie.ordre asc;
---------------------------
--Modifié VIEW
--V_TBL_SERVICE_RESUME_REF
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_SERVICE_RESUME_REF" 
 ( "ANNEE_ID", "TYPE_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID", "SERVICE_ID", "INTERVENANT_ID", "TYPE_INTERVENANT_ID", "STRUCTURE_AFF_ID", "STRUCTURE_ENS_ID", "NIVEAU_FORMATION_ID", "ETAPE_ID", "ELEMENT_PEDAGOGIQUE_ID", "HEURES_REFERENTIEL"
  )  AS 
  SELECT
  fr.annee_id                     annee_id,
  fr.type_volume_horaire_id       type_volume_horaire_id,
  fr.etat_volume_horaire_id       etat_volume_horaire_id,
  -1                              service_id,
  i.id                            intervenant_id,
  ti.id                           type_intervenant_id,
  i.structure_id                  structure_aff_id,
  
  sr.structure_id                 structure_ens_id,
  -1                              niveau_formation_id,
  -1                              etape_id,
  -1                              element_pedagogique_id,

  sr.heures                       heures_referentiel
FROM
  formule_resultat                    fr
  JOIN intervenant                     i ON i.id    = fr.intervenant_id
  JOIN statut_intervenant             si ON si.id   = i.statut_id            
  JOIN type_intervenant               ti ON ti.id   = si.type_intervenant_id 
  JOIN service_referentiel            sr ON sr.INTERVENANT_ID = FR.INTERVENANT_ID AND sr.ANNEE_ID = FR.ANNEE_ID AND 1 = ose_divers.comprise_entre(sr.histo_creation, sr.histo_destruction);


---------------------------
--Nouveau INDEX
--MV_ELEMENT_TAUX_REGIMES_PK
---------------------------
  CREATE UNIQUE INDEX "OSE"."MV_ELEMENT_TAUX_REGIMES_PK" ON "OSE"."MV_ELEMENT_TAUX_REGIMES" ("SOURCE_CODE");
---------------------------
--Nouveau INDEX
--MV_EFFECTIFS_PK
---------------------------
  CREATE UNIQUE INDEX "OSE"."MV_EFFECTIFS_PK" ON "OSE"."MV_EFFECTIFS" ("SOURCE_CODE");

---------------------------
--Nouveau TRIGGER
--WF_TRG_VOLUME_HORAIRE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_VOLUME_HORAIRE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE"
  BEGIN
  ose_workflow.update_intervenants_etapes();
END;
/
---------------------------
--Nouveau TRIGGER
--WF_TRG_VOLUME_HORAIRE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_VOLUME_HORAIRE"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE"
  REFERENCING FOR EACH ROW
  DECLARE
  intervenant_id NUMERIC;
  service_id NUMERIC;
BEGIN
  service_id := CASE WHEN deleting THEN :OLD.service_id ELSE :NEW.service_id END;
  SELECT s.intervenant_id into intervenant_id from service s where id = service_id;
  ose_workflow.add_intervenant_to_update (intervenant_id); 
END;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_VH_VALIDATION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_VH_VALIDATION"
  AFTER INSERT OR DELETE ON "OSE"."VALIDATION_VOL_HORAIRE"
  REFERENCING FOR EACH ROW
  DECLARE
  intervenant_id NUMERIC;
  validation_id NUMERIC;
BEGIN
  validation_id := CASE WHEN deleting THEN :OLD.validation_id ELSE :NEW.validation_id END;
  SELECT V.INTERVENANT_ID INTO intervenant_id FROM validation v WHERE id = validation_id;
  ose_workflow.add_intervenant_to_update (intervenant_id); 
END;
/
---------------------------
--Nouveau TRIGGER
--WF_TRG_SERVICE_VALIDATION_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_SERVICE_VALIDATION_S"
  AFTER INSERT OR DELETE OR UPDATE OF HISTO_DESTRUCTION ON "OSE"."VALIDATION"
  BEGIN
  ose_workflow.update_intervenants_etapes();
END;
/
  ALTER TRIGGER "OSE"."WF_TRG_SERVICE_VALIDATION_S" DISABLE;
/
---------------------------
--Nouveau TRIGGER
--WF_TRG_SERVICE_VALIDATION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_SERVICE_VALIDATION"
  AFTER INSERT OR DELETE OR UPDATE OF HISTO_DESTRUCTION ON "OSE"."VALIDATION"
  REFERENCING FOR EACH ROW
  DECLARE
  type_validation_id NUMERIC;
  code VARCHAR2(128);
  intervenant_id NUMERIC;
BEGIN
  type_validation_id := CASE WHEN deleting THEN :OLD.type_validation_id ELSE :NEW.type_validation_id END;
  SELECT code INTO code FROM type_validation WHERE id = type_validation_id;
  IF code = 'SERVICES_PAR_COMP' THEN
    intervenant_id := CASE WHEN deleting THEN :OLD.intervenant_id ELSE :NEW.intervenant_id END;
    ose_workflow.add_intervenant_to_update (intervenant_id); 
  END IF;
END;
/
  ALTER TRIGGER "OSE"."WF_TRG_SERVICE_VALIDATION" DISABLE;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_SERVICE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_SERVICE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE"
  BEGIN
  ose_workflow.update_intervenants_etapes();
END;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_SERVICE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_SERVICE"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE"
  REFERENCING FOR EACH ROW
  BEGIN
  ose_workflow.add_intervenant_to_update (CASE WHEN deleting THEN :OLD.intervenant_id ELSE :NEW.intervenant_id END); 
END;
/

---------------------------
--Nouveau TRIGGER
--PROV_SERVICE_REFERENTIEL
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."PROV_SERVICE_REFERENTIEL"
  BEFORE INSERT OR UPDATE ON "OSE"."SERVICE_REFERENTIEL"
  REFERENCING FOR EACH ROW
  BEGIN
  :NEW.type_volume_horaire_id := 1;
END;
/


---------------------------
--Modifié TRIGGER
--ELEMENT_PEDAGOGIQUE_CK
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."ELEMENT_PEDAGOGIQUE_CK"
  BEFORE INSERT OR UPDATE ON "OSE"."ELEMENT_PEDAGOGIQUE"
  REFERENCING FOR EACH ROW
  BEGIN IF :NEW.source_id <> OSE_IMPORT.GET_SOURCE_ID('OSE') THEN RETURN;
END IF; -- impossible de checker car l'UPD par import se fait champ par champ...
IF :NEW.fi = 0 AND :NEW.fc = 0 AND :NEW.fa = 0 THEN
  raise_application_error(-20101, 'Un enseignement doit obligatoirement être au moins en FI, FC ou FA');
END IF;
IF 1 <> ROUND(:NEW.taux_fi + :NEW.taux_fc + :NEW.taux_fa, 2) THEN
  raise_application_error( -20101, 'Le total des taux FI, FC et FA n''est pas égal à 1');
END IF;
END;
/
---------------------------
--Modifié PACKAGE
--OSE_WORKFLOW
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_WORKFLOW" AS 

  TYPE T_LIST_STRUCTURE_ID IS TABLE OF NUMBER INDEX BY PLS_INTEGER;

  PROCEDURE add_intervenant_to_update (p_intervenant_id NUMERIC);
  PROCEDURE update_all_intervenants_etapes;
  PROCEDURE update_intervenants_etapes;
  PROCEDURE update_intervenant_etapes (p_intervenant_id NUMERIC);
  
  PROCEDURE fetch_structures_ens_ids (p_intervenant_id NUMERIC, structures_ids IN OUT T_LIST_STRUCTURE_ID);
  
  FUNCTION peut_saisir_dossier (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION peut_saisir_service (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_services (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION peut_saisir_referentiel (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION peut_saisir_piece_jointe (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION necessite_agrement_cr (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION necessite_agrement_ca (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION necessite_contrat (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;

  FUNCTION possede_dossier (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION dossier_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION service_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION referentiel_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION pieces_jointes_fournies (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION agrement_cr_fourni (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION agrement_ca_fourni (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_contrat (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;

END OSE_WORKFLOW;
/
---------------------------
--Modifié PACKAGE
--OSE_IMPORT
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_IMPORT" IS

  v_current_user INTEGER := 1;
  v_date_obs Date := SYSDATE;
 
  FUNCTION get_date_obs RETURN Date;
 
  PROCEDURE set_current_user(p_current_user IN INTEGER);
  FUNCTION get_current_user return INTEGER;

  FUNCTION get_type_intervenant_id( src_code varchar2 ) RETURN Numeric;
  FUNCTION get_civilite_id( src_libelle_court varchar2 ) RETURN Numeric;
  FUNCTION get_source_id( src_code Varchar2 ) return Numeric;

  PROCEDURE SYNC_LOG( message CLOB );
  PROCEDURE SYNC_MVS;
  PROCEDURE SYNC_VOLUME_HORAIRE_ENS;
  PROCEDURE SYNC_TYPE_FORMATION;
  PROCEDURE SYNC_STRUCTURE;
  PROCEDURE SYNC_ROLE;
  PROCEDURE SYNC_PERSONNEL;
  PROCEDURE SYNC_INTERVENANT_PERMANENT;
  PROCEDURE SYNC_INTERVENANT_EXTERIEUR;
  PROCEDURE SYNC_INTERVENANT;
  PROCEDURE SYNC_GROUPE_TYPE_FORMATION;
  PROCEDURE SYNC_ETAPE;
  PROCEDURE SYNC_ETABLISSEMENT;
  PROCEDURE SYNC_ELEMENT_PEDAGOGIQUE;
  PROCEDURE SYNC_EFFECTIFS;
  PROCEDURE SYNC_ELEMENT_TAUX_REGIMES;
  PROCEDURE SYNC_ELEMENT_DISCIPLINE;
  PROCEDURE SYNC_DISCIPLINE;
  PROCEDURE SYNC_CORPS;
  PROCEDURE SYNC_CHEMIN_PEDAGOGIQUE;
  PROCEDURE SYNC_AFFECTATION_RECHERCHE;
  PROCEDURE SYNC_ADRESSE_STRUCTURE;
  PROCEDURE SYNC_ADRESSE_INTERVENANT;
  PROCEDURE SYNC_TYPE_INTERVENTION_EP;
  PROCEDURE SYNC_TYPE_MODULATEUR_EP;
  PROCEDURE SYNC_TABLES;
  PROCEDURE SYNCHRONISATION;
  
  FUNCTION IN_COLUMN_LIST( VALEUR VARCHAR2, CHAMPS CLOB ) RETURN NUMERIC;

  -- AUTOMATIC GENERATION --

  PROCEDURE MAJ_VOLUME_HORAIRE_ENS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_TYPE_MODULATEUR_EP(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_TYPE_INTERVENTION_EP(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_TYPE_FORMATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ROLE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_PERSONNEL(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_INTERVENANT_PERMANENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_INTERVENANT_EXTERIEUR(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_GROUPE_TYPE_FORMATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ETAPE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ETABLISSEMENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ELEMENT_TAUX_REGIMES(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ELEMENT_PEDAGOGIQUE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ELEMENT_DISCIPLINE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_EFFECTIFS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_DISCIPLINE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_CORPS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_CHEMIN_PEDAGOGIQUE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_AFFECTATION_RECHERCHE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ADRESSE_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ADRESSE_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');

  -- END OF AUTOMATIC GENERATION --
END ose_import;
/
---------------------------
--Modifié PACKAGE
--OSE_FORMULE
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_FORMULE" AS 

  TYPE t_intervenant IS RECORD (
    structure_id              NUMERIC,
    heures_service_statutaire FLOAT   DEFAULT 0,
    heures_service_modifie    FLOAT   DEFAULT 0
  );
  
  TYPE t_type_etat_vh IS RECORD (
    type_volume_horaire_id    NUMERIC,
    etat_volume_horaire_id    NUMERIC
  );
  TYPE t_lst_type_etat_vh   IS TABLE OF t_type_etat_vh INDEX BY PLS_INTEGER;
  
  TYPE t_referentiel IS RECORD (
    id                        NUMERIC,
    structure_id              NUMERIC,
    heures                    FLOAT   DEFAULT 0
  );
  TYPE t_lst_referentiel      IS TABLE OF t_referentiel INDEX BY PLS_INTEGER;
  
  TYPE t_service IS RECORD (
    id                        NUMERIC,
    taux_fi                   FLOAT   DEFAULT 1,
    taux_fa                   FLOAT   DEFAULT 0,
    taux_fc                   FLOAT   DEFAULT 0,
    ponderation_service_du    FLOAT   DEFAULT 1,
    ponderation_service_compl FLOAT   DEFAULT 1,
    structure_aff_id          NUMERIC,
    structure_ens_id          NUMERIC
  );
  TYPE t_lst_service          IS TABLE OF t_service INDEX BY PLS_INTEGER;
  
  TYPE t_volume_horaire IS RECORD (
    id                        NUMERIC,
    service_id                NUMERIC,
    type_volume_horaire_id    NUMERIC,
    etat_volume_horaire_id    NUMERIC,
    etat_volume_horaire_ordre NUMERIC,
    heures                    FLOAT   DEFAULT 0,
    taux_service_du           FLOAT   DEFAULT 1,
    taux_service_compl        FLOAT   DEFAULT 1
  );
  TYPE t_lst_volume_horaire   IS TABLE OF t_volume_horaire INDEX BY PLS_INTEGER;

  d_intervenant         t_intervenant;
  d_type_etat_vh        t_lst_type_etat_vh;
  d_referentiel         t_lst_referentiel;
  d_service             t_lst_service;
  d_volume_horaire      t_lst_volume_horaire;

  FUNCTION  GET_DATE_OBS RETURN DATE;
  FUNCTION  SET_DATE_OBS( DATE_OBS DATE DEFAULT NULL ) RETURN DATE;

  FUNCTION NOUVEAU_RESULTAT RETURN formule_resultat%rowtype;
  FUNCTION ENREGISTRER_RESULTAT( fr formule_resultat%rowtype ) RETURN NUMERIC;
  
  FUNCTION NOUVEAU_RESULTAT_SERVICE RETURN formule_resultat_service%rowtype;
  FUNCTION ENREGISTRER_RESULTAT_SERVICE( fs formule_resultat_service%rowtype ) RETURN NUMERIC;
  
  FUNCTION NOUVEAU_RESULTAT_REF RETURN formule_resultat_referentiel%rowtype;
  FUNCTION ENREGISTRER_RESULTAT_REF( fr formule_resultat_referentiel%rowtype ) RETURN NUMERIC;



  PROCEDURE DEMANDE_CALCUL( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC );
  PROCEDURE CALCULER( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC );
  PROCEDURE CALCULER_SUR_DEMANDE; -- mise à jour de tous les items identifiés
  PROCEDURE CALCULER_TOUT;        -- mise à jour de TOUTES les données ! ! ! !

END OSE_FORMULE;
/
---------------------------
--Modifié PACKAGE
--OSE_DIVERS
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_DIVERS" AS 

  FUNCTION INTERVENANT_HAS_PRIVILEGE( intervenant_id NUMERIC, privilege_name VARCHAR2 ) RETURN NUMERIC;

  FUNCTION implode(i_query VARCHAR2, i_seperator VARCHAR2 DEFAULT ',') RETURN VARCHAR2;

  FUNCTION intervenant_est_permanent( INTERVENANT_ID NUMERIC ) RETURN NUMERIC;

  FUNCTION intervenant_est_non_autorise( INTERVENANT_ID NUMERIC ) RETURN NUMERIC;

  FUNCTION intervenant_peut_saisir_serv( INTERVENANT_ID NUMERIC ) RETURN NUMERIC;

  FUNCTION STRUCTURE_DANS_STRUCTURE( structure_testee NUMERIC, structure_cible NUMERIC ) RETURN NUMERIC;

  FUNCTION STR_REDUCE( str CLOB ) RETURN CLOB;
  
  FUNCTION LIKED( haystack CLOB, needle CLOB ) RETURN NUMERIC;

  FUNCTION COMPRISE_ENTRE( date_debut DATE, date_fin DATE DEFAULT NULL, date_obs DATE DEFAULT SYSDATE ) RETURN NUMERIC;

  PROCEDURE DO_NOTHING;

  FUNCTION VOLUME_HORAIRE_VALIDE( volume_horaire_id NUMERIC ) RETURN NUMERIC;

  FUNCTION CALCUL_TAUX_FI( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;
  
  FUNCTION CALCUL_TAUX_FC( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;
  
  FUNCTION CALCUL_TAUX_FA( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;

  FUNCTION STRUCTURE_UNIV_GET_ID RETURN NUMERIC;

  PROCEDURE SYNC_LOG( msg CLOB );

END OSE_DIVERS;
/
---------------------------
--Modifié PACKAGE BODY
--UNICAEN_OSE_FORMULE
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."UNICAEN_OSE_FORMULE" AS

  TYPE t_valeurs IS TABLE OF FLOAT INDEX BY PLS_INTEGER;
  TYPE t_tableau IS RECORD (
    valeurs t_valeurs,
    total_service t_valeurs,
    total   FLOAT
  );  
  TYPE t_tableaux         IS TABLE OF t_tableau                       INDEX BY PLS_INTEGER;

  t                     t_tableaux;
  service_restant_du    t_valeurs;
  resultat              formule_resultat%rowtype;


  PROCEDURE DEBUG_TAB( tab_index PLS_INTEGER ) IS
    id PLS_INTEGER;
    id2 PLS_INTEGER;
    tab t_tableau;
  BEGIN
    tab := t(tab_index);
  
    ose_test.echo( 'Intervenant id = ' || resultat.intervenant_id );
    ose_test.echo( 'Tableau numéro ' || tab_index );
    
    id := ose_formule.d_service.FIRST;
    LOOP EXIT WHEN id IS NULL;
      dbms_output.put( 'Service id=' || lpad(id,6,' ') || ', data = ');
      
      id2 := ose_formule.d_volume_horaire.FIRST;
      LOOP EXIT WHEN id2 IS NULL;
        IF ose_formule.d_volume_horaire(id2).type_volume_horaire_id = resultat.type_volume_horaire_id
        AND ose_formule.d_volume_horaire(id2).etat_volume_horaire_ordre >= resultat.etat_volume_horaire_id AND ose_formule.d_volume_horaire(id2).service_id = id THEN
          
          dbms_output.put( lpad(tab.valeurs(id2),4,' ') || ', ' );
          
        END IF;
      id2 := ose_formule.d_volume_horaire.NEXT(id2);
      END LOOP;
      
      dbms_output.new_line;
      id := ose_formule.d_service.NEXT(id);
    END LOOP;
    
    
    ose_test.echo( 'TOTAL = ' || LPAD(tab.total, 4, ' ') );
  END;

  FUNCTION C_11( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );

    IF NVL(s.structure_ens_id,0) = NVL(s.structure_aff_id,0) AND s.taux_fc < 1 THEN
      RETURN vh.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_12( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
  
    IF NVL(s.structure_ens_id,0) <> NVL(s.structure_aff_id,0) AND s.taux_fc < 1 THEN
      RETURN vh.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_13( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
  
    IF NVL(s.structure_ens_id,0) = NVL(s.structure_aff_id,0) AND s.taux_fc = 1 THEN
      RETURN vh.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_14( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
  
    IF NVL(s.structure_ens_id,0) <> NVL(s.structure_aff_id,0) AND s.taux_fc = 1 THEN
      RETURN vh.heures;
    ELSE
      RETURN 0;
    END IF;
  END;  

  FUNCTION C_15( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF NVL(ose_formule.d_intervenant.structure_id,0) = NVL(fr.structure_id,0) THEN
      RETURN fr.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_16( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF NVL(ose_formule.d_intervenant.structure_id,0) <> NVL(fr.structure_id,0) AND NVL(fr.structure_id,0) <> ose_divers.STRUCTURE_UNIV_GET_ID THEN
      RETURN fr.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_17( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF NVL(fr.structure_id,0) = ose_divers.STRUCTURE_UNIV_GET_ID THEN
      RETURN fr.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_21( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(11).valeurs(vh.id) * vh.taux_service_du;
  END;

  FUNCTION C_22( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(12).valeurs(vh.id) * vh.taux_service_du;
  END;
  
  FUNCTION C_23( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(13).valeurs(vh.id) * vh.taux_service_du;
  END;
  
  FUNCTION C_24( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(14).valeurs(vh.id) * vh.taux_service_du;
  END;

  FUNCTION C_25( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    RETURN t(15).valeurs( fr.id );
  END;
  
  FUNCTION C_26( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    RETURN t(16).valeurs( fr.id );
  END;
  
  FUNCTION C_27( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    RETURN t(17).valeurs( fr.id );
  END;

  FUNCTION C_31 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( resultat.service_du - t(21).total, 0 );
  END;

  FUNCTION C_32 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( service_restant_du(31) - t(22).total, 0 );
  END;

  FUNCTION C_33 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( service_restant_du(32) - t(23).total, 0 );
  END;

  FUNCTION C_34 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( service_restant_du(33) - t(24).total, 0 );
  END;
  
  FUNCTION C_35 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( service_restant_du(34) - t(25).total, 0 );
  END;

  FUNCTION C_36 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( service_restant_du(35) - t(26).total, 0 );
  END;

  FUNCTION C_37 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( service_restant_du(36) - t(27).total, 0 );
  END;

  FUNCTION C_41( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(21).total > 0 THEN
      RETURN t(21).valeurs(vh.id) / t(21).total;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_42( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(22).total > 0 THEN
      RETURN t(22).valeurs(vh.id) / t(22).total;
    ELSE
      RETURN 0;
    END IF;
  END;
  
  FUNCTION C_43( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(23).total > 0 THEN
      RETURN t(23).valeurs(vh.id) / t(23).total;
    ELSE
      RETURN 0;
    END IF;
  END;
  
  FUNCTION C_44( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(24).total > 0 THEN
      RETURN t(24).valeurs(vh.id) / t(24).total;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_45( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF t(25).total > 0 THEN
      RETURN t(25).valeurs(fr.id) / t(25).total;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_46( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF t(26).total > 0 THEN
      RETURN t(26).valeurs(fr.id) / t(26).total;
    ELSE
      RETURN 0;
    END IF;
  END;
  
  FUNCTION C_47( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF t(27).total > 0 THEN
      RETURN t(27).valeurs(fr.id) / t(27).total;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_51( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( resultat.service_du, t(21).total ) * t(41).valeurs(vh.id);
  END;

  FUNCTION C_52( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( service_restant_du(31), t(22).total ) * t(42).valeurs(vh.id);
  END;

  FUNCTION C_53( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( service_restant_du(32), t(23).total ) * t(43).valeurs(vh.id);
  END;

  FUNCTION C_54( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( service_restant_du(33), t(24).total ) * t(44).valeurs(vh.id);
  END;

  FUNCTION C_55( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( service_restant_du(34), t(25).total ) * t(45).valeurs(fr.id);
  END;

  FUNCTION C_56( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( service_restant_du(35), t(26).total ) * t(46).valeurs(fr.id);
  END;
  
  FUNCTION C_57( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( service_restant_du(36), t(27).total ) * t(47).valeurs(fr.id);
  END;  

  FUNCTION C_61( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(21).valeurs(vh.id) > 0 THEN
      RETURN t(51).valeurs(vh.id) / t(21).valeurs(vh.id);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_62( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(22).valeurs(vh.id) > 0 THEN
      RETURN t(52).valeurs(vh.id) / t(22).valeurs(vh.id);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_63( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(23).valeurs(vh.id) > 0 THEN
      RETURN t(53).valeurs(vh.id) / t(23).valeurs(vh.id);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_64( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF t(24).valeurs(vh.id) > 0 THEN
      RETURN t(54).valeurs(vh.id) / t(24).valeurs(vh.id);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_65( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF t(25).valeurs(fr.id) > 0 THEN
      RETURN t(55).valeurs(fr.id) / t(25).valeurs(fr.id);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_66( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF t(26).valeurs(fr.id) > 0 THEN
      RETURN t(56).valeurs(fr.id) / t(26).valeurs(fr.id);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_67( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF t(27).valeurs(fr.id) > 0 THEN
      RETURN t(57).valeurs(fr.id) / t(27).valeurs(fr.id);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_71( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF service_restant_du(37) > 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - t(61).valeurs(vh.id);
    END IF;
  END;

  FUNCTION C_72( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF service_restant_du(37) > 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - t(62).valeurs(vh.id);
    END IF;
  END;

  FUNCTION C_73( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF service_restant_du(37) > 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - t(63).valeurs(vh.id);
    END IF;
  END;

  FUNCTION C_74( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF service_restant_du(37) > 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - t(64).valeurs(vh.id);
    END IF;
  END;

  FUNCTION C_75( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF service_restant_du(37) > 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - t(65).valeurs(fr.id);
    END IF;
  END;

  FUNCTION C_76( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF service_restant_du(37) > 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - t(66).valeurs(fr.id);
    END IF;
  END;
  
  FUNCTION C_77( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    IF service_restant_du(37) > 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - t(67).valeurs(fr.id);
    END IF;
  END;
  
  FUNCTION C_81( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(11).valeurs(vh.id) * vh.taux_service_compl * t(71).valeurs(vh.id);
  END;

  FUNCTION C_82( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(12).valeurs(vh.id) * vh.taux_service_compl * t(72).valeurs(vh.id);
  END;

  FUNCTION C_83( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(13).valeurs(vh.id) * vh.taux_service_compl * t(73).valeurs(vh.id);
  END;
  
  FUNCTION C_84( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN t(14).valeurs(vh.id) * vh.taux_service_compl * t(74).valeurs(vh.id);
  END;

  FUNCTION C_85( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    RETURN t(15).valeurs(fr.id) * t(75).valeurs(fr.id);
  END;

  FUNCTION C_86( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    RETURN t(16).valeurs(fr.id) * t(76).valeurs(fr.id);
  END;

  FUNCTION C_87( fr ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    RETURN t(17).valeurs(fr.id) * t(77).valeurs(fr.id);
  END;

  FUNCTION C_93( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );

    IF s.taux_fc = 1 THEN
      RETURN t(83).valeurs(vh.id) * s.ponderation_service_compl;
    ELSE
      RETURN t(83).valeurs(vh.id);
    END IF;
  END;
  
  FUNCTION C_94( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );

    IF s.taux_fc = 1 THEN
      RETURN t(84).valeurs(vh.id) * s.ponderation_service_compl;
    ELSE
      RETURN t(84).valeurs(vh.id);
    END IF;    
  END;

  FUNCTION C_101( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    RETURN t(81).valeurs(vh.id) * ( s.taux_fi + s.taux_fa );
  END;

  FUNCTION C_102( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    RETURN t(82).valeurs(vh.id) * ( s.taux_fi + s.taux_fa );
  END;
  
  FUNCTION C_103( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    RETURN t(83).valeurs(vh.id) * s.taux_fc;
  END;
  
  FUNCTION C_104( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    RETURN t(84).valeurs(vh.id) * s.taux_fc;
  END;

  FUNCTION RS_1( s ose_formule.t_service ) RETURN FLOAT IS
  BEGIN
    RETURN t(51).total_service( s.id ) + t(52).total_service( s.id ) + t(53).total_service( s.id ) + t(54).total_service( s.id );
  END;

  FUNCTION RS_2( s ose_formule.t_service ) RETURN FLOAT IS
  BEGIN
    RETURN t(101).total_service( s.id ) + t(102).total_service( s.id );
  END;

  FUNCTION RS_3( s ose_formule.t_service ) RETURN FLOAT IS
  BEGIN
    RETURN t(103).total_service( s.id ) + t(104).total_service( s.id );
  END;

  FUNCTION RS_4( r ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    RETURN t(55).total_service( r.id ) + t(56).total_service( r.id ) + t(57).total_service( r.id );
  END;

  FUNCTION RS_5( r ose_formule.t_referentiel ) RETURN FLOAT IS
  BEGIN
    RETURN t(85).total_service( r.id ) + t(86).total_service( r.id ) + t(87).total_service( r.id );
  END;

  PROCEDURE SET_T_VALUE( tab_index PLS_INTEGER, service_id PLS_INTEGER, id PLS_INTEGER, val FLOAT ) IS
  BEGIN
    t(tab_index).valeurs(id) := val;
    t(tab_index).total_service( service_id )   := t(tab_index).total_service( service_id ) + val;
    t(tab_index).total                         := t(tab_index).total + val;
  END;

  PROCEDURE CALCUL_VOLUME_HORAIRE( tab_index PLS_INTEGER, id NUMERIC ) IS
    res FLOAT;
    param ose_formule.t_volume_horaire;
  BEGIN
    param := ose_formule.d_volume_horaire(id);
    res := CASE tab_index
       WHEN  11 THEN  C_11( param ) WHEN  12 THEN  C_12( param ) WHEN  13 THEN  C_13( param ) WHEN  14 THEN  C_14( param )
       WHEN  21 THEN  C_21( param ) WHEN  22 THEN  C_22( param ) WHEN  23 THEN  C_23( param ) WHEN  24 THEN  C_24( param )
       WHEN  41 THEN  C_41( param ) WHEN  42 THEN  C_42( param ) WHEN  43 THEN  C_43( param ) WHEN  44 THEN  C_44( param )
       WHEN  51 THEN  C_51( param ) WHEN  52 THEN  C_52( param ) WHEN  53 THEN  C_53( param ) WHEN  54 THEN  C_54( param )
       WHEN  61 THEN  C_61( param ) WHEN  62 THEN  C_62( param ) WHEN  63 THEN  C_63( param ) WHEN  64 THEN  C_64( param )
       WHEN  71 THEN  C_71( param ) WHEN  72 THEN  C_72( param ) WHEN  73 THEN  C_73( param ) WHEN  74 THEN  C_74( param )
       WHEN  81 THEN  C_81( param ) WHEN  82 THEN  C_82( param ) WHEN  83 THEN  C_83( param ) WHEN  84 THEN  C_84( param )
                                                                 WHEN  93 THEN  C_93( param ) WHEN  94 THEN  C_94( param )
       WHEN 101 THEN C_101( param ) WHEN 102 THEN C_102( param ) WHEN 103 THEN C_103( param ) WHEN 104 THEN C_104( param )
    END;
    SET_T_VALUE( tab_index, param.service_id, id, res );
  END;

  PROCEDURE CALCUL_SERVICE_RESTANT_DU( tab_index PLS_INTEGER ) IS
    res FLOAT;
  BEGIN
    res := CASE tab_index
      WHEN 31 THEN C_31  WHEN 32 THEN C_32  WHEN 33 THEN C_33
      WHEN 34 THEN C_34  WHEN 35 THEN C_35  WHEN 36 THEN C_36
      WHEN 37 THEN C_37
    END;
    service_restant_du(tab_index) := res;
  END;

  PROCEDURE CALCUL_REFERENTIEL( tab_index PLS_INTEGER, id NUMERIC ) IS
    res FLOAT;
    param ose_formule.t_referentiel;
  BEGIN
    param := ose_formule.d_referentiel(id);
    res := CASE tab_index
      WHEN 15 THEN C_15( param )  WHEN 16 THEN C_16( param )  WHEN 17 THEN C_17( param )
      WHEN 25 THEN C_25( param )  WHEN 26 THEN C_26( param )  WHEN 27 THEN C_27( param )
      WHEN 45 THEN C_45( param )  WHEN 46 THEN C_46( param )  WHEN 47 THEN C_47( param )
      WHEN 55 THEN C_55( param )  WHEN 56 THEN C_56( param )  WHEN 57 THEN C_57( param )
      WHEN 65 THEN C_65( param )  WHEN 66 THEN C_66( param )  WHEN 67 THEN C_67( param )
      WHEN 75 THEN C_75( param )  WHEN 76 THEN C_76( param )  WHEN 77 THEN C_77( param )
      WHEN 85 THEN C_85( param )  WHEN 86 THEN C_86( param )  WHEN 87 THEN C_87( param )
    END;
    SET_T_VALUE( tab_index, id, id, res );
  END;

  PROCEDURE CALCUL_RESULTAT_V2( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, ETAT_VOLUME_HORAIRE_ID NUMERIC ) IS
    current_tableau           PLS_INTEGER;
    id                        PLS_INTEGER;
    id2                       PLS_INTEGER;
    val                       FLOAT;
    etat_volume_horaire_ordre NUMERIC;
    dev_null                  NUMERIC;
    TYPE t_liste_tableaux   IS VARRAY (100) OF PLS_INTEGER;
    liste_tableaux            t_liste_tableaux;
    EVH_ORDRE NUMERIC;

    res_service               formule_resultat_service%rowtype;
    res_ref                   formule_resultat_referentiel%rowtype;
  BEGIN
    -- Initialisation
    SELECT ordre INTO EVH_ORDRE FROM etat_volume_horaire WHERE ID = CALCUL_RESULTAT_V2.ETAT_VOLUME_HORAIRE_ID;
    liste_tableaux := t_liste_tableaux();
    t.delete;
    service_restant_du.delete;

    resultat := ose_formule.nouveau_resultat;
    resultat.intervenant_id           := INTERVENANT_ID;
    resultat.annee_id                 := ANNEE_ID;
    resultat.type_volume_horaire_id   := TYPE_VOLUME_HORAIRE_ID;
    resultat.etat_volume_horaire_id   := ETAT_VOLUME_HORAIRE_ID;
    resultat.service_du               := ose_formule.d_intervenant.heures_service_statutaire + ose_formule.d_intervenant.heures_service_modifie;

    liste_tableaux := t_liste_tableaux(
       11,  12,  13,  14,  15,  16,  17,
       21,  22,  23,  24,  25,  26,  27,
       31,  32,  33,  34,  35,  36,  37,
       41,  42,  43,  44,  45,  46,  47,
       51,  52,  53,  54,  55,  56,  57,
       61,  62,  63,  64,  65,  66,  67,
       71,  72,  73,  74,  75,  76,  77,
       81,  82,  83,  84,  85,  86,  87,
                 93,  94,
      101, 102, 103, 104
    );

    id := ose_formule.d_volume_horaire.FIRST;
    LOOP EXIT WHEN id IS NULL;
      IF
        ose_formule.d_volume_horaire(id).type_volume_horaire_id = CALCUL_RESULTAT_V2.TYPE_VOLUME_HORAIRE_ID
        AND ose_formule.d_volume_horaire(id).etat_volume_horaire_ordre >= EVH_ORDRE 
      THEN
        resultat.service := resultat.service + ose_formule.d_volume_horaire( id ).heures;
      END IF;
      id := ose_formule.d_volume_horaire.NEXT(id);
    END LOOP;

    FOR i IN liste_tableaux.FIRST .. liste_tableaux.LAST
    LOOP
      current_tableau := liste_tableaux(i);

      IF current_tableau IN ( -- calcul pour les services
         11,  12,  13,  14,
         21,  22,  23,  24,
         41,  42,  43,  44,
         51,  52,  53,  54,
         61,  62,  63,  64,
         71,  72,  73,  74,
         81,  82,  83,  84,
                   93,  94,
        101, 102, 103, 104
      ) THEN
        t(current_tableau).total := 0;      
        id2 := ose_formule.d_service.FIRST;
        LOOP EXIT WHEN id2 IS NULL;
          t(current_tableau).total_service(id2) := 0;
          id2 := ose_formule.d_service.NEXT(id2);
        END LOOP;
        id := ose_formule.d_volume_horaire.FIRST;
        LOOP EXIT WHEN id IS NULL;
          IF
            ose_formule.d_volume_horaire(id).type_volume_horaire_id = CALCUL_RESULTAT_V2.TYPE_VOLUME_HORAIRE_ID
            AND ose_formule.d_volume_horaire(id).etat_volume_horaire_ordre >= EVH_ORDRE 
          THEN
            CALCUL_VOLUME_HORAIRE( current_tableau, id );
          END IF;
          id := ose_formule.d_volume_horaire.NEXT(id);
        END LOOP;
        
      ELSIF current_tableau IN ( -- calcul des services restants dus
        31, 32, 33, 34, 35, 36, 37
      ) THEN
        CALCUL_SERVICE_RESTANT_DU( current_tableau );

      ELSIF current_tableau IN ( -- tableaux de calcul du référentiel
         15,  16,  17,
         25,  26,  27,
         45,  46,  47,
         55,  56,  57,
         65,  66,  67,
         75,  76,  77,
         85,  86,  87
      ) THEN

        t(current_tableau).total := 0;
        id := ose_formule.d_referentiel.FIRST;
        LOOP EXIT WHEN id IS NULL;
          t(current_tableau).total_service(id) := 0;
          CALCUL_REFERENTIEL( current_tableau, id );
          id := ose_formule.d_referentiel.NEXT(id);
        END LOOP;

      END IF;
    END LOOP;

    resultat.enseignements            := t(51).total + t(52).total + t(53).total + t(54).total + t(81).total + t(82).total + t(93).total + t(94).total;
    resultat.referentiel              := t(55).total + t(56).total + t(57).total + t(85).total + t(86).total + t(87).total;
    resultat.service_assure           := resultat.enseignements + resultat.referentiel;
    resultat.heures_compl_fi          := t(101).total + t(102).total;
    resultat.heures_compl_fc          := t(103).total + t(104).total;
    resultat.heures_compl_referentiel := t(85).total + t(86).total + t(87).total;
    resultat.heures_solde             := resultat.service_assure - resultat.service_du;
    IF resultat.heures_solde >= 0 THEN
      resultat.sous_service           := 0;
      resultat.heures_compl_total     := resultat.heures_solde;
    ELSE
      resultat.sous_service           := resultat.heures_solde * -1;
      resultat.heures_compl_total     := 0;
    END IF;
    resultat.id := OSE_FORMULE.ENREGISTRER_RESULTAT( resultat );

    -- répartition des résultats par service
    id := ose_formule.d_service.FIRST;
    LOOP EXIT WHEN id IS NULL;
      res_service := ose_formule.nouveau_resultat_service;
      res_service.formule_resultat_id := resultat.id;
      res_service.service_id          := id;
      -- calcul des chiffres...
      res_service.heures_service      := RS_1( ose_formule.d_service(id) );
      res_service.heures_compl_fi     := RS_2( ose_formule.d_service(id) );
      res_service.heures_compl_fc     := RS_3( ose_formule.d_service(id) );
      res_service.service_assure      := res_service.heures_service + res_service.heures_compl_fi + res_service.heures_compl_fa + res_service.heures_compl_fc;
      dev_null := ose_formule.ENREGISTRER_RESULTAT_SERVICE( res_service );
      id := ose_formule.d_service.NEXT(id);
    END LOOP;

    -- répartition des résultats par service référentiel
    id := ose_formule.d_referentiel.FIRST;
    LOOP EXIT WHEN id IS NULL;
      res_ref := ose_formule.nouveau_resultat_ref;
      res_ref.formule_resultat_id      := resultat.id;
      res_ref.service_referentiel_id   := id;
      -- calcul des chiffres...
      res_ref.heures_service           := RS_4( ose_formule.d_referentiel(id) );
      res_ref.heures_compl_referentiel := RS_5( ose_formule.d_referentiel(id) );
      
      dev_null := ose_formule.ENREGISTRER_RESULTAT_REF( res_ref );
      id := ose_formule.d_referentiel.NEXT(id);
    END LOOP;
  END;

END UNICAEN_OSE_FORMULE;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_WORKFLOW
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_WORKFLOW" AS

  /**
   * Inscription de l'intervenant dont il faudra regénérer la progression dans le workflow
   */
  PROCEDURE Add_Intervenant_To_Update (p_intervenant_id NUMERIC)
  IS
  BEGIN
    INSERT INTO wf_tmp_intervenant (intervenant_id) VALUES (p_intervenant_id); 
  END;
  
  /**
   * Parcours des intervenants dont il faut regénérer la progression dans le workflow
   */
  PROCEDURE Update_Intervenants_Etapes 
  IS
  BEGIN
    FOR ti IN (SELECT distinct * FROM wf_tmp_intervenant) LOOP
      ose_workflow.Update_Intervenant_Etapes(ti.intervenant_id);
    END LOOP;
    DELETE FROM wf_tmp_intervenant;
  END;
  
  /**
   * Regénère la progression dans le workflow de tous les intervenants dont le statut autorise la saisie de service.
   */
  PROCEDURE Update_All_Intervenants_Etapes 
  IS
    CURSOR intervenant_cur IS 
      SELECT i.* FROM intervenant i 
      JOIN statut_intervenant si ON si.id = i.statut_id AND si.histo_destruction IS NULL AND si.peut_saisir_service = 1
      WHERE i.histo_destruction IS NULL;
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
    structures_ids T_LIST_STRUCTURE_ID;
    structure_id NUMERIC;
    pertinente NUMERIC;
    franchie NUMERIC;
    atteignable NUMERIC;
    courante NUMERIC;
    courante_trouvee NUMERIC := 0;
    ordre NUMERIC := 1;
  BEGIN
    --
    -- RAZ progression.
    --
    DELETE FROM wf_intervenant_etape ie WHERE ie.intervenant_id = p_intervenant_id;
        
    --
    -- Parcours des étapes.
    --
    FOR etape_rec IN (
      --select e.* from wf_etape e where e.code = 'DEBUT'
      --UNION
      -- liste ordonnée des étapes sans les étapes DEBUT et FIN
      select ea.* --ea.id, ea.code, ed.id depart_etape_id, ed.code depart_etape_code
      from wf_etape_to_etape ee
      inner join wf_etape ed on ed.id = ee.depart_etape_id
      inner join wf_etape ea on ea.id = ee.arrivee_etape_id
      where ea.code <> 'FIN'
      connect by ee.depart_etape_id = prior ee.arrivee_etape_id 
      start with ed.code = 'DEBUT'
      --UNION
      --select e.* from wf_etape e where e.code = 'FIN'
    )
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
      structures_ids.DELETE;
      -- id structure null
      structures_ids(structures_ids.COUNT) := NULL;
      -- pour les étapes pouvant être déclinées par structure, collecte des structures d'enseignement
      IF etape_rec.STRUCTURE_DEPENDANT = 1 THEN
        ose_workflow.fetch_structures_ens_ids(p_intervenant_id, structures_ids);
      END IF;
      
      --
      -- Dans la progression de l'intervenant, une même étape peut figurer plusieurs fois : une fois avec un id de structure null 
      -- (ce qui se traduit par "peu importe la structure") + autant de fois qu'il existe de structures d'enseignement dans le cas où 
      -- l'étape est déclinable par structure.
      --
      FOR i IN 0 .. structures_ids.COUNT - 1
      LOOP
        structure_id := structures_ids(i);
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
        
        --
        -- Si l'étape courante n'a pas encore été trouvée.
        --
--        IF courante_trouvee = 0 THEN 
--          IF franchie = 1 THEN 
--            courante := 0;
--          ELSE
--            -- l'étape marquée "courante" est la 1ère étape non franchie quelle que soit la structure
--            IF structure_id IS NULL THEN
--              courante := 1;
--              courante_trouvee := etape_rec.id;
--            END IF;
--          END IF;
--        ELSE
--          -- une étape située après l'étape courante est forcément "non courante"
--          courante := 0;
--          -- une étape située après l'étape courante est forcément "non franchie" SAUF si une structure précise est spécifiée
--          --IF structure_id IS NULL THEN
--          --  franchie := 0;
--          --END IF;
--        END IF;
        
        atteignable := 1;
        IF courante_trouvee = 0 THEN 
          IF franchie = 1 THEN 
            courante := 0;
          ELSE
            -- l'étape marquée "courante" est la 1ère étape non franchie
            courante := 1;
            courante_trouvee := etape_rec.id;
          END IF;
        ELSIF courante_trouvee = etape_rec.id THEN
          courante := 1;
        ELSE
          -- une étape située après l'étape courante est forcément "non courante"
          courante := 0;
          atteignable := 0;
        END IF;
                        
        --
        -- Ecriture dans la table.
        --
        INSERT INTO wf_intervenant_etape (id, intervenant_id, etape_id, structure_id, courante, franchie, atteignable, ordre) 
          SELECT wf_intervenant_etape_id_seq.nextval, p_intervenant_id, etape_rec.id, structure_id, courante, franchie, atteignable, ordre FROM DUAL;
        
        ordre := ordre + 1;
      END LOOP;
      
    END LOOP;
  END;
  
  /**
   *
   */
  PROCEDURE fetch_structures_ens_ids (p_intervenant_id NUMERIC, structures_ids IN OUT T_LIST_STRUCTURE_ID)
  IS
    i PLS_INTEGER;
  BEGIN
    i := structures_ids.COUNT;
    FOR d IN (
      SELECT distinct structure_ens_id FROM service s 
      WHERE s.intervenant_id = p_intervenant_id AND S.ANNEE_ID = OSE_PARAMETRE.GET_ANNEE() AND S.HISTO_DESTRUCTION IS NULL
    ) LOOP
      structures_ids(i) := d.structure_ens_id;
      i := i + 1;
    END LOOP;
  END;
  
  
  /******************** Règles métiers de pertinence et de franchissement des étapes ********************/
  
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
  FUNCTION peut_saisir_service (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT si.peut_saisir_service INTO res FROM statut_intervenant si 
    JOIN intervenant i ON i.statut_id = si.id 
    WHERE i.id = p_intervenant_id;
    RETURN res;
  END;
  
  /**
   *
   */
  FUNCTION possede_services (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    IF p_structure_id IS NULL THEN
      SELECT count(*) INTO res FROM service s 
      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id AND ep.histo_destruction IS NULL
      JOIN etape e ON e.id = ep.etape_id AND e.histo_destruction IS NULL
      WHERE s.intervenant_id = p_intervenant_id AND s.annee_id = ose_parametre.get_annee();
    ELSE
      SELECT count(*) INTO res FROM service s 
      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id 
      JOIN etape e ON e.id = ep.etape_id
      WHERE s.intervenant_id = p_intervenant_id AND s.annee_id = ose_parametre.get_annee()
      AND s.structure_ens_id = p_structure_id;
    END IF;
    RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
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
  FUNCTION peut_saisir_piece_jointe (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
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
  FUNCTION necessite_agrement_cr (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM type_agrement_statut tas 
    JOIN type_agrement ta ON ta.id = tas.type_agrement_id AND ta.code = 'CONSEIL_RESTREINT'
    JOIN statut_intervenant si on tas.statut_intervenant_id = si.id
    JOIN intervenant i ON i.statut_id = si.id
    WHERE tas.PREMIER_RECRUTEMENT = i.PREMIER_RECRUTEMENT AND tas.OBLIGATOIRE = 1 
    AND i.id = p_intervenant_id;
    RETURN res;
  END;
  
  /**
   *
   */
  FUNCTION necessite_agrement_ca (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM type_agrement_statut tas 
    JOIN type_agrement ta ON ta.id = tas.type_agrement_id AND ta.code = 'CONSEIL_ACADEMIQUE'
    JOIN statut_intervenant si on tas.statut_intervenant_id = si.id
    JOIN intervenant i ON i.statut_id = si.id
    WHERE tas.PREMIER_RECRUTEMENT = i.PREMIER_RECRUTEMENT AND tas.OBLIGATOIRE = 1 
    AND i.id = p_intervenant_id;
    RETURN res;
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
  FUNCTION service_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    CURSOR service_cur IS 
      SELECT s.* FROM service s 
      JOIN volume_horaire vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL
      JOIN v_volume_horaire_etat vhe ON vhe.volume_horaire_id = vh.id
      JOIN etat_volume_horaire evh ON evh.id = vhe.etat_volume_horaire_id AND evh.ordre >= ( SELECT min(ordre) FROM etat_volume_horaire WHERE code = 'valide' )
      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id AND ep.histo_destruction IS NULL
      JOIN etape e ON e.id = ep.etape_id AND e.histo_destruction IS NULL
      WHERE s.intervenant_id = p_intervenant_id AND s.annee_id = ose_parametre.get_annee();
--    -- autre version : sans utilisation de la vue v_volume_horaire_etat
--    CURSOR service_cur IS 
--      SELECT s.* FROM service s 
--      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id AND ep.histo_destruction IS NULL
--      JOIN etape e ON e.id = ep.etape_id AND e.histo_destruction IS NULL
--      JOIN volume_horaire vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL
--      JOIN VALIDATION_VOL_HORAIRE vvh on VVH.VOLUME_HORAIRE_ID = vh.id
--      JOIN validation v on VVH.VALIDATION_ID = v.id AND V.HISTO_DESTRUCTION is null
--      WHERE s.intervenant_id = p_intervenant_id AND s.annee_id = ose_parametre.get_annee();
--    CURSOR service_cur IS 
--      SELECT s.* FROM service s 
--      JOIN volume_horaire vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL
--      JOIN v_volume_horaire_etat vhe ON vhe.volume_horaire_id = vh.id
--      JOIN etat_volume_horaire evh ON evh.id = vhe.etat_volume_horaire_id AND evh.ordre < ( SELECT min(ordre) FROM etat_volume_horaire WHERE code = 'valide' )
--      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id AND ep.histo_destruction IS NULL
--      JOIN etape e ON e.id = ep.etape_id AND e.histo_destruction IS NULL
--      WHERE s.intervenant_id = p_intervenant_id AND s.annee_id = ose_parametre.get_annee();
    service_rec service_cur%rowtype;
    res NUMERIC := 0;
    nb numeric;
  BEGIN
    IF p_structure_id IS NULL THEN
      -- si aucune structure n'est spécifiée, on se contente du moindre service trouvé
      OPEN service_cur;
      FETCH service_cur INTO service_rec;
      IF service_cur%FOUND = TRUE THEN
        res := 1;
      END IF;
      CLOSE service_cur;
    ELSE
      -- si une structure précise est spécifiée, on se contente du moindre service trouvé concernant cette structure d'enseignement
      FOR service_rec IN service_cur
      LOOP
        IF service_rec.structure_ens_id = p_structure_id THEN
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
  FUNCTION possede_dossier (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM intervenant_exterieur i JOIN dossier d ON d.id = i.dossier_id AND d.histo_destruction IS NULL
    WHERE i.id = p_intervenant_id;
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
    WHERE v.histo_destruction IS NULL 
    AND v.intervenant_id = p_intervenant_id;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION referentiel_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM validation v 
    JOIN type_validation tv ON tv.id = v.type_validation_id AND tv.code = 'REFERENTIEL' 
    WHERE v.histo_destruction IS NULL 
    AND v.intervenant_id = p_intervenant_id;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION pieces_jointes_fournies (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM (
      WITH 
      ATTENDU_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES pour chaque intervenant
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, COALESCE(vheures.TOTAL_HEURES, 0) TOTAL_HEURES, count(tpjs.id) NB /*+ materialize */
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN DOSSIER d ON IE.DOSSIER_ID = d.ID AND (d.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN STATUT_INTERVENANT si ON d.STATUT_ID = si.ID AND (si.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN si.VALIDITE_DEBUT AND COALESCE(si.VALIDITE_FIN, SYSDATE))
          INNER JOIN TYPE_PIECE_JOINTE_STATUT tpjs ON si.ID = tpjs.STATUT_INTERVENANT_ID AND (tpjs.PREMIER_RECRUTEMENT = d.PREMIER_RECRUTEMENT) AND (tpjs.HISTO_DESTRUCTEUR_ID IS NULL) 
          LEFT JOIN V_PJ_HEURES vheures ON vheures.INTERVENANT_ID = I.ID
          WHERE tpjs.OBLIGATOIRE = 1
          AND (tpjs.SEUIL_HETD IS NULL OR COALESCE(vheures.TOTAL_HEURES, 0) >= tpjs.SEUIL_HETD)
          GROUP BY I.ID, I.SOURCE_CODE, COALESCE(vheures.TOTAL_HEURES, 0)
      ), 
      FOURNI_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES FOURNIES par chaque intervenant
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(tpjAttendu.ID) NB /*+ materialize */
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN DOSSIER d ON IE.DOSSIER_ID = d.ID AND (d.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN STATUT_INTERVENANT si ON d.STATUT_ID = si.ID AND (si.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN si.VALIDITE_DEBUT AND COALESCE(si.VALIDITE_FIN, SYSDATE))
          INNER JOIN TYPE_PIECE_JOINTE_STATUT tpjs ON si.ID = tpjs.STATUT_INTERVENANT_ID AND (tpjs.PREMIER_RECRUTEMENT = d.PREMIER_RECRUTEMENT) AND (tpjs.HISTO_DESTRUCTEUR_ID IS NULL) 
          INNER JOIN TYPE_PIECE_JOINTE tpjAttendu ON tpjs.TYPE_PIECE_JOINTE_ID = tpjAttendu.ID AND (tpjAttendu.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN PIECE_JOINTE pj ON d.ID = pj.DOSSIER_ID AND (pj.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN pj.VALIDITE_DEBUT AND COALESCE(pj.VALIDITE_FIN, SYSDATE))
          INNER JOIN TYPE_PIECE_JOINTE tpjFourni ON pj.TYPE_PIECE_JOINTE_ID = tpjFourni.ID AND (tpjFourni.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN tpjFourni.VALIDITE_DEBUT AND COALESCE(tpjFourni.VALIDITE_FIN, SYSDATE))
          LEFT JOIN V_PJ_HEURES vheures ON vheures.INTERVENANT_ID = I.ID
          WHERE tpjs.OBLIGATOIRE = 1
          AND tpjFourni.ID = tpjAttendu.ID
          AND (tpjs.SEUIL_HETD IS NULL OR COALESCE(vheures.TOTAL_HEURES, 0) >= tpjs.SEUIL_HETD)
          -- %s
          AND pj.VALIDATION_ID IS NOT NULL -- %s
          GROUP BY I.ID, I.SOURCE_CODE
      ), 
      ATTENDU_FACULTATIF AS (
          -- nombres de pj FACULTATIVES pour chaque intervenant
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, COALESCE(vheures.TOTAL_HEURES, 0) TOTAL_HEURES, count(tpjs.id) NB /*+ materialize */
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN DOSSIER d ON IE.DOSSIER_ID = d.ID AND (d.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN STATUT_INTERVENANT si ON d.STATUT_ID = si.ID AND (si.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN si.VALIDITE_DEBUT AND COALESCE(si.VALIDITE_FIN, SYSDATE))
          INNER JOIN TYPE_PIECE_JOINTE_STATUT tpjs ON si.ID = tpjs.STATUT_INTERVENANT_ID AND (tpjs.PREMIER_RECRUTEMENT = d.PREMIER_RECRUTEMENT) AND (tpjs.HISTO_DESTRUCTEUR_ID IS NULL) 
          LEFT JOIN V_PJ_HEURES vheures ON vheures.INTERVENANT_ID = I.ID
          WHERE (tpjs.OBLIGATOIRE = 0 OR tpjs.OBLIGATOIRE = 1 AND tpjs.SEUIL_HETD IS NOT NULL AND COALESCE(vheures.TOTAL_HEURES, 0) < tpjs.SEUIL_HETD)
          GROUP BY I.ID, I.SOURCE_CODE, COALESCE(vheures.TOTAL_HEURES, 0)
      ), 
      FOURNI_FACULTATIF AS (
          -- nombres de pj FACULTATIVES FOURNIES par chaque intervenant
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(tpjAttendu.ID) NB /*+ materialize */
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND (I.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN DOSSIER d ON IE.DOSSIER_ID = d.ID AND (d.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN STATUT_INTERVENANT si ON d.STATUT_ID = si.ID AND (si.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN si.VALIDITE_DEBUT AND COALESCE(si.VALIDITE_FIN, SYSDATE))
          INNER JOIN TYPE_PIECE_JOINTE_STATUT tpjs ON si.ID = tpjs.STATUT_INTERVENANT_ID AND (tpjs.PREMIER_RECRUTEMENT = d.PREMIER_RECRUTEMENT) AND (tpjs.HISTO_DESTRUCTEUR_ID IS NULL) 
          INNER JOIN TYPE_PIECE_JOINTE tpjAttendu ON tpjs.TYPE_PIECE_JOINTE_ID = tpjAttendu.ID AND (tpjAttendu.HISTO_DESTRUCTEUR_ID IS NULL)
          INNER JOIN PIECE_JOINTE pj ON d.ID = pj.DOSSIER_ID AND (pj.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN pj.VALIDITE_DEBUT AND COALESCE(pj.VALIDITE_FIN, SYSDATE))
          INNER JOIN TYPE_PIECE_JOINTE tpjFourni ON pj.TYPE_PIECE_JOINTE_ID = tpjFourni.ID AND (tpjFourni.HISTO_DESTRUCTEUR_ID IS NULL AND SYSDATE BETWEEN tpjFourni.VALIDITE_DEBUT AND COALESCE(tpjFourni.VALIDITE_FIN, SYSDATE))
          LEFT JOIN V_PJ_HEURES vheures ON vheures.INTERVENANT_ID = I.ID
          WHERE (tpjs.OBLIGATOIRE = 0 OR tpjs.OBLIGATOIRE = 1 AND tpjs.SEUIL_HETD IS NOT NULL AND COALESCE(vheures.TOTAL_HEURES, 0) < tpjs.SEUIL_HETD)
          AND tpjFourni.ID = tpjAttendu.ID
          GROUP BY I.ID, I.SOURCE_CODE
      )
      SELECT 
          COALESCE(AO.INTERVENANT_ID, AF.INTERVENANT_ID) ID, 
          COALESCE(AO.SOURCE_CODE, AF.SOURCE_CODE)       SOURCE_CODE, 
          COALESCE(AO.TOTAL_HEURES, AF.TOTAL_HEURES)     TOTAL_HEURES, 
          COALESCE(AO.NB, 0)                             NB_PJ_OBLIG_ATTENDU, 
          COALESCE(FO.NB, 0)                             NB_PJ_OBLIG_FOURNI, 
          COALESCE(AF.NB, 0)                             NB_PJ_FACUL_ATTENDU, 
          COALESCE(FF.NB, 0)                             NB_PJ_FACUL_FOURNI 
      FROM            ATTENDU_OBLIGATOIRE AO
      FULL OUTER JOIN ATTENDU_FACULTATIF  AF ON AF.INTERVENANT_ID = AO.INTERVENANT_ID
      LEFT JOIN       FOURNI_OBLIGATOIRE  FO ON FO.INTERVENANT_ID = AO.INTERVENANT_ID
      LEFT JOIN       FOURNI_FACULTATIF   FF ON FF.INTERVENANT_ID = AF.INTERVENANT_ID
      WHERE COALESCE(AO.INTERVENANT_ID, AF.INTERVENANT_ID) = p_intervenant_id
    )
    WHERE NB_PJ_OBLIG_ATTENDU <= NB_PJ_OBLIG_FOURNI;
    
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION agrement_cr_fourni (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
    code VARCHAR2(64) := 'CONSEIL_RESTREINT';
  BEGIN
    WITH 
    composantes_enseign AS (
        -- composantes d'enseignement par intervenant
        SELECT DISTINCT i.ID, i.source_code, s.structure_ens_id
        FROM service s
        INNER JOIN intervenant i ON i.ID = s.intervenant_id AND (i.histo_destructeur_id IS NULL)
        INNER JOIN STRUCTURE comp ON comp.ID = s.structure_ens_id AND (comp.histo_destructeur_id IS NULL)
        WHERE s.histo_destructeur_id IS NULL
        AND (p_structure_id IS NULL OR p_structure_id IS NOT NULL AND s.structure_ens_id = p_structure_id)
    ),
    agrements_oblig_exist AS (
        -- agréments obligatoires obtenus par intervenant et structure
        SELECT i.ID, i.source_code, A.type_agrement_id, A.ID agrement_id, A.structure_id
        FROM agrement A
        INNER JOIN type_agrement ta ON A.type_agrement_id = ta.ID AND (ta.histo_destructeur_id IS NULL)
        INNER JOIN intervenant i ON A.intervenant_id = i.ID AND (i.histo_destructeur_id IS NULL)
        INNER JOIN type_agrement_statut tas ON i.statut_id = tas.statut_intervenant_id AND ta.ID = tas.type_agrement_id 
            AND i.premier_recrutement = tas.premier_recrutement AND tas.obligatoire = 1 AND (tas.histo_destructeur_id IS NULL) 
        WHERE A.histo_destructeur_id IS NULL
        AND ta.code = code
        AND (p_structure_id IS NULL OR p_structure_id IS NOT NULL AND A.structure_id = p_structure_id)
    ), 
    v_agrement AS (
      -- nombres de composantes d'enseignement et d'agrément obligatoires fournis par intervenant
      SELECT DISTINCT i.ID, i.source_code, 
        ( select count(*) from COMPOSANTES_ENSEIGN ce where ce.id = i.id ) nb_comp, 
        ( select count(*) from AGREMENTS_OBLIG_EXIST ao where ao.id = i.id ) nb_agrem
      FROM intervenant i 
      WHERE i.histo_destructeur_id IS NULL
    )
    SELECT COUNT(*) INTO res
    FROM v_agrement v
    WHERE (
      -- si aucune structure précise n'est spécifiée, on ne retient que les intervenants qui ont au moins un d'agrément CR
      p_structure_id IS NULL AND nb_agrem > 0
      OR 
      -- si une structure précise est spécifiée, on ne retient que les intervenants qui ont (au moins) autant d'agréments CR que de composantes d'enseignement
      p_structure_id IS NOT NULL AND v.nb_comp <= nb_agrem 
    ) 
    AND v.id = p_intervenant_id ;
    
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION agrement_ca_fourni (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
    v_code VARCHAR2(64) := 'CONSEIL_ACADEMIQUE';
  BEGIN
    WITH 
    agrements_oblig_exist AS (
        -- agréments obligatoires obtenus par intervenant et structure
        SELECT i.ID, i.source_code, A.type_agrement_id, A.ID agrement_id, A.structure_id
        FROM agrement A
        INNER JOIN type_agrement ta ON A.type_agrement_id = ta.ID AND (ta.histo_destructeur_id IS NULL)
        INNER JOIN intervenant i ON A.intervenant_id = i.ID AND (i.histo_destructeur_id IS NULL)
        INNER JOIN type_agrement_statut tas ON i.statut_id = tas.statut_intervenant_id AND ta.ID = tas.type_agrement_id 
            AND i.premier_recrutement = tas.premier_recrutement AND tas.obligatoire = 1 AND (tas.histo_destructeur_id IS NULL) 
        WHERE A.histo_destructeur_id IS NULL
        AND ta.code = v_code
    ), 
    v_agrement AS (
      -- nombres d'agrément obligatoires fournis par intervenant
      SELECT DISTINCT i.ID, i.source_code, 
        ( select count(*) from AGREMENTS_OBLIG_EXIST ao where ao.id = i.id ) nb_agrem
      FROM intervenant i 
      WHERE i.histo_destructeur_id IS NULL
    )
    SELECT COUNT(*) INTO res
    FROM v_agrement v
    WHERE nb_agrem > 0
    AND v.id = p_intervenant_id ;
    
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
    JOIN validation v ON c.validation_id = v.id AND v.histo_destruction IS NULL
    WHERE c.HISTO_DESTRUCTION IS NULL 
    AND c.intervenant_id = p_intervenant_id
    AND (p_structure_id IS NULL OR p_structure_id IS NOT NULL AND c.STRUCTURE_ID = p_structure_id) 
    AND ROWNUM = 1;
    
    RETURN res;
  END;

END OSE_WORKFLOW;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_IMPORT
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_IMPORT" IS

  FUNCTION get_date_obs RETURN Date IS
  BEGIN
    RETURN v_date_obs;
  END get_date_obs;

  FUNCTION get_current_user RETURN INTEGER IS
  BEGIN
    RETURN v_current_user;
  END get_current_user;
 
 
  PROCEDURE set_current_user (p_current_user INTEGER) is
  BEGIN
    v_current_user := p_current_user;
  END set_current_user;


  FUNCTION get_type_intervenant_id( src_code varchar2 ) RETURN Numeric IS
    res_id Numeric;
  BEGIN
    SELECT s.id INTO res_id FROM ose.type_intervenant s WHERE s.code = src_code;
    RETURN res_id;
  END get_type_intervenant_id;


  FUNCTION get_civilite_id( src_libelle_court varchar2 ) RETURN Numeric IS
    res_id Numeric;
  BEGIN
    SELECT s.id INTO res_id FROM ose.civilite s WHERE s.libelle_court = src_libelle_court;
    RETURN res_id;
  END get_civilite_id;


  FUNCTION get_source_id( src_code Varchar2 ) RETURN Numeric IS
    res_id Numeric;
  BEGIN
    SELECT s.id INTO res_id FROM ose.source s WHERE s.code = src_code;
    RETURN res_id;
  END get_source_id;

  PROCEDURE SYNC_LOG( message CLOB ) IS
  BEGIN
    INSERT INTO OSE.SYNC_LOG("ID","DATE_SYNC","MESSAGE") VALUES (SYNC_LOG_ID_SEQ.NEXTVAL, SYSDATE, message);
  END SYNC_LOG;

  PROCEDURE SYNC_MVS IS
  BEGIN
    -- Mise à jour des vues matérialisées
    DBMS_MVIEW.REFRESH('MV_ETABLISSEMENT', 'C');
    DBMS_MVIEW.REFRESH('MV_STRUCTURE', 'C');
    DBMS_MVIEW.REFRESH('MV_ADRESSE_STRUCTURE', 'C');
    
    DBMS_MVIEW.REFRESH('MV_PERSONNEL', 'C');
    DBMS_MVIEW.REFRESH('MV_ROLE', 'C');
    
    DBMS_MVIEW.REFRESH('MV_CORPS', 'C');
    
    DBMS_MVIEW.REFRESH('MV_HARP_IND_DER_STRUCT', 'C');
    DBMS_MVIEW.REFRESH('MV_HARP_INDIVIDU_BANQUE', 'C');
    DBMS_MVIEW.REFRESH('MV_HARP_INDIVIDU_STATUT', 'C');
    
    DBMS_MVIEW.REFRESH('MV_INTERVENANT', 'C');
    DBMS_MVIEW.REFRESH('MV_INTERVENANT_EXTERIEUR', 'C');
    DBMS_MVIEW.REFRESH('MV_INTERVENANT_PERMANENT', 'C');
    DBMS_MVIEW.REFRESH('MV_AFFECTATION_RECHERCHE', 'C');
    DBMS_MVIEW.REFRESH('MV_ADRESSE_INTERVENANT', 'C');
    
    DBMS_MVIEW.REFRESH('MV_GROUPE_TYPE_FORMATION', 'C');
    DBMS_MVIEW.REFRESH('MV_TYPE_FORMATION', 'C');
    DBMS_MVIEW.REFRESH('MV_ETAPE', 'C');
    DBMS_MVIEW.REFRESH('MV_ELEMENT_PEDAGOGIQUE', 'C');
    DBMS_MVIEW.REFRESH('MV_EFFECTIFS', 'C');
    --DBMS_MVIEW.REFRESH('MV_ELEMENT_TAUX_REGIMES', 'C'); -- Refresh manuel une fois par an ! ! !
    DBMS_MVIEW.REFRESH('MV_CHEMIN_PEDAGOGIQUE', 'C');
    DBMS_MVIEW.REFRESH('MV_ELEMENT_PORTEUR_PORTE', 'C');
    DBMS_MVIEW.REFRESH('MV_DISCIPLINE', 'C');
    DBMS_MVIEW.REFRESH('MV_ELEMENT_DISCIPLINE', 'C');
    DBMS_MVIEW.REFRESH('MV_VOLUME_HORAIRE_ENS', 'C');  
  END;

  PROCEDURE SYNC_VOLUME_HORAIRE_ENS IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_VOLUME_HORAIRE_ENS();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_VOLUME_HORAIRE_ENS;

  PROCEDURE SYNC_ELEMENT_DISCIPLINE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ELEMENT_DISCIPLINE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ELEMENT_DISCIPLINE;

  PROCEDURE SYNC_DISCIPLINE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_DISCIPLINE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_DISCIPLINE;

  PROCEDURE SYNC_CHEMIN_PEDAGOGIQUE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_CHEMIN_PEDAGOGIQUE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_CHEMIN_PEDAGOGIQUE;

  PROCEDURE SYNC_ELEMENT_PEDAGOGIQUE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ELEMENT_PEDAGOGIQUE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ELEMENT_PEDAGOGIQUE;


  PROCEDURE SYNC_EFFECTIFS IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_EFFECTIFS();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_EFFECTIFS;

  PROCEDURE SYNC_ELEMENT_TAUX_REGIMES IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ELEMENT_TAUX_REGIMES();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ELEMENT_TAUX_REGIMES;

  PROCEDURE SYNC_ETAPE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ETAPE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ETAPE;

  PROCEDURE SYNC_TYPE_FORMATION IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_TYPE_FORMATION();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_TYPE_FORMATION;

  PROCEDURE SYNC_GROUPE_TYPE_FORMATION IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_GROUPE_TYPE_FORMATION();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_GROUPE_TYPE_FORMATION;

  PROCEDURE SYNC_ADRESSE_INTERVENANT IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ADRESSE_INTERVENANT('WHERE INTERVENANT_ID IS NOT NULL');
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ADRESSE_INTERVENANT;

  PROCEDURE SYNC_AFFECTATION_RECHERCHE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_AFFECTATION_RECHERCHE('WHERE INTERVENANT_ID IS NOT NULL');
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_AFFECTATION_RECHERCHE;

  PROCEDURE SYNC_TYPE_INTERVENTION_EP IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_TYPE_INTERVENTION_EP();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_TYPE_INTERVENTION_EP;

  PROCEDURE SYNC_TYPE_MODULATEUR_EP IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_TYPE_MODULATEUR_EP();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_TYPE_MODULATEUR_EP;

  PROCEDURE SYNC_ETABLISSEMENT IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ETABLISSEMENT();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ETABLISSEMENT;

  PROCEDURE SYNC_STRUCTURE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_STRUCTURE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_STRUCTURE;

  PROCEDURE SYNC_ADRESSE_STRUCTURE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ADRESSE_STRUCTURE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ADRESSE_STRUCTURE;

  PROCEDURE SYNC_PERSONNEL IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_PERSONNEL();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_PERSONNEL;
  
  PROCEDURE SYNC_ROLE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ROLE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ROLE;  

  PROCEDURE SYNC_CORPS IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_CORPS();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_CORPS; 

  PROCEDURE SYNC_INTERVENANT IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_INTERVENANT( -- Met à jour toutes les données sauf le statut, qui sera traité à part
        'WHERE IMPORT_ACTION IN (''delete'',''update'',''undelete'')'
      );
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_INTERVENANT; 
  
  PROCEDURE SYNC_INTERVENANT_PERMANENT IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_INTERVENANT_PERMANENT(
        'WHERE (IMPORT_ACTION IN (''delete'',''update'',''undelete'') OR SOURCE_CODE IN (SELECT SOURCE_CODE FROM "INTERVENANT"))'
      );
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_INTERVENANT_PERMANENT; 
  
  PROCEDURE SYNC_INTERVENANT_EXTERIEUR IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_INTERVENANT_EXTERIEUR(
        'WHERE (IMPORT_ACTION IN (''delete'',''update'',''undelete'') OR SOURCE_CODE IN (SELECT SOURCE_CODE FROM "INTERVENANT"))'
      );
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_INTERVENANT_EXTERIEUR;   

  PROCEDURE SYNC_TABLES IS
  BEGIN
    OSE_IMPORT.SET_CURRENT_USER( OSE_PARAMETRE.GET_OSE_USER() );
 
    SYNC_ETABLISSEMENT;
    SYNC_STRUCTURE;
    SYNC_ADRESSE_STRUCTURE;

    SYNC_PERSONNEL;
    SYNC_ROLE;
    
    SYNC_CORPS;

    SYNC_INTERVENANT;
    SYNC_INTERVENANT_EXTERIEUR;
    SYNC_INTERVENANT_PERMANENT;
    
    SYNC_AFFECTATION_RECHERCHE;
    SYNC_ADRESSE_INTERVENANT;
    SYNC_GROUPE_TYPE_FORMATION;
    SYNC_TYPE_FORMATION;
    SYNC_ETAPE;
    SYNC_ELEMENT_PEDAGOGIQUE;
    SYNC_EFFECTIFS;
    --SYNC_ELEMENT_TAUX_REGIMES; -- Synchronisation manuelle ! ! !
    SYNC_CHEMIN_PEDAGOGIQUE;
    SYNC_DISCIPLINE;
    SYNC_ELEMENT_DISCIPLINE;
    SYNC_VOLUME_HORAIRE_ENS;
    
    -- Mise à jour des sources calculées en dernier
    SYNC_TYPE_INTERVENTION_EP;
    SYNC_TYPE_MODULATEUR_EP;
  END;

  PROCEDURE SYNCHRONISATION IS
  BEGIN
    SYNC_MVS;
    SYNC_TABLES;
  END SYNCHRONISATION;

  FUNCTION IN_COLUMN_LIST( VALEUR VARCHAR2, CHAMPS CLOB ) RETURN NUMERIC IS
  BEGIN
    IF REGEXP_LIKE(CHAMPS, '(^|,)[ \t\r\n\v\f]*' || VALEUR || '[ \t\r\n\v\f]*(,|$)') THEN RETURN 1; END IF;
    RETURN 0;
  END;

  -- AUTOMATIC GENERATION --

  PROCEDURE MAJ_VOLUME_HORAIRE_ENS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_VOLUME_HORAIRE_ENS%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_VOLUME_HORAIRE_ENS.* FROM V_DIFF_VOLUME_HORAIRE_ENS ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.VOLUME_HORAIRE_ENS
            ( id, ANNEE_ID,ELEMENT_DISCIPLINE_ID,HEURES,TYPE_INTERVENTION_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,VOLUME_HORAIRE_ENS_ID_SEQ.NEXTVAL), diff_row.ANNEE_ID,diff_row.ELEMENT_DISCIPLINE_ID,diff_row.HEURES,diff_row.TYPE_INTERVENTION_ID, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_DISCIPLINE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET ELEMENT_DISCIPLINE_ID = diff_row.ELEMENT_DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_HEURES = 1 AND IN_COLUMN_LIST('HEURES',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET HEURES = diff_row.HEURES WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_INTERVENTION_ID = 1 AND IN_COLUMN_LIST('TYPE_INTERVENTION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET TYPE_INTERVENTION_ID = diff_row.TYPE_INTERVENTION_ID WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.VOLUME_HORAIRE_ENS SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_DISCIPLINE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET ELEMENT_DISCIPLINE_ID = diff_row.ELEMENT_DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_HEURES = 1 AND IN_COLUMN_LIST('HEURES',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET HEURES = diff_row.HEURES WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_INTERVENTION_ID = 1 AND IN_COLUMN_LIST('TYPE_INTERVENTION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET TYPE_INTERVENTION_ID = diff_row.TYPE_INTERVENTION_ID WHERE ID = diff_row.id; END IF;
          UPDATE OSE.VOLUME_HORAIRE_ENS SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_VOLUME_HORAIRE_ENS;



  PROCEDURE MAJ_TYPE_MODULATEUR_EP(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_TYPE_MODULATEUR_EP%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_TYPE_MODULATEUR_EP.* FROM V_DIFF_TYPE_MODULATEUR_EP ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.TYPE_MODULATEUR_EP
            ( id, ELEMENT_PEDAGOGIQUE_ID,TYPE_MODULATEUR_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,TYPE_MODULATEUR_EP_ID_SEQ.NEXTVAL), diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.TYPE_MODULATEUR_ID, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_MODULATEUR_EP SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_MODULATEUR_ID = 1 AND IN_COLUMN_LIST('TYPE_MODULATEUR_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_MODULATEUR_EP SET TYPE_MODULATEUR_ID = diff_row.TYPE_MODULATEUR_ID WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.TYPE_MODULATEUR_EP SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_MODULATEUR_EP SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_MODULATEUR_ID = 1 AND IN_COLUMN_LIST('TYPE_MODULATEUR_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_MODULATEUR_EP SET TYPE_MODULATEUR_ID = diff_row.TYPE_MODULATEUR_ID WHERE ID = diff_row.id; END IF;
          UPDATE OSE.TYPE_MODULATEUR_EP SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_TYPE_MODULATEUR_EP;



  PROCEDURE MAJ_TYPE_INTERVENTION_EP(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_TYPE_INTERVENTION_EP%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_TYPE_INTERVENTION_EP.* FROM V_DIFF_TYPE_INTERVENTION_EP ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.TYPE_INTERVENTION_EP
            ( id, ELEMENT_PEDAGOGIQUE_ID,TYPE_INTERVENTION_ID,VISIBLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,TYPE_INTERVENTION_EP_ID_SEQ.NEXTVAL), diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.TYPE_INTERVENTION_ID,diff_row.VISIBLE, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_INTERVENTION_ID = 1 AND IN_COLUMN_LIST('TYPE_INTERVENTION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET TYPE_INTERVENTION_ID = diff_row.TYPE_INTERVENTION_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VISIBLE = 1 AND IN_COLUMN_LIST('VISIBLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET VISIBLE = diff_row.VISIBLE WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.TYPE_INTERVENTION_EP SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_INTERVENTION_ID = 1 AND IN_COLUMN_LIST('TYPE_INTERVENTION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET TYPE_INTERVENTION_ID = diff_row.TYPE_INTERVENTION_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VISIBLE = 1 AND IN_COLUMN_LIST('VISIBLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET VISIBLE = diff_row.VISIBLE WHERE ID = diff_row.id; END IF;
          UPDATE OSE.TYPE_INTERVENTION_EP SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_TYPE_INTERVENTION_EP;



  PROCEDURE MAJ_TYPE_FORMATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_TYPE_FORMATION%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_TYPE_FORMATION.* FROM V_DIFF_TYPE_FORMATION ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.TYPE_FORMATION
            ( id, GROUPE_ID,LIBELLE_COURT,LIBELLE_LONG, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,TYPE_FORMATION_ID_SEQ.NEXTVAL), diff_row.GROUPE_ID,diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_GROUPE_ID = 1 AND IN_COLUMN_LIST('GROUPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET GROUPE_ID = diff_row.GROUPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.TYPE_FORMATION SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_GROUPE_ID = 1 AND IN_COLUMN_LIST('GROUPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET GROUPE_ID = diff_row.GROUPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          UPDATE OSE.TYPE_FORMATION SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_TYPE_FORMATION;



  PROCEDURE MAJ_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_STRUCTURE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_STRUCTURE.* FROM V_DIFF_STRUCTURE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.STRUCTURE
            ( id, ETABLISSEMENT_ID,LIBELLE_COURT,LIBELLE_LONG,NIVEAU,PARENTE_ID,STRUCTURE_NIV2_ID,TYPE_ID,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,STRUCTURE_ID_SEQ.NEXTVAL), diff_row.ETABLISSEMENT_ID,diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG,diff_row.NIVEAU,diff_row.PARENTE_ID,diff_row.STRUCTURE_NIV2_ID,diff_row.TYPE_ID,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_ETABLISSEMENT_ID = 1 AND IN_COLUMN_LIST('ETABLISSEMENT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET ETABLISSEMENT_ID = diff_row.ETABLISSEMENT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NIVEAU = 1 AND IN_COLUMN_LIST('NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET NIVEAU = diff_row.NIVEAU WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PARENTE_ID = 1 AND IN_COLUMN_LIST('PARENTE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET PARENTE_ID = diff_row.PARENTE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_NIV2_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_NIV2_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET STRUCTURE_NIV2_ID = diff_row.STRUCTURE_NIV2_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_ID = 1 AND IN_COLUMN_LIST('TYPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET TYPE_ID = diff_row.TYPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.STRUCTURE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_ETABLISSEMENT_ID = 1 AND IN_COLUMN_LIST('ETABLISSEMENT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET ETABLISSEMENT_ID = diff_row.ETABLISSEMENT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NIVEAU = 1 AND IN_COLUMN_LIST('NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET NIVEAU = diff_row.NIVEAU WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PARENTE_ID = 1 AND IN_COLUMN_LIST('PARENTE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET PARENTE_ID = diff_row.PARENTE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_NIV2_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_NIV2_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET STRUCTURE_NIV2_ID = diff_row.STRUCTURE_NIV2_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_ID = 1 AND IN_COLUMN_LIST('TYPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET TYPE_ID = diff_row.TYPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.STRUCTURE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_STRUCTURE;



  PROCEDURE MAJ_ROLE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ROLE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ROLE.* FROM V_DIFF_ROLE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ROLE
            ( id, PERSONNEL_ID,STRUCTURE_ID,TYPE_ID,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ROLE_ID_SEQ.NEXTVAL), diff_row.PERSONNEL_ID,diff_row.STRUCTURE_ID,diff_row.TYPE_ID,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_PERSONNEL_ID = 1 AND IN_COLUMN_LIST('PERSONNEL_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET PERSONNEL_ID = diff_row.PERSONNEL_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_ID = 1 AND IN_COLUMN_LIST('TYPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET TYPE_ID = diff_row.TYPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ROLE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_PERSONNEL_ID = 1 AND IN_COLUMN_LIST('PERSONNEL_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET PERSONNEL_ID = diff_row.PERSONNEL_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_ID = 1 AND IN_COLUMN_LIST('TYPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET TYPE_ID = diff_row.TYPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ROLE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ROLE;



  PROCEDURE MAJ_PERSONNEL(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_PERSONNEL%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_PERSONNEL.* FROM V_DIFF_PERSONNEL ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.PERSONNEL
            ( id, CIVILITE_ID,EMAIL,NOM_PATRONYMIQUE,NOM_USUEL,PRENOM,STRUCTURE_ID,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,PERSONNEL_ID_SEQ.NEXTVAL), diff_row.CIVILITE_ID,diff_row.EMAIL,diff_row.NOM_PATRONYMIQUE,diff_row.NOM_USUEL,diff_row.PRENOM,diff_row.STRUCTURE_ID,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_PATRONYMIQUE = 1 AND IN_COLUMN_LIST('NOM_PATRONYMIQUE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET NOM_PATRONYMIQUE = diff_row.NOM_PATRONYMIQUE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_USUEL = 1 AND IN_COLUMN_LIST('NOM_USUEL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET NOM_USUEL = diff_row.NOM_USUEL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRENOM = 1 AND IN_COLUMN_LIST('PRENOM',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET PRENOM = diff_row.PRENOM WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.PERSONNEL SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_PATRONYMIQUE = 1 AND IN_COLUMN_LIST('NOM_PATRONYMIQUE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET NOM_PATRONYMIQUE = diff_row.NOM_PATRONYMIQUE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_USUEL = 1 AND IN_COLUMN_LIST('NOM_USUEL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET NOM_USUEL = diff_row.NOM_USUEL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRENOM = 1 AND IN_COLUMN_LIST('PRENOM',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET PRENOM = diff_row.PRENOM WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.PERSONNEL SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_PERSONNEL;



  PROCEDURE MAJ_INTERVENANT_PERMANENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_INTERVENANT_PERMANENT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_INTERVENANT_PERMANENT.* FROM V_DIFF_INTERVENANT_PERMANENT ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.INTERVENANT_PERMANENT
            ( id, CORPS_ID,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,INTERVENANT_PERMANENT_ID_SEQ.NEXTVAL), diff_row.CORPS_ID,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_CORPS_ID = 1 AND IN_COLUMN_LIST('CORPS_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_PERMANENT SET CORPS_ID = diff_row.CORPS_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_PERMANENT SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_PERMANENT SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.INTERVENANT_PERMANENT SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_CORPS_ID = 1 AND IN_COLUMN_LIST('CORPS_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_PERMANENT SET CORPS_ID = diff_row.CORPS_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_PERMANENT SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_PERMANENT SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.INTERVENANT_PERMANENT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_INTERVENANT_PERMANENT;



  PROCEDURE MAJ_INTERVENANT_EXTERIEUR(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_INTERVENANT_EXTERIEUR%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_INTERVENANT_EXTERIEUR.* FROM V_DIFF_INTERVENANT_EXTERIEUR ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.INTERVENANT_EXTERIEUR
            ( id, SITUATION_FAMILIALE_ID,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,INTERVENANT_EXTERIEUR_ID_SEQ.NEXTVAL), diff_row.SITUATION_FAMILIALE_ID,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_SITUATION_FAMILIALE_ID = 1 AND IN_COLUMN_LIST('SITUATION_FAMILIALE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_EXTERIEUR SET SITUATION_FAMILIALE_ID = diff_row.SITUATION_FAMILIALE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_EXTERIEUR SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_EXTERIEUR SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.INTERVENANT_EXTERIEUR SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_SITUATION_FAMILIALE_ID = 1 AND IN_COLUMN_LIST('SITUATION_FAMILIALE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_EXTERIEUR SET SITUATION_FAMILIALE_ID = diff_row.SITUATION_FAMILIALE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_EXTERIEUR SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_EXTERIEUR SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.INTERVENANT_EXTERIEUR SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_INTERVENANT_EXTERIEUR;



  PROCEDURE MAJ_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_INTERVENANT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_INTERVENANT.* FROM V_DIFF_INTERVENANT ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.INTERVENANT
            ( id, BIC,CIVILITE_ID,DATE_NAISSANCE,DEP_NAISSANCE_CODE_INSEE,DEP_NAISSANCE_LIBELLE,EMAIL,IBAN,NOM_PATRONYMIQUE,NOM_USUEL,NUMERO_INSEE,NUMERO_INSEE_CLE,NUMERO_INSEE_PROVISOIRE,PAYS_NAISSANCE_CODE_INSEE,PAYS_NAISSANCE_LIBELLE,PAYS_NATIONALITE_CODE_INSEE,PAYS_NATIONALITE_LIBELLE,PRENOM,STATUT_ID,STRUCTURE_ID,TEL_MOBILE,TEL_PRO,TYPE_ID,VILLE_NAISSANCE_CODE_INSEE,VILLE_NAISSANCE_LIBELLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,INTERVENANT_ID_SEQ.NEXTVAL), diff_row.BIC,diff_row.CIVILITE_ID,diff_row.DATE_NAISSANCE,diff_row.DEP_NAISSANCE_CODE_INSEE,diff_row.DEP_NAISSANCE_LIBELLE,diff_row.EMAIL,diff_row.IBAN,diff_row.NOM_PATRONYMIQUE,diff_row.NOM_USUEL,diff_row.NUMERO_INSEE,diff_row.NUMERO_INSEE_CLE,diff_row.NUMERO_INSEE_PROVISOIRE,diff_row.PAYS_NAISSANCE_CODE_INSEE,diff_row.PAYS_NAISSANCE_LIBELLE,diff_row.PAYS_NATIONALITE_CODE_INSEE,diff_row.PAYS_NATIONALITE_LIBELLE,diff_row.PRENOM,diff_row.STATUT_ID,diff_row.STRUCTURE_ID,diff_row.TEL_MOBILE,diff_row.TEL_PRO,diff_row.TYPE_ID,diff_row.VILLE_NAISSANCE_CODE_INSEE,diff_row.VILLE_NAISSANCE_LIBELLE, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_BIC = 1 AND IN_COLUMN_LIST('BIC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET BIC = diff_row.BIC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DATE_NAISSANCE = 1 AND IN_COLUMN_LIST('DATE_NAISSANCE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DATE_NAISSANCE = diff_row.DATE_NAISSANCE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DEP_NAISSANCE_CODE_INSEE = diff_row.DEP_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DEP_NAISSANCE_LIBELLE = diff_row.DEP_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
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
          IF (diff_row.u_TYPE_ID = 1 AND IN_COLUMN_LIST('TYPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET TYPE_ID = diff_row.TYPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('VILLE_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET VILLE_NAISSANCE_CODE_INSEE = diff_row.VILLE_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('VILLE_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET VILLE_NAISSANCE_LIBELLE = diff_row.VILLE_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.INTERVENANT SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_BIC = 1 AND IN_COLUMN_LIST('BIC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET BIC = diff_row.BIC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DATE_NAISSANCE = 1 AND IN_COLUMN_LIST('DATE_NAISSANCE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DATE_NAISSANCE = diff_row.DATE_NAISSANCE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DEP_NAISSANCE_CODE_INSEE = diff_row.DEP_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DEP_NAISSANCE_LIBELLE = diff_row.DEP_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
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
          IF (diff_row.u_TYPE_ID = 1 AND IN_COLUMN_LIST('TYPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET TYPE_ID = diff_row.TYPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('VILLE_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET VILLE_NAISSANCE_CODE_INSEE = diff_row.VILLE_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('VILLE_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET VILLE_NAISSANCE_LIBELLE = diff_row.VILLE_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;
          UPDATE OSE.INTERVENANT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_INTERVENANT;



  PROCEDURE MAJ_GROUPE_TYPE_FORMATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_GROUPE_TYPE_FORMATION%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_GROUPE_TYPE_FORMATION.* FROM V_DIFF_GROUPE_TYPE_FORMATION ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.GROUPE_TYPE_FORMATION
            ( id, LIBELLE_COURT,LIBELLE_LONG,ORDRE,PERTINENCE_NIVEAU, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,GROUPE_TYPE_FORMATION_ID_SEQ.NEXTVAL), diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG,diff_row.ORDRE,diff_row.PERTINENCE_NIVEAU, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PERTINENCE_NIVEAU = 1 AND IN_COLUMN_LIST('PERTINENCE_NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET PERTINENCE_NIVEAU = diff_row.PERTINENCE_NIVEAU WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.GROUPE_TYPE_FORMATION SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PERTINENCE_NIVEAU = 1 AND IN_COLUMN_LIST('PERTINENCE_NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET PERTINENCE_NIVEAU = diff_row.PERTINENCE_NIVEAU WHERE ID = diff_row.id; END IF;
          UPDATE OSE.GROUPE_TYPE_FORMATION SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_GROUPE_TYPE_FORMATION;



  PROCEDURE MAJ_ETAPE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ETAPE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ETAPE.* FROM V_DIFF_ETAPE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ETAPE
            ( id, LIBELLE,NIVEAU,SPECIFIQUE_ECHANGES,STRUCTURE_ID,TYPE_FORMATION_ID,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ETAPE_ID_SEQ.NEXTVAL), diff_row.LIBELLE,diff_row.NIVEAU,diff_row.SPECIFIQUE_ECHANGES,diff_row.STRUCTURE_ID,diff_row.TYPE_FORMATION_ID,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NIVEAU = 1 AND IN_COLUMN_LIST('NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET NIVEAU = diff_row.NIVEAU WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_SPECIFIQUE_ECHANGES = 1 AND IN_COLUMN_LIST('SPECIFIQUE_ECHANGES',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET SPECIFIQUE_ECHANGES = diff_row.SPECIFIQUE_ECHANGES WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_FORMATION_ID = 1 AND IN_COLUMN_LIST('TYPE_FORMATION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET TYPE_FORMATION_ID = diff_row.TYPE_FORMATION_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ETAPE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NIVEAU = 1 AND IN_COLUMN_LIST('NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET NIVEAU = diff_row.NIVEAU WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_SPECIFIQUE_ECHANGES = 1 AND IN_COLUMN_LIST('SPECIFIQUE_ECHANGES',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET SPECIFIQUE_ECHANGES = diff_row.SPECIFIQUE_ECHANGES WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_FORMATION_ID = 1 AND IN_COLUMN_LIST('TYPE_FORMATION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET TYPE_FORMATION_ID = diff_row.TYPE_FORMATION_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ETAPE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ETAPE;



  PROCEDURE MAJ_ETABLISSEMENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ETABLISSEMENT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ETABLISSEMENT.* FROM V_DIFF_ETABLISSEMENT ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ETABLISSEMENT
            ( id, DEPARTEMENT,LIBELLE,LOCALISATION, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ETABLISSEMENT_ID_SEQ.NEXTVAL), diff_row.DEPARTEMENT,diff_row.LIBELLE,diff_row.LOCALISATION, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_DEPARTEMENT = 1 AND IN_COLUMN_LIST('DEPARTEMENT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET DEPARTEMENT = diff_row.DEPARTEMENT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALISATION = 1 AND IN_COLUMN_LIST('LOCALISATION',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET LOCALISATION = diff_row.LOCALISATION WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ETABLISSEMENT SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_DEPARTEMENT = 1 AND IN_COLUMN_LIST('DEPARTEMENT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET DEPARTEMENT = diff_row.DEPARTEMENT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALISATION = 1 AND IN_COLUMN_LIST('LOCALISATION',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET LOCALISATION = diff_row.LOCALISATION WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ETABLISSEMENT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ETABLISSEMENT;



  PROCEDURE MAJ_ELEMENT_TAUX_REGIMES(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ELEMENT_TAUX_REGIMES%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ELEMENT_TAUX_REGIMES.* FROM V_DIFF_ELEMENT_TAUX_REGIMES ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ELEMENT_TAUX_REGIMES
            ( id, ANNEE_ID,ELEMENT_PEDAGOGIQUE_ID,TAUX_FA,TAUX_FC,TAUX_FI, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ELEMENT_TAUX_REGIMES_ID_SEQ.NEXTVAL), diff_row.ANNEE_ID,diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.TAUX_FA,diff_row.TAUX_FC,diff_row.TAUX_FI, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FA = 1 AND IN_COLUMN_LIST('TAUX_FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FA = diff_row.TAUX_FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FC = 1 AND IN_COLUMN_LIST('TAUX_FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FC = diff_row.TAUX_FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FI = 1 AND IN_COLUMN_LIST('TAUX_FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FI = diff_row.TAUX_FI WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ELEMENT_TAUX_REGIMES SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FA = 1 AND IN_COLUMN_LIST('TAUX_FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FA = diff_row.TAUX_FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FC = 1 AND IN_COLUMN_LIST('TAUX_FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FC = diff_row.TAUX_FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FI = 1 AND IN_COLUMN_LIST('TAUX_FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FI = diff_row.TAUX_FI WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ELEMENT_TAUX_REGIMES SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ELEMENT_TAUX_REGIMES;



  PROCEDURE MAJ_ELEMENT_PEDAGOGIQUE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ELEMENT_PEDAGOGIQUE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ELEMENT_PEDAGOGIQUE.* FROM V_DIFF_ELEMENT_PEDAGOGIQUE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ELEMENT_PEDAGOGIQUE
            ( id, ETAPE_ID,FA,FC,FI,LIBELLE,PERIODE_ID,STRUCTURE_ID,TAUX_FA,TAUX_FC,TAUX_FI,TAUX_FOAD, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ELEMENT_PEDAGOGIQUE_ID_SEQ.NEXTVAL), diff_row.ETAPE_ID,diff_row.FA,diff_row.FC,diff_row.FI,diff_row.LIBELLE,diff_row.PERIODE_ID,diff_row.STRUCTURE_ID,diff_row.TAUX_FA,diff_row.TAUX_FC,diff_row.TAUX_FI,diff_row.TAUX_FOAD, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
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
          UPDATE OSE.ELEMENT_PEDAGOGIQUE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
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

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ELEMENT_PEDAGOGIQUE;



  PROCEDURE MAJ_ELEMENT_DISCIPLINE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ELEMENT_DISCIPLINE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ELEMENT_DISCIPLINE.* FROM V_DIFF_ELEMENT_DISCIPLINE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ELEMENT_DISCIPLINE
            ( id, DISCIPLINE_ID,ELEMENT_PEDAGOGIQUE_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ELEMENT_DISCIPLINE_ID_SEQ.NEXTVAL), diff_row.DISCIPLINE_ID,diff_row.ELEMENT_PEDAGOGIQUE_ID, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('DISCIPLINE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_DISCIPLINE SET DISCIPLINE_ID = diff_row.DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_DISCIPLINE SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ELEMENT_DISCIPLINE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('DISCIPLINE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_DISCIPLINE SET DISCIPLINE_ID = diff_row.DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_DISCIPLINE SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ELEMENT_DISCIPLINE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ELEMENT_DISCIPLINE;



  PROCEDURE MAJ_EFFECTIFS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_EFFECTIFS%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_EFFECTIFS.* FROM V_DIFF_EFFECTIFS ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.EFFECTIFS
            ( id, ANNEE_ID,ELEMENT_PEDAGOGIQUE_ID,FA,FC,FI, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,EFFECTIFS_ID_SEQ.NEXTVAL), diff_row.ANNEE_ID,diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.FA,diff_row.FC,diff_row.FI, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FA = 1 AND IN_COLUMN_LIST('FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FA = diff_row.FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FC = 1 AND IN_COLUMN_LIST('FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FC = diff_row.FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FI = 1 AND IN_COLUMN_LIST('FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FI = diff_row.FI WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.EFFECTIFS SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FA = 1 AND IN_COLUMN_LIST('FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FA = diff_row.FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FC = 1 AND IN_COLUMN_LIST('FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FC = diff_row.FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FI = 1 AND IN_COLUMN_LIST('FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FI = diff_row.FI WHERE ID = diff_row.id; END IF;
          UPDATE OSE.EFFECTIFS SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_EFFECTIFS;



  PROCEDURE MAJ_DISCIPLINE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_DISCIPLINE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_DISCIPLINE.* FROM V_DIFF_DISCIPLINE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.DISCIPLINE
            ( id, LIBELLE_COURT,LIBELLE_LONG,ORDRE, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,DISCIPLINE_ID_SEQ.NEXTVAL), diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG,diff_row.ORDRE, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DISCIPLINE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DISCIPLINE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DISCIPLINE SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.DISCIPLINE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DISCIPLINE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DISCIPLINE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DISCIPLINE SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
          UPDATE OSE.DISCIPLINE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_DISCIPLINE;



  PROCEDURE MAJ_CORPS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_CORPS%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_CORPS.* FROM V_DIFF_CORPS ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.CORPS
            ( id, LIBELLE_COURT,LIBELLE_LONG,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,CORPS_ID_SEQ.NEXTVAL), diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.CORPS SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.CORPS SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_CORPS;



  PROCEDURE MAJ_CHEMIN_PEDAGOGIQUE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_CHEMIN_PEDAGOGIQUE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_CHEMIN_PEDAGOGIQUE.* FROM V_DIFF_CHEMIN_PEDAGOGIQUE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.CHEMIN_PEDAGOGIQUE
            ( id, ELEMENT_PEDAGOGIQUE_ID,ETAPE_ID,ORDRE,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,CHEMIN_PEDAGOGIQUE_ID_SEQ.NEXTVAL), diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.ETAPE_ID,diff_row.ORDRE,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.CHEMIN_PEDAGOGIQUE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.CHEMIN_PEDAGOGIQUE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_CHEMIN_PEDAGOGIQUE;



  PROCEDURE MAJ_AFFECTATION_RECHERCHE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_AFFECTATION_RECHERCHE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_AFFECTATION_RECHERCHE.* FROM V_DIFF_AFFECTATION_RECHERCHE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.AFFECTATION_RECHERCHE
            ( id, INTERVENANT_ID,STRUCTURE_ID,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,AFFECTATION_RECHERCHE_ID_SEQ.NEXTVAL), diff_row.INTERVENANT_ID,diff_row.STRUCTURE_ID,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.AFFECTATION_RECHERCHE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.AFFECTATION_RECHERCHE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_AFFECTATION_RECHERCHE;



  PROCEDURE MAJ_ADRESSE_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ADRESSE_STRUCTURE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ADRESSE_STRUCTURE.* FROM V_DIFF_ADRESSE_STRUCTURE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ADRESSE_STRUCTURE
            ( id, CODE_POSTAL,LOCALITE,NOM_VOIE,NO_VOIE,PAYS_CODE_INSEE,PAYS_LIBELLE,PRINCIPALE,STRUCTURE_ID,TELEPHONE,VALIDITE_DEBUT,VALIDITE_FIN,VILLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ADRESSE_STRUCTURE_ID_SEQ.NEXTVAL), diff_row.CODE_POSTAL,diff_row.LOCALITE,diff_row.NOM_VOIE,diff_row.NO_VOIE,diff_row.PAYS_CODE_INSEE,diff_row.PAYS_LIBELLE,diff_row.PRINCIPALE,diff_row.STRUCTURE_ID,diff_row.TELEPHONE,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN,diff_row.VILLE, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

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
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ADRESSE_STRUCTURE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

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
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ADRESSE_STRUCTURE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ADRESSE_STRUCTURE;



  PROCEDURE MAJ_ADRESSE_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ADRESSE_INTERVENANT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ADRESSE_INTERVENANT.* FROM V_DIFF_ADRESSE_INTERVENANT ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ADRESSE_INTERVENANT
            ( id, CODE_POSTAL,INTERVENANT_ID,LOCALITE,MENTION_COMPLEMENTAIRE,NOM_VOIE,NO_VOIE,PAYS_CODE_INSEE,PAYS_LIBELLE,PRINCIPALE,TEL_DOMICILE,VALIDITE_DEBUT,VILLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ADRESSE_INTERVENANT_ID_SEQ.NEXTVAL), diff_row.CODE_POSTAL,diff_row.INTERVENANT_ID,diff_row.LOCALITE,diff_row.MENTION_COMPLEMENTAIRE,diff_row.NOM_VOIE,diff_row.NO_VOIE,diff_row.PAYS_CODE_INSEE,diff_row.PAYS_LIBELLE,diff_row.PRINCIPALE,diff_row.TEL_DOMICILE,diff_row.VALIDITE_DEBUT,diff_row.VILLE, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_CODE_POSTAL = 1 AND IN_COLUMN_LIST('CODE_POSTAL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET CODE_POSTAL = diff_row.CODE_POSTAL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALITE = 1 AND IN_COLUMN_LIST('LOCALITE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET LOCALITE = diff_row.LOCALITE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_MENTION_COMPLEMENTAIRE = 1 AND IN_COLUMN_LIST('MENTION_COMPLEMENTAIRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET MENTION_COMPLEMENTAIRE = diff_row.MENTION_COMPLEMENTAIRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_VOIE = 1 AND IN_COLUMN_LIST('NOM_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET NOM_VOIE = diff_row.NOM_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NO_VOIE = 1 AND IN_COLUMN_LIST('NO_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET NO_VOIE = diff_row.NO_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PAYS_CODE_INSEE = diff_row.PAYS_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PAYS_LIBELLE = diff_row.PAYS_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRINCIPALE = 1 AND IN_COLUMN_LIST('PRINCIPALE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PRINCIPALE = diff_row.PRINCIPALE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_DOMICILE = 1 AND IN_COLUMN_LIST('TEL_DOMICILE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET TEL_DOMICILE = diff_row.TEL_DOMICILE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ADRESSE_INTERVENANT SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_CODE_POSTAL = 1 AND IN_COLUMN_LIST('CODE_POSTAL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET CODE_POSTAL = diff_row.CODE_POSTAL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALITE = 1 AND IN_COLUMN_LIST('LOCALITE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET LOCALITE = diff_row.LOCALITE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_MENTION_COMPLEMENTAIRE = 1 AND IN_COLUMN_LIST('MENTION_COMPLEMENTAIRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET MENTION_COMPLEMENTAIRE = diff_row.MENTION_COMPLEMENTAIRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_VOIE = 1 AND IN_COLUMN_LIST('NOM_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET NOM_VOIE = diff_row.NOM_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NO_VOIE = 1 AND IN_COLUMN_LIST('NO_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET NO_VOIE = diff_row.NO_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PAYS_CODE_INSEE = diff_row.PAYS_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PAYS_LIBELLE = diff_row.PAYS_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRINCIPALE = 1 AND IN_COLUMN_LIST('PRINCIPALE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PRINCIPALE = diff_row.PRINCIPALE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_DOMICILE = 1 AND IN_COLUMN_LIST('TEL_DOMICILE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET TEL_DOMICILE = diff_row.TEL_DOMICILE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ADRESSE_INTERVENANT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ADRESSE_INTERVENANT;

  -- END OF AUTOMATIC GENERATION --
END ose_import;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_FORMULE
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_FORMULE" AS

  v_date_obs DATE;



  FUNCTION GET_DATE_OBS RETURN DATE AS
  BEGIN
    RETURN COALESCE( v_date_obs, SYSDATE );
  END;

  FUNCTION SET_DATE_OBS( DATE_OBS DATE DEFAULT NULL ) RETURN DATE IS
  BEGIN
    v_date_obs := DATE_OBS;
    RETURN v_date_obs;
  END;


  PROCEDURE DEMANDE_CALCUL( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC ) IS
  BEGIN
    MERGE INTO formule_resultat_maj frm USING dual ON (
          frm.INTERVENANT_ID                = DEMANDE_CALCUL.INTERVENANT_ID
      AND frm.ANNEE_ID                      = DEMANDE_CALCUL.ANNEE_ID
    )
    WHEN NOT MATCHED THEN INSERT ( 
      ID,
      INTERVENANT_ID, 
      ANNEE_ID
    ) VALUES (
      FORMULE_RESULTAT_MAJ_ID_SEQ.NEXTVAL,
      DEMANDE_CALCUL.INTERVENANT_ID, 
      DEMANDE_CALCUL.ANNEE_ID
    );
  END;

/*
  PROCEDURE MAJ_RESULTAT( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC )IS
  BEGIN
    DELETE FROM -- pour éliminer les anciens résultats avec des états non corrects
      formule_resultat
    WHERE
          intervenant_id = MAJ_RESULTAT.INTERVENANT_ID
      AND annee_id       = MAJ_RESULTAT.ANNEE_ID;

    FOR fr IN ( -- on ne prend que les plus grands états de volumes horaires car les plus petits sont toujours remis à jour!!
      SELECT DISTINCT type_volume_horaire_id, MAX(etat_volume_horaire_id) etat_volume_horaire_id
      FROM formule_volume_horaire
      WHERE intervenant_id = MAJ_RESULTAT.INTERVENANT_ID AND annee_id = MAJ_RESULTAT.ANNEE_ID
      GROUP BY type_volume_horaire_id
    ) LOOP
      MAJ_RESULTAT( INTERVENANT_ID, ANNEE_ID, fr.type_volume_horaire_id, fr.etat_volume_horaire_id );
    END LOOP;
  END;*/



  PROCEDURE CALCULER_TOUT IS
    a_id NUMERIC;
  BEGIN
    a_id := OSE_PARAMETRE.GET_ANNEE;
    FOR mp IN (
      SELECT DISTINCT
        intervenant_id 
      FROM 
        service s 
      WHERE
        1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction, ose_formule.get_date_obs )
        AND s.annee_id = a_id
        
      UNION
      
      SELECT DISTINCT
        intervenant_id
      FROM
        service_referentiel sr
      WHERE
        1 = ose_divers.comprise_entre( sr.histo_creation, sr.histo_destruction, ose_formule.get_date_obs )
        AND sr.annee_id = a_id

    )
    LOOP
      CALCULER( mp.intervenant_id, a_id );
    END LOOP;
  END;


  PROCEDURE CALCULER_SUR_DEMANDE IS
  BEGIN
    FOR mp IN (SELECT intervenant_id, annee_id FROM formule_resultat_maj)
    LOOP
      CALCULER( mp.intervenant_id, mp.annee_id );
    END LOOP;
    DELETE FROM formule_resultat_maj;
  END;


  FUNCTION NOUVEAU_RESULTAT RETURN formule_resultat%rowtype IS
    resultat formule_resultat%rowtype;
  BEGIN
    resultat.id                       := NULL;
    resultat.intervenant_id           := NULL;
    resultat.annee_id                 := NULL;
    resultat.type_volume_horaire_id   := NULL;
    resultat.etat_volume_horaire_id   := NULL;
    resultat.service_du               := 0;
    resultat.enseignements            := 0;
    resultat.service                  := 0;
    resultat.referentiel              := 0;
    resultat.service_assure           := 0;
    resultat.heures_solde             := 0;
    resultat.heures_compl_fi          := 0;
    resultat.heures_compl_fa          := 0;
    resultat.heures_compl_fc          := 0;
    resultat.heures_compl_referentiel := 0;
    resultat.heures_compl_total       := 0;
    resultat.sous_service             := 0;
    resultat.a_payer                  := 0;
    RETURN resultat;
  END;


  FUNCTION ENREGISTRER_RESULTAT( fr formule_resultat%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
  BEGIN
    MERGE INTO formule_resultat tfr USING dual ON (
    
          tfr.intervenant_id         = fr.intervenant_id
      AND tfr.annee_id               = fr.annee_id
      AND tfr.type_volume_horaire_id = fr.type_volume_horaire_id
      AND tfr.etat_volume_horaire_id = fr.etat_volume_horaire_id
      
    ) WHEN MATCHED THEN UPDATE SET
    
      service_du                     = fr.service_du,
      enseignements                  = fr.enseignements,
      service                        = fr.service,
      referentiel                    = fr.referentiel,
      service_assure                 = fr.service_assure,
      heures_solde                   = fr.heures_solde,
      heures_compl_fi                = fr.heures_compl_fi,
      heures_compl_fa                = fr.heures_compl_fa,
      heures_compl_fc                = fr.heures_compl_fc,
      heures_compl_referentiel       = fr.heures_compl_referentiel,
      heures_compl_total             = fr.heures_compl_total,
      sous_service                   = fr.sous_service,
      a_payer                        = fr.a_payer,
      to_delete                      = 0
      
    WHEN NOT MATCHED THEN INSERT (
    
      ID,
      INTERVENANT_ID,
      ANNEE_ID,
      TYPE_VOLUME_HORAIRE_ID,
      ETAT_VOLUME_HORAIRE_ID,
      SERVICE_DU,
      SERVICE,
      ENSEIGNEMENTS,
      REFERENTIEL,
      SERVICE_ASSURE,
      HEURES_SOLDE,
      HEURES_COMPL_FI,
      HEURES_COMPL_FA,
      HEURES_COMPL_FC,
      HEURES_COMPL_REFERENTIEL,
      HEURES_COMPL_TOTAL,
      SOUS_SERVICE,
      A_PAYER,
      TO_DELETE
      
    ) VALUES (
    
      FORMULE_RESULTAT_ID_SEQ.NEXTVAL,
      fr.intervenant_id,
      fr.annee_id,
      fr.type_volume_horaire_id,
      fr.etat_volume_horaire_id,
      fr.service_du,
      fr.service,
      fr.enseignements,
      fr.referentiel,
      fr.service_assure,
      fr.heures_solde,
      fr.heures_compl_fi,
      fr.heures_compl_fa,
      fr.heures_compl_fc,
      fr.heures_compl_referentiel,
      fr.heures_compl_total,
      fr.sous_service,
      fr.a_payer,
      0
      
    );
    
    SELECT id INTO id FROM formule_resultat tfr WHERE
          tfr.intervenant_id         = fr.intervenant_id
      AND tfr.annee_id               = fr.annee_id
      AND tfr.type_volume_horaire_id = fr.type_volume_horaire_id
      AND tfr.etat_volume_horaire_id = fr.etat_volume_horaire_id;
    RETURN id;
  END;



  FUNCTION NOUVEAU_RESULTAT_SERVICE RETURN formule_resultat_service%rowtype IS
    fs formule_resultat_service%rowtype;
  BEGIN
    fs.id                  := NULL;
    fs.formule_resultat_id := NULL;
    fs.service_id          := NULL;
    fs.service_assure      := 0;
    fs.heures_service      := 0;
    fs.heures_compl_fi     := 0;
    fs.heures_compl_fa     := 0;
    fs.heures_compl_fc     := 0;
    RETURN fs;
  END;
  


  FUNCTION ENREGISTRER_RESULTAT_SERVICE( fs formule_resultat_service%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
  BEGIN
    MERGE INTO formule_resultat_service tfs USING dual ON (
    
          tfs.formule_resultat_id    = fs.formule_resultat_id
      AND tfs.service_id             = fs.service_id

    ) WHEN MATCHED THEN UPDATE SET
    
      service_assure                 = fs.service_assure,
      heures_service                 = fs.heures_service,
      heures_compl_fi                = fs.heures_compl_fi,
      heures_compl_fa                = fs.heures_compl_fa,
      heures_compl_fc                = fs.heures_compl_fc,
      TO_DELETE                      = 0
      
    WHEN NOT MATCHED THEN INSERT (
    
      ID,
      FORMULE_RESULTAT_ID,
      SERVICE_ID,
      SERVICE_ASSURE,
      HEURES_SERVICE,
      HEURES_COMPL_FI,
      HEURES_COMPL_FA,
      HEURES_COMPL_FC,
      TO_DELETE

    ) VALUES (

      FORMULE_RESULTAT_SERVIC_ID_SEQ.NEXTVAL,
      fs.formule_resultat_id,
      fs.service_id,
      fs.service_assure,
      fs.heures_service,
      fs.heures_compl_fi,
      fs.heures_compl_fa,
      fs.heures_compl_fc,
      0
      
    );
    
    SELECT id INTO id FROM formule_resultat_service tfs WHERE
          tfs.formule_resultat_id    = fs.formule_resultat_id
      AND tfs.service_id             = fs.service_id;
    RETURN id;
  END;
  
  
  
  FUNCTION NOUVEAU_RESULTAT_REF RETURN formule_resultat_referentiel%rowtype IS
    fr formule_resultat_referentiel%rowtype;
  BEGIN
    fr.id                       := NULL;
    fr.formule_resultat_id      := NULL;
    fr.service_referentiel_id   := NULL;
    fr.service_assure           := 0;
    fr.heures_service           := 0;
    fr.heures_compl_referentiel := 0;
    RETURN fr;
  END;
  
  
  
  FUNCTION ENREGISTRER_RESULTAT_REF( fr formule_resultat_referentiel%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
  BEGIN
    MERGE INTO formule_resultat_referentiel tfr USING dual ON (

          tfr.formule_resultat_id    = fr.formule_resultat_id
      AND tfr.service_referentiel_id = fr.service_referentiel_id

    ) WHEN MATCHED THEN UPDATE SET

      service_assure                 = fr.service_assure,
      heures_service                 = fr.heures_service,
      heures_compl_referentiel       = fr.heures_compl_referentiel,
      TO_DELETE                      = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      FORMULE_RESULTAT_ID,
      SERVICE_REFERENTIEL_ID,
      SERVICE_ASSURE,
      HEURES_SERVICE,
      HEURES_COMPL_REFERENTIEL,
      TO_DELETE

    ) VALUES (

      FORMULE_RESULTAT_REFERE_ID_SEQ.NEXTVAL,
      fr.formule_resultat_id,
      fr.service_referentiel_id,
      fr.service_assure,
      fr.heures_service,
      fr.heures_compl_referentiel,
      0

    );

    SELECT id INTO id FROM formule_resultat_referentiel tfr WHERE
          tfr.formule_resultat_id    = fr.formule_resultat_id
      AND tfr.service_referentiel_id = fr.service_referentiel_id;
      
    RETURN id;
  END;
  
  

  PROCEDURE POPULATE_INTERVENANT( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC, d_intervenant OUT t_intervenant ) IS
  BEGIN
    SELECT
      structure_id,
      heures_service_statutaire
    INTO
      d_intervenant.structure_id,
      d_intervenant.heures_service_statutaire
    FROM
      v_formule_intervenant fi
    WHERE
      fi.id = POPULATE_INTERVENANT.INTERVENANT_ID;

    SELECT
      NVL( SUM(heures), 0)
    INTO
      d_intervenant.heures_service_modifie
    FROM
      v_formule_service_modifie fsm
    WHERE
      fsm.intervenant_id = POPULATE_INTERVENANT.INTERVENANT_ID
      AND fsm.annee_id   = POPULATE_INTERVENANT.ANNEE_ID;
    
  END;
  

  PROCEDURE POPULATE_REFERENTIEL( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC, d_referentiel OUT t_lst_referentiel ) IS
    i PLS_INTEGER;
  BEGIN
    d_referentiel.delete;

    FOR d IN (
      SELECT
        fr.id,
        fr.structure_id,
        fr.heures
      FROM
        v_formule_referentiel fr
      WHERE
        fr.intervenant_id = POPULATE_REFERENTIEL.INTERVENANT_ID
        AND fr.annee_id   = POPULATE_REFERENTIEL.ANNEE_ID
        AND fr.heures > 0
    ) LOOP
      d_referentiel( d.id ).id           := d.id;
      d_referentiel( d.id ).structure_id := d.structure_id;
      d_referentiel( d.id ).heures       := d.heures;
    END LOOP;

/*
    i := liste_referentiel.FIRST;
    LOOP EXIT WHEN i IS NULL;
--      ose_test.echo('id = ' || i );
      ose_test.echo('structure_id = ' || liste_referentiel( i ).structure_id );
      ose_test.echo('heures = ' || liste_referentiel( i ).heures );
  
      i := liste_referentiel.NEXT(i);
    END LOOP;*/
    
  END;


  PROCEDURE POPULATE_SERVICE( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC, d_service OUT t_lst_service ) IS
  BEGIN
    d_service.delete;

    FOR d IN (
      SELECT
        id,
        taux_fi,
        taux_fa,
        taux_fc,
        structure_aff_id,
        structure_ens_id,
        ponderation_service_du,
        ponderation_service_compl
      FROM
        v_formule_service fs
      WHERE
        fs.intervenant_id = POPULATE_SERVICE.INTERVENANT_ID
        AND fs.annee_id   = POPULATE_SERVICE.ANNEE_ID
    ) LOOP
      d_service( d.id ).id                        := d.id;
      d_service( d.id ).taux_fi                   := d.taux_fi;
      d_service( d.id ).taux_fa                   := d.taux_fa;
      d_service( d.id ).taux_fc                   := d.taux_fc;
      d_service( d.id ).ponderation_service_du    := d.ponderation_service_du;
      d_service( d.id ).ponderation_service_compl := d.ponderation_service_compl;
      d_service( d.id ).structure_aff_id          := d.structure_aff_id;
      d_service( d.id ).structure_ens_id          := d.structure_ens_id;
    END LOOP;
  END;


  PROCEDURE POPULATE_VOLUME_HORAIRE( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC, d_volume_horaire OUT t_lst_volume_horaire ) IS
  BEGIN
    d_volume_horaire.delete;

    FOR d IN (
      SELECT
        id,
        service_id,
        heures,
        taux_service_du,
        taux_service_compl,
        fvh.type_volume_horaire_id,
        fvh.etat_volume_horaire_id,
        fvh.etat_volume_horaire_ordre
      FROM
        v_formule_volume_horaire fvh
      WHERE
        fvh.intervenant_id                = POPULATE_VOLUME_HORAIRE.INTERVENANT_ID
        AND fvh.annee_id                  = POPULATE_VOLUME_HORAIRE.ANNEE_ID
    ) LOOP
      d_volume_horaire( d.id ).id                        := d.id;
      d_volume_horaire( d.id ).service_id                := d.service_id;
      d_volume_horaire( d.id ).heures                    := d.heures;
      d_volume_horaire( d.id ).taux_service_du           := d.taux_service_du;
      d_volume_horaire( d.id ).taux_service_compl        := d.taux_service_compl;
      d_volume_horaire( d.id ).type_volume_horaire_id    := d.type_volume_horaire_id;
      d_volume_horaire( d.id ).etat_volume_horaire_id    := d.etat_volume_horaire_id;
      d_volume_horaire( d.id ).etat_volume_horaire_ordre := d.etat_volume_horaire_ordre;
    END LOOP;
  END;


  PROCEDURE POPULATE_TYPE_ETAT_VH( d_volume_horaire t_lst_volume_horaire, d_type_etat_vh OUT t_lst_type_etat_vh ) IS
    id  PLS_INTEGER;
    id2 PLS_INTEGER;
    found BOOLEAN;
  BEGIN
    d_type_etat_vh.delete;

    id := d_volume_horaire.FIRST;
    LOOP EXIT WHEN id IS NULL;
      found := FALSE;
      
      id2 := d_type_etat_vh.FIRST;
      LOOP EXIT WHEN id2 IS NULL;
        IF
          d_type_etat_vh(id2).type_volume_horaire_id = d_volume_horaire(id).type_volume_horaire_id
          AND d_type_etat_vh(id2).etat_volume_horaire_id = d_volume_horaire(id).etat_volume_horaire_id
        THEN
          found := TRUE;
          EXIT;
        END IF;
        id2 := d_type_etat_vh.NEXT(id2);
      END LOOP;
      
      IF NOT found THEN
        d_type_etat_vh(id).type_volume_horaire_id := d_volume_horaire(id).type_volume_horaire_id;
        d_type_etat_vh(id).etat_volume_horaire_id := d_volume_horaire(id).etat_volume_horaire_id;
      END IF;
      id := d_volume_horaire.NEXT(id);
    END LOOP;
  END;


  PROCEDURE CALCULER( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC ) IS
    id PLS_INTEGER;
    found BOOLEAN;
    function_name VARCHAR2(30);
    package_name VARCHAR2(30);
  BEGIN
    package_name  := OSE_PARAMETRE.GET_FORMULE_PACKAGE_NAME;
    function_name := OSE_PARAMETRE.GET_FORMULE_FUNCTION_NAME;

    POPULATE_INTERVENANT    ( INTERVENANT_ID, ANNEE_ID, d_intervenant );
    POPULATE_REFERENTIEL    ( INTERVENANT_ID, ANNEE_ID, d_referentiel );
    POPULATE_SERVICE        ( INTERVENANT_ID, ANNEE_ID, d_service );
    POPULATE_VOLUME_HORAIRE ( INTERVENANT_ID, ANNEE_ID, d_volume_horaire );
    POPULATE_TYPE_ETAT_VH   ( d_volume_horaire, d_type_etat_vh );

    -- détection de suppression des lignes de résultat obsolètes
    UPDATE formule_resultat SET TO_DELETE = 1 WHERE intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID;
    UPDATE FORMULE_RESULTAT_REFERENTIEL SET TO_DELETE = 1 WHERE formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID);
    UPDATE FORMULE_RESULTAT_SERVICE     SET TO_DELETE = 1 WHERE formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID);

    -- lancement du calcul sur les nouvelles lignes ou sur les lignes existantes
    id := d_type_etat_vh.FIRST;
    LOOP EXIT WHEN id IS NULL;
      -- délégation du calcul à la formule choisie (à des fins de paramétrage)
      EXECUTE IMMEDIATE 
        'BEGIN ' || package_name || '.' || function_name || '( :1, :2, :3, :4 ); END;'
      USING
        INTERVENANT_ID, ANNEE_ID, d_type_etat_vh(id).type_volume_horaire_id, d_type_etat_vh(id).etat_volume_horaire_id;

      id := d_type_etat_vh.NEXT(id);
    END LOOP;

    DELETE FROM FORMULE_RESULTAT_REFERENTIEL WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID);
    DELETE FROM FORMULE_RESULTAT_SERVICE WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID);
    DELETE FROM formule_resultat WHERE TO_DELETE = 1 AND intervenant_id = CALCULER.INTERVENANT_ID AND annee_id = CALCULER.ANNEE_ID;

  END;

END OSE_FORMULE;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_DIVERS
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_DIVERS" AS

FUNCTION INTERVENANT_HAS_PRIVILEGE( intervenant_id NUMERIC, privilege_name VARCHAR2 ) RETURN NUMERIC IS
  statut statut_intervenant%rowtype;
  itype  type_intervenant%rowtype;
  res NUMERIC;
BEGIN
  res := 1;
  SELECT si.* INTO statut FROM statut_intervenant si JOIN intervenant i ON i.statut_id = si.id WHERE i.id = intervenant_id;
  SELECT ti.* INTO itype  FROM type_intervenant ti WHERE ti.id = statut.type_intervenant_id;
  IF 'saisie_service' = privilege_name THEN
    res := statut.peut_saisir_service;
  ELSIF 'saisie_service_exterieur' = privilege_name THEN
    --IF INTERVENANT_HAS_PRIVILEGE( intervenant_id, 'saisie_service' ) = 0 OR itype.code = 'E' THEN -- cascade
    IF itype.code = 'E' THEN
      res := 0;
    END IF;
  ELSIF 'saisie_service_referentiel' = privilege_name THEN
    IF itype.code = 'E' THEN
      res := 0;
    END IF;
  ELSIF 'saisie_service_referentiel_autre_structure' = privilege_name THEN
    res := 1;
  ELSIF 'saisie_motif_non_paiement' = privilege_name THEN
    res := statut.peut_saisir_motif_non_paiement;
  ELSE
    raise_application_error(-20101, 'Le privilège "' || privilege_name || '" n''existe pas.');
  END IF;
  RETURN res;
END;

FUNCTION implode(i_query VARCHAR2, i_seperator VARCHAR2 DEFAULT ',') RETURN VARCHAR2 AS
  l_return CLOB:='';
  l_temp CLOB;
  TYPE r_cursor is REF CURSOR;
  rc r_cursor;
BEGIN
  OPEN rc FOR i_query;
  LOOP
    FETCH rc INTO L_TEMP;
    EXIT WHEN RC%NOTFOUND;
    l_return:=l_return||L_TEMP||i_seperator;
  END LOOP;
  RETURN RTRIM(l_return,i_seperator);
END;

FUNCTION intervenant_est_permanent( INTERVENANT_ID NUMERIC ) RETURN NUMERIC AS
  resultat NUMERIC;
BEGIN
  SELECT COUNT(*) INTO resultat FROM intervenant_permanent WHERE id = INTERVENANT_ID;
  RETURN resultat;
END;

FUNCTION intervenant_est_non_autorise( INTERVENANT_ID NUMERIC ) RETURN NUMERIC AS
  resultat NUMERIC;
BEGIN
  SELECT COUNT(*) INTO resultat FROM intervenant i JOIN statut_intervenant si ON si.id = i.statut_id AND si.non_autorise = 1 WHERE i.id = INTERVENANT_ID;
  RETURN resultat;
END;

FUNCTION intervenant_peut_saisir_serv( INTERVENANT_ID NUMERIC ) RETURN NUMERIC AS
  resultat NUMERIC;
BEGIN
  SELECT COUNT(*) INTO resultat FROM intervenant i JOIN statut_intervenant si ON si.id = i.statut_id AND si.peut_saisir_service = 1 WHERE i.id = INTERVENANT_ID;
  RETURN resultat;
END;

FUNCTION STRUCTURE_DANS_STRUCTURE( structure_testee NUMERIC, structure_cible NUMERIC ) RETURN NUMERIC AS
  RESULTAT NUMERIC;
BEGIN
  IF structure_testee = structure_cible THEN RETURN 1; END IF;
  
  select count(*) into resultat
  from structure
  WHERE structure.id = structure_testee
  start with parente_id = structure_cible
  connect by parente_id = prior id;

  RETURN RESULTAT;
END;

FUNCTION STR_REDUCE( str CLOB ) RETURN CLOB IS
BEGIN
  RETURN NLS_LOWER(str, 'NLS_SORT = BINARY_AI');
END;

FUNCTION LIKED( haystack CLOB, needle CLOB ) RETURN NUMERIC IS
BEGIN
  RETURN CASE WHEN STR_REDUCE(haystack) LIKE STR_REDUCE(needle) THEN 1 ELSE 0 END;
END;

FUNCTION COMPRISE_ENTRE( date_debut DATE, date_fin DATE DEFAULT NULL, date_obs DATE DEFAULT SYSDATE ) RETURN NUMERIC IS
  res NUMERIC;
BEGIN
--  res := 1;
  res := CASE WHEN to_char(date_obs,'YYYY-MM-DD') >= to_char(date_debut,'YYYY-MM-DD') THEN 1 ELSE 0 END;
  IF 1 = res AND date_fin IS NOT NULL THEN
    res := CASE WHEN to_char(date_obs,'YYYY-MM-DD') < to_char(date_fin,'YYYY-MM-DD') THEN 1 ELSE 0 END;
  END IF;
  RETURN res;
END;

PROCEDURE DO_NOTHING IS
BEGIN
  RETURN;
END;

FUNCTION VOLUME_HORAIRE_VALIDE( volume_horaire_id NUMERIC ) RETURN NUMERIC IS
  res NUMERIC;
BEGIN
  SELECT count(*) INTO res FROM
    validation v
    JOIN validation_vol_horaire vvh ON vvh.validation_id = v.id
  WHERE
    1 = ose_divers.comprise_entre( v.histo_creation, v.histo_destruction );
  RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
END;


PROCEDURE CALCUL_TAUX( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, r_fi OUT FLOAT, r_fc OUT FLOAT, r_fa OUT FLOAT, arrondi NUMERIC DEFAULT 15 ) IS
  nt FLOAT;
  bi FLOAT;
  bc FLOAT;
  ba FLOAT;
  reste FLOAT;
BEGIN
  bi := eff_fi * fi;
  bc := eff_fc * fc;
  ba := eff_fa * fa;
  nt := bi + bc + ba;

  IF nt = 0 THEN -- au cas ou, alors on ne prend plus en compte les effectifs!!
    bi := fi;
    bc := fc;
    ba := fa;
    nt := bi + bc + ba;
  END IF;
  
  IF nt = 0 THEN -- toujours au cas ou...
    bi := 1;
    bc := 0;
    ba := 0;
    nt := bi + bc + ba;
  END IF;

  -- Calcul
  r_fi := bi / nt;
  r_fc := bc / nt;
  r_fa := ba / nt;

  -- Arrondis
  r_fi := ROUND( r_fi, arrondi );
  r_fc := ROUND( r_fc, arrondi );
  r_fa := ROUND( r_fa, arrondi );

  -- détermination du reste
  reste := 1 - r_fi - r_fc - r_fa;

  -- répartition éventuelle du reste
  IF reste <> 0 THEN
    IF r_fi > 0 THEN r_fi := r_fi + reste;
    ELSIF r_fc > 0 THEN r_fc := r_fc + reste;
    ELSE r_fa := r_fa + reste; END IF;
  END IF;

END;


FUNCTION CALCUL_TAUX_FI( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT IS
  ri FLOAT;
  rc FLOAT;
  ra FLOAT;
BEGIN
  CALCUL_TAUX( eff_fi, eff_fc, eff_fa, fi, fc, fa, ri, rc, ra, arrondi );
  RETURN ri;
END;
  
FUNCTION CALCUL_TAUX_FC( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT IS
  ri FLOAT;
  rc FLOAT;
  ra FLOAT;
BEGIN
  CALCUL_TAUX( eff_fi, eff_fc, eff_fa, fi, fc, fa, ri, rc, ra, arrondi );
  RETURN rc;
END;
  
FUNCTION CALCUL_TAUX_FA( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT IS
  ri FLOAT;
  rc FLOAT;
  ra FLOAT;
BEGIN
  CALCUL_TAUX( eff_fi, eff_fc, eff_fa, fi, fc, fa, ri, rc, ra, arrondi );
  RETURN ra;
END;

FUNCTION STRUCTURE_UNIV_GET_ID RETURN NUMERIC IS
  res NUMERIC;
BEGIN
  SELECT id INTO res FROM structure WHERE niveau = 1 AND ROWNUM = 1;
  RETURN res;
END;

PROCEDURE SYNC_LOG( msg CLOB ) IS
BEGIN
  INSERT INTO SYNC_LOG( id, date_sync, message ) VALUES ( sync_log_id_seq.nextval, systimestamp, msg );
END;

END OSE_DIVERS;
/
---------------------------
--Modifié PROCEDURE
--UPDATEPJ
---------------------------
CREATE OR REPLACE PROCEDURE "OSE"."UPDATEPJ" 
IS
  validationId validation.id%TYPE;
  validationCreee integer;
  n integer := 1;
  cursor pj_cur is select * from piece_jointe pj where pj.validation_id is null and not exists ( select * from piece_jointe_fichier pjf where pjf.piece_jointe_id = pj.id ) order by id;
BEGIN
   FOR pj IN pj_cur
   LOOP
      -- id validation
      select validation_id_seq.nextval into validationId from dual;
      
      DBMS_OUTPUT.PUT_LINE('');
      DBMS_OUTPUT.PUT(n || '/ PJ n°' || pj.id);
      n := n+1;
      
      -- création validation
      INSERT INTO VALIDATION (
          ID,
          TYPE_VALIDATION_ID,
          INTERVENANT_ID,
          STRUCTURE_ID,
          HISTO_CREATION,
          HISTO_CREATEUR_ID,
          HISTO_MODIFICATEUR_ID
        )
      select
          validationId,
          tv.id,
          i.id,
          i.structure_id,
          pj.HISTO_CREATION,
          pj.HISTO_CREATEUR_ID,
          pj.HISTO_CREATEUR_ID
      from type_validation tv, intervenant_exterieur ie, intervenant i
      where tv.code = 'PIECE_JOINTE'
      and ie.dossier_id = pj.dossier_id
      and i.id = ie.id;
      
      -- verif validation existe
      select count(*) into validationCreee from validation where id = validationId;
      CONTINUE WHEN validationCreee = 0;
      
      -- lien pj -> validation
      update piece_jointe set validation_id = validationId where id = pj.id;
      
      DBMS_OUTPUT.PUT_LINE(' : validation #' || validationId);
   END LOOP;
   
--EXCEPTION
--WHEN OTHERS THEN
 --  raise_application_error(-20001,'An error was encountered - '||SQLCODE||' -ERROR- '||SQLERRM);
END;
/

---------------------------
--Nouveau TRIGGER
--F_VOLUME_HORAIRE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VOLUME_HORAIRE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Modifié TRIGGER
--F_VOLUME_HORAIRE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VOLUME_HORAIRE"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id,
      s.annee_id
    FROM
      service s
    WHERE
      1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
      AND (s.id = :NEW.service_id OR s.id = :OLD.service_id)
  
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );
  ose_divers.sync_log('f_volume_horaire VH.ID=' || NVL(:NEW.id,:OLD.id) || ', heures=' || :NEW.heures || ', histo=' || :NEW.histo_destruction );
  END LOOP;
END;
/
---------------------------
--Nouveau TRIGGER
--F_VALIDATION_VOL_HORAIRE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION_VOL_HORAIRE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION_VOL_HORAIRE"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Modifié TRIGGER
--F_VALIDATION_VOL_HORAIRE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION_VOL_HORAIRE"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION_VOL_HORAIRE"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id,
      s.annee_id
    FROM
      volume_horaire vh
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
    WHERE
      1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      AND (vh.id = :NEW.volume_horaire_id OR vh.id = :OLD.volume_horaire_id)
  
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );
  
  END LOOP;
END;
/
---------------------------
--Nouveau TRIGGER
--F_VALIDATION_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION_S"
  AFTER UPDATE ON "OSE"."VALIDATION"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;

/
---------------------------
--Modifié TRIGGER
--F_VALIDATION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION"
  AFTER UPDATE ON "OSE"."VALIDATION"
  REFERENCING FOR EACH ROW
  BEGIN

  FOR p IN ( -- validations de volume horaire

    SELECT DISTINCT
      s.intervenant_id,
      s.annee_id
    FROM
      validation_vol_horaire vvh
      JOIN volume_horaire vh ON vh.id = vvh.volume_horaire_id AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
    WHERE
      (vvh.validation_id = :OLD.ID OR vvh.validation_id = :NEW.id)

  ) LOOP

    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );

  END LOOP;

  FOR p IN ( -- validations de contrat

    SELECT DISTINCT
      s.intervenant_id,
      s.annee_id
    FROM
      contrat c
      JOIN volume_horaire vh ON vh.contrat_id = c.id AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
    WHERE
      (c.validation_id = :OLD.ID OR c.validation_id = :NEW.id)

  ) LOOP

    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );

  END LOOP;

END;
/
---------------------------
--Nouveau TRIGGER
--F_TYPE_INTERVENTION_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_TYPE_INTERVENTION_S"
  AFTER UPDATE ON "OSE"."TYPE_INTERVENTION"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Modifié TRIGGER
--F_TYPE_INTERVENTION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_TYPE_INTERVENTION"
  AFTER UPDATE ON "OSE"."TYPE_INTERVENTION"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id,
      s.annee_id
    FROM
      volume_horaire vh
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
    WHERE
      1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      AND (vh.type_intervention_id = :NEW.id OR vh.type_intervention_id = :OLD.id)
  
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );
  
  END LOOP;
END;
/
---------------------------
--Nouveau TRIGGER
--F_STATUT_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_STATUT_INTERVENANT_S"
  AFTER UPDATE ON "OSE"."STATUT_INTERVENANT"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Nouveau TRIGGER
--F_STATUT_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_STATUT_INTERVENANT"
  AFTER UPDATE ON "OSE"."STATUT_INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (
  
    SELECT DISTINCT
      fr.intervenant_id,
      fr.annee_id
    FROM
      intervenant i
      JOIN formule_resultat fr ON fr.intervenant_id = i.id
    WHERE
      (i.statut_id = :NEW.id OR i.statut_id = :OLD.id)
      AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
  
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );
  
  END LOOP;
END;
/
---------------------------
--Nouveau TRIGGER
--F_SERVICE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_SERVICE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE"
  BEGIN
  ose_divers.sync_log('f_service_s' );
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Nouveau TRIGGER
--F_SERVICE_REFERENTIEL_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_SERVICE_REFERENTIEL_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE_REFERENTIEL"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Modifié TRIGGER
--F_SERVICE_REFERENTIEL
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_SERVICE_REFERENTIEL"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE_REFERENTIEL"
  REFERENCING FOR EACH ROW
  BEGIN

  IF DELETING OR UPDATING THEN
    OSE_FORMULE.DEMANDE_CALCUL( :OLD.intervenant_id, :OLD.annee_id );
  END IF;
  IF INSERTING OR UPDATING THEN
    OSE_FORMULE.DEMANDE_CALCUL( :NEW.intervenant_id, :NEW.annee_id );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--F_SERVICE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_SERVICE"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE"
  REFERENCING FOR EACH ROW
  BEGIN

  IF DELETING OR UPDATING THEN
    OSE_FORMULE.DEMANDE_CALCUL( :OLD.intervenant_id, :OLD.annee_id );
  END IF;
  IF INSERTING OR UPDATING THEN
    OSE_FORMULE.DEMANDE_CALCUL( :NEW.intervenant_id, :NEW.annee_id );
  END IF;
  ose_divers.sync_log('f_service S.ID=' || NVL(:NEW.id,:OLD.id) );
END;
/
---------------------------
--Nouveau TRIGGER
--F_MOTIF_MODIFICATION_SERVICE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_MOTIF_MODIFICATION_SERVICE_S"
  AFTER DELETE OR UPDATE ON "OSE"."MOTIF_MODIFICATION_SERVICE"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Modifié TRIGGER
--F_MOTIF_MODIFICATION_SERVICE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_MOTIF_MODIFICATION_SERVICE"
  AFTER DELETE OR UPDATE ON "OSE"."MOTIF_MODIFICATION_SERVICE"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (
  
    SELECT DISTINCT
      intervenant_id, 
      annee_id
    FROM
      modification_service_du msd
    WHERE
      1 = OSE_DIVERS.COMPRISE_ENTRE( msd.histo_creation, msd.histo_destruction )
      AND (msd.motif_id = :NEW.id OR msd.motif_id = :OLD.id)
      
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );
  
  END LOOP;

END;
/
---------------------------
--Nouveau TRIGGER
--F_MODULATEUR_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_MODULATEUR_S"
  AFTER DELETE OR UPDATE ON "OSE"."MODULATEUR"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Modifié TRIGGER
--F_MODULATEUR
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_MODULATEUR"
  AFTER DELETE OR UPDATE ON "OSE"."MODULATEUR"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (

    SELECT DISTINCT
      s.annee_id,
      s.intervenant_id
    FROM
      service s
      JOIN element_modulateur em ON 
        em.element_id   = s.element_pedagogique_id 
        AND em.annee_id = s.annee_id 
        AND 1 = ose_divers.comprise_entre( em.histo_creation, em.histo_destruction )
    WHERE
      1 = OSE_DIVERS.COMPRISE_ENTRE( s.histo_creation, s.histo_destruction )
      AND (em.modulateur_id = :OLD.id OR em.modulateur_id = :NEW.id)

  ) LOOP

    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );

  END LOOP;
END;
/
---------------------------
--Nouveau TRIGGER
--F_MODIF_SERVICE_DU_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_MODIF_SERVICE_DU_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."MODIFICATION_SERVICE_DU"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Modifié TRIGGER
--F_MODIF_SERVICE_DU
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_MODIF_SERVICE_DU"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."MODIFICATION_SERVICE_DU"
  REFERENCING FOR EACH ROW
  BEGIN

  IF DELETING OR UPDATING THEN
    OSE_FORMULE.DEMANDE_CALCUL( :OLD.intervenant_id, :OLD.annee_id );
  END IF;
  IF INSERTING OR UPDATING THEN
    OSE_FORMULE.DEMANDE_CALCUL( :NEW.intervenant_id, :NEW.annee_id );
  END IF;

END;
/
---------------------------
--Nouveau TRIGGER
--F_INTERVENANT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_INTERVENANT_S"
  AFTER UPDATE ON "OSE"."INTERVENANT"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Modifié TRIGGER
--F_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_INTERVENANT"
  AFTER UPDATE ON "OSE"."INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN

  FOR p IN (
      
    SELECT DISTINCT
      fr.intervenant_id,
      fr.annee_id
    FROM
      formule_resultat fr
    WHERE
      fr.intervenant_id = :NEW.id OR fr.intervenant_id = :OLD.id
  
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );

  END LOOP;
  
END;
/
---------------------------
--Nouveau TRIGGER
--F_ELEMENT_PEDAGOGIQUE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_ELEMENT_PEDAGOGIQUE_S"
  AFTER DELETE OR UPDATE ON "OSE"."ELEMENT_PEDAGOGIQUE"
  BEGIN OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Modifié TRIGGER
--F_ELEMENT_PEDAGOGIQUE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_ELEMENT_PEDAGOGIQUE"
  AFTER DELETE OR UPDATE ON "OSE"."ELEMENT_PEDAGOGIQUE"
  REFERENCING FOR EACH ROW
  BEGIN FOR p IN
    ( SELECT DISTINCT s.intervenant_id,
      s.annee_id
    FROM service s
    WHERE (s.element_pedagogique_id = :NEW.id
    OR s.element_pedagogique_id     = :OLD.id)
    AND 1                           = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
    ) LOOP OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );
END LOOP;
END;
/
---------------------------
--Nouveau TRIGGER
--F_ELEMENT_MODULATEUR_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_ELEMENT_MODULATEUR_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."ELEMENT_MODULATEUR"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Modifié TRIGGER
--F_ELEMENT_MODULATEUR
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_ELEMENT_MODULATEUR"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."ELEMENT_MODULATEUR"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id,
      s.annee_id
    FROM
      service s
    WHERE
      1 = OSE_DIVERS.COMPRISE_ENTRE( s.histo_creation, s.histo_destruction )
      AND (s.element_pedagogique_id = :OLD.element_id OR s.element_pedagogique_id = :NEW.element_id)
      
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );
    
  END LOOP;

END;
/
---------------------------
--Nouveau TRIGGER
--F_CONTRAT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_CONTRAT_S"
  AFTER DELETE OR UPDATE ON "OSE"."CONTRAT"
  BEGIN
  OSE_FORMULE.CALCULER_SUR_DEMANDE;
END;
/
---------------------------
--Modifié TRIGGER
--F_CONTRAT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_CONTRAT"
  AFTER DELETE OR UPDATE ON "OSE"."CONTRAT"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (

    SELECT DISTINCT
      s.annee_id,
      s.intervenant_id
    FROM
      volume_horaire vh
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
    WHERE
      1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      AND (vh.contrat_id = :OLD.id OR vh.contrat_id = :NEW.id)

  ) LOOP

    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id, p.annee_id );

  END LOOP;

END;

/
begin

  ose_formule.calculer_tout;
end;

/
BEGIN
DBMS_SCHEDULER.set_attribute( name => '"OSE"."OSE_FORMULE_REFRESH"', attribute => 'job_action', value => 'OSE.OSE_FORMULE.CALCULER_TOUT');

END; 
/

-- ********************************************************************* --
-- *          à faire APRÈS avoir mis à jour le code source            * --
-- ********************************************************************* --


BEGIN DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"'); END; 
/