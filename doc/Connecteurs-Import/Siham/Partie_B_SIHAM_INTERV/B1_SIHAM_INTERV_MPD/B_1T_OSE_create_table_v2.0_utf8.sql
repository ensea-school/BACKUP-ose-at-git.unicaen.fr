/* ====================================================================================================
	# Detail du connecteur PARTIE B/ SIHAM_INTERV : les intervenants existant dans SIHAM - Avec user OSE
   
	PHASE1 : EXTRACTION_SIHAM CREATION SCHEMA TABLES INTERMEDIAIRES POUR OSE : nommage UM_<nom table>
	
	CREATE TABLES : pour une 1ere installation
	
	--- types d'objets -----------------
	T_UM_ENREG_STATUT 

	--- tables ------------------------- 
	OSE.UM_INTERVENANT
	OSE.UM_ADRESSE_INTERVENANT  
	OSE.UM_TRANSFERT_INDIVIDU  		-- dossiers selectionnes pour transfert siham vers ose/prose
	OSE.UM_TRANSFERT_FORCE			-- dossiers pour lesquels il faut forcer la prochaine synchro
	OSE.UM_SYNCHRO_A_VALIDER		-- dossiers avec changement de statut en cours d'annee
	OSE.UM_STATUT_INTERVENANT
	------------------------------------
	
	-- v1.8 02/12/2019 - MYP : UM_STRUCTURE renommage tem_manu en eotp_DU_defaut + eotp_DN_defaut	 
    -- v2.0 12/2020 à 03/2021 - MYP : adaptations V15 pour adresses
=====================================================================================================*/

/*------------- TYPES_OBJETS ------------------------------*/
--- MULTI-STATUT : creation type d objet pour UM_AFFECTE_STATUT (une seule fonction) ------
create or replace type T_UM_ENREG_STATUT is object
(
    ID 						number(9)			-- STATUT_ID
    ,CODE_STATUT 			varchar2(20)		-- CODE_STATUT
	,CODE_TYPE_INTERVENANT	varchar2(1)			-- CODE_TYPE_INTERVENANT P = PERM / E = IE
    ,DATE_DEB_STATUT		date				-- DATE debut statut
	,DATE_FIN_STATUT		date				-- DATE fin statut
	,NB_H_MCE				number(8,2)			-- Nombre d'heures avec Mission d'enseignement
);


