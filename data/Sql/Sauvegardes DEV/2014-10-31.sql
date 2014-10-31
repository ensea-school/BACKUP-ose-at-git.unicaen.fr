--------------------------------------------------------
--  Fichier créé - vendredi-octobre-31-2014   
--------------------------------------------------------
--------------------------------------------------------
--  DDL for Type TYPES_MODULATEURS
--------------------------------------------------------

  CREATE OR REPLACE TYPE "OSE"."TYPES_MODULATEURS" 
AS TABLE OF Numeric;

/
--------------------------------------------------------
--  DDL for Sequence ADRESSE_INTERVENANT_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."ADRESSE_INTERVENANT_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 13675 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence ADRESSE_STRUCTURE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."ADRESSE_STRUCTURE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 580 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence AFFECTATION_RECHERCHE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."AFFECTATION_RECHERCHE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 2575 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence AGREMENT_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."AGREMENT_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 80 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence ANNEE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."ANNEE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence CHEMIN_PEDAGOGIQUE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."CHEMIN_PEDAGOGIQUE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 31000 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence CIVILITE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."CIVILITE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 9 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence CONTRAT_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."CONTRAT_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 4382 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence CORPS_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."CORPS_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 3060 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence DISCIPLINE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."DISCIPLINE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 20 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence DOSSIER_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."DOSSIER_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 383 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence ELEMENT_DISCIPLINE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."ELEMENT_DISCIPLINE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 9904 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence ELEMENT_MODULATEUR_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."ELEMENT_MODULATEUR_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 3596 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence ELEMENT_PEDAGOGIQUE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."ELEMENT_PEDAGOGIQUE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 17884 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence ELEMENT_PORTEUR_PORTE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."ELEMENT_PORTEUR_PORTE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 3457 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence ELEMENT_TAUX_REGIMES_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."ELEMENT_TAUX_REGIMES_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence EMPLOI_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."EMPLOI_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence EMPLOYEUR_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."EMPLOYEUR_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence ETABLISSEMENT_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."ETABLISSEMENT_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 85441 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence ETAPE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."ETAPE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 3039 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence ETAT_VOLUME_HORAIRE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."ETAT_VOLUME_HORAIRE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 4 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence FICHIER_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."FICHIER_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 221 CACHE 20 NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence FONCTION_REFERENTIEL_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."FONCTION_REFERENTIEL_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 104 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence FORMULE_REFERENTIEL_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."FORMULE_REFERENTIEL_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 51510 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence FORMULE_SERVICE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."FORMULE_SERVICE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 10961 CACHE 20 NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence FORMULE_VOLUME_HORAIRE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."FORMULE_VOLUME_HORAIRE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 18495 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence GROUPE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."GROUPE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence GROUPE_TYPE_FORMATION_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."GROUPE_TYPE_FORMATION_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 17 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence INTERVENANT_EXTERIEUR_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."INTERVENANT_EXTERIEUR_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 16277 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence INTERVENANT_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."INTERVENANT_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 35352 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence INTERVENANT_PERMANENT_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."INTERVENANT_PERMANENT_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 2682 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence MODIFICATION_SERVICE_DU_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."MODIFICATION_SERVICE_DU_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 5107 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence MODULATEUR_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."MODULATEUR_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 3478 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence MOTIF_MODIFICATION_SERV_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."MOTIF_MODIFICATION_SERV_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 27 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence MOTIF_NON_PAIEMENT_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."MOTIF_NON_PAIEMENT_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 14 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence PARAMETRE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."PARAMETRE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 9 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence PERIODE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."PERIODE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence PERSONNEL_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."PERSONNEL_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 6155 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence PIECE_JOINTE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."PIECE_JOINTE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 424 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence PRIME_EXCELLENCE_SCIENT_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."PRIME_EXCELLENCE_SCIENT_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence REGIME_SECU_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."REGIME_SECU_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1821 CACHE 20 NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence ROLE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."ROLE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 4483 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence ROLE_UTILISATEUR_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."ROLE_UTILISATEUR_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence ROLE_UTILISATEUR_LINKER_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."ROLE_UTILISATEUR_LINKER_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence SERVICE_DU_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."SERVICE_DU_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 4949 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence SERVICE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."SERVICE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 2908 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence SERVICE_REFERENTIEL_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."SERVICE_REFERENTIEL_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 20163 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence SITUATION_FAMILIALE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."SITUATION_FAMILIALE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 21 CACHE 20 NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence SOURCE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."SOURCE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 21 CACHE 20 NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence STATUT_INTERVENANT_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."STATUT_INTERVENANT_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 24 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence STRUCTURE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."STRUCTURE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 11967 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence SYNC_LOG_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."SYNC_LOG_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 221 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence TAUX_HORAIRE_HETD_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."TAUX_HORAIRE_HETD_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence TEST_BUFFER_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."TEST_BUFFER_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 38842 CACHE 20 NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence TYPE_AGREMENT_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."TYPE_AGREMENT_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 4 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence TYPE_AGREMENT_STATUT_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."TYPE_AGREMENT_STATUT_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 138 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence TYPE_CONTRAT_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."TYPE_CONTRAT_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence TYPE_FORMATION_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."TYPE_FORMATION_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 63 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence TYPE_INTERVENANT_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."TYPE_INTERVENANT_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 19 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence TYPE_INTERVENTION_EP_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."TYPE_INTERVENTION_EP_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 688 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence TYPE_INTERVENTION_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."TYPE_INTERVENTION_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 7 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence TYPE_INTERVENTION_STRUC_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."TYPE_INTERVENTION_STRUC_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 2 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence TYPE_MODULATEUR_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."TYPE_MODULATEUR_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 3474 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence TYPE_MODULATEUR_STRUCTU_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."TYPE_MODULATEUR_STRUCTU_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 3478 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence TYPE_PIECE_JOINTE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."TYPE_PIECE_JOINTE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 26 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence TYPE_PIECE_JOINTE_STATU_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."TYPE_PIECE_JOINTE_STATU_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 37 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence TYPE_POSTE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."TYPE_POSTE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence TYPE_ROLE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."TYPE_ROLE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 7 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence TYPE_ROLE_STRUCTURE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."TYPE_ROLE_STRUCTURE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence TYPE_STRUCTURE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."TYPE_STRUCTURE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 291 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence TYPE_VALIDATION_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."TYPE_VALIDATION_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 6 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence TYPE_VOLUME_HORAIRE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."TYPE_VOLUME_HORAIRE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence UTILISATEUR_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."UTILISATEUR_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 188 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence VALIDATION_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."VALIDATION_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 7572 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence VALIDATION_VOL_HORAIRE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."VALIDATION_VOL_HORAIRE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence VOLUME_HORAIRE_ENS_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."VOLUME_HORAIRE_ENS_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 26073 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Sequence VOLUME_HORAIRE_ID_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "OSE"."VOLUME_HORAIRE_ID_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 6290 NOCACHE  NOORDER  NOCYCLE ;
--------------------------------------------------------
--  DDL for Table ADRESSE_INTERVENANT
--------------------------------------------------------

  CREATE TABLE "OSE"."ADRESSE_INTERVENANT" 
   (	"ID" NUMBER(*,0), 
	"INTERVENANT_ID" NUMBER(*,0), 
	"PRINCIPALE" NUMBER(1,0), 
	"TEL_DOMICILE" VARCHAR2(25 CHAR), 
	"MENTION_COMPLEMENTAIRE" VARCHAR2(50 CHAR), 
	"BATIMENT" VARCHAR2(60 CHAR), 
	"NO_VOIE" VARCHAR2(20 CHAR), 
	"NOM_VOIE" VARCHAR2(120 CHAR), 
	"LOCALITE" VARCHAR2(120 CHAR), 
	"CODE_POSTAL" VARCHAR2(15 CHAR), 
	"VILLE" VARCHAR2(120 CHAR), 
	"PAYS_CODE_INSEE" VARCHAR2(3 CHAR), 
	"PAYS_LIBELLE" VARCHAR2(50 CHAR), 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table ADRESSE_STRUCTURE
--------------------------------------------------------

  CREATE TABLE "OSE"."ADRESSE_STRUCTURE" 
   (	"ID" NUMBER(*,0), 
	"STRUCTURE_ID" NUMBER(*,0), 
	"PRINCIPALE" NUMBER(1,0), 
	"TELEPHONE" VARCHAR2(20 CHAR), 
	"NO_VOIE" VARCHAR2(10 CHAR), 
	"NOM_VOIE" VARCHAR2(60 CHAR), 
	"LOCALITE" VARCHAR2(26 CHAR), 
	"CODE_POSTAL" VARCHAR2(15 CHAR), 
	"VILLE" VARCHAR2(26 CHAR), 
	"PAYS_CODE_INSEE" VARCHAR2(3 CHAR), 
	"PAYS_LIBELLE" VARCHAR2(30 CHAR), 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table AFFECTATION_RECHERCHE
--------------------------------------------------------

  CREATE TABLE "OSE"."AFFECTATION_RECHERCHE" 
   (	"ID" NUMBER(*,0), 
	"INTERVENANT_ID" NUMBER(*,0), 
	"STRUCTURE_ID" NUMBER(*,0), 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;

   COMMENT ON TABLE "OSE"."AFFECTATION_RECHERCHE"  IS 'Un chercheur peut avoir plusieurs affectations de recherche';
--------------------------------------------------------
--  DDL for Table AGREMENT
--------------------------------------------------------

  CREATE TABLE "OSE"."AGREMENT" 
   (	"ID" NUMBER(*,0), 
	"TYPE_AGREMENT_ID" NUMBER(*,0), 
	"INTERVENANT_ID" NUMBER(*,0), 
	"ANNEE_ID" NUMBER(*,0), 
	"DATE_DECISION" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0), 
	"STRUCTURE_ID" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table ANNEE
--------------------------------------------------------

  CREATE TABLE "OSE"."ANNEE" 
   (	"ID" NUMBER(*,0), 
	"LIBELLE" VARCHAR2(9 CHAR), 
	"DATE_DEBUT" DATE, 
	"DATE_FIN" DATE
   ) ;
--------------------------------------------------------
--  DDL for Table CHEMIN_PEDAGOGIQUE
--------------------------------------------------------

  CREATE TABLE "OSE"."CHEMIN_PEDAGOGIQUE" 
   (	"ID" NUMBER(*,0), 
	"ELEMENT_PEDAGOGIQUE_ID" NUMBER(*,0), 
	"ETAPE_ID" NUMBER(*,0), 
	"ORDRE" NUMBER(*,0), 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table CIVILITE
--------------------------------------------------------

  CREATE TABLE "OSE"."CIVILITE" 
   (	"ID" NUMBER(*,0), 
	"LIBELLE_COURT" VARCHAR2(5 CHAR), 
	"LIBELLE_LONG" VARCHAR2(15 CHAR), 
	"SEXE" VARCHAR2(1 CHAR)
   ) ;
--------------------------------------------------------
--  DDL for Table CONTRAT
--------------------------------------------------------

  CREATE TABLE "OSE"."CONTRAT" 
   (	"ID" NUMBER(*,0), 
	"TYPE_CONTRAT_ID" NUMBER(*,0), 
	"INTERVENANT_ID" NUMBER(*,0), 
	"STRUCTURE_ID" NUMBER(*,0), 
	"VALIDATION_ID" NUMBER(*,0), 
	"NUMERO_AVENANT" NUMBER DEFAULT 0, 
	"DATE_RETOUR_SIGNE" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0), 
	"CONTRAT_ID" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table CORPS
--------------------------------------------------------

  CREATE TABLE "OSE"."CORPS" 
   (	"ID" NUMBER(*,0), 
	"LIBELLE_LONG" VARCHAR2(40 CHAR), 
	"LIBELLE_COURT" VARCHAR2(20 CHAR), 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table DISCIPLINE
--------------------------------------------------------

  CREATE TABLE "OSE"."DISCIPLINE" 
   (	"ID" NUMBER(*,0), 
	"LIBELLE_COURT" VARCHAR2(20 CHAR), 
	"LIBELLE_LONG" VARCHAR2(200 CHAR), 
	"ORDRE" NUMBER(*,0), 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table DOSSIER
--------------------------------------------------------

  CREATE TABLE "OSE"."DOSSIER" 
   (	"ID" NUMBER(*,0), 
	"NOM_USUEL" VARCHAR2(128 CHAR), 
	"NOM_PATRONYMIQUE" VARCHAR2(128 CHAR), 
	"PRENOM" VARCHAR2(128 CHAR), 
	"CIVILITE_ID" NUMBER, 
	"NUMERO_INSEE" VARCHAR2(20 CHAR), 
	"STATUT_ID" NUMBER, 
	"ADRESSE" VARCHAR2(1024 CHAR), 
	"EMAIL" VARCHAR2(128 CHAR), 
	"TELEPHONE" VARCHAR2(20 BYTE), 
	"PREMIER_RECRUTEMENT" NUMBER(1,0) DEFAULT 0, 
	"PERTE_EMPLOI" NUMBER(1,0) DEFAULT NULL, 
	"RIB" VARCHAR2(50 CHAR), 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0), 
	"NUMERO_INSEE_EST_PROVISOIRE" NUMBER(1,0) DEFAULT 0
   ) ;
--------------------------------------------------------
--  DDL for Table ELEMENT_DISCIPLINE
--------------------------------------------------------

  CREATE TABLE "OSE"."ELEMENT_DISCIPLINE" 
   (	"ID" NUMBER, 
	"ELEMENT_PEDAGOGIQUE_ID" NUMBER(*,0), 
	"DISCIPLINE_ID" NUMBER(*,0), 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table ELEMENT_MODULATEUR
--------------------------------------------------------

  CREATE TABLE "OSE"."ELEMENT_MODULATEUR" 
   (	"ID" NUMBER, 
	"ELEMENT_ID" NUMBER(*,0), 
	"MODULATEUR_ID" NUMBER(*,0), 
	"ANNEE_ID" NUMBER(*,0), 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table ELEMENT_PEDAGOGIQUE
--------------------------------------------------------

  CREATE TABLE "OSE"."ELEMENT_PEDAGOGIQUE" 
   (	"ID" NUMBER(*,0), 
	"LIBELLE" VARCHAR2(120 CHAR), 
	"ETAPE_ID" NUMBER(*,0), 
	"STRUCTURE_ID" NUMBER(*,0), 
	"PERIODE_ID" NUMBER(*,0), 
	"TAUX_FI" FLOAT(126) DEFAULT 1, 
	"TAUX_FC" FLOAT(126) DEFAULT 0, 
	"TAUX_FA" FLOAT(126) DEFAULT 0, 
	"TAUX_FOAD" FLOAT(126) DEFAULT 0, 
	"FI" NUMBER(1,0) DEFAULT 1, 
	"FC" NUMBER(1,0) DEFAULT 0, 
	"FA" NUMBER(1,0) DEFAULT 0, 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;

   COMMENT ON COLUMN "OSE"."ELEMENT_PEDAGOGIQUE"."PERIODE_ID" IS 'Un élément pédagogique ne peut avoir qu''une et une seule période.';
--------------------------------------------------------
--  DDL for Table ELEMENT_PORTEUR_PORTE
--------------------------------------------------------

  CREATE TABLE "OSE"."ELEMENT_PORTEUR_PORTE" 
   (	"ID" NUMBER(*,0), 
	"ELEMENT_PORTEUR_ID" NUMBER(*,0), 
	"ELEMENT_PORTE_ID" NUMBER(*,0), 
	"TYPE_INTERVENTION_ID" NUMBER(*,0), 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table ELEMENT_TAUX_REGIMES
--------------------------------------------------------

  CREATE TABLE "OSE"."ELEMENT_TAUX_REGIMES" 
   (	"ID" NUMBER(*,0), 
	"ANNEE_ID" NUMBER(*,0), 
	"TAUX_FI" FLOAT(126), 
	"TAUX_FC" FLOAT(126), 
	"TAUX_FA" FLOAT(126), 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR), 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table EMPLOI
--------------------------------------------------------

  CREATE TABLE "OSE"."EMPLOI" 
   (	"ID" NUMBER(*,0), 
	"INTERVENANT_ID" NUMBER(*,0), 
	"EMPLOYEUR_ID" NUMBER(*,0), 
	"DATE_DEBUT" DATE, 
	"DATE_FIN" DATE, 
	"INTERVENANT_EXTERIEUR_ID" NUMBER(*,0), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table EMPLOYEUR
--------------------------------------------------------

  CREATE TABLE "OSE"."EMPLOYEUR" 
   (	"ID" NUMBER(*,0), 
	"LIBELLE" VARCHAR2(60 CHAR), 
	"EMPLOYEUR_PERE_ID" NUMBER(*,0), 
	"SIRET" VARCHAR2(14 CHAR), 
	"CODE_NAF" VARCHAR2(4 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table ETABLISSEMENT
--------------------------------------------------------

  CREATE TABLE "OSE"."ETABLISSEMENT" 
   (	"ID" NUMBER(*,0), 
	"LIBELLE" VARCHAR2(100 CHAR), 
	"LOCALISATION" VARCHAR2(60 CHAR), 
	"DEPARTEMENT" VARCHAR2(3 CHAR), 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table ETAPE
--------------------------------------------------------

  CREATE TABLE "OSE"."ETAPE" 
   (	"ID" NUMBER(*,0), 
	"LIBELLE" VARCHAR2(120 CHAR), 
	"TYPE_FORMATION_ID" NUMBER(*,0), 
	"NIVEAU" NUMBER(*,0), 
	"SPECIFIQUE_ECHANGES" NUMBER(1,0) DEFAULT 0, 
	"STRUCTURE_ID" NUMBER(*,0), 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table ETAT_VOLUME_HORAIRE
--------------------------------------------------------

  CREATE TABLE "OSE"."ETAT_VOLUME_HORAIRE" 
   (	"ID" NUMBER(*,0), 
	"CODE" VARCHAR2(30 CHAR), 
	"LIBELLE" VARCHAR2(80 CHAR), 
	"ORDRE" NUMBER(*,0), 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table FICHIER
--------------------------------------------------------

  CREATE TABLE "OSE"."FICHIER" 
   (	"ID" NUMBER(*,0), 
	"NOM" VARCHAR2(128 CHAR), 
	"TYPE" VARCHAR2(64 CHAR), 
	"TAILLE" NUMBER(*,0), 
	"CONTENU" BLOB, 
	"DESCRIPTION" VARCHAR2(256 CHAR), 
	"VALIDATION_ID" NUMBER, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table FONCTION_REFERENTIEL
--------------------------------------------------------

  CREATE TABLE "OSE"."FONCTION_REFERENTIEL" 
   (	"ID" NUMBER(*,0), 
	"CODE" VARCHAR2(10 CHAR), 
	"LIBELLE_LONG" VARCHAR2(100 CHAR), 
	"LIBELLE_COURT" VARCHAR2(40 CHAR), 
	"PLAFOND" FLOAT(126), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table FORMULE_REFERENTIEL
--------------------------------------------------------

  CREATE TABLE "OSE"."FORMULE_REFERENTIEL" 
   (	"ID" NUMBER(*,0), 
	"INTERVENANT_ID" NUMBER(*,0), 
	"ANNEE_ID" NUMBER(*,0), 
	"SERVICE_DU" FLOAT(126) DEFAULT 0, 
	"SERVICE_DU_MODIFICATION" FLOAT(126) DEFAULT 0, 
	"SERVICE_DU_MODIFIE" FLOAT(126) DEFAULT 0, 
	"SERVICE_REFERENTIEL" FLOAT(126) DEFAULT 0
   ) ;
--------------------------------------------------------
--  DDL for Table FORMULE_REFERENTIEL_MAJ
--------------------------------------------------------

  CREATE TABLE "OSE"."FORMULE_REFERENTIEL_MAJ" 
   (	"INTERVENANT_ID" NUMBER(*,0), 
	"ANNEE_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table FORMULE_SERVICE
--------------------------------------------------------

  CREATE TABLE "OSE"."FORMULE_SERVICE" 
   (	"ID" NUMBER(*,0), 
	"SERVICE_ID" NUMBER(*,0), 
	"TAUX_FI" FLOAT(126) DEFAULT 1, 
	"TAUX_FA" FLOAT(126) DEFAULT 0, 
	"TAUX_FC" FLOAT(126) DEFAULT 0, 
	"PONDERATION_SERVICE_DU" FLOAT(126) DEFAULT 1, 
	"PONDERATION_SERVICE_COMPL" FLOAT(126) DEFAULT 1, 
	"INTERVENANT_ID" NUMBER(*,0), 
	"ANNEE_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table FORMULE_SERVICE_MAJ
--------------------------------------------------------

  CREATE TABLE "OSE"."FORMULE_SERVICE_MAJ" 
   (	"SERVICE_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table FORMULE_VOLUME_HORAIRE
--------------------------------------------------------

  CREATE TABLE "OSE"."FORMULE_VOLUME_HORAIRE" 
   (	"ID" NUMBER(*,0), 
	"VOLUME_HORAIRE_ID" NUMBER(*,0), 
	"HEURES" FLOAT(126) DEFAULT 0, 
	"TAUX_SERVICE_DU" FLOAT(126) DEFAULT 1, 
	"TAUX_SERVICE_COMPL" FLOAT(126) DEFAULT 1, 
	"INTERVENANT_ID" NUMBER(*,0), 
	"ANNEE_ID" NUMBER(*,0), 
	"SERVICE_ID" NUMBER(*,0), 
	"TYPE_VOLUME_HORAIRE_ID" NUMBER(*,0), 
	"ETAT_VOLUME_HORAIRE_ID" NUMBER(*,0), 
	"TYPE_INTERVENTION_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table FORMULE_VOLUME_HORAIRE_MAJ
--------------------------------------------------------

  CREATE TABLE "OSE"."FORMULE_VOLUME_HORAIRE_MAJ" 
   (	"VOLUME_HORAIRE_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table GROUPE
--------------------------------------------------------

  CREATE TABLE "OSE"."GROUPE" 
   (	"ID" NUMBER(*,0), 
	"ELEMENT_PEDAGOGIQUE_ID" NUMBER(*,0), 
	"TYPE_INTERVENTION_ID" NUMBER(*,0), 
	"ANNEE_ID" NUMBER(*,0), 
	"NOMBRE" NUMBER(*,0), 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table GROUPE_TYPE_FORMATION
--------------------------------------------------------

  CREATE TABLE "OSE"."GROUPE_TYPE_FORMATION" 
   (	"ID" NUMBER(*,0), 
	"LIBELLE_COURT" VARCHAR2(20 CHAR), 
	"LIBELLE_LONG" VARCHAR2(50 CHAR), 
	"ORDRE" NUMBER(*,0), 
	"PERTINENCE_NIVEAU" NUMBER(1,0) DEFAULT 1, 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR), 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table INTERVENANT
--------------------------------------------------------

  CREATE TABLE "OSE"."INTERVENANT" 
   (	"ID" NUMBER(*,0), 
	"CIVILITE_ID" NUMBER(*,0), 
	"NOM_USUEL" VARCHAR2(60 CHAR), 
	"PRENOM" VARCHAR2(60 CHAR), 
	"NOM_PATRONYMIQUE" VARCHAR2(60 CHAR), 
	"DATE_NAISSANCE" DATE, 
	"PAYS_NAISSANCE_CODE_INSEE" VARCHAR2(3 CHAR), 
	"PAYS_NAISSANCE_LIBELLE" VARCHAR2(60 CHAR), 
	"DEP_NAISSANCE_CODE_INSEE" VARCHAR2(3 CHAR), 
	"DEP_NAISSANCE_LIBELLE" VARCHAR2(30 CHAR), 
	"VILLE_NAISSANCE_CODE_INSEE" VARCHAR2(5 CHAR), 
	"VILLE_NAISSANCE_LIBELLE" VARCHAR2(60 CHAR), 
	"PAYS_NATIONALITE_CODE_INSEE" VARCHAR2(3 CHAR), 
	"PAYS_NATIONALITE_LIBELLE" VARCHAR2(60 CHAR), 
	"TEL_PRO" VARCHAR2(20 CHAR), 
	"TEL_MOBILE" VARCHAR2(20 CHAR), 
	"EMAIL" VARCHAR2(255 CHAR), 
	"TYPE_ID" NUMBER(*,0), 
	"STATUT_ID" NUMBER(*,0), 
	"STRUCTURE_ID" NUMBER(*,0), 
	"DISCIPLINE_ID" NUMBER(*,0), 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR), 
	"PRIME_EXCELLENCE_SCIENT" NUMBER(1,0), 
	"NUMERO_INSEE" VARCHAR2(13 CHAR), 
	"NUMERO_INSEE_CLE" VARCHAR2(2 CHAR), 
	"NUMERO_INSEE_PROVISOIRE" NUMBER(1,0), 
	"IBAN" VARCHAR2(50 CHAR), 
	"BIC" VARCHAR2(20 CHAR), 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;

   COMMENT ON COLUMN "OSE"."INTERVENANT"."STRUCTURE_ID" IS 'Structure principale d''affectation';
--------------------------------------------------------
--  DDL for Table INTERVENANT_EXTERIEUR
--------------------------------------------------------

  CREATE TABLE "OSE"."INTERVENANT_EXTERIEUR" 
   (	"ID" NUMBER(*,0), 
	"SITUATION_FAMILIALE_ID" NUMBER(*,0), 
	"REGIME_SECU_ID" NUMBER(*,0), 
	"TYPE_POSTE_ID" NUMBER(*,0), 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0), 
	"DOSSIER_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table INTERVENANT_PERMANENT
--------------------------------------------------------

  CREATE TABLE "OSE"."INTERVENANT_PERMANENT" 
   (	"ID" NUMBER(*,0), 
	"CORPS_ID" NUMBER(*,0), 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table MODIFICATION_SERVICE_DU
--------------------------------------------------------

  CREATE TABLE "OSE"."MODIFICATION_SERVICE_DU" 
   (	"ID" NUMBER(*,0), 
	"INTERVENANT_ID" NUMBER(*,0), 
	"ANNEE_ID" NUMBER(*,0), 
	"HEURES" FLOAT(126), 
	"MOTIF_ID" NUMBER(*,0), 
	"COMMENTAIRES" CLOB, 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table MODULATEUR
--------------------------------------------------------

  CREATE TABLE "OSE"."MODULATEUR" 
   (	"ID" NUMBER(*,0), 
	"CODE" VARCHAR2(30 CHAR), 
	"LIBELLE" VARCHAR2(40 CHAR), 
	"TYPE_MODULATEUR_ID" NUMBER(*,0), 
	"PONDERATION_SERVICE_DU" FLOAT(126), 
	"PONDERATION_SERVICE_COMPL" FLOAT(126), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table MOTIF_MODIFICATION_SERVICE
--------------------------------------------------------

  CREATE TABLE "OSE"."MOTIF_MODIFICATION_SERVICE" 
   (	"ID" NUMBER(*,0), 
	"CODE" VARCHAR2(64 CHAR), 
	"LIBELLE" VARCHAR2(50 CHAR), 
	"MULTIPLICATEUR" NUMBER(*,0), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table MOTIF_NON_PAIEMENT
--------------------------------------------------------

  CREATE TABLE "OSE"."MOTIF_NON_PAIEMENT" 
   (	"ID" NUMBER(*,0), 
	"CODE" VARCHAR2(3 CHAR), 
	"LIBELLE_COURT" VARCHAR2(50 CHAR), 
	"LIBELLE_LONG" VARCHAR2(200 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table MV_ANTHONY2
--------------------------------------------------------

  CREATE TABLE "OSE"."MV_ANTHONY2" 
   (	"NOM_USUEL" VARCHAR2(120 BYTE), 
	"PRENOM" VARCHAR2(60 BYTE), 
	"NOM_PATRONYMIQUE" VARCHAR2(120 BYTE), 
	"DATE_NAISSANCE" DATE, 
	"PAYS_NAISSANCE_CODE_INSEE" VARCHAR2(9 BYTE), 
	"PAYS_NAISSANCE_LIBELLE" VARCHAR2(120 BYTE), 
	"DEP_NAISSANCE_CODE_INSEE" VARCHAR2(9 BYTE), 
	"DEP_NAISSANCE_LIBELLE" VARCHAR2(120 BYTE), 
	"VILLE_NAISSANCE_CODE_INSEE" VARCHAR2(15 BYTE), 
	"VILLE_NAISSANCE_LIBELLE" VARCHAR2(78 BYTE), 
	"PAYS_NATIONALITE_CODE_INSEE" VARCHAR2(9 BYTE), 
	"PAYS_NATIONALITE_LIBELLE" VARCHAR2(120 BYTE), 
	"TEL_PRO" VARCHAR2(33 BYTE), 
	"TEL_MOBILE" VARCHAR2(60 BYTE), 
	"Z_TYPE_ID" VARCHAR2(1 CHAR), 
	"Z_STATUT_ID" VARCHAR2(100 CHAR), 
	"SOURCE_CODE" VARCHAR2(9 BYTE), 
	"NUMERO_INSEE" VARCHAR2(39 BYTE), 
	"NUMERO_INSEE_CLE" VARCHAR2(40 BYTE), 
	"NUMERO_INSEE_PROVISOIRE" NUMBER, 
	"IBAN" VARCHAR2(108 BYTE), 
	"BIC" VARCHAR2(36 BYTE)
   ) ;
--------------------------------------------------------
--  DDL for Table PARAMETRE
--------------------------------------------------------

  CREATE TABLE "OSE"."PARAMETRE" 
   (	"ID" NUMBER(*,0), 
	"NOM" VARCHAR2(50 CHAR), 
	"VALEUR" CLOB, 
	"DESCRIPTION" CLOB, 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table PERIODE
--------------------------------------------------------

  CREATE TABLE "OSE"."PERIODE" 
   (	"ID" NUMBER(*,0), 
	"CODE" VARCHAR2(3 CHAR), 
	"LIBELLE_LONG" VARCHAR2(40 CHAR), 
	"LIBELLE_COURT" VARCHAR2(15 CHAR), 
	"ORDRE" NUMBER(*,0), 
	"TYPE_INTERVENANT_ID" NUMBER(*,0), 
	"ENSEIGNEMENT" NUMBER(1,0), 
	"PAIEMENT" NUMBER(1,0), 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table PERSONNEL
--------------------------------------------------------

  CREATE TABLE "OSE"."PERSONNEL" 
   (	"ID" NUMBER(*,0), 
	"CIVILITE_ID" NUMBER(*,0), 
	"NOM_USUEL" VARCHAR2(100 CHAR), 
	"PRENOM" VARCHAR2(60 CHAR), 
	"NOM_PATRONYMIQUE" VARCHAR2(100 CHAR), 
	"EMAIL" VARCHAR2(255 CHAR), 
	"STRUCTURE_ID" NUMBER(*,0), 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;

   COMMENT ON COLUMN "OSE"."PERSONNEL"."EMAIL" IS 'Email pro.';
   COMMENT ON COLUMN "OSE"."PERSONNEL"."STRUCTURE_ID" IS 'Structure principale du personnel';
--------------------------------------------------------
--  DDL for Table PIECE_JOINTE
--------------------------------------------------------

  CREATE TABLE "OSE"."PIECE_JOINTE" 
   (	"ID" NUMBER(*,0), 
	"TYPE_PIECE_JOINTE_ID" NUMBER(*,0), 
	"DOSSIER_ID" NUMBER(*,0), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0), 
	"VALIDATION_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table PIECE_JOINTE_FICHIER
--------------------------------------------------------

  CREATE TABLE "OSE"."PIECE_JOINTE_FICHIER" 
   (	"PIECE_JOINTE_ID" NUMBER, 
	"FICHIER_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table PRIME_EXCELLENCE_SCIENT
--------------------------------------------------------

  CREATE TABLE "OSE"."PRIME_EXCELLENCE_SCIENT" 
   (	"ID" NUMBER(*,0), 
	"INTERVENANT_ID" NUMBER(*,0), 
	"ANNEE_ID" NUMBER(*,0), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table REGIME_SECU
--------------------------------------------------------

  CREATE TABLE "OSE"."REGIME_SECU" 
   (	"ID" NUMBER(*,0), 
	"CODE" VARCHAR2(2 CHAR), 
	"LIBELLE" VARCHAR2(60 CHAR), 
	"TAUX_TAXE" FLOAT(126), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table ROLE
--------------------------------------------------------

  CREATE TABLE "OSE"."ROLE" 
   (	"ID" NUMBER(*,0), 
	"STRUCTURE_ID" NUMBER(*,0), 
	"PERSONNEL_ID" NUMBER(*,0), 
	"TYPE_ID" NUMBER(*,0), 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table ROLE_UTILISATEUR
--------------------------------------------------------

  CREATE TABLE "OSE"."ROLE_UTILISATEUR" 
   (	"ID" NUMBER(*,0), 
	"ROLE_ID" VARCHAR2(64 CHAR), 
	"IS_DEFAULT" NUMBER(*,0), 
	"PARENT_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table ROLE_UTILISATEUR_LINKER
--------------------------------------------------------

  CREATE TABLE "OSE"."ROLE_UTILISATEUR_LINKER" 
   (	"UTILISATEUR_ID" NUMBER(*,0), 
	"ROLE_UTILISATEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table SERVICE
--------------------------------------------------------

  CREATE TABLE "OSE"."SERVICE" 
   (	"ID" NUMBER(*,0), 
	"INTERVENANT_ID" NUMBER(*,0), 
	"STRUCTURE_AFF_ID" NUMBER(*,0), 
	"STRUCTURE_ENS_ID" NUMBER(*,0), 
	"ELEMENT_PEDAGOGIQUE_ID" NUMBER(*,0), 
	"ANNEE_ID" NUMBER(*,0), 
	"ETABLISSEMENT_ID" NUMBER(*,0), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;

   COMMENT ON COLUMN "OSE"."SERVICE"."STRUCTURE_AFF_ID" IS 'Structure d''affectation : reprend la strcuture d''affectation principale de l''intervenant.
Utile pour des raisons d''historique : si ce dernier change d''affectation, alors la structure d''affectation pour la service doit rester la même (pour le paiement notament)';
   COMMENT ON COLUMN "OSE"."SERVICE"."STRUCTURE_ENS_ID" IS 'Structure d''enseignement : devrait correspondre à la structure de la VET dans laquelle se situe l''élément pédagogique correspondant.

Valeur obligatoire SAUF SI le service concerne un autre établissement.';
--------------------------------------------------------
--  DDL for Table SERVICE_DU
--------------------------------------------------------

  CREATE TABLE "OSE"."SERVICE_DU" 
   (	"ID" NUMBER(*,0), 
	"INTERVENANT_ID" NUMBER(*,0), 
	"ANNEE_ID" NUMBER(*,0), 
	"HEURES" FLOAT(126), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table SERVICE_REFERENTIEL
--------------------------------------------------------

  CREATE TABLE "OSE"."SERVICE_REFERENTIEL" 
   (	"ID" NUMBER(*,0), 
	"FONCTION_ID" NUMBER(*,0), 
	"INTERVENANT_ID" NUMBER(*,0), 
	"STRUCTURE_ID" NUMBER(*,0), 
	"ANNEE_ID" NUMBER(*,0), 
	"HEURES" FLOAT(126), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0), 
	"COMMENTAIRES" VARCHAR2(256 CHAR)
   ) ;
--------------------------------------------------------
--  DDL for Table SITUATION_FAMILIALE
--------------------------------------------------------

  CREATE TABLE "OSE"."SITUATION_FAMILIALE" 
   (	"ID" NUMBER(*,0), 
	"CODE" VARCHAR2(1 CHAR), 
	"LIBELLE" VARCHAR2(15 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table SOURCE
--------------------------------------------------------

  CREATE TABLE "OSE"."SOURCE" 
   (	"ID" NUMBER(*,0), 
	"CODE" VARCHAR2(15 CHAR), 
	"LIBELLE" VARCHAR2(30 CHAR), 
	"IMPORTABLE" NUMBER(1,0)
   ) ;
--------------------------------------------------------
--  DDL for Table STATUT_INTERVENANT
--------------------------------------------------------

  CREATE TABLE "OSE"."STATUT_INTERVENANT" 
   (	"ID" NUMBER(*,0), 
	"LIBELLE" VARCHAR2(128 CHAR), 
	"SERVICE_STATUTAIRE" FLOAT(126), 
	"DEPASSEMENT" NUMBER(1,0), 
	"PLAFOND_REFERENTIEL" FLOAT(126) DEFAULT 0, 
	"MAXIMUM_HETD" FLOAT(126) DEFAULT 0, 
	"FONCTION_E_C" NUMBER(1,0), 
	"TYPE_INTERVENANT_ID" NUMBER(*,0), 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0), 
	"ORDRE" NUMBER(*,0), 
	"NON_AUTORISE" NUMBER(1,0), 
	"PEUT_SAISIR_SERVICE" NUMBER(1,0), 
	"PEUT_CHOISIR_DANS_DOSSIER" NUMBER(1,0), 
	"PEUT_SAISIR_DOSSIER" NUMBER(1,0), 
	"PEUT_SAISIR_REFERENTIEL" NUMBER(1,0) DEFAULT 0, 
	"PEUT_SAISIR_MOTIF_NON_PAIEMENT" NUMBER(1,0) DEFAULT 0
   ) ;
--------------------------------------------------------
--  DDL for Table STRUCTURE
--------------------------------------------------------

  CREATE TABLE "OSE"."STRUCTURE" 
   (	"ID" NUMBER(*,0), 
	"LIBELLE_LONG" VARCHAR2(60 CHAR), 
	"LIBELLE_COURT" VARCHAR2(25 CHAR), 
	"PARENTE_ID" NUMBER(*,0), 
	"STRUCTURE_NIV2_ID" NUMBER(*,0), 
	"TYPE_ID" NUMBER(*,0), 
	"ETABLISSEMENT_ID" NUMBER(*,0), 
	"NIVEAU" NUMBER(*,0), 
	"CONTACT_PJ" VARCHAR2(255 CHAR), 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table SYNC_LOG
--------------------------------------------------------

  CREATE TABLE "OSE"."SYNC_LOG" 
   (	"ID" NUMBER(*,0), 
	"DATE_SYNC" DATE, 
	"MESSAGE" CLOB
   ) ;
--------------------------------------------------------
--  DDL for Table TAUX_HORAIRE_HETD
--------------------------------------------------------

  CREATE TABLE "OSE"."TAUX_HORAIRE_HETD" 
   (	"ID" NUMBER(*,0), 
	"VALEUR" FLOAT(126), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table TEST_BUFFER
--------------------------------------------------------

  CREATE TABLE "OSE"."TEST_BUFFER" 
   (	"ID" NUMBER(*,0), 
	"TABLE_NAME" VARCHAR2(30 BYTE), 
	"DATA_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table TYPE_AGREMENT
--------------------------------------------------------

  CREATE TABLE "OSE"."TYPE_AGREMENT" 
   (	"ID" NUMBER(*,0), 
	"CODE" VARCHAR2(60 CHAR), 
	"LIBELLE" VARCHAR2(256 CHAR), 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table TYPE_AGREMENT_STATUT
--------------------------------------------------------

  CREATE TABLE "OSE"."TYPE_AGREMENT_STATUT" 
   (	"ID" NUMBER(*,0), 
	"TYPE_AGREMENT_ID" NUMBER(*,0), 
	"STATUT_INTERVENANT_ID" NUMBER(*,0), 
	"OBLIGATOIRE" NUMBER(*,0), 
	"SEUIL_HETD" NUMBER(*,0), 
	"PREMIER_RECRUTEMENT" NUMBER(1,0) DEFAULT 0, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table TYPE_CONTRAT
--------------------------------------------------------

  CREATE TABLE "OSE"."TYPE_CONTRAT" 
   (	"ID" NUMBER(*,0), 
	"CODE" VARCHAR2(15 CHAR), 
	"LIBELLE" VARCHAR2(60 CHAR), 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table TYPE_FORMATION
--------------------------------------------------------

  CREATE TABLE "OSE"."TYPE_FORMATION" 
   (	"ID" NUMBER(*,0), 
	"LIBELLE_LONG" VARCHAR2(80 CHAR), 
	"LIBELLE_COURT" VARCHAR2(15 CHAR), 
	"GROUPE_ID" NUMBER(*,0), 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table TYPE_INTERVENANT
--------------------------------------------------------

  CREATE TABLE "OSE"."TYPE_INTERVENANT" 
   (	"ID" NUMBER(*,0), 
	"CODE" VARCHAR2(1 CHAR), 
	"LIBELLE" VARCHAR2(50 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table TYPE_INTERVENTION
--------------------------------------------------------

  CREATE TABLE "OSE"."TYPE_INTERVENTION" 
   (	"ID" NUMBER(*,0), 
	"CODE" VARCHAR2(10 CHAR), 
	"LIBELLE" VARCHAR2(60 CHAR), 
	"ORDRE" NUMBER(*,0), 
	"TAUX_HETD_SERVICE" FLOAT(126) DEFAULT 1, 
	"TAUX_HETD_COMPLEMENTAIRE" FLOAT(126) DEFAULT 1, 
	"INTERVENTION_INDIVIDUALISEE" NUMBER(1,0), 
	"VISIBLE" NUMBER(1,0), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table TYPE_INTERVENTION_EP
--------------------------------------------------------

  CREATE TABLE "OSE"."TYPE_INTERVENTION_EP" 
   (	"ID" NUMBER(*,0), 
	"TYPE_INTERVENTION_ID" NUMBER(*,0), 
	"ELEMENT_PEDAGOGIQUE_ID" NUMBER(*,0), 
	"VISIBLE" NUMBER(1,0), 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0), 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR)
   ) ;
--------------------------------------------------------
--  DDL for Table TYPE_INTERVENTION_STRUCTURE
--------------------------------------------------------

  CREATE TABLE "OSE"."TYPE_INTERVENTION_STRUCTURE" 
   (	"ID" NUMBER(*,0), 
	"TYPE_INTERVENTION_ID" NUMBER(*,0), 
	"STRUCTURE_ID" NUMBER(*,0), 
	"VISIBLE" NUMBER(1,0), 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table TYPE_MODULATEUR
--------------------------------------------------------

  CREATE TABLE "OSE"."TYPE_MODULATEUR" 
   (	"ID" NUMBER(*,0), 
	"CODE" VARCHAR2(10 CHAR), 
	"LIBELLE" VARCHAR2(50 CHAR), 
	"PUBLIQUE" NUMBER(1,0), 
	"OBLIGATOIRE" NUMBER(1,0), 
	"SAISIE_PAR_ENSEIGNANT" NUMBER(1,0), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table TYPE_MODULATEUR_STRUCTURE
--------------------------------------------------------

  CREATE TABLE "OSE"."TYPE_MODULATEUR_STRUCTURE" 
   (	"ID" NUMBER(*,0), 
	"TYPE_MODULATEUR_ID" NUMBER(*,0), 
	"STRUCTURE_ID" NUMBER(*,0), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table TYPE_PIECE_JOINTE
--------------------------------------------------------

  CREATE TABLE "OSE"."TYPE_PIECE_JOINTE" 
   (	"ID" NUMBER(*,0), 
	"CODE" VARCHAR2(64 CHAR), 
	"LIBELLE" VARCHAR2(150 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0), 
	"URL_MODELE_DOC" VARCHAR2(256 CHAR)
   ) ;
--------------------------------------------------------
--  DDL for Table TYPE_PIECE_JOINTE_STATUT
--------------------------------------------------------

  CREATE TABLE "OSE"."TYPE_PIECE_JOINTE_STATUT" 
   (	"ID" NUMBER(*,0), 
	"TYPE_PIECE_JOINTE_ID" NUMBER(*,0), 
	"STATUT_INTERVENANT_ID" NUMBER(*,0), 
	"OBLIGATOIRE" NUMBER(*,0), 
	"SEUIL_HETD" NUMBER(*,0), 
	"PREMIER_RECRUTEMENT" NUMBER(1,0) DEFAULT 0, 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table TYPE_POSTE
--------------------------------------------------------

  CREATE TABLE "OSE"."TYPE_POSTE" 
   (	"ID" NUMBER(*,0), 
	"LIBELLE" VARCHAR2(60 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table TYPE_ROLE
--------------------------------------------------------

  CREATE TABLE "OSE"."TYPE_ROLE" 
   (	"ID" NUMBER(*,0), 
	"CODE" VARCHAR2(64 CHAR), 
	"LIBELLE" VARCHAR2(50 CHAR), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table TYPE_ROLE_STRUCTURE
--------------------------------------------------------

  CREATE TABLE "OSE"."TYPE_ROLE_STRUCTURE" 
   (	"TYPE_ROLE_ID" NUMBER(*,0), 
	"TYPE_STRUCTURE_ID" NUMBER(*,0), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table TYPE_STRUCTURE
--------------------------------------------------------

  CREATE TABLE "OSE"."TYPE_STRUCTURE" 
   (	"ID" NUMBER(*,0), 
	"CODE" VARCHAR2(3 CHAR), 
	"LIBELLE" VARCHAR2(60 CHAR), 
	"ENSEIGNEMENT" NUMBER(1,0), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table TYPE_VALIDATION
--------------------------------------------------------

  CREATE TABLE "OSE"."TYPE_VALIDATION" 
   (	"ID" NUMBER(*,0), 
	"CODE" VARCHAR2(25 CHAR), 
	"LIBELLE" VARCHAR2(60 CHAR), 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table TYPE_VOLUME_HORAIRE
--------------------------------------------------------

  CREATE TABLE "OSE"."TYPE_VOLUME_HORAIRE" 
   (	"ID" NUMBER(*,0), 
	"CODE" VARCHAR2(15 CHAR), 
	"LIBELLE" VARCHAR2(60 CHAR), 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table UTILISATEUR
--------------------------------------------------------

  CREATE TABLE "OSE"."UTILISATEUR" 
   (	"ID" NUMBER(*,0), 
	"USERNAME" VARCHAR2(255 CHAR), 
	"EMAIL" VARCHAR2(255 CHAR), 
	"DISPLAY_NAME" VARCHAR2(64 CHAR), 
	"PASSWORD" VARCHAR2(128 CHAR), 
	"INTERVENANT_ID" NUMBER(*,0), 
	"PERSONNEL_ID" NUMBER(*,0), 
	"STATE" NUMBER
   ) ;
--------------------------------------------------------
--  DDL for Table VALIDATION
--------------------------------------------------------

  CREATE TABLE "OSE"."VALIDATION" 
   (	"ID" NUMBER(*,0), 
	"TYPE_VALIDATION_ID" NUMBER(*,0), 
	"INTERVENANT_ID" NUMBER(*,0), 
	"STRUCTURE_ID" NUMBER(*,0), 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table VALIDATION_VOL_HORAIRE
--------------------------------------------------------

  CREATE TABLE "OSE"."VALIDATION_VOL_HORAIRE" 
   (	"VALIDATION_ID" NUMBER(*,0), 
	"VOLUME_HORAIRE_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table VOLUME_HORAIRE
--------------------------------------------------------

  CREATE TABLE "OSE"."VOLUME_HORAIRE" 
   (	"ID" NUMBER(*,0), 
	"TYPE_VOLUME_HORAIRE_ID" NUMBER(*,0), 
	"SERVICE_ID" NUMBER(*,0), 
	"PERIODE_ID" NUMBER(*,0), 
	"TYPE_INTERVENTION_ID" NUMBER(*,0), 
	"HEURES" FLOAT(126) DEFAULT 0, 
	"MOTIF_NON_PAIEMENT_ID" NUMBER(*,0), 
	"CONTRAT_ID" NUMBER(*,0), 
	"VALIDITE_DEBUT" DATE DEFAULT SYSDATE, 
	"VALIDITE_FIN" DATE, 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for Table VOLUME_HORAIRE_ENS
--------------------------------------------------------

  CREATE TABLE "OSE"."VOLUME_HORAIRE_ENS" 
   (	"ID" NUMBER(*,0), 
	"ELEMENT_DISCIPLINE_ID" NUMBER, 
	"TYPE_INTERVENTION_ID" NUMBER(*,0), 
	"ANNEE_ID" NUMBER(*,0), 
	"HEURES" FLOAT(126), 
	"SOURCE_ID" NUMBER(*,0), 
	"SOURCE_CODE" VARCHAR2(100 CHAR), 
	"HISTO_CREATION" DATE DEFAULT SYSDATE, 
	"HISTO_CREATEUR_ID" NUMBER(*,0), 
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE, 
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0), 
	"HISTO_DESTRUCTION" DATE, 
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   ) ;
--------------------------------------------------------
--  DDL for View ANTHONY_V_HARP_IND_DER_STRUCT
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."ANTHONY_V_HARP_IND_DER_STRUCT" ("NO_INDIVIDU", "DATE_DEPART", "C_STRUCTURE") AS 
  SELECT DISTINCT
  no_individu
  ,date_depart
  ,c_structure_depart c_structure
FROM
  ANTHONY_MV_HARPEGE
;
--------------------------------------------------------
--  DDL for View ANTHONY_V_HARP_INDIVIDU_BANQUE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."ANTHONY_V_HARP_INDIVIDU_BANQUE" ("NO_INDIVIDU", "IBAN", "BIC") AS 
  SELECT DISTINCT
  no_individu
  ,CASE WHEN id_ind_banque IS NOT NULL THEN
  trim( NVL(c_pays_iso || cle_controle,'FR00') || ' ' ||
    substr(c_banque,0,4) || ' ' ||
    substr(c_banque,5,1) || substr(c_guichet,0,3) || ' ' ||
    substr(c_guichet,4,2) || substr(no_compte,0,2) || ' ' ||
    substr(no_compte,3,4) || ' ' ||
    substr(no_compte,7,4) || ' ' ||
    substr(no_compte,11) || cle_rib) ELSE NULL END IBAN
  ,CASE WHEN id_ind_banque IS NOT NULL THEN
  c_banque_bic || ' ' || c_pays_bic || ' ' || c_emplacement || ' ' || c_branche ELSE NULL END BIC
FROM
  ANTHONY_MV_HARPEGE ;
--------------------------------------------------------
--  DDL for View ANTHONY_V_HARP_INDIVIDU_STATUT
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."ANTHONY_V_HARP_INDIVIDU_STATUT" ("NO_INDIVIDU", "STATUT", "TYPE_INTERVENANT") AS 
  SELECT
  no_individu,
  statut,
  ti.code type_intervenant
FROM
(
SELECT
  no_individu,
  CASE
    WHEN NVL(c.ordre,99999) > NVL(tp.ordre,99999) THEN COALESCE(tp.statut, c.statut, 'AUTRES')
    WHEN NVL(c.ordre,99999) <= NVL(tp.ordre,99999) THEN COALESCE(c.statut, tp.statut, 'AUTRES')
  END statut
FROM
  (SELECT DISTINCT no_individu FROM ANTHONY_MV_HARPEGE) mvh LEFT JOIN
  (SELECT DISTINCT
      i.no_individu no_dossier_pers,
      si.source_code statut,
      si.ordre,
      min(si.ordre) over(partition BY i.no_individu) AS min_ordre
    FROM
      ANTHONY_MV_HARPEGE i
      JOIN statut_intervenant si ON si.source_code = CASE
        WHEN i.c_type_contrat_trav IN ('MC','MA')                THEN 'ASS_MI_TPS'
        WHEN i.c_type_contrat_trav IN ('AT')                     THEN 'ATER'
        WHEN i.c_type_contrat_trav IN ('AX')                     THEN 'ATER_MI_TPS'
        WHEN i.c_type_contrat_trav IN ('DO')                     THEN 'DOCTOR'
        WHEN i.c_type_contrat_trav IN ('GI','PN')                THEN 'ENS_CONTRACT'
        WHEN i.c_type_contrat_trav IN ('LT','LB')                THEN 'LECTEUR'
        WHEN i.c_type_contrat_trav IN ('MB')                     THEN 'MAITRE_LANG'
        WHEN i.c_type_contrat_trav IN ('C3','CA','CB','CD','HA','HS','S3','SX','SW','SY','CS','SZ','VA') THEN 'BIATSS'
        WHEN i.c_type_contrat_trav IN ('CU','AH','CG','MM','PM','IN','DN','ET','NF') THEN 'NON_AUTORISE'
                                                                  ELSE 'AUTRES'
      END
    WHERE
      SYSDATE BETWEEN i.cav_d_deb_contrat_trav AND NVL(i.cav_d_fin_contrat_trav,SYSDATE)
  ) c ON c.no_dossier_pers = mvh.no_individu AND c.ordre = c.min_ordre
  LEFT JOIN (SELECT DISTINCT
      i.no_individu no_dossier_pers,
      si.source_code statut,
      si.ordre,
      min(si.ordre) over(partition BY i.no_individu) AS min_ordre
    FROM
      ANTHONY_MV_HARPEGE i
      JOIN statut_intervenant si ON si.source_code = CASE
        WHEN i.c_type_population IN ('DA','DC','OA')                THEN 'ENS_2ND_DEG'
        WHEN i.c_type_population IN ('SA')                          THEN 'ENS_CH'
        WHEN i.c_type_population IN ('AA','AC','BA','IA','MA')      THEN 'BIATSS'
        WHEN i.c_type_population IN ('MG','SB')                     THEN 'NON_AUTORISE'
                                                                    ELSE 'AUTRES'
      END
    WHERE
      (SYSDATE BETWEEN i.aa_d_deb_affectation AND COALESCE(i.aa_d_fin_affectation,SYSDATE))
  ) tp ON tp.no_dossier_pers = mvh.no_individu AND tp.ordre = tp.min_ordre
) tmp
JOIN statut_intervenant si ON si.source_code = tmp.statut
JOIN type_intervenant ti ON ti.id = si.type_intervenant_id;
--------------------------------------------------------
--  DDL for View ANTHONY_V_INTERVENANT
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."ANTHONY_V_INTERVENANT" ("CIVILITE_ID", "NOM_USUEL", "PRENOM", "NOM_PATRONYMIQUE", "DATE_NAISSANCE", "PAYS_NAISSANCE_CODE_INSEE", "PAYS_NAISSANCE_LIBELLE", "DEP_NAISSANCE_CODE_INSEE", "DEP_NAISSANCE_LIBELLE", "VILLE_NAISSANCE_CODE_INSEE", "VILLE_NAISSANCE_LIBELLE", "PAYS_NATIONALITE_CODE_INSEE", "PAYS_NATIONALITE_LIBELLE", "TEL_PRO", "TEL_MOBILE", "EMAIL", "Z_TYPE_ID", "Z_STATUT_ID", "Z_STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE", "NUMERO_INSEE", "NUMERO_INSEE_CLE", "NUMERO_INSEE_PROVISOIRE", "IBAN", "BIC") AS 
  SELECT
  ose_import.get_civilite_id(
    CASE mvh.c_civilite
    WHEN 'M.' THEN 'M.'
    ELSE 'Mme'
    END)                      "CIVILITE_ID"
  ,initcap(mvh.nom_usuel)        "NOM_USUEL"
  ,initcap(mvh.prenom)           "PRENOM"
  ,initcap(mvh.nom_patronymique) "NOM_PATRONYMIQUE"
  ,mvh.d_naissance            "DATE_NAISSANCE"
  ,mvh.c_pays_naissance       "PAYS_NAISSANCE_CODE_INSEE"
  ,mvh.ll_pays_naissance      "PAYS_NAISSANCE_LIBELLE"
  ,mvh.c_departement          "DEP_NAISSANCE_CODE_INSEE"
  ,mvh.ll_departement         "DEP_NAISSANCE_LIBELLE"
  ,mvh.c_commune_naissance    "VILLE_NAISSANCE_CODE_INSEE"
  ,mvh.ville_de_naissance     "VILLE_NAISSANCE_LIBELLE"
  ,mvh.c_pays_nationalite     "PAYS_NATIONALITE_CODE_INSEE"
  ,mvh.ll_pays_nationalite    "PAYS_NATIONALITE_LIBELLE"
  ,mvh.no_telephone           "TEL_PRO"
  ,mvh.no_tel_portable        "TEL_MOBILE"
  ,NVL(mvh.NO_E_MAIL, UCBN_LDAP.hid2mail(mvh.no_individu)) "EMAIL"
  ,his.type_intervenant       "Z_TYPE_ID"
  ,his.statut                 "Z_STATUT_ID"
  ,NVL(istr.c_structure, 'UNIV')                "Z_STRUCTURE_ID"
  ,ose_import.get_source_id('Harpege')          "SOURCE_ID"
  ,ltrim(TO_CHAR(mvh.no_individu,'99999999'))   "SOURCE_CODE"
--  ,null                                       "PRIME_EXCELLENCE_SCIENTIFIQUE"
  ,mvh.no_insee               "NUMERO_INSEE"
  ,mvh.cle_insee              "NUMERO_INSEE_CLE"
  ,CASE
    WHEN mvh.no_insee IS NULL THEN NULL
    ELSE 0
    END                       "NUMERO_INSEE_PROVISOIRE"
  ,ib.iban                    "IBAN"
  ,ib.bic                     "BIC"
FROM
  ( select distinct c_civilite,nom_usuel,prenom,nom_patronymique,d_naissance,c_pays_naissance,ll_pays_naissance
                    ,c_departement,ll_departement,c_commune_naissance,ville_de_naissance,c_pays_nationalite,ll_pays_nationalite
                    ,no_telephone,no_tel_portable ,NO_E_MAIL,no_individu,no_insee ,cle_insee
    from anthony_mv_harpege
  ) mvh
  LEFT JOIN ANTHONY_V_HARP_INDIVIDU_BANQUE ib ON (mvh.no_individu=ib.no_individu)
  JOIN ANTHONY_V_HARP_IND_DER_STRUCT istr ON (mvh.no_individu = istr.no_individu)
  JOIN ANTHONY_V_HARP_INDIVIDU_STATUT his ON (mvh.no_individu = his.no_individu)
;
--------------------------------------------------------
--  DDL for View SRC_ADRESSE_INTERVENANT
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ADRESSE_INTERVENANT" ("ID", "INTERVENANT_ID", "PRINCIPALE", "TEL_DOMICILE", "MENTION_COMPLEMENTAIRE", "NO_VOIE", "NOM_VOIE", "LOCALITE", "CODE_POSTAL", "VILLE", "PAYS_CODE_INSEE", "PAYS_LIBELLE", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT") AS 
  SELECT
  NULL id,
  INTERVENANT.ID                                        INTERVENANT_ID,
  AI.PRINCIPALE,
  AI.TEL_DOMICILE,
  AI.MENTION_COMPLEMENTAIRE,
  AI.NO_VOIE,
  AI.NOM_VOIE,
  AI.LOCALITE,
  AI.CODE_POSTAL,
  AI.VILLE,
  AI.PAYS_CODE_INSEE,
  AI.PAYS_LIBELLE,
  AI.SOURCE_ID,
  AI.SOURCE_CODE,
  AI.VALIDITE_DEBUT
FROM
  MV_ADRESSE_intervenant ai  
  LEFT JOIN INTERVENANT ON (INTERVENANT.SOURCE_CODE = AI.Z_INTERVENANT_ID);
--------------------------------------------------------
--  DDL for View SRC_ADRESSE_STRUCTURE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ADRESSE_STRUCTURE" ("ID", "STRUCTURE_ID", "PRINCIPALE", "TELEPHONE", "NO_VOIE", "NOM_VOIE", "LOCALITE", "CODE_POSTAL", "VILLE", "PAYS_CODE_INSEE", "PAYS_LIBELLE", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN") AS 
  SELECT
  NULL id,
  s.ID                                        structure_id,
  astr.PRINCIPALE,
  astr.TELEPHONE,
  astr.NO_VOIE,
  astr.NOM_VOIE,
  astr.LOCALITE,
  astr.CODE_POSTAL,
  astr.VILLE,
  astr.PAYS_CODE_INSEE,
  astr.PAYS_LIBELLE,
  astr.SOURCE_ID,
  astr.SOURCE_CODE,
  astr.VALIDITE_DEBUT,
  astr.VALIDITE_FIN
FROM
  mv_adresse_structure astr
  JOIN structure s ON s.source_code = astr.z_structure_id;
--------------------------------------------------------
--  DDL for View SRC_AFFECTATION_RECHERCHE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_AFFECTATION_RECHERCHE" ("ID", "INTERVENANT_ID", "STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN") AS 
  SELECT id, intervenant_id, structure_id, source_id, source_code, validite_debut, validite_fin FROM 
(SELECT * FROM (SELECT
  NULL id,
  i.id intervenant_id,
  NVL(s.STRUCTURE_NIV2_ID,s.id) structure_id,
  aff.SOURCE_ID,
  aff.SOURCE_CODE,
  aff.VALIDITE_DEBUT,
  aff.VALIDITE_FIN,
  min(nvl(aff.validite_fin,sysdate)) over(partition BY i.id) min_validite_fin,
  max(aff.SOURCE_CODE) over(partition by i.id) max_source_code
FROM
  mv_affectation_recherche aff
  LEFT JOIN intervenant i ON (i.source_code = aff.z_intervenant_id)
  LEFT JOIN structure s ON (s.source_code = aff.z_structure_id)
) tmp1
WHERE
  nvl(VALIDITE_FIN,sysdate) = min_validite_fin
) tmp2
WHERE
  source_code = max_source_code;
--------------------------------------------------------
--  DDL for View SRC_CHEMIN_PEDAGOGIQUE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_CHEMIN_PEDAGOGIQUE" ("ID", "ELEMENT_PEDAGOGIQUE_ID", "ETAPE_ID", "ORDRE", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN") AS 
  WITH C AS (

SELECT 
  z_element_pedagogique_id,
  z_etape_id,
  min(ordre) ordre,
  source_id,
  source_code,
  min(validite_debut) validite_debut,
  max(validite_fin) validite_fin
FROM
  MV_CHEMIN_PEDAGOGIQUE
GROUP BY
  z_element_pedagogique_id,
  z_etape_id,
  source_id,
  source_code
)
SELECT
  null id,
  elp.id element_pedagogique_id,
  etp.id ETAPE_ID,
  c.ordre,
  c.source_id,
  C.SOURCE_CODE,
  C.VALIDITE_DEBUT,
  C.VALIDITE_FIN
FROM
  C
  LEFT JOIN ELEMENT_PEDAGOGIQUE elp ON elp.source_code = C.Z_ELEMENT_PEDAGOGIQUE_ID
  LEFT JOIN ETAPE etp ON etp.source_code = C.Z_ETAPE_ID;
--------------------------------------------------------
--  DDL for View SRC_CORPS
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_CORPS" ("ID", "LIBELLE_LONG", "LIBELLE_COURT", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN") AS 
  SELECT
  null id,
  C.LIBELLE_LONG,
  C.LIBELLE_COURT,
  C.SOURCE_ID,
  C.SOURCE_CODE,
  C.VALIDITE_DEBUT,
  C.VALIDITE_FIN
FROM
  MV_corps c;
--------------------------------------------------------
--  DDL for View SRC_DISCIPLINE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_DISCIPLINE" ("ID", "LIBELLE_COURT", "LIBELLE_LONG", "ORDRE", "SOURCE_ID", "SOURCE_CODE") AS 
  SELECT
  null id,
  convert(d.libelle_court, 'WE8ISO8859P1') libelle_court,
  convert(d.libelle_long, 'WE8ISO8859P1') libelle_long,
  d.ordre,
  d.SOURCE_ID,
  d.SOURCE_CODE
FROM
  mv_discipline d;
--------------------------------------------------------
--  DDL for View SRC_ELEMENT_DISCIPLINE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ELEMENT_DISCIPLINE" ("ID", "ELEMENT_PEDAGOGIQUE_ID", "DISCIPLINE_ID", "SOURCE_ID", "SOURCE_CODE") AS 
  SELECT
  null id,
  ep.id ELEMENT_PEDAGOGIQUE_ID,
  d.id DISCIPLINE_ID,
  ed.SOURCE_ID,
  ed.SOURCE_CODE
FROM
  mv_element_discipline ed
  LEFT JOIN element_pedagogique ep ON ep.source_code = ed.Z_ELEMENT_PEDAGOGIQUE_ID
  LEFT JOIN discipline d ON d.source_code = ED.Z_DISCIPLINE_ID;
--------------------------------------------------------
--  DDL for View SRC_ELEMENT_PEDAGOGIQUE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ELEMENT_PEDAGOGIQUE" ("ID", "LIBELLE", "ETAPE_ID", "STRUCTURE_ID", "PERIODE_ID", "TAUX_FI", "TAUX_FC", "TAUX_FA", "TAUX_FOAD", "FC", "FI", "FA", "SOURCE_ID", "SOURCE_CODE") AS 
  SELECT
  null id,
  E.LIBELLE,
  etp.id ETAPE_ID,
  NVL(str.STRUCTURE_NIV2_ID,str.id) structure_id,
  per.id periode_id,
  CASE 
    WHEN etr.id IS NOT NULL THEN etr.taux_fi
    ELSE ROUND( e.fi / (e.fi + e.fc + e.fa), 4)
  END taux_fi,
  CASE 
    WHEN etr.id IS NOT NULL THEN etr.taux_fc
    ELSE ROUND( e.fc / (e.fi + e.fc + e.fa), 4)
  END taux_fc,
  CASE 
    WHEN etr.id IS NOT NULL THEN etr.taux_fa
    ELSE ROUND( e.fa / (e.fi + e.fc + e.fa), 4)
  END taux_fa,
  e.taux_foad,
  e.fc,
  e.fi,
  e.fa,
  E.SOURCE_ID,
  E.SOURCE_CODE
FROM
  MV_ELEMENT_PEDAGOGIQUE E
  LEFT JOIN etape etp ON etp.source_code = E.Z_ETAPE_ID
  LEFT JOIN structure str ON str.source_code = E.Z_STRUCTURE_ID
  LEFT JOIN periode per ON per.libelle_court = E.Z_PERIODE_ID
  LEFT JOIN element_taux_regimes etr ON
    etr.source_code = e.source_code
    AND etr.annee_id = OSE_PARAMETRE.GET_ANNEE
    AND 1 = OSE_DIVERS.COMPRISE_ENTRE( etr.histo_creation, etr.histo_destruction );
--------------------------------------------------------
--  DDL for View SRC_ELEMENT_PORTEUR_PORTE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ELEMENT_PORTEUR_PORTE" ("ID", "ELEMENT_PORTEUR_ID", "ELEMENT_PORTE_ID", "TYPE_INTERVENTION_ID", "SOURCE_ID", "SOURCE_CODE") AS 
  SELECT
  null id,
  elr.id ELEMENT_PORTEUR_ID,
  ele.id ELEMENT_PORTE_ID,
  tin.id TYPE_INTERVENTION_ID,
  EPP.SOURCE_ID,
  EPP.SOURCE_CODE || '_' || trim(EPP.Z_TYPE_INTERVENTION_ID) SOURCE_CODE
FROM
  MV_ELEMENT_PORTEUR_PORTE EPP
  LEFT JOIN TYPE_INTERVENTION tin ON TIN.CODE = trim(EPP.Z_TYPE_INTERVENTION_ID)
  LEFT JOIN ELEMENT_PEDAGOGIQUE ele ON ele.source_code = EPP.Z_ELEMENT_PORTE_ID
  LEFT JOIN ELEMENT_PEDAGOGIQUE elr ON elr.source_code = EPP.Z_ELEMENT_PORTEUR_ID;
--------------------------------------------------------
--  DDL for View SRC_ETABLISSEMENT
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ETABLISSEMENT" ("ID", "LIBELLE", "LOCALISATION", "DEPARTEMENT", "SOURCE_ID", "SOURCE_CODE") AS 
  SELECT
  null id,
  E.LIBELLE,
  E.LOCALISATION,
  E.DEPARTEMENT,
  E.SOURCE_ID,
  E.SOURCE_CODE
FROM
  MV_ETABLISSEMENT E;
--------------------------------------------------------
--  DDL for View SRC_ETAPE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ETAPE" ("ID", "LIBELLE", "TYPE_FORMATION_ID", "NIVEAU", "SPECIFIQUE_ECHANGES", "STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN") AS 
  SELECT
  null id,
  e.libelle,
  tf.id type_formation_id,
  e.niveau,
  e.specifique_echanges,
  NVL(s.STRUCTURE_NIV2_ID,s.id) structure_id,
  e.source_id,
  e.source_code,
  e.validite_debut,
  e.validite_fin
FROM
  MV_ETAPE e
  LEFT JOIN TYPE_FORMATION tf ON tf.source_code = E.Z_TYPE_FORMATION_ID
  LEFT JOIN STRUCTURE s ON s.source_code = E.Z_STRUCTURE_ID;
--------------------------------------------------------
--  DDL for View SRC_GROUPE_TYPE_FORMATION
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_GROUPE_TYPE_FORMATION" ("ID", "LIBELLE_COURT", "LIBELLE_LONG", "ORDRE", "PERTINENCE_NIVEAU", "SOURCE_ID", "SOURCE_CODE") AS 
  SELECT
  null id,
  libelle_court,
  libelle_long,
  ordre,
  pertinence_niveau,
  source_id,
  source_code
FROM
  MV_GROUPE_TYPE_FORMATION;
--------------------------------------------------------
--  DDL for View SRC_INTERVENANT
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_INTERVENANT" ("ID", "CIVILITE_ID", "NOM_USUEL", "PRENOM", "NOM_PATRONYMIQUE", "DATE_NAISSANCE", "PAYS_NAISSANCE_CODE_INSEE", "PAYS_NAISSANCE_LIBELLE", "DEP_NAISSANCE_CODE_INSEE", "DEP_NAISSANCE_LIBELLE", "VILLE_NAISSANCE_CODE_INSEE", "VILLE_NAISSANCE_LIBELLE", "PAYS_NATIONALITE_CODE_INSEE", "PAYS_NATIONALITE_LIBELLE", "TEL_PRO", "TEL_MOBILE", "EMAIL", "TYPE_ID", "STATUT_ID", "STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE", "NUMERO_INSEE", "NUMERO_INSEE_CLE", "NUMERO_INSEE_PROVISOIRE", "IBAN", "BIC") AS 
  SELECT
  null id,
  i."CIVILITE_ID",
  i."NOM_USUEL",
  i."PRENOM",
  i."NOM_PATRONYMIQUE",
  NVL(i."DATE_NAISSANCE",TO_DATE('2099-01-01','YYYY-MM-DD')) DATE_NAISSANCE,
  i."PAYS_NAISSANCE_CODE_INSEE",
  i."PAYS_NAISSANCE_LIBELLE",
  i."DEP_NAISSANCE_CODE_INSEE",
  i."DEP_NAISSANCE_LIBELLE",
  i."VILLE_NAISSANCE_CODE_INSEE",
  i."VILLE_NAISSANCE_LIBELLE",
  i."PAYS_NATIONALITE_CODE_INSEE",
  i."PAYS_NATIONALITE_LIBELLE",
  i."TEL_PRO",
  i."TEL_MOBILE",
  i."EMAIL",
  TI.ID type_id,
  CASE WHEN si.source_code = 'AUTRES' AND d.statut_id IS NOT NULL THEN d.statut_id ELSE si.id END statut_id,
--  si.id statut_id,
  NVL(s.STRUCTURE_NIV2_ID,s.id) structure_id,
  i."SOURCE_ID",
  i."SOURCE_CODE",
  i."NUMERO_INSEE",
  i."NUMERO_INSEE_CLE",
  i."NUMERO_INSEE_PROVISOIRE",
  i."IBAN",
  i."BIC"
FROM
  mv_intervenant i
  LEFT JOIN statut_intervenant si ON si.source_code = i.z_statut_id
  LEFT JOIN type_intervenant ti ON ti.code = I.Z_TYPE_ID
  LEFT JOIN structure s ON s.source_code = i.z_structure_id
  LEFT JOIN intervenant_exterieur ie ON ie.source_code = i.source_code
  LEFT JOIN dossier d ON d.id = ie.dossier_id;
--------------------------------------------------------
--  DDL for View SRC_INTERVENANT_EXTERIEUR
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_INTERVENANT_EXTERIEUR" ("ID", "SITUATION_FAMILIALE_ID", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN") AS 
  select
  i.id,
  ie."SITUATION_FAMILIALE_ID",ie."SOURCE_ID",ie."SOURCE_CODE",ie."VALIDITE_DEBUT",ie."VALIDITE_FIN"
from
  mv_intervenant_exterieur ie
  JOIN intervenant i ON (i.source_code = ie.source_code);
--------------------------------------------------------
--  DDL for View SRC_INTERVENANT_PERMANENT
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_INTERVENANT_PERMANENT" ("ID", "CORPS_ID", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN") AS 
  select
  i.id,
  c.id as corps_id,
  IP.SOURCE_ID,
  IP.SOURCE_CODE,
  IP.VALIDITE_DEBUT,
  IP.VALIDITE_FIN
from
  mv_intervenant_permanent ip
  JOIN intervenant i ON (i.source_code = ip.source_code)
  LEFT JOIN corps c ON (c.source_code = IP.Z_CORPS_ID);
--------------------------------------------------------
--  DDL for View SRC_PERSONNEL
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_PERSONNEL" ("ID", "CIVILITE_ID", "NOM_USUEL", "PRENOM", "NOM_PATRONYMIQUE", "EMAIL", "DATE_NAISSANCE", "STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN") AS 
  SELECT
  NULL id,
  p.civilite_id,
  p.nom_usuel,
  p.prenom,
  p.nom_patronymique,
  p.email,
  p.date_naissance,
  NVL(s.STRUCTURE_NIV2_ID,s.id) structure_id,
  p.source_id,
  p.source_code,
  p.validite_debut,
  p.validite_fin
FROM
  mv_personnel p
  JOIN structure s ON s.source_code = p.z_structure_id;
--------------------------------------------------------
--  DDL for View SRC_ROLE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ROLE" ("ID", "STRUCTURE_ID", "PERSONNEL_ID", "TYPE_ID", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN") AS 
  SELECT
  NULL id,
  NVL(s.STRUCTURE_NIV2_ID,s.id) structure_id,
  p.id personnel_id,
  tr.id type_id,
  r.source_id,
  r.source_code,
  r.validite_debut,
  r.validite_fin
FROM
  mv_role r
  LEFT JOIN personnel p ON p.source_code = r.z_personnel_id
  LEFT JOIN structure s ON s.source_code = r.z_structure_id
  LEFT JOIN type_role tr ON tr.code = r.z_type_id
WHERE
  s.id IS NULL -- rôle global
  OR (
    (
      EXISTS (SELECT * FROM element_pedagogique ep WHERE EP.STRUCTURE_ID = NVL(s.STRUCTURE_NIV2_ID,s.id)) -- soit une resp. dans une composante d'enseignement
      OR r.z_type_id IN ('responsable-recherche-labo')                                                    -- soit un responsable de labo
    )
    AND s.niveau <= 2
  );
--------------------------------------------------------
--  DDL for View SRC_STRUCTURE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_STRUCTURE" ("ID", "LIBELLE_LONG", "LIBELLE_COURT", "PARENTE_ID", "STRUCTURE_NIV2_ID", "TYPE_ID", "ETABLISSEMENT_ID", "NIVEAU", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN") AS 
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
  S.SOURCE_CODE,
  S.VALIDITE_DEBUT,
  S.VALIDITE_FIN
FROM
  mv_structure s
  JOIN type_structure ts on ts.code = S.Z_TYPE_ID
  LEFT JOIN structure sp on (sp.source_code = s.z_parente_id)
  LEFT JOIN structure s2 on (s2.source_code = s.z_structure_niv2_id);
--------------------------------------------------------
--  DDL for View SRC_TYPE_FORMATION
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_TYPE_FORMATION" ("ID", "LIBELLE_LONG", "LIBELLE_COURT", "GROUPE_ID", "SOURCE_ID", "SOURCE_CODE") AS 
  SELECT
  null id,
  tf.libelle_long,
  tf.libelle_court,
  gtf.id groupe_id,
  tf.source_id,
  tf.source_code
FROM
  MV_TYPE_FORMATION tf
  LEFT JOIN GROUPE_TYPE_FORMATION gtf ON gtf.source_code = TF.Z_GROUPE_ID;
--------------------------------------------------------
--  DDL for View SRC_TYPE_INTERVENTION_EP
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_TYPE_INTERVENTION_EP" ("ID", "TYPE_INTERVENTION_ID", "ELEMENT_PEDAGOGIQUE_ID", "VISIBLE", "SOURCE_ID", "SOURCE_CODE") AS 
  SELECT
  null id,
  ti.id type_intervention_id,
  ep.id element_pedagogique_id,
  1 visible,
  ose_import.get_source_id('Calcul') source_id,
  ti.code || '_' || ep.source_code source_code
FROM
  element_pedagogique ep
  JOIN type_intervention ti ON ti.code = 'FOAD' AND ti.histo_destruction IS NULL
  JOIN structure s ON s.id = ep.structure_id
WHERE
  ep.taux_foad > 0
  AND ep.histo_destruction IS NULL
  AND s.source_code IN ('U07','U08','U04');
--------------------------------------------------------
--  DDL for View SRC_VOLUME_HORAIRE_ENS
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_VOLUME_HORAIRE_ENS" ("ID", "ELEMENT_DISCIPLINE_ID", "TYPE_INTERVENTION_ID", "ANNEE_ID", "HEURES", "SOURCE_ID", "SOURCE_CODE") AS 
  SELECT
  null id,
  ed.id ELEMENT_DISCIPLINE_ID,
  ti.id TYPE_INTERVENTION_ID,
  to_number(vh.z_annee_id) annee_id,
  vh.heures,
  vh.source_id,
  vh.SOURCE_CODE
FROM
  MV_VOLUME_HORAIRE_ENS vh
  LEFT JOIN ELEMENT_DISCIPLINE ed ON ed.source_code = VH.Z_ELEMENT_DISCIPLINE_ID
  LEFT JOIN TYPE_INTERVENTION ti ON TI.CODE = VH.Z_TYPE_INTERVENTION_ID;
--------------------------------------------------------
--  DDL for View V_CTL_SERVICES_ODF_HISTO
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_CTL_SERVICES_ODF_HISTO" ("PRENOM", "NOM_USUEL", "ELEMENT", "ETAPE", "TYPE_INTERVENTION", "HEURES", "HAS_CONTRAT", "HAS_VALIDATION", "ELEMENT_SUPPRIME", "ETAPE_SUPPRIMEE", "ETABLISSEMENT_SUPPRIME") AS 
  with vh as (
  SELECT
    vh.service_id,
    ti.code type_intervention,
    SUM(heures) heures,
    CASE WHEN vh.contrat_id IS NULL THEN 0 ELSE 1 END has_contrat,
    CASE WHEN (SELECT COUNT(*) FROM validation_vol_horaire vvh WHERE vvh.volume_horaire_id = vh.id) = 1 THEN 1 ELSE 0 END has_validation
  FROM
    volume_horaire vh
    JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id
    JOIN type_intervention ti ON ti.id = vh.type_intervention_id
  WHERE
    vh.histo_destruction is null
    AND tvh.code = 'PREVU'
  GROUP BY
    vh.id, ti.code, vh.service_id, vh.contrat_id
)
SELECT
  i.prenom, i.nom_usuel,
  ep.source_code "ELEMENT",
  e.source_code etape,

  vh.type_intervention,
  vh.heures,
  vh.has_contrat,
  vh.has_validation,
  CASE WHEN ep.histo_destruction IS NOT NULL THEN 1 ELSE 0 END element_supprime,
  CASE WHEN e.histo_destruction IS NOT NULL THEN 1 ELSE 0 END etape_supprimee,
  CASE WHEN et.histo_destruction IS NOT NULL THEN 1 ELSE 0 END etablissement_supprime
FROM
  service s
  JOIN intervenant i ON i.id = s.intervenant_id
  LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN etape e ON e.id = ep.etape_id
  LEFT JOIN etablissement et ON et.id = s.etablissement_id
  LEFT JOIN vh ON vh.service_id = s.id
WHERE
  s.histo_destruction IS NULL
  AND (
    (ep.id IS NOT NULL AND ep.histo_destruction IS NOT NULL)
    OR
    (e.id IS NOT NULL AND e.histo_destruction IS NOT NULL)
    OR
    (et.id IS NOT NULL AND et.histo_destruction IS NOT NULL)
  )
order by
  nom_usuel, prenom, etape, "ELEMENT", heures;
--------------------------------------------------------
--  DDL for View V_CTL_VH_MAUVAIS_SEMESTRE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_CTL_VH_MAUVAIS_SEMESTRE" ("ID", "NOM_USUEL", "PRENOM", "HEURES", "VALIDATION_ID") AS 
  SELECT
  vh.id,
  i.nom_usuel, i.prenom,
  vh.heures,
  vvh.validation_id
FROM
  volume_horaire vh
  JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id
  JOIN service s ON s.id = vh.service_id
  JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
  JOIN intervenant i ON i.id = s.intervenant_id
  LEFT JOIN VALIDATION_VOL_HORAIRE vvh on VVH.VOLUME_HORAIRE_ID = vh.id
WHERE
  tvh.code = 'PREVU'
  AND ep.periode_id IS NOT NULL
  AND vh.periode_id <> ep.periode_id
ORDER BY
  nom_usuel, prenom, heures;
--------------------------------------------------------
--  DDL for View V_DIFF_ADRESSE_INTERVENANT
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_ADRESSE_INTERVENANT" ("ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "CODE_POSTAL", "INTERVENANT_ID", "LOCALITE", "MENTION_COMPLEMENTAIRE", "NOM_VOIE", "NO_VOIE", "PAYS_CODE_INSEE", "PAYS_LIBELLE", "PRINCIPALE", "TEL_DOMICILE", "VALIDITE_DEBUT", "VILLE", "U_CODE_POSTAL", "U_INTERVENANT_ID", "U_LOCALITE", "U_MENTION_COMPLEMENTAIRE", "U_NOM_VOIE", "U_NO_VOIE", "U_PAYS_CODE_INSEE", "U_PAYS_LIBELLE", "U_PRINCIPALE", "U_TEL_DOMICILE", "U_VALIDITE_DEBUT", "U_VILLE") AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."CODE_POSTAL",diff."INTERVENANT_ID",diff."LOCALITE",diff."MENTION_COMPLEMENTAIRE",diff."NOM_VOIE",diff."NO_VOIE",diff."PAYS_CODE_INSEE",diff."PAYS_LIBELLE",diff."PRINCIPALE",diff."TEL_DOMICILE",diff."VALIDITE_DEBUT",diff."VILLE",diff."U_CODE_POSTAL",diff."U_INTERVENANT_ID",diff."U_LOCALITE",diff."U_MENTION_COMPLEMENTAIRE",diff."U_NOM_VOIE",diff."U_NO_VOIE",diff."U_PAYS_CODE_INSEE",diff."U_PAYS_LIBELLE",diff."U_PRINCIPALE",diff."U_TEL_DOMICILE",diff."U_VALIDITE_DEBUT",diff."U_VILLE" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CODE_POSTAL ELSE S.CODE_POSTAL END CODE_POSTAL,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.INTERVENANT_ID ELSE S.INTERVENANT_ID END INTERVENANT_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LOCALITE ELSE S.LOCALITE END LOCALITE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.MENTION_COMPLEMENTAIRE ELSE S.MENTION_COMPLEMENTAIRE END MENTION_COMPLEMENTAIRE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NOM_VOIE ELSE S.NOM_VOIE END NOM_VOIE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NO_VOIE ELSE S.NO_VOIE END NO_VOIE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PAYS_CODE_INSEE ELSE S.PAYS_CODE_INSEE END PAYS_CODE_INSEE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PAYS_LIBELLE ELSE S.PAYS_LIBELLE END PAYS_LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PRINCIPALE ELSE S.PRINCIPALE END PRINCIPALE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TEL_DOMICILE ELSE S.TEL_DOMICILE END TEL_DOMICILE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_DEBUT ELSE S.VALIDITE_DEBUT END VALIDITE_DEBUT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VILLE ELSE S.VILLE END VILLE,
    CASE WHEN D.CODE_POSTAL <> S.CODE_POSTAL OR (D.CODE_POSTAL IS NULL AND S.CODE_POSTAL IS NOT NULL) OR (D.CODE_POSTAL IS NOT NULL AND S.CODE_POSTAL IS NULL) THEN 1 ELSE 0 END U_CODE_POSTAL,
    CASE WHEN D.INTERVENANT_ID <> S.INTERVENANT_ID OR (D.INTERVENANT_ID IS NULL AND S.INTERVENANT_ID IS NOT NULL) OR (D.INTERVENANT_ID IS NOT NULL AND S.INTERVENANT_ID IS NULL) THEN 1 ELSE 0 END U_INTERVENANT_ID,
    CASE WHEN D.LOCALITE <> S.LOCALITE OR (D.LOCALITE IS NULL AND S.LOCALITE IS NOT NULL) OR (D.LOCALITE IS NOT NULL AND S.LOCALITE IS NULL) THEN 1 ELSE 0 END U_LOCALITE,
    CASE WHEN D.MENTION_COMPLEMENTAIRE <> S.MENTION_COMPLEMENTAIRE OR (D.MENTION_COMPLEMENTAIRE IS NULL AND S.MENTION_COMPLEMENTAIRE IS NOT NULL) OR (D.MENTION_COMPLEMENTAIRE IS NOT NULL AND S.MENTION_COMPLEMENTAIRE IS NULL) THEN 1 ELSE 0 END U_MENTION_COMPLEMENTAIRE,
    CASE WHEN D.NOM_VOIE <> S.NOM_VOIE OR (D.NOM_VOIE IS NULL AND S.NOM_VOIE IS NOT NULL) OR (D.NOM_VOIE IS NOT NULL AND S.NOM_VOIE IS NULL) THEN 1 ELSE 0 END U_NOM_VOIE,
    CASE WHEN D.NO_VOIE <> S.NO_VOIE OR (D.NO_VOIE IS NULL AND S.NO_VOIE IS NOT NULL) OR (D.NO_VOIE IS NOT NULL AND S.NO_VOIE IS NULL) THEN 1 ELSE 0 END U_NO_VOIE,
    CASE WHEN D.PAYS_CODE_INSEE <> S.PAYS_CODE_INSEE OR (D.PAYS_CODE_INSEE IS NULL AND S.PAYS_CODE_INSEE IS NOT NULL) OR (D.PAYS_CODE_INSEE IS NOT NULL AND S.PAYS_CODE_INSEE IS NULL) THEN 1 ELSE 0 END U_PAYS_CODE_INSEE,
    CASE WHEN D.PAYS_LIBELLE <> S.PAYS_LIBELLE OR (D.PAYS_LIBELLE IS NULL AND S.PAYS_LIBELLE IS NOT NULL) OR (D.PAYS_LIBELLE IS NOT NULL AND S.PAYS_LIBELLE IS NULL) THEN 1 ELSE 0 END U_PAYS_LIBELLE,
    CASE WHEN D.PRINCIPALE <> S.PRINCIPALE OR (D.PRINCIPALE IS NULL AND S.PRINCIPALE IS NOT NULL) OR (D.PRINCIPALE IS NOT NULL AND S.PRINCIPALE IS NULL) THEN 1 ELSE 0 END U_PRINCIPALE,
    CASE WHEN D.TEL_DOMICILE <> S.TEL_DOMICILE OR (D.TEL_DOMICILE IS NULL AND S.TEL_DOMICILE IS NOT NULL) OR (D.TEL_DOMICILE IS NOT NULL AND S.TEL_DOMICILE IS NULL) THEN 1 ELSE 0 END U_TEL_DOMICILE,
    CASE WHEN D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL) THEN 1 ELSE 0 END U_VALIDITE_DEBUT,
    CASE WHEN D.VILLE <> S.VILLE OR (D.VILLE IS NULL AND S.VILLE IS NOT NULL) OR (D.VILLE IS NOT NULL AND S.VILLE IS NULL) THEN 1 ELSE 0 END U_VILLE
FROM
  ADRESSE_INTERVENANT D
  FULL JOIN SRC_ADRESSE_INTERVENANT S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.CODE_POSTAL <> S.CODE_POSTAL OR (D.CODE_POSTAL IS NULL AND S.CODE_POSTAL IS NOT NULL) OR (D.CODE_POSTAL IS NOT NULL AND S.CODE_POSTAL IS NULL)
  OR D.INTERVENANT_ID <> S.INTERVENANT_ID OR (D.INTERVENANT_ID IS NULL AND S.INTERVENANT_ID IS NOT NULL) OR (D.INTERVENANT_ID IS NOT NULL AND S.INTERVENANT_ID IS NULL)
  OR D.LOCALITE <> S.LOCALITE OR (D.LOCALITE IS NULL AND S.LOCALITE IS NOT NULL) OR (D.LOCALITE IS NOT NULL AND S.LOCALITE IS NULL)
  OR D.MENTION_COMPLEMENTAIRE <> S.MENTION_COMPLEMENTAIRE OR (D.MENTION_COMPLEMENTAIRE IS NULL AND S.MENTION_COMPLEMENTAIRE IS NOT NULL) OR (D.MENTION_COMPLEMENTAIRE IS NOT NULL AND S.MENTION_COMPLEMENTAIRE IS NULL)
  OR D.NOM_VOIE <> S.NOM_VOIE OR (D.NOM_VOIE IS NULL AND S.NOM_VOIE IS NOT NULL) OR (D.NOM_VOIE IS NOT NULL AND S.NOM_VOIE IS NULL)
  OR D.NO_VOIE <> S.NO_VOIE OR (D.NO_VOIE IS NULL AND S.NO_VOIE IS NOT NULL) OR (D.NO_VOIE IS NOT NULL AND S.NO_VOIE IS NULL)
  OR D.PAYS_CODE_INSEE <> S.PAYS_CODE_INSEE OR (D.PAYS_CODE_INSEE IS NULL AND S.PAYS_CODE_INSEE IS NOT NULL) OR (D.PAYS_CODE_INSEE IS NOT NULL AND S.PAYS_CODE_INSEE IS NULL)
  OR D.PAYS_LIBELLE <> S.PAYS_LIBELLE OR (D.PAYS_LIBELLE IS NULL AND S.PAYS_LIBELLE IS NOT NULL) OR (D.PAYS_LIBELLE IS NOT NULL AND S.PAYS_LIBELLE IS NULL)
  OR D.PRINCIPALE <> S.PRINCIPALE OR (D.PRINCIPALE IS NULL AND S.PRINCIPALE IS NOT NULL) OR (D.PRINCIPALE IS NOT NULL AND S.PRINCIPALE IS NULL)
  OR D.TEL_DOMICILE <> S.TEL_DOMICILE OR (D.TEL_DOMICILE IS NULL AND S.TEL_DOMICILE IS NOT NULL) OR (D.TEL_DOMICILE IS NOT NULL AND S.TEL_DOMICILE IS NULL)
  OR D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL)
  OR D.VILLE <> S.VILLE OR (D.VILLE IS NULL AND S.VILLE IS NOT NULL) OR (D.VILLE IS NOT NULL AND S.VILLE IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
--------------------------------------------------------
--  DDL for View V_DIFF_ADRESSE_STRUCTURE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_ADRESSE_STRUCTURE" ("ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "CODE_POSTAL", "LOCALITE", "NOM_VOIE", "NO_VOIE", "PAYS_CODE_INSEE", "PAYS_LIBELLE", "PRINCIPALE", "STRUCTURE_ID", "TELEPHONE", "VALIDITE_DEBUT", "VALIDITE_FIN", "VILLE", "U_CODE_POSTAL", "U_LOCALITE", "U_NOM_VOIE", "U_NO_VOIE", "U_PAYS_CODE_INSEE", "U_PAYS_LIBELLE", "U_PRINCIPALE", "U_STRUCTURE_ID", "U_TELEPHONE", "U_VALIDITE_DEBUT", "U_VALIDITE_FIN", "U_VILLE") AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."CODE_POSTAL",diff."LOCALITE",diff."NOM_VOIE",diff."NO_VOIE",diff."PAYS_CODE_INSEE",diff."PAYS_LIBELLE",diff."PRINCIPALE",diff."STRUCTURE_ID",diff."TELEPHONE",diff."VALIDITE_DEBUT",diff."VALIDITE_FIN",diff."VILLE",diff."U_CODE_POSTAL",diff."U_LOCALITE",diff."U_NOM_VOIE",diff."U_NO_VOIE",diff."U_PAYS_CODE_INSEE",diff."U_PAYS_LIBELLE",diff."U_PRINCIPALE",diff."U_STRUCTURE_ID",diff."U_TELEPHONE",diff."U_VALIDITE_DEBUT",diff."U_VALIDITE_FIN",diff."U_VILLE" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CODE_POSTAL ELSE S.CODE_POSTAL END CODE_POSTAL,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LOCALITE ELSE S.LOCALITE END LOCALITE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NOM_VOIE ELSE S.NOM_VOIE END NOM_VOIE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NO_VOIE ELSE S.NO_VOIE END NO_VOIE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PAYS_CODE_INSEE ELSE S.PAYS_CODE_INSEE END PAYS_CODE_INSEE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PAYS_LIBELLE ELSE S.PAYS_LIBELLE END PAYS_LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PRINCIPALE ELSE S.PRINCIPALE END PRINCIPALE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_ID ELSE S.STRUCTURE_ID END STRUCTURE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TELEPHONE ELSE S.TELEPHONE END TELEPHONE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_DEBUT ELSE S.VALIDITE_DEBUT END VALIDITE_DEBUT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_FIN ELSE S.VALIDITE_FIN END VALIDITE_FIN,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VILLE ELSE S.VILLE END VILLE,
    CASE WHEN D.CODE_POSTAL <> S.CODE_POSTAL OR (D.CODE_POSTAL IS NULL AND S.CODE_POSTAL IS NOT NULL) OR (D.CODE_POSTAL IS NOT NULL AND S.CODE_POSTAL IS NULL) THEN 1 ELSE 0 END U_CODE_POSTAL,
    CASE WHEN D.LOCALITE <> S.LOCALITE OR (D.LOCALITE IS NULL AND S.LOCALITE IS NOT NULL) OR (D.LOCALITE IS NOT NULL AND S.LOCALITE IS NULL) THEN 1 ELSE 0 END U_LOCALITE,
    CASE WHEN D.NOM_VOIE <> S.NOM_VOIE OR (D.NOM_VOIE IS NULL AND S.NOM_VOIE IS NOT NULL) OR (D.NOM_VOIE IS NOT NULL AND S.NOM_VOIE IS NULL) THEN 1 ELSE 0 END U_NOM_VOIE,
    CASE WHEN D.NO_VOIE <> S.NO_VOIE OR (D.NO_VOIE IS NULL AND S.NO_VOIE IS NOT NULL) OR (D.NO_VOIE IS NOT NULL AND S.NO_VOIE IS NULL) THEN 1 ELSE 0 END U_NO_VOIE,
    CASE WHEN D.PAYS_CODE_INSEE <> S.PAYS_CODE_INSEE OR (D.PAYS_CODE_INSEE IS NULL AND S.PAYS_CODE_INSEE IS NOT NULL) OR (D.PAYS_CODE_INSEE IS NOT NULL AND S.PAYS_CODE_INSEE IS NULL) THEN 1 ELSE 0 END U_PAYS_CODE_INSEE,
    CASE WHEN D.PAYS_LIBELLE <> S.PAYS_LIBELLE OR (D.PAYS_LIBELLE IS NULL AND S.PAYS_LIBELLE IS NOT NULL) OR (D.PAYS_LIBELLE IS NOT NULL AND S.PAYS_LIBELLE IS NULL) THEN 1 ELSE 0 END U_PAYS_LIBELLE,
    CASE WHEN D.PRINCIPALE <> S.PRINCIPALE OR (D.PRINCIPALE IS NULL AND S.PRINCIPALE IS NOT NULL) OR (D.PRINCIPALE IS NOT NULL AND S.PRINCIPALE IS NULL) THEN 1 ELSE 0 END U_PRINCIPALE,
    CASE WHEN D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_ID,
    CASE WHEN D.TELEPHONE <> S.TELEPHONE OR (D.TELEPHONE IS NULL AND S.TELEPHONE IS NOT NULL) OR (D.TELEPHONE IS NOT NULL AND S.TELEPHONE IS NULL) THEN 1 ELSE 0 END U_TELEPHONE,
    CASE WHEN D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL) THEN 1 ELSE 0 END U_VALIDITE_DEBUT,
    CASE WHEN D.VALIDITE_FIN <> S.VALIDITE_FIN OR (D.VALIDITE_FIN IS NULL AND S.VALIDITE_FIN IS NOT NULL) OR (D.VALIDITE_FIN IS NOT NULL AND S.VALIDITE_FIN IS NULL) THEN 1 ELSE 0 END U_VALIDITE_FIN,
    CASE WHEN D.VILLE <> S.VILLE OR (D.VILLE IS NULL AND S.VILLE IS NOT NULL) OR (D.VILLE IS NOT NULL AND S.VILLE IS NULL) THEN 1 ELSE 0 END U_VILLE
FROM
  ADRESSE_STRUCTURE D
  FULL JOIN SRC_ADRESSE_STRUCTURE S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.CODE_POSTAL <> S.CODE_POSTAL OR (D.CODE_POSTAL IS NULL AND S.CODE_POSTAL IS NOT NULL) OR (D.CODE_POSTAL IS NOT NULL AND S.CODE_POSTAL IS NULL)
  OR D.LOCALITE <> S.LOCALITE OR (D.LOCALITE IS NULL AND S.LOCALITE IS NOT NULL) OR (D.LOCALITE IS NOT NULL AND S.LOCALITE IS NULL)
  OR D.NOM_VOIE <> S.NOM_VOIE OR (D.NOM_VOIE IS NULL AND S.NOM_VOIE IS NOT NULL) OR (D.NOM_VOIE IS NOT NULL AND S.NOM_VOIE IS NULL)
  OR D.NO_VOIE <> S.NO_VOIE OR (D.NO_VOIE IS NULL AND S.NO_VOIE IS NOT NULL) OR (D.NO_VOIE IS NOT NULL AND S.NO_VOIE IS NULL)
  OR D.PAYS_CODE_INSEE <> S.PAYS_CODE_INSEE OR (D.PAYS_CODE_INSEE IS NULL AND S.PAYS_CODE_INSEE IS NOT NULL) OR (D.PAYS_CODE_INSEE IS NOT NULL AND S.PAYS_CODE_INSEE IS NULL)
  OR D.PAYS_LIBELLE <> S.PAYS_LIBELLE OR (D.PAYS_LIBELLE IS NULL AND S.PAYS_LIBELLE IS NOT NULL) OR (D.PAYS_LIBELLE IS NOT NULL AND S.PAYS_LIBELLE IS NULL)
  OR D.PRINCIPALE <> S.PRINCIPALE OR (D.PRINCIPALE IS NULL AND S.PRINCIPALE IS NOT NULL) OR (D.PRINCIPALE IS NOT NULL AND S.PRINCIPALE IS NULL)
  OR D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL)
  OR D.TELEPHONE <> S.TELEPHONE OR (D.TELEPHONE IS NULL AND S.TELEPHONE IS NOT NULL) OR (D.TELEPHONE IS NOT NULL AND S.TELEPHONE IS NULL)
  OR D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL)
  OR D.VALIDITE_FIN <> S.VALIDITE_FIN OR (D.VALIDITE_FIN IS NULL AND S.VALIDITE_FIN IS NOT NULL) OR (D.VALIDITE_FIN IS NOT NULL AND S.VALIDITE_FIN IS NULL)
  OR D.VILLE <> S.VILLE OR (D.VILLE IS NULL AND S.VILLE IS NOT NULL) OR (D.VILLE IS NOT NULL AND S.VILLE IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
--------------------------------------------------------
--  DDL for View V_DIFF_AFFECTATION_RECHERCHE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_AFFECTATION_RECHERCHE" ("ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "INTERVENANT_ID", "STRUCTURE_ID", "VALIDITE_DEBUT", "VALIDITE_FIN", "U_INTERVENANT_ID", "U_STRUCTURE_ID", "U_VALIDITE_DEBUT", "U_VALIDITE_FIN") AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."INTERVENANT_ID",diff."STRUCTURE_ID",diff."VALIDITE_DEBUT",diff."VALIDITE_FIN",diff."U_INTERVENANT_ID",diff."U_STRUCTURE_ID",diff."U_VALIDITE_DEBUT",diff."U_VALIDITE_FIN" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.INTERVENANT_ID ELSE S.INTERVENANT_ID END INTERVENANT_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_ID ELSE S.STRUCTURE_ID END STRUCTURE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_DEBUT ELSE S.VALIDITE_DEBUT END VALIDITE_DEBUT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_FIN ELSE S.VALIDITE_FIN END VALIDITE_FIN,
    CASE WHEN D.INTERVENANT_ID <> S.INTERVENANT_ID OR (D.INTERVENANT_ID IS NULL AND S.INTERVENANT_ID IS NOT NULL) OR (D.INTERVENANT_ID IS NOT NULL AND S.INTERVENANT_ID IS NULL) THEN 1 ELSE 0 END U_INTERVENANT_ID,
    CASE WHEN D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_ID,
    CASE WHEN D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL) THEN 1 ELSE 0 END U_VALIDITE_DEBUT,
    CASE WHEN D.VALIDITE_FIN <> S.VALIDITE_FIN OR (D.VALIDITE_FIN IS NULL AND S.VALIDITE_FIN IS NOT NULL) OR (D.VALIDITE_FIN IS NOT NULL AND S.VALIDITE_FIN IS NULL) THEN 1 ELSE 0 END U_VALIDITE_FIN
FROM
  AFFECTATION_RECHERCHE D
  FULL JOIN SRC_AFFECTATION_RECHERCHE S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.INTERVENANT_ID <> S.INTERVENANT_ID OR (D.INTERVENANT_ID IS NULL AND S.INTERVENANT_ID IS NOT NULL) OR (D.INTERVENANT_ID IS NOT NULL AND S.INTERVENANT_ID IS NULL)
  OR D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL)
  OR D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL)
  OR D.VALIDITE_FIN <> S.VALIDITE_FIN OR (D.VALIDITE_FIN IS NULL AND S.VALIDITE_FIN IS NOT NULL) OR (D.VALIDITE_FIN IS NOT NULL AND S.VALIDITE_FIN IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
--------------------------------------------------------
--  DDL for View V_DIFF_CHEMIN_PEDAGOGIQUE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_CHEMIN_PEDAGOGIQUE" ("ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ELEMENT_PEDAGOGIQUE_ID", "ETAPE_ID", "ORDRE", "VALIDITE_DEBUT", "VALIDITE_FIN", "U_ELEMENT_PEDAGOGIQUE_ID", "U_ETAPE_ID", "U_ORDRE", "U_VALIDITE_DEBUT", "U_VALIDITE_FIN") AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ELEMENT_PEDAGOGIQUE_ID",diff."ETAPE_ID",diff."ORDRE",diff."VALIDITE_DEBUT",diff."VALIDITE_FIN",diff."U_ELEMENT_PEDAGOGIQUE_ID",diff."U_ETAPE_ID",diff."U_ORDRE",diff."U_VALIDITE_DEBUT",diff."U_VALIDITE_FIN" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ELEMENT_PEDAGOGIQUE_ID ELSE S.ELEMENT_PEDAGOGIQUE_ID END ELEMENT_PEDAGOGIQUE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ETAPE_ID ELSE S.ETAPE_ID END ETAPE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ORDRE ELSE S.ORDRE END ORDRE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_DEBUT ELSE S.VALIDITE_DEBUT END VALIDITE_DEBUT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_FIN ELSE S.VALIDITE_FIN END VALIDITE_FIN,
    CASE WHEN D.ELEMENT_PEDAGOGIQUE_ID <> S.ELEMENT_PEDAGOGIQUE_ID OR (D.ELEMENT_PEDAGOGIQUE_ID IS NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL) OR (D.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NULL) THEN 1 ELSE 0 END U_ELEMENT_PEDAGOGIQUE_ID,
    CASE WHEN D.ETAPE_ID <> S.ETAPE_ID OR (D.ETAPE_ID IS NULL AND S.ETAPE_ID IS NOT NULL) OR (D.ETAPE_ID IS NOT NULL AND S.ETAPE_ID IS NULL) THEN 1 ELSE 0 END U_ETAPE_ID,
    CASE WHEN D.ORDRE <> S.ORDRE OR (D.ORDRE IS NULL AND S.ORDRE IS NOT NULL) OR (D.ORDRE IS NOT NULL AND S.ORDRE IS NULL) THEN 1 ELSE 0 END U_ORDRE,
    CASE WHEN D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL) THEN 1 ELSE 0 END U_VALIDITE_DEBUT,
    CASE WHEN D.VALIDITE_FIN <> S.VALIDITE_FIN OR (D.VALIDITE_FIN IS NULL AND S.VALIDITE_FIN IS NOT NULL) OR (D.VALIDITE_FIN IS NOT NULL AND S.VALIDITE_FIN IS NULL) THEN 1 ELSE 0 END U_VALIDITE_FIN
FROM
  CHEMIN_PEDAGOGIQUE D
  FULL JOIN SRC_CHEMIN_PEDAGOGIQUE S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ELEMENT_PEDAGOGIQUE_ID <> S.ELEMENT_PEDAGOGIQUE_ID OR (D.ELEMENT_PEDAGOGIQUE_ID IS NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL) OR (D.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NULL)
  OR D.ETAPE_ID <> S.ETAPE_ID OR (D.ETAPE_ID IS NULL AND S.ETAPE_ID IS NOT NULL) OR (D.ETAPE_ID IS NOT NULL AND S.ETAPE_ID IS NULL)
  OR D.ORDRE <> S.ORDRE OR (D.ORDRE IS NULL AND S.ORDRE IS NOT NULL) OR (D.ORDRE IS NOT NULL AND S.ORDRE IS NULL)
  OR D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL)
  OR D.VALIDITE_FIN <> S.VALIDITE_FIN OR (D.VALIDITE_FIN IS NULL AND S.VALIDITE_FIN IS NOT NULL) OR (D.VALIDITE_FIN IS NOT NULL AND S.VALIDITE_FIN IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
--------------------------------------------------------
--  DDL for View V_DIFF_CORPS
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_CORPS" ("ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "LIBELLE_COURT", "LIBELLE_LONG", "VALIDITE_DEBUT", "VALIDITE_FIN", "U_LIBELLE_COURT", "U_LIBELLE_LONG", "U_VALIDITE_DEBUT", "U_VALIDITE_FIN") AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."LIBELLE_COURT",diff."LIBELLE_LONG",diff."VALIDITE_DEBUT",diff."VALIDITE_FIN",diff."U_LIBELLE_COURT",diff."U_LIBELLE_LONG",diff."U_VALIDITE_DEBUT",diff."U_VALIDITE_FIN" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_COURT ELSE S.LIBELLE_COURT END LIBELLE_COURT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_LONG ELSE S.LIBELLE_LONG END LIBELLE_LONG,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_DEBUT ELSE S.VALIDITE_DEBUT END VALIDITE_DEBUT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_FIN ELSE S.VALIDITE_FIN END VALIDITE_FIN,
    CASE WHEN D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL) THEN 1 ELSE 0 END U_LIBELLE_COURT,
    CASE WHEN D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL) THEN 1 ELSE 0 END U_LIBELLE_LONG,
    CASE WHEN D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL) THEN 1 ELSE 0 END U_VALIDITE_DEBUT,
    CASE WHEN D.VALIDITE_FIN <> S.VALIDITE_FIN OR (D.VALIDITE_FIN IS NULL AND S.VALIDITE_FIN IS NOT NULL) OR (D.VALIDITE_FIN IS NOT NULL AND S.VALIDITE_FIN IS NULL) THEN 1 ELSE 0 END U_VALIDITE_FIN
FROM
  CORPS D
  FULL JOIN SRC_CORPS S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL)
  OR D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL)
  OR D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL)
  OR D.VALIDITE_FIN <> S.VALIDITE_FIN OR (D.VALIDITE_FIN IS NULL AND S.VALIDITE_FIN IS NOT NULL) OR (D.VALIDITE_FIN IS NOT NULL AND S.VALIDITE_FIN IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
--------------------------------------------------------
--  DDL for View V_DIFF_DISCIPLINE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_DISCIPLINE" ("ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "LIBELLE_COURT", "LIBELLE_LONG", "ORDRE", "U_LIBELLE_COURT", "U_LIBELLE_LONG", "U_ORDRE") AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."LIBELLE_COURT",diff."LIBELLE_LONG",diff."ORDRE",diff."U_LIBELLE_COURT",diff."U_LIBELLE_LONG",diff."U_ORDRE" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_COURT ELSE S.LIBELLE_COURT END LIBELLE_COURT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_LONG ELSE S.LIBELLE_LONG END LIBELLE_LONG,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ORDRE ELSE S.ORDRE END ORDRE,
    CASE WHEN D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL) THEN 1 ELSE 0 END U_LIBELLE_COURT,
    CASE WHEN D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL) THEN 1 ELSE 0 END U_LIBELLE_LONG,
    CASE WHEN D.ORDRE <> S.ORDRE OR (D.ORDRE IS NULL AND S.ORDRE IS NOT NULL) OR (D.ORDRE IS NOT NULL AND S.ORDRE IS NULL) THEN 1 ELSE 0 END U_ORDRE
FROM
  DISCIPLINE D
  FULL JOIN SRC_DISCIPLINE S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL)
  OR D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL)
  OR D.ORDRE <> S.ORDRE OR (D.ORDRE IS NULL AND S.ORDRE IS NOT NULL) OR (D.ORDRE IS NOT NULL AND S.ORDRE IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
--------------------------------------------------------
--  DDL for View V_DIFF_ELEMENT_DISCIPLINE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_ELEMENT_DISCIPLINE" ("ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "DISCIPLINE_ID", "ELEMENT_PEDAGOGIQUE_ID", "U_DISCIPLINE_ID", "U_ELEMENT_PEDAGOGIQUE_ID") AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."DISCIPLINE_ID",diff."ELEMENT_PEDAGOGIQUE_ID",diff."U_DISCIPLINE_ID",diff."U_ELEMENT_PEDAGOGIQUE_ID" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DISCIPLINE_ID ELSE S.DISCIPLINE_ID END DISCIPLINE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ELEMENT_PEDAGOGIQUE_ID ELSE S.ELEMENT_PEDAGOGIQUE_ID END ELEMENT_PEDAGOGIQUE_ID,
    CASE WHEN D.DISCIPLINE_ID <> S.DISCIPLINE_ID OR (D.DISCIPLINE_ID IS NULL AND S.DISCIPLINE_ID IS NOT NULL) OR (D.DISCIPLINE_ID IS NOT NULL AND S.DISCIPLINE_ID IS NULL) THEN 1 ELSE 0 END U_DISCIPLINE_ID,
    CASE WHEN D.ELEMENT_PEDAGOGIQUE_ID <> S.ELEMENT_PEDAGOGIQUE_ID OR (D.ELEMENT_PEDAGOGIQUE_ID IS NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL) OR (D.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NULL) THEN 1 ELSE 0 END U_ELEMENT_PEDAGOGIQUE_ID
FROM
  ELEMENT_DISCIPLINE D
  FULL JOIN SRC_ELEMENT_DISCIPLINE S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.DISCIPLINE_ID <> S.DISCIPLINE_ID OR (D.DISCIPLINE_ID IS NULL AND S.DISCIPLINE_ID IS NOT NULL) OR (D.DISCIPLINE_ID IS NOT NULL AND S.DISCIPLINE_ID IS NULL)
  OR D.ELEMENT_PEDAGOGIQUE_ID <> S.ELEMENT_PEDAGOGIQUE_ID OR (D.ELEMENT_PEDAGOGIQUE_ID IS NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL) OR (D.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
--------------------------------------------------------
--  DDL for View V_DIFF_ELEMENT_PEDAGOGIQUE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_ELEMENT_PEDAGOGIQUE" ("ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ETAPE_ID", "FA", "FC", "FI", "LIBELLE", "PERIODE_ID", "STRUCTURE_ID", "TAUX_FA", "TAUX_FC", "TAUX_FI", "TAUX_FOAD", "U_ETAPE_ID", "U_FA", "U_FC", "U_FI", "U_LIBELLE", "U_PERIODE_ID", "U_STRUCTURE_ID", "U_TAUX_FA", "U_TAUX_FC", "U_TAUX_FI", "U_TAUX_FOAD") AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ETAPE_ID",diff."FA",diff."FC",diff."FI",diff."LIBELLE",diff."PERIODE_ID",diff."STRUCTURE_ID",diff."TAUX_FA",diff."TAUX_FC",diff."TAUX_FI",diff."TAUX_FOAD",diff."U_ETAPE_ID",diff."U_FA",diff."U_FC",diff."U_FI",diff."U_LIBELLE",diff."U_PERIODE_ID",diff."U_STRUCTURE_ID",diff."U_TAUX_FA",diff."U_TAUX_FC",diff."U_TAUX_FI",diff."U_TAUX_FOAD" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ETAPE_ID ELSE S.ETAPE_ID END ETAPE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.FA ELSE S.FA END FA,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.FC ELSE S.FC END FC,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.FI ELSE S.FI END FI,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE ELSE S.LIBELLE END LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PERIODE_ID ELSE S.PERIODE_ID END PERIODE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_ID ELSE S.STRUCTURE_ID END STRUCTURE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TAUX_FA ELSE S.TAUX_FA END TAUX_FA,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TAUX_FC ELSE S.TAUX_FC END TAUX_FC,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TAUX_FI ELSE S.TAUX_FI END TAUX_FI,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TAUX_FOAD ELSE S.TAUX_FOAD END TAUX_FOAD,
    CASE WHEN D.ETAPE_ID <> S.ETAPE_ID OR (D.ETAPE_ID IS NULL AND S.ETAPE_ID IS NOT NULL) OR (D.ETAPE_ID IS NOT NULL AND S.ETAPE_ID IS NULL) THEN 1 ELSE 0 END U_ETAPE_ID,
    CASE WHEN D.FA <> S.FA OR (D.FA IS NULL AND S.FA IS NOT NULL) OR (D.FA IS NOT NULL AND S.FA IS NULL) THEN 1 ELSE 0 END U_FA,
    CASE WHEN D.FC <> S.FC OR (D.FC IS NULL AND S.FC IS NOT NULL) OR (D.FC IS NOT NULL AND S.FC IS NULL) THEN 1 ELSE 0 END U_FC,
    CASE WHEN D.FI <> S.FI OR (D.FI IS NULL AND S.FI IS NOT NULL) OR (D.FI IS NOT NULL AND S.FI IS NULL) THEN 1 ELSE 0 END U_FI,
    CASE WHEN D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL) THEN 1 ELSE 0 END U_LIBELLE,
    CASE WHEN D.PERIODE_ID <> S.PERIODE_ID OR (D.PERIODE_ID IS NULL AND S.PERIODE_ID IS NOT NULL) OR (D.PERIODE_ID IS NOT NULL AND S.PERIODE_ID IS NULL) THEN 1 ELSE 0 END U_PERIODE_ID,
    CASE WHEN D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_ID,
    CASE WHEN D.TAUX_FA <> S.TAUX_FA OR (D.TAUX_FA IS NULL AND S.TAUX_FA IS NOT NULL) OR (D.TAUX_FA IS NOT NULL AND S.TAUX_FA IS NULL) THEN 1 ELSE 0 END U_TAUX_FA,
    CASE WHEN D.TAUX_FC <> S.TAUX_FC OR (D.TAUX_FC IS NULL AND S.TAUX_FC IS NOT NULL) OR (D.TAUX_FC IS NOT NULL AND S.TAUX_FC IS NULL) THEN 1 ELSE 0 END U_TAUX_FC,
    CASE WHEN D.TAUX_FI <> S.TAUX_FI OR (D.TAUX_FI IS NULL AND S.TAUX_FI IS NOT NULL) OR (D.TAUX_FI IS NOT NULL AND S.TAUX_FI IS NULL) THEN 1 ELSE 0 END U_TAUX_FI,
    CASE WHEN D.TAUX_FOAD <> S.TAUX_FOAD OR (D.TAUX_FOAD IS NULL AND S.TAUX_FOAD IS NOT NULL) OR (D.TAUX_FOAD IS NOT NULL AND S.TAUX_FOAD IS NULL) THEN 1 ELSE 0 END U_TAUX_FOAD
FROM
  ELEMENT_PEDAGOGIQUE D
  FULL JOIN SRC_ELEMENT_PEDAGOGIQUE S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ETAPE_ID <> S.ETAPE_ID OR (D.ETAPE_ID IS NULL AND S.ETAPE_ID IS NOT NULL) OR (D.ETAPE_ID IS NOT NULL AND S.ETAPE_ID IS NULL)
  OR D.FA <> S.FA OR (D.FA IS NULL AND S.FA IS NOT NULL) OR (D.FA IS NOT NULL AND S.FA IS NULL)
  OR D.FC <> S.FC OR (D.FC IS NULL AND S.FC IS NOT NULL) OR (D.FC IS NOT NULL AND S.FC IS NULL)
  OR D.FI <> S.FI OR (D.FI IS NULL AND S.FI IS NOT NULL) OR (D.FI IS NOT NULL AND S.FI IS NULL)
  OR D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL)
  OR D.PERIODE_ID <> S.PERIODE_ID OR (D.PERIODE_ID IS NULL AND S.PERIODE_ID IS NOT NULL) OR (D.PERIODE_ID IS NOT NULL AND S.PERIODE_ID IS NULL)
  OR D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL)
  OR D.TAUX_FA <> S.TAUX_FA OR (D.TAUX_FA IS NULL AND S.TAUX_FA IS NOT NULL) OR (D.TAUX_FA IS NOT NULL AND S.TAUX_FA IS NULL)
  OR D.TAUX_FC <> S.TAUX_FC OR (D.TAUX_FC IS NULL AND S.TAUX_FC IS NOT NULL) OR (D.TAUX_FC IS NOT NULL AND S.TAUX_FC IS NULL)
  OR D.TAUX_FI <> S.TAUX_FI OR (D.TAUX_FI IS NULL AND S.TAUX_FI IS NOT NULL) OR (D.TAUX_FI IS NOT NULL AND S.TAUX_FI IS NULL)
  OR D.TAUX_FOAD <> S.TAUX_FOAD OR (D.TAUX_FOAD IS NULL AND S.TAUX_FOAD IS NOT NULL) OR (D.TAUX_FOAD IS NOT NULL AND S.TAUX_FOAD IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
--------------------------------------------------------
--  DDL for View V_DIFF_ELEMENT_PORTEUR_PORTE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_ELEMENT_PORTEUR_PORTE" ("ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ELEMENT_PORTEUR_ID", "ELEMENT_PORTE_ID", "TYPE_INTERVENTION_ID", "U_ELEMENT_PORTEUR_ID", "U_ELEMENT_PORTE_ID", "U_TYPE_INTERVENTION_ID") AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ELEMENT_PORTEUR_ID",diff."ELEMENT_PORTE_ID",diff."TYPE_INTERVENTION_ID",diff."U_ELEMENT_PORTEUR_ID",diff."U_ELEMENT_PORTE_ID",diff."U_TYPE_INTERVENTION_ID" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ELEMENT_PORTEUR_ID ELSE S.ELEMENT_PORTEUR_ID END ELEMENT_PORTEUR_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ELEMENT_PORTE_ID ELSE S.ELEMENT_PORTE_ID END ELEMENT_PORTE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TYPE_INTERVENTION_ID ELSE S.TYPE_INTERVENTION_ID END TYPE_INTERVENTION_ID,
    CASE WHEN D.ELEMENT_PORTEUR_ID <> S.ELEMENT_PORTEUR_ID OR (D.ELEMENT_PORTEUR_ID IS NULL AND S.ELEMENT_PORTEUR_ID IS NOT NULL) OR (D.ELEMENT_PORTEUR_ID IS NOT NULL AND S.ELEMENT_PORTEUR_ID IS NULL) THEN 1 ELSE 0 END U_ELEMENT_PORTEUR_ID,
    CASE WHEN D.ELEMENT_PORTE_ID <> S.ELEMENT_PORTE_ID OR (D.ELEMENT_PORTE_ID IS NULL AND S.ELEMENT_PORTE_ID IS NOT NULL) OR (D.ELEMENT_PORTE_ID IS NOT NULL AND S.ELEMENT_PORTE_ID IS NULL) THEN 1 ELSE 0 END U_ELEMENT_PORTE_ID,
    CASE WHEN D.TYPE_INTERVENTION_ID <> S.TYPE_INTERVENTION_ID OR (D.TYPE_INTERVENTION_ID IS NULL AND S.TYPE_INTERVENTION_ID IS NOT NULL) OR (D.TYPE_INTERVENTION_ID IS NOT NULL AND S.TYPE_INTERVENTION_ID IS NULL) THEN 1 ELSE 0 END U_TYPE_INTERVENTION_ID
FROM
  ELEMENT_PORTEUR_PORTE D
  FULL JOIN SRC_ELEMENT_PORTEUR_PORTE S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ELEMENT_PORTEUR_ID <> S.ELEMENT_PORTEUR_ID OR (D.ELEMENT_PORTEUR_ID IS NULL AND S.ELEMENT_PORTEUR_ID IS NOT NULL) OR (D.ELEMENT_PORTEUR_ID IS NOT NULL AND S.ELEMENT_PORTEUR_ID IS NULL)
  OR D.ELEMENT_PORTE_ID <> S.ELEMENT_PORTE_ID OR (D.ELEMENT_PORTE_ID IS NULL AND S.ELEMENT_PORTE_ID IS NOT NULL) OR (D.ELEMENT_PORTE_ID IS NOT NULL AND S.ELEMENT_PORTE_ID IS NULL)
  OR D.TYPE_INTERVENTION_ID <> S.TYPE_INTERVENTION_ID OR (D.TYPE_INTERVENTION_ID IS NULL AND S.TYPE_INTERVENTION_ID IS NOT NULL) OR (D.TYPE_INTERVENTION_ID IS NOT NULL AND S.TYPE_INTERVENTION_ID IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
--------------------------------------------------------
--  DDL for View V_DIFF_ETABLISSEMENT
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_ETABLISSEMENT" ("ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "DEPARTEMENT", "LIBELLE", "LOCALISATION", "U_DEPARTEMENT", "U_LIBELLE", "U_LOCALISATION") AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."DEPARTEMENT",diff."LIBELLE",diff."LOCALISATION",diff."U_DEPARTEMENT",diff."U_LIBELLE",diff."U_LOCALISATION" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DEPARTEMENT ELSE S.DEPARTEMENT END DEPARTEMENT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE ELSE S.LIBELLE END LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LOCALISATION ELSE S.LOCALISATION END LOCALISATION,
    CASE WHEN D.DEPARTEMENT <> S.DEPARTEMENT OR (D.DEPARTEMENT IS NULL AND S.DEPARTEMENT IS NOT NULL) OR (D.DEPARTEMENT IS NOT NULL AND S.DEPARTEMENT IS NULL) THEN 1 ELSE 0 END U_DEPARTEMENT,
    CASE WHEN D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL) THEN 1 ELSE 0 END U_LIBELLE,
    CASE WHEN D.LOCALISATION <> S.LOCALISATION OR (D.LOCALISATION IS NULL AND S.LOCALISATION IS NOT NULL) OR (D.LOCALISATION IS NOT NULL AND S.LOCALISATION IS NULL) THEN 1 ELSE 0 END U_LOCALISATION
FROM
  ETABLISSEMENT D
  FULL JOIN SRC_ETABLISSEMENT S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.DEPARTEMENT <> S.DEPARTEMENT OR (D.DEPARTEMENT IS NULL AND S.DEPARTEMENT IS NOT NULL) OR (D.DEPARTEMENT IS NOT NULL AND S.DEPARTEMENT IS NULL)
  OR D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL)
  OR D.LOCALISATION <> S.LOCALISATION OR (D.LOCALISATION IS NULL AND S.LOCALISATION IS NOT NULL) OR (D.LOCALISATION IS NOT NULL AND S.LOCALISATION IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
--------------------------------------------------------
--  DDL for View V_DIFF_ETAPE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_ETAPE" ("ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "LIBELLE", "NIVEAU", "SPECIFIQUE_ECHANGES", "STRUCTURE_ID", "TYPE_FORMATION_ID", "VALIDITE_DEBUT", "VALIDITE_FIN", "U_LIBELLE", "U_NIVEAU", "U_SPECIFIQUE_ECHANGES", "U_STRUCTURE_ID", "U_TYPE_FORMATION_ID", "U_VALIDITE_DEBUT", "U_VALIDITE_FIN") AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."LIBELLE",diff."NIVEAU",diff."SPECIFIQUE_ECHANGES",diff."STRUCTURE_ID",diff."TYPE_FORMATION_ID",diff."VALIDITE_DEBUT",diff."VALIDITE_FIN",diff."U_LIBELLE",diff."U_NIVEAU",diff."U_SPECIFIQUE_ECHANGES",diff."U_STRUCTURE_ID",diff."U_TYPE_FORMATION_ID",diff."U_VALIDITE_DEBUT",diff."U_VALIDITE_FIN" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE ELSE S.LIBELLE END LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NIVEAU ELSE S.NIVEAU END NIVEAU,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.SPECIFIQUE_ECHANGES ELSE S.SPECIFIQUE_ECHANGES END SPECIFIQUE_ECHANGES,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_ID ELSE S.STRUCTURE_ID END STRUCTURE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TYPE_FORMATION_ID ELSE S.TYPE_FORMATION_ID END TYPE_FORMATION_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_DEBUT ELSE S.VALIDITE_DEBUT END VALIDITE_DEBUT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_FIN ELSE S.VALIDITE_FIN END VALIDITE_FIN,
    CASE WHEN D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL) THEN 1 ELSE 0 END U_LIBELLE,
    CASE WHEN D.NIVEAU <> S.NIVEAU OR (D.NIVEAU IS NULL AND S.NIVEAU IS NOT NULL) OR (D.NIVEAU IS NOT NULL AND S.NIVEAU IS NULL) THEN 1 ELSE 0 END U_NIVEAU,
    CASE WHEN D.SPECIFIQUE_ECHANGES <> S.SPECIFIQUE_ECHANGES OR (D.SPECIFIQUE_ECHANGES IS NULL AND S.SPECIFIQUE_ECHANGES IS NOT NULL) OR (D.SPECIFIQUE_ECHANGES IS NOT NULL AND S.SPECIFIQUE_ECHANGES IS NULL) THEN 1 ELSE 0 END U_SPECIFIQUE_ECHANGES,
    CASE WHEN D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_ID,
    CASE WHEN D.TYPE_FORMATION_ID <> S.TYPE_FORMATION_ID OR (D.TYPE_FORMATION_ID IS NULL AND S.TYPE_FORMATION_ID IS NOT NULL) OR (D.TYPE_FORMATION_ID IS NOT NULL AND S.TYPE_FORMATION_ID IS NULL) THEN 1 ELSE 0 END U_TYPE_FORMATION_ID,
    CASE WHEN D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL) THEN 1 ELSE 0 END U_VALIDITE_DEBUT,
    CASE WHEN D.VALIDITE_FIN <> S.VALIDITE_FIN OR (D.VALIDITE_FIN IS NULL AND S.VALIDITE_FIN IS NOT NULL) OR (D.VALIDITE_FIN IS NOT NULL AND S.VALIDITE_FIN IS NULL) THEN 1 ELSE 0 END U_VALIDITE_FIN
FROM
  ETAPE D
  FULL JOIN SRC_ETAPE S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL)
  OR D.NIVEAU <> S.NIVEAU OR (D.NIVEAU IS NULL AND S.NIVEAU IS NOT NULL) OR (D.NIVEAU IS NOT NULL AND S.NIVEAU IS NULL)
  OR D.SPECIFIQUE_ECHANGES <> S.SPECIFIQUE_ECHANGES OR (D.SPECIFIQUE_ECHANGES IS NULL AND S.SPECIFIQUE_ECHANGES IS NOT NULL) OR (D.SPECIFIQUE_ECHANGES IS NOT NULL AND S.SPECIFIQUE_ECHANGES IS NULL)
  OR D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL)
  OR D.TYPE_FORMATION_ID <> S.TYPE_FORMATION_ID OR (D.TYPE_FORMATION_ID IS NULL AND S.TYPE_FORMATION_ID IS NOT NULL) OR (D.TYPE_FORMATION_ID IS NOT NULL AND S.TYPE_FORMATION_ID IS NULL)
  OR D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL)
  OR D.VALIDITE_FIN <> S.VALIDITE_FIN OR (D.VALIDITE_FIN IS NULL AND S.VALIDITE_FIN IS NOT NULL) OR (D.VALIDITE_FIN IS NOT NULL AND S.VALIDITE_FIN IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
--------------------------------------------------------
--  DDL for View V_DIFF_GROUPE_TYPE_FORMATION
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_GROUPE_TYPE_FORMATION" ("ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "LIBELLE_COURT", "LIBELLE_LONG", "ORDRE", "PERTINENCE_NIVEAU", "U_LIBELLE_COURT", "U_LIBELLE_LONG", "U_ORDRE", "U_PERTINENCE_NIVEAU") AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."LIBELLE_COURT",diff."LIBELLE_LONG",diff."ORDRE",diff."PERTINENCE_NIVEAU",diff."U_LIBELLE_COURT",diff."U_LIBELLE_LONG",diff."U_ORDRE",diff."U_PERTINENCE_NIVEAU" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_COURT ELSE S.LIBELLE_COURT END LIBELLE_COURT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_LONG ELSE S.LIBELLE_LONG END LIBELLE_LONG,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ORDRE ELSE S.ORDRE END ORDRE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PERTINENCE_NIVEAU ELSE S.PERTINENCE_NIVEAU END PERTINENCE_NIVEAU,
    CASE WHEN D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL) THEN 1 ELSE 0 END U_LIBELLE_COURT,
    CASE WHEN D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL) THEN 1 ELSE 0 END U_LIBELLE_LONG,
    CASE WHEN D.ORDRE <> S.ORDRE OR (D.ORDRE IS NULL AND S.ORDRE IS NOT NULL) OR (D.ORDRE IS NOT NULL AND S.ORDRE IS NULL) THEN 1 ELSE 0 END U_ORDRE,
    CASE WHEN D.PERTINENCE_NIVEAU <> S.PERTINENCE_NIVEAU OR (D.PERTINENCE_NIVEAU IS NULL AND S.PERTINENCE_NIVEAU IS NOT NULL) OR (D.PERTINENCE_NIVEAU IS NOT NULL AND S.PERTINENCE_NIVEAU IS NULL) THEN 1 ELSE 0 END U_PERTINENCE_NIVEAU
FROM
  GROUPE_TYPE_FORMATION D
  FULL JOIN SRC_GROUPE_TYPE_FORMATION S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL)
  OR D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL)
  OR D.ORDRE <> S.ORDRE OR (D.ORDRE IS NULL AND S.ORDRE IS NOT NULL) OR (D.ORDRE IS NOT NULL AND S.ORDRE IS NULL)
  OR D.PERTINENCE_NIVEAU <> S.PERTINENCE_NIVEAU OR (D.PERTINENCE_NIVEAU IS NULL AND S.PERTINENCE_NIVEAU IS NOT NULL) OR (D.PERTINENCE_NIVEAU IS NOT NULL AND S.PERTINENCE_NIVEAU IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
--------------------------------------------------------
--  DDL for View V_DIFF_INTERVENANT
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_INTERVENANT" ("ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "BIC", "CIVILITE_ID", "DATE_NAISSANCE", "DEP_NAISSANCE_CODE_INSEE", "DEP_NAISSANCE_LIBELLE", "EMAIL", "IBAN", "NOM_PATRONYMIQUE", "NOM_USUEL", "NUMERO_INSEE", "NUMERO_INSEE_CLE", "NUMERO_INSEE_PROVISOIRE", "PAYS_NAISSANCE_CODE_INSEE", "PAYS_NAISSANCE_LIBELLE", "PAYS_NATIONALITE_CODE_INSEE", "PAYS_NATIONALITE_LIBELLE", "PRENOM", "STATUT_ID", "STRUCTURE_ID", "TEL_MOBILE", "TEL_PRO", "TYPE_ID", "VILLE_NAISSANCE_CODE_INSEE", "VILLE_NAISSANCE_LIBELLE", "U_BIC", "U_CIVILITE_ID", "U_DATE_NAISSANCE", "U_DEP_NAISSANCE_CODE_INSEE", "U_DEP_NAISSANCE_LIBELLE", "U_EMAIL", "U_IBAN", "U_NOM_PATRONYMIQUE", "U_NOM_USUEL", "U_NUMERO_INSEE", "U_NUMERO_INSEE_CLE", "U_NUMERO_INSEE_PROVISOIRE", "U_PAYS_NAISSANCE_CODE_INSEE", "U_PAYS_NAISSANCE_LIBELLE", "U_PAYS_NATIONALITE_CODE_INSEE", "U_PAYS_NATIONALITE_LIBELLE", "U_PRENOM", "U_STATUT_ID", "U_STRUCTURE_ID", "U_TEL_MOBILE", "U_TEL_PRO", "U_TYPE_ID", "U_VILLE_NAISSANCE_CODE_INSEE", "U_VILLE_NAISSANCE_LIBELLE") AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."BIC",diff."CIVILITE_ID",diff."DATE_NAISSANCE",diff."DEP_NAISSANCE_CODE_INSEE",diff."DEP_NAISSANCE_LIBELLE",diff."EMAIL",diff."IBAN",diff."NOM_PATRONYMIQUE",diff."NOM_USUEL",diff."NUMERO_INSEE",diff."NUMERO_INSEE_CLE",diff."NUMERO_INSEE_PROVISOIRE",diff."PAYS_NAISSANCE_CODE_INSEE",diff."PAYS_NAISSANCE_LIBELLE",diff."PAYS_NATIONALITE_CODE_INSEE",diff."PAYS_NATIONALITE_LIBELLE",diff."PRENOM",diff."STATUT_ID",diff."STRUCTURE_ID",diff."TEL_MOBILE",diff."TEL_PRO",diff."TYPE_ID",diff."VILLE_NAISSANCE_CODE_INSEE",diff."VILLE_NAISSANCE_LIBELLE",diff."U_BIC",diff."U_CIVILITE_ID",diff."U_DATE_NAISSANCE",diff."U_DEP_NAISSANCE_CODE_INSEE",diff."U_DEP_NAISSANCE_LIBELLE",diff."U_EMAIL",diff."U_IBAN",diff."U_NOM_PATRONYMIQUE",diff."U_NOM_USUEL",diff."U_NUMERO_INSEE",diff."U_NUMERO_INSEE_CLE",diff."U_NUMERO_INSEE_PROVISOIRE",diff."U_PAYS_NAISSANCE_CODE_INSEE",diff."U_PAYS_NAISSANCE_LIBELLE",diff."U_PAYS_NATIONALITE_CODE_INSEE",diff."U_PAYS_NATIONALITE_LIBELLE",diff."U_PRENOM",diff."U_STATUT_ID",diff."U_STRUCTURE_ID",diff."U_TEL_MOBILE",diff."U_TEL_PRO",diff."U_TYPE_ID",diff."U_VILLE_NAISSANCE_CODE_INSEE",diff."U_VILLE_NAISSANCE_LIBELLE" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.BIC ELSE S.BIC END BIC,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CIVILITE_ID ELSE S.CIVILITE_ID END CIVILITE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DATE_NAISSANCE ELSE S.DATE_NAISSANCE END DATE_NAISSANCE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DEP_NAISSANCE_CODE_INSEE ELSE S.DEP_NAISSANCE_CODE_INSEE END DEP_NAISSANCE_CODE_INSEE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DEP_NAISSANCE_LIBELLE ELSE S.DEP_NAISSANCE_LIBELLE END DEP_NAISSANCE_LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.EMAIL ELSE S.EMAIL END EMAIL,
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
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TYPE_ID ELSE S.TYPE_ID END TYPE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VILLE_NAISSANCE_CODE_INSEE ELSE S.VILLE_NAISSANCE_CODE_INSEE END VILLE_NAISSANCE_CODE_INSEE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VILLE_NAISSANCE_LIBELLE ELSE S.VILLE_NAISSANCE_LIBELLE END VILLE_NAISSANCE_LIBELLE,
    CASE WHEN D.BIC <> S.BIC OR (D.BIC IS NULL AND S.BIC IS NOT NULL) OR (D.BIC IS NOT NULL AND S.BIC IS NULL) THEN 1 ELSE 0 END U_BIC,
    CASE WHEN D.CIVILITE_ID <> S.CIVILITE_ID OR (D.CIVILITE_ID IS NULL AND S.CIVILITE_ID IS NOT NULL) OR (D.CIVILITE_ID IS NOT NULL AND S.CIVILITE_ID IS NULL) THEN 1 ELSE 0 END U_CIVILITE_ID,
    CASE WHEN D.DATE_NAISSANCE <> S.DATE_NAISSANCE OR (D.DATE_NAISSANCE IS NULL AND S.DATE_NAISSANCE IS NOT NULL) OR (D.DATE_NAISSANCE IS NOT NULL AND S.DATE_NAISSANCE IS NULL) THEN 1 ELSE 0 END U_DATE_NAISSANCE,
    CASE WHEN D.DEP_NAISSANCE_CODE_INSEE <> S.DEP_NAISSANCE_CODE_INSEE OR (D.DEP_NAISSANCE_CODE_INSEE IS NULL AND S.DEP_NAISSANCE_CODE_INSEE IS NOT NULL) OR (D.DEP_NAISSANCE_CODE_INSEE IS NOT NULL AND S.DEP_NAISSANCE_CODE_INSEE IS NULL) THEN 1 ELSE 0 END U_DEP_NAISSANCE_CODE_INSEE,
    CASE WHEN D.DEP_NAISSANCE_LIBELLE <> S.DEP_NAISSANCE_LIBELLE OR (D.DEP_NAISSANCE_LIBELLE IS NULL AND S.DEP_NAISSANCE_LIBELLE IS NOT NULL) OR (D.DEP_NAISSANCE_LIBELLE IS NOT NULL AND S.DEP_NAISSANCE_LIBELLE IS NULL) THEN 1 ELSE 0 END U_DEP_NAISSANCE_LIBELLE,
    CASE WHEN D.EMAIL <> S.EMAIL OR (D.EMAIL IS NULL AND S.EMAIL IS NOT NULL) OR (D.EMAIL IS NOT NULL AND S.EMAIL IS NULL) THEN 1 ELSE 0 END U_EMAIL,
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
    CASE WHEN D.TYPE_ID <> S.TYPE_ID OR (D.TYPE_ID IS NULL AND S.TYPE_ID IS NOT NULL) OR (D.TYPE_ID IS NOT NULL AND S.TYPE_ID IS NULL) THEN 1 ELSE 0 END U_TYPE_ID,
    CASE WHEN D.VILLE_NAISSANCE_CODE_INSEE <> S.VILLE_NAISSANCE_CODE_INSEE OR (D.VILLE_NAISSANCE_CODE_INSEE IS NULL AND S.VILLE_NAISSANCE_CODE_INSEE IS NOT NULL) OR (D.VILLE_NAISSANCE_CODE_INSEE IS NOT NULL AND S.VILLE_NAISSANCE_CODE_INSEE IS NULL) THEN 1 ELSE 0 END U_VILLE_NAISSANCE_CODE_INSEE,
    CASE WHEN D.VILLE_NAISSANCE_LIBELLE <> S.VILLE_NAISSANCE_LIBELLE OR (D.VILLE_NAISSANCE_LIBELLE IS NULL AND S.VILLE_NAISSANCE_LIBELLE IS NOT NULL) OR (D.VILLE_NAISSANCE_LIBELLE IS NOT NULL AND S.VILLE_NAISSANCE_LIBELLE IS NULL) THEN 1 ELSE 0 END U_VILLE_NAISSANCE_LIBELLE
FROM
  INTERVENANT D
  FULL JOIN SRC_INTERVENANT S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.BIC <> S.BIC OR (D.BIC IS NULL AND S.BIC IS NOT NULL) OR (D.BIC IS NOT NULL AND S.BIC IS NULL)
  OR D.CIVILITE_ID <> S.CIVILITE_ID OR (D.CIVILITE_ID IS NULL AND S.CIVILITE_ID IS NOT NULL) OR (D.CIVILITE_ID IS NOT NULL AND S.CIVILITE_ID IS NULL)
  OR D.DATE_NAISSANCE <> S.DATE_NAISSANCE OR (D.DATE_NAISSANCE IS NULL AND S.DATE_NAISSANCE IS NOT NULL) OR (D.DATE_NAISSANCE IS NOT NULL AND S.DATE_NAISSANCE IS NULL)
  OR D.DEP_NAISSANCE_CODE_INSEE <> S.DEP_NAISSANCE_CODE_INSEE OR (D.DEP_NAISSANCE_CODE_INSEE IS NULL AND S.DEP_NAISSANCE_CODE_INSEE IS NOT NULL) OR (D.DEP_NAISSANCE_CODE_INSEE IS NOT NULL AND S.DEP_NAISSANCE_CODE_INSEE IS NULL)
  OR D.DEP_NAISSANCE_LIBELLE <> S.DEP_NAISSANCE_LIBELLE OR (D.DEP_NAISSANCE_LIBELLE IS NULL AND S.DEP_NAISSANCE_LIBELLE IS NOT NULL) OR (D.DEP_NAISSANCE_LIBELLE IS NOT NULL AND S.DEP_NAISSANCE_LIBELLE IS NULL)
  OR D.EMAIL <> S.EMAIL OR (D.EMAIL IS NULL AND S.EMAIL IS NOT NULL) OR (D.EMAIL IS NOT NULL AND S.EMAIL IS NULL)
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
  OR D.TYPE_ID <> S.TYPE_ID OR (D.TYPE_ID IS NULL AND S.TYPE_ID IS NOT NULL) OR (D.TYPE_ID IS NOT NULL AND S.TYPE_ID IS NULL)
  OR D.VILLE_NAISSANCE_CODE_INSEE <> S.VILLE_NAISSANCE_CODE_INSEE OR (D.VILLE_NAISSANCE_CODE_INSEE IS NULL AND S.VILLE_NAISSANCE_CODE_INSEE IS NOT NULL) OR (D.VILLE_NAISSANCE_CODE_INSEE IS NOT NULL AND S.VILLE_NAISSANCE_CODE_INSEE IS NULL)
  OR D.VILLE_NAISSANCE_LIBELLE <> S.VILLE_NAISSANCE_LIBELLE OR (D.VILLE_NAISSANCE_LIBELLE IS NULL AND S.VILLE_NAISSANCE_LIBELLE IS NOT NULL) OR (D.VILLE_NAISSANCE_LIBELLE IS NOT NULL AND S.VILLE_NAISSANCE_LIBELLE IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
--------------------------------------------------------
--  DDL for View V_DIFF_INTERVENANT_EXTERIEUR
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_INTERVENANT_EXTERIEUR" ("ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "SITUATION_FAMILIALE_ID", "VALIDITE_DEBUT", "VALIDITE_FIN", "U_SITUATION_FAMILIALE_ID", "U_VALIDITE_DEBUT", "U_VALIDITE_FIN") AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."SITUATION_FAMILIALE_ID",diff."VALIDITE_DEBUT",diff."VALIDITE_FIN",diff."U_SITUATION_FAMILIALE_ID",diff."U_VALIDITE_DEBUT",diff."U_VALIDITE_FIN" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.SITUATION_FAMILIALE_ID ELSE S.SITUATION_FAMILIALE_ID END SITUATION_FAMILIALE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_DEBUT ELSE S.VALIDITE_DEBUT END VALIDITE_DEBUT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_FIN ELSE S.VALIDITE_FIN END VALIDITE_FIN,
    CASE WHEN D.SITUATION_FAMILIALE_ID <> S.SITUATION_FAMILIALE_ID OR (D.SITUATION_FAMILIALE_ID IS NULL AND S.SITUATION_FAMILIALE_ID IS NOT NULL) OR (D.SITUATION_FAMILIALE_ID IS NOT NULL AND S.SITUATION_FAMILIALE_ID IS NULL) THEN 1 ELSE 0 END U_SITUATION_FAMILIALE_ID,
    CASE WHEN D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL) THEN 1 ELSE 0 END U_VALIDITE_DEBUT,
    CASE WHEN D.VALIDITE_FIN <> S.VALIDITE_FIN OR (D.VALIDITE_FIN IS NULL AND S.VALIDITE_FIN IS NOT NULL) OR (D.VALIDITE_FIN IS NOT NULL AND S.VALIDITE_FIN IS NULL) THEN 1 ELSE 0 END U_VALIDITE_FIN
FROM
  INTERVENANT_EXTERIEUR D
  FULL JOIN SRC_INTERVENANT_EXTERIEUR S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.SITUATION_FAMILIALE_ID <> S.SITUATION_FAMILIALE_ID OR (D.SITUATION_FAMILIALE_ID IS NULL AND S.SITUATION_FAMILIALE_ID IS NOT NULL) OR (D.SITUATION_FAMILIALE_ID IS NOT NULL AND S.SITUATION_FAMILIALE_ID IS NULL)
  OR D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL)
  OR D.VALIDITE_FIN <> S.VALIDITE_FIN OR (D.VALIDITE_FIN IS NULL AND S.VALIDITE_FIN IS NOT NULL) OR (D.VALIDITE_FIN IS NOT NULL AND S.VALIDITE_FIN IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
--------------------------------------------------------
--  DDL for View V_DIFF_INTERVENANT_PERMANENT
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_INTERVENANT_PERMANENT" ("ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "CORPS_ID", "VALIDITE_DEBUT", "VALIDITE_FIN", "U_CORPS_ID", "U_VALIDITE_DEBUT", "U_VALIDITE_FIN") AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."CORPS_ID",diff."VALIDITE_DEBUT",diff."VALIDITE_FIN",diff."U_CORPS_ID",diff."U_VALIDITE_DEBUT",diff."U_VALIDITE_FIN" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CORPS_ID ELSE S.CORPS_ID END CORPS_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_DEBUT ELSE S.VALIDITE_DEBUT END VALIDITE_DEBUT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_FIN ELSE S.VALIDITE_FIN END VALIDITE_FIN,
    CASE WHEN D.CORPS_ID <> S.CORPS_ID OR (D.CORPS_ID IS NULL AND S.CORPS_ID IS NOT NULL) OR (D.CORPS_ID IS NOT NULL AND S.CORPS_ID IS NULL) THEN 1 ELSE 0 END U_CORPS_ID,
    CASE WHEN D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL) THEN 1 ELSE 0 END U_VALIDITE_DEBUT,
    CASE WHEN D.VALIDITE_FIN <> S.VALIDITE_FIN OR (D.VALIDITE_FIN IS NULL AND S.VALIDITE_FIN IS NOT NULL) OR (D.VALIDITE_FIN IS NOT NULL AND S.VALIDITE_FIN IS NULL) THEN 1 ELSE 0 END U_VALIDITE_FIN
FROM
  INTERVENANT_PERMANENT D
  FULL JOIN SRC_INTERVENANT_PERMANENT S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.CORPS_ID <> S.CORPS_ID OR (D.CORPS_ID IS NULL AND S.CORPS_ID IS NOT NULL) OR (D.CORPS_ID IS NOT NULL AND S.CORPS_ID IS NULL)
  OR D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL)
  OR D.VALIDITE_FIN <> S.VALIDITE_FIN OR (D.VALIDITE_FIN IS NULL AND S.VALIDITE_FIN IS NOT NULL) OR (D.VALIDITE_FIN IS NOT NULL AND S.VALIDITE_FIN IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
--------------------------------------------------------
--  DDL for View V_DIFF_PERSONNEL
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_PERSONNEL" ("ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "CIVILITE_ID", "EMAIL", "NOM_PATRONYMIQUE", "NOM_USUEL", "PRENOM", "STRUCTURE_ID", "VALIDITE_DEBUT", "VALIDITE_FIN", "U_CIVILITE_ID", "U_EMAIL", "U_NOM_PATRONYMIQUE", "U_NOM_USUEL", "U_PRENOM", "U_STRUCTURE_ID", "U_VALIDITE_DEBUT", "U_VALIDITE_FIN") AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."CIVILITE_ID",diff."EMAIL",diff."NOM_PATRONYMIQUE",diff."NOM_USUEL",diff."PRENOM",diff."STRUCTURE_ID",diff."VALIDITE_DEBUT",diff."VALIDITE_FIN",diff."U_CIVILITE_ID",diff."U_EMAIL",diff."U_NOM_PATRONYMIQUE",diff."U_NOM_USUEL",diff."U_PRENOM",diff."U_STRUCTURE_ID",diff."U_VALIDITE_DEBUT",diff."U_VALIDITE_FIN" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CIVILITE_ID ELSE S.CIVILITE_ID END CIVILITE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.EMAIL ELSE S.EMAIL END EMAIL,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NOM_PATRONYMIQUE ELSE S.NOM_PATRONYMIQUE END NOM_PATRONYMIQUE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NOM_USUEL ELSE S.NOM_USUEL END NOM_USUEL,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PRENOM ELSE S.PRENOM END PRENOM,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_ID ELSE S.STRUCTURE_ID END STRUCTURE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_DEBUT ELSE S.VALIDITE_DEBUT END VALIDITE_DEBUT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_FIN ELSE S.VALIDITE_FIN END VALIDITE_FIN,
    CASE WHEN D.CIVILITE_ID <> S.CIVILITE_ID OR (D.CIVILITE_ID IS NULL AND S.CIVILITE_ID IS NOT NULL) OR (D.CIVILITE_ID IS NOT NULL AND S.CIVILITE_ID IS NULL) THEN 1 ELSE 0 END U_CIVILITE_ID,
    CASE WHEN D.EMAIL <> S.EMAIL OR (D.EMAIL IS NULL AND S.EMAIL IS NOT NULL) OR (D.EMAIL IS NOT NULL AND S.EMAIL IS NULL) THEN 1 ELSE 0 END U_EMAIL,
    CASE WHEN D.NOM_PATRONYMIQUE <> S.NOM_PATRONYMIQUE OR (D.NOM_PATRONYMIQUE IS NULL AND S.NOM_PATRONYMIQUE IS NOT NULL) OR (D.NOM_PATRONYMIQUE IS NOT NULL AND S.NOM_PATRONYMIQUE IS NULL) THEN 1 ELSE 0 END U_NOM_PATRONYMIQUE,
    CASE WHEN D.NOM_USUEL <> S.NOM_USUEL OR (D.NOM_USUEL IS NULL AND S.NOM_USUEL IS NOT NULL) OR (D.NOM_USUEL IS NOT NULL AND S.NOM_USUEL IS NULL) THEN 1 ELSE 0 END U_NOM_USUEL,
    CASE WHEN D.PRENOM <> S.PRENOM OR (D.PRENOM IS NULL AND S.PRENOM IS NOT NULL) OR (D.PRENOM IS NOT NULL AND S.PRENOM IS NULL) THEN 1 ELSE 0 END U_PRENOM,
    CASE WHEN D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_ID,
    CASE WHEN D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL) THEN 1 ELSE 0 END U_VALIDITE_DEBUT,
    CASE WHEN D.VALIDITE_FIN <> S.VALIDITE_FIN OR (D.VALIDITE_FIN IS NULL AND S.VALIDITE_FIN IS NOT NULL) OR (D.VALIDITE_FIN IS NOT NULL AND S.VALIDITE_FIN IS NULL) THEN 1 ELSE 0 END U_VALIDITE_FIN
FROM
  PERSONNEL D
  FULL JOIN SRC_PERSONNEL S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.CIVILITE_ID <> S.CIVILITE_ID OR (D.CIVILITE_ID IS NULL AND S.CIVILITE_ID IS NOT NULL) OR (D.CIVILITE_ID IS NOT NULL AND S.CIVILITE_ID IS NULL)
  OR D.EMAIL <> S.EMAIL OR (D.EMAIL IS NULL AND S.EMAIL IS NOT NULL) OR (D.EMAIL IS NOT NULL AND S.EMAIL IS NULL)
  OR D.NOM_PATRONYMIQUE <> S.NOM_PATRONYMIQUE OR (D.NOM_PATRONYMIQUE IS NULL AND S.NOM_PATRONYMIQUE IS NOT NULL) OR (D.NOM_PATRONYMIQUE IS NOT NULL AND S.NOM_PATRONYMIQUE IS NULL)
  OR D.NOM_USUEL <> S.NOM_USUEL OR (D.NOM_USUEL IS NULL AND S.NOM_USUEL IS NOT NULL) OR (D.NOM_USUEL IS NOT NULL AND S.NOM_USUEL IS NULL)
  OR D.PRENOM <> S.PRENOM OR (D.PRENOM IS NULL AND S.PRENOM IS NOT NULL) OR (D.PRENOM IS NOT NULL AND S.PRENOM IS NULL)
  OR D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL)
  OR D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL)
  OR D.VALIDITE_FIN <> S.VALIDITE_FIN OR (D.VALIDITE_FIN IS NULL AND S.VALIDITE_FIN IS NOT NULL) OR (D.VALIDITE_FIN IS NOT NULL AND S.VALIDITE_FIN IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
--------------------------------------------------------
--  DDL for View V_DIFF_ROLE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_ROLE" ("ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "PERSONNEL_ID", "STRUCTURE_ID", "TYPE_ID", "VALIDITE_DEBUT", "VALIDITE_FIN", "U_PERSONNEL_ID", "U_STRUCTURE_ID", "U_TYPE_ID", "U_VALIDITE_DEBUT", "U_VALIDITE_FIN") AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."PERSONNEL_ID",diff."STRUCTURE_ID",diff."TYPE_ID",diff."VALIDITE_DEBUT",diff."VALIDITE_FIN",diff."U_PERSONNEL_ID",diff."U_STRUCTURE_ID",diff."U_TYPE_ID",diff."U_VALIDITE_DEBUT",diff."U_VALIDITE_FIN" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PERSONNEL_ID ELSE S.PERSONNEL_ID END PERSONNEL_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_ID ELSE S.STRUCTURE_ID END STRUCTURE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TYPE_ID ELSE S.TYPE_ID END TYPE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_DEBUT ELSE S.VALIDITE_DEBUT END VALIDITE_DEBUT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_FIN ELSE S.VALIDITE_FIN END VALIDITE_FIN,
    CASE WHEN D.PERSONNEL_ID <> S.PERSONNEL_ID OR (D.PERSONNEL_ID IS NULL AND S.PERSONNEL_ID IS NOT NULL) OR (D.PERSONNEL_ID IS NOT NULL AND S.PERSONNEL_ID IS NULL) THEN 1 ELSE 0 END U_PERSONNEL_ID,
    CASE WHEN D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_ID,
    CASE WHEN D.TYPE_ID <> S.TYPE_ID OR (D.TYPE_ID IS NULL AND S.TYPE_ID IS NOT NULL) OR (D.TYPE_ID IS NOT NULL AND S.TYPE_ID IS NULL) THEN 1 ELSE 0 END U_TYPE_ID,
    CASE WHEN D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL) THEN 1 ELSE 0 END U_VALIDITE_DEBUT,
    CASE WHEN D.VALIDITE_FIN <> S.VALIDITE_FIN OR (D.VALIDITE_FIN IS NULL AND S.VALIDITE_FIN IS NOT NULL) OR (D.VALIDITE_FIN IS NOT NULL AND S.VALIDITE_FIN IS NULL) THEN 1 ELSE 0 END U_VALIDITE_FIN
FROM
  ROLE D
  FULL JOIN SRC_ROLE S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.PERSONNEL_ID <> S.PERSONNEL_ID OR (D.PERSONNEL_ID IS NULL AND S.PERSONNEL_ID IS NOT NULL) OR (D.PERSONNEL_ID IS NOT NULL AND S.PERSONNEL_ID IS NULL)
  OR D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL)
  OR D.TYPE_ID <> S.TYPE_ID OR (D.TYPE_ID IS NULL AND S.TYPE_ID IS NOT NULL) OR (D.TYPE_ID IS NOT NULL AND S.TYPE_ID IS NULL)
  OR D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL)
  OR D.VALIDITE_FIN <> S.VALIDITE_FIN OR (D.VALIDITE_FIN IS NULL AND S.VALIDITE_FIN IS NOT NULL) OR (D.VALIDITE_FIN IS NOT NULL AND S.VALIDITE_FIN IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
--------------------------------------------------------
--  DDL for View V_DIFF_STRUCTURE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_STRUCTURE" ("ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ETABLISSEMENT_ID", "LIBELLE_COURT", "LIBELLE_LONG", "NIVEAU", "PARENTE_ID", "STRUCTURE_NIV2_ID", "TYPE_ID", "VALIDITE_DEBUT", "VALIDITE_FIN", "U_ETABLISSEMENT_ID", "U_LIBELLE_COURT", "U_LIBELLE_LONG", "U_NIVEAU", "U_PARENTE_ID", "U_STRUCTURE_NIV2_ID", "U_TYPE_ID", "U_VALIDITE_DEBUT", "U_VALIDITE_FIN") AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ETABLISSEMENT_ID",diff."LIBELLE_COURT",diff."LIBELLE_LONG",diff."NIVEAU",diff."PARENTE_ID",diff."STRUCTURE_NIV2_ID",diff."TYPE_ID",diff."VALIDITE_DEBUT",diff."VALIDITE_FIN",diff."U_ETABLISSEMENT_ID",diff."U_LIBELLE_COURT",diff."U_LIBELLE_LONG",diff."U_NIVEAU",diff."U_PARENTE_ID",diff."U_STRUCTURE_NIV2_ID",diff."U_TYPE_ID",diff."U_VALIDITE_DEBUT",diff."U_VALIDITE_FIN" from (SELECT
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
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_DEBUT ELSE S.VALIDITE_DEBUT END VALIDITE_DEBUT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_FIN ELSE S.VALIDITE_FIN END VALIDITE_FIN,
    CASE WHEN D.ETABLISSEMENT_ID <> S.ETABLISSEMENT_ID OR (D.ETABLISSEMENT_ID IS NULL AND S.ETABLISSEMENT_ID IS NOT NULL) OR (D.ETABLISSEMENT_ID IS NOT NULL AND S.ETABLISSEMENT_ID IS NULL) THEN 1 ELSE 0 END U_ETABLISSEMENT_ID,
    CASE WHEN D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL) THEN 1 ELSE 0 END U_LIBELLE_COURT,
    CASE WHEN D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL) THEN 1 ELSE 0 END U_LIBELLE_LONG,
    CASE WHEN D.NIVEAU <> S.NIVEAU OR (D.NIVEAU IS NULL AND S.NIVEAU IS NOT NULL) OR (D.NIVEAU IS NOT NULL AND S.NIVEAU IS NULL) THEN 1 ELSE 0 END U_NIVEAU,
    CASE WHEN D.PARENTE_ID <> S.PARENTE_ID OR (D.PARENTE_ID IS NULL AND S.PARENTE_ID IS NOT NULL) OR (D.PARENTE_ID IS NOT NULL AND S.PARENTE_ID IS NULL) THEN 1 ELSE 0 END U_PARENTE_ID,
    CASE WHEN D.STRUCTURE_NIV2_ID <> S.STRUCTURE_NIV2_ID OR (D.STRUCTURE_NIV2_ID IS NULL AND S.STRUCTURE_NIV2_ID IS NOT NULL) OR (D.STRUCTURE_NIV2_ID IS NOT NULL AND S.STRUCTURE_NIV2_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_NIV2_ID,
    CASE WHEN D.TYPE_ID <> S.TYPE_ID OR (D.TYPE_ID IS NULL AND S.TYPE_ID IS NOT NULL) OR (D.TYPE_ID IS NOT NULL AND S.TYPE_ID IS NULL) THEN 1 ELSE 0 END U_TYPE_ID,
    CASE WHEN D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL) THEN 1 ELSE 0 END U_VALIDITE_DEBUT,
    CASE WHEN D.VALIDITE_FIN <> S.VALIDITE_FIN OR (D.VALIDITE_FIN IS NULL AND S.VALIDITE_FIN IS NOT NULL) OR (D.VALIDITE_FIN IS NOT NULL AND S.VALIDITE_FIN IS NULL) THEN 1 ELSE 0 END U_VALIDITE_FIN
FROM
  STRUCTURE D
  FULL JOIN SRC_STRUCTURE S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
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
  OR D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL)
  OR D.VALIDITE_FIN <> S.VALIDITE_FIN OR (D.VALIDITE_FIN IS NULL AND S.VALIDITE_FIN IS NOT NULL) OR (D.VALIDITE_FIN IS NOT NULL AND S.VALIDITE_FIN IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
--------------------------------------------------------
--  DDL for View V_DIFF_TYPE_FORMATION
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_TYPE_FORMATION" ("ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "GROUPE_ID", "LIBELLE_COURT", "LIBELLE_LONG", "U_GROUPE_ID", "U_LIBELLE_COURT", "U_LIBELLE_LONG") AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."GROUPE_ID",diff."LIBELLE_COURT",diff."LIBELLE_LONG",diff."U_GROUPE_ID",diff."U_LIBELLE_COURT",diff."U_LIBELLE_LONG" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.GROUPE_ID ELSE S.GROUPE_ID END GROUPE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_COURT ELSE S.LIBELLE_COURT END LIBELLE_COURT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_LONG ELSE S.LIBELLE_LONG END LIBELLE_LONG,
    CASE WHEN D.GROUPE_ID <> S.GROUPE_ID OR (D.GROUPE_ID IS NULL AND S.GROUPE_ID IS NOT NULL) OR (D.GROUPE_ID IS NOT NULL AND S.GROUPE_ID IS NULL) THEN 1 ELSE 0 END U_GROUPE_ID,
    CASE WHEN D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL) THEN 1 ELSE 0 END U_LIBELLE_COURT,
    CASE WHEN D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL) THEN 1 ELSE 0 END U_LIBELLE_LONG
FROM
  TYPE_FORMATION D
  FULL JOIN SRC_TYPE_FORMATION S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.GROUPE_ID <> S.GROUPE_ID OR (D.GROUPE_ID IS NULL AND S.GROUPE_ID IS NOT NULL) OR (D.GROUPE_ID IS NOT NULL AND S.GROUPE_ID IS NULL)
  OR D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL)
  OR D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
--------------------------------------------------------
--  DDL for View V_DIFF_TYPE_INTERVENTION_EP
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_TYPE_INTERVENTION_EP" ("ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ELEMENT_PEDAGOGIQUE_ID", "TYPE_INTERVENTION_ID", "VISIBLE", "U_ELEMENT_PEDAGOGIQUE_ID", "U_TYPE_INTERVENTION_ID", "U_VISIBLE") AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ELEMENT_PEDAGOGIQUE_ID",diff."TYPE_INTERVENTION_ID",diff."VISIBLE",diff."U_ELEMENT_PEDAGOGIQUE_ID",diff."U_TYPE_INTERVENTION_ID",diff."U_VISIBLE" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ELEMENT_PEDAGOGIQUE_ID ELSE S.ELEMENT_PEDAGOGIQUE_ID END ELEMENT_PEDAGOGIQUE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TYPE_INTERVENTION_ID ELSE S.TYPE_INTERVENTION_ID END TYPE_INTERVENTION_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VISIBLE ELSE S.VISIBLE END VISIBLE,
    CASE WHEN D.ELEMENT_PEDAGOGIQUE_ID <> S.ELEMENT_PEDAGOGIQUE_ID OR (D.ELEMENT_PEDAGOGIQUE_ID IS NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL) OR (D.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NULL) THEN 1 ELSE 0 END U_ELEMENT_PEDAGOGIQUE_ID,
    CASE WHEN D.TYPE_INTERVENTION_ID <> S.TYPE_INTERVENTION_ID OR (D.TYPE_INTERVENTION_ID IS NULL AND S.TYPE_INTERVENTION_ID IS NOT NULL) OR (D.TYPE_INTERVENTION_ID IS NOT NULL AND S.TYPE_INTERVENTION_ID IS NULL) THEN 1 ELSE 0 END U_TYPE_INTERVENTION_ID,
    CASE WHEN D.VISIBLE <> S.VISIBLE OR (D.VISIBLE IS NULL AND S.VISIBLE IS NOT NULL) OR (D.VISIBLE IS NOT NULL AND S.VISIBLE IS NULL) THEN 1 ELSE 0 END U_VISIBLE
FROM
  TYPE_INTERVENTION_EP D
  FULL JOIN SRC_TYPE_INTERVENTION_EP S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ELEMENT_PEDAGOGIQUE_ID <> S.ELEMENT_PEDAGOGIQUE_ID OR (D.ELEMENT_PEDAGOGIQUE_ID IS NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL) OR (D.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NULL)
  OR D.TYPE_INTERVENTION_ID <> S.TYPE_INTERVENTION_ID OR (D.TYPE_INTERVENTION_ID IS NULL AND S.TYPE_INTERVENTION_ID IS NOT NULL) OR (D.TYPE_INTERVENTION_ID IS NOT NULL AND S.TYPE_INTERVENTION_ID IS NULL)
  OR D.VISIBLE <> S.VISIBLE OR (D.VISIBLE IS NULL AND S.VISIBLE IS NOT NULL) OR (D.VISIBLE IS NOT NULL AND S.VISIBLE IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
--------------------------------------------------------
--  DDL for View V_DIFF_VOLUME_HORAIRE_ENS
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_VOLUME_HORAIRE_ENS" ("ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ANNEE_ID", "ELEMENT_DISCIPLINE_ID", "HEURES", "TYPE_INTERVENTION_ID", "U_ANNEE_ID", "U_ELEMENT_DISCIPLINE_ID", "U_HEURES", "U_TYPE_INTERVENTION_ID") AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ANNEE_ID",diff."ELEMENT_DISCIPLINE_ID",diff."HEURES",diff."TYPE_INTERVENTION_ID",diff."U_ANNEE_ID",diff."U_ELEMENT_DISCIPLINE_ID",diff."U_HEURES",diff."U_TYPE_INTERVENTION_ID" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ANNEE_ID ELSE S.ANNEE_ID END ANNEE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ELEMENT_DISCIPLINE_ID ELSE S.ELEMENT_DISCIPLINE_ID END ELEMENT_DISCIPLINE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.HEURES ELSE S.HEURES END HEURES,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TYPE_INTERVENTION_ID ELSE S.TYPE_INTERVENTION_ID END TYPE_INTERVENTION_ID,
    CASE WHEN D.ANNEE_ID <> S.ANNEE_ID OR (D.ANNEE_ID IS NULL AND S.ANNEE_ID IS NOT NULL) OR (D.ANNEE_ID IS NOT NULL AND S.ANNEE_ID IS NULL) THEN 1 ELSE 0 END U_ANNEE_ID,
    CASE WHEN D.ELEMENT_DISCIPLINE_ID <> S.ELEMENT_DISCIPLINE_ID OR (D.ELEMENT_DISCIPLINE_ID IS NULL AND S.ELEMENT_DISCIPLINE_ID IS NOT NULL) OR (D.ELEMENT_DISCIPLINE_ID IS NOT NULL AND S.ELEMENT_DISCIPLINE_ID IS NULL) THEN 1 ELSE 0 END U_ELEMENT_DISCIPLINE_ID,
    CASE WHEN D.HEURES <> S.HEURES OR (D.HEURES IS NULL AND S.HEURES IS NOT NULL) OR (D.HEURES IS NOT NULL AND S.HEURES IS NULL) THEN 1 ELSE 0 END U_HEURES,
    CASE WHEN D.TYPE_INTERVENTION_ID <> S.TYPE_INTERVENTION_ID OR (D.TYPE_INTERVENTION_ID IS NULL AND S.TYPE_INTERVENTION_ID IS NOT NULL) OR (D.TYPE_INTERVENTION_ID IS NOT NULL AND S.TYPE_INTERVENTION_ID IS NULL) THEN 1 ELSE 0 END U_TYPE_INTERVENTION_ID
FROM
  VOLUME_HORAIRE_ENS D
  FULL JOIN SRC_VOLUME_HORAIRE_ENS S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ANNEE_ID <> S.ANNEE_ID OR (D.ANNEE_ID IS NULL AND S.ANNEE_ID IS NOT NULL) OR (D.ANNEE_ID IS NOT NULL AND S.ANNEE_ID IS NULL)
  OR D.ELEMENT_DISCIPLINE_ID <> S.ELEMENT_DISCIPLINE_ID OR (D.ELEMENT_DISCIPLINE_ID IS NULL AND S.ELEMENT_DISCIPLINE_ID IS NOT NULL) OR (D.ELEMENT_DISCIPLINE_ID IS NOT NULL AND S.ELEMENT_DISCIPLINE_ID IS NULL)
  OR D.HEURES <> S.HEURES OR (D.HEURES IS NULL AND S.HEURES IS NOT NULL) OR (D.HEURES IS NOT NULL AND S.HEURES IS NULL)
  OR D.TYPE_INTERVENTION_ID <> S.TYPE_INTERVENTION_ID OR (D.TYPE_INTERVENTION_ID IS NULL AND S.TYPE_INTERVENTION_ID IS NOT NULL) OR (D.TYPE_INTERVENTION_ID IS NOT NULL AND S.TYPE_INTERVENTION_ID IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
--------------------------------------------------------
--  DDL for View V_ELEMENT_TYPE_INTERVENTION
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_ELEMENT_TYPE_INTERVENTION" ("ELEMENT_PEDAGOGIQUE_ID", "TYPE_INTERVENTION_ID") AS 
  SELECT
  ep.id element_pedagogique_id,
  ti.id type_intervention_id
FROM
  element_pedagogique ep
  JOIN structure s_ep ON s_ep.id = ep.structure_id
  JOIN type_intervention ti ON 1=1
  LEFT JOIN TYPE_INTERVENTION_EP ti_ep ON TI_EP.ELEMENT_PEDAGOGIQUE_ID = EP.ID AND TI_EP.TYPE_INTERVENTION_ID = TI.ID AND TI_EP.HISTO_CREATION <= SYSDATE AND NVL(TI_EP.HISTO_DESTRUCTION,SYSDATE) >= SYSDATE
  --LEFT JOIN TYPE_INTERVENTION_STRUCTURE ti_s ON OSE_DIVERS.STRUCTURE_DANS_STRUCTURE( EP.STRUCTURE_ID, TI_S.STRUCTURE_ID) = 1 AND TI_S.TYPE_INTERVENTION_ID = TI.ID AND TI_S.HISTO_CREATION <= SYSDATE AND NVL(TI_S.HISTO_DESTRUCTION,SYSDATE) >= SYSDATE
  LEFT JOIN TYPE_INTERVENTION_STRUCTURE ti_s ON S_EP.STRUCTURE_NIV2_ID = TI_S.STRUCTURE_ID AND TI_S.TYPE_INTERVENTION_ID = TI.ID AND TI_S.HISTO_CREATION <= SYSDATE AND NVL(TI_S.HISTO_DESTRUCTION,SYSDATE) >= SYSDATE
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
--------------------------------------------------------
--  DDL for View V_FORMULE_A_PAYER
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_A_PAYER" ("INTERVENANT_ID", "MONTANT") AS 
  SELECT
  fhc.intervenant_id,
  CASE WHEN fhc.heures < 0 THEN 0 ELSE
  fhc.heures 
  * (SELECT MAX(valeur)
     FROM TAUX_HORAIRE_HETD thh
     WHERE
       ose_formule.get_date_obs BETWEEN thh.validite_debut AND NVL(thh.validite_fin,ose_formule.get_date_obs)
       AND thh.histo_destruction IS NULL) END montant
FROM
  V_FORMULE_HEURES_COMP fhc;
--------------------------------------------------------
--  DDL for View V_FORMULE_HEURES_COMP
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_HEURES_COMP" ("INTERVENANT_ID", "HEURES") AS 
  SELECT
  frr.intervenant_id,
  CASE WHEN frr.reste_a_payer < 0 THEN
    frr.sum_pourc_serv * SUM(NVL(fvh.heures,0)) + NVL(fsr.heures,0) - NVL(fsd.heures,0) - NVL(FMSD.heures,0)
  ELSE
    frr.reste_a_payer * frr.sum_pourc_comp + NVL(fsr.heures,0)
  END heures
FROM
  V_FORMULE_REEVAL_RESTEAPAYER frr
  LEFT JOIN V_FORMULE_VOLUME_HORAIRE fvh ON FVH.INTERVENANT_ID = FRR.INTERVENANT_ID
  LEFT JOIN V_FORMULE_SERVICE_REFERENTIEL fsr ON fsr.intervenant_id = frr.intervenant_id
  LEFT JOIN V_FORMULE_SERVICE_DU fsd ON fsd.intervenant_id = frr.intervenant_id
  LEFT JOIN V_FORMULE_MODIF_SERVICE_DU fmsd ON FMSD.INTERVENANT_ID = FSD.INTERVENANT_ID
GROUP BY
  frr.intervenant_id,
  frr.reste_a_payer,
  frr.sum_pourc_serv,
  frr.sum_pourc_comp,
  fsr.heures,
  fsd.heures,
  FMSD.heures;
--------------------------------------------------------
--  DDL for View V_FORMULE_HEURES_HETD
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_HEURES_HETD" ("INTERVENANT_ID", "HEURES") AS 
  SELECT
  frr.intervenant_id,
  CASE WHEN NVL(fvht.heures,0) > NVL(fsr.heures,0) THEN
  
    NVL(fsref.heures,0)
    + NVL(fsr.heures,0) * NVL(frr.sum_pourc_serv,1)
    + (NVL(fvht.heures,0) - NVL(fsr.heures,0)) * NVL(frr.sum_pourc_comp,1)
  
  ELSE
  
    NVL(fvht.heures,0) *  NVL(frr.sum_pourc_serv,1)
    
  END heures
FROM
  V_FORMULE_REEVAL_RESTEAPAYER frr
  LEFT JOIN V_FORMULE_SERVICE_REFERENTIEL fsref ON fsref.intervenant_id = frr.intervenant_id
  LEFT JOIN V_FORMULE_SERVICE_RESTANT fsr ON fsr.intervenant_id = frr.intervenant_id
  LEFT JOIN V_FORMULE_VOLUME_HORAIRE_TOTAL fvht ON FVHT.INTERVENANT_ID = FRR.INTERVENANT_ID;
--------------------------------------------------------
--  DDL for View V_FORMULE_MODIF_SERVICE_DU
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_MODIF_SERVICE_DU" ("INTERVENANT_ID", "HEURES") AS 
  SELECT
      msd.intervenant_id, SUM( msd.heures * mms.multiplicateur ) heures
    FROM
      modification_service_du msd
      JOIN MOTIF_MODIFICATION_SERVICE mms ON mms.id = msd.motif_id
    WHERE
      ose_formule.get_date_obs BETWEEN msd.validite_debut AND NVL(msd.validite_fin,ose_formule.get_date_obs)
      AND ose_formule.get_date_obs BETWEEN msd.histo_creation AND NVL(msd.histo_destruction,ose_formule.get_date_obs)
      AND msd.annee_id = OSE_PARAMETRE.GET_ANNEE
    GROUP BY msd.intervenant_id;
--------------------------------------------------------
--  DDL for View V_FORMULE_PONDERATION_ELEMENT
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_PONDERATION_ELEMENT" ("ELEMENT_PEDAGOGIQUE_ID", "PONDERATION_SERVICE_DU", "PONDERATION_SERVICE_COMPL") AS 
  SELECT
  EM.ELEMENT_ID element_pedagogique_id,
  EXP (SUM (LN (m.ponderation_service_du))) ponderation_service_du,
  EXP (SUM (LN (m.ponderation_service_compl))) ponderation_service_compl
FROM
  element_modulateur em
  JOIN modulateur m ON m.id = em.modulateur_id
WHERE
  ose_formule.get_date_obs BETWEEN em.histo_creation AND NVL(em.histo_destruction,ose_formule.get_date_obs)
  AND ose_formule.get_date_obs BETWEEN m.validite_debut AND NVL(m.validite_fin,ose_formule.get_date_obs)
  AND ose_formule.get_date_obs BETWEEN m.histo_creation AND NVL(m.histo_destruction,ose_formule.get_date_obs)
  AND em.annee_id = OSE_PARAMETRE.GET_ANNEE
GROUP BY em.element_id;
--------------------------------------------------------
--  DDL for View V_FORMULE_REEVAL_RESTEAPAYER
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_REEVAL_RESTEAPAYER" ("INTERVENANT_ID", "SUM_POURC_SERV", "SUM_POURC_COMP", "REEVAL_SERV", "RESTE_A_PAYER") AS 
  SELECT
  NVL(fv.intervenant_id,FSD.INTERVENANT_ID),
  CASE WHEN FV.intervenant_id IS NULL THEN 1 ELSE SUM(FV.POURC_SERV) END sum_pourc_serv,
  CASE WHEN FV.intervenant_id IS NULL THEN 1 ELSE SUM(FV.POURC_COMP) END sum_pourc_comp,
  (NVL(fsd.heures,0) + NVL(fmsd.heures,0)) / CASE WHEN FV.intervenant_id IS NULL THEN 1 ELSE SUM(FV.POURC_SERV) END reeval_serv,
  NVL(fvht.heures,0) - (NVL(fsd.heures,0) + NVL(fmsd.heures,0)) / CASE WHEN FV.intervenant_id IS NULL THEN 1 ELSE SUM(FV.POURC_SERV) END reste_a_payer
FROM
  v_formule_ventilation fv
  FULL JOIN V_FORMULE_SERVICE_DU fsd ON FSD.INTERVENANT_ID = FV.INTERVENANT_ID
  FULL JOIN V_FORMULE_MODIF_SERVICE_DU fmsd ON FMSD.INTERVENANT_ID = FV.INTERVENANT_ID
  FULL JOIN V_FORMULE_SERVICE_RESTANT fsr ON FSR.INTERVENANT_ID = FV.INTERVENANT_ID
  FULL JOIN V_FORMULE_VOLUME_HORAIRE_TOTAL fvht ON fvht.intervenant_id = fv.intervenant_id
GROUP BY
  fv.intervenant_id,
  FSD.INTERVENANT_ID,
  fsd.heures,
  fsr.heures,
  fmsd.heures,
  fvht.heures;
--------------------------------------------------------
--  DDL for View V_FORMULE_SERVICE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_SERVICE" ("SERVICE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "TYPE_SERVICE", "ELEMENT_PEDAGOGIQUE_ID", "ETABLISSEMENT_ID", "HEURES") AS 
  SELECT
  s.id service_id,
  s.intervenant_id,
  s.structure_ens_id structure_id,
  CASE WHEN s.etablissement_id = OSE_PARAMETRE.GET_ETABLISSEMENT THEN 'interne' ELSE 'externe' END type_service,
  s.element_pedagogique_id,
  s.etablissement_id,
  SUM(vh.heures) heures
FROM
  service s
  JOIN volume_horaire vh ON vh.service_id = s.id
  JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id
WHERE
  ose_formule.get_date_obs BETWEEN s.validite_debut AND NVL(s.validite_fin,ose_formule.get_date_obs)
  AND ose_formule.get_date_obs BETWEEN s.histo_creation AND NVL(s.histo_destruction,ose_formule.get_date_obs)
  AND s.annee_id = OSE_PARAMETRE.GET_ANNEE
  AND ose_formule.get_date_obs BETWEEN vh.validite_debut AND NVL(vh.validite_fin,ose_formule.get_date_obs)
  AND ose_formule.get_date_obs BETWEEN vh.histo_creation AND NVL(vh.histo_destruction,ose_formule.get_date_obs)
  AND tvh.code = 'PREVU'
  AND vh.motif_non_paiement_id IS NULL
GROUP BY
  s.id,
  s.intervenant_id,
  s.structure_ens_id,
  s.etablissement_id,
  s.element_pedagogique_id;
--------------------------------------------------------
--  DDL for View V_FORMULE_SERVICE_DU
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_SERVICE_DU" ("INTERVENANT_ID", "HEURES") AS 
  SELECT
      sd.intervenant_id, SUM(heures) heures
    FROM
      service_du sd
    WHERE
      ose_formule.get_date_obs BETWEEN sd.validite_debut AND NVL(sd.validite_fin,ose_formule.get_date_obs)
      AND ose_formule.get_date_obs BETWEEN sd.histo_creation AND NVL(sd.histo_destruction,ose_formule.get_date_obs)
      AND sd.annee_id = OSE_PARAMETRE.GET_ANNEE
    GROUP BY
      sd.intervenant_id;
--------------------------------------------------------
--  DDL for View V_FORMULE_SERVICE_DU_MODIFIE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_SERVICE_DU_MODIFIE" ("INTERVENANT_ID", "HEURES") AS 
  SELECT
  fsd.intervenant_id, 
  NVL(fsd.heures,0) + NVL(fmsd.heures,0) heures
FROM
  V_FORMULE_SERVICE_DU fsd
  LEFT JOIN V_FORMULE_MODIF_SERVICE_DU fmsd ON FMSD.INTERVENANT_ID = FSD.INTERVENANT_ID;
--------------------------------------------------------
--  DDL for View V_FORMULE_SERVICE_REFERENTIEL
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_SERVICE_REFERENTIEL" ("INTERVENANT_ID", "HEURES") AS 
  SELECT
      sr.intervenant_id, SUM( sr.heures ) heures
    FROM
      service_referentiel sr
    WHERE
      ose_formule.get_date_obs BETWEEN sr.validite_debut AND NVL(sr.validite_fin,ose_formule.get_date_obs)
      AND ose_formule.get_date_obs BETWEEN sr.histo_creation AND NVL(sr.histo_destruction,ose_formule.get_date_obs)
      AND sr.annee_id = OSE_PARAMETRE.GET_ANNEE
    GROUP BY sr.intervenant_id;
--------------------------------------------------------
--  DDL for View V_FORMULE_SERVICE_RESTANT
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_SERVICE_RESTANT" ("INTERVENANT_ID", "HEURES") AS 
  SELECT
  fsd.intervenant_id, 
  NVL(fsd.heures,0) + NVL(fmsd.heures,0) - NVL(fsr.heures,0) heures
FROM
  V_FORMULE_SERVICE_DU fsd
  LEFT JOIN V_FORMULE_MODIF_SERVICE_DU fmsd ON FMSD.INTERVENANT_ID = FSD.INTERVENANT_ID
  LEFT JOIN V_FORMULE_SERVICE_REFERENTIEL fsr ON FSR.INTERVENANT_ID = FSD.INTERVENANT_ID;
--------------------------------------------------------
--  DDL for View V_FORMULE_VENTILATION
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_VENTILATION" ("INTERVENANT_ID", "TYPE_INTERVENTION_ID", "HEURES", "POURC_SERV", "POURC_COMP") AS 
  SELECT
  fvh.intervenant_id,
  fvh.type_intervention_id,
  SUM(fvh.heures) heures,
  CASE WHEN NVL(fvht.heures,0) = 0 THEN 0 ELSE SUM(fvh.heures_serv) / NVL(fvht.heures,0) END pourc_serv,
  CASE WHEN NVL(fvht.heures,0) = 0 THEN 1 ELSE SUM(fvh.heures_comp) / NVL(fvht.heures,0) END pourc_comp
FROM
  V_FORMULE_VOLUME_HORAIRE fvh
  JOIN V_FORMULE_VOLUME_HORAIRE_TOTAL fvht ON fvht.intervenant_id = fvh.intervenant_id
GROUP BY
  fvh.intervenant_id,
  fvh.type_intervention_id,
  fvht.heures;
--------------------------------------------------------
--  DDL for View V_FORMULE_VOLUME_HORAIRE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_VOLUME_HORAIRE" ("SERVICE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "TYPE_INTERVENTION_ID", "HEURES", "TX_SERV", "TX_COMP", "HEURES_SERV", "HEURES_COMP") AS 
  SELECT
  s.id service_id,
  s.intervenant_id,
  s.structure_ens_id structure_id,
  vh.type_intervention_id,
  SUM(vh.heures) heures,
  TI.TAUX_HETD_SERVICE * NVL(FPE.PONDERATION_SERVICE_DU,1) tx_serv,
  TI.TAUX_HETD_COMPLEMENTAIRE * NVL(FPE.PONDERATION_SERVICE_COMPL,1) tx_comp,
  SUM(vh.heures) * TI.TAUX_HETD_SERVICE * NVL(FPE.PONDERATION_SERVICE_DU,1)  heures_serv,
  SUM(vh.heures) * TI.TAUX_HETD_COMPLEMENTAIRE * NVL(FPE.PONDERATION_SERVICE_COMPL,1) heures_comp
FROM
  service s
  JOIN volume_horaire vh ON vh.service_id = s.id
  JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id
  JOIN type_intervention ti ON ti.id = vh.type_intervention_id
  LEFT JOIN v_formule_ponderation_element fpe ON FPE.ELEMENT_PEDAGOGIQUE_ID = S.ELEMENT_PEDAGOGIQUE_ID
WHERE
  ose_formule.get_date_obs BETWEEN s.validite_debut AND NVL(s.validite_fin,ose_formule.get_date_obs)
  AND ose_formule.get_date_obs BETWEEN s.histo_creation AND NVL(s.histo_destruction,ose_formule.get_date_obs)
  AND s.annee_id = OSE_PARAMETRE.GET_ANNEE
  AND ose_formule.get_date_obs BETWEEN vh.validite_debut AND NVL(vh.validite_fin,ose_formule.get_date_obs)
  AND ose_formule.get_date_obs BETWEEN vh.histo_creation AND NVL(vh.histo_destruction,ose_formule.get_date_obs)
  AND tvh.code = 'PREVU'
  AND VH.MOTIF_NON_PAIEMENT_ID IS NULL
GROUP BY
  s.id,
  s.intervenant_id,
  s.structure_ens_id,
  vh.type_intervention_id,
  TI.TAUX_HETD_SERVICE,
  TI.TAUX_HETD_COMPLEMENTAIRE,
  FPE.PONDERATION_SERVICE_DU,
  FPE.PONDERATION_SERVICE_COMPL;
--------------------------------------------------------
--  DDL for View V_FORMULE_VOLUME_HORAIRE_TOTAL
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_VOLUME_HORAIRE_TOTAL" ("INTERVENANT_ID", "HEURES") AS 
  SELECT
  intervenant_id,
  SUM(heures)
FROM
  V_FORMULE_VOLUME_HORAIRE fvh
GROUP BY
  intervenant_id;
--------------------------------------------------------
--  DDL for View V_IMPORT_TAB_COLS
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_IMPORT_TAB_COLS" ("TABLE_NAME", "COLUMN_NAME", "DATA_TYPE", "LENGTH", "NULLABLE", "HAS_DEFAULT", "C_TABLE_NAME", "C_COLUMN_NAME", "IMPORT_ACTIF") AS 
  WITH importable_tables (table_name )AS (
  SELECT
  t.table_name
FROM
  user_tab_cols c
  join user_tables t on t.table_name = c.table_name
WHERE
  c.column_name = 'SOURCE_CODE'

MINUS

SELECT
  mview_name table_name
FROM
  USER_MVIEWS
), c_values (table_name, column_name, c_table_name, c_column_name) AS (
SELECT
  tc.table_name,
  tc.column_name,
  pcc.table_name c_table_name,
  pcc.column_name c_column_name
FROM
  user_tab_cols tc  
  JOIN USER_CONS_COLUMNS cc ON cc.table_name = tc.table_name AND cc.column_name = tc.column_name
  JOIN USER_CONSTRAINTS c ON c.constraint_name = cc.constraint_name
  JOIN USER_CONSTRAINTS pc ON pc.constraint_name = c.r_constraint_name
  JOIN USER_CONS_COLUMNS pcc ON pcc.constraint_name = pc.constraint_name
WHERE
  c.constraint_type = 'R' AND pc.constraint_type = 'P'
)
SELECT
  tc.table_name,
  tc.column_name,
  tc.data_type,
  CASE WHEN tc.char_length = 0 THEN NULL ELSE tc.char_length END length,
  CASE WHEN tc.nullable = 'Y' THEN 1 ELSE 0 END nullable,
  CASE WHEN tc.data_default IS NOT NULL THEN 1 ELSE 0 END has_default,
  cv.c_table_name,
  cv.c_column_name,
  CASE WHEN stc.table_name IS NULL THEN 0 ELSE 1 END AS import_actif
FROM
  user_tab_cols tc
  JOIN importable_tables t ON t.table_name = tc.table_name
  LEFT JOIN c_values cv ON cv.table_name = tc.table_name AND cv.column_name = tc.column_name
  LEFT JOIN user_tab_cols stc ON stc.table_name = 'SRC_' || tc.table_name AND stc.column_name = tc.column_name
WHERE
  tc.column_name not like 'HISTO_%'
ORDER BY
  tc.table_name, tc.column_id;
--------------------------------------------------------
--  DDL for View V_PJ_HEURES
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_PJ_HEURES" ("NOM_USUEL", "PRENOM", "INTERVENANT_ID", "SOURCE_CODE", "ANNEE_ID", "CATEG", "TOTAL_HEURES") AS 
  SELECT i.NOM_USUEL, i.PRENOM, i.id intervenant_id, i.SOURCE_CODE, s.annee_id, 'service' categ, sum(vh.HEURES) as total_heures
  from INTERVENANT i 
  join SERVICE s on s.INTERVENANT_ID = i.id                                   and s.HISTO_DESTRUCTEUR_ID is null and sysdate between s.VALIDITE_DEBUT and nvl(s.VALIDITE_FIN, sysdate)
  join VOLUME_HORAIRE vh on vh.SERVICE_ID = s.id                         and vh.HISTO_DESTRUCTEUR_ID is null and sysdate between vh.VALIDITE_DEBUT and nvl(vh.VALIDITE_FIN, sysdate)
  join type_volume_horaire tvh ON TVH.ID = VH.TYPE_VOLUME_HORAIRE_ID AND (tvh.code = 'PREVU')
  join ELEMENT_PEDAGOGIQUE ep on s.ELEMENT_PEDAGOGIQUE_ID = ep.id        and ep.HISTO_DESTRUCTEUR_ID is null and sysdate between ep.VALIDITE_DEBUT and nvl(ep.VALIDITE_FIN, sysdate)
  join ETAPE e on ep.ETAPE_ID = e.id and e.HISTO_DESTRUCTEUR_ID is null  and sysdate between e.VALIDITE_DEBUT and nvl(e.VALIDITE_FIN, sysdate)
  where i.HISTO_DESTRUCTEUR_ID is null
  group by i.NOM_USUEL, i.PRENOM, i.id, i.SOURCE_CODE, s.annee_id, 'service'
UNION
  select i.NOM_USUEL, i.PRENOM, i.id intervenant_id, i.SOURCE_CODE, s.annee_id, 'referentiel' categ, sum(s.HEURES) as total_heures
  from INTERVENANT i 
  join SERVICE_REFERENTIEL s on s.INTERVENANT_ID = i.id and s.HISTO_DESTRUCTEUR_ID is null and sysdate between s.VALIDITE_DEBUT and nvl(s.VALIDITE_FIN, sysdate)
  where i.HISTO_DESTRUCTEUR_ID is null
  group by i.NOM_USUEL, i.PRENOM, i.id, i.SOURCE_CODE, s.annee_id, 'referentiel';
--------------------------------------------------------
--  DDL for View V_RESUME_REFERENTIEL
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_RESUME_REFERENTIEL" ("NOM_USUEL", "PRENOM", "INTERVENANT_ID", "SOURCE_CODE", "TYPE_INTERVENANT_CODE", "STRUCTURE_ENS_ID", "STRUCTURE_AFF_ID", "SERVICE_ID", "ANNEE_ID", "TOTAL_HEURES") AS 
  select 
    i.NOM_USUEL, i.PRENOM, i.id intervenant_id, i.SOURCE_CODE, ti.CODE type_intervenant_code,
    s.STRUCTURE_ID STRUCTURE_ENS_ID, i.STRUCTURE_ID STRUCTURE_AFF_ID,
    s.id service_id, s.annee_id,
    sum(nvl(s.HEURES, 0)) as total_heures
  from INTERVENANT i 
  join TYPE_INTERVENANT ti on i.TYPE_ID = ti.id 
  join SERVICE_REFERENTIEL s on s.INTERVENANT_ID = i.id               and s.HISTO_DESTRUCTEUR_ID is null and sysdate between s.VALIDITE_DEBUT and nvl(s.VALIDITE_FIN, sysdate)
  where i.HISTO_DESTRUCTEUR_ID is null
  group by 
    i.NOM_USUEL, i.PRENOM, i.id, i.SOURCE_CODE, ti.CODE,
    s.STRUCTURE_ID, i.STRUCTURE_ID,
    s.id, s.annee_id
  order by i.NOM_USUEL, i.PRENOM;
--------------------------------------------------------
--  DDL for View V_RESUME_SERVICE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_RESUME_SERVICE" ("NOM_USUEL", "PRENOM", "INTERVENANT_ID", "SOURCE_CODE", "TYPE_INTERVENANT_CODE", "TYPE_INTERVENTION_ID", "STRUCTURE_ENS_ID", "STRUCTURE_AFF_ID", "SERVICE_ID", "ELEMENT_PEDAGOGIQUE_ID", "ETAPE_ID", "ANNEE_ID", "TOTAL_HEURES", "SERVICE_DU", "HEURES_COMP") AS 
  SELECT
    i.NOM_USUEL, i.PRENOM, i.id intervenant_id, i.SOURCE_CODE, ti.CODE type_intervenant_code,
    vh.TYPE_INTERVENTION_ID, 
    s.STRUCTURE_ENS_ID STRUCTURE_ENS_ID,
    s.STRUCTURE_AFF_ID STRUCTURE_AFF_ID,
    s.id service_id, ep.id element_pedagogique_id, e.id etape_id, s.annee_id,
    sum(nvl(vh.HEURES, 0)) as total_heures,
    NVL(fsm.heures,0) AS service_du,
    NVL(fhc.heures,0) AS heures_comp
  from INTERVENANT i 
  join TYPE_INTERVENANT ti on i.TYPE_ID = ti.id 
  left join SERVICE s on s.INTERVENANT_ID = i.id                                   and s.HISTO_DESTRUCTEUR_ID is null and sysdate between s.VALIDITE_DEBUT and nvl(s.VALIDITE_FIN, sysdate)
  left join VOLUME_HORAIRE vh on vh.SERVICE_ID = s.id                         and vh.HISTO_DESTRUCTEUR_ID is null and sysdate between vh.VALIDITE_DEBUT and nvl(vh.VALIDITE_FIN, sysdate)
  left join type_volume_horaire tvh ON TVH.ID = VH.TYPE_VOLUME_HORAIRE_ID AND (tvh.code = 'PREVU')
  left join ELEMENT_PEDAGOGIQUE ep on s.ELEMENT_PEDAGOGIQUE_ID = ep.id        and ep.HISTO_DESTRUCTEUR_ID is null and sysdate between ep.VALIDITE_DEBUT and nvl(ep.VALIDITE_FIN, sysdate)
  left join ETAPE e on ep.ETAPE_ID = e.id and e.HISTO_DESTRUCTEUR_ID is null  and sysdate between e.VALIDITE_DEBUT and nvl(e.VALIDITE_FIN, sysdate)
  left join V_FORMULE_SERVICE_DU_MODIFIE fsm ON fsm.intervenant_id = i.id
  left join v_formule_heures_comp fhc ON fhc.intervenant_id = i.id
  where
    i.HISTO_DESTRUCTEUR_ID is null
    AND (exists( select * FROM service s where intervenant_id = i.id AND s.HISTO_DESTRUCTEUR_ID is null and sysdate between s.VALIDITE_DEBUT and nvl(s.VALIDITE_FIN, sysdate))
      OR exists( select * from service_referentiel sr WHERE sr.intervenant_id = i.id AND sr.HISTO_DESTRUCTEUR_ID is null and sysdate between sr.VALIDITE_DEBUT and nvl(sr.VALIDITE_FIN, sysdate)) )
  group by vh.TYPE_INTERVENTION_ID, s.STRUCTURE_ENS_ID, s.STRUCTURE_AFF_ID, ep.id, e.id, s.annee_id, i.NOM_USUEL, i.PRENOM, i.id, i.SOURCE_CODE, ti.CODE, s.id, fsm.heures, fhc.heures
  order by i.NOM_USUEL, i.PRENOM;
--------------------------------------------------------
--  DDL for View V_SERVICE_NON_VALIDE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_SERVICE_NON_VALIDE" ("ID", "INTERVENANT_ID", "SERVICE_ID", "VOLUME_HORAIRE_ID", "ELEMENT_PEDAGOGIQUE_ID", "LIBELLE", "HEURES") AS 
  select vh.ID, i.ID as intervenant_id, s.ID as service_id, vh.ID as volume_horaire_id, ep.id as element_pedagogique_id, ep.LIBELLE, vh.HEURES
  from service s
  inner join INTERVENANT i on s.INTERVENANT_ID = i.id
  inner join ELEMENT_PEDAGOGIQUE ep on s.ELEMENT_PEDAGOGIQUE_ID = ep.id and ep.HISTO_DESTRUCTION is null
  inner join VOLUME_HORAIRE vh on vh.SERVICE_ID = s.ID and vh.HISTO_DESTRUCTION is null
  left join VALIDATION_VOL_HORAIRE vvh on vvh.VOLUME_HORAIRE_ID = vh.ID
  left join VALIDATION v on vvh.VALIDATION_ID = v.ID
  left join TYPE_VALIDATION tv on v.TYPE_VALIDATION_ID = tv.ID
  where (v.ID is null or v.HISTO_DESTRUCTION is not null) and
  not exists (
    select * from VALIDATION_VOL_HORAIRE vvh2
    inner join VALIDATION v2 on vvh2.VALIDATION_ID = v2.ID and v2.HISTO_DESTRUCTION is null
    where vvh2.VOLUME_HORAIRE_ID = vvh.VOLUME_HORAIRE_ID
  );
--------------------------------------------------------
--  DDL for View V_SERVICE_VALIDE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_SERVICE_VALIDE" ("ID", "INTERVENANT_ID", "SERVICE_ID", "VOLUME_HORAIRE_ID", "ELEMENT_PEDAGOGIQUE_ID", "LIBELLE", "HEURES", "VALIDATION_ID", "CODE") AS 
  select vh.ID, i.ID as intervenant_id, s.ID as service_id, vh.ID as volume_horaire_id, ep.id as element_pedagogique_id, ep.LIBELLE, vh.HEURES, v.ID as validation_id, tv.CODE
  from service s
  inner join INTERVENANT i on s.INTERVENANT_ID = i.id
  inner join ELEMENT_PEDAGOGIQUE ep on s.ELEMENT_PEDAGOGIQUE_ID = ep.id
  inner join VOLUME_HORAIRE vh on vh.SERVICE_ID = s.ID
  inner join VALIDATION_VOL_HORAIRE vvh on vvh.VOLUME_HORAIRE_ID = vh.ID
  inner join VALIDATION v on vvh.VALIDATION_ID = v.ID
  inner join TYPE_VALIDATION tv on v.TYPE_VALIDATION_ID = tv.ID
  where v.HISTO_DESTRUCTION is null;
--------------------------------------------------------
--  DDL for View V_SYMPA_LISTE
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_SYMPA_LISTE" ("EMAIL") AS 
  select distinct p.email
  from role r
  inner join type_role tr on r.type_id = tr.id and tr.histo_destructeur_id is null
  inner join personnel p on r.personnel_id = p.id and p.histo_destructeur_id is null
  where tr.code in (
     'gestionnaire-composante'
    ,'responsable-composante'
    ,'responsable-drh'
    ,'gestionnaire-drh'
    ,'administrateur'
  )
  and r.histo_destructeur_id is null
  order by p.email;
--------------------------------------------------------
--  DDL for View V_VOLUME_HORAIRE_ETAT
--------------------------------------------------------

  CREATE OR REPLACE FORCE VIEW "OSE"."V_VOLUME_HORAIRE_ETAT" ("VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID") AS 
  SELECT 
  vh.id volume_horaire_id,
  evh.id etat_volume_horaire_id
FROM
  volume_horaire vh
  LEFT JOIN contrat c ON c.id = vh.contrat_id AND 1 = ose_divers.comprise_entre( c.histo_creation, c.histo_destruction )
  LEFT JOIN validation cv ON cv.id = c.validation_id AND 1 = ose_divers.comprise_entre( cv.histo_creation, cv.histo_destruction )
  JOIN etat_volume_horaire evh ON evh.code = CASE
    WHEN c.date_retour_signe IS NOT NULL THEN 'contrat-signe'
    WHEN cv.id IS NOT NULL THEN 'contrat-edite'
    WHEN EXISTS(
      SELECT * FROM validation v JOIN validation_vol_horaire vvh ON vvh.validation_id = v.id
      WHERE vvh.volume_horaire_id = vh.id AND 1 = ose_divers.comprise_entre( v.histo_creation, v.histo_destruction )
    ) THEN 'valide'
    ELSE 'saisi'
  END;
--------------------------------------------------------
--  DDL for Materialized View ANTHONY_MV_HARPEGE
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."ANTHONY_MV_HARPEGE" ("NO_INDIVIDU", "C_CIVILITE", "NOM_USUEL", "PRENOM", "NOM_PATRONYMIQUE", "D_NAISSANCE", "C_PAYS_NAISSANCE", "LL_PAYS_NAISSANCE", "C_DEPARTEMENT", "LL_DEPARTEMENT", "C_COMMUNE_NAISSANCE", "VILLE_DE_NAISSANCE", "C_PAYS_NATIONALITE", "LL_PAYS_NATIONALITE", "NO_TELEPHONE", "NO_TEL_PORTABLE", "NO_E_MAIL", "NO_INSEE", "CLE_INSEE", "ID_IND_BANQUE", "C_PAYS_ISO", "CLE_CONTROLE", "C_BANQUE", "C_GUICHET", "NO_COMPTE", "CLE_RIB", "C_BANQUE_BIC", "C_PAYS_BIC", "C_EMPLACEMENT", "C_BRANCHE", "CAV_D_DEB_CONTRAT_TRAV", "CAV_D_FIN_CONTRAT_TRAV", "AA_NO_SEQ_AFFECTATION", "AA_D_DEB_AFFECTATION", "AA_D_FIN_AFFECTATION", "AR_NO_SEQ_AFFE_RECH", "AR_D_FIN_AFFE_RECH", "UCBN_FLAG_ENS_NO_INDIVIDU", "UCBN_FLAG_ENS_DEB", "UCBN_FLAG_ENS_FIN", "CC_NO_SEQ_CHERCHEUR", "CC_D_DEB_STR_TRAV", "CC_D_FIN_STR_TRAV", "C_TYPE_CONTRAT_TRAV", "C_TYPE_POPULATION", "DATE_DEPART", "C_STRUCTURE_DEPART")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH FORCE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS WITH dernier_rib AS
  (
  SELECT no_dossier_pers, MAX(ID_IND_BANQUE) KEEP (DENSE_RANK LAST ORDER BY d_creation) id_ind_banque
  FROM individu_banque@harpprod GROUP BY no_dossier_pers
  ),
     depart AS
  (
  SELECT DISTINCT
    i.no_individu,
    MAX(greatest(
      CASE WHEN aa.no_seq_affectation IS NOT NULL THEN nvl(aa.d_fin_affectation,SYSDATE) ELSE to_date('01/01/1000','DD/MM/YYYY') END,
      CASE WHEN ar.no_seq_affe_rech IS NOT NULL THEN nvl(ar.d_fin_affe_rech,SYSDATE) ELSE to_date('01/01/1000','DD/MM/YYYY') END,
      CASE WHEN ufe.no_individu IS NOT NULL THEN nvl(ufe.fin,SYSDATE) ELSE to_date('01/01/1000','DD/MM/YYYY') END,
      CASE WHEN cc.no_seq_chercheur IS NOT NULL THEN nvl(cc.d_fin_str_trav,SYSDATE) ELSE to_date('01/01/1000','DD/MM/YYYY') END
    )) over(partition by i.no_individu)
     date_depart
  FROM
    individu@harpprod i
    LEFT JOIN ucbn_flag_enseignant@harpprod ufe ON ufe.no_individu = i.no_individu
    LEFT JOIN chercheur@harpprod cc ON cc.no_individu = i.no_individu
    LEFT JOIN affectation@harpprod aa ON aa.no_dossier_pers = i.no_individu
    LEFT JOIN affectation_recherche@harpprod ar ON ar.no_dossier_pers = i.no_individu
  )
SELECT
  -- Etat Civil
  individu.no_individu
  ,individu.c_civilite
  ,individu.nom_usuel
  ,individu.prenom
  ,individu.nom_patronymique
  ,individu.d_naissance
  ,pays_naissance.c_pays         "C_PAYS_NAISSANCE"
  ,pays_naissance.ll_pays        "LL_PAYS_NAISSANCE"
  ,departement.c_departement
  ,departement.ll_departement
  ,individu.c_commune_naissance
  ,individu.ville_de_naissance
  ,pays_nationalite.c_pays       "C_PAYS_NATIONALITE"
  ,pays_nationalite.ll_pays      "LL_PAYS_NATIONALITE"
  ,individu_telephone.no_telephone
  ,individu.no_tel_portable
  ,individu_e_mail.no_e_mail
  ,code_insee.no_insee
  ,code_insee.cle_insee
  -- Pour les coordonnees bancaires:
  ,ib.id_ind_banque
  ,ib.c_pays_iso
  ,ib.cle_controle
  ,ib.c_banque
  ,ib.c_guichet
  ,ib.no_compte
  ,ib.cle_rib
  ,ib.c_banque_bic
  ,ib.c_pays_bic
  ,ib.c_emplacement
  ,ib.c_branche
  -- Pour les structures associees
  ,cav.d_deb_contrat_trav "CAV_D_DEB_CONTRAT_TRAV"
  ,cav.d_fin_contrat_trav "CAV_D_FIN_CONTRAT_TRAV"
  ,aa.no_seq_affectation  "AA_NO_SEQ_AFFECTATION"
  ,aa.d_deb_affectation   "AA_D_DEB_AFFECTATION"
  ,aa.d_fin_affectation   "AA_D_FIN_AFFECTATION"
  ,ar.no_seq_affe_rech    "AR_NO_SEQ_AFFE_RECH"
  ,ar.d_fin_affe_rech     "AR_D_FIN_AFFE_RECH"
  ,ufe.no_individu        "UCBN_FLAG_ENS_NO_INDIVIDU"
  ,ufe.debut              "UCBN_FLAG_ENS_DEB"
  ,ufe.fin                "UCBN_FLAG_ENS_FIN"
  ,cc.no_seq_chercheur    "CC_NO_SEQ_CHERCHEUR"
  ,cc.d_deb_str_trav      "CC_D_DEB_STR_TRAV"
  ,cc.d_fin_str_trav      "CC_D_FIN_STR_TRAV"
  -- Pour le statut
  ,ct.c_type_contrat_trav
  ,ca.c_type_population
  -- Pour le dernier statut
  ,CASE depart.date_depart
    WHEN to_date('01/01/1000','DD/MM/YYYY') THEN NULL
    ELSE depart.date_depart
   END date_depart
  ,pbs_divers__cicg.c_structure_globale@harpprod(depart.no_individu, CASE depart.date_depart WHEN to_date('01/01/1000','DD/MM/YYYY') THEN SYSDATE else depart.date_depart END ) c_structure_depart
FROM
  -- Etat Civil
  individu@harpprod                     individu
  LEFT JOIN pays@harpprod               pays_naissance ON (pays_naissance.c_pays = individu.c_pays_naissance)
  LEFT JOIN departement@harpprod        departement ON (departement.c_departement = individu.c_dept_naissance)
  LEFT JOIN pays@harpprod               pays_nationalite ON (pays_nationalite.c_pays = individu.c_pays_nationnalite)
  LEFT JOIN individu_e_mail@harpprod    individu_e_mail ON (individu_e_mail.no_individu = individu.no_individu)
  LEFT JOIN individu_telephone@harpprod individu_telephone ON (individu_telephone.no_individu = individu.no_individu
                                                               AND individu_telephone.tem_tel_principal='O'
                                                               AND individu_telephone.tem_tel='O')
  LEFT JOIN code_insee@harpprod         code_insee ON (code_insee.no_dossier_pers = individu.no_individu)
  -- Pour la banque
  LEFT JOIN dernier_rib ON (individu.no_individu = dernier_rib.no_dossier_pers)
  LEFT JOIN individu_banque@harpprod ib ON (dernier_rib.id_ind_banque = ib.id_ind_banque)
  -- Pour les structures attachees
  LEFT JOIN ucbn_flag_enseignant@harpprod ufe ON ufe.no_individu = individu.no_individu
  LEFT JOIN chercheur@harpprod cc ON cc.no_individu = individu.no_individu
  LEFT JOIN affectation@harpprod aa ON aa.no_dossier_pers = individu.no_individu
  LEFT JOIN affectation_recherche@harpprod ar ON ar.no_dossier_pers = individu.no_individu
  -- Pour le statut
  LEFT JOIN carriere@harpprod ca ON (ca.no_dossier_pers = individu.no_individu AND ca.no_seq_carriere = aa.no_seq_carriere)
  LEFT JOIN contrat_travail@harpprod ct ON (individu.no_individu = ct.no_dossier_pers AND aa.no_contrat_travail = ct.no_contrat_travail)
  LEFT JOIN contrat_avenant@harpprod cav ON (cav.no_dossier_pers = ct.no_dossier_pers AND cav.no_contrat_travail = ct.no_contrat_travail )
  -- Pour le dernier statut
  LEFT JOIN depart ON (individu.no_individu=depart.no_individu)
;

   COMMENT ON MATERIALIZED VIEW "OSE"."ANTHONY_MV_HARPEGE"  IS 'snapshot table for snapshot OSE.ANTHONY_MV_HARPEGE';
--------------------------------------------------------
--  DDL for Materialized View MV_ADRESSE_INTERVENANT
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_ADRESSE_INTERVENANT" ("Z_INTERVENANT_ID", "PRINCIPALE", "TEL_DOMICILE", "MENTION_COMPLEMENTAIRE", "NO_VOIE", "NOM_VOIE", "LOCALITE", "CODE_POSTAL", "VILLE", "PAYS_CODE_INSEE", "PAYS_LIBELLE", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS SELECT
  
  LTRIM(TO_CHAR(NO_INDIVIDU,'99999999'))                Z_INTERVENANT_ID,
  CASE TEM_ADR_PERS_PRINC WHEN 'O' THEN 1 ELSE 0 END    PRINCIPALE,
  TRIM(TELEPHONE_DOMICILE)                              TEL_DOMICILE,
  TRIM(UPPER(HABITANT_CHEZ))                            MENTION_COMPLEMENTAIRE,
  NO_VOIE || CASE BIS_TER
    WHEN 'B' THEN ' BIS'
    WHEN 'T' THEN ' TER'
    WHEN 'Q' THEN ' QUATER'
    WHEN 'C' THEN ' QUINQUIES'
    ELSE ''
  END                                                   NO_VOIE,
  UPPER(TRIM(TRIM(V.L_VOIE) || ' ' || TRIM(NOM_VOIE)))  NOM_VOIE,
  LOCALITE                                              LOCALITE,
  COALESCE( CP_ETRANGER, CODE_POSTAL )                  CODE_POSTAL,
  TRIM(VILLE)                                           VILLE,
  PAYS.C_PAYS                                           PAYS_CODE_INSEE,
  PAYS.LL_PAYS                                          PAYS_LIBELLE,
  ose_import.get_source_id('Harpege')                   SOURCE_ID,
  to_char(ID_ADRESSE_PERSO)                             SOURCE_CODE,
  ADRESSE.D_CREATION                                    VALIDITE_DEBUT
FROM
  ADRESSE_PERSONNELLE@HARPPROD ADRESSE
  LEFT JOIN PAYS@HARPPROD PAYS ON (PAYS.C_PAYS = ADRESSE.C_PAYS)
  LEFT JOIN VOIRIE@HARPPROD V ON (V.C_VOIE = ADRESSE.C_VOIE);

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_ADRESSE_INTERVENANT"  IS 'snapshot table for snapshot OSE.MV_ADRESSE_INTERVENANT';
--------------------------------------------------------
--  DDL for Materialized View MV_ADRESSE_STRUCTURE
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_ADRESSE_STRUCTURE" ("Z_STRUCTURE_ID", "PRINCIPALE", "TELEPHONE", "NO_VOIE", "NOM_VOIE", "LOCALITE", "CODE_POSTAL", "VILLE", "PAYS_CODE_INSEE", "PAYS_LIBELLE", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS SELECT
  "Z_STRUCTURE_ID", "PRINCIPALE", "TELEPHONE", "NO_VOIE", "NOM_VOIE", "LOCALITE", "CODE_POSTAL", "VILLE", "PAYS_CODE_INSEE", "PAYS_LIBELLE", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN"
FROM (

SELECT DISTINCT
  ls.c_structure                                                  z_structure_id,
  CASE ls.tem_local_principal WHEN 'O' THEN 1 ELSE 0 END          principale,
  ls.no_telephone                                                 telephone,
  NO_VOIE_A || CASE BIS_TER_A
    WHEN 'B' THEN ' BIS'
    WHEN 'T' THEN ' TER'
    WHEN 'Q' THEN ' QUATER'
    WHEN 'C' THEN ' QUINQUIES'
    ELSE ''
  END                                                             NO_VOIE,
  UPPER(TRIM(TRIM(V.L_VOIE) || ' ' || TRIM(NOM_VOIE_A)))          NOM_VOIE,
  LOCALITE_A                                                      LOCALITE,
  COALESCE( CP_ETRANGER_ADMIN, CODE_POSTAL_A )                    CODE_POSTAL,
  TRIM(VILLE_A)                                                   VILLE,
  PAYS.C_PAYS                                                     PAYS_CODE_INSEE,
  PAYS.LL_PAYS                                                    PAYS_LIBELLE,
  ose_import.get_source_id('Harpege')                             source_id,
  to_char(aa.id_adresse_admin) || '_' || ls.c_structure           source_code,
  NVL(aa.d_deb_val, to_date('01/01/1950','DD/MM/YYYY'))           validite_debut,
  aa.d_fin_val                                                    validite_fin,
  COUNT(*) over(partition by aa.id_adresse_admin,ls.c_structure)  doublons
FROM
  adresse_administrat@harpprod aa
  JOIN "LOCAL"@harpprod l ON l.id_adresse_admin = aa.id_adresse_admin
  JOIN localisation_structure@harpprod ls ON ls.c_local = l.c_local
  LEFT JOIN PAYS@HARPPROD PAYS ON (PAYS.C_PAYS = aa.C_PAYS)
  LEFT JOIN VOIRIE@HARPPROD V ON (V.C_VOIE = aa.C_VOIE)
  
) tmp1

WHERE
  doublons = 1 OR principale = 1;

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_ADRESSE_STRUCTURE"  IS 'snapshot table for snapshot OSE.MV_ADRESSE_STRUCTURE';
--------------------------------------------------------
--  DDL for Materialized View MV_AFFECTATION_RECHERCHE
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_AFFECTATION_RECHERCHE" ("Z_STRUCTURE_ID", "Z_INTERVENANT_ID", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS SELECT
    to_char(AR.C_STRUCTURE)                       Z_STRUCTURE_ID,
    to_char(AR.NO_DOSSIER_PERS)                   Z_INTERVENANT_ID,
    ose_import.get_source_id('Harpege')           SOURCE_ID,
    MIN(to_char(AR.no_seq_affe_rech))             SOURCE_CODE,
    MIN(AR.D_DEB_AFFE_RECH)                       VALIDITE_DEBUT,
    MAX(AR.D_FIN_AFFE_RECH)                       VALIDITE_FIN
FROM
  affectation_recherche@harpprod ar
GROUP BY
  AR.C_STRUCTURE, AR.NO_DOSSIER_PERS;

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_AFFECTATION_RECHERCHE"  IS 'snapshot table for snapshot OSE.MV_AFFECTATION_RECHERCHE';
--------------------------------------------------------
--  DDL for Materialized View MV_CHEMIN_PEDAGOGIQUE
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_CHEMIN_PEDAGOGIQUE" ("Z_ELEMENT_PEDAGOGIQUE_ID", "Z_ETAPE_ID", "ORDRE", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS SELECT
  z_element_pedagogique_id,
  z_etape_id,
  rownum ordre,
  ose_import.get_source_id('Apogee') source_id,
  source_code,
  NVL(validite_debut,to_date('01/01/1950', 'DD/MM/YYYY')) validite_debut,
  validite_fin
FROM
  ucbn_ose_chemin_pedagogique@apoprod;

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_CHEMIN_PEDAGOGIQUE"  IS 'snapshot table for snapshot OSE.MV_CHEMIN_PEDAGOGIQUE';
--------------------------------------------------------
--  DDL for Materialized View MV_CORPS
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_CORPS" ("LIBELLE_LONG", "LIBELLE_COURT", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS select  
  ll_corps                            libelle_long,
  lc_corps                            libelle_court,
  OSE_IMPORT.GET_SOURCE_ID('Harpege') source_id,
  c_corps                             source_code,
  COALESCE(d_ouverture_corps,to_date('01/01/1950','DD/MM/YYYY')) validite_debut,
  d_fermeture_corps                   validite_fin
from
  corps@harpprod c;

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_CORPS"  IS 'snapshot table for snapshot OSE.MV_CORPS';
--------------------------------------------------------
--  DDL for Materialized View MV_DISCIPLINE
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_DISCIPLINE" ("LIBELLE_COURT", "LIBELLE_LONG", "ORDRE", "SOURCE_ID", "SOURCE_CODE")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS SELECT
  libelle_court,
  libelle_long,
  ordre,
  ose_import.get_source_id('Apogee') source_id,
  source_code
FROM
  ucbn_ose_discipline@apoprod;

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_DISCIPLINE"  IS 'snapshot table for snapshot OSE.MV_DISCIPLINE';
--------------------------------------------------------
--  DDL for Materialized View MV_ELEMENT_DISCIPLINE
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_ELEMENT_DISCIPLINE" ("Z_ELEMENT_PEDAGOGIQUE_ID", "Z_DISCIPLINE_ID", "SOURCE_ID", "SOURCE_CODE")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS SELECT
  z_element_pedagogique_id,
  z_discipline_id,
  ose_import.get_source_id('Apogee') source_id,
  source_code
FROM
  ucbn_ose_element_discipline@apoprod;

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_ELEMENT_DISCIPLINE"  IS 'snapshot table for snapshot OSE.MV_ELEMENT_DISCIPLINE';
--------------------------------------------------------
--  DDL for Materialized View MV_ELEMENT_PEDAGOGIQUE
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_ELEMENT_PEDAGOGIQUE" ("LIBELLE", "Z_ETAPE_ID", "Z_STRUCTURE_ID", "Z_PERIODE_ID", "FI", "FC", "FA", "TAUX_FOAD", "SOURCE_ID", "SOURCE_CODE")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS SELECT
  libelle,
  z_etape_id,
  z_structure_id,
  z_periode_id,
  fi,fc,fa,
  taux_foad,
  ose_import.get_source_id('Apogee') source_id,
  source_code
FROM
  ucbn_ose_element_pedagogique@apoprod;

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_ELEMENT_PEDAGOGIQUE"  IS 'snapshot table for snapshot OSE.MV_ELEMENT_PEDAGOGIQUE';
--------------------------------------------------------
--  DDL for Materialized View MV_ELEMENT_PORTEUR_PORTE
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_ELEMENT_PORTEUR_PORTE" ("Z_ELEMENT_PORTEUR_ID", "Z_ELEMENT_PORTE_ID", "Z_TYPE_INTERVENTION_ID", "SOURCE_ID", "SOURCE_CODE")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS SELECT
  z_element_porteur_id,
  z_element_porte_id,
  z_type_intervention_id,
  ose_import.get_source_id('Apogee') source_id,
  source_code
FROM
  ucbn_ose_element_porteur_porte@apoprod;

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_ELEMENT_PORTEUR_PORTE"  IS 'snapshot table for snapshot OSE.MV_ELEMENT_PORTEUR_PORTE';
--------------------------------------------------------
--  DDL for Materialized View MV_ETABLISSEMENT
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_ETABLISSEMENT" ("LIBELLE", "LOCALISATION", "DEPARTEMENT", "SOURCE_ID", "SOURCE_CODE")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS SELECT
  lib_off_etb as libelle,
  lic_etb as localisation,
  cod_dep as departement,
  ose_import.get_source_id('Apogee') as source_id,
  cod_etb as source_code
FROM
  etablissement@apoprod e;

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_ETABLISSEMENT"  IS 'snapshot table for snapshot OSE.MV_ETABLISSEMENT';
--------------------------------------------------------
--  DDL for Materialized View MV_ETAPE
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_ETAPE" ("LIBELLE", "Z_TYPE_FORMATION_ID", "NIVEAU", "SPECIFIQUE_ECHANGES", "Z_STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS SELECT
  libelle,
  z_type_formation_id,
  to_number(niveau) niveau,
  specifique_echanges,
  z_structure_id,
  ose_import.get_source_id('Apogee') source_id,
  source_code,
  validite_debut,
  validite_fin
FROM
  ucbn_ose_etape@apoprod;

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_ETAPE"  IS 'snapshot table for snapshot OSE.MV_ETAPE';
--------------------------------------------------------
--  DDL for Materialized View MV_GROUPE_TYPE_FORMATION
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_GROUPE_TYPE_FORMATION" ("LIBELLE_COURT", "LIBELLE_LONG", "ORDRE", "PERTINENCE_NIVEAU", "SOURCE_ID", "SOURCE_CODE")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS SELECT
  libelle_court,
  libelle_long,
  ordre,
  pertinence_niveau,
  ose_import.get_source_id('Apogee') source_id,
  source_code
FROM
  ucbn_ose_groupe_type_formation@apoprod;

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_GROUPE_TYPE_FORMATION"  IS 'snapshot table for snapshot OSE.MV_GROUPE_TYPE_FORMATION';
--------------------------------------------------------
--  DDL for Materialized View MV_HARP_IND_DER_STRUCT
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_HARP_IND_DER_STRUCT" ("NO_INDIVIDU", "DATE_DEPART", "C_STRUCTURE")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS SELECT
  no_individu,
  CASE date_depart WHEN to_date('01/01/1000','DD/MM/YYYY') THEN NULL else date_depart END date_depart,
  pbs_divers__cicg.c_structure_globale@harpprod(no_individu, CASE date_depart WHEN to_date('01/01/1000','DD/MM/YYYY') THEN SYSDATE else date_depart END ) c_structure
FROM
(
SELECT DISTINCT
  i.no_individu,
  MAX(greatest(
    CASE WHEN aa.no_seq_affectation IS NOT NULL THEN nvl(aa.d_fin_affectation,SYSDATE) ELSE to_date('01/01/1000','DD/MM/YYYY') END,
    CASE WHEN ar.no_seq_affe_rech IS NOT NULL THEN nvl(ar.d_fin_affe_rech,SYSDATE) ELSE to_date('01/01/1000','DD/MM/YYYY') END,
    CASE WHEN ufe.no_individu IS NOT NULL THEN nvl(ufe.fin,SYSDATE) ELSE to_date('01/01/1000','DD/MM/YYYY') END,
    CASE WHEN cc.no_seq_chercheur IS NOT NULL THEN nvl(cc.d_fin_str_trav,SYSDATE) ELSE to_date('01/01/1000','DD/MM/YYYY') END
  )) over(partition by i.no_individu)
   date_depart
FROM
  individu@harpprod i
  LEFT JOIN ucbn_flag_enseignant@harpprod ufe ON ufe.no_individu = i.no_individu
  LEFT JOIN chercheur@harpprod cc ON cc.no_individu = i.no_individu
  LEFT JOIN affectation@harpprod aa ON aa.no_dossier_pers = i.no_individu
  LEFT JOIN affectation_recherche@harpprod ar ON ar.no_dossier_pers = i.no_individu

) tmp1;

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_HARP_IND_DER_STRUCT"  IS 'snapshot table for snapshot OSE.MV_HARP_IND_DER_STRUCT';
--------------------------------------------------------
--  DDL for Materialized View MV_HARP_INDIVIDU_BANQUE
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_HARP_INDIVIDU_BANQUE" ("NO_INDIVIDU", "IBAN", "BIC")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS WITH comptes AS (SELECT
  i.no_dossier_pers no_individu,
  rank() over(partition by i.no_dossier_pers order by d_creation) rank_compte,
  count(*) over(partition by i.no_dossier_pers) nombre_comptes,
  CASE WHEN i.no_dossier_pers IS NOT NULL THEN
    trim( NVL(i.c_pays_iso || i.cle_controle,'FR00') || ' ' ||
    substr(i.c_banque,0,4) || ' ' ||
    substr(i.c_banque,5,1) || substr(i.c_guichet,0,3) || ' ' ||
    substr(i.c_guichet,4,2) || substr(i.no_compte,0,2) || ' ' ||
    substr(i.no_compte,3,4) || ' ' ||
    substr(i.no_compte,7,4) || ' ' ||
    substr(i.no_compte,11) || i.cle_rib) ELSE NULL END IBAN,
  CASE WHEN i.no_dossier_pers IS NOT NULL THEN i.c_banque_bic || ' ' || i.c_pays_bic || ' ' || i.c_emplacement || ' ' || i.c_branche ELSE NULL END BIC
from
  individu_banque@harpprod i
)
SELECT no_individu, iban, bic FROM comptes WHERE rank_compte = nombre_comptes;

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_HARP_INDIVIDU_BANQUE"  IS 'snapshot table for snapshot OSE.MV_HARP_INDIVIDU_BANQUE';
--------------------------------------------------------
--  DDL for Materialized View MV_HARP_INDIVIDU_STATUT
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_HARP_INDIVIDU_STATUT" ("NO_INDIVIDU", "STATUT", "TYPE_INTERVENANT")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS SELECT
  no_individu, 
  statut,
  ti.code type_intervenant
FROM
(
SELECT
  i.no_individu,
  CASE
    WHEN NVL(c.ordre,99999) > NVL(tp.ordre,99999) THEN COALESCE(tp.statut, c.statut, 'AUTRES')
    WHEN NVL(c.ordre,99999) <= NVL(tp.ordre,99999) THEN COALESCE(c.statut, tp.statut, 'AUTRES')
  END statut
FROM
  individu@harpprod i
  LEFT JOIN (SELECT DISTINCT
      ct.no_dossier_pers no_dossier_pers,
      si.source_code statut,
      si.ordre,
      min(si.ordre) over(partition BY ct.no_dossier_pers) AS min_ordre
    FROM
      contrat_travail@harpprod ct
      JOIN contrat_avenant@harpprod ca ON ca.no_dossier_pers = ct.no_dossier_pers AND ca.no_contrat_travail = ct.no_contrat_travail
      JOIN statut_intervenant si ON si.source_code = CASE 
        WHEN ct.c_type_contrat_trav IN ('MC','MA')                THEN 'ASS_MI_TPS'
        WHEN ct.c_type_contrat_trav IN ('AT')                     THEN 'ATER'
        WHEN ct.c_type_contrat_trav IN ('AX')                     THEN 'ATER_MI_TPS'
        WHEN ct.c_type_contrat_trav IN ('DO')                     THEN 'DOCTOR'
        WHEN ct.c_type_contrat_trav IN ('GI','PN')                THEN 'ENS_CONTRACT'
        WHEN ct.c_type_contrat_trav IN ('LT','LB')                THEN 'LECTEUR'
        WHEN ct.c_type_contrat_trav IN ('MB')                     THEN 'MAITRE_LANG'
        WHEN ct.c_type_contrat_trav IN ('C3','CA','CB','CD','HA','HS','S3','SX','SW','SY','CS','SZ','VA') THEN 'BIATSS'
        WHEN ct.c_type_contrat_trav IN ('CU','AH','CG','MM','PM','IN','DN','ET','NF') THEN 'NON_AUTORISE'
                                                                  ELSE 'AUTRES'
      END
    WHERE
      SYSDATE BETWEEN ca.d_deb_contrat_trav AND NVL(ca.d_fin_contrat_trav,SYSDATE)
  ) c ON c.no_dossier_pers = i.no_individu AND c.ordre = c.min_ordre
  LEFT JOIN (SELECT DISTINCT
      a.no_dossier_pers,
      si.source_code statut,
      si.ordre,
      min(si.ordre) over(partition BY a.no_dossier_pers) AS min_ordre
    FROM
      affectation@harpprod a
      JOIN carriere@harpprod c ON  c.no_dossier_pers = a.no_dossier_pers AND c.no_seq_carriere = a.no_seq_carriere
      JOIN statut_intervenant si ON si.source_code = CASE 
        WHEN c.c_type_population IN ('DA','OA','DC')                THEN 'ENS_2ND_DEG'
        WHEN c.c_type_population IN ('SA')                          THEN 'ENS_CH'
        WHEN c.c_type_population IN ('AA','AC','BA','IA','MA')      THEN 'BIATSS'
        WHEN c.c_type_population IN ('MG','SB')                     THEN 'NON_AUTORISE'
                                                                    ELSE 'AUTRES'
      END
    WHERE
      (SYSDATE BETWEEN a.d_deb_affectation AND COALESCE(a.d_fin_affectation,SYSDATE))
  ) tp ON tp.no_dossier_pers = i.no_individu AND tp.ordre = tp.min_ordre
  
) tmp
JOIN statut_intervenant si ON si.source_code = tmp.statut
JOIN type_intervenant ti ON ti.id = si.type_intervenant_id;

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_HARP_INDIVIDU_STATUT"  IS 'snapshot table for snapshot OSE.MV_HARP_INDIVIDU_STATUT';
--------------------------------------------------------
--  DDL for Materialized View MV_INTERVENANT
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_INTERVENANT" ("CIVILITE_ID", "NOM_USUEL", "PRENOM", "NOM_PATRONYMIQUE", "DATE_NAISSANCE", "PAYS_NAISSANCE_CODE_INSEE", "PAYS_NAISSANCE_LIBELLE", "DEP_NAISSANCE_CODE_INSEE", "DEP_NAISSANCE_LIBELLE", "VILLE_NAISSANCE_CODE_INSEE", "VILLE_NAISSANCE_LIBELLE", "PAYS_NATIONALITE_CODE_INSEE", "PAYS_NATIONALITE_LIBELLE", "TEL_PRO", "TEL_MOBILE", "EMAIL", "Z_TYPE_ID", "Z_STATUT_ID", "Z_STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE", "NUMERO_INSEE", "NUMERO_INSEE_CLE", "NUMERO_INSEE_PROVISOIRE", "IBAN", "BIC")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING TRUSTED CONSTRAINTS DISABLE QUERY REWRITE
  AS SELECT 
  ose_import.get_civilite_id(CASE individu.c_civilite 
    WHEN 'M.' THEN 'M.' ELSE 'Mme'
  END)                                            civilite_id,
  initcap(individu.nom_usuel)                     nom_usuel,
  initcap(individu.prenom)                        prenom,
  initcap(individu.nom_patronymique)              nom_patronymique,
  individu.d_naissance                            date_naissance,
  pays_naissance.c_pays                           pays_naissance_code_insee,
  pays_naissance.ll_pays                          pays_naissance_libelle,
  departement.c_departement                       dep_naissance_code_insee,
  departement.ll_departement                      dep_naissance_libelle,
  individu.c_commune_naissance                    ville_naissance_code_insee,
  individu.ville_de_naissance                     ville_naissance_libelle,
  pays_nationalite.c_pays                         pays_nationalite_code_insee,
  pays_nationalite.ll_pays                        pays_nationalite_libelle,
  individu_telephone.no_telephone                 tel_pro,
  individu.no_tel_portable                        tel_mobile,
  --INDIVIDU_E_MAIL.NO_E_MAIL                       EMAIL,
  CASE 
    WHEN INDIVIDU_E_MAIL.NO_E_MAIL IS NULL AND individu.d_creation > SYSDATE -2 THEN 
      UCBN_LDAP.hid2mail(individu.no_individu)
    ELSE
      INDIVIDU_E_MAIL.NO_E_MAIL
  END                                             EMAIL,
  his.type_intervenant                            z_type_id,
  his.statut                                      z_statut_id,
  NVL(istr.c_structure, 'UNIV')                   z_structure_id,
  ose_import.get_source_id('Harpege')             source_id,
  ltrim(TO_CHAR(individu.no_individu,'99999999')) source_code,
--  null                                            prime_excellence_scientifique,
  code_insee.no_insee                             numero_insee,
  TO_CHAR(code_insee.cle_insee)                   numero_insee_cle,
  CASE WHEN code_insee.no_insee IS NULL THEN NULL ELSE 0 END numero_insee_provisoire,
  ib.iban iban,
  ib.bic bic
FROM
  individu@harpprod                     individu
  JOIN MV_HARP_IND_DER_STRUCT istr ON (istr.no_individu = individu.no_individu)
  JOIN mv_harp_individu_statut his ON his.no_individu = individu.no_individu
  LEFT JOIN pays@harpprod               pays_naissance ON (pays_naissance.c_pays = individu.c_pays_naissance)
  LEFT JOIN departement@harpprod        departement ON (departement.c_departement = individu.c_dept_naissance)
  LEFT JOIN pays@harpprod               pays_nationalite ON (pays_nationalite.c_pays = individu.c_pays_nationnalite)
  LEFT JOIN individu_e_mail@harpprod    individu_e_mail ON (individu_e_mail.no_individu = individu.no_individu)
  LEFT JOIN individu_telephone@harpprod individu_telephone ON (individu_telephone.no_individu = individu.no_individu AND individu_telephone.tem_tel_principal='O' AND individu_telephone.tem_tel='O')
  LEFT JOIN code_insee@harpprod         code_insee ON (code_insee.no_dossier_pers = individu.no_individu)
  LEFT JOIN mv_harp_individu_banque      ib ON (ib.no_individu = individu.no_individu);

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_INTERVENANT"  IS 'snapshot table for snapshot OSE.MV_INTERVENANT';
--------------------------------------------------------
--  DDL for Materialized View MV_INTERVENANT_EXTERIEUR
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_INTERVENANT_EXTERIEUR" ("SITUATION_FAMILIALE_ID", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS WITH validite ( no_individu, debut, fin ) AS (
  SELECT
    no_individu, MIN( date_debut ) date_debut, MAX( date_fin ) date_fin
  FROM (
      SELECT no_individu, ch.d_deb_str_trav date_debut, ch.d_fin_str_trav date_fin FROM chercheur@harpprod ch
      WHERE ose_import.get_date_obs IS NULL OR ose_import.get_date_obs BETWEEN ch.d_deb_str_trav AND COALESCE(ch.d_fin_str_trav,ose_import.get_date_obs)
    UNION
      SELECT a.no_dossier_pers, a.d_deb_affectation date_debut, a.d_fin_affectation date_fin FROM affectation@harpprod a
      WHERE ose_import.get_date_obs IS NULL OR ose_import.get_date_obs BETWEEN a.d_deb_affectation AND COALESCE(a.d_fin_affectation,ose_import.get_date_obs)
    UNION
      SELECT fe.NO_INDIVIDU, fe.DEBUT date_debut, fe.FIN date_fin FROM ucbn_flag_enseignant@harpprod fe
      WHERE ose_import.get_date_obs IS NULL OR ose_import.get_date_obs BETWEEN fe.DEBUT AND COALESCE(fe.FIN,ose_import.get_date_obs)
  )
  GROUP BY
    no_individu
) 
SELECT
--  null                                       type_intervenant_exterieur_id,
  s.id                                       situation_familiale_id,
--  null                                       regime_secu_id,
--  null                                       type_poste_id,
  ose_import.get_source_id('Harpege')        source_id,
  ltrim(TO_CHAR(i.no_individu,'99999999'))   source_code,
  nvl(validite.debut,TRUNC(SYSDATE))         validite_debut,
  validite.fin                               validite_fin
FROM
  individu@harpprod i
  JOIN MV_HARP_INDIVIDU_STATUT his ON his.no_individu = ltrim(TO_CHAR(i.no_individu,'99999999'))
  LEFT JOIN PERSONNEL@harpprod p ON (p.no_dossier_pers = i.no_individu)
  LEFT JOIN SITUATION_FAMILIALE s on (s.code = p.C_SITUATION_FAMILLE)
  LEFT JOIN validite ON (validite.no_individu = i.no_individu)
WHERE
  'E' = his.type_intervenant;

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_INTERVENANT_EXTERIEUR"  IS 'snapshot table for snapshot OSE.MV_INTERVENANT_EXTERIEUR';
--------------------------------------------------------
--  DDL for Materialized View MV_INTERVENANT_PERMANENT
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_INTERVENANT_PERMANENT" ("Z_CORPS_ID", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING TRUSTED CONSTRAINTS DISABLE QUERY REWRITE
  AS WITH validite ( no_individu, debut, fin ) AS (
  SELECT
    no_individu, MIN( date_debut ) date_debut, MAX( date_fin ) date_fin
  FROM (
      SELECT no_individu, ch.d_deb_str_trav date_debut, ch.d_fin_str_trav date_fin FROM chercheur@harpprod ch
      WHERE ose_import.get_date_obs IS NULL OR ose_import.get_date_obs BETWEEN ch.d_deb_str_trav AND COALESCE(ch.d_fin_str_trav,ose_import.get_date_obs)
    UNION
      SELECT a.no_dossier_pers, a.d_deb_affectation date_debut, a.d_fin_affectation date_fin FROM affectation@harpprod a
      WHERE ose_import.get_date_obs IS NULL OR ose_import.get_date_obs BETWEEN a.d_deb_affectation AND COALESCE(a.d_fin_affectation,ose_import.get_date_obs)
    UNION
      SELECT fe.NO_INDIVIDU, fe.DEBUT date_debut, fe.FIN date_fin FROM ucbn_flag_enseignant@harpprod fe
      WHERE ose_import.get_date_obs IS NULL OR ose_import.get_date_obs BETWEEN fe.DEBUT AND COALESCE(fe.FIN,ose_import.get_date_obs)
  )
  GROUP BY
    no_individu
)
SELECT  
  pbs_divers__cicg.c_corps@harpprod(i.no_individu, ose_import.get_date_obs) z_corps_id,
  --null section_cnu_id
  ose_import.get_source_id('Harpege')           source_id,
  ltrim(TO_CHAR(i.no_individu,'99999999'))      source_code,
  NVL(validite.debut,to_date('01/01/1950','DD/MM/YYYY'))              validite_debut,
  validite.fin                                  validite_fin
FROM
  individu@harpprod i
  JOIN MV_HARP_INDIVIDU_STATUT his ON his.no_individu = ltrim(TO_CHAR(i.no_individu,'99999999'))
  LEFT JOIN validite ON (validite.no_individu = i.no_individu)
WHERE
  'P' = his.type_intervenant;

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_INTERVENANT_PERMANENT"  IS 'snapshot table for snapshot OSE.MV_INTERVENANT_PERMANENT';
--------------------------------------------------------
--  DDL for Materialized View MV_PERSONNEL
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_PERSONNEL" ("CIVILITE_ID", "NOM_USUEL", "PRENOM", "NOM_PATRONYMIQUE", "DATE_NAISSANCE", "EMAIL", "Z_STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS WITH aff_ifs (no_dossier_pers, debut, fin) AS (

  SELECT
    ifs.no_dossier_pers,
    ifs.DT_DEB_EXERC_RESP debut,
    ifs.DT_FIN_EXERC_RESP fin
  FROM
    individu_fonct_struct@harpprod ifs
    
  UNION

  SELECT
    aff.no_dossier_pers,
    aff.d_deb_affectation         debut,
    aff.d_fin_affectation         fin
  FROM
    affectation@harpprod aff
  )
  SELECT
  ose_import.get_civilite_id(CASE i.c_civilite 
    WHEN 'M.' THEN 'M.' ELSE 'Mme'
  END)                                     civilite_id,
  initcap(i.nom_usuel)                     nom_usuel,
  initcap(i.prenom)                        prenom,
  initcap(i.nom_patronymique)              nom_patronymique,
  i.d_naissance                            date_naissance,
  im.no_e_mail                             email,
  ids.c_structure                          z_structure_id,
  ose_import.get_source_id('Harpege')      source_id,
  ltrim(TO_CHAR(i.no_individu,'99999999')) source_code,
  MIN(ai.debut)                            validite_debut,
  MAX(ai.fin)                              validite_fin
FROM
  individu@harpprod i
  JOIN aff_ifs ai ON ai.no_dossier_pers = i.no_individu
  JOIN V_HARP_IND_DER_STRUCT ids ON ids.no_individu = i.no_individu
  JOIN individu_e_mail@harpprod    im ON (im.no_individu = i.no_individu)
WHERE
  OSE_IMPORT.GET_DATE_OBS BETWEEN ai.debut AND NVL(ai.fin,OSE_IMPORT.GET_DATE_OBS)
GROUP BY
  i.c_civilite,i.nom_usuel,i.prenom,i.nom_patronymique,i.d_naissance,im.no_e_mail,ids.c_structure,i.no_individu;

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_PERSONNEL"  IS 'snapshot table for snapshot OSE.MV_PERSONNEL';
--------------------------------------------------------
--  DDL for Materialized View MV_ROLE
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_ROLE" ("Z_STRUCTURE_ID", "Z_PERSONNEL_ID", "Z_TYPE_ID", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH FORCE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS SELECT DISTINCT
  z_structure_id,
  z_personnel_id,
  z_type_id,
  source_id,
  MIN( source_code) source_code,
  MIN( validite_debut ) validite_debut,
  MAX(validite_fin ) validite_fin
FROM ( SELECT
    CASE WHEN ifs.c_structure = 'UNIV' THEN NULL ELSE ifs.c_structure END z_structure_id,
    ifs.no_dossier_pers z_personnel_id,
    CASE 
      when fs.lc_fonction IN ('_D30a', '_D30b', '_D30c', '_D30d', '_D30e' ) then 'directeur-composante'
      when fs.lc_fonction IN ('_R00', '_R40', '_R40b')                      then 'responsable-composante'
      when fs.lc_fonction IN ('_R00c')                                      then 'responsable-recherche-labo'
      when ifs.c_structure = 'UNIV' AND fs.lc_fonction = '_P00' OR fs.lc_fonction LIKE '_P10%' OR fs.lc_fonction like '_P50%' then 'superviseur-etablissement'
      else NULL
    END z_type_id,
    ose_import.get_source_id('Harpege') as source_id,
    to_char(ifs.no_exercice_respons) source_code,
    ifs.DT_DEB_EXERC_RESP as validite_debut,
    ifs.DT_FIN_EXERC_RESP as validite_fin
  FROM
    individu_fonct_struct@harpprod ifs
    JOIN fonction_structurelle@harpprod fs ON fs.c_fonction = ifs.c_fonction
  WHERE
    OSE_IMPORT.GET_DATE_OBS BETWEEN ifs.DT_DEB_EXERC_RESP AND NVL(ifs.DT_FIN_EXERC_RESP,OSE_IMPORT.GET_DATE_OBS)
  ) tmp
WHERE
  tmp.z_type_id IS NOT NULL
GROUP BY
  z_structure_id, z_personnel_id, z_type_id,source_id;

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_ROLE"  IS 'snapshot table for snapshot OSE.MV_ROLE';
--------------------------------------------------------
--  DDL for Materialized View MV_STRUCTURE
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_STRUCTURE" ("LIBELLE_LONG", "LIBELLE_COURT", "Z_PARENTE_ID", "Z_STRUCTURE_NIV2_ID", "Z_TYPE_ID", "NIVEAU", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS SELECT
  ll_structure                                    as libelle_long,
  lc_structure                                    as libelle_court,
  c_structure_pere                                as z_parente_id,
  CASE LEVEL WHEN 2 THEN C_STRUCTURE ELSE STR_LEVEL2_CODE@harpprod(c_structure) END as z_structure_niv2_id,
  CASE c_statut_juridique
    WHEN 'UN' THEN 'UNI'
    WHEN 'C' THEN 'CMP'
    WHEN 'EC' THEN 'AUT'
    WHEN 'SC' THEN 'SCM'
    WHEN 'I' THEN 'STI'
    WHEN 'EI' THEN 'IEI'
    WHEN 'EP' THEN 'AUT'
    WHEN 'SE' THEN 'SIE'
    WHEN 'CH' THEN 'CHU'
    WHEN 'IU' THEN 'IUF'
    WHEN 'PR' THEN 'PRS'
    ELSE 'AUT'
  END                                             as z_type_id,
  LEVEL                                           as niveau,
  OSE_IMPORT.GET_SOURCE_ID('Harpege')             as source_id,
  c_structure                                     as source_code,
  date_ouverture                                  as validite_debut,
  date_fermeture                                  as validite_fin
FROM
  structure@harpprod
START WITH c_structure_pere IS NULL
CONNECT BY PRIOR c_structure = c_structure_pere;

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_STRUCTURE"  IS 'snapshot table for snapshot OSE.MV_STRUCTURE';
--------------------------------------------------------
--  DDL for Materialized View MV_TYPE_FORMATION
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_TYPE_FORMATION" ("LIBELLE_LONG", "LIBELLE_COURT", "Z_GROUPE_ID", "SOURCE_ID", "SOURCE_CODE")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS SELECT
  libelle_long,
  libelle_court,
  z_groupe_id,
  ose_import.get_source_id('Apogee') source_id,
  source_code
FROM
  ucbn_ose_type_formation@apoprod;

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_TYPE_FORMATION"  IS 'snapshot table for snapshot OSE.MV_TYPE_FORMATION';
--------------------------------------------------------
--  DDL for Materialized View MV_VOLUME_HORAIRE_ENS
--------------------------------------------------------

  CREATE MATERIALIZED VIEW "OSE"."MV_VOLUME_HORAIRE_ENS" ("Z_ELEMENT_DISCIPLINE_ID", "Z_TYPE_INTERVENTION_ID", "Z_ANNEE_ID", "HEURES", "SOURCE_ID", "SOURCE_CODE")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS SELECT
  z_element_discipline_id,
  trim(z_type_intervention_id),
  z_annee_id,
  heures,  
  ose_import.get_source_id('Apogee') source_id,
  source_code
FROM
  ucbn_ose_volume_horaire_ens@apoprod;

   COMMENT ON MATERIALIZED VIEW "OSE"."MV_VOLUME_HORAIRE_ENS"  IS 'snapshot table for snapshot OSE.MV_VOLUME_HORAIRE_ENS';
--------------------------------------------------------
--  DDL for Index TYPE_MODULATEUR_STRUCTURE_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_MODULATEUR_STRUCTURE_UN" ON "OSE"."TYPE_MODULATEUR_STRUCTURE" ("TYPE_MODULATEUR_ID", "STRUCTURE_ID") 
  ;
--------------------------------------------------------
--  DDL for Index MODULATEUR__UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MODULATEUR__UN" ON "OSE"."MODULATEUR" ("CODE") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_VALIDATION_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_VALIDATION_PK" ON "OSE"."TYPE_VALIDATION" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index MV_ANTHONY2_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_ANTHONY2_PK" ON "OSE"."MV_ANTHONY2" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_FORMATION__UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_FORMATION__UN" ON "OSE"."TYPE_FORMATION" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index MV_HARP_INDIVIDU_BANQUE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_HARP_INDIVIDU_BANQUE_PK" ON "OSE"."MV_HARP_INDIVIDU_BANQUE" ("NO_INDIVIDU") 
  ;
--------------------------------------------------------
--  DDL for Index SITUATION_FAMILIALE_CODE_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."SITUATION_FAMILIALE_CODE_UN" ON "OSE"."SITUATION_FAMILIALE" ("CODE") 
  ;
--------------------------------------------------------
--  DDL for Index SERVICE_ANNEE_IDX
--------------------------------------------------------

  CREATE INDEX "OSE"."SERVICE_ANNEE_IDX" ON "OSE"."SERVICE" ("ANNEE_ID") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_AGREMENT_STATUT_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_AGREMENT_STATUT_PK" ON "OSE"."TYPE_AGREMENT_STATUT" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index ELEMENT_TAUX_REGIMES_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ELEMENT_TAUX_REGIMES_PK" ON "OSE"."ELEMENT_TAUX_REGIMES" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_ROLE_CODE_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_ROLE_CODE_UN" ON "OSE"."TYPE_ROLE" ("CODE") 
  ;
--------------------------------------------------------
--  DDL for Index VOLUME_HORAIRE_TYPE_IDX
--------------------------------------------------------

  CREATE INDEX "OSE"."VOLUME_HORAIRE_TYPE_IDX" ON "OSE"."VOLUME_HORAIRE" ("TYPE_VOLUME_HORAIRE_ID") 
  ;
--------------------------------------------------------
--  DDL for Index PERSONNEL_SOURCE__UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."PERSONNEL_SOURCE__UN" ON "OSE"."PERSONNEL" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index ELEMENT_MODULATEUR_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ELEMENT_MODULATEUR_PK" ON "OSE"."ELEMENT_MODULATEUR" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index INTERVENANT_SOURCE__UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."INTERVENANT_SOURCE__UN" ON "OSE"."INTERVENANT" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index SERVICE_ELEMENT_IDX
--------------------------------------------------------

  CREATE INDEX "OSE"."SERVICE_ELEMENT_IDX" ON "OSE"."SERVICE" ("ELEMENT_PEDAGOGIQUE_ID") 
  ;
--------------------------------------------------------
--  DDL for Index MV_ADRESSE_INTERVENANT
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_ADRESSE_INTERVENANT" ON "OSE"."MV_ADRESSE_INTERVENANT" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index EMPLOI_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."EMPLOI_UN" ON "OSE"."EMPLOI" ("INTERVENANT_ID", "EMPLOYEUR_ID", "DATE_DEBUT") 
  ;
--------------------------------------------------------
--  DDL for Index VOLUME_HORAIRE_PERIODE_IDX
--------------------------------------------------------

  CREATE INDEX "OSE"."VOLUME_HORAIRE_PERIODE_IDX" ON "OSE"."VOLUME_HORAIRE" ("PERIODE_ID") 
  ;
--------------------------------------------------------
--  DDL for Index IP_SOURCE_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."IP_SOURCE_UN" ON "OSE"."INTERVENANT_PERMANENT" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index EPP_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."EPP_UN" ON "OSE"."ELEMENT_PORTEUR_PORTE" ("ELEMENT_PORTEUR_ID", "ELEMENT_PORTE_ID", "TYPE_INTERVENTION_ID") 
  ;
--------------------------------------------------------
--  DDL for Index MV_ELEMENT_DISCIPLINE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_ELEMENT_DISCIPLINE_PK" ON "OSE"."MV_ELEMENT_DISCIPLINE" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index ROLE_UTILISATEUR_LINKER_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ROLE_UTILISATEUR_LINKER_PK" ON "OSE"."ROLE_UTILISATEUR_LINKER" ("UTILISATEUR_ID", "ROLE_UTILISATEUR_ID") 
  ;
--------------------------------------------------------
--  DDL for Index MV_HARP_INDIVIDU_STATUT_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_HARP_INDIVIDU_STATUT_PK" ON "OSE"."MV_HARP_INDIVIDU_STATUT" ("NO_INDIVIDU") 
  ;
--------------------------------------------------------
--  DDL for Index SERVICE_HISTO_IDX
--------------------------------------------------------

  CREATE INDEX "OSE"."SERVICE_HISTO_IDX" ON "OSE"."SERVICE" ("HISTO_DESTRUCTION") 
  ;
--------------------------------------------------------
--  DDL for Index MV_HARP_IND_DER_STRUCT_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_HARP_IND_DER_STRUCT_PK" ON "OSE"."MV_HARP_IND_DER_STRUCT" ("NO_INDIVIDU") 
  ;
--------------------------------------------------------
--  DDL for Index SERVICE_SENS_IDX
--------------------------------------------------------

  CREATE INDEX "OSE"."SERVICE_SENS_IDX" ON "OSE"."SERVICE" ("STRUCTURE_ENS_ID") 
  ;
--------------------------------------------------------
--  DDL for Index UTILISATEUR_INTERVENANT_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."UTILISATEUR_INTERVENANT_UN" ON "OSE"."UTILISATEUR" ("INTERVENANT_ID") 
  ;
--------------------------------------------------------
--  DDL for Index VALIDATION_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."VALIDATION_PK" ON "OSE"."VALIDATION" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index EPP_SOURCE_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."EPP_SOURCE_UN" ON "OSE"."ELEMENT_PORTEUR_PORTE" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index CORPS_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."CORPS_PK" ON "OSE"."CORPS" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index UTILISATEUR_USERNAME_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."UTILISATEUR_USERNAME_UN" ON "OSE"."UTILISATEUR" ("USERNAME") 
  ;
--------------------------------------------------------
--  DDL for Index FORMULE_REFERENTIEL_MAJ_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."FORMULE_REFERENTIEL_MAJ_PK" ON "OSE"."FORMULE_REFERENTIEL_MAJ" ("INTERVENANT_ID", "ANNEE_ID") 
  ;
--------------------------------------------------------
--  DDL for Index EP_CODE__UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."EP_CODE__UN" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index AFFECTATION_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."AFFECTATION_PK" ON "OSE"."AFFECTATION_RECHERCHE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index IE_SOURCE_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."IE_SOURCE_UN" ON "OSE"."INTERVENANT_EXTERIEUR" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index MV_STRUCTURE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_STRUCTURE_PK" ON "OSE"."MV_STRUCTURE" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index ETABLISSEMENT_SOURCE_ID_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ETABLISSEMENT_SOURCE_ID_UN" ON "OSE"."ETABLISSEMENT" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index STATUT_INTERVENANT__UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."STATUT_INTERVENANT__UN" ON "OSE"."STATUT_INTERVENANT" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index INTERVENANT_PERMANENT_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."INTERVENANT_PERMANENT_PK" ON "OSE"."INTERVENANT_PERMANENT" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index ADRESSE_INTERVENANT_SOURCE_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ADRESSE_INTERVENANT_SOURCE_UN" ON "OSE"."ADRESSE_INTERVENANT" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index ELEMENT_PEDAGOGIQUE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ELEMENT_PEDAGOGIQUE_PK" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index SERVICE_REFERENTIEL_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."SERVICE_REFERENTIEL_PK" ON "OSE"."SERVICE_REFERENTIEL" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index CIVILITE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."CIVILITE_PK" ON "OSE"."CIVILITE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index VOLUME_HORAIRE_ENS_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."VOLUME_HORAIRE_ENS_PK" ON "OSE"."VOLUME_HORAIRE_ENS" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index ANNEE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ANNEE_PK" ON "OSE"."ANNEE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_AGREMENT__UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_AGREMENT__UN" ON "OSE"."TYPE_AGREMENT" ("CODE") 
  ;
--------------------------------------------------------
--  DDL for Index EMPLOI_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."EMPLOI_PK" ON "OSE"."EMPLOI" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index UTILISATEUR_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."UTILISATEUR_PK" ON "OSE"."UTILISATEUR" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_INTERVENANT_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_INTERVENANT_PK" ON "OSE"."TYPE_INTERVENANT" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_AGREMENT_STATUT__UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_AGREMENT_STATUT__UN" ON "OSE"."TYPE_AGREMENT_STATUT" ("TYPE_AGREMENT_ID", "STATUT_INTERVENANT_ID", "PREMIER_RECRUTEMENT") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_STRUCTURE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_STRUCTURE_PK" ON "OSE"."TYPE_STRUCTURE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index EMPLOYEUR_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."EMPLOYEUR_PK" ON "OSE"."EMPLOYEUR" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index VOLUME_HORAIRE_ENS_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."VOLUME_HORAIRE_ENS_UN" ON "OSE"."VOLUME_HORAIRE_ENS" ("ELEMENT_DISCIPLINE_ID", "TYPE_INTERVENTION_ID", "ANNEE_ID") 
  ;
--------------------------------------------------------
--  DDL for Index ETAPE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ETAPE_PK" ON "OSE"."ETAPE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index STRUCTURE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."STRUCTURE_PK" ON "OSE"."STRUCTURE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_MODULATEUR_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_MODULATEUR_PK" ON "OSE"."TYPE_MODULATEUR" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FORMULE_SERVICE_MAJ_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."FORMULE_SERVICE_MAJ_PK" ON "OSE"."FORMULE_SERVICE_MAJ" ("SERVICE_ID") 
  ;
--------------------------------------------------------
--  DDL for Index SOURCE_CODE_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."SOURCE_CODE_UN" ON "OSE"."SOURCE" ("CODE") 
  ;
--------------------------------------------------------
--  DDL for Index MV_PERSONNEL_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_PERSONNEL_PK" ON "OSE"."MV_PERSONNEL" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index PES_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."PES_PK" ON "OSE"."PRIME_EXCELLENCE_SCIENT" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TAUX_HORAIRE_HETD_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TAUX_HORAIRE_HETD_PK" ON "OSE"."TAUX_HORAIRE_HETD" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_AGREMENT_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_AGREMENT_PK" ON "OSE"."TYPE_AGREMENT" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_VOLUME_HORAIRE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_VOLUME_HORAIRE_PK" ON "OSE"."TYPE_VOLUME_HORAIRE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index SERVICE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."SERVICE_PK" ON "OSE"."SERVICE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index SERVICE_SAFF_IDX
--------------------------------------------------------

  CREATE INDEX "OSE"."SERVICE_SAFF_IDX" ON "OSE"."SERVICE" ("STRUCTURE_AFF_ID") 
  ;
--------------------------------------------------------
--  DDL for Index DISCIPLINE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."DISCIPLINE_PK" ON "OSE"."DISCIPLINE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index MODIFICATION_SERVICE_DU_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MODIFICATION_SERVICE_DU_PK" ON "OSE"."MODIFICATION_SERVICE_DU" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_PIECE_JOINTE_STATUT_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_PIECE_JOINTE_STATUT_PK" ON "OSE"."TYPE_PIECE_JOINTE_STATUT" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index GROUPE__UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."GROUPE__UN" ON "OSE"."GROUPE" ("ELEMENT_PEDAGOGIQUE_ID", "ANNEE_ID", "HISTO_DESTRUCTEUR_ID", "TYPE_INTERVENTION_ID") 
  ;
--------------------------------------------------------
--  DDL for Index STATUT_INTERVENANT_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."STATUT_INTERVENANT_PK" ON "OSE"."STATUT_INTERVENANT" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index PERSONNEL_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."PERSONNEL_PK" ON "OSE"."PERSONNEL" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index ELEMENT_DISCIPLINE_SUN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ELEMENT_DISCIPLINE_SUN" ON "OSE"."ELEMENT_DISCIPLINE" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index VOLUME_HORAIRE_SERVICE_IDX
--------------------------------------------------------

  CREATE INDEX "OSE"."VOLUME_HORAIRE_SERVICE_IDX" ON "OSE"."VOLUME_HORAIRE" ("SERVICE_ID") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_MODULATEUR_STRUCTURE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_MODULATEUR_STRUCTURE_PK" ON "OSE"."TYPE_MODULATEUR_STRUCTURE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index MV_INTERVENANT_PERMANENT_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_INTERVENANT_PERMANENT_PK" ON "OSE"."MV_INTERVENANT_PERMANENT" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index CIVILITE_LIBELLE_COURT_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."CIVILITE_LIBELLE_COURT_UN" ON "OSE"."CIVILITE" ("LIBELLE_COURT") 
  ;
--------------------------------------------------------
--  DDL for Index MV_VOLUME_HORAIRE_ENS_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_VOLUME_HORAIRE_ENS_PK" ON "OSE"."MV_VOLUME_HORAIRE_ENS" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_PIECE_JOINTE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_PIECE_JOINTE_PK" ON "OSE"."TYPE_PIECE_JOINTE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index ETAPE_CODE__UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ETAPE_CODE__UN" ON "OSE"."ETAPE" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index GTYPE_FORMATION_SOURCE_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."GTYPE_FORMATION_SOURCE_UN" ON "OSE"."GROUPE_TYPE_FORMATION" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index MV_GROUPE_TYPE_FORMATION_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_GROUPE_TYPE_FORMATION_PK" ON "OSE"."MV_GROUPE_TYPE_FORMATION" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index STRUCTURE_SOURCE_ID_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."STRUCTURE_SOURCE_ID_UN" ON "OSE"."STRUCTURE" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_ROLE_STRUCTURE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_ROLE_STRUCTURE_PK" ON "OSE"."TYPE_ROLE_STRUCTURE" ("TYPE_ROLE_ID", "TYPE_STRUCTURE_ID") 
  ;
--------------------------------------------------------
--  DDL for Index MV_AFFECTATION_RECHERCHE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_AFFECTATION_RECHERCHE_PK" ON "OSE"."MV_AFFECTATION_RECHERCHE" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_INTERVENTION_EP_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_INTERVENTION_EP_PK" ON "OSE"."TYPE_INTERVENTION_EP" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index UTILISATEUR_PERSONNEL_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."UTILISATEUR_PERSONNEL_UN" ON "OSE"."UTILISATEUR" ("PERSONNEL_ID") 
  ;
--------------------------------------------------------
--  DDL for Index FONCTION_REFERENTIEL_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."FONCTION_REFERENTIEL_PK" ON "OSE"."FONCTION_REFERENTIEL" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index CONTRAT_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."CONTRAT_PK" ON "OSE"."CONTRAT" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_INTERVENANT_CODE_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_INTERVENANT_CODE_UN" ON "OSE"."TYPE_INTERVENANT" ("CODE") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_PIECE_JOINTE_STATUT__UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_PIECE_JOINTE_STATUT__UN" ON "OSE"."TYPE_PIECE_JOINTE_STATUT" ("TYPE_PIECE_JOINTE_ID", "STATUT_INTERVENANT_ID", "PREMIER_RECRUTEMENT") 
  ;
--------------------------------------------------------
--  DDL for Index MV_CHEMIN_PEDAGOGIQUE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_CHEMIN_PEDAGOGIQUE_PK" ON "OSE"."MV_CHEMIN_PEDAGOGIQUE" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index PERIODE__UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."PERIODE__UN" ON "OSE"."PERIODE" ("CODE") 
  ;
--------------------------------------------------------
--  DDL for Index CHEMIN_PEDAGO_SRC_ID_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."CHEMIN_PEDAGO_SRC_ID_UN" ON "OSE"."CHEMIN_PEDAGOGIQUE" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index AGREMENT_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."AGREMENT_PK" ON "OSE"."AGREMENT" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FORMULE_VOLUME_HORAIRE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."FORMULE_VOLUME_HORAIRE_PK" ON "OSE"."FORMULE_VOLUME_HORAIRE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TEST_BUFFER_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TEST_BUFFER_PK" ON "OSE"."TEST_BUFFER" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index VALIDATION_TYPE_VALIDATION_FK
--------------------------------------------------------

  CREATE INDEX "OSE"."VALIDATION_TYPE_VALIDATION_FK" ON "OSE"."VALIDATION" ("TYPE_VALIDATION_ID") 
  ;
--------------------------------------------------------
--  DDL for Index ETAT_VOLUME_HORAIRE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ETAT_VOLUME_HORAIRE_PK" ON "OSE"."ETAT_VOLUME_HORAIRE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index AFFECTATION_SRC_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."AFFECTATION_SRC_UN" ON "OSE"."AFFECTATION_RECHERCHE" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index ROLE_SOURCE_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ROLE_SOURCE_UN" ON "OSE"."ROLE" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index PARAMETRE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."PARAMETRE_PK" ON "OSE"."PARAMETRE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index MV_INTERVENANT_EXTERIEUR_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_INTERVENANT_EXTERIEUR_PK" ON "OSE"."MV_INTERVENANT_EXTERIEUR" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index MV_DISCIPLINE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_DISCIPLINE_PK" ON "OSE"."MV_DISCIPLINE" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index SOURCE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."SOURCE_PK" ON "OSE"."SOURCE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index MV_CORPS_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_CORPS_PK" ON "OSE"."MV_CORPS" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index VOLUME_HORAIRE_CONTRAT_IDX
--------------------------------------------------------

  CREATE INDEX "OSE"."VOLUME_HORAIRE_CONTRAT_IDX" ON "OSE"."VOLUME_HORAIRE" ("CONTRAT_ID") 
  ;
--------------------------------------------------------
--  DDL for Index CORPS_SOURCE_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."CORPS_SOURCE_UN" ON "OSE"."CORPS" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index SERVICE__UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."SERVICE__UN" ON "OSE"."SERVICE" ("INTERVENANT_ID", "ELEMENT_PEDAGOGIQUE_ID", "ANNEE_ID", "ETABLISSEMENT_ID", "HISTO_DESTRUCTION") 
  ;
--------------------------------------------------------
--  DDL for Index ADRESSE_STRUCTURE_SOURCE_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ADRESSE_STRUCTURE_SOURCE_UN" ON "OSE"."ADRESSE_STRUCTURE" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index VOLUME_HORAIRE_HEURES_IDX
--------------------------------------------------------

  CREATE INDEX "OSE"."VOLUME_HORAIRE_HEURES_IDX" ON "OSE"."VOLUME_HORAIRE" ("HEURES") 
  ;
--------------------------------------------------------
--  DDL for Index INTERVENANT_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."INTERVENANT_PK" ON "OSE"."INTERVENANT" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_STRUCTURE_CODE_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_STRUCTURE_CODE_UN" ON "OSE"."TYPE_STRUCTURE" ("CODE") 
  ;
--------------------------------------------------------
--  DDL for Index FORMULE_REFERENTIEL__UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."FORMULE_REFERENTIEL__UN" ON "OSE"."FORMULE_REFERENTIEL" ("INTERVENANT_ID", "ANNEE_ID") 
  ;
--------------------------------------------------------
--  DDL for Index MV_ADRESSE_STRUCTURE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_ADRESSE_STRUCTURE_PK" ON "OSE"."MV_ADRESSE_STRUCTURE" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index SITUATION_FAMILIALE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."SITUATION_FAMILIALE_PK" ON "OSE"."SITUATION_FAMILIALE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index ELEMENT_PORTEUR_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ELEMENT_PORTEUR_PK" ON "OSE"."ELEMENT_PORTEUR_PORTE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index MODULATEUR_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MODULATEUR_PK" ON "OSE"."MODULATEUR" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FORMULE_VOLUME_HORAIRE_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."FORMULE_VOLUME_HORAIRE_UK1" ON "OSE"."FORMULE_VOLUME_HORAIRE" ("VOLUME_HORAIRE_ID") 
  ;
--------------------------------------------------------
--  DDL for Index ELEMENT_TAUX_REGIMES__UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ELEMENT_TAUX_REGIMES__UN" ON "OSE"."ELEMENT_TAUX_REGIMES" ("SOURCE_CODE", "ANNEE_ID") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_POSTE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_POSTE_PK" ON "OSE"."TYPE_POSTE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index CHEMIN_PEDAGOGIQUE__UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."CHEMIN_PEDAGOGIQUE__UN" ON "OSE"."CHEMIN_PEDAGOGIQUE" ("ELEMENT_PEDAGOGIQUE_ID", "ETAPE_ID") 
  ;
--------------------------------------------------------
--  DDL for Index DOSSIER_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."DOSSIER_PK" ON "OSE"."DOSSIER" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index ELEMENT_DISCIPLINE_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ELEMENT_DISCIPLINE_UN" ON "OSE"."ELEMENT_DISCIPLINE" ("ELEMENT_PEDAGOGIQUE_ID", "DISCIPLINE_ID") 
  ;
--------------------------------------------------------
--  DDL for Index MOTIF_MODIFICATION_SERVIC_UK1
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MOTIF_MODIFICATION_SERVIC_UK1" ON "OSE"."MOTIF_MODIFICATION_SERVICE" ("CODE") 
  ;
--------------------------------------------------------
--  DDL for Index VOLUME_HORAIRE_MNP_IDX
--------------------------------------------------------

  CREATE INDEX "OSE"."VOLUME_HORAIRE_MNP_IDX" ON "OSE"."VOLUME_HORAIRE" ("MOTIF_NON_PAIEMENT_ID") 
  ;
--------------------------------------------------------
--  DDL for Index MV_ETABLISSEMENT_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_ETABLISSEMENT_PK" ON "OSE"."MV_ETABLISSEMENT" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index ETAT_VOLUME_HORAIRE__UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ETAT_VOLUME_HORAIRE__UN" ON "OSE"."ETAT_VOLUME_HORAIRE" ("CODE", "HISTO_DESTRUCTION") 
  ;
--------------------------------------------------------
--  DDL for Index INTERVENANT_NOM_IDX
--------------------------------------------------------

  CREATE INDEX "OSE"."INTERVENANT_NOM_IDX" ON "OSE"."INTERVENANT" ("NOM_USUEL") 
  ;
--------------------------------------------------------
--  DDL for Index SERVICE_DU_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."SERVICE_DU_PK" ON "OSE"."SERVICE_DU" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index INTERVENANT_EXTERIEUR_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."INTERVENANT_EXTERIEUR_PK" ON "OSE"."INTERVENANT_EXTERIEUR" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index GROUPE_TYPE_FORMATION_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."GROUPE_TYPE_FORMATION_PK" ON "OSE"."GROUPE_TYPE_FORMATION" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_ROLE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_ROLE_PK" ON "OSE"."TYPE_ROLE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FORMULE_VOLUME_HORAIRE_MAJ_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."FORMULE_VOLUME_HORAIRE_MAJ_PK" ON "OSE"."FORMULE_VOLUME_HORAIRE_MAJ" ("VOLUME_HORAIRE_ID") 
  ;
--------------------------------------------------------
--  DDL for Index SYNC_LOG_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."SYNC_LOG_PK" ON "OSE"."SYNC_LOG" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index MOTIF_NON_PAIEMENT_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MOTIF_NON_PAIEMENT_PK" ON "OSE"."MOTIF_NON_PAIEMENT" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index VOLUME_HORAIRE_TI_IDX
--------------------------------------------------------

  CREATE INDEX "OSE"."VOLUME_HORAIRE_TI_IDX" ON "OSE"."VOLUME_HORAIRE" ("TYPE_INTERVENTION_ID") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_INTERVENTION_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_INTERVENTION_PK" ON "OSE"."TYPE_INTERVENTION" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index MV_ELEMENT_PORTEUR_PORTE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_ELEMENT_PORTEUR_PORTE_PK" ON "OSE"."MV_ELEMENT_PORTEUR_PORTE" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index AGREMENT__UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."AGREMENT__UN" ON "OSE"."AGREMENT" ("TYPE_AGREMENT_ID", "INTERVENANT_ID", "ANNEE_ID", "STRUCTURE_ID") 
  ;
--------------------------------------------------------
--  DDL for Index MV_ROLE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_ROLE_PK" ON "OSE"."MV_ROLE" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index ADRESSE_STRUCTURE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ADRESSE_STRUCTURE_PK" ON "OSE"."ADRESSE_STRUCTURE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index ELEMENT_MODULATEUR__UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ELEMENT_MODULATEUR__UN" ON "OSE"."ELEMENT_MODULATEUR" ("ELEMENT_ID", "MODULATEUR_ID", "HISTO_DESTRUCTION") 
  ;
--------------------------------------------------------
--  DDL for Index ROLE_UTILISATEUR_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ROLE_UTILISATEUR_PK" ON "OSE"."ROLE_UTILISATEUR" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index MV_ETAPE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_ETAPE_PK" ON "OSE"."MV_ETAPE" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index PERIODE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."PERIODE_PK" ON "OSE"."PERIODE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index CONTRAT_NUMERO_AVENANT_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."CONTRAT_NUMERO_AVENANT_UN" ON "OSE"."CONTRAT" ("INTERVENANT_ID", "STRUCTURE_ID", "NUMERO_AVENANT", "VALIDATION_ID") 
  ;
--------------------------------------------------------
--  DDL for Index SERVICE_ETABLISSEMENT_IDX
--------------------------------------------------------

  CREATE INDEX "OSE"."SERVICE_ETABLISSEMENT_IDX" ON "OSE"."SERVICE" ("ETABLISSEMENT_ID") 
  ;
--------------------------------------------------------
--  DDL for Index ROLE_UTILISATEUR_ID_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ROLE_UTILISATEUR_ID_UN" ON "OSE"."ROLE_UTILISATEUR" ("ROLE_ID") 
  ;
--------------------------------------------------------
--  DDL for Index VOLUME_HORAIRE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."VOLUME_HORAIRE_PK" ON "OSE"."VOLUME_HORAIRE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index SERVICE_INTERVENANT_IDX
--------------------------------------------------------

  CREATE INDEX "OSE"."SERVICE_INTERVENANT_IDX" ON "OSE"."SERVICE" ("INTERVENANT_ID") 
  ;
--------------------------------------------------------
--  DDL for Index FICHIER_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."FICHIER_PK" ON "OSE"."FICHIER" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index INTERVENANT_PRENOM_IDX
--------------------------------------------------------

  CREATE INDEX "OSE"."INTERVENANT_PRENOM_IDX" ON "OSE"."INTERVENANT" ("PRENOM") 
  ;
--------------------------------------------------------
--  DDL for Index FORMULE_SERVICE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."FORMULE_SERVICE_PK" ON "OSE"."FORMULE_SERVICE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index ELEMENT_DISCIPLINE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ELEMENT_DISCIPLINE_PK" ON "OSE"."ELEMENT_DISCIPLINE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index ROLE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ROLE_PK" ON "OSE"."ROLE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index MV_INTERVENANT_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_INTERVENANT_PK" ON "OSE"."MV_INTERVENANT" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index ETABLISSEMENT_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ETABLISSEMENT_PK" ON "OSE"."ETABLISSEMENT" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_INTERVENTION_STRUCTURE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_INTERVENTION_STRUCTURE_PK" ON "OSE"."TYPE_INTERVENTION_STRUCTURE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index MOTIF_MODIFICATION_SERVICE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MOTIF_MODIFICATION_SERVICE_PK" ON "OSE"."MOTIF_MODIFICATION_SERVICE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_FORMATION_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_FORMATION_PK" ON "OSE"."TYPE_FORMATION" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index MV_TYPE_FORMATION_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_TYPE_FORMATION_PK" ON "OSE"."MV_TYPE_FORMATION" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index PIECE_JOINTE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."PIECE_JOINTE_PK" ON "OSE"."PIECE_JOINTE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index GROUPE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."GROUPE_PK" ON "OSE"."GROUPE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index FORMULE_REFERENTIEL_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."FORMULE_REFERENTIEL_PK" ON "OSE"."FORMULE_REFERENTIEL" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index DISCIPLINE_SOURCE_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."DISCIPLINE_SOURCE_UN" ON "OSE"."DISCIPLINE" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index REGIME_SECU_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."REGIME_SECU_PK" ON "OSE"."REGIME_SECU" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index VHE_SOURCE_UN
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."VHE_SOURCE_UN" ON "OSE"."VOLUME_HORAIRE_ENS" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index TYPE_CONTRAT_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."TYPE_CONTRAT_PK" ON "OSE"."TYPE_CONTRAT" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index PIECE_JOINTE_FICHIER_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."PIECE_JOINTE_FICHIER_PK" ON "OSE"."PIECE_JOINTE_FICHIER" ("PIECE_JOINTE_ID", "FICHIER_ID") 
  ;
--------------------------------------------------------
--  DDL for Index CHEMIN_PEDAGOGIQUE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."CHEMIN_PEDAGOGIQUE_PK" ON "OSE"."CHEMIN_PEDAGOGIQUE" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index ADRESSE_INTERVENANT_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."ADRESSE_INTERVENANT_PK" ON "OSE"."ADRESSE_INTERVENANT" ("ID") 
  ;
--------------------------------------------------------
--  DDL for Index MV_ELEMENT_PEDAGOGIQUE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."MV_ELEMENT_PEDAGOGIQUE_PK" ON "OSE"."MV_ELEMENT_PEDAGOGIQUE" ("SOURCE_CODE") 
  ;
--------------------------------------------------------
--  DDL for Index VALIDATION_VOL_HORAIRE_PK
--------------------------------------------------------

  CREATE UNIQUE INDEX "OSE"."VALIDATION_VOL_HORAIRE_PK" ON "OSE"."VALIDATION_VOL_HORAIRE" ("VALIDATION_ID", "VOLUME_HORAIRE_ID") 
  ;
--------------------------------------------------------
--  Constraints for Table FORMULE_VOLUME_HORAIRE_MAJ
--------------------------------------------------------

  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE_MAJ" ADD CONSTRAINT "FORMULE_VOLUME_HORAIRE_MAJ_PK" PRIMARY KEY ("VOLUME_HORAIRE_ID") ENABLE;
  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE_MAJ" MODIFY ("VOLUME_HORAIRE_ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table STRUCTURE
--------------------------------------------------------

  ALTER TABLE "OSE"."STRUCTURE" ADD CONSTRAINT "STRUCTURE_SOURCE_ID_UN" UNIQUE ("SOURCE_CODE") ENABLE;
  ALTER TABLE "OSE"."STRUCTURE" ADD CONSTRAINT "STRUCTURE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."STRUCTURE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STRUCTURE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STRUCTURE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STRUCTURE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STRUCTURE" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STRUCTURE" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STRUCTURE" MODIFY ("NIVEAU" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STRUCTURE" MODIFY ("ETABLISSEMENT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STRUCTURE" MODIFY ("TYPE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STRUCTURE" MODIFY ("LIBELLE_COURT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STRUCTURE" MODIFY ("LIBELLE_LONG" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STRUCTURE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table SERVICE
--------------------------------------------------------

  ALTER TABLE "OSE"."SERVICE" ADD CONSTRAINT "SERVICE__UN" UNIQUE ("INTERVENANT_ID", "ELEMENT_PEDAGOGIQUE_ID", "ANNEE_ID", "ETABLISSEMENT_ID", "HISTO_DESTRUCTION") ENABLE;
  ALTER TABLE "OSE"."SERVICE" ADD CONSTRAINT "SERVICE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."SERVICE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE" MODIFY ("ETABLISSEMENT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE" MODIFY ("ANNEE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE" MODIFY ("STRUCTURE_AFF_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE" MODIFY ("INTERVENANT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table MOTIF_NON_PAIEMENT
--------------------------------------------------------

  ALTER TABLE "OSE"."MOTIF_NON_PAIEMENT" ADD CONSTRAINT "MOTIF_NON_PAIEMENT_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."MOTIF_NON_PAIEMENT" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MOTIF_NON_PAIEMENT" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MOTIF_NON_PAIEMENT" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MOTIF_NON_PAIEMENT" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MOTIF_NON_PAIEMENT" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MOTIF_NON_PAIEMENT" MODIFY ("LIBELLE_LONG" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MOTIF_NON_PAIEMENT" MODIFY ("LIBELLE_COURT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MOTIF_NON_PAIEMENT" MODIFY ("CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MOTIF_NON_PAIEMENT" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table ELEMENT_MODULATEUR
--------------------------------------------------------

  ALTER TABLE "OSE"."ELEMENT_MODULATEUR" ADD CONSTRAINT "ELEMENT_MODULATEUR__UN" UNIQUE ("ELEMENT_ID", "MODULATEUR_ID", "HISTO_DESTRUCTION") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_MODULATEUR" ADD CONSTRAINT "ELEMENT_MODULATEUR_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_MODULATEUR" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_MODULATEUR" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_MODULATEUR" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_MODULATEUR" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_MODULATEUR" MODIFY ("ANNEE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_MODULATEUR" MODIFY ("MODULATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_MODULATEUR" MODIFY ("ELEMENT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_MODULATEUR" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table SERVICE_DU
--------------------------------------------------------

  ALTER TABLE "OSE"."SERVICE_DU" ADD CONSTRAINT "SERVICE_DU_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."SERVICE_DU" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE_DU" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE_DU" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE_DU" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE_DU" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE_DU" MODIFY ("HEURES" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE_DU" MODIFY ("ANNEE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE_DU" MODIFY ("INTERVENANT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE_DU" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table INTERVENANT_PERMANENT
--------------------------------------------------------

  ALTER TABLE "OSE"."INTERVENANT_PERMANENT" ADD CONSTRAINT "IP_SOURCE_UN" UNIQUE ("SOURCE_CODE") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT_PERMANENT" ADD CONSTRAINT "INTERVENANT_PERMANENT_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT_PERMANENT" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT_PERMANENT" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT_PERMANENT" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT_PERMANENT" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT_PERMANENT" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT_PERMANENT" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT_PERMANENT" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TYPE_INTERVENANT
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_INTERVENANT" ADD CONSTRAINT "TYPE_INTERVENANT_CODE_UN" UNIQUE ("CODE") ENABLE;
  ALTER TABLE "OSE"."TYPE_INTERVENANT" ADD CONSTRAINT "TYPE_INTERVENANT_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_INTERVENANT" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENANT" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENANT" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENANT" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENANT" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENANT" MODIFY ("LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENANT" MODIFY ("CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENANT" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table ELEMENT_TAUX_REGIMES
--------------------------------------------------------

  ALTER TABLE "OSE"."ELEMENT_TAUX_REGIMES" ADD CONSTRAINT "ELEMENT_TAUX_REGIMES__UN" UNIQUE ("SOURCE_CODE", "ANNEE_ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_TAUX_REGIMES" ADD CONSTRAINT "ELEMENT_TAUX_REGIMES_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_TAUX_REGIMES" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_TAUX_REGIMES" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_TAUX_REGIMES" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_TAUX_REGIMES" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_TAUX_REGIMES" MODIFY ("SOURCE_CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_TAUX_REGIMES" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_TAUX_REGIMES" MODIFY ("TAUX_FA" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_TAUX_REGIMES" MODIFY ("TAUX_FC" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_TAUX_REGIMES" MODIFY ("TAUX_FI" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_TAUX_REGIMES" MODIFY ("ANNEE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_TAUX_REGIMES" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table STATUT_INTERVENANT
--------------------------------------------------------

  ALTER TABLE "OSE"."STATUT_INTERVENANT" MODIFY ("PEUT_SAISIR_SERVICE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STATUT_INTERVENANT" MODIFY ("NON_AUTORISE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STATUT_INTERVENANT" ADD CONSTRAINT "STATUT_INTERVENANT__UN" UNIQUE ("SOURCE_CODE") ENABLE;
  ALTER TABLE "OSE"."STATUT_INTERVENANT" ADD CONSTRAINT "STATUT_INTERVENANT_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."STATUT_INTERVENANT" MODIFY ("PEUT_CHOISIR_DANS_DOSSIER" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STATUT_INTERVENANT" MODIFY ("ORDRE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STATUT_INTERVENANT" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STATUT_INTERVENANT" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STATUT_INTERVENANT" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STATUT_INTERVENANT" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STATUT_INTERVENANT" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STATUT_INTERVENANT" MODIFY ("SOURCE_CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STATUT_INTERVENANT" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STATUT_INTERVENANT" MODIFY ("TYPE_INTERVENANT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STATUT_INTERVENANT" MODIFY ("FONCTION_E_C" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STATUT_INTERVENANT" MODIFY ("MAXIMUM_HETD" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STATUT_INTERVENANT" MODIFY ("PLAFOND_REFERENTIEL" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STATUT_INTERVENANT" MODIFY ("DEPASSEMENT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STATUT_INTERVENANT" MODIFY ("SERVICE_STATUTAIRE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STATUT_INTERVENANT" MODIFY ("LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STATUT_INTERVENANT" MODIFY ("ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STATUT_INTERVENANT" MODIFY ("PEUT_SAISIR_MOTIF_NON_PAIEMENT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STATUT_INTERVENANT" MODIFY ("PEUT_SAISIR_REFERENTIEL" NOT NULL ENABLE);
  ALTER TABLE "OSE"."STATUT_INTERVENANT" MODIFY ("PEUT_SAISIR_DOSSIER" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table ELEMENT_DISCIPLINE
--------------------------------------------------------

  ALTER TABLE "OSE"."ELEMENT_DISCIPLINE" ADD CONSTRAINT "ELEMENT_DISCIPLINE_UN" UNIQUE ("ELEMENT_PEDAGOGIQUE_ID", "DISCIPLINE_ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_DISCIPLINE" ADD CONSTRAINT "ELEMENT_DISCIPLINE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_DISCIPLINE" ADD CONSTRAINT "ELEMENT_DISCIPLINE_SUN" UNIQUE ("SOURCE_CODE") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_DISCIPLINE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_DISCIPLINE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_DISCIPLINE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_DISCIPLINE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_DISCIPLINE" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_DISCIPLINE" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_DISCIPLINE" MODIFY ("DISCIPLINE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_DISCIPLINE" MODIFY ("ELEMENT_PEDAGOGIQUE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_DISCIPLINE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TYPE_PIECE_JOINTE_STATUT
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE_STATUT" ADD CONSTRAINT "TYPE_PIECE_JOINTE_STATUT__UN" UNIQUE ("TYPE_PIECE_JOINTE_ID", "STATUT_INTERVENANT_ID", "PREMIER_RECRUTEMENT") ENABLE;
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE_STATUT" ADD CONSTRAINT "TYPE_PIECE_JOINTE_STATUT_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE_STATUT" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE_STATUT" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE_STATUT" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE_STATUT" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE_STATUT" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE_STATUT" MODIFY ("PREMIER_RECRUTEMENT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE_STATUT" MODIFY ("OBLIGATOIRE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE_STATUT" MODIFY ("STATUT_INTERVENANT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE_STATUT" MODIFY ("TYPE_PIECE_JOINTE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE_STATUT" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TYPE_AGREMENT
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_AGREMENT" ADD CONSTRAINT "TYPE_AGREMENT__UN" UNIQUE ("CODE") ENABLE;
  ALTER TABLE "OSE"."TYPE_AGREMENT" ADD CONSTRAINT "TYPE_AGREMENT_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_AGREMENT" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_AGREMENT" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_AGREMENT" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_AGREMENT" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_AGREMENT" MODIFY ("LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_AGREMENT" MODIFY ("CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_AGREMENT" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table MOTIF_MODIFICATION_SERVICE
--------------------------------------------------------

  ALTER TABLE "OSE"."MOTIF_MODIFICATION_SERVICE" ADD CONSTRAINT "MOTIF_MODIFICATION_SERVICE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."MOTIF_MODIFICATION_SERVICE" ADD CONSTRAINT "MOTIF_MODIFICATION_SERVIC_UK1" UNIQUE ("CODE") ENABLE;
  ALTER TABLE "OSE"."MOTIF_MODIFICATION_SERVICE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MOTIF_MODIFICATION_SERVICE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MOTIF_MODIFICATION_SERVICE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MOTIF_MODIFICATION_SERVICE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MOTIF_MODIFICATION_SERVICE" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MOTIF_MODIFICATION_SERVICE" MODIFY ("MULTIPLICATEUR" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MOTIF_MODIFICATION_SERVICE" MODIFY ("LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MOTIF_MODIFICATION_SERVICE" MODIFY ("CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MOTIF_MODIFICATION_SERVICE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table CIVILITE
--------------------------------------------------------

  ALTER TABLE "OSE"."CIVILITE" ADD CONSTRAINT "CIVILITE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."CIVILITE" ADD CONSTRAINT "CIVILITE_LIBELLE_COURT_UN" UNIQUE ("LIBELLE_COURT") ENABLE;
  ALTER TABLE "OSE"."CIVILITE" MODIFY ("SEXE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CIVILITE" MODIFY ("LIBELLE_LONG" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CIVILITE" MODIFY ("LIBELLE_COURT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CIVILITE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table MODIFICATION_SERVICE_DU
--------------------------------------------------------

  ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" ADD CONSTRAINT "MODIFICATION_SERVICE_DU_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" MODIFY ("MOTIF_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" MODIFY ("HEURES" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" MODIFY ("ANNEE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" MODIFY ("INTERVENANT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TYPE_ROLE_STRUCTURE
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_ROLE_STRUCTURE" ADD CONSTRAINT "TYPE_ROLE_STRUCTURE_PK" PRIMARY KEY ("TYPE_ROLE_ID", "TYPE_STRUCTURE_ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_ROLE_STRUCTURE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_ROLE_STRUCTURE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_ROLE_STRUCTURE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_ROLE_STRUCTURE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_ROLE_STRUCTURE" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_ROLE_STRUCTURE" MODIFY ("TYPE_STRUCTURE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_ROLE_STRUCTURE" MODIFY ("TYPE_ROLE_ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TYPE_VOLUME_HORAIRE
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_VOLUME_HORAIRE" ADD CONSTRAINT "TYPE_VOLUME_HORAIRE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_VOLUME_HORAIRE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_VOLUME_HORAIRE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_VOLUME_HORAIRE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_VOLUME_HORAIRE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_VOLUME_HORAIRE" MODIFY ("LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_VOLUME_HORAIRE" MODIFY ("CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_VOLUME_HORAIRE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TYPE_INTERVENTION_EP
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_INTERVENTION_EP" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION_EP" ADD CONSTRAINT "TYPE_INTERVENTION_EP_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_INTERVENTION_EP" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION_EP" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION_EP" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION_EP" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION_EP" MODIFY ("VISIBLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION_EP" MODIFY ("ELEMENT_PEDAGOGIQUE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION_EP" MODIFY ("TYPE_INTERVENTION_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION_EP" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table CHEMIN_PEDAGOGIQUE
--------------------------------------------------------

  ALTER TABLE "OSE"."CHEMIN_PEDAGOGIQUE" ADD CONSTRAINT "CHEMIN_PEDAGO_SRC_ID_UN" UNIQUE ("SOURCE_CODE") ENABLE;
  ALTER TABLE "OSE"."CHEMIN_PEDAGOGIQUE" ADD CONSTRAINT "CHEMIN_PEDAGOGIQUE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."CHEMIN_PEDAGOGIQUE" ADD CONSTRAINT "CHEMIN_PEDAGOGIQUE__UN" UNIQUE ("ELEMENT_PEDAGOGIQUE_ID", "ETAPE_ID") ENABLE;
  ALTER TABLE "OSE"."CHEMIN_PEDAGOGIQUE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CHEMIN_PEDAGOGIQUE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CHEMIN_PEDAGOGIQUE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CHEMIN_PEDAGOGIQUE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CHEMIN_PEDAGOGIQUE" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CHEMIN_PEDAGOGIQUE" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CHEMIN_PEDAGOGIQUE" MODIFY ("ORDRE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CHEMIN_PEDAGOGIQUE" MODIFY ("ETAPE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CHEMIN_PEDAGOGIQUE" MODIFY ("ELEMENT_PEDAGOGIQUE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CHEMIN_PEDAGOGIQUE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table PERIODE
--------------------------------------------------------

  ALTER TABLE "OSE"."PERIODE" ADD CONSTRAINT "PERIODE__UN" UNIQUE ("CODE") ENABLE;
  ALTER TABLE "OSE"."PERIODE" ADD CONSTRAINT "PERIODE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."PERIODE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PERIODE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PERIODE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PERIODE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PERIODE" MODIFY ("ORDRE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PERIODE" MODIFY ("LIBELLE_LONG" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PERIODE" MODIFY ("CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PERIODE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table ANNEE
--------------------------------------------------------

  ALTER TABLE "OSE"."ANNEE" ADD CONSTRAINT "ANNEE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."ANNEE" MODIFY ("DATE_FIN" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ANNEE" MODIFY ("DATE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ANNEE" MODIFY ("LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ANNEE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table SERVICE_REFERENTIEL
--------------------------------------------------------

  ALTER TABLE "OSE"."SERVICE_REFERENTIEL" ADD CONSTRAINT "SERVICE_REFERENTIEL_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."SERVICE_REFERENTIEL" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE_REFERENTIEL" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE_REFERENTIEL" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE_REFERENTIEL" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE_REFERENTIEL" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE_REFERENTIEL" MODIFY ("HEURES" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE_REFERENTIEL" MODIFY ("ANNEE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE_REFERENTIEL" MODIFY ("INTERVENANT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE_REFERENTIEL" MODIFY ("FONCTION_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SERVICE_REFERENTIEL" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table ADRESSE_STRUCTURE
--------------------------------------------------------

  ALTER TABLE "OSE"."ADRESSE_STRUCTURE" ADD CONSTRAINT "ADRESSE_STRUCTURE_SOURCE_UN" UNIQUE ("SOURCE_CODE") ENABLE;
  ALTER TABLE "OSE"."ADRESSE_STRUCTURE" ADD CONSTRAINT "ADRESSE_STRUCTURE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."ADRESSE_STRUCTURE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ADRESSE_STRUCTURE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ADRESSE_STRUCTURE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ADRESSE_STRUCTURE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ADRESSE_STRUCTURE" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ADRESSE_STRUCTURE" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ADRESSE_STRUCTURE" MODIFY ("STRUCTURE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ADRESSE_STRUCTURE" MODIFY ("ID" CONSTRAINT "NNC_ADRESSE_INTERVENANTV1_ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table AGREMENT
--------------------------------------------------------

  ALTER TABLE "OSE"."AGREMENT" ADD CONSTRAINT "AGREMENT_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."AGREMENT" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."AGREMENT" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."AGREMENT" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."AGREMENT" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."AGREMENT" ADD CONSTRAINT "AGREMENT__UN" UNIQUE ("TYPE_AGREMENT_ID", "INTERVENANT_ID", "ANNEE_ID", "STRUCTURE_ID") ENABLE;
  ALTER TABLE "OSE"."AGREMENT" MODIFY ("DATE_DECISION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."AGREMENT" MODIFY ("ANNEE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."AGREMENT" MODIFY ("INTERVENANT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."AGREMENT" MODIFY ("TYPE_AGREMENT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."AGREMENT" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TYPE_VALIDATION
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_VALIDATION" ADD CONSTRAINT "TYPE_VALIDATION_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_VALIDATION" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_VALIDATION" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_VALIDATION" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_VALIDATION" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_VALIDATION" MODIFY ("CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_VALIDATION" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table VOLUME_HORAIRE_ENS
--------------------------------------------------------

  ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" ADD CONSTRAINT "VOLUME_HORAIRE_ENS_UN" UNIQUE ("ELEMENT_DISCIPLINE_ID", "TYPE_INTERVENTION_ID", "ANNEE_ID") ENABLE;
  ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" ADD CONSTRAINT "VHE_SOURCE_UN" UNIQUE ("SOURCE_CODE") ENABLE;
  ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" ADD CONSTRAINT "VOLUME_HORAIRE_ENS_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" MODIFY ("HEURES" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" MODIFY ("ANNEE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" MODIFY ("TYPE_INTERVENTION_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" MODIFY ("ELEMENT_DISCIPLINE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table VOLUME_HORAIRE
--------------------------------------------------------

  ALTER TABLE "OSE"."VOLUME_HORAIRE" ADD CONSTRAINT "VOLUME_HORAIRE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."VOLUME_HORAIRE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VOLUME_HORAIRE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VOLUME_HORAIRE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VOLUME_HORAIRE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VOLUME_HORAIRE" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VOLUME_HORAIRE" MODIFY ("HEURES" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VOLUME_HORAIRE" MODIFY ("TYPE_INTERVENTION_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VOLUME_HORAIRE" MODIFY ("PERIODE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VOLUME_HORAIRE" MODIFY ("SERVICE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VOLUME_HORAIRE" MODIFY ("TYPE_VOLUME_HORAIRE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VOLUME_HORAIRE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table ADRESSE_INTERVENANT
--------------------------------------------------------

  ALTER TABLE "OSE"."ADRESSE_INTERVENANT" ADD CONSTRAINT "ADRESSE_INTERVENANT_SOURCE_UN" UNIQUE ("SOURCE_CODE") ENABLE;
  ALTER TABLE "OSE"."ADRESSE_INTERVENANT" ADD CONSTRAINT "ADRESSE_INTERVENANT_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."ADRESSE_INTERVENANT" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ADRESSE_INTERVENANT" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ADRESSE_INTERVENANT" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ADRESSE_INTERVENANT" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ADRESSE_INTERVENANT" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ADRESSE_INTERVENANT" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ADRESSE_INTERVENANT" MODIFY ("PAYS_LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ADRESSE_INTERVENANT" MODIFY ("PAYS_CODE_INSEE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ADRESSE_INTERVENANT" MODIFY ("PRINCIPALE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ADRESSE_INTERVENANT" MODIFY ("INTERVENANT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ADRESSE_INTERVENANT" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table GROUPE_TYPE_FORMATION
--------------------------------------------------------

  ALTER TABLE "OSE"."GROUPE_TYPE_FORMATION" ADD CONSTRAINT "GTYPE_FORMATION_SOURCE_UN" UNIQUE ("SOURCE_CODE") ENABLE;
  ALTER TABLE "OSE"."GROUPE_TYPE_FORMATION" ADD CONSTRAINT "GROUPE_TYPE_FORMATION_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."GROUPE_TYPE_FORMATION" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."GROUPE_TYPE_FORMATION" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."GROUPE_TYPE_FORMATION" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."GROUPE_TYPE_FORMATION" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."GROUPE_TYPE_FORMATION" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."GROUPE_TYPE_FORMATION" MODIFY ("PERTINENCE_NIVEAU" NOT NULL ENABLE);
  ALTER TABLE "OSE"."GROUPE_TYPE_FORMATION" MODIFY ("ORDRE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."GROUPE_TYPE_FORMATION" MODIFY ("LIBELLE_LONG" NOT NULL ENABLE);
  ALTER TABLE "OSE"."GROUPE_TYPE_FORMATION" MODIFY ("LIBELLE_COURT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."GROUPE_TYPE_FORMATION" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TYPE_MODULATEUR_STRUCTURE
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_MODULATEUR_STRUCTURE" ADD CONSTRAINT "TYPE_MODULATEUR_STRUCTURE_UN" UNIQUE ("TYPE_MODULATEUR_ID", "STRUCTURE_ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_MODULATEUR_STRUCTURE" ADD CONSTRAINT "TYPE_MODULATEUR_STRUCTURE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_MODULATEUR_STRUCTURE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_MODULATEUR_STRUCTURE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_MODULATEUR_STRUCTURE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_MODULATEUR_STRUCTURE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_MODULATEUR_STRUCTURE" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_MODULATEUR_STRUCTURE" MODIFY ("STRUCTURE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_MODULATEUR_STRUCTURE" MODIFY ("TYPE_MODULATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_MODULATEUR_STRUCTURE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TAUX_HORAIRE_HETD
--------------------------------------------------------

  ALTER TABLE "OSE"."TAUX_HORAIRE_HETD" ADD CONSTRAINT "TAUX_HORAIRE_HETD_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."TAUX_HORAIRE_HETD" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TAUX_HORAIRE_HETD" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TAUX_HORAIRE_HETD" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TAUX_HORAIRE_HETD" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TAUX_HORAIRE_HETD" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TAUX_HORAIRE_HETD" MODIFY ("VALEUR" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TAUX_HORAIRE_HETD" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table GROUPE
--------------------------------------------------------

  ALTER TABLE "OSE"."GROUPE" ADD CONSTRAINT "GROUPE__UN" UNIQUE ("ELEMENT_PEDAGOGIQUE_ID", "ANNEE_ID", "HISTO_DESTRUCTEUR_ID", "TYPE_INTERVENTION_ID") ENABLE;
  ALTER TABLE "OSE"."GROUPE" ADD CONSTRAINT "GROUPE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."GROUPE" MODIFY ("HISTO_DESTRUCTEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."GROUPE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."GROUPE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."GROUPE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."GROUPE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."GROUPE" MODIFY ("NOMBRE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."GROUPE" MODIFY ("ANNEE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."GROUPE" MODIFY ("TYPE_INTERVENTION_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."GROUPE" MODIFY ("ELEMENT_PEDAGOGIQUE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."GROUPE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table PIECE_JOINTE
--------------------------------------------------------

  ALTER TABLE "OSE"."PIECE_JOINTE" ADD CONSTRAINT "PIECE_JOINTE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."PIECE_JOINTE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PIECE_JOINTE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PIECE_JOINTE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PIECE_JOINTE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PIECE_JOINTE" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PIECE_JOINTE" MODIFY ("DOSSIER_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PIECE_JOINTE" MODIFY ("TYPE_PIECE_JOINTE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PIECE_JOINTE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FORMULE_REFERENTIEL
--------------------------------------------------------

  ALTER TABLE "OSE"."FORMULE_REFERENTIEL" ADD CONSTRAINT "FORMULE_REFERENTIEL__UN" UNIQUE ("INTERVENANT_ID", "ANNEE_ID") ENABLE;
  ALTER TABLE "OSE"."FORMULE_REFERENTIEL" ADD CONSTRAINT "FORMULE_REFERENTIEL_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."FORMULE_REFERENTIEL" MODIFY ("SERVICE_REFERENTIEL" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_REFERENTIEL" MODIFY ("SERVICE_DU_MODIFIE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_REFERENTIEL" MODIFY ("SERVICE_DU_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_REFERENTIEL" MODIFY ("SERVICE_DU" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_REFERENTIEL" MODIFY ("ANNEE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_REFERENTIEL" MODIFY ("INTERVENANT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_REFERENTIEL" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TYPE_STRUCTURE
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_STRUCTURE" ADD CONSTRAINT "TYPE_STRUCTURE_CODE_UN" UNIQUE ("CODE") ENABLE;
  ALTER TABLE "OSE"."TYPE_STRUCTURE" ADD CONSTRAINT "TYPE_STRUCTURE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_STRUCTURE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_STRUCTURE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_STRUCTURE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_STRUCTURE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_STRUCTURE" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_STRUCTURE" MODIFY ("ENSEIGNEMENT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_STRUCTURE" MODIFY ("LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_STRUCTURE" MODIFY ("CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_STRUCTURE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table ROLE
--------------------------------------------------------

  ALTER TABLE "OSE"."ROLE" ADD CONSTRAINT "ROLE_SOURCE_UN" UNIQUE ("SOURCE_CODE") ENABLE;
  ALTER TABLE "OSE"."ROLE" ADD CONSTRAINT "ROLE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."ROLE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ROLE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ROLE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ROLE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ROLE" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ROLE" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ROLE" MODIFY ("TYPE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ROLE" MODIFY ("PERSONNEL_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ROLE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table REGIME_SECU
--------------------------------------------------------

  ALTER TABLE "OSE"."REGIME_SECU" ADD CONSTRAINT "REGIME_SECU_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."REGIME_SECU" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."REGIME_SECU" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."REGIME_SECU" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."REGIME_SECU" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."REGIME_SECU" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."REGIME_SECU" MODIFY ("TAUX_TAXE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."REGIME_SECU" MODIFY ("LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."REGIME_SECU" MODIFY ("CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."REGIME_SECU" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table ETAPE
--------------------------------------------------------

  ALTER TABLE "OSE"."ETAPE" ADD CONSTRAINT "ETAPE_CODE__UN" UNIQUE ("SOURCE_CODE") ENABLE;
  ALTER TABLE "OSE"."ETAPE" ADD CONSTRAINT "ETAPE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."ETAPE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETAPE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETAPE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETAPE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETAPE" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETAPE" MODIFY ("SOURCE_CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETAPE" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETAPE" MODIFY ("STRUCTURE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETAPE" MODIFY ("SPECIFIQUE_ECHANGES" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETAPE" MODIFY ("TYPE_FORMATION_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETAPE" MODIFY ("LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETAPE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FORMULE_SERVICE
--------------------------------------------------------

  ALTER TABLE "OSE"."FORMULE_SERVICE" ADD CONSTRAINT "FORMULE_SERVICE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."FORMULE_SERVICE" MODIFY ("ANNEE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_SERVICE" MODIFY ("INTERVENANT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_SERVICE" MODIFY ("PONDERATION_SERVICE_COMPL" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_SERVICE" MODIFY ("PONDERATION_SERVICE_DU" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_SERVICE" MODIFY ("TAUX_FC" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_SERVICE" MODIFY ("TAUX_FA" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_SERVICE" MODIFY ("TAUX_FI" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_SERVICE" MODIFY ("SERVICE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_SERVICE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TYPE_POSTE
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_POSTE" ADD CONSTRAINT "TYPE_POSTE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_POSTE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_POSTE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_POSTE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_POSTE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_POSTE" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_POSTE" MODIFY ("LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_POSTE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table PERSONNEL
--------------------------------------------------------

  ALTER TABLE "OSE"."PERSONNEL" ADD CONSTRAINT "PERSONNEL_SOURCE__UN" UNIQUE ("SOURCE_CODE") ENABLE;
  ALTER TABLE "OSE"."PERSONNEL" ADD CONSTRAINT "PERSONNEL_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."PERSONNEL" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PERSONNEL" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PERSONNEL" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PERSONNEL" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PERSONNEL" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PERSONNEL" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PERSONNEL" MODIFY ("STRUCTURE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PERSONNEL" MODIFY ("EMAIL" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PERSONNEL" MODIFY ("NOM_PATRONYMIQUE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PERSONNEL" MODIFY ("PRENOM" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PERSONNEL" MODIFY ("NOM_USUEL" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PERSONNEL" MODIFY ("CIVILITE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PERSONNEL" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table SITUATION_FAMILIALE
--------------------------------------------------------

  ALTER TABLE "OSE"."SITUATION_FAMILIALE" ADD CONSTRAINT "SITUATION_FAMILIALE_CODE_UN" UNIQUE ("CODE") ENABLE;
  ALTER TABLE "OSE"."SITUATION_FAMILIALE" ADD CONSTRAINT "SITUATION_FAMILIALE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."SITUATION_FAMILIALE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SITUATION_FAMILIALE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SITUATION_FAMILIALE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SITUATION_FAMILIALE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SITUATION_FAMILIALE" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SITUATION_FAMILIALE" MODIFY ("LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SITUATION_FAMILIALE" MODIFY ("CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SITUATION_FAMILIALE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table INTERVENANT_EXTERIEUR
--------------------------------------------------------

  ALTER TABLE "OSE"."INTERVENANT_EXTERIEUR" ADD CONSTRAINT "IE_SOURCE_UN" UNIQUE ("SOURCE_CODE") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT_EXTERIEUR" ADD CONSTRAINT "INTERVENANT_EXTERIEUR_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT_EXTERIEUR" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT_EXTERIEUR" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT_EXTERIEUR" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT_EXTERIEUR" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT_EXTERIEUR" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT_EXTERIEUR" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT_EXTERIEUR" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table VALIDATION_VOL_HORAIRE
--------------------------------------------------------

  ALTER TABLE "OSE"."VALIDATION_VOL_HORAIRE" ADD CONSTRAINT "VALIDATION_VOL_HORAIRE_PK" PRIMARY KEY ("VALIDATION_ID", "VOLUME_HORAIRE_ID") ENABLE;
  ALTER TABLE "OSE"."VALIDATION_VOL_HORAIRE" MODIFY ("VOLUME_HORAIRE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VALIDATION_VOL_HORAIRE" MODIFY ("VALIDATION_ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TYPE_FORMATION
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_FORMATION" ADD CONSTRAINT "TYPE_FORMATION__UN" UNIQUE ("SOURCE_CODE") ENABLE;
  ALTER TABLE "OSE"."TYPE_FORMATION" ADD CONSTRAINT "TYPE_FORMATION_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_FORMATION" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_FORMATION" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_FORMATION" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_FORMATION" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_FORMATION" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_FORMATION" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_FORMATION" MODIFY ("GROUPE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_FORMATION" MODIFY ("LIBELLE_COURT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_FORMATION" MODIFY ("LIBELLE_LONG" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_FORMATION" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table MODULATEUR
--------------------------------------------------------

  ALTER TABLE "OSE"."MODULATEUR" ADD CONSTRAINT "MODULATEUR__UN" UNIQUE ("CODE") ENABLE;
  ALTER TABLE "OSE"."MODULATEUR" ADD CONSTRAINT "MODULATEUR_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."MODULATEUR" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MODULATEUR" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MODULATEUR" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MODULATEUR" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MODULATEUR" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MODULATEUR" MODIFY ("PONDERATION_SERVICE_COMPL" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MODULATEUR" MODIFY ("PONDERATION_SERVICE_DU" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MODULATEUR" MODIFY ("TYPE_MODULATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MODULATEUR" MODIFY ("LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MODULATEUR" MODIFY ("CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MODULATEUR" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TEST_BUFFER
--------------------------------------------------------

  ALTER TABLE "OSE"."TEST_BUFFER" ADD CONSTRAINT "TEST_BUFFER_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."TEST_BUFFER" MODIFY ("DATA_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TEST_BUFFER" MODIFY ("TABLE_NAME" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TEST_BUFFER" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TYPE_MODULATEUR
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_MODULATEUR" ADD CONSTRAINT "TYPE_MODULATEUR_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_MODULATEUR" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_MODULATEUR" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_MODULATEUR" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_MODULATEUR" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_MODULATEUR" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_MODULATEUR" MODIFY ("SAISIE_PAR_ENSEIGNANT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_MODULATEUR" MODIFY ("OBLIGATOIRE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_MODULATEUR" MODIFY ("PUBLIQUE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_MODULATEUR" MODIFY ("LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_MODULATEUR" MODIFY ("CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_MODULATEUR" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table VALIDATION
--------------------------------------------------------

  ALTER TABLE "OSE"."VALIDATION" ADD CONSTRAINT "VALIDATION_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."VALIDATION" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VALIDATION" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VALIDATION" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VALIDATION" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VALIDATION" MODIFY ("STRUCTURE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VALIDATION" MODIFY ("INTERVENANT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VALIDATION" MODIFY ("TYPE_VALIDATION_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."VALIDATION" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FONCTION_REFERENTIEL
--------------------------------------------------------

  ALTER TABLE "OSE"."FONCTION_REFERENTIEL" ADD CONSTRAINT "FONCTION_REFERENTIEL_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."FONCTION_REFERENTIEL" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FONCTION_REFERENTIEL" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FONCTION_REFERENTIEL" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FONCTION_REFERENTIEL" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FONCTION_REFERENTIEL" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FONCTION_REFERENTIEL" MODIFY ("LIBELLE_COURT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FONCTION_REFERENTIEL" MODIFY ("LIBELLE_LONG" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FONCTION_REFERENTIEL" MODIFY ("CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FONCTION_REFERENTIEL" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table ELEMENT_PEDAGOGIQUE
--------------------------------------------------------

  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" MODIFY ("FA" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" MODIFY ("FC" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" MODIFY ("FI" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" ADD CONSTRAINT "EP_CODE__UN" UNIQUE ("SOURCE_CODE") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" ADD CONSTRAINT "ELEMENT_PEDAGOGIQUE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" MODIFY ("SOURCE_CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" MODIFY ("TAUX_FOAD" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" MODIFY ("TAUX_FA" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" MODIFY ("TAUX_FC" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" MODIFY ("TAUX_FI" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" MODIFY ("STRUCTURE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" MODIFY ("ETAPE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" MODIFY ("LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table PIECE_JOINTE_FICHIER
--------------------------------------------------------

  ALTER TABLE "OSE"."PIECE_JOINTE_FICHIER" ADD CONSTRAINT "PIECE_JOINTE_FICHIER_PK" PRIMARY KEY ("PIECE_JOINTE_ID", "FICHIER_ID") ENABLE;
  ALTER TABLE "OSE"."PIECE_JOINTE_FICHIER" MODIFY ("FICHIER_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PIECE_JOINTE_FICHIER" MODIFY ("PIECE_JOINTE_ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TYPE_CONTRAT
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_CONTRAT" ADD CONSTRAINT "TYPE_CONTRAT_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_CONTRAT" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_CONTRAT" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_CONTRAT" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_CONTRAT" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_CONTRAT" MODIFY ("LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_CONTRAT" MODIFY ("CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_CONTRAT" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FORMULE_REFERENTIEL_MAJ
--------------------------------------------------------

  ALTER TABLE "OSE"."FORMULE_REFERENTIEL_MAJ" ADD CONSTRAINT "FORMULE_REFERENTIEL_MAJ_PK" PRIMARY KEY ("INTERVENANT_ID", "ANNEE_ID") ENABLE;
  ALTER TABLE "OSE"."FORMULE_REFERENTIEL_MAJ" MODIFY ("ANNEE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_REFERENTIEL_MAJ" MODIFY ("INTERVENANT_ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table ETABLISSEMENT
--------------------------------------------------------

  ALTER TABLE "OSE"."ETABLISSEMENT" ADD CONSTRAINT "ETABLISSEMENT_SOURCE_ID_UN" UNIQUE ("SOURCE_CODE") ENABLE;
  ALTER TABLE "OSE"."ETABLISSEMENT" ADD CONSTRAINT "ETABLISSEMENT_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."ETABLISSEMENT" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETABLISSEMENT" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETABLISSEMENT" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETABLISSEMENT" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETABLISSEMENT" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETABLISSEMENT" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETABLISSEMENT" MODIFY ("LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETABLISSEMENT" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table SYNC_LOG
--------------------------------------------------------

  ALTER TABLE "OSE"."SYNC_LOG" ADD CONSTRAINT "SYNC_LOG_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."SYNC_LOG" MODIFY ("MESSAGE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SYNC_LOG" MODIFY ("DATE_SYNC" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SYNC_LOG" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table EMPLOI
--------------------------------------------------------

  ALTER TABLE "OSE"."EMPLOI" ADD CONSTRAINT "EMPLOI_UN" UNIQUE ("INTERVENANT_ID", "EMPLOYEUR_ID", "DATE_DEBUT") ENABLE;
  ALTER TABLE "OSE"."EMPLOI" ADD CONSTRAINT "EMPLOI_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."EMPLOI" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."EMPLOI" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."EMPLOI" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."EMPLOI" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."EMPLOI" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."EMPLOI" MODIFY ("INTERVENANT_EXTERIEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."EMPLOI" MODIFY ("DATE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."EMPLOI" MODIFY ("EMPLOYEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."EMPLOI" MODIFY ("INTERVENANT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."EMPLOI" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table ROLE_UTILISATEUR
--------------------------------------------------------

  ALTER TABLE "OSE"."ROLE_UTILISATEUR" ADD CONSTRAINT "UTILISATEUR_ROLE_ID_UN" UNIQUE ("ROLE_ID") ENABLE;
  ALTER TABLE "OSE"."ROLE_UTILISATEUR" ADD CONSTRAINT "ROLE_UTILISATEUR_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."ROLE_UTILISATEUR" MODIFY ("IS_DEFAULT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ROLE_UTILISATEUR" MODIFY ("ROLE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ROLE_UTILISATEUR" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TYPE_PIECE_JOINTE
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE" ADD CONSTRAINT "TYPE_PIECE_JOINTE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE" MODIFY ("LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE" MODIFY ("CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FORMULE_SERVICE_MAJ
--------------------------------------------------------

  ALTER TABLE "OSE"."FORMULE_SERVICE_MAJ" ADD CONSTRAINT "FORMULE_SERVICE_MAJ_PK" PRIMARY KEY ("SERVICE_ID") ENABLE;
  ALTER TABLE "OSE"."FORMULE_SERVICE_MAJ" MODIFY ("SERVICE_ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FORMULE_VOLUME_HORAIRE
--------------------------------------------------------

  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE" ADD CONSTRAINT "FORMULE_VOLUME_HORAIRE_UK1" UNIQUE ("VOLUME_HORAIRE_ID") ENABLE;
  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE" ADD CONSTRAINT "FORMULE_VOLUME_HORAIRE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE" MODIFY ("TYPE_INTERVENTION_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE" MODIFY ("ETAT_VOLUME_HORAIRE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE" MODIFY ("TYPE_VOLUME_HORAIRE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE" MODIFY ("SERVICE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE" MODIFY ("ANNEE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE" MODIFY ("INTERVENANT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE" MODIFY ("TAUX_SERVICE_COMPL" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE" MODIFY ("TAUX_SERVICE_DU" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE" MODIFY ("HEURES" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE" MODIFY ("VOLUME_HORAIRE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TYPE_INTERVENTION_STRUCTURE
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_INTERVENTION_STRUCTURE" ADD CONSTRAINT "TYPE_INTERVENTION_STRUCTURE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_INTERVENTION_STRUCTURE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION_STRUCTURE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION_STRUCTURE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION_STRUCTURE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION_STRUCTURE" MODIFY ("VISIBLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION_STRUCTURE" MODIFY ("STRUCTURE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION_STRUCTURE" MODIFY ("TYPE_INTERVENTION_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION_STRUCTURE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TYPE_ROLE
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_ROLE" ADD CONSTRAINT "TYPE_ROLE_CODE_UN" UNIQUE ("CODE") ENABLE;
  ALTER TABLE "OSE"."TYPE_ROLE" ADD CONSTRAINT "TYPE_ROLE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_ROLE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_ROLE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_ROLE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_ROLE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_ROLE" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_ROLE" MODIFY ("LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_ROLE" MODIFY ("CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_ROLE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table EMPLOYEUR
--------------------------------------------------------

  ALTER TABLE "OSE"."EMPLOYEUR" ADD CONSTRAINT "EMPLOYEUR_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."EMPLOYEUR" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."EMPLOYEUR" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."EMPLOYEUR" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."EMPLOYEUR" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."EMPLOYEUR" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."EMPLOYEUR" MODIFY ("CODE_NAF" NOT NULL ENABLE);
  ALTER TABLE "OSE"."EMPLOYEUR" MODIFY ("SIRET" NOT NULL ENABLE);
  ALTER TABLE "OSE"."EMPLOYEUR" MODIFY ("LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."EMPLOYEUR" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table DOSSIER
--------------------------------------------------------

  ALTER TABLE "OSE"."DOSSIER" MODIFY ("NUMERO_INSEE_EST_PROVISOIRE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DOSSIER" ADD CONSTRAINT "DOSSIER_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."DOSSIER" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DOSSIER" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DOSSIER" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DOSSIER" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DOSSIER" MODIFY ("RIB" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DOSSIER" MODIFY ("PREMIER_RECRUTEMENT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DOSSIER" MODIFY ("EMAIL" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DOSSIER" MODIFY ("ADRESSE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DOSSIER" MODIFY ("STATUT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DOSSIER" MODIFY ("NUMERO_INSEE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DOSSIER" MODIFY ("CIVILITE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DOSSIER" MODIFY ("PRENOM" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DOSSIER" MODIFY ("NOM_USUEL" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DOSSIER" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table FICHIER
--------------------------------------------------------

  ALTER TABLE "OSE"."FICHIER" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FICHIER" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FICHIER" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FICHIER" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FICHIER" ADD CONSTRAINT "FICHIER_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."FICHIER" MODIFY ("CONTENU" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FICHIER" MODIFY ("TAILLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FICHIER" MODIFY ("TYPE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FICHIER" MODIFY ("NOM" NOT NULL ENABLE);
  ALTER TABLE "OSE"."FICHIER" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table PARAMETRE
--------------------------------------------------------

  ALTER TABLE "OSE"."PARAMETRE" ADD CONSTRAINT "PARAMETRE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."PARAMETRE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PARAMETRE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PARAMETRE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PARAMETRE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PARAMETRE" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PARAMETRE" MODIFY ("NOM" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PARAMETRE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table DISCIPLINE
--------------------------------------------------------

  ALTER TABLE "OSE"."DISCIPLINE" ADD CONSTRAINT "DISCIPLINE_SOURCE_UN" UNIQUE ("SOURCE_CODE") ENABLE;
  ALTER TABLE "OSE"."DISCIPLINE" ADD CONSTRAINT "DISCIPLINE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."DISCIPLINE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DISCIPLINE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DISCIPLINE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DISCIPLINE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DISCIPLINE" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DISCIPLINE" MODIFY ("SOURCE_CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DISCIPLINE" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DISCIPLINE" MODIFY ("ORDRE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DISCIPLINE" MODIFY ("LIBELLE_LONG" NOT NULL ENABLE);
  ALTER TABLE "OSE"."DISCIPLINE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table UTILISATEUR
--------------------------------------------------------

  ALTER TABLE "OSE"."UTILISATEUR" ADD CONSTRAINT "UTILISATEUR_PERSONNEL_UN" UNIQUE ("PERSONNEL_ID") ENABLE;
  ALTER TABLE "OSE"."UTILISATEUR" ADD CONSTRAINT "UTILISATEUR_INTERVENANT_UN" UNIQUE ("INTERVENANT_ID") ENABLE;
  ALTER TABLE "OSE"."UTILISATEUR" ADD CONSTRAINT "UTILISATEUR_USERNAME_UN" UNIQUE ("USERNAME") ENABLE;
  ALTER TABLE "OSE"."UTILISATEUR" ADD CONSTRAINT "UTILISATEUR_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."UTILISATEUR" MODIFY ("STATE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."UTILISATEUR" MODIFY ("PASSWORD" NOT NULL ENABLE);
  ALTER TABLE "OSE"."UTILISATEUR" MODIFY ("DISPLAY_NAME" NOT NULL ENABLE);
  ALTER TABLE "OSE"."UTILISATEUR" MODIFY ("USERNAME" NOT NULL ENABLE);
  ALTER TABLE "OSE"."UTILISATEUR" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table PRIME_EXCELLENCE_SCIENT
--------------------------------------------------------

  ALTER TABLE "OSE"."PRIME_EXCELLENCE_SCIENT" ADD CONSTRAINT "PES_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."PRIME_EXCELLENCE_SCIENT" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PRIME_EXCELLENCE_SCIENT" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PRIME_EXCELLENCE_SCIENT" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PRIME_EXCELLENCE_SCIENT" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PRIME_EXCELLENCE_SCIENT" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PRIME_EXCELLENCE_SCIENT" MODIFY ("ANNEE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PRIME_EXCELLENCE_SCIENT" MODIFY ("INTERVENANT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."PRIME_EXCELLENCE_SCIENT" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table CORPS
--------------------------------------------------------

  ALTER TABLE "OSE"."CORPS" ADD CONSTRAINT "CORPS_SOURCE_UN" UNIQUE ("SOURCE_CODE") ENABLE;
  ALTER TABLE "OSE"."CORPS" ADD CONSTRAINT "CORPS_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."CORPS" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CORPS" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CORPS" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CORPS" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CORPS" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CORPS" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CORPS" MODIFY ("LIBELLE_COURT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CORPS" MODIFY ("LIBELLE_LONG" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CORPS" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table ROLE_UTILISATEUR_LINKER
--------------------------------------------------------

  ALTER TABLE "OSE"."ROLE_UTILISATEUR_LINKER" ADD CONSTRAINT "ROLE_UTILISATEUR_LINKER_PK" PRIMARY KEY ("UTILISATEUR_ID", "ROLE_UTILISATEUR_ID") ENABLE;
  ALTER TABLE "OSE"."ROLE_UTILISATEUR_LINKER" MODIFY ("ROLE_UTILISATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ROLE_UTILISATEUR_LINKER" MODIFY ("UTILISATEUR_ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table SOURCE
--------------------------------------------------------

  ALTER TABLE "OSE"."SOURCE" ADD CONSTRAINT "SOURCE_CODE_UN" UNIQUE ("CODE") ENABLE;
  ALTER TABLE "OSE"."SOURCE" ADD CONSTRAINT "SOURCE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."SOURCE" MODIFY ("IMPORTABLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SOURCE" MODIFY ("LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SOURCE" MODIFY ("CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."SOURCE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TYPE_AGREMENT_STATUT
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_AGREMENT_STATUT" ADD CONSTRAINT "TYPE_AGREMENT_STATUT__UN" UNIQUE ("TYPE_AGREMENT_ID", "STATUT_INTERVENANT_ID", "PREMIER_RECRUTEMENT") ENABLE;
  ALTER TABLE "OSE"."TYPE_AGREMENT_STATUT" ADD CONSTRAINT "TYPE_AGREMENT_STATUT_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_AGREMENT_STATUT" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_AGREMENT_STATUT" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_AGREMENT_STATUT" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_AGREMENT_STATUT" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_AGREMENT_STATUT" MODIFY ("OBLIGATOIRE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_AGREMENT_STATUT" MODIFY ("STATUT_INTERVENANT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_AGREMENT_STATUT" MODIFY ("TYPE_AGREMENT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_AGREMENT_STATUT" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table CONTRAT
--------------------------------------------------------

  ALTER TABLE "OSE"."CONTRAT" ADD CONSTRAINT "CONTRAT_NUMERO_AVENANT_UN" UNIQUE ("INTERVENANT_ID", "STRUCTURE_ID", "NUMERO_AVENANT", "VALIDATION_ID") ENABLE;
  ALTER TABLE "OSE"."CONTRAT" ADD CONSTRAINT "CONTRAT_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."CONTRAT" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CONTRAT" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CONTRAT" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CONTRAT" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CONTRAT" MODIFY ("NUMERO_AVENANT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CONTRAT" MODIFY ("STRUCTURE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CONTRAT" MODIFY ("INTERVENANT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CONTRAT" MODIFY ("TYPE_CONTRAT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."CONTRAT" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table MV_ANTHONY2
--------------------------------------------------------

  ALTER TABLE "OSE"."MV_ANTHONY2" MODIFY ("Z_TYPE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."MV_ANTHONY2" ADD CONSTRAINT "MV_ANTHONY2_PK" PRIMARY KEY ("SOURCE_CODE") ENABLE;
  ALTER TABLE "OSE"."MV_ANTHONY2" MODIFY ("SOURCE_CODE" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table AFFECTATION_RECHERCHE
--------------------------------------------------------

  ALTER TABLE "OSE"."AFFECTATION_RECHERCHE" ADD CONSTRAINT "AFFECTATION_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."AFFECTATION_RECHERCHE" ADD CONSTRAINT "AFFECTATION_SRC_UN" UNIQUE ("SOURCE_CODE") ENABLE;
  ALTER TABLE "OSE"."AFFECTATION_RECHERCHE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."AFFECTATION_RECHERCHE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."AFFECTATION_RECHERCHE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."AFFECTATION_RECHERCHE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."AFFECTATION_RECHERCHE" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."AFFECTATION_RECHERCHE" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."AFFECTATION_RECHERCHE" MODIFY ("STRUCTURE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."AFFECTATION_RECHERCHE" MODIFY ("INTERVENANT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."AFFECTATION_RECHERCHE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table ELEMENT_PORTEUR_PORTE
--------------------------------------------------------

  ALTER TABLE "OSE"."ELEMENT_PORTEUR_PORTE" ADD CONSTRAINT "ELEMENT_PORTEUR_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_PORTEUR_PORTE" ADD CONSTRAINT "EPP_UN" UNIQUE ("ELEMENT_PORTEUR_ID", "ELEMENT_PORTE_ID", "TYPE_INTERVENTION_ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_PORTEUR_PORTE" ADD CONSTRAINT "EPP_SOURCE_UN" UNIQUE ("SOURCE_CODE") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_PORTEUR_PORTE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PORTEUR_PORTE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PORTEUR_PORTE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PORTEUR_PORTE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PORTEUR_PORTE" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PORTEUR_PORTE" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PORTEUR_PORTE" MODIFY ("TYPE_INTERVENTION_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PORTEUR_PORTE" MODIFY ("ELEMENT_PORTE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PORTEUR_PORTE" MODIFY ("ELEMENT_PORTEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ELEMENT_PORTEUR_PORTE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table TYPE_INTERVENTION
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_INTERVENTION" ADD CONSTRAINT "TYPE_INTERVENTION_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_INTERVENTION" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION" MODIFY ("VALIDITE_DEBUT" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION" MODIFY ("VISIBLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION" MODIFY ("INTERVENTION_INDIVIDUALISEE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION" MODIFY ("TAUX_HETD_COMPLEMENTAIRE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION" MODIFY ("TAUX_HETD_SERVICE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION" MODIFY ("ORDRE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION" MODIFY ("LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION" MODIFY ("CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."TYPE_INTERVENTION" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table INTERVENANT
--------------------------------------------------------

  ALTER TABLE "OSE"."INTERVENANT" ADD CONSTRAINT "INTERVENANT_SOURCE__UN" UNIQUE ("SOURCE_CODE") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT" ADD CONSTRAINT "INTERVENANT_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT" MODIFY ("SOURCE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT" MODIFY ("STRUCTURE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT" MODIFY ("STATUT_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT" MODIFY ("TYPE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT" MODIFY ("PAYS_NAISSANCE_LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT" MODIFY ("PAYS_NAISSANCE_CODE_INSEE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT" MODIFY ("DATE_NAISSANCE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT" MODIFY ("NOM_PATRONYMIQUE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT" MODIFY ("PRENOM" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT" MODIFY ("NOM_USUEL" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT" MODIFY ("CIVILITE_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."INTERVENANT" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Constraints for Table ETAT_VOLUME_HORAIRE
--------------------------------------------------------

  ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" MODIFY ("ORDRE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" ADD CONSTRAINT "ETAT_VOLUME_HORAIRE__UN" UNIQUE ("CODE", "HISTO_DESTRUCTION") ENABLE;
  ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" ADD CONSTRAINT "ETAT_VOLUME_HORAIRE_PK" PRIMARY KEY ("ID") ENABLE;
  ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" MODIFY ("HISTO_MODIFICATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" MODIFY ("HISTO_MODIFICATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" MODIFY ("HISTO_CREATEUR_ID" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" MODIFY ("HISTO_CREATION" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" MODIFY ("LIBELLE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" MODIFY ("CODE" NOT NULL ENABLE);
  ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" MODIFY ("ID" NOT NULL ENABLE);
--------------------------------------------------------
--  Ref Constraints for Table ADRESSE_INTERVENANT
--------------------------------------------------------

  ALTER TABLE "OSE"."ADRESSE_INTERVENANT" ADD CONSTRAINT "ADRESSE_INTERVENANT_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ADRESSE_INTERVENANT" ADD CONSTRAINT "ADRESSE_INTERVENANT_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ADRESSE_INTERVENANT" ADD CONSTRAINT "ADRESSE_INTERVENANT_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ADRESSE_INTERVENANT" ADD CONSTRAINT "ADRESSE_INTERVENANT_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."ADRESSE_INTERVENANT" ADD CONSTRAINT "AII_FK" FOREIGN KEY ("INTERVENANT_ID")
	  REFERENCES "OSE"."INTERVENANT" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table ADRESSE_STRUCTURE
--------------------------------------------------------

  ALTER TABLE "OSE"."ADRESSE_STRUCTURE" ADD CONSTRAINT "ADRESSE_STRUCTURE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ADRESSE_STRUCTURE" ADD CONSTRAINT "ADRESSE_STRUCTURE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ADRESSE_STRUCTURE" ADD CONSTRAINT "ADRESSE_STRUCTURE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ADRESSE_STRUCTURE" ADD CONSTRAINT "ADRESSE_STRUCTURE_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."ADRESSE_STRUCTURE" ADD CONSTRAINT "ADRESSE_STRUCTURE_STRUCTURE_FK" FOREIGN KEY ("STRUCTURE_ID")
	  REFERENCES "OSE"."STRUCTURE" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table AFFECTATION_RECHERCHE
--------------------------------------------------------

  ALTER TABLE "OSE"."AFFECTATION_RECHERCHE" ADD CONSTRAINT "AFFECTATION_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."AFFECTATION_RECHERCHE" ADD CONSTRAINT "AFFECTATION_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."AFFECTATION_RECHERCHE" ADD CONSTRAINT "AFFECTATION_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."AFFECTATION_RECHERCHE" ADD CONSTRAINT "AFFECTATION_INTERVENANT_FK" FOREIGN KEY ("INTERVENANT_ID")
	  REFERENCES "OSE"."INTERVENANT" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."AFFECTATION_RECHERCHE" ADD CONSTRAINT "AFFECTATION_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."AFFECTATION_RECHERCHE" ADD CONSTRAINT "AFFECTATION_STRUCTURE_FK" FOREIGN KEY ("STRUCTURE_ID")
	  REFERENCES "OSE"."STRUCTURE" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table AGREMENT
--------------------------------------------------------

  ALTER TABLE "OSE"."AGREMENT" ADD CONSTRAINT "AGREMENT_ANNEE_FK" FOREIGN KEY ("ANNEE_ID")
	  REFERENCES "OSE"."ANNEE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."AGREMENT" ADD CONSTRAINT "AGREMENT_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."AGREMENT" ADD CONSTRAINT "AGREMENT_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."AGREMENT" ADD CONSTRAINT "AGREMENT_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."AGREMENT" ADD CONSTRAINT "AGREMENT_INTERVENANT_FK" FOREIGN KEY ("INTERVENANT_ID")
	  REFERENCES "OSE"."INTERVENANT" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."AGREMENT" ADD CONSTRAINT "AGREMENT_STRUCTURE_FK" FOREIGN KEY ("STRUCTURE_ID")
	  REFERENCES "OSE"."STRUCTURE" ("ID") ENABLE;
  ALTER TABLE "OSE"."AGREMENT" ADD CONSTRAINT "AGREMENT_TYPE_AGREMENT_FK" FOREIGN KEY ("TYPE_AGREMENT_ID")
	  REFERENCES "OSE"."TYPE_AGREMENT" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table CHEMIN_PEDAGOGIQUE
--------------------------------------------------------

  ALTER TABLE "OSE"."CHEMIN_PEDAGOGIQUE" ADD CONSTRAINT "CHEMIN_PEDAGOGIQUE_ETAPE_FK" FOREIGN KEY ("ETAPE_ID")
	  REFERENCES "OSE"."ETAPE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."CHEMIN_PEDAGOGIQUE" ADD CONSTRAINT "CHEMIN_PEDAGOGIQUE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."CHEMIN_PEDAGOGIQUE" ADD CONSTRAINT "CHEMIN_PEDAGOGIQUE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."CHEMIN_PEDAGOGIQUE" ADD CONSTRAINT "CHEMIN_PEDAGOGIQUE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."CHEMIN_PEDAGOGIQUE" ADD CONSTRAINT "CHEMIN_PEDAGOGIQUE_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."CHEMIN_PEDAGOGIQUE" ADD CONSTRAINT "CPEP_FK" FOREIGN KEY ("ELEMENT_PEDAGOGIQUE_ID")
	  REFERENCES "OSE"."ELEMENT_PEDAGOGIQUE" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table CONTRAT
--------------------------------------------------------

  ALTER TABLE "OSE"."CONTRAT" ADD CONSTRAINT "CONTRAT_CONTRAT_FK" FOREIGN KEY ("CONTRAT_ID")
	  REFERENCES "OSE"."CONTRAT" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."CONTRAT" ADD CONSTRAINT "CONTRAT_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."CONTRAT" ADD CONSTRAINT "CONTRAT_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."CONTRAT" ADD CONSTRAINT "CONTRAT_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."CONTRAT" ADD CONSTRAINT "CONTRAT_IE_FK" FOREIGN KEY ("INTERVENANT_ID")
	  REFERENCES "OSE"."INTERVENANT_EXTERIEUR" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."CONTRAT" ADD CONSTRAINT "CONTRAT_STRUCTURE_FK" FOREIGN KEY ("STRUCTURE_ID")
	  REFERENCES "OSE"."STRUCTURE" ("ID") ENABLE;
  ALTER TABLE "OSE"."CONTRAT" ADD CONSTRAINT "CONTRAT_TYPE_CONTRAT_FK" FOREIGN KEY ("TYPE_CONTRAT_ID")
	  REFERENCES "OSE"."TYPE_CONTRAT" ("ID") ENABLE;
  ALTER TABLE "OSE"."CONTRAT" ADD CONSTRAINT "CONTRAT_VALIDATION_FK" FOREIGN KEY ("VALIDATION_ID")
	  REFERENCES "OSE"."VALIDATION" ("ID") ON DELETE SET NULL ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table CORPS
--------------------------------------------------------

  ALTER TABLE "OSE"."CORPS" ADD CONSTRAINT "CORPS_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."CORPS" ADD CONSTRAINT "CORPS_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."CORPS" ADD CONSTRAINT "CORPS_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."CORPS" ADD CONSTRAINT "CORPS_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table DISCIPLINE
--------------------------------------------------------

  ALTER TABLE "OSE"."DISCIPLINE" ADD CONSTRAINT "DISCIPLINE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."DISCIPLINE" ADD CONSTRAINT "DISCIPLINE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."DISCIPLINE" ADD CONSTRAINT "DISCIPLINE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."DISCIPLINE" ADD CONSTRAINT "DISCIPLINE_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table DOSSIER
--------------------------------------------------------

  ALTER TABLE "OSE"."DOSSIER" ADD CONSTRAINT "DOSSIER_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."DOSSIER" ADD CONSTRAINT "DOSSIER_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."DOSSIER" ADD CONSTRAINT "DOSSIER_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table ELEMENT_DISCIPLINE
--------------------------------------------------------

  ALTER TABLE "OSE"."ELEMENT_DISCIPLINE" ADD CONSTRAINT "ED_DISCIPLINE_FK" FOREIGN KEY ("DISCIPLINE_ID")
	  REFERENCES "OSE"."DISCIPLINE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."ELEMENT_DISCIPLINE" ADD CONSTRAINT "ED_EP_FK" FOREIGN KEY ("ELEMENT_PEDAGOGIQUE_ID")
	  REFERENCES "OSE"."ELEMENT_PEDAGOGIQUE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."ELEMENT_DISCIPLINE" ADD CONSTRAINT "ELEMENT_DISCIPLINE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_DISCIPLINE" ADD CONSTRAINT "ELEMENT_DISCIPLINE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_DISCIPLINE" ADD CONSTRAINT "ELEMENT_DISCIPLINE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_DISCIPLINE" ADD CONSTRAINT "ELEMENT_DISCIPLINE_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table ELEMENT_MODULATEUR
--------------------------------------------------------

  ALTER TABLE "OSE"."ELEMENT_MODULATEUR" ADD CONSTRAINT "ELEMENT_MODULATEUR_ANNEE_FK" FOREIGN KEY ("ANNEE_ID")
	  REFERENCES "OSE"."ANNEE" ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_MODULATEUR" ADD CONSTRAINT "ELEMENT_MODULATEUR_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_MODULATEUR" ADD CONSTRAINT "ELEMENT_MODULATEUR_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_MODULATEUR" ADD CONSTRAINT "ELEMENT_MODULATEUR_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_MODULATEUR" ADD CONSTRAINT "EM_ELEMENT_PEDAGOGIQUE_FK" FOREIGN KEY ("ELEMENT_ID")
	  REFERENCES "OSE"."ELEMENT_PEDAGOGIQUE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."ELEMENT_MODULATEUR" ADD CONSTRAINT "EM_MODULATEUR_FK" FOREIGN KEY ("MODULATEUR_ID")
	  REFERENCES "OSE"."MODULATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table ELEMENT_PEDAGOGIQUE
--------------------------------------------------------

  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" ADD CONSTRAINT "ELEMENT_PEDAGOGIQUE_ETAPE_FK" FOREIGN KEY ("ETAPE_ID")
	  REFERENCES "OSE"."ETAPE" ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" ADD CONSTRAINT "ELEMENT_PEDAGOGIQUE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" ADD CONSTRAINT "ELEMENT_PEDAGOGIQUE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" ADD CONSTRAINT "ELEMENT_PEDAGOGIQUE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" ADD CONSTRAINT "ELEMENT_PEDAGOGIQUE_PERIODE_FK" FOREIGN KEY ("PERIODE_ID")
	  REFERENCES "OSE"."PERIODE" ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" ADD CONSTRAINT "ELEMENT_PEDAGOGIQUE_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" ADD CONSTRAINT "EPS_FK" FOREIGN KEY ("STRUCTURE_ID")
	  REFERENCES "OSE"."STRUCTURE" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table ELEMENT_PORTEUR_PORTE
--------------------------------------------------------

  ALTER TABLE "OSE"."ELEMENT_PORTEUR_PORTE" ADD CONSTRAINT "ELEMENT_PORTEUR_PORTE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_PORTEUR_PORTE" ADD CONSTRAINT "ELEMENT_PORTEUR_PORTE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_PORTEUR_PORTE" ADD CONSTRAINT "ELEMENT_PORTEUR_PORTE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_PORTEUR_PORTE" ADD CONSTRAINT "EPP_PORTEUR_FK" FOREIGN KEY ("ELEMENT_PORTEUR_ID")
	  REFERENCES "OSE"."ELEMENT_PEDAGOGIQUE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."ELEMENT_PORTEUR_PORTE" ADD CONSTRAINT "EPP_PORTE_FK" FOREIGN KEY ("ELEMENT_PORTE_ID")
	  REFERENCES "OSE"."ELEMENT_PEDAGOGIQUE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."ELEMENT_PORTEUR_PORTE" ADD CONSTRAINT "EPP_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_PORTEUR_PORTE" ADD CONSTRAINT "EPP_TYPE_INTERVENTION_FK" FOREIGN KEY ("TYPE_INTERVENTION_ID")
	  REFERENCES "OSE"."TYPE_INTERVENTION" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table ELEMENT_TAUX_REGIMES
--------------------------------------------------------

  ALTER TABLE "OSE"."ELEMENT_TAUX_REGIMES" ADD CONSTRAINT "ELEMENT_TAUX_REGIMES_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_TAUX_REGIMES" ADD CONSTRAINT "ELEMENT_TAUX_REGIMES_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_TAUX_REGIMES" ADD CONSTRAINT "ELEMENT_TAUX_REGIMES_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ELEMENT_TAUX_REGIMES" ADD CONSTRAINT "ELEMENT_TAUX_REGIMES_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."ELEMENT_TAUX_REGIMES" ADD CONSTRAINT "ETR_ANNEE_FK" FOREIGN KEY ("ANNEE_ID")
	  REFERENCES "OSE"."ANNEE" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table EMPLOI
--------------------------------------------------------

  ALTER TABLE "OSE"."EMPLOI" ADD CONSTRAINT "EIE_FK" FOREIGN KEY ("INTERVENANT_EXTERIEUR_ID")
	  REFERENCES "OSE"."INTERVENANT_EXTERIEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."EMPLOI" ADD CONSTRAINT "EMPLOIS_EMPLOYEURS_FK" FOREIGN KEY ("EMPLOYEUR_ID")
	  REFERENCES "OSE"."EMPLOYEUR" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."EMPLOI" ADD CONSTRAINT "EMPLOI_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."EMPLOI" ADD CONSTRAINT "EMPLOI_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."EMPLOI" ADD CONSTRAINT "EMPLOI_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table EMPLOYEUR
--------------------------------------------------------

  ALTER TABLE "OSE"."EMPLOYEUR" ADD CONSTRAINT "EMPLOYEURS_EMPLOYEURS_FK" FOREIGN KEY ("EMPLOYEUR_PERE_ID")
	  REFERENCES "OSE"."EMPLOYEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."EMPLOYEUR" ADD CONSTRAINT "EMPLOYEUR_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."EMPLOYEUR" ADD CONSTRAINT "EMPLOYEUR_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."EMPLOYEUR" ADD CONSTRAINT "EMPLOYEUR_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table ETABLISSEMENT
--------------------------------------------------------

  ALTER TABLE "OSE"."ETABLISSEMENT" ADD CONSTRAINT "ETABLISSEMENT_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ETABLISSEMENT" ADD CONSTRAINT "ETABLISSEMENT_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ETABLISSEMENT" ADD CONSTRAINT "ETABLISSEMENT_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ETABLISSEMENT" ADD CONSTRAINT "ETABLISSEMENT_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table ETAPE
--------------------------------------------------------

  ALTER TABLE "OSE"."ETAPE" ADD CONSTRAINT "ETAPE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ETAPE" ADD CONSTRAINT "ETAPE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ETAPE" ADD CONSTRAINT "ETAPE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ETAPE" ADD CONSTRAINT "ETAPE_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."ETAPE" ADD CONSTRAINT "ETAPE_STRUCTURE_FK" FOREIGN KEY ("STRUCTURE_ID")
	  REFERENCES "OSE"."STRUCTURE" ("ID") ENABLE;
  ALTER TABLE "OSE"."ETAPE" ADD CONSTRAINT "ETAPE_TYPE_FORMATION_FK" FOREIGN KEY ("TYPE_FORMATION_ID")
	  REFERENCES "OSE"."TYPE_FORMATION" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table ETAT_VOLUME_HORAIRE
--------------------------------------------------------

  ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" ADD CONSTRAINT "ETAT_VOLUME_HORAIRE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" ADD CONSTRAINT "ETAT_VOLUME_HORAIRE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" ADD CONSTRAINT "ETAT_VOLUME_HORAIRE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table FICHIER
--------------------------------------------------------

  ALTER TABLE "OSE"."FICHIER" ADD CONSTRAINT "FICHIER_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."FICHIER" ADD CONSTRAINT "FICHIER_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."FICHIER" ADD CONSTRAINT "FICHIER_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."FICHIER" ADD CONSTRAINT "FICHIER_VALID_FK" FOREIGN KEY ("VALIDATION_ID")
	  REFERENCES "OSE"."VALIDATION" ("ID") ON DELETE SET NULL ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table FONCTION_REFERENTIEL
--------------------------------------------------------

  ALTER TABLE "OSE"."FONCTION_REFERENTIEL" ADD CONSTRAINT "FONCTION_REFERENTIEL_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."FONCTION_REFERENTIEL" ADD CONSTRAINT "FONCTION_REFERENTIEL_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."FONCTION_REFERENTIEL" ADD CONSTRAINT "FONCTION_REFERENTIEL_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table FORMULE_REFERENTIEL
--------------------------------------------------------

  ALTER TABLE "OSE"."FORMULE_REFERENTIEL" ADD CONSTRAINT "FORMULE_REFERENTIEL_ANNEE_FK" FOREIGN KEY ("ANNEE_ID")
	  REFERENCES "OSE"."ANNEE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."FORMULE_REFERENTIEL" ADD CONSTRAINT "FORMULE_REF_INTERVENANT_FK" FOREIGN KEY ("INTERVENANT_ID")
	  REFERENCES "OSE"."INTERVENANT" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table FORMULE_REFERENTIEL_MAJ
--------------------------------------------------------

  ALTER TABLE "OSE"."FORMULE_REFERENTIEL_MAJ" ADD CONSTRAINT "FRM_ANNEE_FK" FOREIGN KEY ("ANNEE_ID")
	  REFERENCES "OSE"."ANNEE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."FORMULE_REFERENTIEL_MAJ" ADD CONSTRAINT "FRM_INTERVENANT_FK" FOREIGN KEY ("INTERVENANT_ID")
	  REFERENCES "OSE"."INTERVENANT" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table FORMULE_SERVICE
--------------------------------------------------------

  ALTER TABLE "OSE"."FORMULE_SERVICE" ADD CONSTRAINT "FS_ANNEE_FK" FOREIGN KEY ("ANNEE_ID")
	  REFERENCES "OSE"."ANNEE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."FORMULE_SERVICE" ADD CONSTRAINT "FS_INTERVENANT_FK" FOREIGN KEY ("INTERVENANT_ID")
	  REFERENCES "OSE"."INTERVENANT" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."FORMULE_SERVICE" ADD CONSTRAINT "FS_SERVICE_FK" FOREIGN KEY ("SERVICE_ID")
	  REFERENCES "OSE"."SERVICE" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table FORMULE_SERVICE_MAJ
--------------------------------------------------------

  ALTER TABLE "OSE"."FORMULE_SERVICE_MAJ" ADD CONSTRAINT "FSM_SERVICE_FK" FOREIGN KEY ("SERVICE_ID")
	  REFERENCES "OSE"."SERVICE" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table FORMULE_VOLUME_HORAIRE
--------------------------------------------------------

  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE" ADD CONSTRAINT "FVH_ANNEE_FK" FOREIGN KEY ("ANNEE_ID")
	  REFERENCES "OSE"."ANNEE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE" ADD CONSTRAINT "FVH_ETAT_VOLUME_HORAIRE_FK" FOREIGN KEY ("ETAT_VOLUME_HORAIRE_ID")
	  REFERENCES "OSE"."ETAT_VOLUME_HORAIRE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE" ADD CONSTRAINT "FVH_INTERVENANT_FK" FOREIGN KEY ("INTERVENANT_ID")
	  REFERENCES "OSE"."INTERVENANT" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE" ADD CONSTRAINT "FVH_SERVICE_FK" FOREIGN KEY ("SERVICE_ID")
	  REFERENCES "OSE"."SERVICE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE" ADD CONSTRAINT "FVH_TYPE_INTERVENTION_FK" FOREIGN KEY ("TYPE_INTERVENTION_ID")
	  REFERENCES "OSE"."TYPE_INTERVENTION" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE" ADD CONSTRAINT "FVH_TYPE_VOLUME_HORAIRE_FK" FOREIGN KEY ("TYPE_VOLUME_HORAIRE_ID")
	  REFERENCES "OSE"."TYPE_VOLUME_HORAIRE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE" ADD CONSTRAINT "FVH_VOLUME_HORAIRE_FK" FOREIGN KEY ("VOLUME_HORAIRE_ID")
	  REFERENCES "OSE"."VOLUME_HORAIRE" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table FORMULE_VOLUME_HORAIRE_MAJ
--------------------------------------------------------

  ALTER TABLE "OSE"."FORMULE_VOLUME_HORAIRE_MAJ" ADD CONSTRAINT "FVHM_VOLUME_HORAIRE_FK" FOREIGN KEY ("VOLUME_HORAIRE_ID")
	  REFERENCES "OSE"."VOLUME_HORAIRE" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table GROUPE
--------------------------------------------------------

  ALTER TABLE "OSE"."GROUPE" ADD CONSTRAINT "GROUPE_ANNEE_FK" FOREIGN KEY ("ANNEE_ID")
	  REFERENCES "OSE"."ANNEE" ("ID") ENABLE;
  ALTER TABLE "OSE"."GROUPE" ADD CONSTRAINT "GROUPE_ELEMENT_PEDAGOGIQUE_FK" FOREIGN KEY ("ELEMENT_PEDAGOGIQUE_ID")
	  REFERENCES "OSE"."ELEMENT_PEDAGOGIQUE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."GROUPE" ADD CONSTRAINT "GROUPE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."GROUPE" ADD CONSTRAINT "GROUPE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."GROUPE" ADD CONSTRAINT "GROUPE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."GROUPE" ADD CONSTRAINT "GROUPE_TYPE_INTERVENTION_FK" FOREIGN KEY ("TYPE_INTERVENTION_ID")
	  REFERENCES "OSE"."TYPE_INTERVENTION" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table GROUPE_TYPE_FORMATION
--------------------------------------------------------

  ALTER TABLE "OSE"."GROUPE_TYPE_FORMATION" ADD CONSTRAINT "GROUPE_TYPE_FORMATION_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."GROUPE_TYPE_FORMATION" ADD CONSTRAINT "GROUPE_TYPE_FORMATION_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."GROUPE_TYPE_FORMATION" ADD CONSTRAINT "GROUPE_TYPE_FORMATION_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."GROUPE_TYPE_FORMATION" ADD CONSTRAINT "GTYPE_FORMATION_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table INTERVENANT
--------------------------------------------------------

  ALTER TABLE "OSE"."INTERVENANT" ADD CONSTRAINT "IIT_FK" FOREIGN KEY ("TYPE_ID")
	  REFERENCES "OSE"."TYPE_INTERVENANT" ("ID") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT" ADD CONSTRAINT "INTERVENANTS_CIVILITES_FK" FOREIGN KEY ("CIVILITE_ID")
	  REFERENCES "OSE"."CIVILITE" ("ID") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT" ADD CONSTRAINT "INTERVENANT_DISCIPLINE_FK" FOREIGN KEY ("DISCIPLINE_ID")
	  REFERENCES "OSE"."DISCIPLINE" ("ID") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT" ADD CONSTRAINT "INTERVENANT_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT" ADD CONSTRAINT "INTERVENANT_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT" ADD CONSTRAINT "INTERVENANT_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT" ADD CONSTRAINT "INTERVENANT_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."INTERVENANT" ADD CONSTRAINT "INTERVENANT_STATUT_FK" FOREIGN KEY ("STATUT_ID")
	  REFERENCES "OSE"."STATUT_INTERVENANT" ("ID") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT" ADD CONSTRAINT "INTERVENANT_STRUCTURE_FK" FOREIGN KEY ("STRUCTURE_ID")
	  REFERENCES "OSE"."STRUCTURE" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table INTERVENANT_EXTERIEUR
--------------------------------------------------------

  ALTER TABLE "OSE"."INTERVENANT_EXTERIEUR" ADD CONSTRAINT "IEI_FK" FOREIGN KEY ("ID")
	  REFERENCES "OSE"."INTERVENANT" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."INTERVENANT_EXTERIEUR" ADD CONSTRAINT "IERS_FK" FOREIGN KEY ("REGIME_SECU_ID")
	  REFERENCES "OSE"."REGIME_SECU" ("ID") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT_EXTERIEUR" ADD CONSTRAINT "IESF_FKV2" FOREIGN KEY ("SITUATION_FAMILIALE_ID")
	  REFERENCES "OSE"."SITUATION_FAMILIALE" ("ID") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT_EXTERIEUR" ADD CONSTRAINT "IE_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT_EXTERIEUR" ADD CONSTRAINT "IE_TYPE_POSTE_FK" FOREIGN KEY ("TYPE_POSTE_ID")
	  REFERENCES "OSE"."TYPE_POSTE" ("ID") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT_EXTERIEUR" ADD CONSTRAINT "INTERVENANT_EXTERIEUR_DOSSIER" FOREIGN KEY ("DOSSIER_ID")
	  REFERENCES "OSE"."DOSSIER" ("ID") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT_EXTERIEUR" ADD CONSTRAINT "INTERVENANT_EXTERIEUR_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT_EXTERIEUR" ADD CONSTRAINT "INTERVENANT_EXTERIEUR_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT_EXTERIEUR" ADD CONSTRAINT "INTERVENANT_EXTERIEUR_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table INTERVENANT_PERMANENT
--------------------------------------------------------

  ALTER TABLE "OSE"."INTERVENANT_PERMANENT" ADD CONSTRAINT "INTERVENANT_PERMANENT_CORPS_FK" FOREIGN KEY ("CORPS_ID")
	  REFERENCES "OSE"."CORPS" ("ID") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT_PERMANENT" ADD CONSTRAINT "INTERVENANT_PERMANENT_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT_PERMANENT" ADD CONSTRAINT "INTERVENANT_PERMANENT_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT_PERMANENT" ADD CONSTRAINT "INTERVENANT_PERMANENT_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."INTERVENANT_PERMANENT" ADD CONSTRAINT "IPI_FK" FOREIGN KEY ("ID")
	  REFERENCES "OSE"."INTERVENANT" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."INTERVENANT_PERMANENT" ADD CONSTRAINT "IP_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table MODIFICATION_SERVICE_DU
--------------------------------------------------------

  ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" ADD CONSTRAINT "DS_ANNEE_FK" FOREIGN KEY ("ANNEE_ID")
	  REFERENCES "OSE"."ANNEE" ("ID") ENABLE;
  ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" ADD CONSTRAINT "DS_IP_FK" FOREIGN KEY ("INTERVENANT_ID")
	  REFERENCES "OSE"."INTERVENANT_PERMANENT" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" ADD CONSTRAINT "DS_MDS_FK" FOREIGN KEY ("MOTIF_ID")
	  REFERENCES "OSE"."MOTIF_MODIFICATION_SERVICE" ("ID") ENABLE;
  ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" ADD CONSTRAINT "MODIFICATION_SERVICE_DU_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" ADD CONSTRAINT "MODIFICATION_SERVICE_DU_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" ADD CONSTRAINT "MODIFICATION_SERVICE_DU_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table MODULATEUR
--------------------------------------------------------

  ALTER TABLE "OSE"."MODULATEUR" ADD CONSTRAINT "MODULATEUR_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."MODULATEUR" ADD CONSTRAINT "MODULATEUR_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."MODULATEUR" ADD CONSTRAINT "MODULATEUR_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."MODULATEUR" ADD CONSTRAINT "MODULATEUR_TYPE_MODULATEUR_FK" FOREIGN KEY ("TYPE_MODULATEUR_ID")
	  REFERENCES "OSE"."TYPE_MODULATEUR" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table MOTIF_MODIFICATION_SERVICE
--------------------------------------------------------

  ALTER TABLE "OSE"."MOTIF_MODIFICATION_SERVICE" ADD CONSTRAINT "MOTIF_MODIFICATION_SERVIC_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."MOTIF_MODIFICATION_SERVICE" ADD CONSTRAINT "MOTIF_MODIFICATION_SERVIC_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."MOTIF_MODIFICATION_SERVICE" ADD CONSTRAINT "MOTIF_MODIFICATION_SERVIC_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table MOTIF_NON_PAIEMENT
--------------------------------------------------------

  ALTER TABLE "OSE"."MOTIF_NON_PAIEMENT" ADD CONSTRAINT "MOTIF_NON_PAIEMENT_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."MOTIF_NON_PAIEMENT" ADD CONSTRAINT "MOTIF_NON_PAIEMENT_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."MOTIF_NON_PAIEMENT" ADD CONSTRAINT "MOTIF_NON_PAIEMENT_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table PARAMETRE
--------------------------------------------------------

  ALTER TABLE "OSE"."PARAMETRE" ADD CONSTRAINT "PARAMETRE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."PARAMETRE" ADD CONSTRAINT "PARAMETRE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."PARAMETRE" ADD CONSTRAINT "PARAMETRE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table PERIODE
--------------------------------------------------------

  ALTER TABLE "OSE"."PERIODE" ADD CONSTRAINT "PERIODE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."PERIODE" ADD CONSTRAINT "PERIODE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."PERIODE" ADD CONSTRAINT "PERIODE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."PERIODE" ADD CONSTRAINT "PERIODE_TYPE_INTERVENANT_FK" FOREIGN KEY ("TYPE_INTERVENANT_ID")
	  REFERENCES "OSE"."TYPE_INTERVENANT" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table PERSONNEL
--------------------------------------------------------

  ALTER TABLE "OSE"."PERSONNEL" ADD CONSTRAINT "PERSONNEL_CIVILITE_FK" FOREIGN KEY ("CIVILITE_ID")
	  REFERENCES "OSE"."CIVILITE" ("ID") ENABLE;
  ALTER TABLE "OSE"."PERSONNEL" ADD CONSTRAINT "PERSONNEL_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."PERSONNEL" ADD CONSTRAINT "PERSONNEL_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."PERSONNEL" ADD CONSTRAINT "PERSONNEL_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."PERSONNEL" ADD CONSTRAINT "PERSONNEL_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."PERSONNEL" ADD CONSTRAINT "PERSONNEL_STRUCTURE_FK" FOREIGN KEY ("STRUCTURE_ID")
	  REFERENCES "OSE"."STRUCTURE" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table PIECE_JOINTE
--------------------------------------------------------

  ALTER TABLE "OSE"."PIECE_JOINTE" ADD CONSTRAINT "PIECE_JOINTE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."PIECE_JOINTE" ADD CONSTRAINT "PIECE_JOINTE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."PIECE_JOINTE" ADD CONSTRAINT "PIECE_JOINTE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."PIECE_JOINTE" ADD CONSTRAINT "PIECE_JOINTE_VFK" FOREIGN KEY ("VALIDATION_ID")
	  REFERENCES "OSE"."VALIDATION" ("ID") ON DELETE SET NULL ENABLE;
  ALTER TABLE "OSE"."PIECE_JOINTE" ADD CONSTRAINT "PJ_DOSSIER_FK" FOREIGN KEY ("DOSSIER_ID")
	  REFERENCES "OSE"."DOSSIER" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."PIECE_JOINTE" ADD CONSTRAINT "PJ_TYPE_PIECE_JOINTE_FK" FOREIGN KEY ("TYPE_PIECE_JOINTE_ID")
	  REFERENCES "OSE"."TYPE_PIECE_JOINTE" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table PIECE_JOINTE_FICHIER
--------------------------------------------------------

  ALTER TABLE "OSE"."PIECE_JOINTE_FICHIER" ADD CONSTRAINT "PIECE_JOINTE_FICHIER_FFK" FOREIGN KEY ("FICHIER_ID")
	  REFERENCES "OSE"."FICHIER" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."PIECE_JOINTE_FICHIER" ADD CONSTRAINT "PIECE_JOINTE_FICHIER_PJFK" FOREIGN KEY ("PIECE_JOINTE_ID")
	  REFERENCES "OSE"."PIECE_JOINTE" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table PRIME_EXCELLENCE_SCIENT
--------------------------------------------------------

  ALTER TABLE "OSE"."PRIME_EXCELLENCE_SCIENT" ADD CONSTRAINT "PESA_FK" FOREIGN KEY ("ANNEE_ID")
	  REFERENCES "OSE"."ANNEE" ("ID") ENABLE;
  ALTER TABLE "OSE"."PRIME_EXCELLENCE_SCIENT" ADD CONSTRAINT "PESI_FK" FOREIGN KEY ("INTERVENANT_ID")
	  REFERENCES "OSE"."INTERVENANT" ("ID") ENABLE;
  ALTER TABLE "OSE"."PRIME_EXCELLENCE_SCIENT" ADD CONSTRAINT "PRIME_EXCELLENCE_SCIENTIF_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."PRIME_EXCELLENCE_SCIENT" ADD CONSTRAINT "PRIME_EXCELLENCE_SCIENTIF_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."PRIME_EXCELLENCE_SCIENT" ADD CONSTRAINT "PRIME_EXCELLENCE_SCIENTIF_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table REGIME_SECU
--------------------------------------------------------

  ALTER TABLE "OSE"."REGIME_SECU" ADD CONSTRAINT "REGIME_SECU_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."REGIME_SECU" ADD CONSTRAINT "REGIME_SECU_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."REGIME_SECU" ADD CONSTRAINT "REGIME_SECU_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table ROLE
--------------------------------------------------------

  ALTER TABLE "OSE"."ROLE" ADD CONSTRAINT "ROLE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ROLE" ADD CONSTRAINT "ROLE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ROLE" ADD CONSTRAINT "ROLE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."ROLE" ADD CONSTRAINT "ROLE_PERSONNEL_FK" FOREIGN KEY ("PERSONNEL_ID")
	  REFERENCES "OSE"."PERSONNEL" ("ID") ENABLE;
  ALTER TABLE "OSE"."ROLE" ADD CONSTRAINT "ROLE_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ENABLE;
  ALTER TABLE "OSE"."ROLE" ADD CONSTRAINT "ROLE_STRUCTURE_FK" FOREIGN KEY ("STRUCTURE_ID")
	  REFERENCES "OSE"."STRUCTURE" ("ID") ENABLE;
  ALTER TABLE "OSE"."ROLE" ADD CONSTRAINT "ROLE_TYPE_ROLE_FK" FOREIGN KEY ("TYPE_ID")
	  REFERENCES "OSE"."TYPE_ROLE" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table ROLE_UTILISATEUR
--------------------------------------------------------

  ALTER TABLE "OSE"."ROLE_UTILISATEUR" ADD CONSTRAINT "ROLE_UTILISATEUR_PARENT_FK" FOREIGN KEY ("PARENT_ID")
	  REFERENCES "OSE"."ROLE_UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table ROLE_UTILISATEUR_LINKER
--------------------------------------------------------

  ALTER TABLE "OSE"."ROLE_UTILISATEUR_LINKER" ADD CONSTRAINT "RUL_UTILISATEUR_FK" FOREIGN KEY ("UTILISATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."ROLE_UTILISATEUR_LINKER" ADD CONSTRAINT "RUL_UTILISATEUR_ROLE_FK" FOREIGN KEY ("ROLE_UTILISATEUR_ID")
	  REFERENCES "OSE"."ROLE_UTILISATEUR" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table SERVICE
--------------------------------------------------------

  ALTER TABLE "OSE"."SERVICE" ADD CONSTRAINT "SERVICE_ANNEE_FK" FOREIGN KEY ("ANNEE_ID")
	  REFERENCES "OSE"."ANNEE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."SERVICE" ADD CONSTRAINT "SERVICE_ELEMENT_PEDAGOGIQUE_FK" FOREIGN KEY ("ELEMENT_PEDAGOGIQUE_ID")
	  REFERENCES "OSE"."ELEMENT_PEDAGOGIQUE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."SERVICE" ADD CONSTRAINT "SERVICE_ETABLISSEMENT_FK" FOREIGN KEY ("ETABLISSEMENT_ID")
	  REFERENCES "OSE"."ETABLISSEMENT" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."SERVICE" ADD CONSTRAINT "SERVICE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."SERVICE" ADD CONSTRAINT "SERVICE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."SERVICE" ADD CONSTRAINT "SERVICE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."SERVICE" ADD CONSTRAINT "SERVICE_INTERVENANT_FK" FOREIGN KEY ("INTERVENANT_ID")
	  REFERENCES "OSE"."INTERVENANT" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."SERVICE" ADD CONSTRAINT "SERVICE_STRUCTURE_AFF_FK" FOREIGN KEY ("STRUCTURE_AFF_ID")
	  REFERENCES "OSE"."STRUCTURE" ("ID") ENABLE;
  ALTER TABLE "OSE"."SERVICE" ADD CONSTRAINT "SERVICE_STRUCTURE_ENS_FK" FOREIGN KEY ("STRUCTURE_ENS_ID")
	  REFERENCES "OSE"."STRUCTURE" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table SERVICE_DU
--------------------------------------------------------

  ALTER TABLE "OSE"."SERVICE_DU" ADD CONSTRAINT "SERVICE_DU_ANNEE_FK" FOREIGN KEY ("ANNEE_ID")
	  REFERENCES "OSE"."ANNEE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."SERVICE_DU" ADD CONSTRAINT "SERVICE_DU_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."SERVICE_DU" ADD CONSTRAINT "SERVICE_DU_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."SERVICE_DU" ADD CONSTRAINT "SERVICE_DU_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."SERVICE_DU" ADD CONSTRAINT "SERVICE_DU_INTERVENANT_FK" FOREIGN KEY ("INTERVENANT_ID")
	  REFERENCES "OSE"."INTERVENANT" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table SERVICE_REFERENTIEL
--------------------------------------------------------

  ALTER TABLE "OSE"."SERVICE_REFERENTIEL" ADD CONSTRAINT "SERVICES_REFERENTIEL_ANNEES_FK" FOREIGN KEY ("ANNEE_ID")
	  REFERENCES "OSE"."ANNEE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."SERVICE_REFERENTIEL" ADD CONSTRAINT "SERVICE_REFERENTIEL_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."SERVICE_REFERENTIEL" ADD CONSTRAINT "SERVICE_REFERENTIEL_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."SERVICE_REFERENTIEL" ADD CONSTRAINT "SERVICE_REFERENTIEL_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."SERVICE_REFERENTIEL" ADD CONSTRAINT "SRFR_FK" FOREIGN KEY ("FONCTION_ID")
	  REFERENCES "OSE"."FONCTION_REFERENTIEL" ("ID") ENABLE;
  ALTER TABLE "OSE"."SERVICE_REFERENTIEL" ADD CONSTRAINT "SR_IP_FK" FOREIGN KEY ("INTERVENANT_ID")
	  REFERENCES "OSE"."INTERVENANT_PERMANENT" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."SERVICE_REFERENTIEL" ADD CONSTRAINT "SR_STRUCTURE_FK" FOREIGN KEY ("STRUCTURE_ID")
	  REFERENCES "OSE"."STRUCTURE" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table SITUATION_FAMILIALE
--------------------------------------------------------

  ALTER TABLE "OSE"."SITUATION_FAMILIALE" ADD CONSTRAINT "SITUATION_FAMILIALE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."SITUATION_FAMILIALE" ADD CONSTRAINT "SITUATION_FAMILIALE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."SITUATION_FAMILIALE" ADD CONSTRAINT "SITUATION_FAMILIALE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table STATUT_INTERVENANT
--------------------------------------------------------

  ALTER TABLE "OSE"."STATUT_INTERVENANT" ADD CONSTRAINT "STATUT_INTERVENANT_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."STATUT_INTERVENANT" ADD CONSTRAINT "STATUT_INTERVENANT_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."STATUT_INTERVENANT" ADD CONSTRAINT "STATUT_INTERVENANT_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."STATUT_INTERVENANT" ADD CONSTRAINT "STATUT_INTERVENANT_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."STATUT_INTERVENANT" ADD CONSTRAINT "STATUT_INTERVENANT_TYPE_FK" FOREIGN KEY ("TYPE_INTERVENANT_ID")
	  REFERENCES "OSE"."TYPE_INTERVENANT" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table STRUCTURE
--------------------------------------------------------

  ALTER TABLE "OSE"."STRUCTURE" ADD CONSTRAINT "STRUCTURES_STRUCTURES_FK" FOREIGN KEY ("PARENTE_ID")
	  REFERENCES "OSE"."STRUCTURE" ("ID") ENABLE;
  ALTER TABLE "OSE"."STRUCTURE" ADD CONSTRAINT "STRUCTURE_ETABLISSEMENT_FK" FOREIGN KEY ("ETABLISSEMENT_ID")
	  REFERENCES "OSE"."ETABLISSEMENT" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."STRUCTURE" ADD CONSTRAINT "STRUCTURE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."STRUCTURE" ADD CONSTRAINT "STRUCTURE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."STRUCTURE" ADD CONSTRAINT "STRUCTURE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."STRUCTURE" ADD CONSTRAINT "STRUCTURE_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."STRUCTURE" ADD CONSTRAINT "STRUCTURE_STRUCTURE_FK" FOREIGN KEY ("STRUCTURE_NIV2_ID")
	  REFERENCES "OSE"."STRUCTURE" ("ID") ON DELETE SET NULL ENABLE;
  ALTER TABLE "OSE"."STRUCTURE" ADD CONSTRAINT "STRUCTURE_TYPE_STRUCTURE_FK" FOREIGN KEY ("TYPE_ID")
	  REFERENCES "OSE"."TYPE_STRUCTURE" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table TAUX_HORAIRE_HETD
--------------------------------------------------------

  ALTER TABLE "OSE"."TAUX_HORAIRE_HETD" ADD CONSTRAINT "TAUX_HORAIRE_HETD_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TAUX_HORAIRE_HETD" ADD CONSTRAINT "TAUX_HORAIRE_HETD_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TAUX_HORAIRE_HETD" ADD CONSTRAINT "TAUX_HORAIRE_HETD_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table TYPE_AGREMENT
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_AGREMENT" ADD CONSTRAINT "TYPE_AGREMENT_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_AGREMENT" ADD CONSTRAINT "TYPE_AGREMENT_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_AGREMENT" ADD CONSTRAINT "TYPE_AGREMENT_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table TYPE_AGREMENT_STATUT
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_AGREMENT_STATUT" ADD CONSTRAINT "TAS_STATUT_INTERVENANT_FK" FOREIGN KEY ("STATUT_INTERVENANT_ID")
	  REFERENCES "OSE"."STATUT_INTERVENANT" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."TYPE_AGREMENT_STATUT" ADD CONSTRAINT "TAS_TYPE_AGREMENT_FK" FOREIGN KEY ("TYPE_AGREMENT_ID")
	  REFERENCES "OSE"."TYPE_AGREMENT" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."TYPE_AGREMENT_STATUT" ADD CONSTRAINT "TYPE_AGREMENT_STATUT_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_AGREMENT_STATUT" ADD CONSTRAINT "TYPE_AGREMENT_STATUT_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_AGREMENT_STATUT" ADD CONSTRAINT "TYPE_AGREMENT_STATUT_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table TYPE_CONTRAT
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_CONTRAT" ADD CONSTRAINT "TYPE_CONTRAT_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_CONTRAT" ADD CONSTRAINT "TYPE_CONTRAT_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_CONTRAT" ADD CONSTRAINT "TYPE_CONTRAT_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table TYPE_FORMATION
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_FORMATION" ADD CONSTRAINT "TYPE_FORMATION_GROUPE_FK" FOREIGN KEY ("GROUPE_ID")
	  REFERENCES "OSE"."GROUPE_TYPE_FORMATION" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_FORMATION" ADD CONSTRAINT "TYPE_FORMATION_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_FORMATION" ADD CONSTRAINT "TYPE_FORMATION_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_FORMATION" ADD CONSTRAINT "TYPE_FORMATION_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_FORMATION" ADD CONSTRAINT "TYPE_FORMATION_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table TYPE_INTERVENANT
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_INTERVENANT" ADD CONSTRAINT "TYPE_INTERVENANT_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_INTERVENANT" ADD CONSTRAINT "TYPE_INTERVENANT_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_INTERVENANT" ADD CONSTRAINT "TYPE_INTERVENANT_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table TYPE_INTERVENTION
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_INTERVENTION" ADD CONSTRAINT "TYPE_INTERVENTION_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_INTERVENTION" ADD CONSTRAINT "TYPE_INTERVENTION_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_INTERVENTION" ADD CONSTRAINT "TYPE_INTERVENTION_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table TYPE_INTERVENTION_EP
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_INTERVENTION_EP" ADD CONSTRAINT "TIEP_ELEMENT_PEDAGOGIQUE_FK" FOREIGN KEY ("ELEMENT_PEDAGOGIQUE_ID")
	  REFERENCES "OSE"."ELEMENT_PEDAGOGIQUE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."TYPE_INTERVENTION_EP" ADD CONSTRAINT "TIEP_TYPE_INTERVENTION_FK" FOREIGN KEY ("TYPE_INTERVENTION_ID")
	  REFERENCES "OSE"."TYPE_INTERVENTION" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."TYPE_INTERVENTION_EP" ADD CONSTRAINT "TYPE_INTERVENTION_EP_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_INTERVENTION_EP" ADD CONSTRAINT "TYPE_INTERVENTION_EP_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_INTERVENTION_EP" ADD CONSTRAINT "TYPE_INTERVENTION_EP_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_INTERVENTION_EP" ADD CONSTRAINT "TYPE_INTERVENTION_EP_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table TYPE_INTERVENTION_STRUCTURE
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_INTERVENTION_STRUCTURE" ADD CONSTRAINT "TIS_STRUCTURE_FK" FOREIGN KEY ("STRUCTURE_ID")
	  REFERENCES "OSE"."STRUCTURE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."TYPE_INTERVENTION_STRUCTURE" ADD CONSTRAINT "TIS_TYPE_INTERVENTION_FK" FOREIGN KEY ("TYPE_INTERVENTION_ID")
	  REFERENCES "OSE"."TYPE_INTERVENTION" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."TYPE_INTERVENTION_STRUCTURE" ADD CONSTRAINT "TYPE_INTERVENTION_STRUCTU_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_INTERVENTION_STRUCTURE" ADD CONSTRAINT "TYPE_INTERVENTION_STRUCTU_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_INTERVENTION_STRUCTURE" ADD CONSTRAINT "TYPE_INTERVENTION_STRUCTU_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table TYPE_MODULATEUR
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_MODULATEUR" ADD CONSTRAINT "TYPE_MODULATEUR_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_MODULATEUR" ADD CONSTRAINT "TYPE_MODULATEUR_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_MODULATEUR" ADD CONSTRAINT "TYPE_MODULATEUR_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table TYPE_MODULATEUR_STRUCTURE
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_MODULATEUR_STRUCTURE" ADD CONSTRAINT "TMS_STRUCTURE_FK" FOREIGN KEY ("STRUCTURE_ID")
	  REFERENCES "OSE"."STRUCTURE" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_MODULATEUR_STRUCTURE" ADD CONSTRAINT "TMS_TYPE_MODUL_FK" FOREIGN KEY ("TYPE_MODULATEUR_ID")
	  REFERENCES "OSE"."TYPE_MODULATEUR" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."TYPE_MODULATEUR_STRUCTURE" ADD CONSTRAINT "TYPE_MODULATEUR_STRUCTURE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_MODULATEUR_STRUCTURE" ADD CONSTRAINT "TYPE_MODULATEUR_STRUCTURE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_MODULATEUR_STRUCTURE" ADD CONSTRAINT "TYPE_MODULATEUR_STRUCTURE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table TYPE_PIECE_JOINTE
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE" ADD CONSTRAINT "TYPE_PIECE_JOINTE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE" ADD CONSTRAINT "TYPE_PIECE_JOINTE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE" ADD CONSTRAINT "TYPE_PIECE_JOINTE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table TYPE_PIECE_JOINTE_STATUT
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE_STATUT" ADD CONSTRAINT "TPJS_STATUT_INTERVENANT_FK" FOREIGN KEY ("STATUT_INTERVENANT_ID")
	  REFERENCES "OSE"."STATUT_INTERVENANT" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE_STATUT" ADD CONSTRAINT "TPJS_TYPE_PIECE_JOINTE_FK" FOREIGN KEY ("TYPE_PIECE_JOINTE_ID")
	  REFERENCES "OSE"."TYPE_PIECE_JOINTE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE_STATUT" ADD CONSTRAINT "TYPE_PIECE_JOINTE_STATUT_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE_STATUT" ADD CONSTRAINT "TYPE_PIECE_JOINTE_STATUT_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_PIECE_JOINTE_STATUT" ADD CONSTRAINT "TYPE_PIECE_JOINTE_STATUT_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table TYPE_POSTE
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_POSTE" ADD CONSTRAINT "TYPE_POSTE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_POSTE" ADD CONSTRAINT "TYPE_POSTE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_POSTE" ADD CONSTRAINT "TYPE_POSTE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table TYPE_ROLE
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_ROLE" ADD CONSTRAINT "TYPE_ROLE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_ROLE" ADD CONSTRAINT "TYPE_ROLE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_ROLE" ADD CONSTRAINT "TYPE_ROLE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table TYPE_ROLE_STRUCTURE
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_ROLE_STRUCTURE" ADD CONSTRAINT "TRSTS_FK" FOREIGN KEY ("TYPE_STRUCTURE_ID")
	  REFERENCES "OSE"."TYPE_STRUCTURE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."TYPE_ROLE_STRUCTURE" ADD CONSTRAINT "TRS_TYPE_ROLE_FK" FOREIGN KEY ("TYPE_ROLE_ID")
	  REFERENCES "OSE"."TYPE_ROLE" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_ROLE_STRUCTURE" ADD CONSTRAINT "TYPE_ROLE_STRUCTURE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_ROLE_STRUCTURE" ADD CONSTRAINT "TYPE_ROLE_STRUCTURE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_ROLE_STRUCTURE" ADD CONSTRAINT "TYPE_ROLE_STRUCTURE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table TYPE_STRUCTURE
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_STRUCTURE" ADD CONSTRAINT "TYPE_STRUCTURE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_STRUCTURE" ADD CONSTRAINT "TYPE_STRUCTURE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_STRUCTURE" ADD CONSTRAINT "TYPE_STRUCTURE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table TYPE_VALIDATION
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_VALIDATION" ADD CONSTRAINT "TYPE_VALIDATION_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_VALIDATION" ADD CONSTRAINT "TYPE_VALIDATION_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_VALIDATION" ADD CONSTRAINT "TYPE_VALIDATION_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table TYPE_VOLUME_HORAIRE
--------------------------------------------------------

  ALTER TABLE "OSE"."TYPE_VOLUME_HORAIRE" ADD CONSTRAINT "TYPE_VOLUME_HORAIRE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_VOLUME_HORAIRE" ADD CONSTRAINT "TYPE_VOLUME_HORAIRE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."TYPE_VOLUME_HORAIRE" ADD CONSTRAINT "TYPE_VOLUME_HORAIRE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table UTILISATEUR
--------------------------------------------------------

  ALTER TABLE "OSE"."UTILISATEUR" ADD CONSTRAINT "UTILISATEUR_INTERVENANT_FK" FOREIGN KEY ("INTERVENANT_ID")
	  REFERENCES "OSE"."INTERVENANT" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."UTILISATEUR" ADD CONSTRAINT "UTILISATEUR_PERSONNEL_FK" FOREIGN KEY ("PERSONNEL_ID")
	  REFERENCES "OSE"."PERSONNEL" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table VALIDATION
--------------------------------------------------------

  ALTER TABLE "OSE"."VALIDATION" ADD CONSTRAINT "VALIDATION_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."VALIDATION" ADD CONSTRAINT "VALIDATION_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."VALIDATION" ADD CONSTRAINT "VALIDATION_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."VALIDATION" ADD CONSTRAINT "VALIDATION_INTERVENANT_FK" FOREIGN KEY ("INTERVENANT_ID")
	  REFERENCES "OSE"."INTERVENANT" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."VALIDATION" ADD CONSTRAINT "VALIDATION_STRUCTURE_FK" FOREIGN KEY ("STRUCTURE_ID")
	  REFERENCES "OSE"."STRUCTURE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."VALIDATION" ADD CONSTRAINT "VALIDATION_TYPE_VALIDATION_FK" FOREIGN KEY ("TYPE_VALIDATION_ID")
	  REFERENCES "OSE"."TYPE_VALIDATION" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table VALIDATION_VOL_HORAIRE
--------------------------------------------------------

  ALTER TABLE "OSE"."VALIDATION_VOL_HORAIRE" ADD CONSTRAINT "VVH_VALIDATION_FK" FOREIGN KEY ("VALIDATION_ID")
	  REFERENCES "OSE"."VALIDATION" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."VALIDATION_VOL_HORAIRE" ADD CONSTRAINT "VVH_VOLUME_HORAIRE_FK" FOREIGN KEY ("VOLUME_HORAIRE_ID")
	  REFERENCES "OSE"."VOLUME_HORAIRE" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table VOLUME_HORAIRE
--------------------------------------------------------

  ALTER TABLE "OSE"."VOLUME_HORAIRE" ADD CONSTRAINT "VHIT_FK" FOREIGN KEY ("TYPE_INTERVENTION_ID")
	  REFERENCES "OSE"."TYPE_INTERVENTION" ("ID") ENABLE;
  ALTER TABLE "OSE"."VOLUME_HORAIRE" ADD CONSTRAINT "VHMNP_FK" FOREIGN KEY ("MOTIF_NON_PAIEMENT_ID")
	  REFERENCES "OSE"."MOTIF_NON_PAIEMENT" ("ID") ENABLE;
  ALTER TABLE "OSE"."VOLUME_HORAIRE" ADD CONSTRAINT "VH_PERIODE_FK" FOREIGN KEY ("PERIODE_ID")
	  REFERENCES "OSE"."PERIODE" ("ID") ENABLE;
  ALTER TABLE "OSE"."VOLUME_HORAIRE" ADD CONSTRAINT "VH_TYPE_VOLUME_HORAIRE_FK" FOREIGN KEY ("TYPE_VOLUME_HORAIRE_ID")
	  REFERENCES "OSE"."TYPE_VOLUME_HORAIRE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."VOLUME_HORAIRE" ADD CONSTRAINT "VOLUMES_HORAIRES_SERVICES_FK" FOREIGN KEY ("SERVICE_ID")
	  REFERENCES "OSE"."SERVICE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."VOLUME_HORAIRE" ADD CONSTRAINT "VOLUME_HORAIRE_CONTRAT_FK" FOREIGN KEY ("CONTRAT_ID")
	  REFERENCES "OSE"."CONTRAT" ("ID") ENABLE;
  ALTER TABLE "OSE"."VOLUME_HORAIRE" ADD CONSTRAINT "VOLUME_HORAIRE_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."VOLUME_HORAIRE" ADD CONSTRAINT "VOLUME_HORAIRE_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."VOLUME_HORAIRE" ADD CONSTRAINT "VOLUME_HORAIRE_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table VOLUME_HORAIRE_ENS
--------------------------------------------------------

  ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" ADD CONSTRAINT "VHENS_ELEMENT_DISCIPLINE_FK" FOREIGN KEY ("ELEMENT_DISCIPLINE_ID")
	  REFERENCES "OSE"."ELEMENT_DISCIPLINE" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" ADD CONSTRAINT "VHENS_TYPE_INTERVENTION_FK" FOREIGN KEY ("TYPE_INTERVENTION_ID")
	  REFERENCES "OSE"."TYPE_INTERVENTION" ("ID") ON DELETE CASCADE ENABLE;
  ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" ADD CONSTRAINT "VOLUME_HORAIRE_ENS_ANNEE_FK" FOREIGN KEY ("ANNEE_ID")
	  REFERENCES "OSE"."ANNEE" ("ID") ENABLE;
  ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" ADD CONSTRAINT "VOLUME_HORAIRE_ENS_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" ADD CONSTRAINT "VOLUME_HORAIRE_ENS_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" ADD CONSTRAINT "VOLUME_HORAIRE_ENS_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	  REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE;
  ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" ADD CONSTRAINT "VOLUME_HORAIRE_ENS_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	  REFERENCES "OSE"."SOURCE" ("ID") ON DELETE CASCADE ENABLE;
--------------------------------------------------------
--  DDL for Trigger AFFECTATION_RECHERCHE_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."AFFECTATION_RECHERCHE_CK" 
BEFORE INSERT OR UPDATE ON affectation_recherche
FOR EACH ROW
DECLARE
  pragma autonomous_transaction;
  rows_found integer;
BEGIN

  if :NEW.histo_destruction IS NOT NULL THEN RETURN; END IF; -- pas de check si c'est pour une historicisation
  
  select 
    count(*) into rows_found
  from
    ose.affectation_recherche
  where
    intervenant_id = :new.intervenant_id
    AND structure_id = :new.structure_id
    AND histo_destruction is null
    AND id <> :NEW.id;
  
  if rows_found > 0 THEN
    raise_application_error(-20101, 'Un enseignant (id=' || :NEW.intervenant_id || ') ne peut pas avoir plusieurs affectations de recherche pour une même structure');
  END IF;

END;
/
ALTER TRIGGER "OSE"."AFFECTATION_RECHERCHE_CK" ENABLE;
--------------------------------------------------------
--  DDL for Trigger ELEMENT_PEDAGOGIQUE_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."ELEMENT_PEDAGOGIQUE_CK" 
BEFORE INSERT OR UPDATE ON element_pedagogique 
FOR EACH ROW
BEGIN

  IF :NEW.source_id <> OSE_IMPORT.GET_SOURCE_ID('OSE') THEN RETURN; END IF; -- impossible de checker car l'UPD par import se fait champ par champ...
  
  IF :NEW.fi = 0 AND :NEW.fc = 0 AND :NEW.fa = 0 THEN
    raise_application_error(-20101, 'Un enseignement doit obligatoirement être au moins en FI, FC ou FA');
  END IF;

  IF 1 <> ROUND(:NEW.taux_fi + :NEW.taux_fc + :NEW.taux_fa, 2) THEN
    raise_application_error(-20101, 'Le total des taux FI, FC et FA n''est pas égal à 1');
  END IF;

END;
/
ALTER TRIGGER "OSE"."ELEMENT_PEDAGOGIQUE_CK" ENABLE;
--------------------------------------------------------
--  DDL for Trigger ELEMENT_PORTEUR_PORTE_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."ELEMENT_PORTEUR_PORTE_CK" 
BEFORE INSERT OR UPDATE ON element_porteur_porte 
FOR EACH ROW
DECLARE 
  pragma autonomous_transaction;
  num_rows integer;
BEGIN
  
  SELECT count(*) INTO num_rows FROM element_porteur_porte WHERE histo_destruction IS NULL AND element_porteur_id = :NEW.element_porte_id;
  IF num_rows = 1 THEN 
    raise_application_error(-20101, 'Un élément porteur ne peut pas être également porté');
  END IF;

  SELECT count(*) INTO num_rows FROM element_porteur_porte WHERE histo_destruction IS NULL AND element_porte_id = :NEW.element_porteur_id;
  IF num_rows = 1 THEN 
    raise_application_error(-20101, 'Un élément porté ne peut pas être également porteur');
  END IF;
END;

/
ALTER TRIGGER "OSE"."ELEMENT_PORTEUR_PORTE_CK" ENABLE;
--------------------------------------------------------
--  DDL for Trigger ELEMENT_TAUX_REGIMES_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."ELEMENT_TAUX_REGIMES_CK" 
BEFORE INSERT OR UPDATE ON element_taux_regimes 
FOR EACH ROW
BEGIN

  IF 1 <> ROUND(:NEW.taux_fi + :NEW.taux_fc + :NEW.taux_fa, 2) THEN
    raise_application_error(-20101, 'Le total des taux FI, FC et FA n''est pas égal à 1');
  END IF;

END;
/
ALTER TRIGGER "OSE"."ELEMENT_TAUX_REGIMES_CK" ENABLE;
--------------------------------------------------------
--  DDL for Trigger F_CONTRAT
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."F_CONTRAT" 
AFTER UPDATE OR DELETE ON contrat
FOR EACH ROW
BEGIN
  FOR p IN (

    SELECT
      vh.id volume_horaire_id
    FROM
      volume_horaire vh 
    WHERE
      1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      AND (vh.contrat_id = :OLD.id OR vh.contrat_id = :NEW.id)

  ) LOOP

    OSE_FORMULE.IDT_MAJ_VOLUME_HORAIRE( p.volume_horaire_id );

  END LOOP;
  OSE_FORMULE.RUN_SIGNAL;
END;
/
ALTER TRIGGER "OSE"."F_CONTRAT" ENABLE;
--------------------------------------------------------
--  DDL for Trigger F_ELEMENT_MODULATEUR
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."F_ELEMENT_MODULATEUR" 
AFTER INSERT OR UPDATE OR DELETE ON element_modulateur
FOR EACH ROW
BEGIN
  FOR p IN (
  
    SELECT
      id service_id
    FROM
      service s
    WHERE
      1 = OSE_DIVERS.COMPRISE_ENTRE( s.histo_creation, s.histo_destruction )
      AND (s.element_pedagogique_id = :OLD.element_id OR s.element_pedagogique_id = :NEW.element_id)
      
  ) LOOP
  
    OSE_FORMULE.IDT_MAJ_SERVICE( p.service_id );
    
  END LOOP;
  OSE_FORMULE.RUN_SIGNAL;
END;
/
ALTER TRIGGER "OSE"."F_ELEMENT_MODULATEUR" ENABLE;
--------------------------------------------------------
--  DDL for Trigger F_ELEMENT_PEDAGOGIQUE
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."F_ELEMENT_PEDAGOGIQUE" 
AFTER UPDATE ON element_pedagogique
FOR EACH ROW
BEGIN

  FOR p IN (

    SELECT
      s.id service_id
    FROM
      service s
    WHERE
      (s.element_pedagogique_id = :NEW.id OR s.element_pedagogique_id = :OLD.id)
      AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )

  ) LOOP

    OSE_FORMULE.IDT_MAJ_SERVICE( p.service_id );

  END LOOP;
  OSE_FORMULE.RUN_SIGNAL;
END;
/
ALTER TRIGGER "OSE"."F_ELEMENT_PEDAGOGIQUE" ENABLE;
--------------------------------------------------------
--  DDL for Trigger F_INTERVENANT
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."F_INTERVENANT" 
AFTER UPDATE OR DELETE ON intervenant
FOR EACH ROW
BEGIN
  IF :OLD.id IS NOT NULL THEN
    OSE_FORMULE.IDT_MAJ_REFERENTIEL( :OLD.id, OSE_PARAMETRE.GET_ANNEE );
  END IF;
  IF :NEW.id IS NOT NULL THEN
    OSE_FORMULE.IDT_MAJ_REFERENTIEL( :NEW.id, OSE_PARAMETRE.GET_ANNEE );
  END IF;
  
  FOR p IN (
  
    SELECT
      s.id  service_id,
      vh.id volume_horaire_id
    FROM
      service s
      LEFT JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
    WHERE
      1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
      AND (s.intervenant_id = :NEW.id OR s.intervenant_id = :OLD.id)
  
  ) LOOP
  
    OSE_FORMULE.IDT_MAJ_SERVICE( p.service_id );  
    IF p.volume_horaire_id IS NOT NULL THEN
      OSE_FORMULE.IDT_MAJ_VOLUME_HORAIRE( p.volume_horaire_id );
    END IF;

  END LOOP;
  OSE_FORMULE.RUN_SIGNAL;
END;
/
ALTER TRIGGER "OSE"."F_INTERVENANT" ENABLE;
--------------------------------------------------------
--  DDL for Trigger F_MODIF_SERVICE_DU
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."F_MODIF_SERVICE_DU" 
AFTER INSERT OR UPDATE OR DELETE ON modification_service_du
FOR EACH ROW
BEGIN
  IF DELETING OR UPDATING THEN
    OSE_FORMULE.IDT_MAJ_REFERENTIEL( :OLD.intervenant_id, :OLD.annee_id );
  END IF;
  IF INSERTING OR UPDATING THEN
    OSE_FORMULE.IDT_MAJ_REFERENTIEL( :NEW.intervenant_id, :NEW.annee_id );
  END IF;
  OSE_FORMULE.RUN_SIGNAL;
END;
/
ALTER TRIGGER "OSE"."F_MODIF_SERVICE_DU" ENABLE;
--------------------------------------------------------
--  DDL for Trigger F_MODULATEUR
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."F_MODULATEUR" 
AFTER UPDATE OR DELETE ON modulateur
FOR EACH ROW
BEGIN
  FOR p IN (

    SELECT
      s.id service_id
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

    OSE_FORMULE.IDT_MAJ_SERVICE( p.service_id );

  END LOOP;
  OSE_FORMULE.RUN_SIGNAL;
END;
/
ALTER TRIGGER "OSE"."F_MODULATEUR" ENABLE;
--------------------------------------------------------
--  DDL for Trigger F_MOTIF_MODIFICATION_SERVICE
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."F_MOTIF_MODIFICATION_SERVICE" 
AFTER UPDATE OR DELETE ON motif_modification_service
FOR EACH ROW
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
  
    OSE_FORMULE.IDT_MAJ_REFERENTIEL( p.intervenant_id, p.annee_id );
  
  END LOOP;
  OSE_FORMULE.RUN_SIGNAL;
END;
/
ALTER TRIGGER "OSE"."F_MOTIF_MODIFICATION_SERVICE" ENABLE;
--------------------------------------------------------
--  DDL for Trigger FORMULE_REFERENTIEL_CALC
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."FORMULE_REFERENTIEL_CALC" 
BEFORE INSERT OR UPDATE ON FORMULE_REFERENTIEL
FOR EACH ROW
BEGIN
  -- calculs
  :NEW.service_du_modifie         := :NEW.service_du         + :NEW.service_du_modification;
  RETURN;
END;
/
ALTER TRIGGER "OSE"."FORMULE_REFERENTIEL_CALC" ENABLE;
--------------------------------------------------------
--  DDL for Trigger F_SERVICE
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."F_SERVICE" 
AFTER INSERT OR UPDATE ON service
FOR EACH ROW
BEGIN

  IF :OLD.id IS NOT NULL THEN
    OSE_FORMULE.IDT_MAJ_SERVICE( :OLD.id );
  END IF;
  IF :NEW.id IS NOT NULL THEN
    OSE_FORMULE.IDT_MAJ_SERVICE( :NEW.id );
  END IF;
  FOR p IN (
  
    SELECT
      vh.id volume_horaire_id
    FROM
      volume_horaire vh
    WHERE
      (vh.service_id = :NEW.id OR vh.service_id = :OLD.id)
      --AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction ) -- pas d'historique car des VH peuvent être restaurés!!
      
  ) LOOP
  
    OSE_FORMULE.IDT_MAJ_VOLUME_HORAIRE( p.volume_horaire_id );
    
  END LOOP;
  OSE_FORMULE.RUN_SIGNAL;
END;
/
ALTER TRIGGER "OSE"."F_SERVICE" ENABLE;
--------------------------------------------------------
--  DDL for Trigger F_SERVICE_DU
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."F_SERVICE_DU" 
AFTER INSERT OR UPDATE OR DELETE ON service_du
FOR EACH ROW
BEGIN
  IF DELETING OR UPDATING THEN
    OSE_FORMULE.IDT_MAJ_REFERENTIEL( :OLD.intervenant_id, :OLD.annee_id );
  END IF;
  IF INSERTING OR UPDATING THEN
    OSE_FORMULE.IDT_MAJ_REFERENTIEL( :NEW.intervenant_id, :NEW.annee_id );
  END IF;
  OSE_FORMULE.RUN_SIGNAL;
END;
/
ALTER TRIGGER "OSE"."F_SERVICE_DU" ENABLE;
--------------------------------------------------------
--  DDL for Trigger F_SERVICE_REFERENTIEL
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."F_SERVICE_REFERENTIEL" 
AFTER INSERT OR UPDATE OR DELETE ON service_referentiel
FOR EACH ROW
BEGIN
  IF DELETING OR UPDATING THEN
    OSE_FORMULE.IDT_MAJ_REFERENTIEL( :OLD.intervenant_id, :OLD.annee_id );
  END IF;
  IF INSERTING OR UPDATING THEN
    OSE_FORMULE.IDT_MAJ_REFERENTIEL( :NEW.intervenant_id, :NEW.annee_id );
  END IF;
  OSE_FORMULE.RUN_SIGNAL;
END;
/
ALTER TRIGGER "OSE"."F_SERVICE_REFERENTIEL" ENABLE;
--------------------------------------------------------
--  DDL for Trigger F_TYPE_INTERVENTION
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."F_TYPE_INTERVENTION" 
AFTER UPDATE ON type_intervention
FOR EACH ROW
BEGIN
  FOR p IN (
  
    SELECT
      vh.id volume_horaire_id
    FROM
      volume_horaire vh
    WHERE
      1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      AND (vh.type_intervention_id = :NEW.id OR vh.type_intervention_id = :OLD.id)
  
  ) LOOP
  
    OSE_FORMULE.IDT_MAJ_VOLUME_HORAIRE( p.volume_horaire_id );
  
  END LOOP;
  OSE_FORMULE.RUN_SIGNAL;
END;
/
ALTER TRIGGER "OSE"."F_TYPE_INTERVENTION" ENABLE;
--------------------------------------------------------
--  DDL for Trigger F_VALIDATION
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION" 
AFTER UPDATE ON validation
FOR EACH ROW
BEGIN

  FOR p IN ( -- validations de volume horaire

    SELECT
      vh.id volume_horaire_id
    FROM
      validation_vol_horaire vvh
      JOIN volume_horaire vh ON vh.id = vvh.volume_horaire_id AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
    WHERE
      (vvh.validation_id = :OLD.ID OR vvh.validation_id = :NEW.id)

  ) LOOP

    OSE_FORMULE.IDT_MAJ_VOLUME_HORAIRE( p.volume_horaire_id );

  END LOOP;

  FOR p IN ( -- validations de contrat

    SELECT
      vh.id volume_horaire_id
    FROM
      contrat c
      JOIN volume_horaire vh ON vh.contrat_id = c.id AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
    WHERE
      (c.validation_id = :OLD.ID OR c.validation_id = :NEW.id)

  ) LOOP

    OSE_FORMULE.IDT_MAJ_VOLUME_HORAIRE( p.volume_horaire_id );

  END LOOP;

  OSE_FORMULE.RUN_SIGNAL;
END;
/
ALTER TRIGGER "OSE"."F_VALIDATION" ENABLE;
--------------------------------------------------------
--  DDL for Trigger F_VALIDATION_VOL_HORAIRE
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION_VOL_HORAIRE" 
AFTER INSERT OR UPDATE OR DELETE ON validation_vol_horaire
FOR EACH ROW
BEGIN
  IF INSERTING OR UPDATING THEN
    OSE_FORMULE.IDT_MAJ_VOLUME_HORAIRE( :NEW.volume_horaire_id );
  END IF;
  IF DELETING OR UPDATING THEN
    OSE_FORMULE.IDT_MAJ_VOLUME_HORAIRE( :OLD.volume_horaire_id );
  END IF;
  OSE_FORMULE.RUN_SIGNAL;
END;
/
ALTER TRIGGER "OSE"."F_VALIDATION_VOL_HORAIRE" ENABLE;
--------------------------------------------------------
--  DDL for Trigger F_VOLUME_HORAIRE
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."F_VOLUME_HORAIRE" 
AFTER INSERT OR UPDATE ON volume_horaire
FOR EACH ROW
BEGIN
  IF UPDATING THEN
    OSE_FORMULE.IDT_MAJ_VOLUME_HORAIRE( :OLD.id );
  END IF;
  IF INSERTING OR UPDATING THEN
    OSE_FORMULE.IDT_MAJ_VOLUME_HORAIRE( :NEW.id );
  END IF;
  OSE_FORMULE.RUN_SIGNAL;
END;
/
ALTER TRIGGER "OSE"."F_VOLUME_HORAIRE" ENABLE;
--------------------------------------------------------
--  DDL for Trigger INTERVENANT_EXTERIEUR_SRC_MAJ
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."INTERVENANT_EXTERIEUR_SRC_MAJ" 
BEFORE INSERT ON OSE.intervenant_exterieur
FOR EACH ROW
DECLARE
  interv INTERVENANT%ROWTYPE;
BEGIN
  SELECT 
    * INTO interv
  FROM
    intervenant i
  WHERE
    i.id = :NEW.id;
    
  IF :NEW.source_id IS NULL THEN :NEW.source_id := interv.source_id; END IF;
  IF :NEW.source_code IS NULL THEN :NEW.source_code := interv.source_code; END IF;
  IF :NEW.histo_createur_id IS NULL THEN :NEW.histo_createur_id := interv.histo_createur_id; END IF;
  IF :NEW.histo_creation IS NULL THEN :NEW.histo_creation := SYSDATE; END IF;
  IF :NEW.histo_modificateur_id IS NULL THEN :NEW.histo_modificateur_id := interv.histo_modificateur_id; END IF;
  IF :NEW.histo_modification IS NULL THEN :NEW.histo_modification := SYSDATE; END IF;
  RETURN;
END;

/
ALTER TRIGGER "OSE"."INTERVENANT_EXTERIEUR_SRC_MAJ" ENABLE;
--------------------------------------------------------
--  DDL for Trigger INTERVENANT_PERMANENT_SRC_MAJ
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."INTERVENANT_PERMANENT_SRC_MAJ" 
BEFORE INSERT ON OSE.intervenant_permanent
FOR EACH ROW
DECLARE
  interv INTERVENANT%ROWTYPE;
BEGIN
  SELECT 
    * INTO interv
  FROM
    intervenant i
  WHERE
    i.id = :NEW.id;
    
  IF :NEW.source_id IS NULL THEN :NEW.source_id := interv.source_id; END IF;
  IF :NEW.source_code IS NULL THEN :NEW.source_code := interv.source_code; END IF;
  IF :NEW.histo_createur_id IS NULL THEN :NEW.histo_createur_id := interv.histo_createur_id; END IF;
  IF :NEW.histo_creation IS NULL THEN :NEW.histo_creation := SYSDATE; END IF;
  IF :NEW.histo_modificateur_id IS NULL THEN :NEW.histo_modificateur_id := interv.histo_modificateur_id; END IF;
  IF :NEW.histo_modification IS NULL THEN :NEW.histo_modification := SYSDATE; END IF;
  RETURN;
END;

/
ALTER TRIGGER "OSE"."INTERVENANT_PERMANENT_SRC_MAJ" ENABLE;
--------------------------------------------------------
--  DDL for Trigger INTERVENANT_STATUT_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."INTERVENANT_STATUT_CK" 
  BEFORE INSERT OR UPDATE ON OSE.intervenant
  FOR EACH ROW
DECLARE
  statut_type_intervenant INTEGER;
BEGIN
  IF :NEW.statut_id IS NOT NULL THEN
    SELECT si.type_intervenant_id
    INTO statut_type_intervenant
    FROM statut_intervenant si
    WHERE si.id      = :NEW.statut_id;
    
    IF :OLD.type_id = :NEW.type_id AND statut_type_intervenant <> :NEW.type_id THEN
      :NEW.type_id := statut_type_intervenant;
    END IF;
    
    IF :NEW.type_id <> statut_type_intervenant THEN
      raise_application_error(-20101, 'Ce statut n''est pas appliquable à cet intervenant.');
    END IF;
  END IF;
END;
/
ALTER TRIGGER "OSE"."INTERVENANT_STATUT_CK" ENABLE;
--------------------------------------------------------
--  DDL for Trigger INTERVENANT_STATUT_MAJ
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."INTERVENANT_STATUT_MAJ" AFTER
  INSERT OR
  UPDATE ON OSE.intervenant FOR EACH ROW DECLARE heures_service_statutaire FLOAT;
  found  INTEGER;
  action VARCHAR2(15);
  BEGIN

    IF :OLD.statut_id = :NEW.statut_id THEN
      RETURN;
    END IF; -- ne rien faire si rien ne change
    IF :OLD.histo_destruction IS NULL AND :NEW.histo_destruction IS NOT NULL THEN
      action                  := 'delete';
    ELSIF :OLD.statut_id      IS NOT NULL AND :NEW.statut_id IS NOT NULL THEN
      action                  := 'update';
    ELSIF :OLD.statut_id      IS NULL AND :NEW.statut_id IS NOT NULL THEN
      action                  := 'insert';
    END IF;
    IF action <> 'delete' THEN
      SELECT si.service_statutaire
      INTO heures_service_statutaire
      FROM ose.statut_intervenant si
      WHERE si.histo_destruction  IS NULL
      AND si.id                    = :NEW.statut_id;
      IF heures_service_statutaire = 0 THEN
        IF action                  = 'update' THEN
          action                  := 'delete';
        ELSIF action               = 'insert' THEN
          action                  := 'no';
        END IF;
      END IF;
      SELECT COUNT(*)
      INTO found
      FROM service_du
      WHERE annee_id         = OSE_PARAMETRE.GET_ANNEE()
      AND histo_destruction IS NULL
      AND intervenant_id     = :NEW.id;
      IF 0                   = found THEN
        IF action            = 'update' THEN
          action            := 'insert';
        END IF;
      ELSIF action = 'insert' THEN
        action    := 'update';
      END IF;
    END IF;
    CASE action
    WHEN 'insert' THEN
      INSERT
      INTO OSE.SERVICE_DU
        (
          id,
          intervenant_id,
          annee_id,
          heures,
          histo_creation,
          histo_createur_id,
          histo_modification,
          histo_modificateur_id
        )
        VALUES
        (
          service_du_id_seq.nextval,
          :NEW.id,
          OSE_PARAMETRE.GET_ANNEE(),
          heures_service_statutaire,
          SYSDATE,
          OSE_PARAMETRE.GET_OSE_USER(),
          SYSDATE,
          OSE_PARAMETRE.GET_OSE_USER()
        );
    WHEN 'update' THEN
      UPDATE OSE.service_du
      SET heures              = heures_service_statutaire,
        histo_modification    = SYSDATE,
        histo_modificateur_id = :NEW.histo_modificateur_id
      WHERE intervenant_id    = :OLD.ID
      AND annee_id            = OSE_PARAMETRE.GET_ANNEE();
    WHEN 'delete' THEN
      UPDATE OSE.service_du
      SET histo_destruction  = SYSDATE,
        histo_destructeur_id = OSE_PARAMETRE.GET_OSE_USER()
      WHERE intervenant_id   = :OLD.ID
      AND annee_id           = OSE_PARAMETRE.GET_ANNEE();
    ELSE
      RETURN;
    END CASE;
  END;
/
ALTER TRIGGER "OSE"."INTERVENANT_STATUT_MAJ" ENABLE;
--------------------------------------------------------
--  DDL for Trigger SERVICE_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."SERVICE_CK" 
BEFORE INSERT OR UPDATE ON service
FOR EACH ROW
DECLARE 
  etablissement integer;
  structure_ens_id NUMERIC;  
BEGIN
  
  etablissement := OSE_PARAMETRE.GET_ETABLISSEMENT();
  
  IF :NEW.etablissement_id = etablissement AND :NEW.element_pedagogique_id IS NULL THEN
    raise_application_error(-20101, 'Un enseignement doit obligatoirement être renseigné si le service est réalisé en interne.');
  END IF;

  IF OSE_DIVERS.INTERVENANT_HAS_PRIVILEGE(:NEW.intervenant_id, 'saisie_service') = 0 THEN
    raise_application_error(-20101, 'Il est impossible de saisir des services pour cet intervenant.');
  END IF;

  IF :NEW.etablissement_id <> etablissement AND OSE_DIVERS.INTERVENANT_HAS_PRIVILEGE(:NEW.intervenant_id, 'saisie_service_exterieur') = 0 THEN
    raise_application_error(-20101, 'Les intervenants vacataires n''ont pas la possibilité de renseigner des enseignements pris à l''extérieur.');
  END IF;

  IF :NEW.element_pedagogique_id <> :OLD.element_pedagogique_id THEN
    SELECT structure_id INTO structure_ens_id FROM element_pedagogique WHERE id = :NEW.element_pedagogique_id;
    :NEW.structure_ens_id := structure_ens_id;
  END IF;

  --IF :OLD.id IS NOT NULL AND ( :NEW.etablissement_id <> :OLD.etablissement_id OR :NEW.element_pedagogique_id <> :OLD.element_pedagogique_id ) THEN
    --UPDATE volume_horaire SET histo_destruction = SYSDATE, histo_destructeur_id = :NEW.histo_modificateur_id WHERE service_id = :NEW.id;
  --END IF;

END;
/
ALTER TRIGGER "OSE"."SERVICE_CK" ENABLE;
--------------------------------------------------------
--  DDL for Trigger SERVICE_DU_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."SERVICE_DU_CK" 
BEFORE INSERT OR UPDATE ON service_du
FOR EACH ROW
DECLARE
  pragma autonomous_transaction;
  rows_found integer;
BEGIN
return;
  if :NEW.histo_destruction IS NOT NULL THEN RETURN; END IF; -- pas de check si c'est pour une historicisation
  
  select count(*) into rows_found from ose.service_du where intervenant_id = :new.intervenant_id AND annee_id = :new.annee_id AND histo_destruction is null;
  
  if rows_found > 0 THEN
    raise_application_error(-20101, 'Un enseignants ne peut avoir plusieurs services dûs la même année');
  END IF;

END;

/
ALTER TRIGGER "OSE"."SERVICE_DU_CK" ENABLE;
--------------------------------------------------------
--  DDL for Trigger SERVICE_HISTO_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."SERVICE_HISTO_CK" 
BEFORE UPDATE ON service
FOR EACH ROW
DECLARE
  has_validation integer;
BEGIN
  SELECT COUNT(*) INTO has_validation
  FROM
    VALIDATION_VOL_HORAIRE vvh
    JOIN validation v ON v.id = VVH.VALIDATION_ID
    JOIN volume_horaire vh ON vh.id = VVH.VOLUME_HORAIRE_ID
  WHERE
    V.HISTO_DESTRUCTION IS NULL
    AND vh.service_ID = :NEW.ID;

  IF 0 <> has_validation THEN
    raise_application_error(-20101, 'Il est impossible de modifier ou supprimer un service dont des heures ont déjà été validées.');
  END IF;

  -- En cas de mise en historique d'un service
  IF :NEW.histo_destruction IS NOT NULL AND :OLD.histo_destruction IS NULL THEN
    UPDATE VOLUME_HORAIRE SET histo_destruction = :NEW.histo_destruction, histo_destructeur_id = :NEW.histo_destructeur_id WHERE service_id = :NEW.id;
  END IF;
  -- En cas de restauration d'un service, on ne restaure pas les historiques de volumes horaires pour ne pas récussiter d'éventuels volume horaires indésirables car préalablement supprimés
    
END;
/
ALTER TRIGGER "OSE"."SERVICE_HISTO_CK" ENABLE;
--------------------------------------------------------
--  DDL for Trigger TYPE_INTERVENTION_STRUCTURE_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."TYPE_INTERVENTION_STRUCTURE_CK" 
BEFORE INSERT OR UPDATE ON type_intervention_structure
FOR EACH ROW
DECLARE 
  structure_niveau NUMERIC;
BEGIN
  
  SELECT structure.niveau INTO structure_niveau FROM structure WHERE structure.id = :NEW.structure_id;
  
  IF structure_niveau <> 2 THEN
    raise_application_error(-20101, 'Les types d''intervention ne peuvent être associés qu''à des structures de niveau 2.');
  END IF;

END;
/
ALTER TRIGGER "OSE"."TYPE_INTERVENTION_STRUCTURE_CK" ENABLE;
--------------------------------------------------------
--  DDL for Trigger VALIDATION_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."VALIDATION_CK" 
BEFORE UPDATE OR DELETE ON validation
FOR EACH ROW
DECLARE 
  nb_validations_blindees NUMERIC;  
  pragma autonomous_transaction;
BEGIN
  
SELECT
  SUM(CASE WHEN c.id IS NOT NULL THEN 1 ELSE 0 END) INTO nb_validations_blindees
FROM
  validation_vol_horaire vvh
  JOIN volume_horaire vh ON vh.id = vvh.volume_horaire_id
  LEFT JOIN contrat c ON c.id = vh.contrat_id AND c.histo_destruction IS NULL
WHERE
  vvh.validation_id = :OLD.id;
  
  -- Si des volumes horaires ont déjà fait l'objet de contrats alors pas de dévalidation possible des heures
  IF nb_validations_blindees > 0 THEN
    raise_application_error(-20101, 'La dévalidation est impossible car des contrats ont déjà été édités sur la base de ces heures.');
  END IF;

END;
/
ALTER TRIGGER "OSE"."VALIDATION_CK" ENABLE;
--------------------------------------------------------
--  DDL for Trigger VALIDATION_VOL_HORAIRE_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."VALIDATION_VOL_HORAIRE_CK" 
BEFORE UPDATE OR DELETE ON validation_vol_horaire
FOR EACH ROW
DECLARE 
  contrat_blinde NUMERIC;  
  pragma autonomous_transaction;
BEGIN
  
SELECT
  count(*) INTO contrat_blinde
FROM
  volume_horaire vh
  JOIN contrat c ON c.id = vh.contrat_id AND 1 = ose_divers.comprise_entre( c.histo_creation, c.histo_destruction )
WHERE
  vh.id = :OLD.volume_horaire_id;
  
  -- Si des volumes horaires ont déjà fait l'objet de contrats alors pas de dévalidation possible des heures
  IF contrat_blinde = 1 THEN
    raise_application_error(-20101, 'La dévalidation est impossible car un contrat a déjà été édité sur la base de ces heures.');
  END IF;

END;
/
ALTER TRIGGER "OSE"."VALIDATION_VOL_HORAIRE_CK" ENABLE;
--------------------------------------------------------
--  DDL for Trigger VOLUME_HORAIRE_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."VOLUME_HORAIRE_CK" 
BEFORE INSERT OR UPDATE ON volume_horaire 
FOR EACH ROW 
  DECLARE
    has_validation NUMERIC;
    modified       BOOLEAN;
    intervenant_id NUMERIC;
  BEGIN
    IF :OLD.motif_non_paiement_id IS NULL AND :NEW.motif_non_paiement_id IS NOT NULL THEN
      SELECT s.intervenant_id INTO intervenant_id FROM service s WHERE s.id = :NEW.service_id;
      IF 0 = ose_divers.intervenant_has_privilege( intervenant_id, 'saisie_motif_non_paiement') THEN
        raise_application_error(-20101, 'Il est impossible d''associer un motif de non paiement à un intervenant vacataire ou BIATSS.');
      END IF;
    END IF;
    
    IF :NEW.motif_non_paiement_id IS NOT NULL AND :NEW.contrat_id IS NOT NULL THEN
      raise_application_error(-20101, 'Les heures ayant un motif de non paiement ne peuvent faire l''objet d''une contractualisation');
    END IF;

    modified := 
      NVL(:NEW.id,0) <> NVL(:OLD.id,0)
      OR NVL(:NEW.type_volume_horaire_id,0) <> NVL(:OLD.type_volume_horaire_id,0)
      OR NVL(:NEW.service_id,0) <> NVL(:OLD.service_id,0)
      OR NVL(:NEW.periode_id,0) <> NVL(:OLD.periode_id,0)
      OR NVL(:NEW.type_intervention_id,0) <> NVL(:OLD.type_intervention_id,0)
      OR NVL(:NEW.heures,0) <> NVL(:OLD.heures,0)
      OR NVL(:NEW.motif_non_paiement_id,0) <> NVL(:OLD.motif_non_paiement_id,0)
      OR NVL(:NEW.validite_debut,SYSDATE) <> NVL(:OLD.validite_debut,SYSDATE)
      OR NVL(:NEW.validite_fin,SYSDATE) <> NVL(:OLD.validite_fin,SYSDATE)
      OR NVL(:NEW.histo_creation,SYSDATE) <> NVL(:OLD.histo_creation,SYSDATE)
      OR NVL(:NEW.histo_createur_id,0) <> NVL(:OLD.histo_createur_id,0)
      OR NVL(:NEW.histo_destruction,SYSDATE) <> NVL(:OLD.histo_destruction,SYSDATE)
      OR NVL(:NEW.histo_destructeur_id,0) <> NVL(:OLD.histo_destructeur_id,0);
    
    SELECT
      COUNT(*)
    INTO
      has_validation
    FROM
      VALIDATION_VOL_HORAIRE vvh
      JOIN validation v ON v.id = VVH.VALIDATION_ID
    WHERE
      V.HISTO_DESTRUCTION IS NULL
      AND vvh.VOLUME_HORAIRE_ID  = :NEW.ID;
      
    IF modified AND 0 <> has_validation THEN
      raise_application_error(-20101, 'Il est impossible de modifier des heures déjà validées.');
    END IF;
  END;
/
ALTER TRIGGER "OSE"."VOLUME_HORAIRE_CK" ENABLE;
--------------------------------------------------------
--  DDL for Trigger VOLUME_HORAIRE_DEL_CK
--------------------------------------------------------

  CREATE OR REPLACE TRIGGER "OSE"."VOLUME_HORAIRE_DEL_CK" BEFORE
  DELETE ON volume_horaire FOR EACH ROW DECLARE has_validation INTEGER;
  pragma autonomous_transaction;
  BEGIN
    SELECT COUNT(*)
    INTO has_validation
    FROM VALIDATION_VOL_HORAIRE vvh
    JOIN validation v
    ON v.id                    = VVH.VALIDATION_ID
    WHERE V.HISTO_DESTRUCTION IS NULL
    AND vvh.VOLUME_HORAIRE_ID  = :OLD.ID;
    IF 0                      <> has_validation THEN
      raise_application_error(-20101, 'Il est impossible de supprimer des heures déjà validées.');
    END IF;
  END;
  
/
ALTER TRIGGER "OSE"."VOLUME_HORAIRE_DEL_CK" ENABLE;
--------------------------------------------------------
--  DDL for Function SQUIRREL_GET_ERROR_OFFSET
--------------------------------------------------------

  CREATE OR REPLACE FUNCTION "OSE"."SQUIRREL_GET_ERROR_OFFSET" (query IN varchar2) return number authid current_user is      l_theCursor     integer default dbms_sql.open_cursor;      l_status        integer; begin          begin          dbms_sql.parse(  l_theCursor, query, dbms_sql.native );          exception                  when others then l_status := dbms_sql.last_error_position;          end;          dbms_sql.close_cursor( l_theCursor );          return l_status; end; 

/
--------------------------------------------------------
--  DDL for Package OSE_DIVERS
--------------------------------------------------------

  CREATE OR REPLACE PACKAGE "OSE"."OSE_DIVERS" AS 

  FUNCTION INTERVENANT_HAS_PRIVILEGE( intervenant_id NUMERIC, privilege_name VARCHAR2 ) RETURN NUMERIC;

  FUNCTION GET_TYPE_MODULATEUR_IDS( STRUCTURE_ID NUMERIC, DATE_OBS DATE DEFAULT SYSDATE ) RETURN types_modulateurs;

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

END OSE_DIVERS;

/
--------------------------------------------------------
--  DDL for Package OSE_FORMULE
--------------------------------------------------------

  CREATE OR REPLACE PACKAGE "OSE"."OSE_FORMULE" AS 

  FUNCTION  GET_DATE_OBS RETURN DATE;
  FUNCTION  SET_DATE_OBS( DATE_OBS DATE DEFAULT NULL ) RETURN DATE;

  FUNCTION  GET_DEF_REFERENTIEL( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC ) RETURN formule_referentiel%rowtype;
  FUNCTION      GET_REFERENTIEL( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC ) RETURN formule_referentiel%rowtype;
  FUNCTION     CALC_REFERENTIEL( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC ) RETURN formule_referentiel%rowtype;
  PROCEDURE IDT_MAJ_REFERENTIEL( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC );
  PROCEDURE     MAJ_REFERENTIEL( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC );
  PROCEDURE MAJ_ALL_REFERENTIEL;
  PROCEDURE MAJ_IDT_REFERENTIEL;

  FUNCTION  GET_DEF_SERVICE( SERVICE_ID NUMERIC ) RETURN formule_service%rowtype;
  FUNCTION      GET_SERVICE( SERVICE_ID NUMERIC ) RETURN formule_service%rowtype;
  FUNCTION     CALC_SERVICE( SERVICE_ID NUMERIC ) RETURN formule_service%rowtype;
  PROCEDURE IDT_MAJ_SERVICE( SERVICE_ID NUMERIC );
  PROCEDURE     MAJ_SERVICE( SERVICE_ID NUMERIC );
  PROCEDURE MAJ_ALL_SERVICE;
  PROCEDURE MAJ_IDT_SERVICE;

  FUNCTION  GET_DEF_VOLUME_HORAIRE( VOLUME_HORAIRE_ID NUMERIC ) RETURN formule_volume_horaire%rowtype;
  FUNCTION      GET_VOLUME_HORAIRE( VOLUME_HORAIRE_ID NUMERIC ) RETURN formule_volume_horaire%rowtype;
  FUNCTION     CALC_VOLUME_HORAIRE( VOLUME_HORAIRE_ID NUMERIC ) RETURN formule_volume_horaire%rowtype;
  PROCEDURE IDT_MAJ_VOLUME_HORAIRE( VOLUME_HORAIRE_ID NUMERIC );
  PROCEDURE     MAJ_VOLUME_HORAIRE( VOLUME_HORAIRE_ID NUMERIC );
  PROCEDURE MAJ_ALL_VOLUME_HORAIRE;
  PROCEDURE MAJ_IDT_VOLUME_HORAIRE;

  PROCEDURE MAJ_ALL_IDT; -- mise à jour de tous les items identifiés

  PROCEDURE REGISTER_SIGNAL;
  
  PROCEDURE UNREGISTER_SIGNAL;
  
  PROCEDURE RUN_SIGNAL;

END OSE_FORMULE;

/
--------------------------------------------------------
--  DDL for Package OSE_IMPORT
--------------------------------------------------------

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
  PROCEDURE SYNC_ELEMENT_PORTEUR_PORTE;
  PROCEDURE SYNC_ELEMENT_PEDAGOGIQUE;
  PROCEDURE SYNC_ELEMENT_DISCIPLINE;
  PROCEDURE SYNC_DISCIPLINE;
  PROCEDURE SYNC_CORPS;
  PROCEDURE SYNC_CHEMIN_PEDAGOGIQUE;
  PROCEDURE SYNC_AFFECTATION_RECHERCHE;
  PROCEDURE SYNC_ADRESSE_STRUCTURE;
  PROCEDURE SYNC_ADRESSE_INTERVENANT;
  PROCEDURE SYNC_TYPE_INTERVENTION_EP;
  PROCEDURE SYNC_TABLES;
  PROCEDURE SYNCHRONISATION;
  
  FUNCTION IN_COLUMN_LIST( VALEUR VARCHAR2, CHAMPS CLOB ) RETURN NUMERIC;

  -- AUTOMATIC GENERATION --

  PROCEDURE MAJ_ELEMENT_PEDAGOGIQUE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_TYPE_INTERVENTION_EP(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_DISCIPLINE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ELEMENT_DISCIPLINE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ETAPE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ETABLISSEMENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_CHEMIN_PEDAGOGIQUE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ADRESSE_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ROLE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_CORPS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_INTERVENANT_PERMANENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_INTERVENANT_EXTERIEUR(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ELEMENT_PORTEUR_PORTE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_AFFECTATION_RECHERCHE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_VOLUME_HORAIRE_ENS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ADRESSE_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_PERSONNEL(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_TYPE_FORMATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_GROUPE_TYPE_FORMATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');

  -- END OF AUTOMATIC GENERATION --
END ose_import;

/
--------------------------------------------------------
--  DDL for Package OSE_PARAMETRE
--------------------------------------------------------

  CREATE OR REPLACE PACKAGE "OSE"."OSE_PARAMETRE" AS 

  function get_etablissement return Numeric;
  function get_annee return Numeric;
  function get_ose_user return Numeric;
  function get_drh_structure_id return Numeric;
  function get_date_fin_saisie_permanents RETURN DATE;

END OSE_PARAMETRE;

/
--------------------------------------------------------
--  DDL for Package OSE_TEST
--------------------------------------------------------

  CREATE OR REPLACE PACKAGE "OSE"."OSE_TEST" AS 

  -- SET SERVEROUTPUT ON

  PROCEDURE SHOW_SUCCES;

  PROCEDURE HIDE_SUCCES;

  PROCEDURE ECHO( MSG CLOB );

  PROCEDURE INIT;

  PROCEDURE SHOW_STATS;

  PROCEDURE DEBUT( TEST_NAME CLOB );
  
  PROCEDURE FIN;

  PROCEDURE ASSERT( condition BOOLEAN, MSG CLOB );
  
  PROCEDURE DELETE_TEST_DATA;

  FUNCTION GET_USER RETURN NUMERIC;

  FUNCTION GET_SOURCE RETURN NUMERIC;


  FUNCTION GET_CIVILITE( libelle_court VARCHAR2 DEFAULT NULL ) RETURN civilite%rowtype;

  FUNCTION GET_TYPE_INTERVENANT( code VARCHAR2 DEFAULT NULL ) RETURN type_intervenant%rowtype;

  FUNCTION GET_TYPE_INTERVENANT_BY_ID( id NUMERIC ) RETURN type_intervenant%rowtype;

  FUNCTION GET_STATUT_INTERVENANT( source_code VARCHAR2 DEFAULT NULL ) RETURN statut_intervenant%rowtype;
  
  FUNCTION GET_STATUT_INTERVENANT_BY_ID( id NUMERIC ) RETURN statut_intervenant%rowtype;

  FUNCTION GET_TYPE_STRUCTURE( code VARCHAR2 DEFAULT NULL ) RETURN type_structure%rowtype;

  FUNCTION GET_STRUCTURE( source_code VARCHAR2 DEFAULT NULL ) RETURN structure%rowtype;
  
  FUNCTION GET_STRUCTURE_BY_ID( id NUMERIC ) RETURN structure%rowtype;
  
  FUNCTION GET_STRUCTURE_ENS_BY_NIVEAU( niveau NUMERIC ) RETURN structure%rowtype;

  FUNCTION GET_STRUCTURE_UNIV RETURN "STRUCTURE"%rowtype;

  FUNCTION ADD_STRUCTURE(
    libelle_long  VARCHAR2,
    libelle_court VARCHAR2,
    parente_id    NUMERIC,
    type_id       NUMERIC,
    source_code   VARCHAR2
  ) RETURN NUMERIC;

  FUNCTION GET_INTERVENANT( source_code VARCHAR2 DEFAULT NULL ) RETURN intervenant%rowtype;

  FUNCTION GET_INTERVENANT_BY_ID( id NUMERIC DEFAULT NULL ) RETURN intervenant%rowtype;

  FUNCTION GET_INTERVENANT_BY_STATUT( statut_id NUMERIC ) RETURN intervenant%rowtype;

  FUNCTION GET_INTERVENANT_BY_TYPE( type_id NUMERIC ) RETURN intervenant%rowtype;

  FUNCTION ADD_INTERVENANT(
    civilite_id     NUMERIC,
    nom_usuel       VARCHAR2,
    prenom          VARCHAR2,
    date_naissance  DATE,
    email           VARCHAR2,
    statut_id       NUMERIC,
    structure_id    NUMERIC,
    source_code     VARCHAR2
  ) RETURN NUMERIC;

  FUNCTION GET_GROUPE_TYPE_FORMATION( source_code VARCHAR2 DEFAULT NULL ) RETURN groupe_type_formation%rowtype;
  
  FUNCTION ADD_GROUPE_TYPE_FORMATION(
    libelle_long  VARCHAR2,
    libelle_court VARCHAR2,
    source_code   VARCHAR2
  ) RETURN NUMERIC;

  FUNCTION GET_TYPE_FORMATION( source_code VARCHAR2 ) RETURN type_formation%rowtype;
  
  FUNCTION ADD_TYPE_FORMATION(
    libelle_long  VARCHAR2,
    libelle_court VARCHAR2,
    groupe_id     NUMERIC,
    source_code   VARCHAR2
  ) RETURN NUMERIC;

  FUNCTION GET_ETAPE( source_code VARCHAR2 DEFAULT NULL ) RETURN etape%rowtype;
  
  FUNCTION ADD_ETAPE(
    libelle           VARCHAR2,
    type_formation_id NUMERIC,
    niveau            NUMERIC,
    structure_id      NUMERIC,
    source_code       VARCHAR2
  ) RETURN NUMERIC;

  FUNCTION GET_PERIODE( code VARCHAR2 DEFAULT NULL ) RETURN periode%rowtype;

  FUNCTION GET_ELEMENT_PEDAGOGIQUE( source_code VARCHAR2 DEFAULT NULL ) RETURN element_pedagogique%rowtype;
  
  FUNCTION GET_ELEMENT_PEDAGOGIQUE_BY_ID( ID NUMERIC ) RETURN element_pedagogique%rowtype;
  
  FUNCTION ADD_ELEMENT_PEDAGOGIQUE(
    libelle       VARCHAR2,
    etape_id      NUMERIC,
    structure_id  NUMERIC,
    periode_id    NUMERIC,
    taux_foad     FLOAT,
    taux_fi       FLOAT,
    taux_fc       FLOAT,
    taux_fa       FLOAT,
    source_code   VARCHAR2
  ) RETURN NUMERIC;

  FUNCTION GET_TYPE_MODULATEUR( code VARCHAR2 DEFAULT NULL ) RETURN type_modulateur%rowtype;
  
  FUNCTION ADD_TYPE_MODULATEUR(
    code        VARCHAR2,
    libelle     VARCHAR2,
    publique    NUMERIC,
    obligatoire NUMERIC
  ) RETURN NUMERIC;

  FUNCTION GET_MODULATEUR( code VARCHAR2 DEFAULT NULL ) RETURN modulateur%rowtype;
  
  FUNCTION ADD_MODULATEUR(
    code                      VARCHAR2,
    libelle                   VARCHAR2,
    type_modulateur_id        NUMERIC,
    ponderation_service_du    FLOAT,
    ponderation_service_compl FLOAT
  ) RETURN NUMERIC;

  FUNCTION ADD_ELEMENT_MODULATEUR(
    element_id    NUMERIC,
    modulateur_id NUMERIC,
    annee_id      NUMERIC
  ) RETURN NUMERIC;

  FUNCTION GET_FONCTION_REFERENTIEL( code VARCHAR2 DEFAULT NULL ) RETURN fonction_referentiel%rowtype;
  
  FUNCTION ADD_FONCTION_REFERENTIEL(
    code          VARCHAR2,
    libelle_long  VARCHAR2,
    libelle_court VARCHAR2,
    plafond       FLOAT
  ) RETURN NUMERIC;
  
  FUNCTION ADD_SERVICE_REFERENTIEL(
    fonction_id     NUMERIC,
    intervenant_id  NUMERIC,
    structure_id    NUMERIC,
    annee_id        NUMERIC,
    heures          FLOAT
  ) RETURN NUMERIC;
  
  FUNCTION ADD_MODIFICATION_SERVICE_DU(
    intervenant_id  NUMERIC,
    annee_id        NUMERIC,
    heures          FLOAT,
    motif_id        NUMERIC,
    commentaires    CLOB DEFAULT NULL
  ) RETURN NUMERIC;

  FUNCTION GET_MOTIF_MODIFICATION_SERVICE( code VARCHAR2 DEFAULT NULL, multiplicateur FLOAT DEFAULT NULL ) RETURN motif_modification_service%rowtype;

  FUNCTION GET_ETABLISSEMENT( source_code VARCHAR2 DEFAULT NULL ) RETURN etablissement%rowtype;
  
  FUNCTION GET_SERVICE_BY_ID( id NUMERIC ) RETURN service%rowtype;

  FUNCTION ADD_SERVICE(
    intervenant_id          NUMERIC,
    annee_id                NUMERIC,
    element_pedagogique_id  NUMERIC,
    etablissement_id        NUMERIC DEFAULT NULL
  ) RETURN NUMERIC;

  FUNCTION GET_ETAT_VOLUME_HORAIRE( code VARCHAR2 DEFAULT NULL ) RETURN etat_volume_horaire%rowtype;
  
  FUNCTION GET_TYPE_VOLUME_HORAIRE( code VARCHAR2 DEFAULT NULL ) RETURN type_volume_horaire%rowtype;
  
  FUNCTION GET_TYPE_INTERVENTION( code VARCHAR2 DEFAULT NULL ) RETURN type_intervention%rowtype;

  FUNCTION GET_TYPE_INTERVENTION_BY_ID( id NUMERIC ) RETURN type_intervention%rowtype;

  FUNCTION GET_TYPE_INTERVENTION_BY_ELEMT( ELEMENT_ID NUMERIC ) RETURN type_intervention%rowtype;

  FUNCTION GET_MOTIF_NON_PAIEMENT( code VARCHAR2 DEFAULT NULL ) RETURN motif_non_paiement%rowtype;
  
  FUNCTION GET_VOLUME_HORAIRE( id NUMERIC DEFAULT NULL ) RETURN volume_horaire%rowtype;
  
  FUNCTION ADD_VOLUME_HORAIRE(
    type_volume_horaire_id  NUMERIC,
    service_id              NUMERIC,
    periode_id              NUMERIC,
    type_intervention_id    NUMERIC,
    heures                  FLOAT,
    motif_non_paiement_id   NUMERIC DEFAULT NULL
  ) RETURN NUMERIC;

  FUNCTION ADD_VALIDATION_VOLUME_HORAIRE(
    structure_id      NUMERIC DEFAULT NULL,
    intervenant_id    NUMERIC DEFAULT NULL,
    volume_horaire_id NUMERIC DEFAULT NULL,
    service_id        NUMERIC DEFAULT NULL
  ) RETURN NUMERIC;

  PROCEDURE DEL_VALIDATION_VOLUME_HORAIRE(
    structure_id      NUMERIC DEFAULT NULL,
    intervenant_id    NUMERIC DEFAULT NULL,
    volume_horaire_id NUMERIC DEFAULT NULL,
    service_id        NUMERIC DEFAULT NULL,
    validation_id     NUMERIC DEFAULT NULL
  );

  FUNCTION GET_CONTRAT_BY_ID( ID NUMERIC ) RETURN contrat%rowtype;

  FUNCTION ADD_CONTRAT(
    structure_id      NUMERIC DEFAULT NULL,
    intervenant_id    NUMERIC DEFAULT NULL,
    volume_horaire_id NUMERIC DEFAULT NULL,
    service_id        NUMERIC DEFAULT NULL    
  ) RETURN NUMERIC;
  
  FUNCTION SIGNATURE_CONTRAT( contrat_id NUMERIC ) RETURN NUMERIC;
  
  FUNCTION ADD_CONTRAT_VALIDATION( contrat_id NUMERIC ) RETURN NUMERIC;

  FUNCTION DEL_CONTRAT_VALIDATION( contrat_id NUMERIC ) RETURN NUMERIC;

  FUNCTION GET_TYPE_VALIDATION( code VARCHAR2 DEFAULT NULL ) RETURN type_validation%rowtype;
END OSE_TEST;

/
--------------------------------------------------------
--  DDL for Package OSE_TEST_FORMULE
--------------------------------------------------------

  CREATE OR REPLACE PACKAGE "OSE"."OSE_TEST_FORMULE" AS 

  -- SET SERVEROUTPUT ON

  PROCEDURE TEST_MODIFY_INTERVENANT       ( intervenant_id NUMERIC );
  PROCEDURE TEST_MODIFY_SERVICE_DU        ( intervenant_id NUMERIC );
  PROCEDURE TEST_MODIFY_SERVICE_DU_MODIF  ( intervenant_id NUMERIC );
  PROCEDURE TEST_MODIFY_SERVICE_REF       ( intervenant_id NUMERIC );
  PROCEDURE TEST_MODIFY_MOTIF_MOD_SERV    ( intervenant_id NUMERIC );
  PROCEDURE TEST_MODIFY_SERVICE           ( intervenant_id NUMERIC );

  PROCEDURE TEST_MODIFY_ELEMENT           ( service_id NUMERIC );
  PROCEDURE TEST_MODIFY_MODULATEUR        ( service_id NUMERIC );
  PROCEDURE TEST_MODIFY_VOLUME_HORAIRE    ( service_id NUMERIC );
  
  PROCEDURE TEST_MODIFY_TYPE_INTERVENTION ( volume_horaire_id NUMERIC );
  PROCEDURE TEST_MODIFY_VALIDATION        ( volume_horaire_id NUMERIC );
  PROCEDURE TEST_MODIFY_CONTRAT           ( volume_horaire_id NUMERIC );

  FUNCTION GET_ANNEE RETURN NUMERIC;
END OSE_TEST_FORMULE;

/
--------------------------------------------------------
--  DDL for Package UCBN_LDAP
--------------------------------------------------------

  CREATE OR REPLACE PACKAGE "OSE"."UCBN_LDAP" AUTHID CURRENT_USER AS
  ldap_sess DBMS_LDAP.SESSION := NULL ; -- Ne met a NULL qu'une seule fois par session!!!!
  last_used NUMBER ;
  TYPE ARRAY_STR IS VARRAY(64) OF VARCHAR2(256 char);
  a_multi ARRAY_STR ;
  FUNCTION version RETURN VARCHAR2 ;
  FUNCTION get(filtre IN VARCHAR2, attribut IN VARCHAR2, v_multi IN VARCHAR2 DEFAULT 'N', a_multi OUT ARRAY_STR) RETURN VARCHAR2 ;
  FUNCTION uid2mail(ldap_uid IN VARCHAR2) RETURN VARCHAR2 ;
  FUNCTION hid2mail(harpege_uid IN NUMBER) RETURN VARCHAR2 ;
  FUNCTION etu2mail(code_etu IN NUMBER) RETURN VARCHAR2 ;
  FUNCTION uid2alias(ldap_uid IN VARCHAR2) RETURN VARCHAR2 ;
  FUNCTION hid2alias(harpege_uid IN NUMBER) RETURN VARCHAR2 ;
  FUNCTION uid2cn(ldap_uid IN VARCHAR2) RETURN VARCHAR2 ;
  FUNCTION uid2sn(ldap_uid IN VARCHAR2) RETURN VARCHAR2 ;
  FUNCTION uid2givenname(ldap_uid IN VARCHAR2) RETURN VARCHAR2 ;
  FUNCTION uid2gn(ldap_uid IN VARCHAR2) RETURN VARCHAR2 ; -- givenname + sn
  FUNCTION hidIsPrimaryTeacher(harpege_uid IN NUMBER) RETURN VARCHAR2 ;
  FUNCTION hidIsTeacher(harpege_uid IN NUMBER) RETURN VARCHAR2 ;
  FUNCTION ldap_connect RETURN NUMBER ;
  FUNCTION free RETURN NUMBER ;
END ucbn_ldap;

/
--------------------------------------------------------
--  DDL for Package Body OSE_DIVERS
--------------------------------------------------------

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
    IF itype.code = 'P' AND SYSDATE >= OSE_PARAMETRE.get_date_fin_saisie_permanents THEN
      res := 0;
    END IF;
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

FUNCTION GET_TYPE_MODULATEUR_IDS( STRUCTURE_ID NUMERIC, DATE_OBS DATE DEFAULT SYSDATE ) RETURN types_modulateurs AS
   VSID NUMERIC;
   tm_result types_modulateurs;
BEGIN
  VSID := STRUCTURE_ID;
  SELECT DISTINCT
    tms.type_modulateur_id BULK COLLECT INTO tm_result
  FROM
    structure s
    LEFT JOIN type_modulateur_structure tms ON tms.structure_id = s.id
  WHERE
    tms.type_modulateur_id IS NOT NULL
    AND DATE_OBS BETWEEN TMS.VALIDITE_DEBUT AND NVL(TMS.VALIDITE_FIN,DATE_OBS) -- respect des validités
    AND DATE_OBS >= TMS.HISTO_CREATION                                         -- respect de la date de création
    AND (TMS.HISTO_DESTRUCTION IS NULL OR TMS.HISTO_DESTRUCTION >= DATE_OBS)   -- respect des destructions
  START WITH
    s.id = VSID
  CONNECT BY
    PRIOR s.parente_id = s.id
  ;
  
  RETURN tm_result;
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
  res := CASE WHEN date_obs >= date_debut THEN 1 ELSE 0 END;
  IF 1 = res AND date_fin IS NOT NULL THEN
    res := CASE WHEN date_obs < date_fin THEN 1 ELSE 0 END;
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

END OSE_DIVERS;

/
--------------------------------------------------------
--  DDL for Package Body OSE_FORMULE
--------------------------------------------------------

  CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_FORMULE" AS

  v_date_obs DATE;
  
  

  FUNCTION GET_DATE_OBS RETURN DATE AS
  BEGIN
    RETURN SYSDATE;
    --RETURN NVL( v_date_obs, SYSDATE );
  END;
  
  FUNCTION SET_DATE_OBS( DATE_OBS DATE DEFAULT NULL ) RETURN DATE IS
  BEGIN
    v_date_obs := DATE_OBS;
    RETURN v_date_obs;
  END;




  FUNCTION GET_DEF_REFERENTIEL( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC ) RETURN formule_referentiel%rowtype IS
    fr formule_referentiel%rowtype;
  BEGIN
    fr.intervenant_id           := INTERVENANT_ID;
    fr.annee_id                 := ANNEE_ID;
    fr.service_du               := 0;
    fr.service_du_modification  := 0;
    fr.service_du_modifie       := 0;
    fr.service_referentiel      := 0;
    RETURN fr;
  END;


  FUNCTION GET_REFERENTIEL( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC ) RETURN formule_referentiel%rowtype IS
    fr formule_referentiel%rowtype;
  BEGIN
    SELECT * INTO fr FROM formule_referentiel WHERE intervenant_id = GET_REFERENTIEL.INTERVENANT_ID AND annee_id = GET_REFERENTIEL.ANNEE_ID;
    RETURN fr;
    EXCEPTION WHEN NO_DATA_FOUND THEN RETURN GET_DEF_REFERENTIEL( INTERVENANT_ID, ANNEE_ID );
  END;


  FUNCTION CALC_REFERENTIEL( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC ) RETURN formule_referentiel%rowtype IS
    res formule_referentiel%rowtype;
    sd service_du%rowtype;
    rr float;
  BEGIN
    res := GET_REFERENTIEL( INTERVENANT_ID, ANNEE_ID );

    SELECT
      CASE WHEN 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction, ose_formule.get_date_obs ) THEN i.id ELSE NULL END
    INTO
      res.intervenant_id
    FROM
      intervenant i
    WHERE
      i.id = res.intervenant_id;

    IF res.intervenant_id IS NULL THEN RETURN res; END IF;

    SELECT
      NVL( SUM( heures ), 0 ) INTO res.service_du
    FROM
      service_du sd
    WHERE
          1 = ose_divers.comprise_entre( sd.validite_debut, sd.validite_fin,      ose_formule.get_date_obs )
      AND 1 = ose_divers.comprise_entre( sd.histo_creation, sd.histo_destruction, ose_formule.get_date_obs )
      AND sd.annee_id       = res.annee_id
      AND sd.intervenant_id = res.intervenant_id;

    SELECT
      NVL( SUM( msd.heures * mms.multiplicateur ), 0 ) INTO res.service_du_modification
    FROM
      modification_service_du msd
      JOIN MOTIF_MODIFICATION_SERVICE mms ON mms.id = msd.motif_id
    WHERE
          1 = ose_divers.comprise_entre( msd.validite_debut, msd.validite_fin,      ose_formule.get_date_obs )
      AND 1 = ose_divers.comprise_entre( msd.histo_creation, msd.histo_destruction, ose_formule.get_date_obs )
      AND msd.annee_id       = res.annee_id
      AND msd.intervenant_id = res.intervenant_id;

    SELECT
      NVL( SUM( sr.heures ), 0 ) INTO res.service_referentiel
    FROM
      service_referentiel sr
    WHERE
          1 = ose_divers.comprise_entre( sr.validite_debut, sr.validite_fin,      ose_formule.get_date_obs )
      AND 1 = ose_divers.comprise_entre( sr.histo_creation, sr.histo_destruction, ose_formule.get_date_obs )
      AND sr.annee_id       = res.annee_id
      AND sr.intervenant_id = res.intervenant_id;

    RETURN res;
    EXCEPTION WHEN NO_DATA_FOUND THEN RETURN GET_DEF_REFERENTIEL( INTERVENANT_ID, ANNEE_ID );
  END;


  PROCEDURE MAJ_REFERENTIEL( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC ) IS
    fr formule_referentiel%rowtype; 
  BEGIN
    fr := CALC_REFERENTIEL( INTERVENANT_ID, ANNEE_ID );

    IF fr.intervenant_id IS NULL THEN
    
      DELETE FROM formule_referentiel WHERE intervenant_id = MAJ_REFERENTIEL.INTERVENANT_ID AND annee_id = MAJ_REFERENTIEL.ANNEE_ID;

    ELSIF fr.id IS NOT NULL THEN

      UPDATE formule_referentiel SET
        service_du              = fr.service_du,
        service_du_modification = fr.service_du_modification,
        service_referentiel     = fr.service_referentiel
      WHERE
        id = fr.id;

    ELSE
      fr.id := FORMULE_REFERENTIEL_ID_SEQ.NEXTVAL;

      INSERT INTO FORMULE_REFERENTIEL(
        ID,
        INTERVENANT_ID,
        ANNEE_ID,
        SERVICE_DU,
        SERVICE_DU_MODIFICATION,
        SERVICE_REFERENTIEL
      )VALUES(
        fr.id,
        fr.intervenant_id,
        fr.annee_id,
        fr.service_du,
        fr.service_du_modification,
        fr.service_referentiel
      );

    END IF;
  END;


  PROCEDURE IDT_MAJ_REFERENTIEL( INTERVENANT_ID NUMERIC, ANNEE_ID NUMERIC ) IS
  BEGIN
    MERGE INTO formule_referentiel_maj frm USING dual ON (frm.intervenant_id = IDT_MAJ_REFERENTIEL.INTERVENANT_ID AND frm.annee_id = IDT_MAJ_REFERENTIEL.ANNEE_ID)
    WHEN NOT MATCHED THEN INSERT ( INTERVENANT_ID, ANNEE_ID ) VALUES ( IDT_MAJ_REFERENTIEL.INTERVENANT_ID, IDT_MAJ_REFERENTIEL.ANNEE_ID );
  END;


  PROCEDURE MAJ_ALL_REFERENTIEL IS
    a_id NUMERIC;
  BEGIN
    a_id := OSE_PARAMETRE.GET_ANNEE;
    FOR i IN (
      SELECT i.id
      FROM intervenant i
      WHERE
        1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction, ose_formule.get_date_obs )
    )
    LOOP
      MAJ_REFERENTIEL( i.id, a_id );
    END LOOP;
  END;


  PROCEDURE MAJ_IDT_REFERENTIEL IS
  BEGIN
    FOR mp IN (SELECT * FROM formule_referentiel_maj)
    LOOP
      MAJ_REFERENTIEL( mp.intervenant_id, mp.annee_id );
    END LOOP;
    DELETE FROM formule_referentiel_maj;
  END;
   




  FUNCTION GET_DEF_SERVICE( SERVICE_ID NUMERIC ) RETURN formule_service%rowtype IS
    fs formule_service%rowtype;
  BEGIN
    fs.service_id                := SERVICE_ID;
    fs.taux_fi                   := 1;
    fs.taux_fa                   := 0;
    fs.taux_fc                   := 0;
    fs.ponderation_service_du    := 0;
    fs.ponderation_service_compl := 0;
    RETURN fs;
  END;
  

  FUNCTION GET_SERVICE( SERVICE_ID NUMERIC ) RETURN formule_service%rowtype IS
    fs formule_service%rowtype;
  BEGIN
    SELECT * INTO fs FROM formule_service WHERE service_id = GET_SERVICE.service_id;
    RETURN fs;
    EXCEPTION WHEN NO_DATA_FOUND THEN RETURN GET_DEF_SERVICE( SERVICE_ID );
  END;


  FUNCTION CALC_SERVICE( SERVICE_ID NUMERIC ) RETURN formule_service%rowtype IS
    res formule_service%rowtype;
  BEGIN
    res := GET_SERVICE( SERVICE_ID );
    
    SELECT
      CASE WHEN ep.id IS NOT NULL THEN ep.taux_fi ELSE 1 END,
      CASE WHEN ep.id IS NOT NULL THEN ep.taux_fa ELSE 0 END,
      CASE WHEN ep.id IS NOT NULL THEN ep.taux_fc ELSE 0 END,
      CASE WHEN ose_divers.comprise_entre( i.histo_creation, i.histo_destruction, ose_formule.get_date_obs ) = 1 THEN i.id ELSE NULL END,
      s.annee_id,
      CASE WHEN ose_divers.comprise_entre( s.histo_creation, s.histo_destruction, ose_formule.get_date_obs ) = 1 THEN s.id ELSE NULL END
    INTO
      res.taux_fi,
      res.taux_fa,
      res.taux_fc,
      res.intervenant_id,
      res.annee_id,
      res.service_id
    FROM
      service s
      JOIN intervenant i ON i.id = s.intervenant_id
      LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id 
    WHERE
      s.id = CALC_SERVICE.SERVICE_ID;

    IF res.service_id IS NULL THEN RETURN res; END IF;

    SELECT
      NVL( EXP (SUM (LN (m.ponderation_service_du))), 1),
      NVL( EXP (SUM (LN (m.ponderation_service_compl))), 1)
    INTO
      res.ponderation_service_du,
      res.ponderation_service_compl
    FROM
      service                      s
      LEFT JOIN element_modulateur em ON
        em.element_id = s.element_pedagogique_id
        AND em.annee_id = s.annee_id
        AND 1 = ose_divers.comprise_entre( em.histo_creation, em.histo_destruction, ose_formule.get_date_obs )
      LEFT JOIN modulateur         m ON m.id = em.modulateur_id
    WHERE
      1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction, ose_formule.get_date_obs )
      AND s.id = res.service_id;

    RETURN res;
    EXCEPTION WHEN NO_DATA_FOUND THEN RETURN GET_DEF_SERVICE( SERVICE_ID );
  END;


  PROCEDURE MAJ_SERVICE( SERVICE_ID NUMERIC ) IS
    fs formule_service%rowtype; 
  BEGIN
    fs := CALC_SERVICE( SERVICE_ID );

    IF fs.service_id IS NULL OR fs.intervenant_id IS NULL THEN
    
      DELETE FROM formule_service WHERE service_id = MAJ_SERVICE.SERVICE_ID;
    
    ELSIF fs.id IS NOT NULL THEN

      UPDATE formule_service SET
        taux_fi                   = fs.taux_fi,
        taux_fa                   = fs.taux_fa,
        taux_fc                   = fs.taux_fc,
        ponderation_service_du    = fs.ponderation_service_du,
        ponderation_service_compl = fs.ponderation_service_compl,
        intervenant_id            = fs.intervenant_id,
        annee_id                  = fs.annee_id
      WHERE
        id = fs.id;

    ELSE
      fs.id := FORMULE_SERVICE_ID_SEQ.NEXTVAL;

      INSERT INTO FORMULE_SERVICE(
        ID,
        SERVICE_ID,
        TAUX_FI,
        TAUX_FA,
        TAUX_FC,
        PONDERATION_SERVICE_DU,
        PONDERATION_SERVICE_COMPL,
        INTERVENANT_ID,
        ANNEE_ID
      )VALUES(
        fs.id,
        fs.service_id,
        fs.taux_fi,
        fs.taux_fa,
        fs.taux_fc,
        fs.ponderation_service_du,
        fs.ponderation_service_compl,
        fs.intervenant_id,
        fs.annee_id
      );

    END IF;
  END;
  
  
  PROCEDURE IDT_MAJ_SERVICE( SERVICE_ID NUMERIC ) IS
  BEGIN
    MERGE INTO formule_service_maj fsm USING dual ON (fsm.service_id = IDT_MAJ_SERVICE.SERVICE_ID)
    WHEN NOT MATCHED THEN INSERT ( SERVICE_ID ) VALUES ( IDT_MAJ_SERVICE.SERVICE_ID );
  END;
  
  
  PROCEDURE MAJ_ALL_SERVICE IS
  BEGIN
    FOR s IN (
      SELECT s.id
      FROM service s
      WHERE
        1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction, ose_formule.get_date_obs )
    )
    LOOP
      MAJ_SERVICE( s.id );
    END LOOP;
  END;


  PROCEDURE MAJ_IDT_SERVICE IS
  BEGIN
    FOR mp IN (SELECT * FROM formule_service_maj)
    LOOP
      MAJ_SERVICE( mp.service_id );
    END LOOP;
    DELETE FROM formule_service_maj;
  END;
  
  



  FUNCTION GET_DEF_VOLUME_HORAIRE( VOLUME_HORAIRE_ID NUMERIC ) RETURN formule_volume_horaire%rowtype IS
    fvh formule_volume_horaire%rowtype;
  BEGIN
    fvh.volume_horaire_id  := VOLUME_HORAIRE_ID;
    fvh.heures             := 0;
    fvh.taux_service_du    := 1;
    fvh.taux_service_compl := 1;
    RETURN fvh;
  END;
  

  FUNCTION GET_VOLUME_HORAIRE( VOLUME_HORAIRE_ID NUMERIC ) RETURN formule_volume_horaire%rowtype IS
    fvh formule_volume_horaire%rowtype;
  BEGIN
    SELECT * INTO fvh FROM formule_volume_horaire WHERE volume_horaire_id = GET_VOLUME_HORAIRE.volume_horaire_id;
    RETURN fvh;
    EXCEPTION WHEN NO_DATA_FOUND THEN RETURN GET_DEF_VOLUME_HORAIRE( VOLUME_HORAIRE_ID );
  END;


  FUNCTION CALC_VOLUME_HORAIRE( VOLUME_HORAIRE_ID NUMERIC ) RETURN formule_volume_horaire%rowtype IS
    res formule_volume_horaire%rowtype;
  BEGIN
    res := GET_VOLUME_HORAIRE( VOLUME_HORAIRE_ID );

    SELECT
      CASE WHEN 1 = ose_divers.comprise_entre( vh.histo_creation,  vh.histo_destruction,  ose_formule.get_date_obs ) AND vh.motif_non_paiement_id IS NULL THEN vh.id ELSE NULL END,
      vh.heures,
      ti.taux_hetd_service,
      ti.taux_hetd_complementaire,
      CASE WHEN 1 = ose_divers.comprise_entre( i.histo_creation,   i.histo_destruction,   ose_formule.get_date_obs ) THEN i.id   ELSE NULL END,
      s.annee_id,
      CASE WHEN 1 = ose_divers.comprise_entre( s.histo_creation,   s.histo_destruction,   ose_formule.get_date_obs ) THEN s.id   ELSE NULL END,
      CASE WHEN 1 = ose_divers.comprise_entre( tvh.histo_creation, tvh.histo_destruction, ose_formule.get_date_obs ) THEN tvh.id ELSE NULL END,
      CASE WHEN 1 = ose_divers.comprise_entre( evh.histo_creation, evh.histo_destruction, ose_formule.get_date_obs ) THEN evh.id ELSE NULL END,
      CASE WHEN 1 = ose_divers.comprise_entre( ti.histo_creation,  ti.histo_destruction,  ose_formule.get_date_obs ) THEN ti.id  ELSE NULL END
    INTO
      res.volume_horaire_id,
      res.heures,
      res.taux_service_du,
      res.taux_service_compl,
      res.intervenant_id,
      res.annee_id,
      res.service_id,
      res.type_volume_horaire_id,
      res.etat_volume_horaire_id,
      res.type_intervention_id
    FROM
      volume_horaire            vh
      JOIN service              s   ON s.id     = vh.service_id
      JOIN type_intervention    ti  ON ti.id    = vh.type_intervention_id
      JOIN intervenant          i   ON i.id     = s.intervenant_id
      JOIN type_volume_horaire  tvh ON tvh.id   = vh.type_volume_horaire_id
      LEFT JOIN contrat         c   ON c.id     = vh.contrat_id   AND 1 = ose_divers.comprise_entre( c.histo_creation,  c.histo_destruction,  ose_formule.get_date_obs )
      LEFT JOIN validation      cv  ON cv.id    = c.validation_id AND 1 = ose_divers.comprise_entre( cv.histo_creation, cv.histo_destruction, ose_formule.get_date_obs )
      JOIN etat_volume_horaire  evh ON evh.code = CASE
        WHEN c.date_retour_signe IS NOT NULL THEN 'contrat-signe'
        WHEN cv.id IS NOT NULL THEN 'contrat-edite'
        WHEN EXISTS(
          SELECT * FROM validation v JOIN validation_vol_horaire vvh ON vvh.validation_id = v.id
          WHERE vvh.volume_horaire_id = vh.id AND 1 = ose_divers.comprise_entre( v.histo_creation, v.histo_destruction, ose_formule.get_date_obs )
        ) THEN 'valide'
        ELSE 'saisi'
      END
    WHERE
      vh.ID = CALC_VOLUME_HORAIRE.VOLUME_HORAIRE_ID
      AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction );

    RETURN res;
    EXCEPTION WHEN NO_DATA_FOUND THEN RETURN GET_DEF_VOLUME_HORAIRE( VOLUME_HORAIRE_ID );
  END;


  PROCEDURE MAJ_VOLUME_HORAIRE( VOLUME_HORAIRE_ID NUMERIC ) IS
    fvh formule_volume_horaire%rowtype; 
  BEGIN
    fvh := CALC_VOLUME_HORAIRE( VOLUME_HORAIRE_ID );
    IF fvh.volume_horaire_id IS NULL
       OR fvh.intervenant_id IS NULL
       OR fvh.service_id IS NULL
       OR fvh.type_volume_horaire_id IS NULL
       OR fvh.etat_volume_horaire_id IS NULL
       OR fvh.type_intervention_id IS NULL
    THEN

      DELETE FROM formule_volume_horaire WHERE volume_horaire_id = MAJ_VOLUME_HORAIRE.VOLUME_HORAIRE_ID;
    
    ELSIF fvh.id IS NOT NULL THEN

      UPDATE formule_volume_horaire SET
        heures                  = fvh.heures,
        taux_service_du         = fvh.taux_service_du,
        taux_service_compl      = fvh.taux_service_compl,
        intervenant_id          = fvh.intervenant_id,
        annee_id                = fvh.annee_id,
        service_id              = fvh.service_id,
        type_volume_horaire_id  = fvh.type_volume_horaire_id,
        etat_volume_horaire_id  = fvh.etat_volume_horaire_id,
        type_intervention_id    = fvh.type_intervention_id
      WHERE
        id = fvh.id;

    ELSE
      fvh.id := FORMULE_VOLUME_HORAIRE_ID_SEQ.NEXTVAL;

      INSERT INTO FORMULE_VOLUME_HORAIRE(
        ID,
        VOLUME_HORAIRE_ID,
        HEURES,
        TAUX_SERVICE_DU,
        TAUX_SERVICE_COMPL,
        INTERVENANT_ID,
        ANNEE_ID,
        SERVICE_ID,        
        TYPE_VOLUME_HORAIRE_ID,
        ETAT_VOLUME_HORAIRE_ID,
        TYPE_INTERVENTION_ID
      )VALUES(
        fvh.id,
        fvh.volume_horaire_id,
        fvh.heures,
        fvh.taux_service_du,
        fvh.taux_service_compl,
        fvh.intervenant_id,
        fvh.annee_id,
        fvh.service_id,
        fvh.type_volume_horaire_id,
        fvh.etat_volume_horaire_id,
        fvh.type_intervention_id
      );

    END IF;
  END;


  PROCEDURE IDT_MAJ_VOLUME_HORAIRE( VOLUME_HORAIRE_ID NUMERIC ) IS
  BEGIN
    MERGE INTO formule_volume_horaire_maj fvhm USING dual ON (fvhm.volume_horaire_id = IDT_MAJ_VOLUME_HORAIRE.VOLUME_HORAIRE_ID)
    WHEN NOT MATCHED THEN INSERT ( VOLUME_HORAIRE_ID ) VALUES ( IDT_MAJ_VOLUME_HORAIRE.VOLUME_HORAIRE_ID );
  END;


  PROCEDURE MAJ_ALL_VOLUME_HORAIRE IS
  BEGIN
    FOR vh IN (
      SELECT vh.id
      FROM volume_horaire vh
      WHERE
        1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction, ose_formule.get_date_obs )
        AND motif_non_paiement_id IS NULL
    )
    LOOP
      MAJ_VOLUME_HORAIRE( vh.id );
    END LOOP;
  END;


  PROCEDURE MAJ_IDT_VOLUME_HORAIRE IS
  BEGIN
    FOR mp IN (SELECT * FROM formule_volume_horaire_maj)
    LOOP
      MAJ_VOLUME_HORAIRE( mp.volume_horaire_id );
    END LOOP;
    DELETE FROM formule_volume_horaire_maj;
  END;





  PROCEDURE MAJ_ALL_IDT IS
  BEGIN
    MAJ_IDT_REFERENTIEL;
    MAJ_IDT_SERVICE;
    MAJ_IDT_VOLUME_HORAIRE;
  END;

  PROCEDURE EXECUTE_SIGNAL IS
    v_mesg VARCHAR2(30);
    v_status INTEGER;
  BEGIN
    dbms_output.put_line('OK, c''est fait!');
    INSERT INTO SYNC_LOG(
      ID, DATE_SYNC, MESSAGE
    ) VALUES (
      SYNC_LOG_ID_SEQ.NEXTVAL, SYSDATE, 'EXECUTE SIGNAL'
    );
    OSE_FORMULE.MAJ_ALL_IDT;

    DBMS_ALERT.WAITONE('ose_formule_maj', v_mesg, v_status); 
    --IF v_status = 0 THEN EXECUTE_SIGNAL; END IF;
  END;

 /* PROCEDURE REGISTER_SIGNAL IS
  BEGIN
    DBMS_ALERT.REGISTER('ose_formule_maj');
    EXECUTE_SIGNAL;
  END;*/

  PROCEDURE REGISTER_SIGNAL IS
    v_mesg VARCHAR2(30);
    v_status INTEGER;
  BEGIN
    RETURN;
/*    DBMS_ALERT.REGISTER('ose_formule_maj');

    DBMS_ALERT.WAITONE('ose_formule_maj', v_mesg, v_status); 
    dbms_output.put_line('Msg: ' || v_mesg || ' Stat: ' || TO_CHAR(v_status));
  */    
  END;

  PROCEDURE UNREGISTER_SIGNAL IS
  BEGIN
    RETURN;
    DBMS_ALERT.REMOVE('ose_formule_maj');
  END;


  PROCEDURE RUN_SIGNAL IS
  BEGIN
    RETURN;
    DBMS_ALERT.SIGNAL('ose_formule_maj', 'test'); 
  END;
  
END OSE_FORMULE;

/
--------------------------------------------------------
--  DDL for Package Body OSE_IMPORT
--------------------------------------------------------

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

  PROCEDURE SYNC_ELEMENT_PORTEUR_PORTE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ELEMENT_PORTEUR_PORTE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ELEMENT_PORTEUR_PORTE;

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
    SYNC_CHEMIN_PEDAGOGIQUE;
    SYNC_ELEMENT_PORTEUR_PORTE;
    SYNC_DISCIPLINE;
    SYNC_ELEMENT_DISCIPLINE;
    SYNC_VOLUME_HORAIRE_ENS;
    
    -- Mise à jour des sources calculées en dernier
    SYNC_TYPE_INTERVENTION_EP;
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



  PROCEDURE MAJ_ELEMENT_PORTEUR_PORTE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ELEMENT_PORTEUR_PORTE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ELEMENT_PORTEUR_PORTE.* FROM V_DIFF_ELEMENT_PORTEUR_PORTE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ELEMENT_PORTEUR_PORTE
            ( id, ELEMENT_PORTEUR_ID,ELEMENT_PORTE_ID,TYPE_INTERVENTION_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ELEMENT_PORTEUR_PORTE_ID_SEQ.NEXTVAL), diff_row.ELEMENT_PORTEUR_ID,diff_row.ELEMENT_PORTE_ID,diff_row.TYPE_INTERVENTION_ID, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_ELEMENT_PORTEUR_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PORTEUR_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PORTEUR_PORTE SET ELEMENT_PORTEUR_ID = diff_row.ELEMENT_PORTEUR_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PORTE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PORTE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PORTEUR_PORTE SET ELEMENT_PORTE_ID = diff_row.ELEMENT_PORTE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_INTERVENTION_ID = 1 AND IN_COLUMN_LIST('TYPE_INTERVENTION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PORTEUR_PORTE SET TYPE_INTERVENTION_ID = diff_row.TYPE_INTERVENTION_ID WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ELEMENT_PORTEUR_PORTE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_ELEMENT_PORTEUR_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PORTEUR_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PORTEUR_PORTE SET ELEMENT_PORTEUR_ID = diff_row.ELEMENT_PORTEUR_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PORTE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PORTE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PORTEUR_PORTE SET ELEMENT_PORTE_ID = diff_row.ELEMENT_PORTE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_INTERVENTION_ID = 1 AND IN_COLUMN_LIST('TYPE_INTERVENTION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PORTEUR_PORTE SET TYPE_INTERVENTION_ID = diff_row.TYPE_INTERVENTION_ID WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ELEMENT_PORTEUR_PORTE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ELEMENT_PORTEUR_PORTE;



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

  -- END OF AUTOMATIC GENERATION --
END ose_import;

/
--------------------------------------------------------
--  DDL for Package Body OSE_PARAMETRE
--------------------------------------------------------

  CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_PARAMETRE" AS

  cache_ose_user NUMERIC;
  cache_annee_id NUMERIC;

  function get_etablissement return Numeric AS
    etab_id numeric;
  BEGIN
    select to_number(valeur) into etab_id from parametre where nom = 'etablissement';
    RETURN etab_id;
  END get_etablissement;

  function get_annee return Numeric AS
    annee_id numeric;
  BEGIN
    IF cache_annee_id IS NOT NULL THEN RETURN cache_annee_id; END IF;
    select to_number(valeur) into annee_id from parametre where nom = 'annee';
    cache_annee_id := annee_id;
    RETURN cache_annee_id;
  END get_annee;

  function get_ose_user return Numeric AS
    ose_user_id numeric;
  BEGIN
    IF cache_ose_user IS NOT NULL THEN RETURN cache_ose_user; END IF;
    select to_number(valeur) into ose_user_id from parametre where nom = 'oseuser';
    cache_ose_user := ose_user_id;
    RETURN cache_ose_user;
  END get_ose_user;

  function get_drh_structure_id return Numeric AS
    drh_structure_id numeric;
  BEGIN
    select to_number(valeur) into drh_structure_id from parametre where nom = 'drh_structure_id';
    RETURN drh_structure_id;
  END get_drh_structure_id;

  FUNCTION get_date_fin_saisie_permanents RETURN DATE IS
    date_fin_saisie_permanents date;
  BEGIN
    select TO_DATE(valeur, 'dd/mm/yyyy') into date_fin_saisie_permanents from parametre where nom = 'date_fin_saisie_permanents';
    RETURN date_fin_saisie_permanents;
  END;

END OSE_PARAMETRE;

/
--------------------------------------------------------
--  DDL for Package Body OSE_TEST
--------------------------------------------------------

  CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_TEST" AS
  TYPE OUT_LIST IS TABLE OF CLOB;

  SUCCES_SHOWN BOOLEAN DEFAULT TRUE;
  T_SUCCES_COUNT NUMERIC DEFAULT 0;
  T_ECHECS_COUNT NUMERIC DEFAULT 0;
  A_SUCCES_COUNT NUMERIC DEFAULT 0;
  A_ECHECS_COUNT NUMERIC DEFAULT 0;
  CURRENT_TEST CLOB;
  CURRENT_TEST_OUTPUT_BUFFER OUT_LIST := OUT_LIST();
  CURRENT_TEST_OUTPUT_BUFFER_ERR BOOLEAN;
  
  PROCEDURE SHOW_SUCCES IS
  BEGIN
    SUCCES_SHOWN := true;
  END SHOW_SUCCES;

  PROCEDURE HIDE_SUCCES IS
  BEGIN
    SUCCES_SHOWN := false;
  END HIDE_SUCCES;

  PROCEDURE DEBUT( TEST_NAME CLOB ) IS
  BEGIN
    CURRENT_TEST := TEST_NAME;
    CURRENT_TEST_OUTPUT_BUFFER_ERR := FALSE;
    echo (' '); echo('TEST ' || TEST_NAME || ' >>>>>>>>>>' );
  END;

  PROCEDURE FIN IS
    TEST_NAME CLOB;
  BEGIN
    IF CURRENT_TEST_OUTPUT_BUFFER_ERR THEN
      T_ECHECS_COUNT := T_ECHECS_COUNT + 1;
      echo('>>>>>>>>>> FIN DU TEST ' || CURRENT_TEST ); echo (' ');
      CURRENT_TEST := NULL;

      FOR i IN 1 .. CURRENT_TEST_OUTPUT_BUFFER.COUNT LOOP
        echo( CURRENT_TEST_OUTPUT_BUFFER(i) );
      END LOOP;
    ELSE
      T_SUCCES_COUNT := T_SUCCES_COUNT + 1;
      TEST_NAME := CURRENT_TEST;
      CURRENT_TEST := NULL;
      echo('SUCCÈS DU TEST : ' || TEST_NAME );
    END IF;
    CURRENT_TEST_OUTPUT_BUFFER.DELETE; -- clear buffer
  END;

  PROCEDURE ECHO( MSG CLOB ) IS
  BEGIN
    IF CURRENT_TEST IS NULL THEN
      dbms_output.put_line(MSG);
    ELSE
      CURRENT_TEST_OUTPUT_BUFFER.EXTEND;
      CURRENT_TEST_OUTPUT_BUFFER (CURRENT_TEST_OUTPUT_BUFFER.LAST) := MSG;
    END IF;
  END;

  PROCEDURE INIT IS
  BEGIN
    T_SUCCES_COUNT  := 0;
    T_ECHECS_COUNT  := 0;
    A_SUCCES_COUNT  := 0;
    A_ECHECS_COUNT  := 0;
    CURRENT_TEST    := NULL;
  END INIT;

  PROCEDURE SHOW_STATS IS
  BEGIN
    echo ( ' ' );
    echo ( '********************************* STATISTIQUES *********************************' );
    echo ( ' ' );
    echo ( '   - nombre de tests passés avec succès :       ' || T_SUCCES_COUNT );
    echo ( '   - nombre de tests ayant échoué :             ' || T_ECHECS_COUNT );
    echo ( ' ' );
    echo ( '   - nombre d''assertions passés avec succès :   ' || A_SUCCES_COUNT );
    echo ( '   - nombre d''assertions ayant échoué :         ' || A_ECHECS_COUNT );
    echo ( ' ' );
    echo ( '********************************************************************************' );
    echo ( ' ' );
  END;

  PROCEDURE ASSERT( condition BOOLEAN, MSG CLOB ) IS
  BEGIN
    IF condition THEN
      A_SUCCES_COUNT := A_SUCCES_COUNT + 1;
      IF SUCCES_SHOWN THEN
        ECHO('        SUCCÈS : ' || MSG );
      END IF;
    ELSE
      A_ECHECS_COUNT := A_ECHECS_COUNT + 1;
      CURRENT_TEST_OUTPUT_BUFFER_ERR := TRUE;
      ECHO('        ** ECHEC ** : ' || MSG );
    END IF;
  END;
  
  PROCEDURE ADD_BUFFER( table_name VARCHAR2, id NUMERIC ) IS
  BEGIN
    INSERT INTO TEST_BUFFER( ID, TABLE_NAME, DATA_ID ) 
                    VALUES ( TEST_BUFFER_ID_SEQ.NEXTVAL, table_name, id );
  END;
  
  PROCEDURE DELETE_TEST_DATA IS
  BEGIN
    FOR tb IN (SELECT * FROM TEST_BUFFER)
    LOOP
      EXECUTE IMMEDIATE 'DELETE FROM ' || tb.table_name || ' WHERE ID = ' || tb.data_id;
    END LOOP;
    DELETE FROM TEST_BUFFER;
  END;
  
  FUNCTION GET_USER RETURN NUMERIC IS
  BEGIN
    RETURN 1; -- utilisateur réservé aux tests... (à revoir!!)
  END;
 
  FUNCTION GET_SOURCE RETURN NUMERIC IS
    res_id Numeric;
  BEGIN
    SELECT s.id INTO res_id FROM ose.source s WHERE s.code = 'TEST';
    RETURN res_id;
  END;
  
  
  FUNCTION GET_CIVILITE( libelle_court VARCHAR2 DEFAULT NULL ) RETURN civilite%rowtype IS
    res civilite%rowtype;
  BEGIN
    SELECT * INTO res FROM civilite WHERE
      (OSE_DIVERS.LIKED( libelle_court, GET_CIVILITE.libelle_court ) = 1 OR GET_CIVILITE.libelle_court IS NULL) AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_TYPE_INTERVENANT( code VARCHAR2 DEFAULT NULL ) RETURN type_intervenant%rowtype IS
    res type_intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM type_intervenant WHERE
      (OSE_DIVERS.LIKED( code, GET_TYPE_INTERVENANT.code ) = 1 OR GET_TYPE_INTERVENANT.code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_TYPE_INTERVENANT_BY_ID( id NUMERIC ) RETURN type_intervenant%rowtype IS
    res type_intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM type_intervenant WHERE
      id = GET_TYPE_INTERVENANT_BY_ID.id;
    RETURN res;
  END;

  FUNCTION GET_STATUT_INTERVENANT( source_code VARCHAR2 DEFAULT NULL ) RETURN statut_intervenant%rowtype IS
    res statut_intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM statut_intervenant WHERE
      (OSE_DIVERS.LIKED( source_code, GET_STATUT_INTERVENANT.source_code ) = 1 OR GET_STATUT_INTERVENANT.source_code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_STATUT_INTERVENANT_BY_ID( id NUMERIC ) RETURN statut_intervenant%rowtype IS
    res statut_intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM statut_intervenant WHERE id = GET_STATUT_INTERVENANT_BY_ID.id;
    RETURN res;
  END;
  
  FUNCTION GET_TYPE_STRUCTURE( code VARCHAR2 DEFAULT NULL ) RETURN type_structure%rowtype IS
    res type_structure%rowtype;
  BEGIN
    SELECT * INTO res FROM type_structure WHERE
      (OSE_DIVERS.LIKED( code, GET_TYPE_STRUCTURE.code ) = 1 OR GET_TYPE_STRUCTURE.code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_STRUCTURE( source_code VARCHAR2 DEFAULT NULL ) RETURN structure%rowtype IS
    res structure%rowtype;
  BEGIN
    SELECT * INTO res FROM structure WHERE
      (OSE_DIVERS.LIKED( source_code, GET_STRUCTURE.source_code ) = 1 OR GET_STRUCTURE.source_code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION GET_STRUCTURE_BY_ID( id NUMERIC ) RETURN structure%rowtype IS
    res structure%rowtype;
  BEGIN
    SELECT * INTO res FROM structure WHERE id = GET_STRUCTURE_BY_ID.id;
    RETURN res;
  END;
  
  FUNCTION GET_STRUCTURE_ENS_BY_NIVEAU( niveau NUMERIC ) RETURN structure%rowtype IS
    res structure%rowtype;
  BEGIN
    SELECT * INTO res FROM structure WHERE
      niveau = GET_STRUCTURE_ENS_BY_NIVEAU.niveau AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_STRUCTURE_UNIV RETURN "STRUCTURE"%rowtype IS
    res "STRUCTURE"%rowtype;
  BEGIN
    SELECT * INTO res FROM "STRUCTURE" WHERE source_code = 'UNIV' AND histo_destruction IS NULL ;
    RETURN res;  
  END;

  FUNCTION ADD_STRUCTURE(
    libelle_long  VARCHAR2,
    libelle_court VARCHAR2,
    parente_id    NUMERIC,
    type_id       NUMERIC,
    source_code   VARCHAR2
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
    parente  structure%rowtype;
    niv2_id  NUMERIC;
  BEGIN
    entity_id := STRUCTURE_ID_SEQ.NEXTVAL;
    IF parente_id IS NOT NULL THEN
      parente := GET_STRUCTURE_BY_ID( parente_id );
      niv2_id := CASE
        WHEN parente.niveau = 1 THEN entity_id
        WHEN parente.niveau = 2 THEN parente_id
        WHEN parente.niveau = 3 THEN parente.parente_id
        WHEN parente.niveau = 4 THEN GET_STRUCTURE_BY_ID( parente.parente_id ).parente_id
        WHEN parente.niveau = 5 THEN GET_STRUCTURE_BY_ID( GET_STRUCTURE_BY_ID( parente.parente_id ).parente_id ).parente_id
        WHEN parente.niveau = 6 THEN GET_STRUCTURE_BY_ID( GET_STRUCTURE_BY_ID( GET_STRUCTURE_BY_ID( parente.parente_id ).parente_id ).parente_id ).parente_id
      END;
    END IF;
    INSERT INTO STRUCTURE (
      ID,
      LIBELLE_LONG,
      LIBELLE_COURT,
      PARENTE_ID,
      STRUCTURE_NIV2_ID,
      TYPE_ID,
      ETABLISSEMENT_ID,
      NIVEAU,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      libelle_long,
      libelle_court,
      parente_id,
      niv2_id,
      type_id,
      OSE_PARAMETRE.GET_ETABLISSEMENT,
      NVL( parente.niveau, 1),
      GET_SOURCE,
      source_code,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'structure', entity_id);
    RETURN entity_id;
  END;

  FUNCTION GET_INTERVENANT( source_code VARCHAR2 DEFAULT NULL ) RETURN intervenant%rowtype IS
    res intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM intervenant WHERE
      (OSE_DIVERS.LIKED( source_code, GET_INTERVENANT.source_code ) = 1 OR GET_INTERVENANT.source_code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_INTERVENANT_BY_ID( id NUMERIC DEFAULT NULL ) RETURN intervenant%rowtype IS
    res intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM intervenant WHERE id = GET_INTERVENANT_BY_ID.id;
    RETURN res;
  END;

  FUNCTION GET_INTERVENANT_BY_STATUT( statut_id NUMERIC ) RETURN intervenant%rowtype IS
    res intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM intervenant WHERE
      statut_id = GET_INTERVENANT_BY_STATUT.statut_id AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_INTERVENANT_BY_TYPE( type_id NUMERIC ) RETURN intervenant%rowtype IS
    res intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM intervenant WHERE
      type_id = GET_INTERVENANT_BY_TYPE.type_id AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;  
  END;

  FUNCTION ADD_INTERVENANT(
    civilite_id     NUMERIC,
    nom_usuel       VARCHAR2,
    prenom          VARCHAR2,
    date_naissance  DATE,
    email           VARCHAR2,
    statut_id       NUMERIC,
    structure_id    NUMERIC,
    source_code     VARCHAR2
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
    statut statut_intervenant%rowtype;
    type_interv type_intervenant%rowtype;
  BEGIN
    entity_id := INTERVENANT_ID_SEQ.NEXTVAL;
    statut := GET_STATUT_INTERVENANT_BY_ID( statut_id );
    type_interv := GET_TYPE_INTERVENANT_BY_ID( statut.type_intervenant_id );
    INSERT INTO INTERVENANT (
      ID,
      CIVILITE_ID,
      NOM_USUEL,
      PRENOM,
      NOM_PATRONYMIQUE,
      DATE_NAISSANCE,
      PAYS_NAISSANCE_CODE_INSEE,
      PAYS_NAISSANCE_LIBELLE,
      EMAIL,
      TYPE_ID,
      STATUT_ID,
      STRUCTURE_ID,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      civilite_id,
      nom_usuel,
      prenom,
      nom_usuel,
      date_naissance,
      100,
      'FRANCE',
      email,
      type_interv.id,
      statut_id,
      structure_id,
      GET_SOURCE,
      source_code,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'intervenant', entity_id);
    IF type_interv.code = 'P' THEN
      INSERT INTO INTERVENANT_PERMANENT(
        ID,
        SOURCE_ID,
        SOURCE_CODE,
        HISTO_CREATEUR_ID,
        HISTO_MODIFICATEUR_ID
      )VALUES(
        entity_id,
        GET_SOURCE,
        source_code,
        GET_USER,
        GET_USER
      );
      INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'intervenant_permanent', entity_id);
    END IF;
    IF type_interv.code = 'E' THEN
      INSERT INTO INTERVENANT_EXTERIEUR(
        ID,
        SOURCE_ID,
        SOURCE_CODE,
        HISTO_CREATEUR_ID,
        HISTO_MODIFICATEUR_ID
      )VALUES(
        entity_id,
        GET_SOURCE,
        source_code,
        GET_USER,
        GET_USER
      );
      INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'intervenant_exterieur', entity_id);
    END IF;
    RETURN entity_id;
  END;

  FUNCTION GET_GROUPE_TYPE_FORMATION( source_code VARCHAR2 DEFAULT NULL ) RETURN groupe_type_formation%rowtype IS
    res groupe_type_formation%rowtype;
  BEGIN
    SELECT * INTO res FROM groupe_type_formation WHERE
      (OSE_DIVERS.LIKED( source_code, GET_GROUPE_TYPE_FORMATION.source_code ) = 1 OR GET_GROUPE_TYPE_FORMATION.source_code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION ADD_GROUPE_TYPE_FORMATION(
    libelle_long  VARCHAR2,
    libelle_court VARCHAR2,
    source_code   VARCHAR2
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := GROUPE_TYPE_FORMATION_ID_SEQ.NEXTVAL;
    INSERT INTO GROUPE_TYPE_FORMATION (
      ID,
      LIBELLE_COURT,
      LIBELLE_LONG,
      ORDRE,
      PERTINENCE_NIVEAU,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    ) VALUES (
      entity_id,
      libelle_court,
      libelle_long,
      999,
      0,
      GET_SOURCE,
      source_code,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'groupe_type_formation', entity_id);
    RETURN entity_id;
  END;

  FUNCTION GET_TYPE_FORMATION( source_code VARCHAR2 ) RETURN type_formation%rowtype IS
    res type_formation%rowtype;
  BEGIN
    SELECT * INTO res FROM type_formation WHERE
      (OSE_DIVERS.LIKED( source_code, GET_TYPE_FORMATION.source_code ) = 1 OR GET_TYPE_FORMATION.source_code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION ADD_TYPE_FORMATION(
    libelle_long  VARCHAR2,
    libelle_court VARCHAR2,
    groupe_id     NUMERIC,
    source_code   VARCHAR2
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := TYPE_FORMATION_ID_SEQ.NEXTVAL;
    INSERT INTO TYPE_FORMATION(
      ID,
      LIBELLE_LONG,
      LIBELLE_COURT,
      GROUPE_ID,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    ) VALUES (
      entity_id,
      libelle_long,
      libelle_court,
      groupe_id,
      GET_SOURCE,
      source_code,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'type_formation', entity_id);
    RETURN entity_id;
  END;

  FUNCTION GET_ETAPE( source_code VARCHAR2 DEFAULT NULL ) RETURN etape%rowtype IS
    res etape%rowtype;
  BEGIN
    SELECT * INTO res FROM etape WHERE
      (OSE_DIVERS.LIKED( source_code, GET_ETAPE.source_code ) = 1 OR GET_ETAPE.source_code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION ADD_ETAPE(
    libelle           VARCHAR2,
    type_formation_id NUMERIC,
    niveau            NUMERIC,
    structure_id      NUMERIC,
    source_code       VARCHAR2
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := ETAPE_ID_SEQ.NEXTVAL;
    INSERT INTO ETAPE (
      ID,
      LIBELLE,
      TYPE_FORMATION_ID,
      NIVEAU,
      SPECIFIQUE_ECHANGES,
      STRUCTURE_ID,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      libelle,
      type_formation_id,
      niveau,
      0,
      structure_id,
      GET_SOURCE,
      source_code,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'etape', entity_id);
    RETURN entity_id;
  END;

  FUNCTION GET_PERIODE( code VARCHAR2 DEFAULT NULL ) RETURN periode%rowtype IS
    res periode%rowtype;
  BEGIN
    SELECT * INTO res FROM periode WHERE
      (OSE_DIVERS.LIKED( code, GET_PERIODE.code ) = 1 OR GET_PERIODE.code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_ELEMENT_PEDAGOGIQUE( source_code VARCHAR2 DEFAULT NULL ) RETURN element_pedagogique%rowtype IS
    res element_pedagogique%rowtype;
  BEGIN
    SELECT * INTO res FROM element_pedagogique WHERE
      (OSE_DIVERS.LIKED( source_code, GET_ELEMENT_PEDAGOGIQUE.source_code ) = 1 OR GET_ELEMENT_PEDAGOGIQUE.source_code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION GET_ELEMENT_PEDAGOGIQUE_BY_ID( ID NUMERIC ) RETURN element_pedagogique%rowtype IS
    res element_pedagogique%rowtype;
  BEGIN
    SELECT * INTO res FROM element_pedagogique WHERE id = GET_ELEMENT_PEDAGOGIQUE_BY_ID.id;
    RETURN res;
  END;
  
  FUNCTION ADD_ELEMENT_PEDAGOGIQUE(
    libelle       VARCHAR2,
    etape_id      NUMERIC,
    structure_id  NUMERIC,
    periode_id    NUMERIC,
    taux_foad     FLOAT,
    taux_fi       FLOAT,
    taux_fc       FLOAT,
    taux_fa       FLOAT,
    source_code   VARCHAR2
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
    ch_id NUMERIC;
  BEGIN
    entity_id := ELEMENT_PEDAGOGIQUE_ID_SEQ.NEXTVAL;
    INSERT INTO ELEMENT_PEDAGOGIQUE (
      ID,
      LIBELLE,
      ETAPE_ID,
      STRUCTURE_ID,
      PERIODE_ID,
      TAUX_FOAD,
      TAUX_FI,
      TAUX_FC,
      TAUX_FA,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      libelle,
      etape_id,
      structure_id,
      periode_id,
      taux_foad,
      taux_fi,
      taux_fc,
      taux_fa,
      GET_SOURCE,
      source_code,
      GET_USER,
      GET_USER
    );
    ch_id := CHEMIN_PEDAGOGIQUE_ID_SEQ.NEXTVAL;
    INSERT INTO CHEMIN_PEDAGOGIQUE (
      ID,
      ELEMENT_PEDAGOGIQUE_ID,
      ETAPE_ID,
      ORDRE,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      ch_id,
      entity_id,
      etape_id,
      9999999,
      GET_SOURCE,
      source_code,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'element_pedagogique', entity_id);
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'chemin_pedagogique', ch_id);
    RETURN entity_id;
  END;

  FUNCTION GET_TYPE_MODULATEUR( code VARCHAR2 DEFAULT NULL ) RETURN type_modulateur%rowtype IS
    res type_modulateur%rowtype;
  BEGIN
    SELECT * INTO res FROM type_modulateur WHERE
      (OSE_DIVERS.LIKED( code, GET_TYPE_MODULATEUR.code ) = 1 OR GET_TYPE_MODULATEUR.code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION ADD_TYPE_MODULATEUR(
    code        VARCHAR2,
    libelle     VARCHAR2,
    publique    NUMERIC,
    obligatoire NUMERIC
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
    tms_id    NUMERIC;
    structure_id NUMERIC;
  BEGIN
    entity_id := TYPE_MODULATEUR_ID_SEQ.NEXTVAL;
    INSERT INTO TYPE_MODULATEUR (
      ID,
      CODE,
      LIBELLE,
      PUBLIQUE,
      OBLIGATOIRE,
      SAISIE_PAR_ENSEIGNANT,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      code,
      libelle,
      publique,
      obligatoire,
      0,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'type_modulateur', entity_id);
    structure_id := ose_test.get_structure_univ().id;
    tms_id := TYPE_MODULATEUR_STRUCTU_ID_SEQ.NEXTVAL;
    INSERT INTO TYPE_MODULATEUR_STRUCTURE(
      ID,
      TYPE_MODULATEUR_ID,
      STRUCTURE_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      tms_id,
      entity_id,
      structure_id,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'type_modulateur_structure', tms_id);
    RETURN entity_id;
  END;

  FUNCTION GET_MODULATEUR( code VARCHAR2 DEFAULT NULL ) RETURN modulateur%rowtype IS
    res modulateur%rowtype;
  BEGIN
    SELECT * INTO res FROM modulateur WHERE
      (OSE_DIVERS.LIKED( code, GET_MODULATEUR.code ) = 1 OR GET_MODULATEUR.code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION ADD_MODULATEUR(
    code                      VARCHAR2,
    libelle                   VARCHAR2,
    type_modulateur_id        NUMERIC,
    ponderation_service_du    FLOAT,
    ponderation_service_compl FLOAT
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := MODULATEUR_ID_SEQ.NEXTVAL;
    INSERT INTO MODULATEUR (
      ID,
      CODE,
      LIBELLE,
      TYPE_MODULATEUR_ID,
      PONDERATION_SERVICE_DU,
      PONDERATION_SERVICE_COMPL,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      code,
      libelle,
      type_modulateur_id,
      ponderation_service_du,
      ponderation_service_compl,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'modulateur', entity_id);
    RETURN entity_id;
  END;

  FUNCTION ADD_ELEMENT_MODULATEUR(
    element_id    NUMERIC,
    modulateur_id NUMERIC,
    annee_id      NUMERIC
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := ELEMENT_MODULATEUR_ID_SEQ.NEXTVAL;
    INSERT INTO ELEMENT_MODULATEUR (
      ID,
      ELEMENT_ID,
      MODULATEUR_ID,
      ANNEE_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      element_id,
      modulateur_id,
      annee_id,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'element_modulateur', entity_id);
    RETURN entity_id;
  END;

  FUNCTION GET_FONCTION_REFERENTIEL( code VARCHAR2 DEFAULT NULL ) RETURN fonction_referentiel%rowtype IS
    res fonction_referentiel%rowtype;
  BEGIN
    SELECT * INTO res FROM fonction_referentiel WHERE
      (OSE_DIVERS.LIKED( code, GET_FONCTION_REFERENTIEL.code ) = 1 OR GET_FONCTION_REFERENTIEL.code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION ADD_FONCTION_REFERENTIEL(
    code          VARCHAR2,
    libelle_long  VARCHAR2,
    libelle_court VARCHAR2,
    plafond       FLOAT
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := FONCTION_REFERENTIEL_ID_SEQ.NEXTVAL;
    INSERT INTO FONCTION_REFERENTIEL (
      ID,
      CODE,
      LIBELLE_LONG,
      LIBELLE_COURT,
      PLAFOND,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      code,
      libelle_long,
      libelle_court,
      plafond,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'fonction_referentiel', entity_id);
    RETURN entity_id;
  END;
  
  FUNCTION ADD_SERVICE_REFERENTIEL(
    fonction_id     NUMERIC,
    intervenant_id  NUMERIC,
    structure_id    NUMERIC,
    annee_id        NUMERIC,
    heures          FLOAT
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := SERVICE_REFERENTIEL_ID_SEQ.NEXTVAL;
    INSERT INTO SERVICE_REFERENTIEL (
      ID,
      FONCTION_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      ANNEE_ID,
      HEURES,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      fonction_id,
      intervenant_id,
      structure_id,
      annee_id,
      heures,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'service_referentiel', entity_id);
    RETURN entity_id;
  END;
  
  FUNCTION ADD_MODIFICATION_SERVICE_DU(
    intervenant_id  NUMERIC,
    annee_id        NUMERIC,
    heures          FLOAT,
    motif_id        NUMERIC,
    commentaires    CLOB DEFAULT NULL
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := MODIFICATION_SERVICE_DU_ID_SEQ.NEXTVAL;
    INSERT INTO MODIFICATION_SERVICE_DU (
      ID,
      INTERVENANT_ID,
      ANNEE_ID,
      HEURES,
      MOTIF_ID,
      COMMENTAIRES,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      intervenant_id,
      annee_id,
      heures,
      motif_id,
      commentaires,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'modification_service_du', entity_id);
    RETURN entity_id;
  END;

  FUNCTION GET_MOTIF_MODIFICATION_SERVICE( code VARCHAR2 DEFAULT NULL, multiplicateur FLOAT DEFAULT NULL ) RETURN motif_modification_service%rowtype IS
    res motif_modification_service%rowtype;
  BEGIN
    SELECT * INTO res FROM motif_modification_service WHERE
      (OSE_DIVERS.LIKED( code, GET_MOTIF_MODIFICATION_SERVICE.code ) = 1 OR GET_MOTIF_MODIFICATION_SERVICE.code IS NULL)
      AND (multiplicateur = GET_MOTIF_MODIFICATION_SERVICE.multiplicateur OR GET_MOTIF_MODIFICATION_SERVICE.multiplicateur IS NULL)
      AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_ETABLISSEMENT( source_code VARCHAR2 DEFAULT NULL ) RETURN etablissement%rowtype IS
    res etablissement%rowtype;
  BEGIN
    SELECT * INTO res FROM etablissement WHERE
      (OSE_DIVERS.LIKED( source_code, GET_ETABLISSEMENT.source_code ) = 1 OR (GET_ETABLISSEMENT.source_code IS NULL AND id <> OSE_PARAMETRE.GET_ETABLISSEMENT))
      AND histo_destruction IS NULL
      AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_SERVICE_BY_ID( id NUMERIC ) RETURN service%rowtype IS
    res service%rowtype;
  BEGIN
    SELECT * INTO res FROM service WHERE id = GET_SERVICE_BY_ID.id;
    RETURN res;
  END;

  FUNCTION ADD_SERVICE(
    intervenant_id          NUMERIC,
    annee_id                NUMERIC,
    element_pedagogique_id  NUMERIC,
    etablissement_id        NUMERIC DEFAULT NULL
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
    ep element_pedagogique%rowtype;
    interv intervenant%rowtype;
  BEGIN
    entity_id := SERVICE_ID_SEQ.NEXTVAL;
    IF element_pedagogique_id IS NOT NULL THEN
      ep := GET_ELEMENT_PEDAGOGIQUE_BY_ID( element_pedagogique_id );
    END IF;
    interv := GET_INTERVENANT_BY_ID( intervenant_id );
    INSERT INTO SERVICE (
      ID,
      INTERVENANT_ID,
      STRUCTURE_AFF_ID,
      STRUCTURE_ENS_ID,
      ELEMENT_PEDAGOGIQUE_ID,
      ANNEE_ID,
      ETABLISSEMENT_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      intervenant_id,
      interv.structure_id,
      ep.structure_id,
      element_pedagogique_id,
      annee_id,
      COALESCE( ADD_SERVICE.etablissement_id, OSE_PARAMETRE.GET_ETABLISSEMENT),
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'service', entity_id);
    RETURN entity_id;
  END;

  FUNCTION GET_ETAT_VOLUME_HORAIRE( code VARCHAR2 DEFAULT NULL ) RETURN etat_volume_horaire%rowtype IS
    res etat_volume_horaire%rowtype;
  BEGIN
    SELECT * INTO res FROM etat_volume_horaire WHERE
      (OSE_DIVERS.LIKED( code, GET_ETAT_VOLUME_HORAIRE.code ) = 1 OR GET_ETAT_VOLUME_HORAIRE.code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION GET_TYPE_VOLUME_HORAIRE( code VARCHAR2 DEFAULT NULL ) RETURN type_volume_horaire%rowtype IS
    res type_volume_horaire%rowtype;
  BEGIN
    SELECT * INTO res FROM type_volume_horaire WHERE
      (OSE_DIVERS.LIKED( code, GET_TYPE_VOLUME_HORAIRE.code ) = 1 OR GET_TYPE_VOLUME_HORAIRE.code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION GET_TYPE_INTERVENTION( code VARCHAR2 DEFAULT NULL ) RETURN type_intervention%rowtype IS
    res type_intervention%rowtype;
  BEGIN
    SELECT * INTO res FROM type_intervention WHERE
      (OSE_DIVERS.LIKED( code, GET_TYPE_INTERVENTION.code ) = 1 OR GET_TYPE_INTERVENTION.code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_TYPE_INTERVENTION_BY_ID( id NUMERIC ) RETURN type_intervention%rowtype IS
    res type_intervention%rowtype;
  BEGIN
    SELECT * INTO res FROM type_intervention WHERE id = GET_TYPE_INTERVENTION_BY_ID.id;
    RETURN res;
  END;

  FUNCTION GET_TYPE_INTERVENTION_BY_ELEMT( ELEMENT_ID NUMERIC ) RETURN type_intervention%rowtype IS
    res type_intervention%rowtype;
  BEGIN
    SELECT
      ti.*
    INTO
      res
    FROM
      type_intervention ti
      JOIN v_element_type_intervention eti ON eti.type_intervention_id = ti.id AND eti.element_pedagogique_id = ELEMENT_ID
    WHERE
      ti.histo_destruction IS NULL
      AND rownum = 1;
    RETURN res;
  END;

  FUNCTION GET_MOTIF_NON_PAIEMENT( code VARCHAR2 DEFAULT NULL ) RETURN motif_non_paiement%rowtype IS
    res motif_non_paiement%rowtype;
  BEGIN
    SELECT * INTO res FROM motif_non_paiement WHERE
      (OSE_DIVERS.LIKED( code, GET_MOTIF_NON_PAIEMENT.code ) = 1 OR GET_MOTIF_NON_PAIEMENT.code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_VOLUME_HORAIRE( id NUMERIC DEFAULT NULL ) RETURN volume_horaire%rowtype IS
    res volume_horaire%rowtype;
  BEGIN
    SELECT * INTO res FROM volume_horaire WHERE
      id = GET_VOLUME_HORAIRE.id OR (GET_VOLUME_HORAIRE.id IS NULL AND histo_destruction IS NULL AND ROWNUM = 1);
    RETURN res;    
  END;

  FUNCTION ADD_VOLUME_HORAIRE(
    type_volume_horaire_id  NUMERIC,
    service_id              NUMERIC,
    periode_id              NUMERIC,
    type_intervention_id    NUMERIC,
    heures                  FLOAT,
    motif_non_paiement_id   NUMERIC DEFAULT NULL
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := VOLUME_HORAIRE_ID_SEQ.NEXTVAL;
    INSERT INTO VOLUME_HORAIRE (
      ID,
      TYPE_VOLUME_HORAIRE_ID,
      SERVICE_ID,
      PERIODE_ID,
      TYPE_INTERVENTION_ID,
      HEURES,
      MOTIF_NON_PAIEMENT_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      type_volume_horaire_id,
      service_id,
      periode_id,
      type_intervention_id,
      heures,
      motif_non_paiement_id,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'volume_horaire', entity_id);
    RETURN entity_id;
  END;

  FUNCTION ADD_VALIDATION_VOLUME_HORAIRE(
    structure_id      NUMERIC,
    intervenant_id    NUMERIC,
    volume_horaire_id NUMERIC DEFAULT NULL,
    service_id        NUMERIC DEFAULT NULL
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := VALIDATION_ID_SEQ.NEXTVAL;
    INSERT INTO VALIDATION (
      ID,
      TYPE_VALIDATION_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      (SELECT id FROM type_validation WHERE code = 'SERVICES_PAR_COMP'),
      intervenant_id,
      structure_id,
      GET_USER,
      GET_USER
    );
    FOR vh IN (
      SELECT vh.id FROM volume_horaire vh JOIN service s ON s.id = vh.service_id
      WHERE
        vh.histo_destruction IS NULL AND
        s.histo_destruction IS NULL
        AND (s.structure_ens_id = ADD_VALIDATION_VOLUME_HORAIRE.structure_id OR s.structure_aff_id = ADD_VALIDATION_VOLUME_HORAIRE.structure_id)
        AND (s.intervenant_id = ADD_VALIDATION_VOLUME_HORAIRE.intervenant_id)
        AND (vh.id = ADD_VALIDATION_VOLUME_HORAIRE.volume_horaire_id OR ADD_VALIDATION_VOLUME_HORAIRE.volume_horaire_id IS NULL)
        AND (s.id = ADD_VALIDATION_VOLUME_HORAIRE.service_id OR ADD_VALIDATION_VOLUME_HORAIRE.service_id IS NULL)
    ) LOOP
      INSERT INTO VALIDATION_VOL_HORAIRE(
        VALIDATION_ID,
        VOLUME_HORAIRE_ID
      )VALUES(
        entity_id,
        vh.id
      );
    END LOOP;
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'validation', entity_id);
    RETURN entity_id;
  END;

  PROCEDURE DEL_VALIDATION_VOLUME_HORAIRE(
    structure_id      NUMERIC,
    intervenant_id    NUMERIC,
    volume_horaire_id NUMERIC DEFAULT NULL,
    service_id        NUMERIC DEFAULT NULL,
    validation_id     NUMERIC DEFAULT NULL
  ) IS
    vvh_count NUMERIC;
  BEGIN
    FOR vh IN (
      SELECT vh.id FROM volume_horaire vh JOIN service s ON s.id = vh.service_id
      WHERE
        vh.histo_destruction IS NULL AND
        s.histo_destruction IS NULL
        AND (s.structure_ens_id = DEL_VALIDATION_VOLUME_HORAIRE.structure_id OR s.structure_aff_id = DEL_VALIDATION_VOLUME_HORAIRE.structure_id)
        AND (s.intervenant_id = DEL_VALIDATION_VOLUME_HORAIRE.intervenant_id)
        AND (vh.id = DEL_VALIDATION_VOLUME_HORAIRE.volume_horaire_id OR DEL_VALIDATION_VOLUME_HORAIRE.volume_horaire_id IS NULL)
        AND (s.id = DEL_VALIDATION_VOLUME_HORAIRE.service_id OR DEL_VALIDATION_VOLUME_HORAIRE.service_id IS NULL)
    ) LOOP
      DELETE FROM VALIDATION_VOL_HORAIRE WHERE 
        VOLUME_HORAIRE_ID = vh.id 
        AND (VALIDATION_ID = DEL_VALIDATION_VOLUME_HORAIRE.validation_id OR DEL_VALIDATION_VOLUME_HORAIRE.validation_id IS NULL);
    END LOOP;
    IF VALIDATION_ID IS NOT NULL THEN
      SELECT count(*) INTO vvh_count FROM VALIDATION_VOL_HORAIRE WHERE VALIDATION_ID = DEL_VALIDATION_VOLUME_HORAIRE.validation_id;
      IF 0 = vvh_count THEN
        DELETE FROM validation WHERE id = VALIDATION_ID;
      END IF;
    END IF;
  END;

  FUNCTION GET_CONTRAT_BY_ID( ID NUMERIC ) RETURN contrat%rowtype IS
    res contrat%rowtype;
  BEGIN
    SELECT * INTO res FROM contrat WHERE id = GET_CONTRAT_BY_ID.id;
    RETURN res;
  END;

  FUNCTION ADD_CONTRAT(
    structure_id      NUMERIC DEFAULT NULL,
    intervenant_id    NUMERIC DEFAULT NULL,
    volume_horaire_id NUMERIC DEFAULT NULL,
    service_id        NUMERIC DEFAULT NULL    
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := CONTRAT_ID_SEQ.NEXTVAL;
    INSERT INTO CONTRAT (
      ID,
      TYPE_CONTRAT_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      NUMERO_AVENANT,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      (SELECT id FROM type_contrat WHERE code = 'CONTRAT'),
      intervenant_id,
      structure_id,
      (SELECT MAX(numero_avenant) FROM contrat) + 1,
      GET_USER,
      GET_USER
    );
    FOR vh IN (
      SELECT vh.id FROM volume_horaire vh JOIN service s ON s.id = vh.service_id
      WHERE
        vh.histo_destruction IS NULL
        AND s.histo_destruction IS NULL
        AND (s.intervenant_id = ADD_CONTRAT.intervenant_id OR ADD_CONTRAT.intervenant_id IS NULL)
        AND (vh.id = ADD_CONTRAT.volume_horaire_id OR ADD_CONTRAT.volume_horaire_id IS NULL)
        AND (s.id = ADD_CONTRAT.service_id OR ADD_CONTRAT.service_id IS NULL)
        AND vh.contrat_id IS NULL
    ) LOOP
      UPDATE volume_horaire SET contrat_id = entity_id WHERE volume_horaire.id = vh.id;
    END LOOP;

    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'contrat', entity_id);
    RETURN entity_id;
  END;
  
  FUNCTION SIGNATURE_CONTRAT(
    contrat_id        NUMERIC
  ) RETURN NUMERIC IS
  BEGIN
    UPDATE contrat SET date_retour_signe = SYSDATE WHERE id = SIGNATURE_CONTRAT.contrat_id;
    RETURN contrat_id;
  END;
  
  FUNCTION ADD_CONTRAT_VALIDATION( contrat_id NUMERIC ) RETURN NUMERIC IS
    entity_id NUMERIC;
    ctr contrat%rowtype;
  BEGIN
    ctr := GET_CONTRAT_BY_ID( contrat_id );

    IF ctr.validation_id IS NOT NULL THEN RETURN NULL; END IF;

    entity_id := VALIDATION_ID_SEQ.NEXTVAL;
    INSERT INTO VALIDATION (
      ID,
      TYPE_VALIDATION_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      (SELECT id FROM type_validation WHERE code = 'CONTRAT_PAR_COMP'),
      ctr.intervenant_id,
      ctr.structure_id,
      GET_USER,
      GET_USER
    );
    UPDATE contrat SET validation_id = entity_id WHERE id = ADD_CONTRAT_VALIDATION.contrat_id;
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'validation', entity_id);
    RETURN entity_id;
  END;  
  
  FUNCTION DEL_CONTRAT_VALIDATION( contrat_id NUMERIC ) RETURN NUMERIC IS
    ctr contrat%rowtype;
  BEGIN
    ctr := GET_CONTRAT_BY_ID( contrat_id );
    
    IF ctr.validation_id IS NOT NULL THEN
      UPDATE contrat SET validation_id = NULL WHERE contrat_id = DEL_CONTRAT_VALIDATION.contrat_id;
      DELETE FROM validation WHERE id = ctr.validation_id;
    END IF;
    RETURN contrat_id;
  END;
  
  FUNCTION GET_TYPE_VALIDATION( code VARCHAR2 DEFAULT NULL ) RETURN type_validation%rowtype IS
    res type_validation%rowtype;
  BEGIN
    SELECT * INTO res FROM type_validation WHERE
      (OSE_DIVERS.LIKED( code, GET_TYPE_VALIDATION.code ) = 1 OR GET_TYPE_VALIDATION.code IS NULL) AND histo_destruction IS NULL AND ROWNUM = 1;
    RETURN res;
  END;
  
END OSE_TEST;

/
--------------------------------------------------------
--  DDL for Package Body OSE_TEST_FORMULE
--------------------------------------------------------

  CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_TEST_FORMULE" AS

  annee_id numeric;

  FUNCTION GET_ANNEE RETURN NUMERIC IS
  BEGIN
    IF annee_id IS NULL THEN
      annee_id := OSE_PARAMETRE.GET_ANNEE;
    END IF;
    RETURN annee_id;
  END;


  PROCEDURE ASSERT( condition BOOLEAN, MSG CLOB ) IS -- alias
  BEGIN
    OSE_TEST.ASSERT( condition, MSG );
  END;

  PROCEDURE ASSERT_GOOD_F_REFERENTIEL( params formule_referentiel%rowtype, MSG CLOB DEFAULT NULL, maj_idt BOOLEAN DEFAULT TRUE ) IS
    tested formule_referentiel%ROWTYPE;
    expected formule_referentiel%ROWTYPE;
    update_identified NUMERIC;
  BEGIN
    IF maj_idt THEN
      SELECT count(*) INTO update_identified FROM formule_referentiel_maj WHERE intervenant_id = params.INTERVENANT_ID AND annee_id = params.annee_id;
      ose_formule.MAJ_IDT_REFERENTIEL; -- PROVISOIRE
    END IF;
    tested := OSE_FORMULE.GET_REFERENTIEL( params.INTERVENANT_ID, params.annee_id );
    expected := params;

    -- calculs automatiques
    expected.service_du_modifie         := expected.service_du         + expected.service_du_modification;

    IF MSG IS NOT NULL THEN
      ose_test.echo (' -- ' || MSG || ' -- ');
    END IF;

    IF maj_idt THEN
      ASSERT( 1 = update_identified, 'Mise à jour identifiée' );
    END IF;

    IF tested.service_du = expected.service_du THEN
      ASSERT( true, 'service_du = ' || tested.service_du );
    ELSE
      ASSERT( false, 'service_du testé = ' || tested.service_du || ', attendu = ' || expected.service_du );
    END IF;

    IF tested.service_du_modification = expected.service_du_modification THEN
      ASSERT( true, 'service_du_modification = ' || tested.service_du_modification );
    ELSE
      ASSERT( false, 'service_du_modification testé = ' || tested.service_du_modification || ', attendu = ' || expected.service_du_modification );
    END IF;

    IF tested.service_referentiel = expected.service_referentiel THEN
      ASSERT( true, 'service_referentiel = ' || tested.service_referentiel );
    ELSE
      ASSERT( false, 'service_referentiel testé = ' || tested.service_referentiel || ', attendu = ' || expected.service_referentiel );
    END IF;

    IF tested.service_du_modifie = expected.service_du_modifie THEN
      ASSERT( true, 'service_du_modifie = ' || tested.service_du_modifie );
    ELSE
      ASSERT( false, 'service_du_modifie testé = ' || tested.service_du_modifie || ', attendu = ' || expected.service_du_modifie );
    END IF;

    IF MSG IS NOT NULL THEN
      ose_test.echo (' ');
    END IF;
  END;

  PROCEDURE ASSERT_GOOD_F_SERVICE( expected formule_service%rowtype, MSG CLOB DEFAULT NULL, maj_idt BOOLEAN DEFAULT TRUE ) IS
    tested formule_service%ROWTYPE;
    update_identified NUMERIC;
  BEGIN
    IF maj_idt THEN
      SELECT count(*) INTO update_identified FROM formule_service_maj WHERE service_id = expected.SERVICE_ID;
      ose_formule.MAJ_IDT_SERVICE; -- PROVISOIRE
    END IF;
    tested := OSE_FORMULE.GET_SERVICE( expected.SERVICE_ID );

    IF MSG IS NOT NULL THEN
      ose_test.echo (' -- ' || MSG || ' -- ');
    END IF;

    IF maj_idt THEN
      ASSERT( 1 = update_identified, 'Mise à jour identifiée' );
    END IF;

    IF tested.taux_fi = expected.taux_fi THEN
      ASSERT( true, 'taux_fi = ' || tested.taux_fi );
    ELSE
      ASSERT( false, 'taux_fi testé = ' || tested.taux_fi || ', attendu = ' || expected.taux_fi );
    END IF;

    IF tested.taux_fa = expected.taux_fa THEN
      ASSERT( true, 'taux_fa = ' || tested.taux_fa );
    ELSE
      ASSERT( false, 'taux_fa testé = ' || tested.taux_fa || ', attendu = ' || expected.taux_fa );
    END IF;
    
    IF tested.taux_fc = expected.taux_fc THEN
      ASSERT( true, 'taux_fc = ' || tested.taux_fc );
    ELSE
      ASSERT( false, 'taux_fc testé = ' || tested.taux_fc || ', attendu = ' || expected.taux_fc );
    END IF;    

    IF tested.ponderation_service_du = expected.ponderation_service_du THEN
      ASSERT( true, 'ponderation_service_du = ' || tested.ponderation_service_du );
    ELSE
      ASSERT( false, 'ponderation_service_du testé = ' || tested.ponderation_service_du || ', attendu = ' || expected.ponderation_service_du );
    END IF;

    IF tested.ponderation_service_compl = expected.ponderation_service_compl THEN
      ASSERT( true, 'ponderation_service_compl = ' || tested.ponderation_service_compl );
    ELSE
      ASSERT( false, 'ponderation_service_compl testé = ' || tested.ponderation_service_compl || ', attendu = ' || expected.ponderation_service_compl );
    END IF;
   
    IF nvl(tested.intervenant_id,0) = nvl(expected.intervenant_id,0) THEN
      ASSERT( true, 'intervenant_id = ' || tested.intervenant_id );
    ELSE
      ASSERT( false, 'intervenant_id testé = ' || tested.intervenant_id || ', attendu = ' || expected.intervenant_id );
    END IF;
    
    IF nvl(tested.annee_id,0) = nvl(expected.annee_id,0) THEN
      ASSERT( true, 'annee_id = ' || tested.annee_id );
    ELSE
      ASSERT( false, 'annee_id testé = ' || tested.annee_id || ', attendu = ' || expected.annee_id );
    END IF;
    
    IF MSG IS NOT NULL THEN
      ose_test.echo (' ');
    END IF;
  END;

  PROCEDURE ASSERT_GOOD_F_VOLUME_HORAIRE( expected formule_volume_horaire%rowtype, MSG CLOB DEFAULT NULL, maj_idt BOOLEAN DEFAULT TRUE ) IS
    tested formule_volume_horaire%ROWTYPE;
    update_identified NUMERIC;
  BEGIN
    IF maj_idt THEN
      SELECT count(*) INTO update_identified FROM formule_volume_horaire_maj WHERE volume_horaire_id = expected.volume_horaire_id;
      ose_formule.MAJ_IDT_VOLUME_HORAIRE; -- PROVISOIRE
    END IF;
    tested := OSE_FORMULE.GET_VOLUME_HORAIRE( expected.VOLUME_HORAIRE_ID );

    IF MSG IS NOT NULL THEN
      ose_test.echo (' -- ' || MSG || ' -- ');
    END IF;

    IF maj_idt THEN
      ASSERT( 1 = update_identified, 'Mise à jour identifiée' );
    END IF;

    IF tested.heures = expected.heures THEN
      ASSERT( true, 'heures = ' || tested.heures );
    ELSE
      ASSERT( false, 'heures testé = ' || tested.heures || ', attendu = ' || expected.heures );
    END IF;

    IF tested.taux_service_du = expected.taux_service_du THEN
      ASSERT( true, 'taux_service_du = ' || tested.taux_service_du );
    ELSE
      ASSERT( false, 'taux_service_du testé = ' || tested.taux_service_du || ', attendu = ' || expected.taux_service_du );
    END IF;

    IF tested.taux_service_compl = expected.taux_service_compl THEN
      ASSERT( true, 'taux_service_compl = ' || tested.taux_service_compl );
    ELSE
      ASSERT( false, 'taux_service_compl testé = ' || tested.taux_service_compl || ', attendu = ' || expected.taux_service_compl );
    END IF;
   
    IF nvl(tested.intervenant_id,0) = nvl(expected.intervenant_id,0) THEN
      ASSERT( true, 'intervenant_id = ' || tested.intervenant_id );
    ELSE
      ASSERT( false, 'intervenant_id testé = ' || tested.intervenant_id || ', attendu = ' || expected.intervenant_id );
    END IF;
    
    IF nvl(tested.annee_id,0) = nvl(expected.annee_id,0) THEN
      ASSERT( true, 'annee_id = ' || tested.annee_id );
    ELSE
      ASSERT( false, 'annee_id testé = ' || tested.annee_id || ', attendu = ' || expected.annee_id );
    END IF;
    
    IF nvl(tested.service_id,0) = nvl(expected.service_id,0) THEN
      ASSERT( true, 'service_id = ' || tested.service_id );
    ELSE
      ASSERT( false, 'service_id testé = ' || tested.service_id || ', attendu = ' || expected.service_id );
    END IF;
    
    IF nvl(tested.type_volume_horaire_id,0) = nvl(expected.type_volume_horaire_id,0) THEN
      ASSERT( true, 'type_volume_horaire_id = ' || tested.type_volume_horaire_id );
    ELSE
      ASSERT( false, 'type_volume_horaire_id testé = ' || tested.type_volume_horaire_id || ', attendu = ' || expected.type_volume_horaire_id );
    END IF;
    
    IF nvl(tested.etat_volume_horaire_id,0) = nvl(expected.etat_volume_horaire_id,0) THEN
      ASSERT( true, 'etat_volume_horaire_id = ' || tested.etat_volume_horaire_id );
    ELSE
      ASSERT( false, 'etat_volume_horaire_id testé = ' || tested.etat_volume_horaire_id || ', attendu = ' || expected.etat_volume_horaire_id );
    END IF;
    
    IF nvl(tested.type_intervention_id,0) = nvl(expected.type_intervention_id,0) THEN
      ASSERT( true, 'type_intervention_id = ' || tested.type_intervention_id );
    ELSE
      ASSERT( false, 'type_intervention_id testé = ' || tested.type_intervention_id || ', attendu = ' || expected.type_intervention_id );
    END IF;
    
    IF MSG IS NOT NULL THEN
      ose_test.echo (' ');
    END IF;
  END;

  PROCEDURE TEST_MODIFY_INTERVENANT( intervenant_id NUMERIC ) IS
    has_service NUMERIC;
    has_volume_horaire NUMERIC;
    service_id NUMERIC;
    volume_horaire_id NUMERIC;
  BEGIN
    ose_test.debut('OSE_TEST_FORMULE.TEST_MODIFY_INTERVENANT' );

    SELECT count(*) INTO has_service FROM formule_service
    WHERE
      intervenant_id = TEST_MODIFY_INTERVENANT.intervenant_id
      AND annee_id = get_annee AND rownum = 1;

    SELECT count(*) INTO has_volume_horaire FROM formule_volume_horaire
    WHERE
      intervenant_id = TEST_MODIFY_INTERVENANT.intervenant_id
      AND annee_id = get_annee AND rownum = 1;

    IF has_service > 0 THEN
      SELECT service_id INTO service_id FROM formule_service WHERE intervenant_id = TEST_MODIFY_INTERVENANT.intervenant_id AND annee_id = get_annee AND rownum = 1;
    END IF;
    IF has_volume_horaire > 0 THEN
      SELECT volume_horaire_id INTO volume_horaire_id FROM formule_volume_horaire WHERE intervenant_id = TEST_MODIFY_INTERVENANT.intervenant_id AND annee_id = get_annee AND rownum = 1;
    END IF;

    -- soft delete
    UPDATE intervenant SET histo_destruction = SYSDATE, histo_destructeur_id = ose_test.get_user WHERE id = intervenant_id;
    ASSERT_GOOD_F_REFERENTIEL( ose_formule.get_def_referentiel( intervenant_id, get_annee ), 'SOFT DELETE REFERENTIEL' );
    IF has_service > 0 THEN
      ASSERT_GOOD_F_SERVICE( ose_formule.get_def_service( service_id ), 'SOFT DELETE SERVICE');
    END IF;
    IF has_volume_horaire > 0 THEN
      ASSERT_GOOD_F_VOLUME_HORAIRE( ose_formule.get_def_volume_horaire( volume_horaire_id ), 'SOFT DELETE VOLUME HORAIRE');
    END IF;

    -- undelete
    UPDATE intervenant SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE id = intervenant_id;
    ASSERT_GOOD_F_REFERENTIEL( ose_formule.calc_referentiel( intervenant_id, get_annee ), 'UNDELETE REFERENTIEL' );
    IF has_service > 0 THEN
      ASSERT_GOOD_F_SERVICE( ose_formule.calc_service( service_id ), 'UNDELETE SERVICE');
    END IF;
    IF has_volume_horaire > 0 THEN
      ASSERT_GOOD_F_VOLUME_HORAIRE( ose_formule.calc_volume_horaire( volume_horaire_id ), 'UNDELETE VOLUME HORAIRE');
    END IF;

    ose_test.fin;

  END;


  PROCEDURE TEST_MODIFY_SERVICE_DU( intervenant_id NUMERIC ) IS
    ori formule_referentiel%rowtype;
    exp formule_referentiel%rowtype;
    sd OSE.service_du%ROWTYPE;
  BEGIN
    IF ose_divers.intervenant_has_privilege( intervenant_id, 'saisie_service_referentiel' ) = 0 THEN RETURN; END IF;

    ori := ose_formule.calc_referentiel( intervenant_id, get_annee );
    exp := ori;
    
    SELECT * INTO sd FROM service_du WHERE intervenant_id = TEST_MODIFY_SERVICE_DU.intervenant_id AND annee_id = get_annee and histo_destruction is null;

    ose_test.debut('OSE_TEST_FORMULE.TEST_MODIFY_SERVICE_DU' );
    
    -- test update --
    UPDATE service_du SET heures = heures + 1 WHERE id = sd.id;
    exp.service_du := ori.service_du + 1;
    ASSERT_GOOD_F_REFERENTIEL( exp, 'UPDATE' );

    -- test soft delete
    UPDATE service_du SET histo_destruction = SYSDATE, histo_destructeur_id = ose_test.get_user WHERE id = sd.id;
    exp.service_du := 0;
    ASSERT_GOOD_F_REFERENTIEL( exp, 'SOFT DELETE' );

    -- test undelete
    UPDATE service_du SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE id = sd.id;
    exp.service_du := ori.service_du + 1;
    ASSERT_GOOD_F_REFERENTIEL( exp, 'UNDELETE' );

    -- test delete --
    DELETE FROM OSE.SERVICE_DU WHERE ID = sd.id;
    exp.service_du := 0;
    ASSERT_GOOD_F_REFERENTIEL( exp, 'DELETE' );

    -- test insert --
    INSERT INTO SERVICE_DU (
      ID,
      INTERVENANT_ID,
      ANNEE_ID,
      HEURES,
      VALIDITE_DEBUT,
      VALIDITE_FIN,
      HISTO_CREATION,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATION,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      sd.ID,
      sd.INTERVENANT_ID,
      sd.ANNEE_ID,
      sd.HEURES,
      sd.VALIDITE_DEBUT,
      sd.VALIDITE_FIN,
      sd.HISTO_CREATION,
      sd.HISTO_CREATEUR_ID,
      sd.HISTO_MODIFICATION,
      sd.HISTO_MODIFICATEUR_ID
    );
    exp.service_du := ori.service_du;
    ASSERT_GOOD_F_REFERENTIEL( exp, 'INSERT' );
    
    ose_test.fin;
  END;


  PROCEDURE TEST_MODIFY_SERVICE_DU_MODIF( intervenant_id NUMERIC ) IS
    ori formule_referentiel%rowtype;
    exp formule_referentiel%rowtype;
    sdm_id NUMERIC;
  BEGIN
    ori := ose_formule.calc_referentiel( intervenant_id, get_annee );
    exp := ori;
  
    IF ose_divers.intervenant_has_privilege( intervenant_id, 'saisie_service_referentiel' ) = 0 THEN RETURN; END IF;
    
    ose_test.debut('OSE_TEST_FORMULE.TEST_MODIFY_SERVICE_DU_MODIF' );

    -- test insert --
    sdm_id := ose_test.add_modification_service_du( intervenant_id, get_annee, 9, OSE_TEST.GET_MOTIF_MODIFICATION_SERVICE( NULL, -1 ).id );
    exp.service_du_modification := ori.service_du_modification - 9;
    ASSERT_GOOD_F_REFERENTIEL( exp, 'INSERT' );
  
    -- test update heures --
    UPDATE modification_service_du SET heures = heures + 1 WHERE id = sdm_id;
    exp.service_du_modification := ori.service_du_modification - 9 - 1;
    ASSERT_GOOD_F_REFERENTIEL( exp, 'UPDATE' );
  
    -- test soft delete --
    UPDATE MODIFICATION_SERVICE_DU SET HISTO_DESTRUCTION = SYSDATE, HISTO_DESTRUCTEUR_ID = 1 WHERE ID = sdm_id;
    exp.service_du_modification := ori.service_du_modification;
    ASSERT_GOOD_F_REFERENTIEL( exp, 'SOFT DELETE' );
      
    -- test undelete
    UPDATE MODIFICATION_SERVICE_DU SET HISTO_DESTRUCTION = NULL, HISTO_DESTRUCTEUR_ID = NULL WHERE ID = sdm_id;
    exp.service_du_modification := ori.service_du_modification - 10;
    ASSERT_GOOD_F_REFERENTIEL( exp, 'UNDELETE' );
      
    -- test hard delete --
    DELETE FROM OSE.modification_service_du WHERE ID = sdm_id;
    exp.service_du_modification := ori.service_du_modification;
    ASSERT_GOOD_F_REFERENTIEL( exp, 'DELETE' );
      
    ose_test.fin;
  END;
  
  
  PROCEDURE TEST_MODIFY_SERVICE_REF( intervenant_id NUMERIC ) IS
    ori formule_referentiel%rowtype;
    exp formule_referentiel%rowtype;
    sr_id NUMERIC;
  BEGIN
    IF ose_divers.intervenant_has_privilege( intervenant_id, 'saisie_service_referentiel' ) = 0 THEN RETURN; END IF;
  
    ori := ose_formule.calc_referentiel( intervenant_id, get_annee );
    exp := ori;
    
    ose_test.debut('OSE_TEST_FORMULE.TEST_MODIFY_SERVICE_REF' );

    -- test insert --
    sr_id := OSE_TEST.ADD_SERVICE_REFERENTIEL( ose_test.get_fonction_referentiel().id, intervenant_id, ose_test.GET_STRUCTURE_ENS_BY_NIVEAU( 2 ).id, get_annee, 15 );
    exp.service_referentiel := ori.service_referentiel + 15;
    ASSERT_GOOD_F_REFERENTIEL( exp, 'INSERT' );
  
    -- test update heures --
    UPDATE SERVICE_REFERENTIEL SET heures = heures + 1 WHERE id = sr_id;
    exp.service_referentiel := ori.service_referentiel + 16;
    ASSERT_GOOD_F_REFERENTIEL( exp, 'UPDATE' );
  
    -- test soft delete --
    UPDATE SERVICE_REFERENTIEL SET HISTO_DESTRUCTION = SYSDATE, HISTO_DESTRUCTEUR_ID = 1 WHERE ID = sr_id;
    exp.service_referentiel := ori.service_referentiel;
    ASSERT_GOOD_F_REFERENTIEL( exp, 'SOFT DELETE' );
      
    -- test undelete
    UPDATE SERVICE_REFERENTIEL SET HISTO_DESTRUCTION = NULL, HISTO_DESTRUCTEUR_ID = NULL WHERE ID = sr_id;
    exp.service_referentiel := ori.service_referentiel + 16;
    ASSERT_GOOD_F_REFERENTIEL( exp, 'UNDELETE' );
      
    -- test hard delete --
    DELETE FROM OSE.SERVICE_REFERENTIEL WHERE ID = sr_id;
    exp.service_referentiel := ori.service_referentiel;
    ASSERT_GOOD_F_REFERENTIEL( exp, 'DELETE' );

    ose_test.fin;
  END;


  PROCEDURE TEST_MODIFY_MOTIF_MOD_SERV( intervenant_id NUMERIC ) IS
    ori formule_referentiel%rowtype;
    exp formule_referentiel%rowtype;
    sr_id NUMERIC;
    m_count NUMERIC;
    motif_id NUMERIC;
    heures FLOAT;
    multiplicateur FLOAT;
  BEGIN
    IF ose_divers.intervenant_has_privilege( intervenant_id, 'saisie_service_referentiel' ) = 0 THEN RETURN; END IF;

    SELECT
      count(*) INTO m_count
    FROM modification_service_du msd
    WHERE
      1 = ose_divers.comprise_entre( msd.histo_creation, msd.histo_destruction ) 
      AND msd.intervenant_id = TEST_MODIFY_MOTIF_MOD_SERV.intervenant_id;
    IF 0 = m_count THEN RETURN; END IF; -- intervenant non concerné

    SELECT motif_id, heures, multiplicateur INTO
      motif_id, heures, multiplicateur FROM (
    SELECT
      motif_id,
      SUM( heures ) heures,
      multiplicateur
    
    FROM
      modification_service_du msd
      JOIN motif_modification_service mss ON mss.id = msd.motif_id
    WHERE
      1 = ose_divers.comprise_entre( msd.histo_creation, msd.histo_destruction ) 
      AND msd.intervenant_id = TEST_MODIFY_MOTIF_MOD_SERV.intervenant_id
    GROUP BY
      motif_id, multiplicateur
    ) tmp WHERE rownum = 1;

    ori := ose_formule.calc_referentiel( intervenant_id, get_annee );
    exp := ori;

    ose_test.debut('OSE_TEST_FORMULE.TEST_MODIFY_MOTIF_MOD_SERV' );

    UPDATE motif_modification_service SET multiplicateur = multiplicateur * 2 WHERE id = motif_id;
    exp.service_du_modification := ori.service_du_modification + (heures * multiplicateur);
    ASSERT_GOOD_F_REFERENTIEL( exp, 'UPDATE' );

    ose_test.fin;
    
    UPDATE motif_modification_service SET multiplicateur = multiplicateur / 2 WHERE id = motif_id;
    ose_formule.MAJ_ALL_IDT;
  END;


  PROCEDURE TEST_MODIFY_SERVICE( intervenant_id NUMERIC ) IS
    element_pedagogique     ose.element_pedagogique%rowtype;
    fs_count                NUMERIC;
    upd_ep                  NUMERIC;
    upd_int                 NUMERIC;
    upd_et                  NUMERIC;
    fs                      formule_service%rowtype;
    fs_exp                  formule_service%rowtype;
    modulateur_id           NUMERIC;
    modulateur              ose.modulateur %rowtype;
    volume_horaire_id       NUMERIC;
  BEGIN
    IF OSE_DIVERS.INTERVENANT_HAS_PRIVILEGE( intervenant_id, 'saisie_service') = 0 THEN RETURN; END IF;

    element_pedagogique := ose_test.get_element_pedagogique();

    ose_test.debut('OSE_TEST_FORMULE.TEST_MODIFY_SERVICE' );

    -- insert
    fs.service_id := ose_test.add_service( intervenant_id, get_annee, element_pedagogique.id );
    fs := ose_formule.calc_service(fs.service_id);
    fs_exp := fs;
    ASSERT_GOOD_F_SERVICE( fs_exp, 'INSERT' );

    -- add vh
    volume_horaire_id := ose_test.add_volume_horaire(
      ose_test.get_type_volume_horaire('prevu').id,
      fs.service_id,
      COALESCE( element_pedagogique.periode_id, ose_test.get_periode().id ),
      ose_test.GET_TYPE_INTERVENTION_BY_ELEMT( element_pedagogique.id ).id,
      10
    );

    -- soft delete
    update service set histo_destructeur_id = ose_test.get_user, histo_destruction = sysdate where id = fs.service_id;
    select count(*) INTO fs_count FROM formule_service WHERE service_id = fs.service_id;
    ASSERT_GOOD_F_SERVICE( ose_formule.get_def_service(fs.service_id), 'SOFT DELETE SERVICE');
    ASSERT_GOOD_F_VOLUME_HORAIRE( ose_formule.get_def_volume_horaire(volume_horaire_id), 'SOFT DELETE VOLUME HORAIRE' );

    -- undelete
    update service set histo_destructeur_id = null, histo_destruction = null where id = fs.service_id;
    ASSERT_GOOD_F_SERVICE( fs_exp, 'UNDELETE SERVICE' );
    ASSERT_GOOD_F_VOLUME_HORAIRE( ose_formule.calc_volume_horaire(volume_horaire_id), 'UNDELETE VOLUME HORAIRE' );
    
    -- update element sans fc sans modulateur
    SELECT id, taux_fi, taux_fa, taux_fc into upd_ep, fs_exp.taux_fi, fs_exp.taux_fa, fs_exp.taux_fc FROM element_pedagogique 
    WHERE
      id <> TEST_MODIFY_SERVICE.element_pedagogique.id
      AND histo_destruction IS NULL
      AND taux_fc = 0
      AND NOT EXISTS(SELECT * FROM element_modulateur em WHERE element_id = element_pedagogique.id AND em.histo_destruction is null AND em.annee_id = get_annee)
      AND rownum = 1;
    update service set element_pedagogique_id = upd_ep WHERE id = fs.service_id;
    fs_exp.ponderation_service_du    := 1;
    fs_exp.ponderation_service_compl := 1;
    ASSERT_GOOD_F_SERVICE( fs_exp, 'UPDATE ELEMENT sans FC' );
    
    -- update element avec fc sans modulateur
    SELECT id, taux_fi, taux_fa, taux_fc into upd_ep, fs_exp.taux_fi, fs_exp.taux_fa, fs_exp.taux_fc FROM element_pedagogique 
    WHERE
      histo_destruction IS NULL
      AND taux_fc > 0
      AND NOT EXISTS(SELECT * FROM element_modulateur em WHERE element_id = element_pedagogique.id AND em.histo_destruction is null AND em.annee_id = get_annee)
      AND rownum = 1;
    update service set element_pedagogique_id = upd_ep WHERE id = fs.service_id;
    ASSERT_GOOD_F_SERVICE( fs_exp, 'UPDATE ELEMENT FC' );

    -- element avec modulateur
    SELECT ep.id, ep.taux_fi, ep.taux_fa, ep.taux_fc, M.PONDERATION_SERVICE_DU, M.PONDERATION_SERVICE_COMPL into upd_ep, fs_exp.taux_fi, fs_exp.taux_fa, fs_exp.taux_fc, fs_exp.ponderation_service_du, fs_exp.ponderation_service_compl
    FROM
      element_pedagogique ep
      JOIN element_modulateur em ON element_id = ep.id AND em.histo_destruction IS NULL AND em.annee_id = get_annee
      JOIN modulateur m ON m.id = em.modulateur_id AND m.histo_destruction IS NULL
    WHERE
      ep.histo_destruction IS NULL
      AND rownum = 1;
    update service set element_pedagogique_id = upd_ep WHERE id = fs.service_id;
    ASSERT_GOOD_F_SERVICE( fs_exp, 'UPDATE ELEMENT modulateur' );

    -- update intervenant
    SELECT id into upd_int FROM intervenant WHERE id <> intervenant_id AND OSE_DIVERS.INTERVENANT_HAS_PRIVILEGE(id,'saisie_service') = 1 AND histo_destruction IS NULL AND rownum = 1;
    update service set intervenant_id = upd_int WHERE id = fs.service_id;
    fs_exp.intervenant_id := upd_int;
    ASSERT_GOOD_F_SERVICE( fs_exp, 'UPDATE INTERVENANT' );
    update service set intervenant_id = TEST_MODIFY_SERVICE.intervenant_id WHERE id = fs.service_id;
    fs_exp.intervenant_id := intervenant_id;

    -- update etablissement
    IF ose_divers.intervenant_has_privilege( intervenant_id, 'saisie_service_exterieur' ) = 1 THEN
      upd_et := ose_test.get_etablissement().id;
      update service set element_pedagogique_id = null, etablissement_id = upd_et where id = fs.service_id;
      fs_exp.taux_fi := 1;
      fs_exp.taux_fa := 0;
      fs_exp.taux_fc := 0;
      fs_exp.ponderation_service_du    := 1;
      fs_exp.ponderation_service_compl := 1;
      ASSERT_GOOD_F_SERVICE( fs_exp, 'UPDATE ETABLISSEMENT' );
    END IF;

    -- delete
    delete from volume_horaire where id = volume_horaire_id;
    delete from service where id = fs.service_id;
    select count(*) INTO fs_count FROM formule_service WHERE service_id = fs.service_id;
    ASSERT( 0 = fs_count, 'DELETE');

    ose_test.fin;
  END;


  PROCEDURE TEST_MODIFY_ELEMENT( service_id NUMERIC ) IS
    fs                      formule_service%rowtype;
    fs_exp                  formule_service%rowtype;
    element_pedagogique_id  NUMERIC;
    ep     ose.element_pedagogique%rowtype;
  BEGIN
    fs := ose_formule.GET_SERVICE( service_id );
    fs_exp := fs;

    element_pedagogique_id := ose_test.get_service_by_id( service_id ).element_pedagogique_id;
    if element_pedagogique_id is null then return; end if;
    ep := ose_test.get_element_pedagogique_by_id( element_pedagogique_id );

    IF 0 = ose_divers.comprise_entre( ep.histo_creation, ep.histo_destruction ) THEN RETURN; END IF;

    ose_test.debut('OSE_TEST_FORMULE.TEST_MODIFY_ELEMENT' );

    -- update fc
    update element_pedagogique set taux_fc = 0.33, taux_fi = 0.33, taux_fa = 0.34 where id = element_pedagogique_id;
    fs_exp.taux_fi := 0.33;
    fs_exp.taux_fa := 0.34;
    fs_exp.taux_fc := 0.33;
    ASSERT_GOOD_F_SERVICE( fs_exp, 'UPDATE TAUX_FORMATION' );
    update element_pedagogique set taux_fc = ep.taux_fc, taux_fi = ep.taux_fi, taux_fa = ep.taux_fa where id = element_pedagogique_id;

    ose_test.fin;
  END;


  PROCEDURE TEST_MODIFY_MODULATEUR( service_id NUMERIC ) IS
    fs                      formule_service%rowtype;
    fs_exp                  formule_service%rowtype;
    element_pedagogique_id  NUMERIC;
    element_pedagogique     ose.element_pedagogique%rowtype;
    type_modulateur_id      NUMERIC;
    modulateur_id           NUMERIC;
    element_modulateur_id   NUMERIC;
  BEGIN
    element_pedagogique_id := ose_test.get_service_by_id( service_id ).element_pedagogique_id;
    if element_pedagogique_id is null then return; end if;

    fs := ose_formule.CALC_SERVICE( service_id );
    fs_exp := fs;

    -- création des données de test
    type_modulateur_id := ose_test.add_type_modulateur( 'MTEST', 'Type de modulateur de test', 1, 0 );
    modulateur_id := ose_test.add_modulateur(
      'MTESTM', 
      'Modulateur de test', 
      type_modulateur_id,
      1,
      1.12
    );


    ose_test.debut('OSE_TEST_FORMULE.TEST_MODIFY_MODULATEUR' );

    -- application du modulateur
    element_modulateur_id := ose_test.add_element_modulateur( element_pedagogique_id, modulateur_id, get_annee );
    fs_exp.ponderation_service_compl := fs.ponderation_service_compl * 1.12;
    ASSERT_GOOD_F_SERVICE( fs_exp, 'ADD MODULATEUR' );

    -- update
    UPDATE modulateur set ponderation_service_compl = 1.25 where id = modulateur_id;
    fs_exp.ponderation_service_compl := fs.ponderation_service_compl * 1.25;
    ASSERT_GOOD_F_SERVICE( fs_exp, 'UPDATE MODULATEUR' );

    delete from element_modulateur where id = element_modulateur_id;
    delete from modulateur where id = modulateur_id;
    delete from type_modulateur_structure where type_modulateur_id = TEST_MODIFY_MODULATEUR.type_modulateur_id;
    delete from type_modulateur where id = type_modulateur_id;

    fs_exp.ponderation_service_compl := fs.ponderation_service_compl;
    ASSERT_GOOD_F_SERVICE( fs_exp, 'AFTER DELETED MODULATEUR' );


    ose_test.fin;
  END;


  PROCEDURE TEST_MODIFY_VOLUME_HORAIRE( service_id NUMERIC ) IS
    fvh_ori           formule_volume_horaire%rowtype;
    fvh_exp           formule_volume_horaire%rowtype;
    s                 ose.service%rowtype;
    volume_horaire_id NUMERIC;
    c_id NUMERIC;
    v_id NUMERIC;
    vc_id NUMERIC;
    ti type_intervention%rowtype;
    pe_id NUMERIC;
    ori_etat NUMERIC;
    mnp_id NUMERIC;
  BEGIN
    ose_test.debut('OSE_TEST_FORMULE.TEST_MODIFY_VOLUME_HORAIRE' );

    s := ose_test.get_service_by_id( service_id );

    -- insert
    IF s.element_pedagogique_id IS NULL THEN
      pe_id := ose_test.get_periode().id;
      ti := ose_test.get_type_intervention('cm');
    ELSE
      pe_id := COALESCE( ose_test.get_element_pedagogique_by_id( s.element_pedagogique_id ).periode_id, ose_test.get_periode().id );
      ti := ose_test.GET_TYPE_INTERVENTION_BY_ELEMT( s.element_pedagogique_id );
    END IF;
    volume_horaire_id := ose_test.add_volume_horaire(
      ose_test.get_type_volume_horaire('prevu').id,
      service_id,
      pe_id,
      ti.id,
      10
    );

    fvh_ori := ose_formule.calc_volume_horaire( volume_horaire_id );
    fvh_exp := fvh_ori;

    ASSERT_GOOD_F_VOLUME_HORAIRE( fvh_exp, 'INSERT' );

    -- update heures
    update volume_horaire set heures = heures + 5 where id = volume_horaire_id;
    fvh_exp.heures := fvh_ori.heures + 5;
    ASSERT_GOOD_F_VOLUME_HORAIRE( fvh_exp, 'UPDATE HEURES' );

    -- soft delete
    update volume_horaire set histo_destruction = sysdate, histo_destructeur_id = ose_test.get_user where id = volume_horaire_id;
    ASSERT_GOOD_F_VOLUME_HORAIRE( ose_formule.get_def_volume_horaire( volume_horaire_id ), 'SOFT DELETE' );

    -- undelete
    update volume_horaire set histo_destruction = null, histo_destructeur_id = null where id = volume_horaire_id;
    ASSERT_GOOD_F_VOLUME_HORAIRE( fvh_exp, 'UNDELETE' );

    -- update motif non paiement
    IF 1 = ose_divers.intervenant_has_privilege( s.intervenant_id, 'saisie_motif_non_paiement' ) THEN
      mnp_id := ose_test.GET_MOTIF_NON_PAIEMENT().id;
      update volume_horaire set motif_non_paiement_id = mnp_id where id = volume_horaire_id;
      ASSERT_GOOD_F_VOLUME_HORAIRE( ose_formule.get_def_volume_horaire(volume_horaire_id), 'UPDATE MOTIF NON PAIEMENT NOT NULL' );

      update volume_horaire set motif_non_paiement_id = null, heures=12 where id = volume_horaire_id;
      fvh_exp.heures := 12;
      ASSERT_GOOD_F_VOLUME_HORAIRE( fvh_exp, 'UPDATE MOTIF NON PAIEMENT NULL and HEURES' );
    END IF;

    IF ose_divers.intervenant_est_permanent( s.intervenant_id ) = 0 AND s.etablissement_id = ose_parametre.get_etablissement THEN
      -- add validation
      v_id := ose_test.ADD_VALIDATION_VOLUME_HORAIRE( s.structure_ens_id, s.intervenant_id, volume_horaire_id, s.id );

      fvh_exp.etat_volume_horaire_id := ose_test.get_etat_volume_horaire('valide').id;
      ASSERT_GOOD_F_VOLUME_HORAIRE( fvh_exp, 'UPDATE VALIDATION VH validés' );

      -- add contrat
      c_id := ose_test.add_contrat( s.structure_ens_id, s.intervenant_id, volume_horaire_id, s.id );
      ASSERT_GOOD_F_VOLUME_HORAIRE( fvh_exp, 'ADD CONTRAT PROJET : un projet de contrat ne change rien', false );

      -- validation contrat
      vc_id := ose_test.add_contrat_validation( c_id );
      fvh_exp.etat_volume_horaire_id := ose_test.get_etat_volume_horaire('contrat_edite').id;
      ASSERT_GOOD_F_VOLUME_HORAIRE( fvh_exp, 'UPDATE CONTRAT EDITE' );

      -- signature contrat
      c_id := ose_test.SIGNATURE_CONTRAT( c_id );
      fvh_exp.etat_volume_horaire_id := ose_test.get_etat_volume_horaire('contrat_signe').id;
      ASSERT_GOOD_F_VOLUME_HORAIRE( fvh_exp, 'UPDATE CONTRAT SIGNE' );

      c_id := ose_test.DEL_CONTRAT_VALIDATION( c_id );
      update volume_horaire set contrat_id = NULL where id = volume_horaire_id;
      delete from contrat where id = c_id;
      OSE_TEST.DEL_VALIDATION_VOLUME_HORAIRE( s.structure_ens_id, s.intervenant_id, volume_horaire_id, s.id, v_id );
    END IF;

    -- delete
    delete from volume_horaire where id = volume_horaire_id;
    ASSERT_GOOD_F_VOLUME_HORAIRE( ose_formule.get_def_volume_horaire(volume_horaire_id), 'DELETE', false );

    ose_test.fin;
  END;


  PROCEDURE TEST_MODIFY_TYPE_INTERVENTION( volume_horaire_id NUMERIC ) IS
    fvh_ori           formule_volume_horaire%rowtype;
    fvh_exp           formule_volume_horaire%rowtype;
    vh                volume_horaire%rowtype;
    ti                type_intervention%rowtype;
  BEGIN
    vh := ose_test.get_volume_horaire( volume_horaire_id );

    IF vh.motif_non_paiement_id IS NOT NULL THEN RETURN; END IF;

    ti := ose_test.get_type_intervention_by_id( vh.type_intervention_id );

    fvh_ori := ose_formule.calc_volume_horaire( volume_horaire_id );
    fvh_exp := fvh_ori;

    ose_test.debut('OSE_TEST_FORMULE.TEST_MODIFY_TYPE_INTERVENTION' );

    -- update taux
    UPDATE type_intervention SET taux_hetd_service = 9.4, taux_hetd_complementaire = 9.5 WHERE id = ti.id;
    fvh_exp.taux_service_du := 9.4;
    fvh_exp.taux_service_compl := 9.5;
    ASSERT_GOOD_F_VOLUME_HORAIRE( fvh_exp, 'UPDATE taux VH' );

    ose_test.fin;
    
    UPDATE type_intervention SET taux_hetd_service = ti.taux_hetd_service, taux_hetd_complementaire = ti.taux_hetd_complementaire WHERE id = ti.id;
  END;
  

  PROCEDURE TEST_MODIFY_VALIDATION ( volume_horaire_id NUMERIC ) IS
    fvh_ori formule_volume_horaire%rowtype;
    fvh_exp formule_volume_horaire%rowtype;
    vh      volume_horaire%rowtype;
    s       service%rowtype;
    c       contrat%rowtype;
    v_id    NUMERIC;
  BEGIN
    vh := ose_test.get_volume_horaire( volume_horaire_id );
    
    IF vh.motif_non_paiement_id IS NOT NULL THEN RETURN; END IF;
    
    s  := ose_test.get_service_by_id ( vh.service_id );
    IF vh.contrat_id IS NOT NULL THEN
      c  := ose_test.get_contrat_by_id ( vh.contrat_id );
      IF c.histo_destruction IS NOT NULL THEN
        c.id := null; -- pas de contrat s'il est historisé!!
      END IF;
    END IF;
    fvh_ori := ose_formule.calc_volume_horaire( volume_horaire_id );
    fvh_exp := fvh_ori;
    
    ose_test.debut('OSE_TEST_FORMULE.TEST_MODIFY_VALIDATION' );

    -- validation du volume horaire
    -- ajout (si nécessaire )

    IF c.id IS NULL AND 0 = ose_divers.volume_horaire_valide(volume_horaire_id) THEN -- validation de volume horaire

      -- insert
      v_id := ose_test.ADD_VALIDATION_VOLUME_HORAIRE( COALESCE( s.structure_ens_id, s.structure_aff_id ), s.intervenant_id, volume_horaire_id, s.id );
      IF fvh_ori.etat_volume_horaire_id = ose_test.get_etat_volume_horaire('saisi').id THEN -- si pas encore validé, alors devient validé !!
        fvh_exp.etat_volume_horaire_id := ose_test.get_etat_volume_horaire('valide').id;
      END IF;
      ASSERT_GOOD_F_VOLUME_HORAIRE( fvh_exp, 'INSERT pour volume_horaire' );
  
      -- soft delete
      UPDATE validation SET histo_destruction = SYSDATE, histo_destructeur_id = ose_test.get_user WHERE id = v_id;
      fvh_exp.etat_volume_horaire_id := fvh_ori.etat_volume_horaire_id;
      ASSERT_GOOD_F_VOLUME_HORAIRE( fvh_exp, 'SOFT DELETE pour volume_horaire' );
  
      -- undelete
      UPDATE validation SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE id = v_id;
      IF fvh_ori.etat_volume_horaire_id = ose_test.get_etat_volume_horaire('saisi').id THEN -- si pas encore validé, alors devient validé !!
        fvh_exp.etat_volume_horaire_id := ose_test.get_etat_volume_horaire('valide').id;
      END IF;
      ASSERT_GOOD_F_VOLUME_HORAIRE( fvh_exp, 'UNDELETE pour volume_horaire' );
  
      -- delete
      ose_test.DEL_VALIDATION_VOLUME_HORAIRE( s.structure_ens_id, s.intervenant_id, volume_horaire_id, s.id, v_id );
      fvh_exp.etat_volume_horaire_id := fvh_ori.etat_volume_horaire_id;
      ASSERT_GOOD_F_VOLUME_HORAIRE( fvh_exp, 'DELETE pour volume_horaire' );

    ELSIF c.id IS NOT NULL AND c.validation_id IS NULL THEN -- validation de contrat si pas déjà validé

      -- insert
      v_id := ose_test.ADD_CONTRAT_VALIDATION( c.id );
      fvh_exp.etat_volume_horaire_id := ose_test.get_etat_volume_horaire( CASE WHEN c.date_retour_signe IS NULL THEN 'contrat_edite' ELSE 'contrat_signe' END ).id;
      ASSERT_GOOD_F_VOLUME_HORAIRE( fvh_exp, 'INSERT pour contrat' );
      
      -- soft delete
      UPDATE validation SET histo_destruction = SYSDATE, histo_destructeur_id = ose_test.get_user WHERE id = v_id;
      fvh_exp.etat_volume_horaire_id := fvh_ori.etat_volume_horaire_id;
      ASSERT_GOOD_F_VOLUME_HORAIRE( fvh_exp, 'SOFT DELETE pour contrat' );
      
      -- undelete
      UPDATE validation SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE id = v_id;
      fvh_exp.etat_volume_horaire_id := ose_test.get_etat_volume_horaire( CASE WHEN c.date_retour_signe IS NULL THEN 'contrat_edite' ELSE 'contrat_signe' END ).id;
      ASSERT_GOOD_F_VOLUME_HORAIRE( fvh_exp, 'UNDELETE pour contrat' );
      
      -- delete
      c.id := ose_test.DEL_CONTRAT_VALIDATION( c.id );
      fvh_exp.etat_volume_horaire_id := fvh_ori.etat_volume_horaire_id;
      ASSERT_GOOD_F_VOLUME_HORAIRE( fvh_exp, 'DELETE pour contrat' );
    END IF;

    ose_test.fin;
  END;


  PROCEDURE TEST_MODIFY_CONTRAT ( volume_horaire_id NUMERIC ) IS
    fvh_ori formule_volume_horaire%rowtype;
    fvh_exp formule_volume_horaire%rowtype;
    vh      volume_horaire%rowtype;
    s       service%rowtype;
    c_id    NUMERIC;
    v_id    NUMERIC;
  BEGIN
    vh := ose_test.get_volume_horaire( volume_horaire_id );

    IF vh.motif_non_paiement_id IS NOT NULL THEN RETURN; END IF;

    s  := ose_test.get_service_by_id ( vh.service_id );

    fvh_ori := ose_formule.calc_volume_horaire( volume_horaire_id );
    fvh_exp := fvh_ori;
  
    IF vh.contrat_id IS NOT NULL THEN RETURN; END IF; -- pas de test si un contrat est déjà présent!!
    IF 0 = ose_divers.VOLUME_HORAIRE_VALIDE( volume_horaire_id ) THEN RETURN; END IF; -- Pas de contractualisation sur un volume horaire non validé
  
    ose_test.debut('OSE_TEST_FORMULE.TEST_MODIFY_CONTRAT' );

    -- insert
    c_id := ose_test.add_contrat( COALESCE(s.structure_ens_id,s.structure_aff_id), s.intervenant_id, volume_horaire_id, s.id );
    v_id := ose_test.ADD_CONTRAT_VALIDATION( c_id );
    fvh_exp.etat_volume_horaire_id := ose_test.get_etat_volume_horaire( 'contrat_edite' ).id;
    ASSERT_GOOD_F_VOLUME_HORAIRE( fvh_exp, 'INSERT pour contrat' );

    -- soft delete
    UPDATE contrat SET histo_destruction = SYSDATE, histo_destructeur_id = ose_test.get_user WHERE id = c_id;
    fvh_exp.etat_volume_horaire_id := fvh_ori.etat_volume_horaire_id;
    ASSERT_GOOD_F_VOLUME_HORAIRE( fvh_exp, 'SOFT DELETE pour contrat' );

    -- undelete
    UPDATE contrat SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE id = c_id;
    fvh_exp.etat_volume_horaire_id := ose_test.get_etat_volume_horaire( 'contrat_edite' ).id;
    ASSERT_GOOD_F_VOLUME_HORAIRE( fvh_exp, 'UNDELETE pour contrat' );

    -- signature
    c_id := ose_test.SIGNATURE_CONTRAT( c_id );
    fvh_exp.etat_volume_horaire_id := ose_test.get_etat_volume_horaire( 'contrat_signe' ).id;
    ASSERT_GOOD_F_VOLUME_HORAIRE( fvh_exp, 'SIGNATURE pour contrat' );

    -- delete
    c_id := ose_test.DEL_CONTRAT_VALIDATION( c_id );
    update volume_horaire set contrat_id = null where contrat_id = c_id;
    delete from contrat where id = c_id;
    fvh_exp.etat_volume_horaire_id := fvh_ori.etat_volume_horaire_id;
    ASSERT_GOOD_F_VOLUME_HORAIRE( fvh_exp, 'DELETE pour contrat' );

    ose_test.fin;
  END;

END OSE_TEST_FORMULE;

/
--------------------------------------------------------
--  DDL for Package Body UCBN_LDAP
--------------------------------------------------------

  CREATE OR REPLACE PACKAGE BODY "OSE"."UCBN_LDAP" AS

--===================================================================
--===================================================================
-- version()
--===================================================================
FUNCTION version RETURN VARCHAR2 IS
BEGIN
  RETURN ' 0.5.0 (2013-09-24) ';
END version;


--===================================================================
--===================================================================
-- free()
--===================================================================
FUNCTION free RETURN NUMBER IS
  l_retval PLS_INTEGER ;
BEGIN
  BEGIN
    l_retval := DBMS_LDAP.unbind_s(ld => ldap_sess);
    RETURN l_retval ;
  EXCEPTION
    WHEN DBMS_LDAP.INVALID_SESSION THEN
    RETURN NULL ;
  END;
END free ;


--===================================================================
--===================================================================
-- ldap_connect()
--===================================================================
FUNCTION ldap_connect RETURN NUMBER IS
  ldap_host   VARCHAR2(256 char) := 'ldap.unicaen.fr';
  ldap_port   VARCHAR2(3 char)   := '389';
  ldap_user   VARCHAR2(256 char) := 'uid=authinfo,ou=system,dc=unicaen,dc=fr' ;
  ldap_passwd VARCHAR2(30 char)  := 'iLnDfAoP2010' ;
  ldap_base   VARCHAR2(256 char) := 'ou=people,dc=unicaen,dc=fr';

  l_retval  PLS_INTEGER ;

  resultat  VARCHAR2(1024 char) := NULL ;

BEGIN
  -- Ouverture de connexion
  BEGIN
  ldap_sess := DBMS_LDAP.init(hostname => ldap_host,
                              portnum  => ldap_port) ;
  EXCEPTION
    WHEN DBMS_LDAP.INIT_FAILED THEN
      RETURN 1 ;
  END;


  -- Authentification
  BEGIN
  l_retval := DBMS_LDAP.simple_bind_s(ld     => ldap_sess,
                                      dn     => ldap_user,
                                      passwd => ldap_passwd) ;
  EXCEPTION
    WHEN DBMS_LDAP.GENERAL_ERROR THEN
      l_retval := DBMS_LDAP.unbind_s(ld => ldap_sess);
      RETURN 2 ;
    WHEN DBMS_LDAP.INVALID_SESSION THEN
      l_retval := DBMS_LDAP.unbind_s(ld => ldap_sess);
      RETURN 2 ;
  END;
  RETURN 0 ;
END ldap_connect;






--===================================================================
--===================================================================
-- get(filtre, attribut)
--===================================================================
FUNCTION get(filtre IN VARCHAR2, attribut IN VARCHAR2, v_multi IN VARCHAR2 DEFAULT 'N', a_multi OUT ARRAY_STR) RETURN VARCHAR2 IS
  ldap_base   VARCHAR2(256 char) := 'ou=people,dc=unicaen,dc=fr';
  l_retval  PLS_INTEGER ;
  l_attrs   DBMS_LDAP.string_collection ;
  l_message DBMS_LDAP.message ;
  l_entry   DBMS_LDAP.message ;
  l_attr_name VARCHAR2(256 char) ;
  l_ber_element  DBMS_LDAP.ber_element;
  l_vals         DBMS_LDAP.string_collection;

  i         PLS_INTEGER ;
  nb_res    PLS_INTEGER ;
  probleme  EXCEPTION ;
  resultat  VARCHAR2(1024 char) := NULL ;

  elapsed_since_used NUMBER ;

BEGIN

  -- On regarde depuis combien de temps la session n'a pas ete utilisee
  elapsed_since_used:= to_number( to_char( SYSDATE,'yyyymmddhh24miss' ) ) - last_used ;
  last_used := to_number( to_char( SYSDATE,'yyyymmddhh24miss' ) ) ;

  -- Si c'est trop vieux, on se reconnecte
  IF elapsed_since_used > 10 THEN
    l_retval := free() ;
  END IF ;

  -- Si on n'est pas connecte:
  IF ldap_sess IS NULL THEN
    DBMS_OUTPUT.PUT_LINE('Reconnexion au serveur LDAP...');
    l_retval := ldap_connect() ;
    CASE l_retval
      WHEN 1 THEN RETURN '#Err 0010';
      WHEN 2 THEN RETURN '#Err 0011';
      ELSE NULL;
    END CASE;
  END IF ;

  -- On cherche le mail seulement
  l_attrs(1) := attribut ;
  BEGIN
  l_retval := DBMS_LDAP.search_s(ld       => ldap_sess,
                                 base     => ldap_base,
                                 scope    => DBMS_LDAP.SCOPE_SUBTREE,
                                 filter   => filtre,
                                 attrs    => l_attrs,
                                 attronly => 0,
                                 res      => l_message) ;
  EXCEPTION
    WHEN DBMS_LDAP.GENERAL_ERROR THEN
      DBMS_OUTPUT.PUT_LINE('Erreur: '||SQLERRM);
      RETURN '#Err 0020' ;
    WHEN DBMS_LDAP.INVALID_SESSION THEN
      RETURN '#Err 0021' ;
    WHEN DBMS_LDAP.invalid_search_scope THEN
      RETURN '#Err 0022' ;
  END;


  BEGIN
  nb_res := DBMS_LDAP.count_entries(ld => ldap_sess, msg => l_message) ;
  EXCEPTION
    WHEN DBMS_LDAP.INVALID_SESSION THEN
      RETURN '#Err 0030' ;
    WHEN DBMS_LDAP.INVALID_MESSAGE THEN
      RETURN '#Err 0031' ;
    WHEN DBMS_LDAP.count_entry_error THEN
      RETURN '#Err 0032' ;
  END;

  IF nb_res < 1 THEN
    -- Pas besoin de fermer la connexion puisqu'on en utilise qu'une...
    -- l_retval := DBMS_LDAP.unbind_s(ld => ldap_sess);
    -- RETURN '#Err 0033'; -- On retourne NULL depuis la 0.4.1
    RETURN NULL ;
  END IF;

  -- Les entrees retournees
  BEGIN
  l_entry := DBMS_LDAP.first_entry(ld => ldap_sess, msg => l_message);

  EXCEPTION
    WHEN DBMS_LDAP.INVALID_SESSION THEN
      RETURN '#Err 0034' ;
    WHEN DBMS_LDAP.INVALID_MESSAGE THEN
      RETURN '#Err 0035' ;
  END;


  WHILE l_entry IS NOT NULL LOOP
    -- Tous les attributs de l'entree:
    BEGIN
    l_attr_name := DBMS_LDAP.first_attribute(ld        => ldap_sess,
                                             ldapentry => l_entry,
                                             ber_elem  => l_ber_element);
    EXCEPTION
      WHEN DBMS_LDAP.INVALID_SESSION THEN
        RETURN '#Err 0040' ;
      WHEN DBMS_LDAP.INVALID_MESSAGE THEN
        RETURN '#Err 0041' ;
    END;

    WHILE l_attr_name IS NOT NULL LOOP
      -- Les valeurs de cet attribut
      BEGIN
      l_vals := DBMS_LDAP.get_values (ld        => ldap_sess,
                                      ldapentry => l_entry,
                                      attr      => l_attr_name);
      EXCEPTION
        WHEN DBMS_LDAP.INVALID_SESSION THEN
          RETURN '#Err 0044' ;
        WHEN DBMS_LDAP.INVALID_MESSAGE THEN
          RETURN '#Err 0045' ;
      END;

      -- On ne retourne que la premiere valeur si mono-value
      -- Sinon, on retourne le tableau a_multi
      IF v_multi = 'N' THEN
        resultat := l_vals(l_vals.FIRST) ;
      ELSE
        a_multi := ARRAY_STR() ; -- Initialisation du tableau
        i := 0 ; -- tableau commence a 1 (d'ou i++ a l'entree du FOR)
        FOR v IN l_vals.FIRST .. l_vals.LAST LOOP
          i := i + 1 ;
          a_multi.extend ;
          a_multi(i) := l_vals(v) ;
        END LOOP ;
        resultat := '#Err Multi-value: '||i ;
      END IF;

      EXIT WHEN resultat IS NOT NULL ;

      -- Attribut suivant
      BEGIN
      l_attr_name := DBMS_LDAP.next_attribute(ld        => ldap_sess,
                                              ldapentry => l_entry,
                                              ber_elem  => l_ber_element);
      EXCEPTION
        WHEN DBMS_LDAP.INVALID_SESSION THEN
          RETURN '#Err 0042' ;
        WHEN DBMS_LDAP.INVALID_MESSAGE THEN
          RETURN '#Err 0043' ;
      END;
    END LOOP ; -- LOOP Fin des attributs
    IF l_ber_element IS NOT NULL THEN
      DBMS_LDAP.ber_free(l_ber_element, 0) ;
    END IF ;
    EXIT WHEN resultat IS NOT NULL ;
    BEGIN
    l_entry := DBMS_LDAP.next_entry(ld  => ldap_sess,
                                    msg => l_entry);
    EXCEPTION
      WHEN DBMS_LDAP.INVALID_SESSION THEN
        RETURN '#Err 0036' ;
      WHEN DBMS_LDAP.INVALID_MESSAGE THEN
        RETURN '#Err 0037' ;
    END;
  END LOOP ; -- LOOP Fin des entrees

  -- Liberation de la memoire
  --l_retval := DBMS_LDAP.msgfree(l_message) ;
  IF l_entry IS NOT NULL THEN
    l_retval := DBMS_LDAP.msgfree(l_entry) ;
  END IF ;

  -- Pas de deconnexion (on la reutilisera)
  --l_retval := DBMS_LDAP.unbind_s(ld => l_session);
  --DBMS_OUTPUT.PUT_LINE('L_RETVAL: ' || l_retval);

  RETURN resultat ;

END get ;


--===================================================================
--===================================================================
-- uid2mail(ldap_uid)
--===================================================================
FUNCTION uid2mail(ldap_uid IN VARCHAR2) RETURN VARCHAR2 IS
BEGIN
  RETURN get('uid='||ldap_uid, 'mail', 'N', a_multi) ;
END uid2mail;


--===================================================================
--===================================================================
-- hid2mail(harpege_uid)
--===================================================================
FUNCTION hid2mail(harpege_uid IN NUMBER) RETURN VARCHAR2 IS
BEGIN
  RETURN get('uid=p'||to_char(harpege_uid,'FM00000000'), 'mail', 'N', a_multi) ;
END hid2mail;


--===================================================================
--===================================================================
-- etu2mail(code_etu)
--===================================================================
FUNCTION etu2mail(code_etu IN NUMBER) RETURN VARCHAR2 IS
BEGIN
  RETURN get('uid=e'||to_char(code_etu,'FM00000000'), 'mail', 'N', a_multi) ;
END etu2mail;


--===================================================================
--===================================================================
-- uid2alias(ldap_uid)
--===================================================================
FUNCTION uid2alias(ldap_uid IN VARCHAR2) RETURN VARCHAR2 IS
BEGIN
  RETURN get('uid='||ldap_uid, 'supannAliasLogin', 'N', a_multi) ;
END uid2alias;

--===================================================================
--===================================================================
-- hid2alias(harpege_uid)
--===================================================================
FUNCTION hid2alias(harpege_uid IN NUMBER) RETURN VARCHAR2 IS
BEGIN
  RETURN get('uid=p'||to_char(harpege_uid,'FM00000000'), 'supannAliasLogin', 'N', a_multi) ;
END hid2alias;


--===================================================================
--===================================================================
-- uid2cn(ldap_uid)
--===================================================================
FUNCTION uid2cn(ldap_uid IN VARCHAR2) RETURN VARCHAR2 IS
BEGIN
  RETURN get('uid='||ldap_uid, 'cn', 'N', a_multi) ;
END uid2cn;


--===================================================================
--===================================================================
-- uid2sn(ldap_uid)
--===================================================================
FUNCTION uid2sn(ldap_uid IN VARCHAR2) RETURN VARCHAR2 IS
BEGIN
  RETURN get('uid='||ldap_uid, 'sn', 'N', a_multi) ;
END uid2sn;

--===================================================================
--===================================================================
-- uid2givenname(ldap_uid)
--===================================================================
FUNCTION uid2givenname(ldap_uid IN VARCHAR2) RETURN VARCHAR2 IS
BEGIN
  RETURN get('uid='||ldap_uid, 'givenname', 'N', a_multi) ;
END uid2givenname;

--===================================================================
--===================================================================
-- uid2gn(ldap_uid)
--===================================================================
FUNCTION uid2gn(ldap_uid IN VARCHAR2) RETURN VARCHAR2 IS
BEGIN
  RETURN get('uid='||ldap_uid, 'givenname', 'N', a_multi)||' '||get('uid='||ldap_uid, 'sn', 'N', a_multi) ;
END uid2gn;



--===================================================================
--===================================================================
-- hidIsPrimaryTeacher(harpege_uid)
--
-- Verifie eduPersonPrimaryAffiliation
--===================================================================
FUNCTION hidIsPrimaryTeacher(harpege_uid IN NUMBER) RETURN VARCHAR2 IS
  l_resultat VARCHAR2(1024 char) := NULL ;
  isTeacher VARCHAR2(1) := 'N' ;
BEGIN
  l_resultat := get('uid=p'||to_char(harpege_uid,'FM00000000'), 'eduPersonPrimaryAffiliation', 'N', a_multi) ;

  IF l_resultat IS NULL THEN
    RETURN NULL ;
  END IF ;

  IF SUBSTR( l_resultat, 1, 4 ) = '#Err' THEN
    RETURN l_resultat ;
  END IF ;

  IF l_resultat = 'teacher' THEN
    isTeacher := 'O' ;
  END IF ;

  RETURN isTeacher ;

END hidIsPrimaryTeacher;


--===================================================================
--===================================================================
-- hidIsTeacher(harpege_uid)
--
-- Retourne NULL si non trouve,
--             O si flag teacher ou faculty
--             N si pas ce flag.
--===================================================================
FUNCTION hidIsTeacher(harpege_uid IN NUMBER) RETURN VARCHAR2 IS
  l_resultat VARCHAR2(1024 char) := NULL ;
  isTeacher VARCHAR2(1) := 'N' ;
BEGIN
  l_resultat := get('uid=p'||to_char(harpege_uid,'FM00000000'), 'eduPersonAffiliation', 'Y', a_multi) ;
  -- ici, l_resultat ne contient que '#Err Multi-value: i'

  -- On verifie qu'on a bien obtenu des resultats
  IF l_resultat IS NULL OR SUBSTR( l_resultat, 1, 18) != '#Err Multi-value: ' THEN
    RETURN l_resultat ;
  END IF ;

  -- Le Nombre de resultats
  IF a_multi.count = 0 THEN
    RETURN NULL ;
  END IF ;

  FOR i IN 1 .. a_multi.count LOOP
    IF a_multi(i)='teacher' THEN
      isTeacher := 'O' ;
    END IF ;
  END LOOP ;

  RETURN isTeacher ;

END hidIsTeacher;




END ucbn_ldap ;

/
--------------------------------------------------------
--  DDL for Procedure UPDATEPJ
--------------------------------------------------------
set define off;

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