/*------------- UM_INTERVENANT ------------------------------*/
CREATE TABLE OSE.UM_INTERVENANT
(
  ID						NUMBER(9)	default  0,		
  CIVILITE_ID				NUMBER(9)	default  0,
  NOM_USUEL					VARCHAR2(60  CHAR),
  PRENOM					VARCHAR2(60  CHAR),
  NOM_PATRONYMIQUE			VARCHAR2(60  CHAR),
  DATE_NAISSANCE			DATE,
  LIBRE1					VARCHAR2(5  CHAR),  		-- v2.0 ex VILLE_NAISSANCE_CODE_INSEE
  VILLE_NAISSANCE_LIBELLE	VARCHAR2(60  CHAR),
  TEL_PRO					VARCHAR2(20  CHAR),
  TEL_MOBILE				VARCHAR2(20  CHAR),
  EMAIL_PRO					VARCHAR2(255  CHAR),		-- v2.0 ex EMAIL
  STATUT_ID					NUMBER(9),	
  STRUCTURE_ID				NUMBER(9),	
  DISCIPLINE_ID				NUMBER(9),	
  SOURCE_ID					NUMBER(9),	
  SOURCE_CODE				VARCHAR2(100  CHAR),
  NUMERO_INSEE				VARCHAR2(13  CHAR),
  NUMERO_INSEE_CLE			VARCHAR2(2  CHAR),
  NUMERO_INSEE_PROVISOIRE	NUMBER(20),	
  IBAN						VARCHAR2(50  CHAR),
  BIC						VARCHAR2(20  CHAR),
  LIBRE2					NUMBER(1),					-- v2.0 ex PREMIER_RECRUTEMENT
  ANNEE_ID					NUMBER(9),	
  GRADE_ID					NUMBER(9),	
  MONTANT_INDEMNITE_FC		FLOAT,	
  CRITERE_RECHERCHE			VARCHAR2(255  CHAR),
  CODE						VARCHAR2(60  CHAR),
  SUPANN_EMP_ID				VARCHAR2(60  CHAR),
  PAYS_NAISSANCE_ID			NUMBER(9),	
  DEP_NAISSANCE 			VARCHAR2(3	CHAR),
  PAYS_NATIONALITE_ID		NUMBER(9),
  W_STATUT_PIP				VARCHAR2(8  CHAR),
  W_TEM_ENSEIG				VARCHAR2(1  CHAR),
  W_GROUPE_HIE				VARCHAR2(2  CHAR),
  W_CODE_EMPLOI				VARCHAR2(10  CHAR),
  W_LIB_EMPLOI				VARCHAR2(60  CHAR),
  W_TYPE_FONCTION			VARCHAR2(4  CHAR),
  W_FONCTION				VARCHAR2(4  CHAR),
  W_STRUCTURE_UO			VARCHAR2(10  CHAR),
  W_POSITION				VARCHAR2(10  CHAR),
  W_LIB_POSITION			VARCHAR2(60  CHAR),
  DATE_DEPART_DEF			DATE,
  CAUSE_DEPART_DEF			VARCHAR2(45  CHAR),
  DATE_HORODATAGE			DATE,
  EMPLOYEUR					VARCHAR2(255 CHAR),
  VILLE_SERVICE_RECTORAT	VARCHAR2(255 CHAR),
  TYPE_EMPLOYEUR 			VARCHAR2(10 CHAR),
  TYPE_SPECIALITE			VARCHAR2(3 CHAR),
  SPECIALITE				VARCHAR2(8 CHAR),
  RECRUTEMENT				VARCHAR2(50  CHAR),
  QUOTITE_TEMPS_PARTAGE		NUMBER(3),
  OREC_CODE_UO				VARCHAR2(10  CHAR),
  NB_H_SERVICE_RECTORAT 	NUMBER(8,2),
  OREC_LIB_CATEGORIE		VARCHAR2(32  CHAR),
  W_NB_HEURE_MCE			NUMBER(8,2),
  RIB_HORS_SEPA 			NUMBER(1);					-- v2.0
  EMPLOYEUR_ID				NUMBER(9);					-- v2.0
  DATE_DEB_STATUT			DATE,						-- v2.0
  DATE_FIN_STATUT			DATE,						-- v2.0
  DATE_HORODATAGE_STATUT	DATE,						-- v2.0
  CONSTRAINT PK_ID_UM_INTERV PRIMARY KEY (ID),
  CONSTRAINT UK_UM_INTERV_SOURCE_CODE UNIQUE (SOURCE_CODE, ANNEE_ID, STATUT_ID)		-- v2.0 + statut_id et source_code en 1er
);

CREATE INDEX IDX_UM_INTERV_DATE on OSE.UM_INTERVENANT(ANNEE_ID, DATE_HORODATAGE); --v1.7

CREATE SEQUENCE OSE.SEQ_ID_INTERV START WITH 1 INCREMENT BY 1 CACHE 10;

CREATE OR REPLACE TRIGGER  OSE.TRG_ID_UM_INTERV
BEFORE INSERT ON OSE.UM_INTERVENANT
FOR EACH ROW
BEGIN
  SELECT OSE.SEQ_ID_INTERV.NEXTVAL
  INTO   :new.id
  FROM   dual;
END;

/*------------- UM_ADRESSE_INTERVENANT ----------------------*/
CREATE TABLE OSE.UM_ADRESSE_INTERVENANT
(
  ID						NUMBER(9)	default  0,	
  INTERVENANT_ID			NUMBER(9),	
  TEL_DOMICILE				VARCHAR2(25  CHAR),
  MENTION_COMPLEMENTAIRE	VARCHAR2(50  CHAR),
  BATIMENT					VARCHAR2(60  CHAR),
  NO_VOIE					VARCHAR2(20  CHAR),
  NOM_VOIE					VARCHAR2(120  CHAR),
  LOCALITE					VARCHAR2(120  CHAR),
  CODE_POSTAL				VARCHAR2(15  CHAR),
  VILLE						VARCHAR2(120  CHAR),
  PAYS_CODE_INSEE			VARCHAR2(3  CHAR),
  PAYS_LIBELLE				VARCHAR2(50  CHAR),
  SOURCE_ID					NUMBER(9),
  SOURCE_CODE				VARCHAR2(100  CHAR),
  W_MAIL_PERSO				VARCHAR2(255  CHAR),
  NUMERO_COMPL_CODE			VARCHAR2(5 CHAR),			-- v2.0 OSE V15
  VOIRIE_CODE				VARCHAR2(5 CHAR) 		-- v2.0 OSE V15
);


/*===================================================================================
		TABLES DE TRAVAIL POUR OSE/PROSE
=================================================================================== */

/*------------- UM_TRANSFERT_INDIVIDU -----------------------*/
-- POUR DSIN TRACE JOURNALIERE DES ETAPES DE SYNCHRO
CREATE TABLE OSE.UM_TRANSFERT_INDIVIDU
(
  ID						NUMBER(9),
  TYPE_TRANSFERT			VARCHAR2(25  CHAR),
  D_HORODATAGE				DATE,
  NUDOSS					NUMBER(38),
  MATCLE					VARCHAR2(12  CHAR),
  UID_LDAP					VARCHAR2(12  CHAR),
  QUALIT					VARCHAR2(1  CHAR),
  NOMUSE					VARCHAR2(40  CHAR),
  PRENOM					VARCHAR2(30  CHAR),
  NOMPAT					VARCHAR2(40  CHAR),
  TEM_OSE_UPDATE			VARCHAR2(5  CHAR),
  TEM_OSE_INSERT			VARCHAR2(5  CHAR),
  TEM_PROSE_UPDATE			VARCHAR2(5  CHAR),
  TEM_PROSE_INSERT			VARCHAR2(5  CHAR),
  RECRUTEMENT				VARCHAR2(50  CHAR),
  TYPE_EMP 					VARCHAR2(5  CHAR),
  TEM_FONCTIONNAIRE			VARCHAR2(3  CHAR),
  DATE_DEPART_DEF			DATE,
  CAUSE_DEPART_DEF			VARCHAR2(45  CHAR),
  GROUPE_HIERARCHIQUE		VARCHAR2(2 CHAR),
  EMPLOYEUR              	VARCHAR2(255 CHAR),
  VILLE_SERVICE_RECTORAT   	VARCHAR2(255 CHAR),
  OREC_CODE_UO				VARCHAR2(10 CHAR),
  NB_H_SERVICE_RECTORAT 	NUMBER(8,2),
  OREC_LIB_CATEGORIE		VARCHAR2(32  CHAR),
  CHANGEMENT_STATUT			VARCHAR2(100  CHAR),
  OREC_TYPE_VAC				VARCHAR2(3	CHAR),
  ANNEE_ID 					NUMBER(9),		-- v1.7
  CONSTRAINT PK_ID_TRANSF_IND PRIMARY KEY (ID),
  CONSTRAINT UK_TRANSF_IND_MATCLE UNIQUE (ANNEE_ID,MATCLE)	-- v1.7
);


CREATE SEQUENCE OSE.SEQ_ID_TRANSF_IND START WITH 1;

CREATE OR REPLACE TRIGGER OSE.TRG_ID_TRANSF_IND
BEFORE INSERT ON OSE.UM_TRANSFERT_INDIVIDU
FOR EACH ROW
BEGIN
  SELECT OSE.SEQ_ID_TRANSF_IND.NEXTVAL
  INTO   :new.id
  FROM   dual;
END;

/*------------- UM_TRANSFERT_FORCE --------------------------*/
-- POUR DSIN POUR FORCER LA SYNCHRO MANUELLE SUR UM MATRICULE
CREATE TABLE OSE.UM_TRANSFERT_FORCE
(
  D_HORODATAGE				DATE,
  NUDOSS					NUMBER(38),
  MATCLE					VARCHAR2(12   CHAR),
  NOMUSE					VARCHAR2(40   CHAR),
  PRENOM					VARCHAR2(30   CHAR),
  RAISON_FORCAGE  			VARCHAR2(255  CHAR),
  D_VERIF_MANUELLE			DATE
);

CREATE INDEX IDX_UM_TRANSFERT_FORCE on UM_TRANSFERT_FORCE(NUDOSS,D_VERIF_MANUELLE); -- v1.7

/*------------- UM_SYNCHRO_A_VALIDER ------------------------*/
-- POUR DSIN POUR FORCER TRACE DES CHANGEMENTSDE STATUTS DETECTES VALIDES OU A VALIDER
CREATE TABLE OSE.UM_SYNCHRO_A_VALIDER
(
  ID						NUMBER(9),
  D_HORODATAGE				DATE,
  NUDOSS					NUMBER(38),
  MATCLE					VARCHAR2(12  CHAR),
  QUALIT					VARCHAR2(1   CHAR),
  NOMUSE					VARCHAR2(40  CHAR),
  PRENOM					VARCHAR2(30  CHAR),
  NOMPAT					VARCHAR2(40  CHAR),
  CHANGEMENT_STATUT			VARCHAR2(100 CHAR),
  TEM_VALIDATION			VARCHAR2(2	 CHAR),		-- v2.0 09/03/21 valeurs possibles : A/AI/N/O/I  A : Auto unique/AI : Auto insert/N : pas validé/O:validé et unique/I : validé et insert multiple
  D_VALIDATION				DATE,
  COMMENTAIRE				VARCHAR2(255 CHAR),
  D_TRANSFERT_FORCE			DATE,
  ANNEE_ID					NUMBER(9),				-- v1.7
  ACTU_STATUT_ID			NUMBER(9),				-- v2.0 09/03/21 stockage infos statut deja existant selon T_UM_ENREG_STATUT
  ACTU_CODE_STATUT			VARCHAR2(20 CHAR),
  ACTU_CODE_TYPE_INT		VARCHAR2(1  CHAR),
  ACTU_DATE_DEB_STATUT		DATE,
  ACTU_DATE_FIN_STATUT		DATE,
  ACTU_NB_H_MCE				NUMBER(8,2),
  NEW_STATUT_ID				NUMBER(9),				-- v2.0 09/03/21 stockage infos statut nouveau proposé selon T_UM_ENREG_STATUT
  NEW_CODE_STATUT			VARCHAR2(20 CHAR),
  NEW_CODE_TYPE_INT			VARCHAR2(1  CHAR),
  NEW_DATE_DEB_STATUT		DATE,
  NEW_DATE_FIN_STATUT		DATE,
  NEW_NB_H_MCE				NUMBER(8,2),
  PARAM_GESTION_STATUT		VARCHAR2(15 CHAR),
  CONSTRAINT PK_ID_SYNCHRO_A_VAL PRIMARY KEY (ID),
  CONSTRAINT UK_SYNCHRO_A_VAL UNIQUE (ANNEE_ID, MATCLE, CHANGEMENT_STATUT) -- v1.7
);

CREATE SEQUENCE OSE.SEQ_ID_SYNCHRO_A_VAL START WITH 1;

CREATE OR REPLACE TRIGGER OSE.TRG_ID_SYNCHRO_A_VAL
BEFORE INSERT ON OSE.UM_SYNCHRO_A_VALIDER
FOR EACH ROW
BEGIN
  SELECT OSE.SEQ_ID_SYNCHRO_A_VAL.NEXTVAL
  INTO   :new.id
  FROM   dual;
END;

/*===================================================================================
		TABLES NON SYNCHRO MAIS CREES POUR SIMULER PARAM OSE
=================================================================================== */	

/*------------- UM_STATUT_INTERVENANT -----------------------*/
CREATE TABLE OSE.UM_STATUT_INTERVENANT
(
  ID						NUMBER(9) default 0,
  CODE_STATUT				VARCHAR2(20  CHAR),
  LIBELLE					VARCHAR2(128  CHAR),
  SERVICE_STATUTAIRE		FLOAT,	
  FONCTION_E_C				NUMBER(1),
  TYPE_INTERVENANT_ID		NUMBER(9),	
  SOURCE_ID					NUMBER(9),
  SOURCE_CODE				VARCHAR2(100  CHAR),
  ORDRE						NUMBER(2),	
  NON_AUTORISE				NUMBER(1),	
  PEUT_SAISIR_SERVICE		NUMBER(1),	
  PEUT_CHOISIR_DANS_DOSSIER	NUMBER(1),	
  PEUT_SAISIR_DOSSIER		NUMBER(1),	
  PEUT_SAISIR_MOTIF_NON_PAIEMENT	NUMBER(1),
  PEUT_AVOIR_CONTRAT		NUMBER(1),	
  PEUT_SAISIR_REFERENTIEL	NUMBER(1),	
  PLAFOND_HC_REMU_FC		FLOAT,
  DEPASSEMENT_SERVICE_DU_SANS_HC	NUMBER(1),
  PEUT_CLOTURER_SAISIE		NUMBER(1),	
  PEUT_SAISIR_SERVICE_EXT	NUMBER(1),
  PROSE_LIB_STATUT			VARCHAR2(20  CHAR),
  CONSTRAINT PK_ID_ST_INTERV PRIMARY KEY (ID),
  CONSTRAINT UK_ST_INTERV_CODE_STATUT UNIQUE (CODE_STATUT)
);

-- ##A_PERSONNALISER_CHOIX_OSE## suivant les statuts à décliner / service statutaire / dans OSE droits dans OSE .... a voir avec votre DRH
-- Ces statuts devront également etre créés dans la vraie table OSE.STATUT_INTERVENANT (cf. B_SIHAM_INTERV_ConnecteursOse_v1.1.sql)
-- MYP le 14/09/2018 --- avec Perso_UM_Statut_Intervenant_v1.1.xlsx -- le 12/10/2020 modif libellé service partagé (sans FDE)
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	1,'ENS_CH_UM','Enseignant-chercheur',192,1,'ENS Ch'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	2,'ENS_1D','Enseignant premier degré',384,1,'ENS 1er'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	3,'ENS_2D','Enseignant second degré',384,1,'ENS 2nd'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	4,'ENS_HU','Enseignant-chercheur HU TITU',9999,1,'ENS HU'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	5,'DOC_MCE','Doctorant contractuel avec MCE',64,1,'DOC MCE'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	6,'ENS_ASS','Enseignant associé',192,1,'ENS Associé'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	7,'ENS_ASS_50','Enseignant associé mi-temps',96,1,'ENS Associé'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	8,'ENS_CTR','Enseignant contractuel',384,1,'ENS CTR'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	9,'ENS_CTR_50','Enseignant contractuel mi-temps',192,1,'ENS CTR'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	10,'ENS_CH_CTR','Enseignant-chercheur contractuel',192,1,'ENS CTR'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	11,'ENS_CH_CTR_50','Enseignant-chercheur contractuel mi-temps',96,1,'ENS CTR'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	12,'ATER_UM','ATER',192,1,'ATER'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	13,'ATER_50','ATER mi-temps',96,1,'ATER 50%'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	14,'ENS_HU_CTR','Enseignant-chercheur HU CTR',9999,1,'ENS HU CTR'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	15,'MLV','Maîtres de langues',192,1,'MLV'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	16,'LECT','Lecteur',200,1,'LECT'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	17,'INF_ORIEN_EDU','Personnel d''éducation et d''orientation ',384,1,'Inf/Orien/Edu'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	18,'SPART','Service partagé',192,1,'SPART'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	19,'MAD_1D','MAD premier degré',384,1,'MAD ou PFA'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	20,'MAD_2D','MAD second degré',384,1,'MAD ou PFA'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	21,'PFA','PFA',384,1,'MAD ou PFA'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	22,'CONV','Enseignant avec convention entrante',384,1,'CONV'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	23,'CEV_TIT_R','CEV TITU Rémunéré',0,2,'IE FP'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	24,'CEV_TIT_G','CEV TITU Titre gracieux',0,2,'IE FP'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	25,'CEV_NTIT_R','CEV NTITU Rémunéré',0,2,'IE Hors FP'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	26,'CEV_NTIT_G','CEV NTITU Titre gracieux',0,2,'IE Hors FP'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	27,'ATV_R','ATV Rémunéré',0,2,'ATV'	);
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	28,'ATV_G','ATV Titre gracieux',0,2,'ATV'	);
-- 18/09/2018
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	29,'ENS_MADE','Enseignant en mise à dispo entrante',192,1,'ENS Ch'	);
-- 13/12/2018 modif 11/01/2019
insert into OSE.UM_STATUT_INTERVENANT (ID, CODE_STATUT,LIBELLE, SERVICE_STATUTAIRE, TYPE_INTERVENANT_ID, PROSE_LIB_STATUT) VALUES (	30,'CONV_MCE','Doctorant contractuel hors UM avec MCE à l''UM (convention)',64,1,'DOC MCE CONV'	);

